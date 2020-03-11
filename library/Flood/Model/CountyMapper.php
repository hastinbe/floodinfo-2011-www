<?php
/* @see Model_AbstractMapper */
require_once 'Flood/Model/AbstractMapper.php';

class Flood_Model_CountyMapper extends Flood_Model_AbstractMapper
{
  /**
   * Get the table data gateway
   *
   * @return Zend_Db_Table_Abstract
   */
  public function getDbTable()
  {
    if (null === $this->_dbTable) $this->setDbTable('Flood_Model_DbTable_County');
    return $this->_dbTable;
  }

  /**
   * Convert a Zend_Db_Table_Row_Abstract to Default_Model_Abstract
   *
   * @param   Zend_Db_Table_Row_Abstract $row The row result per the Zend_Db_Adapter fetch mode, or null if no row found
   * @return  Model_County
   */
  protected function rowToModel(Zend_Db_Table_Row_Abstract $row)
  {
    $model = new Model_County();
    $model->setId($row->id)
          ->setName($row->name)
          ->setMapper($this);
    return $model;
  }

  /**
   * Save the entry
   *
   * @param   Model_County   $model  The model
   * @return  void
   */
  public function save($model)
  {
    $data = array(
      'id'   => $model->getId(),
      'name' => $model->getName(),
    );
    
    if (null === ($id = $model->getId())) {
        unset($data['id']);
      $model->setId($this->getDbTable()->insert($data));
    }
    else {
      $this->getDbTable()->update($data, array('id = ?' => $id));
    }
  }

  /**
   * Finds the specified row id
   *
   * @param   integer       $id     The row ID
   * @param   Model_County  $model  The model
   * @return  Model_County
   */
  public function find($id, $model)
  {
    $result = $this->getDbTable()->find($id);
    if (0 == count($result)) return;

    $row = $result->current();

    $model->setId($row->id)
          ->setName($row->name);
    return $model;
  }
}