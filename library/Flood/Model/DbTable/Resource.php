<?php
/* @see Zend_Db_Table_Abstract */
require_once 'Zend/Db/Table/Abstract.php';

class Flood_Model_DbTable_Resource extends Zend_Db_Table_Abstract
{
	protected $_name = 'resource';
	protected $_table = 'resource';
}