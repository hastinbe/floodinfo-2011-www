<?php
/** @see Bootstrap **/
require '../Bootstrap.php';

$file   = '../data/Bismarck_Addresses_with_Elevation 6-2-11 3pm_150k_6_2.txt';
$match  = array();
$handle = fopen($file, 'r');
$num_lines = 0;
$num_written = 0;
$errors = array();

// STREET ADDRESS: SUFFIX: UNIT: ELEVATION: PROTECTED BY LEVEE(S): WATER DEPTH: 
// 1 CUSTER DR 1684.88 NO 0.000 
$old_regex = '/^(?P<Street_Address>.*\s(ST|AV|PL|LP|LN|RD|DR))(?:\s)?(?P<Suffix>(?:(NE|NW|SE|SW|N|S|E|W)\s))?(?:\s)?(?P<Unit>(?:.*))?\s(?P<Elevation>\d+\.\d+)\s(?P<Levee>\w+)\s(?P<Water_Depth>\d+\.\d+)\s+$/i';

// Street Address: Unit: Elevation: Water Depth: Protection: 
// 100 E BISMARCK 1640.79 0.00 YES 
$regex = '/^(?P<Street_Address>.*\s(?:ST|AV|PL|LP|LN|RD|DR)?[A-Za-z0-9 \-\(\)\#]+)\s(?P<Elevation>\d+\.\d+)\s(?P<Water_Depth>\d+\.\d+)\s(?P<Protection>\w+)\s+$/i';

while (false !== ($data = fgets($handle, 2048)))
{
  $num_lines++;
  $found = preg_match($regex, $data, $match);
  if (!$found) {
    echo 'NO MATCH: ' . $data . '<br/><br/>';
    continue;
  }
  /*
  echo $match['Street_Address'] . '<br/>';
  echo $match['Elevation'] . '<br/>';
  echo $match['Water_Depth'] . '<br/>';
  echo $match['Protection'] . '<br/>';
  echo '<br/><br/>';
*/
  $elevation = new Flood_Model_Elevation(array(
    'countyId'      => 1, // Burleigh ID in County table
    'streetAddress' => $match['Street_Address'],
    'elevation'     => $match['Elevation'],
    'levee'         => $match['Protection'],
    'waterDepth'    => $match['Water_Depth']
  ));
  
  // Optional
  if (!empty($match['Suffix'])) $elevation->setSuffix($match['Suffix']);
  if (!empty($match['Unit']))   $elevation->setUnit($match['Unit']);

  try {
    $elevation->save();
  }
  catch (Exception $e)
  {
    $errors[] = "Line $num_lines: " . $e->getMessage();
    continue;
  }
  $num_written++;

}
/*
if (!feof($handle)) echo "fgets(): an unexpected error occurred\n";

fclose($handle);

$summary = "Read $num_lines lines\n" .
           "Wrote $num_written records\n" .
           "Errors: " . count($errors) . "\n" .
           "===========================";
foreach ($errors as $error)
  $summary .= $error . "\n";

echo $summary;
*/