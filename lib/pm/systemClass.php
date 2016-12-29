<?php
class System {

  public $db;

  private $systemName;
  private $systemId;
  private $sectorId;

  function __construct(){
    $this->db = Database::getInstance();
  }

  function getSystems(){
    $query = $this->db->query("SELECT * FROM systems ORDER BY sectorId ASC, systemName ASC");
    $result = array();
    while($row = $query->fetch_assoc()){
      $result[] = $row;
    }

    return $result;
  }

  function getSystemName($systemId){
    $query = $this->db->prepare("SELECT systemName FROM systems WHERE systemId=?");
    $query -> bind_param("i",$systemId);
    $query -> execute();
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    return $row['systemName'];
  }
}
