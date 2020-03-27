<?php
$response_data = file_get_contents("https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-current-situation/covid-19-current-cases/covid-19-current-cases-details?".rand());

$dom = new DOMDocument;
@$dom->loadHTML($response_data);
// $dom->validateOnParse = true;

$covidResults = [];
$covidResponse = $dom->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr');

foreach ($covidResponse as $index => $result) {
 
  $results = explode("\n", str_replace("\t", "" , $result->nodeValue));
 
  // if ($results[1] === "DHB" || $results[1] === "Total") continue;
  $covidResults[] = array(
    "case" => $results[1],
    "dhb" => $results[2],
    "age" => $results[3],
    "gender" => $results[4],
    "details" => $results[5]
  );
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
echo json_encode($covidResults);