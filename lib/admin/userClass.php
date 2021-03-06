<?php

class User {

  private $db;
  private $userHandle;
  private $userPass;
  private $userGroup;
  private $userLevel;

  function __construct(){
    $this->db = Database::getInstance();
  }

  function addUser($userHandle, $userPass, $userGroup, $userLevel){
    if(!self::checkDepositLocation($plaX, $plaY, $planetId)){
      $query = $this->db->stmt_init();
      $query = $this->db->prepare("INSERT INTO users (userHandle, userPass, userGroup, userLevel) VALUES(?, ?, ?, ?)");
      $query -> bind_param("ssii", $userHandle, $userPass, $userGroup, $userLevel);
      $query -> execute();
      $query -> close();
    }
  }

  function banUser($userId){

  }

  function deleteUser($userId){

  }

  function assignUser($userId, $option, $param){
    $query = $this->db->stmt_init();
    if($option == 'group'){
      $query = $this->db->prepare("UPDATE users SET userGroup=? WHERE userId=?");
    }
    elseif($option == 'level'){
      $query = $this->db->prepare("UPDATE users SET userLevel=? WHERE userId=?");
    }
    else{
      return;
    }
    $query -> bind_param("ii", $param, $userId);
    $query -> execute();
    $query -> close();
  }

  function getPermissionLevel($userId){
    $query = $this->db->prepare("SELECT userLevel from users WHERE userId=?");
    $query -> bind_param("i", $userId);
    $query -> execute();
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    return $row['userLevel'];
  }

  function hasAccess($userId, $requiredLevel){
    $query = $this->db->stmt_init();
    $query = $this->db->prepare("SELECT userLevel from users WHERE userId=?");
    $query -> bind_param("i", $userId);
    $query -> execute();
    $foundLevel = $query -> store_result();
    if($foundLevel >= $required){
      return true;
    }
    return false;
  }

  function login($username, $password){
    $password = md5('prefix' . md5($password) . 'suffix');
    $query = $this->db->prepare("SELECT * FROM `users` WHERE `userHandle`=? && `userPass`=?");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    session_start();
    $_SESSION['username'] = $row['userHandle'];
    $_SESSION['userId'] = $row['userId'];
    $_SESSION['userGroup'] = $row['userGroup'];
    $_SESSION['userLevel'] = $row['userLevel'];
    session_write_close();
  }

  function permissionIndex($permissionIndex){
    $type = array(
      null => 0,
      "User" => 1,
      "Assistant" => 2,
      "Consiglio" => 3,
      "Vigo" => 4,
      "Throne" => 5,
    );

    return $type[$permissionIndex];
  }
}


?>
