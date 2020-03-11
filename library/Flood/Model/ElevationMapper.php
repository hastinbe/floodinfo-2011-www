<?php
/* @see Model_AbstractMapper */
require_once 'Flood/Model/AbstractMapper.php';

class Flood_Model_ElevationMapper extends Flood_Model_AbstractMapper
{
  /**
   * Get the table data gateway
   *
   * @return Zend_Db_Table_Abstract
   */
  public function getDbTable()
  {
    if (null === $this->_dbTable) $this->setDbTable('Flood_Model_DbTable_Elevation');
    return $this->_dbTable;
  }

  /**
   * Convert a Zend_Db_Table_Row_Abstract to Default_Model_Abstract
   *
   * @param   Zend_Db_Table_Row_Abstract $row The row result per the Zend_Db_Adapter fetch mode, or null if no row found
   * @return  Model_Elevation
   */
  protected function rowToModel(Zend_Db_Table_Row_Abstract $row)
  {
    $model = new Model_Elevation();
    $model->setId($row->id)
          ->setCountyId($row->county_id)
          ->setStreetAdress($row->street_address)
          ->setSuffix($row->suffix)
          ->setUnit($row->unit)
          ->setElevation($row->elevation)
          ->setLevee($row->levee)
          ->setWaterDepth($row->water_depth)
          ->setMapper($this);
    return $model;
  }

  /**
   * Save the entry
   *
   * @param   Model_Elevation   $model  The model
   * @return  void
   */
  public function save($model)
  {
    $data = array(
      'id'             => $model->getId(),
      'county_id'      => $model->getCountyId(),
      'street_address' => $model->getStreetAddress(),
      'suffix'         => $model->getSuffix(),
      'unit'           => $model->getUnit(),
      'elevation'      => $model->getElevation(),
      'levee'          => $model->getLevee(),
      'water_depth'    => $model->getWaterDepth(),
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
   * @param   integer         $id     The row ID
   * @param   Model_Elevation $model  The model
   * @return  Model_Elevation
   */
  public function find($id, $model)
  {
    $result = $this->getDbTable()->find($id);
    if (0 == count($result)) return;

    $row = $result->current();

    $model->setId($row->id)
          ->setCountyId($row->county_id)
          ->setStreetAddress($row->street_address)
          ->setSuffix($row->suffix)
          ->setUnit($row->unit)
          ->setElevation($row->elevation)
          ->setLevee($row->levee)
          ->setWaterDepth($row->water_depth);
    return $model;
  }
}