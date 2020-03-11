<?php
abstract class Flood_Model_AbstractMapper
{
  /**
	 * Paginator class instance
	 *
	 * @var Zend_Paginator
	 */
	protected $_paginator;

  /**
	 * Database table class instance
	 *
	 * @var Zend_Db_Table_Abstract
	 */
  protected $_dbTable;

  /**
   * Enable or disable the paginator
   *
   * @var boolean
   */
	protected $_usePaginator = false;

  /**
	 * Set the table data gateway
	 *
	 * @param   Zend_Db_Table_Abstract  $dbTable  An instance of Zend_Db_Table_Abstract
	 * @return  Flood_Model_AbstractMapper
	 */
  public function setDbTable($dbTable)
  {
    if (is_string($dbTable))
      $dbTable = new $dbTable();

    if (!$dbTable instanceof Zend_Db_Table_Abstract)
      throw new Exception('Invalid table data gateway provided');

    $this->_dbTable = $dbTable;
    return $this;
  }

  /**
   * Get the table data gateway
   *
   * @return Zend_Db_Table_Abstract
   */
  abstract public function getDbTable();

  /**
   * Set the paginator
   *
   * @param   Zend_Paginator|string	  $paginator  A Zend_Paginator instance or class name as a string
   * @return  Flood_Model_AbstractMapper
   */
  public function setPaginator($paginator)
  {
    if (is_string($paginator))
      $paginator = new $paginator();

    if (!$paginator instanceof Zend_Paginator)
      throw new Exception('Invalid paginator provided');

    $this->_paginator = $paginator;
    return $this;
  }

  /**
   * Get the paginator
   *
   * @return Zend_Paginator
   */
	public function getPaginator()
	{
		if (null === $this->_paginator)
			$this->setPaginator('Zend_Paginator');

    return $this->_paginator;
  }

  /**
   * Sets whether or not to return a Zend_Pagination object for fetch operations
   *
   * @param   boolean   $enable	  Specifies whether to use a paginator for result sets
   * @return  void
   */
  public function usePagination($enable=true)
  {
    $this->_usePaginator = $enable;
  }

  /**
   * Convert a Zend_Db_Table_Rowset_Abstract to an array of models
   *
   * @param   Zend_Db_Table_Rowset_Abstract   $resultSet  The row results per the Zend_Db_Adapter fetch mode.
   * @return 	array
   */
  protected function rowSetToModel(Zend_Db_Table_Rowset_Abstract $rowSet)
  {
    $entries = array();

    foreach ($rowSet as $row)
      $entries[] = $this->rowToModel($row);

    return $entries;
  }

  /**
   * Convert a Zend_Db_Table_Row_Abstract to Model_Abstract
   *
   * @param   Zend_Db_Table_Row_Abstract  $row  The row result per the Zend_Db_Adapter fetch mode, or null if no row found.
   * @return  Model_Abstract
   */
  abstract protected function rowToModel(Zend_Db_Table_Row_Abstract $row);

  /**
   * Save the entry
   *
   * @param   Model_Abstract  $model
   * @return  void
   */
  abstract public function save($model);

  /**
   * Deletes the specified row id
   *
   * @param   integer $id The row id
   * @return  integer The number of rows deleted
   */
	public function delete($id)
	{
		$where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id);
		return $this->getDbTable()->delete($where);
	}

  /**
   * Finds the specified row id
   *
   * @param   integer         $id     The row id
   * @param   Model_Abstract  $model
   * @return  Model_Abstract
   */
  abstract public function find($id, $model);

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
    $asRowSet = (bool) $asRowSet;
    $table = $this->getDbTable();
    $select = $table->select();
    
    if ($limit !== null)
      $select->limit($limit);
    
    if (is_array($where))
    {
      if (!is_array($where[0]))
        $select->where($where[0], $where[1]);
      else {
        foreach ($where as $w)
          $select->where($w[0], $w[1]);
      }
      
      $rowSet = $table->fetchAll($select);
    }
    else {
      $rowSet = $table->fetchAll();
    }
    
		// Pagination
		if ($this->_usePaginator)
	  {
      $paginator = Zend_Paginator::factory($rowSet);
      $this->setPaginator($paginator);

      return $this->getPaginator();
    }

    if ($asRowSet)
      return $rowSet;

    return $this->rowSetToModel($rowSet);
  }

  /**
   * Fetches one row in an object of type Zend_Db_Table_Row_Abstract,
   * or returns null if no row matches the specified criteria.
   *
   * @param boolean                           $asRow  Return the resultset as an array of Zend_Db_Table_Row_Abstract or Model_Abstract
   * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
   * @param string|array                      $order  OPTIONAL An SQL ORDER clause.
   * @return Zend_Db_Table_Row_Abstract|Model_Abstract|null
   */
  public function fetchRow($asRow=true, $where=null, $order=null)
  {
    $row = $this->getDbTable()->fetchRow($where, $order);

    if ($asRow) return $row;
    return $row === null ? null : $this->rowToModel($row);
  }
}