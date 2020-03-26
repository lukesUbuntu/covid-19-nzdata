<?php
$response_data = file_get_contents("https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-current-cases?".rand());

$dom = new DOMDocument;
@$dom->loadHTML($response_data);
// $dom->validateOnParse = true;

$covidResults = [];
$covidResponse = $dom->getElementsByTagName('tbody')->item(1)->getElementsByTagName('tr');

foreach ($covidResponse as $index => $result) {
 
  $results = explode("\n", str_replace("\t", "" , $result->nodeValue));
  if ($results[1] === "DHB" || $results[1] === "Total") continue;
  $covidResults[] = array(
    "dhb" => $results[1],
    "confirmed" => $results[2],
    "probable" => $results[3],
    "total" => $results[4],
  );
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
echo json_encode($covidResults);