<?php
require 'Bootstrap.php';

$counties = new Flood_Model_County();
$counties = $counties->fetchAll();

$flex_counties = array();

foreach ($counties as $county)
{
  $flex_counties[] = array(
    'data' => $county->id,
    'label' => $county->name,
  );
}

echo json_encode(array('counties' => $flex_counties));