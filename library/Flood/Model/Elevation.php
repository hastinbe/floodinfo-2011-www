<?php
/* @see Flood_Model_Abstract */
require_once 'Flood/Model/Abstract.php';

class Flood_Model_Elevation extends Flood_Model_Abstract
{
  /**
   * @var integer
   */
  protected $_id = null;

  /**
   * @var integer
   */
  protected $_county_id = null;
  
  /**
   * @var string
   */
  protected $_street_address = null;

  /**
   * @var string
   */
  protected $_suffix = null;

  /**
   * @var string
   */
  protected $_unit = null;
  
  /**
   * @var float
   */
  protected $_elevation = null;
  
  /**
   * @var string
   */
  protected $_levee = null;
  
  /**
   * @var float
   */
  protected $_water_depth = null;

  /**
   * Retrieve the id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->_id;
  }

  /**
   * Set the id
   *
   * @param integer $value
   * @return Flood_Model_Elevation
   */
  public function setId($value)
  {
    $this->_id = $value;
    return $this;
  }
  
  /**
   * Retrieve the county id
   *
   * @return integer
   */
  public function getCountyId()
  {
    return $this->_county_id;
  }

  /**
   * Set the county id
   *
   * @param integer $value
   * @return Flood_Model_Elevation
   */
  public function setCountyId($value)
  {
    $this->_county_id = $value;
    return $this;
  }

  /**
   * Retrieve the street address
   *
   * @return string
   */
  public function getStreetAddress()
  {
    return $this->_street_address;
  }

  /**
   * Set the street address
   *
   * @param string $value
   * @return Flood_Model_Elevation
   */
  public function setStreetAddress($value)
  {
    $this->_street_address = $value;
    return $this;
  }

  /**
   * Retrieve the suffix
   *
   * @return string
   */
  public function getSuffix()
  {
    return $this->_suffix;
  }

  /**
   * Set the suffix
   *
   * @param string $value
   * @return Flood_Model_Elevation
   */
  public function setSuffix($value)
  {
    $this->_suffix = $value;
    return $this;
  }

  /**
   * Retrieve the unit
   *
   * @return string
   */
  public function getUnit()
  {
    return $this->_unit;
  }

  /**
   * Set the unit
   *
   * @param string $value
   * @return Flood_Model_Elevation
   */
  public function setUnit($value)
  {
    $this->_unit = $value;
    return $this;
  }
  
  /**
   * Retrieve the elevation
   *
   * @return float
   */
  public function getElevation()
  {
    return $this->_elevation;
  }

  /**
   * Set the elevation
   *
   * @param float $value
   * @return Flood_Model_Elevation
   */
  public function setElevation($value)
  {
    $this->_elevation = $value;
    return $this;
  }
  
  /**
   * Retrieve levee
   *
   * @return string
   */
  public function getLevee()
  {
    return $this->_levee;
  }

  /**
   * Set levee
   *
   * @param string $value
   * @return Flood_Model_Elevation
   */
  public function setLevee($value)
  {
    $this->_levee = $value;
    return $this;
  }
  
  /**
   * Retrieve the water depth
   *
   * @return float
   */
  public function getWaterDepth()
  {
    return $this->_water_depth;
  }

  /**
   * Set the water depth
   *
   * @param float $value
   * @return Flood_Model_Elevation
   */
  public function setWaterDepth($value)
  {
    $this->_water_depth = $value;
    return $this;
  }

  /**
   * Get the mapper
   *
   * @return object
   */
  public function getMapper()
  {
    if (null === $this->_mapper)
    $this->setMapper(new Flood_Model_ElevationMapper());
    return $this->_mapper;
  }
}