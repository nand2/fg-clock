<?php

$result = array();

// Process GLD data
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

$result["gld_data"] = array_slice($data, -201);

// Process predictive decline
$predictive_data = array();
$sample_size = 75;
$sum_deltas = 0;
for($i = count($data) - 1; $i > 0 && $i > count($data) - 1 - $sample_size; $i--) {
  $sum_deltas += $data[$i]["tons"] - $data[$i-1]["tons"];
}
$avg_delta = $sum_deltas / $sample_size;
$estimated_remaining_days = - round(($data[count($data)-1]["tons"] / $avg_delta));

if($estimated_remaining_days > 0) {
  $last_gld_day = new DateTime($data[count($data)-1]["date"]);

  $remaining = $estimated_remaining_days;
  $cur_day = $last_gld_day;
  $cur_tons = $data[count($data)-1]["tons"];
  while($remaining > 0) {
    $cur_day = $cur_day->modify("+1 weekday");
    $cur_tons += $avg_delta;
    $predictive_data[] = array(
      "date" => $cur_day->format("d-M-Y"),
      "tons" => round($cur_tons, 2)
    );
    $remaining--;
  }

  $result["predictive_data"] = $predictive_data;
}

echo json_encode($result);
  