<?php

$csv = file_get_contents("http://www.spdrgoldshares.com/assets/dynamic/GLD/GLD_US_archive_EN.csv");
$csv_lines = explode("\n", $csv);

$data = array();
foreach($csv_lines as $csv_line) {
  $new_line = str_getcsv($csv_line);

  foreach($new_line as &$entry) {
    if(is_numeric($entry)) {
      $entry = floatval($entry);
    }
  }
  if(isset($new_line[10]) && is_numeric($new_line[10])) {
    $data[] = array(
      "date" => $new_line[0],
      "tons" => $new_line[10]
    );
  }
}

$data = array_slice($data, -201);

echo json_encode($data);
  