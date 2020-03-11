<?php
abstract class Flood_Model_Abstract
{
  /**
	 * Model to DbTable mapper
	 * @var Default_Flood_Model_AbstractMapper
	 */
	protected $_mapper;

  /**
	 * Constructor
	 *
	 * @param array $options
	 * @return void
	 */
	public function __construct(array $options = null)
  {
    if (is_array($options))
      $this->setOptions($options);
  }

  /**
   * Set row field value
   *
   * @param string $name The column key
   * @param mixed $value The value for the property
   * @return void
   */
	public function __set($name, $value)
  {
    $method = 'set' . $name;

    if (('mapper' == $name) || !method_exists($this, $method))
      throw new Exception('Invalid property');

    $this->$method($value);
  }

  /**
   * Retrieve row field value
   *
   * @param string $name	The user-specified column name
   * @return mixed The corresponding column value
   */
	public function __get($name)
  {
    $method = 'get' . $name;

    if (('mapper' == $name) || !method_exists($this, $method))
      throw new Exception('Invalid property');

    return $this->$method();
  }

  /**
   * Set row field value
   *
   * @param array $options An associative array of column key and
   *                       column value pairs
   * @return Default_Flood_Model_Abstract
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
   * Sets whether or not to return a Zend_Pagination object
   * for fetch operations
   *
   * @param boolean $enable
   *
   * @return Default_Flood_Model_Abstract
   */
  public function setUsePagination($enable=true)
  {
    $this->getMapper()->usePagination($enable);
    return $this;
  }

  /**
   * Set the DbTable mapper
   *
   * @param object $mapper A DbTable mapper class instance
   * @return Default_Flood_Model_Abstract
   */
  public function setMapper($mapper)
  {
    $this->_mapper = $mapper;
    return $this;
  }

  /**
   * Get the DbTable mapper
   *
   * @return Default_Flood_Model_AbstractMapper
   */
  abstract public function getMapper();

  /**
   * Save the entry
   *
   * @return void
   */
  public function save()
  {
    $this->getMapper()->save($this);
  }

  /**
   * Deletes the specified row id
   *
   * @param integer $id The row id
   * @return integer The number of rows deleted.
   */
	public function delete($id)
	{
		return $this->getMapper()->delete($id);
	}

  /**
   * Finds the specified row id
   *
   * @param integer $id The row id
   * @return Default_Flood_Model_Abstract
   */
  public function find($id)
  {
    return (null === $this->getMapper()->find($id, $this) ? null : $this);
  }

  /**
   * Fetches all rows
   *
   * @param   boolean $asRowSet Return the result set as an array of Zend_Db_Table_Rowset or Model_Abstract
   * @param   array   $where    Optional array in format of (condition, value)
   * @param   integer $limit    Number of results to limit
   * @return 	Model_Abstract
   */
  public function fetchAll($asRowSet=true, $where=null, $limit=null)
  {
    return $this->getMapper()->fetchAll($asRowSet, $where, $limit);
  }

  /**
   * Fetches one row in an object of type Zend_Db_Table_Row_Abstract,
   * or returns null if no row matches the specified criteria.
   *
   * @param boolean $asRow Specifies whether or not to return the resultset
   * 					             as an array of type Zend_Db_Table_Row_Abstract
   *                       or an array of type Default_Model_User
   * @param string|array|Zend_Db_Table_Select $where OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
   * @param string|array $order OPTIONAL An SQL ORDER clause.
   * @return Zend_Db_Table_Row_Abstract|Default_Flood_Model_Abstract|null
   */
  public function fetchRow($asRow=true, $where=null, $order=null)
  {
    return $this->getMapper()->fetchRow($asRow, $where, $order);
  }
}