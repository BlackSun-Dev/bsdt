<?php

require_once "lib/autoloader.php";

$planets = new Planet;
$deposits = new Deposit;

$planetId = isset($_REQUEST['planetId']) ? $_REQUEST['planetId'] : null;

if($planetId){
  $size = $planets->getPlanetSize($planetId);
  $totalSize = $size * $size;
  $inputArray = array();
  $string = "";
  $inputArray = isset($_REQUEST['terrainInput']) ? $_REQUEST['terrainInput'] : null;
  foreach ($inputArray as $terrain){
    $code[] = $planets->getTerrainCode($terrain);
  }
  for($i=0; $i < count($code); $i++){
    $string = $string . $code[$i];
  }
  $planets->setPlanetTerrain($planetId, $string);
}

header("Location: planet?id=".$planetId."");
?>
