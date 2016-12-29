<?php
class Group {

  private $db;
  private $groupName;
  private $groupMembers;

  function __construct(){
    $this->db = Database::getInstance();
  }


}
?>
