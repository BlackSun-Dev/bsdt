<?php
require_once "lib/autoloader.php";

  if(isset($_POST['login'])){
    $users = new User;

    $debug = array($users, isset($_POST['login']),$_POST['username'], $_POST['password']);
    new debug($debug);

    $users->login($_POST['username'], $_POST['password']);

    $debug = array($users, isset($_POST['login']),$_POST['username'], $_POST['password']);
    new debug($debug);

	}

  //header("Location: index.php");

?>
