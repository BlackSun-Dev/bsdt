<?php

class Deposit {

  public $db;

  private $depositId;
  private $plaX;
  private $plaY;
  private $depositSize;
  private $depositType;
  private $planetId;

  function __construct(){
    $this->db = Database::getInstance();
  }

  function addDeposit($plaX, $plaY, $depositSize, $depositType, $planetId){
    if(!self::checkDepositLocation($plaX, $plaY, $planetId)){
      $query = $this->db->prepare("INSERT INTO deposits (plaX, plaY, depositSize, depositType, planetId) VALUES(?, ?, ?, ?, ?)");
      $query -> bind_param("iiiii", $plaX, $plaY, $depositSize, $depositType, $planetId);
      $query -> execute();
      $query -> close();
    }
  }

  function checkDeposit($depositId){
    $query = $this->db->prepare("SELECT * FROM deposits WHERE depositId=?");
    $query -> bind_param("i", $depositId);
    $query -> execute();
    $query -> store_result();
    if($query->num_rows == 0){
      return false;
    }
    return true;
  }

  function checkDepositbyPlanet($planetId){
    $query = $this->db->prepare("SELECT * FROM deposits WHERE planetId=?");
    $query -> bind_param("i", $planetId);
    $query -> execute();
    $query -> store_result();
    if($query->num_rows == 0){
      return false;
    }
    return true;
  }

  function checkDepositLocation($plaX, $plaY, $planetId){
    $query = $this->db->prepare("SELECT * FROM deposits WHERE plaX=? AND plaY=? AND planetId=?");
    $query -> bind_param("iii", $plaX, $plaY, $planetId);
    $query -> execute();
    $query -> store_result();
    if($query->num_rows == 0){
      return false;
    }
    return true;
  }


  function deleteDeposit($depositId){
    if(self::checkDeposit($depositId)){
      $query = $this->db->prepare("DELETE * FROM deposits WHERE depositId=?");
      $query -> bind_param("i", $depositId);
      $query -> execute();
      $query -> close();
    }
  }

  function editDeposit($depositId, $option, $statement){
    if($option == "location"){
      setplaX($depositId,$statement);
    }
    elseif ($option == "size") {
      setDepositSize($depositId,$statement);
    }
    elseif($option == "type"){
      setDepositType($depositId,$statement);
    }
  }

  function getDepositLocation($depositId){
    if(self::checkDeposit($depositId)){
      $query = $this->db->prepare("SELECT plaX, plaY FROM deposits WHERE depositId=?");
      $query -> bind_param("i", $depositId);
      $query -> execute();
      return $query;
    }
  }

  function getDepositByLocation($plaX, $plaY, $planetId){
    $query = $this->db->prepare("SELECT * FROM deposits WHERE plaX=? AND plaY=? AND planetId=?");
    $query -> bind_param("iii", $plaX, $plaY, $planetId);
    $query -> execute();
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    return $row;
  }

  function getDepositsPlanet($planetId){
    if(self::checkDepositbyPlanet($planetId)){
      $query = $this->db->prepare("SELECT * FROM deposits WHERE planetId=? ORDER BY plaX ASC, plaY ASC");
      $query -> bind_param("i",$planetId);
      $query -> execute();
      $result = $query->get_result();
      while($row = $result->fetch_assoc()){
        $rows[] = $row;
      }
      return $rows;
    }
    else return null;
  }

  function getDepositSize($depositId){
    if(self::checkDeposit($depositId)){
      $query = $this->db->prepare("SELECT depositSize FROM deposits WHERE depositId=?");
      $query -> bind_param("i", $depositId);
      $query -> execute();
      $result = $query ->get_result();
      $row = $result->fetch_assoc();
      return $row['depositSize'];
    }
  }

  function getDepositType($depositId){
    if(self::checkDeposit($depositId)){
      $query = $this->db->prepare("SELECT depositType FROM deposits WHERE depositId=?");
      $query -> bind_param("i", $depositId);
      $query -> execute();
      $result = $query ->get_result();
      $row = $result->fetch_assoc();
      return $row['depositType'];
    }
  }

  function getTypeName($typeIndex){
    $type = array(
      "0" => "Quantum",
      "1" => "Meleenium",
      "2" => "Ardanium",
      "3" => "Rudic",
      "4" => "Ryll",
      "5" => "Duracrete",
      "6" => "Alazhi",
      "7" => "Laboi",
      "8" => "Adegan",
      "9" => "Rockivory",
      "10" => "Tibannagas",
      "11" => "Nova",
      "12" => "Varium",
      "13" => "Varmigio",
      "14" => "Lommite",
      "15" => "Hibridium",
      "16" => "Durelium",
      "17" => "Lowickan",
      "18" => "Vertex",
      "19" => "Berubian",
      "20" => "Bacta");

      return $type[$typeIndex];
    }

    /*
    * Determine the probability of finding a deposit on a certain terrain.
    * args: $terrainType, $depositType
    * return: $probability
    */

    function getTypeRarity(){

    }

    function setDepositLocation($depositId, $statement){
      if(self::checkDeposit($depositId)){
        $query = $this->db->prepare("UPDATE deposits SET plaX=?, plaY=? WHERE depositId=?");
        $query -> bind_param("ii", $statement, $depositId);
        $query -> execute();
      }
    }

    function setDepositPlanet($depositId, $statement){
      if(self::checkDeposit($depositId)){
        $query = $this->db->prepare("UPDATE deposits SET planetId=? WHERE depositId=?");
        $query -> bind_param("ii", $statement, $depositId);
        $query -> execute();
      }
    }

    function setDepositSize($depositId, $statement){
      if(self::checkDeposit($depositId)){
        $query = $this->db->prepare("UPDATE deposits SET depositSize=? WHERE depositId=?");
        $query -> bind_param("ii", $statement, $depositId);
        $query -> execute();
      }
    }

    function setDepositType($depositId, $statement){
      if(self::checkDeposit($depositId)){
        $query = $this->db->prepare("UPDATE deposits SET depositType=? WHERE depositId=?");
        $query -> bind_param("ii", $statement, $depositId);
        $query -> execute();
      }
    }
  }

  ?>
