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
    $query = $this->db->prepare("SELECT * FROM `users` WHERE `userHandle`=?");
    $query->bind_param("s", $userHandle);
    $query->execute();
    $query->store_result();
    if($query->num_rows == 0){
      $userPass = md5('prefix' . md5($userPass) . 'suffix');
      $query = $this->db->prepare("INSERT INTO users (userHandle, userPass, userGroup, userLevel) VALUES(?, ?, ?, ?)");
      $query -> bind_param("ssii", $userHandle, $userPass, $userGroup, $userLevel);
      $query -> execute();
      $query -> close();
    }
  }

  function banUser($userId){
    $query = $this->db->prepare("UPDATE users SET isBanned=1 WHERE userId=?");
    $query -> bind_param("i", $userId);
    $query -> execute();
    $query -> close();
  }

  function isBanned($userId){
    $query = $this->db->prepare("SELECT isBanned from users WHERE userId=?");
    $query -> bind_param("i", $userId);
    $query -> execute();
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    return $row['isBanned'];
  }

  function unbanUser($userId){
    $query = $this->db->prepare("UPDATE users SET isBanned=0 WHERE userId=?");
    $query -> bind_param("i", $userId);
    $query -> execute();
    $query -> close();
  }

  function deleteUser($userId){
    $query = $this->db->prepare("DELETE FROM users WHERE userId=?");
    $query -> bind_param("i", $userId);
    $query -> execute();
    $query -> close();
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

  function changePassword($userId, $currPassword, $newPassword){
    $currPassword = md5('prefix' . md5($currPassword) . 'suffix');
    $newPassword = md5('prefix' . md5($newPassword) . 'suffix');
    $query = $this->db->prepare("SELECT * FROM `users` WHERE `userId`=? && `userPass`=?");
    $query->bind_param("is", $userId, $currPassword);
    $query->execute();
    $query -> store_result();
    if($query->num_rows != 0){
      $query = $this->db->prepare("UPDATE `users` SET `userPass`=? WHERE `userId`=?");
      $query->bind_param("si", $newPassword, $userId);
      if($query->execute()){
        return true;
      }
    }
    return false;
  }

}


?>
