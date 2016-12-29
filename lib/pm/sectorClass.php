<?php

class Sector {

  public $db;

  private $sectorName;
  private $sectorId;

  function __construct(){
    $this->db = Database::getInstance();
  }

  function getSectorName($sectorId){
    $query = $this->db->prepare("SELECT sectorName FROM sectors WHERE sectorId=?");
    $query -> bind_param("i",$sectorId);
    $query -> execute();
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    return $row['sectorName'];
  }
}
