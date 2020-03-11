<?php
/* @see Model_AbstractMapper */
require_once 'Flood/Model/AbstractMapper.php';

class Flood_Model_ResourceMapper extends Flood_Model_AbstractMapper
{
  /**
   * Get the table data gateway
   *
   * @return Zend_Db_Table_Abstract
   */
  public function getDbTable()
  {
    if (null === $this->_dbTable) $this->setDbTable('Flood_Model_DbTable_Resource');
    return $this->_dbTable;
  }

  /**
   * Convert a Zend_Db_Table_Row_Abstract to Default_Model_Abstract
   *
   * @param   Zend_Db_Table_Row_Abstract $row The row result per the Zend_Db_Adapter fetch mode, or null if no row found
   * @return  Model_Resource
   */
  protected function rowToModel(Zend_Db_Table_Row_Abstract $row)
  {
    $model = new Model_Resource();
    $model->setId($row->id)
          ->setLocationId($row->location_id)
          ->setName($row->name)
          ->setUrl($row->url)
          ->setTwitterUrl($row->twitter_url)
          ->setFacebookUrl($row->facebook_url)
          ->setMapper($this);
    return $model;
  }

  /**
   * Save the entry
   *
   * @param   Model_Resource   $model  The model
   * @return  void
   */
  public function save($model)
  {
    $data = array(
      'id'           => $model->getId(),
      'location_id'  => $model->getLocationId(),
      'name'         => $model->getName(),
      'url'          => $model->getUrl(),
      'twitter_url'  => $model->getTwitterUrl(),
      'facebook_url' => $model->getFacebookUrl(),
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
   * @param   Model_Resource  $model  The model
   * @return  Model_Resource
   */
  public function find($id, $model)
  {
    $result = $this->getDbTable()->find($id);
    if (0 == count($result)) return;

    $row = $result->current();

    $model->setId($row->id)
          ->setLocationId($row->location_id)
          ->setName($row->name)
          ->setUrl($row->url)
          ->setTwitterUrl($row->twitter_url)
          ->setFacebookUrl($row->facebook_url);
    return $model;
  }
}