<?php
/* @see Zend_Db_Table_Abstract */
require_once 'Zend/Db/Table/Abstract.php';

class Flood_Model_DbTable_Elevation extends Zend_Db_Table_Abstract
{
	protected $_name = 'elevation';
	protected $_table = 'elevation';
}