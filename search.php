<?php
require 'Bootstrap.php';

/**
 * Check if request method was POST
 *
 * @return boolean
 */
function isPost()
{
  return isset($_SERVER['REQUEST_METHOD'])
            && $_SERVER['REQUEST_METHOD'] == 'POST';
}

if (!isPost()) exit;

$input_county      = filter_input(INPUT_POST, 'county',      FILTER_SANITIZE_SPECIAL_CHARS);
$input_address     = filter_input(INPUT_POST, 'address',     FILTER_SANITIZE_SPECIAL_CHARS);
$input_elevation   = filter_input(INPUT_POST, 'elevation',   FILTER_SANITIZE_SPECIAL_CHARS);
$input_water_depth = filter_input(INPUT_POST, 'water_depth', FILTER_SANITIZE_SPECIAL_CHARS);
$input_levee       = filter_input(INPUT_POST, 'levee',       FILTER_SANITIZE_SPECIAL_CHARS);
  
if (!empty($input_county))          $options[] = array('county_id = ?',         $input_county);
if (!empty($input_address))         $options[] = array('street_address LIKE ?', "%$input_address%");
if (is_numeric($input_water_depth)) $options[] = array('water_depth >= ?',      $input_water_depth);
if (is_numeric($input_elevation))   $options[] = array('elevation >= ?',        $input_elevation);
if (!empty($input_levee))           $options[] = array('levee = ?',             $input_levee);
  
$elevation = new Flood_Model_Elevation();
$elevations = $elevation->fetchAll(true, $options, MAX_RESULTS);

echo json_encode(array(
  'status'   => 'success',
  'response' => $elevations->toArray(),
));