<?php
/* @see Flood_Model_Abstract */
require_once 'Flood/Model/Abstract.php';

class Flood_Model_County extends Flood_Model_Abstract
{
  /**
   * @var integer
   */
  protected $_id = null;

  /**
   * @var string
   */
  protected $_name = null;

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
   * @return Flood_Model_County
   */
  public function setId($value)
  {
    $this->_id = $value;
    return $this;
  }

  /**
   * Retrieve the name
   *
   * @return string
   */
  public function getName()
  {
    return $this->_name;
  }

  /**
   * Set the name
   *
   * @param string $value
   * @return Flood_Model_County
   */
  public function setName($value)
  {
    $this->_name = $value;
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
    $this->setMapper(new Flood_Model_CountyMapper());
    return $this->_mapper;
  }
}