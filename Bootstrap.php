<?php
// Must be defined before constants.php
if (!empty($_GET['loc']))
{
  switch ($_GET['loc'])
  {
    case 'bisman':
    case 'bismarck':
    case 'mandan':          define('LOCATION', 'bisman'); break;
    case 'minot':           define('LOCATION', 'minot');  break;
    case 'fargo-moorhead':  define('LOCATION', 'fargo-moorhead');  break;
    default:                define('LOCATION', 'bisman');
  }
}
else {
  define('LOCATION', 'bisman');
}

require_once 'constants.php';

date_default_timezone_set('US/Central');
set_include_path(implode(PATH_SEPARATOR, array(realpath(APP_PATH . '/library'))));

if (APP_ENV == 'production')
{
  ini_set('display_errors', 0);
  error_reporting(0);
  $db_options = array(
    'host'     => '127.0.0.1',
    'username' => 'flood2011',
    'password' => 'k!baWrcmWHM7BvUG#Wgni1mf',
    'dbname'   => 'flood2011'
  );
}
else {
  ini_set('display_errors', 1);
  error_reporting(E_ALL | E_STRICT);
  $db_options = array(
    'host'     => '127.0.0.1',
    'username' => 'test',
    'password' => 'test',
    'dbname'   => 'test'
  );
}

require 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Flood_');

$db = Zend_Db::factory('PDO_MYSQL', $db_options);
Zend_Db_Table::setDefaultAdapter($db);