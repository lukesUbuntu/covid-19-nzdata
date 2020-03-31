<?php
$response_data = file_get_contents("https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-current-situation/covid-19-current-cases?".rand(),0, stream_context_create(["http"=>["timeout"=>45]]));
if($response_data === FALSE){
  die("can't connect to health.govt.nz");
}
$dom = new DOMDocument;
@$dom->loadHTML($response_data);
// $dom->validateOnParse = true;

$covidResults = [];
$covidResponse = $dom->getElementsByTagName('tbody')->item(1)->getElementsByTagName('tr');

foreach ($covidResponse as $index => $result) {
 
  $results = explode("\n", str_replace("\t", "" , $result->nodeValue));
  
  if ($results[1] === "Total") continue;

  $covidResults[] = array(
    "dhb" => $results[1],
    "cases" => $results[2],
  );
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
echo json_encode($covidResults);