<?php
/* @see Flood_Model_Abstract */
require_once 'Flood/Model/Abstract.php';

class Flood_Model_Resource extends Flood_Model_Abstract
{
  /**
   * @var integer
   */
  protected $_id = null;

  /**
   * @var integer
   */
  protected $_location_id = null;
  
  /**
   * @var string
   */
  protected $_name = null;

  /**
   * @var string
   */
  protected $_url = null;
  
  /**
   * @var string
   */
  protected $_twitter_url = null;
  
  /**
   * @var string
   */
  protected $_facebook_url = null;
  
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
   * @return Flood_Model_Resource
   */
  public function setId($value)
  {
    $this->_id = $value;
    return $this;
  }
  
  /**
   * Retrieve the location id
   *
   * @return integer
   */
  public function getLocationId()
  {
    return $this->_location_id;
  }

  /**
   * Set the location id
   *
   * @param integer $value
   * @return Flood_Model_Resource
   */
  public function setLocationId($value)
  {
    $this->_location_id = $value;
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
   * @return Flood_Model_Resource
   */
  public function setName($value)
  {
    $this->_name = $value;
    return $this;
  }
  
  /**
   * Retrieve the url
   *
   * @return string
   */
  public function getUrl()
  {
    return $this->_url;
  }

  /**
   * Set the url
   *
   * @param string $value
   * @return Flood_Model_Resource
   */
  public function setUrl($value)
  {
    $this->_url = $value;
    return $this;
  }
  
  /**
   * Retrieve the Twitter url
   *
   * @return string
   */
  public function getTwitterUrl()
  {
    return $this->_twitter_url;
  }

  /**
   * Set the Twitter url
   *
   * @param string $value
   * @return Flood_Model_Resource
   */
  public function setTwitterUrl($value)
  {
    $this->_twitter_url = $value;
    return $this;
  }
  
  /**
   * Retrieve the Facebook url
   *
   * @return string
   */
  public function getFacebookUrl()
  {
    return $this->_facebook_url;
  }

  /**
   * Set the Facebook url
   *
   * @param string $value
   * @return Flood_Model_Resource
   */
  public function setFacebookUrl($value)
  {
    $this->_facebook_url = $value;
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
    $this->setMapper(new Flood_Model_ResourceMapper());
    return $this->_mapper;
  }
}