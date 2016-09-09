<?php
    require "lib/autoloader.php";

$planets = new Planet;
$deposits = new Deposit;

if(isset($_POST['addDeposit'])){
  if(isset($_POST['depositSize']) && isset($_POST['depositType']) && isset($_POST['planetId']) && isset($_POST['location'])){
    $array = explode(",", $_POST['location']);
    $deposits->addDeposit($array[0],$array[1],$_POST['depositSize'], $_POST['depositType'], $_POST['planetId']);
    if($deposits->checkDepositLocation($array[0],$array[1],$_POST['planetId'])){
    header("Location: planet?id=".$_POST['planetId']."");
    }
  }
}
if(isset($_POST['addPlanet'])){
  $array = explode(',', $_POST['location']);
  $planets->addPlanet($array[0],$array[1], $_POST['planetSize'], $_POST['planetType'], $_POST['planetName']);
}

?>
