<?php
/** 
 * Notes:
 *   Data text file is cleaned up using Regex replace on ^[^0-9]+ before this script is executed
 */
/** @see Bootstrap **/
require '../Bootstrap.php';

$file   = '../data/Morton_County_Addresses_with_Elevation 6-2-11 3pm_150k.txt';
$match  = array();
$handle = fopen($file, 'r');
$num_lines = 0;
$num_written = 0;
$num_matches = 0;
$errors = array();
// Name: Street Address: Elevation: Protection: Water Depth: 
// 0000 SANDY LN SE 1640.68 YES 0.000 
//$old_regex = '/^(?P<Street_Address>.*\s(ST|AV|PL|LP|LN|RD|DR))(?:\s)?(?P<Suffix>(?:(NE|NW|SE|SW|N|S|E|W)\s))?(?:\s)?(?P<Unit>(?:.*))?\s(?P<Elevation>\d+\.\d+)\s(?P<Levee>\w+)\s(?P<Water_Depth>\d+\.\d+)\s+$/i';

// Name: Street Address: Elevation: Protection: Water Depth: 
// 0000 SANDY LN SE 1640.68 YES 0.000 
$regex = '/^(?P<Street_Address>.*\s(?:ST|AV|PL|LP|LN|RD|DR)?[A-Za-z0-9 \-\(\)\#]+)\s(?P<Elevation>\d+\.\d+)\s(?P<Protection>\w+)\s(?P<Water_Depth>\d+\.\d+)\s+$/i';

while (false !== ($data = fgets($handle, 2048)))
{
  $num_lines++;
  $found = preg_match($regex, $data, $match);
  if (!$found) {
    echo "NO MATCH: $data ON LINE $num_lines<br/>";
    continue;
  }
  $num_matches++;

  /*
  echo $match['Street_Address'] . '<br/>';
  echo $match['Elevation'] . '<br/>';
  echo $match['Water_Depth'] . '<br/>';
  echo $match['Protection'] . '<br/>';
  echo '<br/>';
  */

  $elevation = new Flood_Model_Elevation(array(
    'countyId'      => 2, // Morton ID in County table
    'streetAddress' => $match['Street_Address'],
    'elevation'     => $match['Elevation'],
    'levee'         => $match['Protection'],
    'waterDepth'    => $match['Water_Depth']
  ));
  
  // Optional
  if (!empty($match['Suffix'])) $elevation->setSuffix($match['Suffix']);
  if (!empty($match['Unit']))   $elevation->setUnit($match['Unit']);

  try {
    $elevation->save();
  }
  catch (Exception $e)
  {
    $errors[] = "Line $num_lines: " . $e->getMessage();
    continue;
  }
  $num_written++;
}

if (!feof($handle)) echo "fgets(): an unexpected error occurred\n";
fclose($handle);

$summary = "Read $num_lines lines<br/>" .
           "Wrote $num_written records<br/>" .
           "Errors: " . count($errors) . "<br/>" .
           "===========================";
foreach ($errors as $error)
  $summary .= $error . "\n";

echo $summary;