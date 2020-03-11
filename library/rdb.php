<?php

//
// rdb.php
//
// Written by Mark D. Hamill
// mhamill@computer.org (or mdhamill@usgs.gov)
//
// This class is used to easily transform U.S. Geological Survey instantaneous RDB files into XML and JSON formats.
//
// RDB files are tab delimited files of data produced by various components of the U.S. Geological Survey and
// which is a principle output format for USGS National Water Information System for the Web located
// at http://waterdata.usgs.gov/nwis/.
//
// For the RDB specification see: http://pubs.usgs.gov/of/2003/ofr03123/6.4rdb_format.pdf 
//
// Note: as a practical matter, you need to create a cache directory to use this class. The cache directory is used to cache RDB files so they are
// not fetched repeatedly with the same information. You can change the location of the cache directory by editing the $cache_dir class variable
// below. By default there is expected to be a /cache/ directory in the same folder as this class. Make sure the folder has public write permissions.
// The class will attempt to create it if it does not exist, but it may not have the privilege to do so. Also note the default time to cache an RDB 
// file is one hour. USGS only rarely has sites that transmit more frequently than once an hour, so please consider carefully before reducing this 
// time as you are unlikely to get any new data and you will be unnecessarily consuming USGS server resources.
//
// This class is in the public domain since at least some portion of it was created on government time.
//
// Version		Release Date		Notes
// -------		------------		-----
//
//     0.1      9 April 2009		Initial version for comments
//     0.2      12 May 2009			Line 1000 changed to use intval. In one odd case the timestamp otherwise was converted into a float.
//     0.3      31 Aug 2009			rdb function avoids a double call if cached version of file must be refreshed. This happened through a copy
//									statement that was subsequently followed by an fopen statement.
//     0.4      20 Jul 2010			certain variables that hold arrays are initialized as empty arrays rather than nulls to resolve
//									PHP notices.

class rdb 
{
	// Class properties
	public $limit = NULL;			// If you only want the first X data rows returned, set this to a positive whole number, ex: $my_rdb->limit = 5;
	public $show_columns = array();	// An array with the columns you want outputted. The first column is 0. For example to show columns 3 and 4, 
									// 	$my_rdb->show_columns = array(2,3);	
	public $sort_columns = array();	// An array with the column sort sequence, ex: array(2,3) means sort by column 3 first, then by column 4 (0 is first column) 
	public $sort_order = array();	// An array with the column sort order sequence, ex: array(SORT_ASC, SORT_DESC) means sort by column 3 in ascending sequence, 
									// then sort column 4 in descending sequence. (0 is first column). Number of elements in array must be consistent with
									// $sort_columns. For more information, see http://www.php.net/sort. Please use the constants in the array, ex:
									//  $my_rdb->sort_order = array(SORT_ASC, SORT_DESC); 
	public $sort_type = array();	// An array with the column sort type sequence, ex: array(SORT_STRING, SORT_NUMBER) means sort by column 3 as a string, 
									// then sort column 4 as a number. (0 is first column). Number of elements in array must be consistent with $sort_columns.
									// For a full list of allowed values, see http://www.php.net/sort. Please use the constants in the array, ex:
									//  $my_rdb->sort_type = array(SORT_STRING, SORT_NUMBER); 
	public $valid = FALSE;			// Indicates if the RDB file is in a valid format. If the file is loaded successfully, this will become TRUE
	
	// Used by the class to store data. These are not directly accessible.
	private $cache_dir = './cache';	// Location of the cache directory. If it does not exist the class will attempt to create it if caching is desired
	private $cache_expir_days = 7;	// Number of days to leave a cache file in the cache directory before deleting
	private $cache_time = 900;		// Number of seconds to use cached RDB file before refetching
	private $columns = array();		// Two-dimensional array containing the column information
	private $comments = array();	// Two-dimensional array containing the comment lines
	private $data = array();		// Two-dimensional array containing the actual data 
	private $dd_params = array();	// Contains data descriptor and parameter information parsed out of the RDB comments
	private $error_info = NULL;		// Contains error information if $valid == FALSE.
	private $fields = array();		// Two-dimensional array containing the field information that appears in statewide tables
	private $formats = array();		// Two-dimensional array containing the format information
	private $sites = array();		// Contains site information parsed out of the RDB comments
	private $user_agent = 'USGS RDB Class for PHP/0.4';	// Please do not change this

  /**
   * Set row field value
   *
   * @param   string  $name   Name of property
   * @param   mixed   $value  Value for the property
   * @return  void
   */
	public function __set($name, $value)
  {
    $method = 'set' . $name;
    $this->$method($value);
  }

  /**
   * Retrieve row field value
   *
   * @param   string  $name	  Property
   * @return  mixed           The corresponding property value
   */
	public function __get($name)
  {
    $method = 'get' . $name;
    return $this->$method();
  }

  /**
   * Set properties
   *
   * @param   array   $options  An associative array of property name and value pairs
   * @return  rdb
   */
	public function setOptions(array $options)
  {
    $methods = get_class_methods($this);

    foreach ($options as $key => $value)
    {
      $method = 'set' . ucfirst($key);

      if (in_array($method, $methods))
        $this->$method($value);
    }
    return $this;
  }
  
  /**
   * Set location of the cache directory
   *
   * @param   string  $value 
   * @return  rdb
   */
  public function setCacheDir($value)
  {
    $this->cache_dir = $value;
    return $this;
  }
  
  /**
   * Retrieve location of the cache directory
   *
   * @return  string
   */
  public function getCacheDir()
  {
    return $this->cache_dir;
  }
  
  /**
   * Set number of days to leave a cache file in the cache directory before deleting
   *
   * @param   integer   $value 
   * @return  rdb
   */
  public function setCacheExpirationDays($value)
  {
    $this->cache_expir_days = $value;
    return $this;
  }
  
  /**
   * Retrieve number of days to leave a cache file in the cache directory before deleting
   *
   * @return  integer
   */
  public function getCacheExpirationDays()
  {
    return $this->cache_expir_days;
  }
  
  /**
   * Set number of seconds to use cached RDB file before refetching
   *
   * @param   integer   $value 
   * @return  rdb
   */
  public function setCacheLifeTime($value)
  {
    $this->cache_time = $value;
    return $this;
  }
  
  /**
   * Retrieve number of seconds to use cached RDB file before refetching
   *
   * @return  integer
   */
  public function getCacheLifeTime()
  {
    return $this->cache_time;
  }
  
  /**
	 * Constructor
	 *
	 * @param   array   $options
	 * @return  void
	 */
  public function __construct(array $options = null)
  {
    if (is_array($options))
      $this->setOptions($options);
  }
  
	// Initialization function	
	public function load($uri, $use_cache=TRUE)
	{
		// Parameters:
		//		$uri = The URI to the RDB instantaneous data file, typically a URL on http://waterdata.usgs.gov, but it could also be stored locally
		// 		$use_cache = Set to false to bypass the caching feature. Not recommended and if used extensively may get you blacklisted by USGS
		
		// Local variables
		$begin_loading_sites = false;
		$begin_loading_dds = false;
		$begin_loading_fields = false;
		$column_line_found = false;	// Indicates whether the line that contains column names was found in the RDB file
		$format_line_found = false; // Indicates whether the format line was found in the RDB file
		$lines_read = 0;
		$sites_read = 0;
		$fields_read = -1;			// Set to -1 so we can skip over a blank comment before actual field data is loaded
		
		ini_set('user_agent', $this->user_agent);	// This identified this class and tracks usage of this class on USGS web servers

		// Clear old cache files
		$expire_time = time() - ($this->cache_expir_days * 24 * 60 * 60);
		if ($handle = opendir($this->cache_dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..")
				{
					$file_path = $this->cache_dir . DIRECTORY_SEPARATOR . $file;
					$file_access_time = filemtime($file_path);
					if ($file_access_time < $expire_time)
					{
						unlink($file_path);
					}
				}
			}
			closedir($handle);
		}
		
		if ($use_cache)
		{
		
			// Try to create cache directory if it does not exist. PHP may not be compiled to have the requisite permissions.
			if (!file_exists($this->cache_dir))
			{
				$status = @mkdir($this->cache_dir, 0666);
				
				if (!$status)
				{
					echo "Cache couldn't create a cache directory '" . $this->cache_dir . "'. Please create manually with full write permissions (0666).";
					exit;
				}
			}
			
			// Is RDB file already in the cache?
			$md5_uri = $this->cache_dir . DIRECTORY_SEPARATOR . md5($uri);
			$cache_file_mod_date = filemtime($md5_uri); // Returns FALSE if the file is not in the cache
			
			// Use cache only if the file cache time has not expired
			if ($cache_file_mod_date)	// Cache file exists
			{
				$update_cache = FALSE;
				if ((time() - $this->cache_time) > $cache_file_mod_date)
				{
					$update_cache = TRUE;
				}
			}
			else
			{
				$update_cache = TRUE;
			}
			
			// Copy remote file to cache if it does not exist or if it is too old
			if ($update_cache)
			{
				// Place RDB file in cache
				copy($uri, $md5_uri);
			}
			$uri = $md5_uri;	// Okay to use cache, so point to local cache file rather than URL
		
		}
		
		$handle = fopen($uri, "r"); // Try to fetch the RDB file, which is typically a URI

		while (($line = fgetcsv($handle, NULL, "\t")) !== FALSE) // Read until end of file
		{
			// Determine if the line is data, a column name line, a column format line or a data line
			if (substr($line[0],0,1) == '#')
			{

				// This is a comment line
				$this->comments[] = $line;
				
				// Site information is typically embedded in the comments. Parse for it and store it. It is not present in all RDB files.
				if ($line[0] == '# -----------------------------------------------------------------------------------') 
				{
					$begin_loading_sites = false;
					$loading_sites_done = true;
				}
				
				if ($begin_loading_sites)
				{
					$site_info = explode(' ', $line[0],7);
					$this->sites[] = array('agency_cd' => $site_info[4], 'site_no' => $site_info[5], 'site_desc' => $site_info[6]);
				}
				
				if ($line[0] == '# Data for the following site(s) are contained in this file')
				{
					$begin_loading_sites = true;
				}
				
				// DD/Parameter information may be embedded in the comments. Parse for it and store it.
				if ($line[0] == '#' && $begin_loading_dds)
				{
					$begin_loading_dds = false;
				}
				
				if ($begin_loading_dds)
				{
					$dd_param_info = explode(' ', $line[0],7);
					$param_desc = explode(' ', $dd_param_info[6], 7);
					$this->dd_params[] = array('dd' => $dd_param_info[4], 'parameter' => $param_desc[1], 'parameter_desc' => $param_desc[6]);
				}
				
				if ($line[0] == '#    DD parameter   Description')
				{
					$begin_loading_dds = true;
				}
				
				// Field information may be embedded in the comments. Parse for it and store it.
				if (($line[0] == '#') && ($fields_read > 0) && $begin_loading_fields)
				{
					$begin_loading_fields = false;
				}
				
				if ($begin_loading_fields)
				{
					$fields_read++;
					if ($fields_read > 0)
					{
						$field_info = explode(' ', $line[0], 5);
						if ($field_info[3] != '')
						{
							if ($field_info[2] == 'result' && $field_info[3] == 'va')
							{
								// solves a USGS bug for "result va" which should be "result_va"
								$this->fields[$field_info[2] . '_' . $field_info[3]] = trim($field_info[4]);
							}
							else
							{
								$this->fields[$field_info[2]] = $field_info[3] . ' ' . trim($field_info[4]);
							}
						}
						else
						{
							$this->fields[$field_info[2]] = trim($field_info[4]);
						}
					}
				}
				
				if ($line[0] == '# This information includes the following fields:')
				{
					$begin_loading_fields = true;
				}
				
			}
			
			else if (!$column_line_found)
			{
			
				// According to the RDB specification, the first line following the comments contains a list of column names
				$column_line_found = TRUE;
				$this->columns[] = $line;
				
			}
			
			else if (!$format_line_found)
			{
				// After the column line, the next line is the format line, which indicates the type of data in each column and the maximum number of characters
				// the column can contain.
				$format_line_found = TRUE;
				$this->formats[] = $line;
			}
			
			else
			{
				// After comments, the column line and the format line, all remaining lines should be record lines
				// Each line is a record and fields are separated by tabs
				$this->data[] = $line;
			}
			
			$lines_read++;
		}
		
		// Note: If the first data line contains the HTML <head> tag then we know an error page is being returned, probably due to a mangled URL
		if (!(($this->data[0][0] == '<head>') || ($lines_read == 0) || (!$column_line_found) || (!$format_line_found)))
		{
			// Set the RDB file flag as valid only if it passes all the consistency checks expected 
			$this->valid = TRUE;
		}
		else
		{
			$this->valid = FALSE;
			if ($this->data[0][0] == '<head>')
			{
				$this->error_info = 'RDB file ' . $uri . ' is invalid, probably because the site does not exist.';
			}
			else if ($lines_read == 0)
			{
				$this->error_info = 'RDB file ' . $uri . ' is invalid because the file is empty.';
			}
			else if (!$column_line_found)
			{
				$this->error_info = 'RDB file ' . $uri . ' is invalid because no column line was found, and it is required.';
			}
			else if (!$format_line_found)
			{
				$this->error_info = 'RDB file ' . $uri . ' is invalid because no format line was found, and it is required.';
			}
			else
			{
				$this->error_info = 'RDB file ' . $uri . ' does not exist or is formatted incorrectly';
			}
		}
	}
	
	public function sort()
	{
	
		// This function sorts the RDB data. It assumes that the object's sort_order, sort_columns and sort_type properties have been 
		// properly set. This class should return FALSE if a bad sort_order, sort_columns and sort_type was set.
		
		if (sizeof($this->sort_columns) == 0)
		{	
			return TRUE;	// Nothing to sort
		}
		
		// Set error for inconsistent sort information
		if (sizeof($this->sort_columns) != sizeof($this->sort_order))
		{
			return FALSE;
		}
	
		if (sizeof($this->sort_columns) != sizeof($this->sort_type))
		{
			return FALSE;
		}
	
		// Transpose rows to columns for sorting
		$new_data = array();
		for ($i=0; $i < sizeof($this->data); $i++)
		{
			for ($j=0; $j < sizeof($this->data[1]); $j++)
			{
				$new_data[$j][$i] = $this->data[$i][$j];
			}
		}
		
		// Dynamically create the PHP sort command
		$sort_command = 'array_multisort(';
		foreach ($this->sort_columns as $key => $value)
		{
			switch ($this->sort_order[$key])
			{
				case SORT_ASC:
					$sort_order = 'SORT_ASC';
					break;
				case SORT_DESC:
					$sort_order = 'SORT_DESC';
					break;
				default:
					return FALSE;
			}
			switch ($this->sort_type[$key])
			{
				case SORT_REGULAR:
					$sort_type = 'SORT_REGULAR';
					break;
				case SORT_NUMERIC:
					$sort_type = 'SORT_NUMERIC';
					break;
				case SORT_STRING:
					$sort_type = 'SORT_STRING';
					break;
				case SORT_LOCALE_STRING:
					$sort_type = 'SORT_LOCALE_STRING';
					break;
				default:
					return FALSE;
			}
			
			$sort_command .= '$new_data[' . $value . '], ' . $sort_order . ', ' . $sort_type . ',';
		}
		
		// For the sort I chose, all arrays must be sorted to keep the data consistent. So if a column sort is not specified I assume
		// the other data columns are sorted in ASCENDING sequence. As a practical matter this rarely matters as the primary sorts
		// will remove uniqueness from the other columns.
		foreach ($new_data as $key => $value)
		{
			if (!in_array($key, $this->sort_columns))
			{
				$sort_command .= ' $new_data[' . $key . '], SORT_ASC, SORT_REGULAR,';
			}
		}
		
		// Complete the sort command by removing the trailing command and adding the closing parenthesis and semicolon.
		$sort_command = substr($sort_command, 0, strlen($sort_command) - 1) . ');';
		
		eval($sort_command);	// Execute the sort
							
		// Transpose columns back into rows now that the data is properly sorted 
		unset($this->data);
		for ($i=0; $i < sizeof($new_data); $i++)
		{
			for ($j=0; $j < sizeof($new_data[1]); $j++)
			{
				$this->data[$j][$i] = $new_data[$i][$j];
			}
		}
		
		return TRUE;
	}
	
	public function outputXML ($suppress_headers=FALSE, $show_sites=TRUE, $iso_dates=TRUE, $show_dds=TRUE, $column_formats=TRUE, $compact=TRUE)
	{

		// Parameters:
		//		$suppress_headers == TRUE, then XML headers will be suppressed. Use if you intend to capture output to a variable
		//		$show_sites == TRUE, site description information will appear in the XML tree if it is provided in the RDB File. This can be helpful 
		//		if site description is desired.
		//		$iso_dates == TRUE then any USGS NWIS date/time strings in a YYYY-MM-DD HH:MM format are converted into ISO-8601 format YYYY-MM-DDTHH:MM
		//		$show_dds == TRUE, data descriptors and parameters information will appear in the XML tree if they were provided in the RDB file.
		//		$column_formats == TRUE, column format information will appear in the XML tree.
		// 		$compact == TRUE, newlines and tabs are removed from output except from comment lines (needed for visibility of legal information)

		// Can this client accept the MIME application/xml? If so use that.
		if (!$suppress_headers)
		{
			$charset = "utf-8";
			$mime    = (stristr($_SERVER["HTTP_ACCEPT"],"application/xml")) ? "application/xml" : "text/xml";
			header("content-type:$mime;charset=$charset");
		}
 		
		$nl = ($compact) ? "\n" : NULL;
		$tab = ($compact) ? "\t" : NULL; 
		$records_written = 0;
		
		$xml = "<?xml version=\"1.0\" ?>\n";
		
		// Publish RDB comments as XML comments
		$xml .= '<!--' . $nl . '<![CDATA[';
		foreach ($this->comments as $comment)
		{
			foreach ($comment as $element)
			{
				$xml .= str_replace('--','==',$element) . "\n"; // -- does not validate inside a CDATA section, so replace
			}
		} 
		$xml .= "]]>-->" . $nl . $nl;
		
		// Publish the description and data
		$xml .= "<recordset>" . $nl;
		
		// Publish error information
		$xml .= '<error_info>';
		$xml .= $tab . $this->format_tag('error', !$this->valid) . $nl;
		$xml .= $tab . $this->format_tag('error_explanation', $this->error_info) . $nl;
		$xml .= '</error_info>' . $nl;
		
		if ($this->valid)
		{
		
			// Publish column information
			if ($column_formats)
			{
				if (sizeof($this->formats) > 0)
				{
					$xml .= "<columns>" . $nl;
					foreach ($this->formats as $key => $value)
					{
						foreach ($value as $key2 => $value2)
						{
							$tag_name = (is_numeric(substr($this->columns[0][$key2],0,1))) ? 'field_' . $this->columns[0][$key2] : $this->columns[0][$key2];
							$xml .= $tab . '<' . $tag_name . '>' . $nl;
							// If column description is available, print it
							if (array_key_exists($this->columns[0][$key2], $this->fields))
							{
								$xml .= $tab . $tab . '<description>' . $this->fields[$this->columns[0][$key2]] . '</description>' . $nl;
							}
							// Print the column size
							$tag = $this->format_tag('size', $value2);
							$xml .= $tab . $tab . $tag . $nl;
							$xml .= $tab . '</' . $tag_name . '>' . $nl;
						}
					} 
					$xml .= "</columns>" . $nl;
				}
				else
				{
					$xml .= "<columns />" . $nl;
				}
			}
			
			// Publish site information
			if ($show_sites)
			{
				if (sizeof($this->sites) > 0)
				{
					$xml .= "<sites>" . $nl;
					foreach ($this->sites as $key => $value)
					{
						$xml .= $tab . "<site>" . $nl;
						foreach ($value as $key2 => $value2)
						{
							$tag = $this->format_tag($key2, $value2);
							$xml .= $tab . $tab . $tag . $nl;
						}
						$xml .= $tab . "</site>" . $nl;
					}
					$xml .= "</sites>" . $nl;
				}
				else
				{
					$xml .= "<sites />" . $nl;
				}
			}
			
			// Publish DD information
			if ($show_dds)
			{
				if (sizeof($this->dd_params) > 0)
				{
					$xml .= "<dd_parameters>" . $nl;
					foreach ($this->dd_params as $key => $value)
					{
						$xml .= $tab . "<dd_parameter>" . $nl;
						foreach ($value as $key2 => $value2)
						{
							$tag = $this->format_tag($key2, $value2);
							$xml .= $tab . $tab . $tag . $nl;
						}
						$xml .= $tab . "</dd_parameter>" . $nl;
					}
					$xml .= "</dd_parameters>" . $nl;
				}
				else
				{
					$xml .= "<dd_parameters />" . $nl;
				}
			}
			
			// Now publish the data
			if (sizeof($this->data) > 0)
			{
				$xml .= "<records>" . $nl;
				foreach ($this->data as $key => $value)
				{
					$xml .= $tab. "<record>" . $nl;
					foreach ($value as $key2 => $value2)
					{
						// Check record value to see if it is a USGS NWIS datatime stamp and convert if desired
						$value2 = ($iso_dates) ? $this->is_nwis_datetime($value2, TRUE) : $value2;
	
						if (in_array($key2, $this->show_columns) || sizeof($this->show_columns) == 0)	// Restrict columns in output, if so requested
						{
							$tag = $this->format_tag($this->columns[0][$key2], $value2);
							$xml .= $tab . $tab . $tag . $nl;
						}
					}
					$xml .= $tab . "</record>" . $nl;
					$records_written++;
					if ($this->limit !== NULL && $records_written >= $this->limit)	// Stop if maximum number of records have been output
					{
						break;
					}
				}
				$xml .= "</records>" . $nl;
			}
			else
			{
				$xml .= "<records />" . $nl;
			}
		
		}
		
		$xml .= "</recordset>" . $nl;
		
		return $xml;
	} 
	
	public function outputJSON ($js_timestamps=TRUE, $use_arrays=TRUE, $suppress_headers=FALSE, $pretty=FALSE, $show_sites=TRUE, $show_dds=TRUE, $column_formats=TRUE)
	{

		// Parameters:
		//		$js_timestamps == TRUE, if the value appears to be a NWIS date in the format YYYY-MM-DD HH:SS, the date/time
		//			will be converted into a Javascript timestamp, which is a UNIX timestamp expressed as milliseconds. This eases plotting
		//			timeseries values in packages like flot.
		//		$use_arrays == TRUE, if this is true, records are written as arrays instead of object. This facilitates plotting data.
		//		$suppress_headers == TRUE, if true headers will not be output. It is assumed instead the JSON will be read by the calling
		//			program into a variable for further processing.
		//		$pretty == TRUE, then tabs and newlines will be inserted so that it shows on the screen in a logically indented manner.
		//		$show_sites == TRUE, site description information will appear in the structure if it exists in the RDB file. This can be helpful if 
		//		site description is desired.
		//		$show_dds == TRUE, data descriptors and parameters information will appear in the structure if they were provided in the RDB.
		//		$column_formats == TRUE, column format information will appear in the structure.
		//		$suppress_headers == TRUE, if true headers will not be output. It is assumed instead the JSON will be read by the calling
		//			program into a variable for further processing.

		$records_written = 0;
		if ($pretty)
		{
			$nl = "\n";
			$tab = "\t";
		}
		else
		{
			$nl = NULL;
			$tab = NULL;
		}
				
		// Start outputting JSON. First right the correct header based on whether the client can natively consume application/json
		if (!$suppress_headers)
		{
			$xhr = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
			header('Content-Type: ' . ($xhr ? 'application/json' : 'text/plain'));
		}
		
		// Publish error information
		$explanation = ($this->error_info === NULL) ? 'null' : '"' . $this->error_info . '"';
		$json = "{" . $nl;
		$json .= $tab . "\"error_info\" : " . $nl;
		$json .= $tab . $tab . '{' . $nl;
		$json .= $tab . $tab . "\"error\" : " . intval(!$this->valid) . ', ' . $nl;
		$json .= $tab . $tab . "\"error_explanation\" : " . $explanation . $nl;
		
		if (!$this->valid)
		{
			$json .= $tab . $tab . '}' . $nl;
			$json .= '}' . $nl;
		}
		else
		{
			$json .= $tab . $tab . '},' . $nl;
		
			// Write the comments in JSON format
			$json .= $tab . "\"comments\" : [" . $nl;
			
			// Need need to know in advance when we will print the last row of $this->comments
			end($this->comments);
			$last_row_key = key($this->comments);
			
			// Write out each comment in JSON format
			foreach ($this->comments as $key => $comment)
			{
				foreach ($comment as $element)
				{
					if ($key != $last_row_key)
					{
						$json .= $tab . $tab . "\"" . $element . "\"," . $nl;
					}
					else
					{
						// Last row much not have a concluding comma
						$json .= $tab . $tab . "\"" . $element . "\"" . $nl;
					}
				}
			} 
			
			// Make it look pretty
			$json .= $tab . "]," . $nl;
			
			// Write the column formats	so this information can be parsed if needed
			if ($column_formats)
			{
			
				$json .= $tab . "\"columns\" : " . $nl;
				$json .= $tab . $tab . "{" . $nl;
				
				// Need need to know in advance when we will print the last row of $this->formats
				end($this->formats[0]);
				$last_row_key = key($this->formats[0]);
				
				foreach ($this->formats as $key => $format)
				{
					foreach ($format as $key2 => $element)
					{
						$json .= $tab . $tab . "\"" . $this->columns[0][$key2] . "\" :" . $nl;
						$json .= $tab . $tab . $tab . "{" . $nl;
	
						if (array_key_exists($this->columns[0][$key2], $this->fields))
						{
							$json .= $tab . $tab . $tab . "\"description\" : \"" . $this->fields[$this->columns[0][$key2]] . "\"," . $nl;
							$json .= $tab . $tab . $tab . "\"size\" : \"" . $element . "\"" . $nl;
						}
						else
						{
							$json .= $tab . $tab . $tab . "\"size\" : \"" . $element . "\"" . $nl;
						}
	
						if ($key2 != $last_row_key)
						{
							$json .= $tab . $tab . $tab . "}," . $nl;
						}
						else
						{
							$json .= $tab . $tab . $tab . "}" . $nl;
						}
					}
				}
				
				// Make it look pretty
				$json .= $tab . $tab . "}," . $nl;
				
			} 
			
			if ($show_sites)
			{
			
				// Need need to know in advance when we will print the last row of $this->sites
				end($this->sites);
				$last_row_key = key($this->sites);
				
				// Write the site information so this information can be parsed if needed
				$json .= $tab . "\"sites\" : [" . $nl;
				$json .= $tab . $tab . "{" . $nl;
				
				foreach ($this->sites as $site_key => $site)
				{
					$json .= $tab . $tab . "\"site\" : " . $nl;
					$json .= $tab . $tab . $tab . "{" . $nl;
					foreach ($site as $key => $value)
					{
						if ($key != 'site_desc')
						{
							$json .= $tab . $tab . $tab . "\"" . $key . "\" : \"" . $value . "\"," . $nl;
						}
						else
						{
							// Last row much not have a concluding comma
							$json .= $tab . $tab . $tab . "\"" . $key . "\" : \"" . $value . "\"" . $nl;
						}
					}
					if ($site_key !== $last_row_key)
					{
						$json .= $tab . $tab . $tab . "}," . $nl;
					}
					else
					{
						$json .= $tab . $tab . $tab . "}" . $nl;
					}
				} 
				
				// Make it look pretty
				$json .= $tab . $tab . "}" . $nl;
				$json .= $tab . "]," . $nl;
				
			}
			
			if ($show_dds)
			{
				
				// Need need to know in advance when we will print the last row of $this->dd_params
				end($this->dd_params);
				$last_row_key = key($this->dd_params);
				
				// Write the site information so this information can be parsed if needed
				$json .= $tab . "\"dd_parameters\" : [" . $nl;
				
				foreach ($this->dd_params as $dd_param_key => $dd_param)
				{
					$json .= $tab . $tab . "{" . $nl;
					foreach ($dd_param as $key => $value)
					{
						if ($key != 'parameter_desc')
						{
							$json .= $tab . $tab . $tab . "\"" . $key . "\" : \"" . $value . "\"," . $nl;
						}
						else
						{
							// Last row much not have a concluding comma
							$json .= $tab . $tab . $tab . "\"" . $key . "\" : \"" . $value . "\"" . $nl;
						}
					}
					if ($dd_param_key !== $last_row_key)
					{
						$json .= $tab . $tab . "}," . $nl;
					}
					else
					{
						$json .= $tab . $tab . "}" . $nl;
					}
				} 
				
				// Make it look pretty
				$json .= $tab . "]," . $nl;
			
			}
				
			// Need to know in advance when we are at the last element of $this->data[0] that will be output
			if (sizeof($this->show_columns) > 0)
			{
				// The maximum value in the array is the last column key needed
				$last_column_key = max($this->show_columns);
			}
			else
			{
				end($this->data[0]);
				$last_column_key = key($this->data[0]);
			}
			
			// Need also to know in advance when we will print the last row of $this->data
			end($this->data);
			$last_row_key = key($this->data);
			if ($this->limit > 0)
			{
				$last_row_key = min($last_row_key, $this->limit - 1); // Allows limit to be factored in
			}
			
			// Create the JSON data object. This is where the important data resides
			$json .= $tab . "\"records\" : [" . $nl;
			
			// Publish the actual data in JSON format
			
			foreach ($this->data as $key => $value)
			{
				if ($use_arrays)
				{
					$json .= $tab . $tab . "[" . $nl;
				}
				else
				{
					$json .= $tab . $tab . "{" . $nl;
				}
				foreach ($value as $key2 => $value2)
				{
					if (in_array($key2, $this->show_columns) || sizeof($this->show_columns) == 0)	// Restrict columns in output, if so requested
					{
					
						// Check record value to see if it is a USGS NWIS datatime stamp and convert if desired
						$value2 = ($js_timestamps) ? $this->is_nwis_datetime($value2, FALSE) : $value2;
	
						// If the value to be output is a string, it's important to escape certain characters.
						$is_numeric = is_numeric($value2);
						if ($is_numeric && substr($value2,0,1) == '0')
						{
							$is_numeric = false;
						}
						$quote_char = ($is_numeric) ? NULL : '"';
						
						$column_value = (strlen($value2) == 0) ? 'null' : $value2;
						if ($column_value == 'null')
						{
							$quote_char = NULL;
						}
						
						// If this is the last data row to be printed, do not delimit end of record with a comma
						$comma = ($key2 == $last_column_key) ? NULL : ',';
						if ($use_arrays)
						{
							$json .= $tab . $tab . $tab . $quote_char . $column_value . $quote_char . $comma . $nl;
						}
						else
						{
							$json .= $tab . $tab . $tab . "\"" . $this->columns[0][$key2] . "\" : " . $quote_char . $column_value . $quote_char . $comma . " " . $nl;
						}
						
					}
				}
				if ($key != $last_row_key)
				{
					if ($use_arrays)
					{
						$json .= $tab . $tab . "]," . $nl;
					}
					else
					{
						$json .= $tab . $tab . "}," . $nl;
					}
				}
				else
				{
					// The last row of data must not end with a comma
					if ($use_arrays)
					{
						$json .= $tab . $tab . "]" . $nl;
					}
					else
					{
						$json .= $tab . $tab . "}" . $nl;
					}
				}
	
				$records_written++;
				if ($this->limit !== NULL && $records_written >= $this->limit)	// Stop if maximum number of records have been output
				{
					break;
				}
				
			}
			// Close JSON syntax
			$json .= $tab . "]" . $nl;
			$json .= "}";
		
		}
		
		return $json;
			
	} 
	
	private function format_tag ($name, $value)
	{

		// XML tag names may not start with a number or punctuation character. If it does we will assume this is a parameter code and prepend tag name with "field_"
		if (!strstr('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', substr($name,0,1)))
		{
			$name = 'field_' . $name;
		}
		
		// For empty tags, create an empty tag shortcut
		if (trim($value) == '')
		{
			return '<' . $name . ' />';
		}
		
		// If there are characters that are part of the XML syntax in $data, encapsulate $data in a CDATA section
		if ((strpos($value, '<')) || (strpos($value, '>')) || (strpos($value, '&')) || (strpos($value, '"')))
		{
			return '<' . $name . '><![CDATA[' . $value . ']]></' . $name . '>';
		}
		else
		{
			return '<' . $name . '>' . $value . '</' . $name . '>';
		}
		
	}
	
	private function is_valid_url($url)
	{
		if (preg_match("/^(http(s?):\/\/|ftp:\/\/{1})((\w+\.){1,})\w{2,}$/i", $url))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	private function is_nwis_datetime ($string, $iso=TRUE)
	
	{
	
		// This function examines a string to see if it is in the USGS NWIS timestamp format of YYYY-MM-DD HH:MM. If it is it converts it into
		// a Javascript timestamp, which makes plotting timeseries much simpler.
		
		// Parameters:
		//	$string = value to check and turn into valid time value if a USGS NWIS timestamp
		//	$iso = if TRUE yields ISO-8601 dates, if FALSE yields Javascript timestamps.
	
		// Example of valid NWIS date/time: 2009-02-12 13:45	
		$year = substr($string,0,4); 	// 2009
		$d1 = substr($string,4,1);		// -
		$month = substr($string,5,2);	// 02
		$d2 = substr($string,7,1);		// -
		$days = substr($string,8,2);	// 12
		$d3 = substr($string,10,1);		// space
		$hr = substr($string,11,2);		// 13
		$d4 = substr($string,13,1);		// :
		$min = substr($string,14,2);	// 45
		
		static $server_timezone_calculated = false;
		static $time_offset;
		
		if (!$server_timezone_calculated)
		{
			// mktime seems to convert time to server time from an assumed UTC. Need to offset timestamps by this difference to render site local time.
			$server_timezone = floatval(date('O')/100);
			$time_offset = $server_timezone * 60 * 60;
			$server_timezone_calculated = true;
		}
		
		if (($d1 !== '-') || ($d2 !== '-') || ($d3 !== ' ') || ($d4 !== ':'))
		{
			return $string;
		}
		
		if ((!is_numeric($year)) || (!is_numeric($month)) || (!is_numeric($days)) || (!is_numeric($hr)) || (!is_numeric($min)))
		{
			return $string;
		}
		
		$month = intval($month);
		if (($month < 1) || ($month > 12))
		{
			return $string;
		}
			
		$days = intval($days);
		if (($days < 1) || ($days > 31))
		{
			return $string;
		}
	
		$hr = intval($hr);
		if (($hr < 0) || ($hr > 24))
		{
			return $string;
		}

		$min = intval($min);
		if (($min < 0) || ($min > 60))
		{
			return $string;
		}
		
		if ($iso)
		{
			// Render an ISO-8601 date
			return substr($string,0,10) . 'T' . substr($string,11);
		}
		else
		{
			// If it passes all these tests, it is easy to convert into a UNIX timestamp
			$timestamp = mktime($hr, $min, 0, $month, $days, $year);
			// Next line fix by MDH, 5/12/2009 to use intval. In some cases it was rendering exponential notation.
			return (intval($timestamp) + intval($time_offset)) . '000'; // Better to append three zeros than multiple by 1000, to avoid automatic PHP exponentation of value
		}
	}
}