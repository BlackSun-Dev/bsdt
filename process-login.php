<?php
require_once "lib/autoloader.php";

  if(isset($_POST['login'])){
    $users = new User;
    $users->login($_POST['username'], $_POST['password']);
	}

  header("Location: index");

?>
