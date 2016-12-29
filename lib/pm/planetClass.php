<?php

class Planet {

  public $db;

  private $planetId;
  private $planetLocation;
  private $planetName;
  private $planetSize;
  private $planetSystem;

  function __construct(){
    $this->db = Database::getInstance();
  }

  function addPlanet($planetLocation,$planetName,$planetSize,$planetType,$systemId){
    if(!self::checkPlanetLocation($planetLocation,$systemId)){
      $query = $this->db->prepare("INSERT INTO planets WHERE planetLocation = :planetLocation and planetSize= :planetSize and planetType=:planetType and systemId= :systemId");
      $query -> bind_param(":planetLocation", $planetLocation);
      $query -> bind_param(":planetSize", $planetSize);
      $query -> bind_param(":planetType", $planetType);
      $query -> bind_param(":systemId", $systemId);
      $query -> execute();
      $query -> close();
    }
  }

  function checkPlanet($planetId){
    $query = $this->db->prepare("SELECT * FROM planets WHERE planetId=?");
    $query -> bind_param("i", $planetId);
    $query -> execute();
    $query -> store_result();
    if($query->num_rows == 0){
      return false;
    }
    return true;
  }

  function checkPlanetLocation($sysX,$sysY,$systemId){
    $query = $this->db->prepare("SELECT * FROM deposits WHERE sysX=? AND sysY=? AND systemId=?");
    $query -> bind_param("iii", $sysX, $sysY, $systemId);
    $query -> execute();
    $query -> store_result();
    if($query->num_rows == 0){
      return false;
    }
    return true;
  }

  function checkPlanetTerrain($planetId){
    $query = $this->db->prepare("SELECT terrain FROM planets WHERE planetId=?");
    $query -> bind_param("i", $planetId);
    $query -> execute();
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    if($row['terrain'] == ''){
      return false;
    }
    return true;
  }

  function countPlanetDeposits($planetId){
    if(self::checkPlanet($planetId)){
      $query = $deposit->getDepositsPlanet($planetId);
    }
    return $query;
  }

  function deletePlanet($planetId){
    if(self::checkPlanet($planetId)){
      $query = $this->db->prepare("DELETE * FROM planets WHERE planetId=?");
      $query -> bind_param("i",$planetId);
      $query -> execute();
      $query -> close();
    }
  }

  function editPlanet(){

  }

  function getPlanets(){
    $query = $this->db->query("SELECT * FROM planets ORDER BY systemId ASC, planetName ASC");
    $result = array();
    while($row = $query->fetch_assoc()){
      $result[] = $row;
    }

    return $result;
  }

  function getPlanetIdbyName($planetName){
    $query = $this->db->prepare("SELECT planetId FROM planets WHERE planetName=?");
    $query->bind_param("s",$planetName);
    $query->execute();
    return $query;
  }

  function getPlanetLocation($planetId){
    $query = $this->db->prepare("SELECT sysX, sysY FROM planets WHERE planetId=?");
    $query -> bind_param("i",$planetId);
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    return $row['sysX'] . ", " . $row['sysY'];
  }

  function getPlanetName($planetId){
    $query = $this->db->prepare("SELECT planetName FROM planets WHERE planetId=?");
    $query -> bind_param("i",$planetId);
    $query -> execute();
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    return $row['planetName'];
  }

  function getPlanetSize($planetId){
    $query = $this->db->prepare("SELECT planetSize FROM planets WHERE planetId=?");
    $query -> bind_param("i",$planetId);
    $query -> execute();
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    return $row['planetSize'];
  }

  function getPlanetbySystem($systemId){
    $query = $this->db->prepare("SELECT * FROM planets WHERE systemId=? ORDER BY planetName ASC");
    $query -> bind_param("i",$systemId);
    $query -> execute();
    $result = $query->get_result();
    while($row = $result->fetch_assoc()){
      $rows[] = $row;
    }
    return $rows;
  }

  function getPlanetTerrain($planetId){
    $query = $this->db->prepare("SELECT terrain FROM planets WHERE planetId=?");
    $query -> bind_param("i",$planetId);
    $query -> execute();
    $result = $query ->get_result();
    $row = $result->fetch_assoc();
    return $row['terrain'];
  }

  function getPlanetTerrainName($plaX, $plaY, $planetId){
    $typeIndex = (self::getPlanetSize($planetId) * $plaY) + $plaX;
    $typeStr = substr(self::getPlanetTerrain($planetId), $typeIndex, 1);
    $type = array(
      "a" => "Cave",
      "b" => "Crater",
      "c" => "Desert",
      "d" => "Forest",
      "e" => "Gas Giant",
      "f" => "Glacier",
      "g" => "Grassland",
      "h" => "Jungle",
      "i" => "Mountain",
      "j" => "Ocean",
      "k" => "River",
      "l" => "Rock",
      "m" => "Swamp",
      "n" => "Volcanic");
      return $type[$typeStr];
    }

    function getTerrainName($terrain){
      $type = array(
        "a" => "Cave",
        "b" => "Crater",
        "c" => "Desert",
        "d" => "Forest",
        "e" => "Gas Giant",
        "f" => "Glacier",
        "g" => "Grassland",
        "h" => "Jungle",
        "i" => "Mountain",
        "j" => "Ocean",
        "k" => "River",
        "l" => "Rock",
        "m" => "Swamp",
        "n" => "Volcanic");
        return $type[$terrain];
      }

      function listTerrainName($terrainIndex){
        $type = array(
          "0" => "Cave",
          "1" => "Crater",
          "2" => "Desert",
          "3" => "Forest",
          "4" => "Gas Giant",
          "5" => "Glacier",
          "6" => "Grassland",
          "7" => "Jungle",
          "8" => "Mountain",
          "9" => "Ocean",
          "10" => "River",
          "11" => "Rock",
          "12" => "Swamp",
          "13" => "Volcanic");
          return $type[$terrainIndex];
        }

      function getTerrainCode($terrain){
        $type = array(
          "Cave" => "a",
          "Crater" => "b",
          "Desert" => "c",
          "Forest" => "d",
          "Gas Giant" => "e",
          "Glacier" => "f",
          "Grassland" => "g",
          "Jungle" => "h",
          "Mountain" => "i",
          "Ocean" => "j",
          "River" => "k",
          "Rock" => "l",
          "Swamp" => "m",
          "Volcanic" => "n");
          return $type[$terrain];
        }

        function getPlanetType($planetId){
          $query = $this->db->prepare("SELECT planetType FROM planets WHERE planetId=?");
          $query -> bind_param("i",$planetId);
          $query -> execute();
          return $query;
        }

        function setPlanetLocation($planetId, $statement){
          if(self::checkPlanet($planetId)){
            $query = $this->db->prepare("UPDATE planets SET planetLocation =? WHERE planetId=?");
            $query -> bind_param("si",$statement, $planetId);
            $query -> execute();
            $query -> close();
          }
        }

        function setPlanetName($planetId, $statement){
          if(self::checkPlanet($planetId)){
            $query = $this->db->prepare("UPDATE planets SET planetName=? WHERE planetId=?");
            $query -> bind_param("si",$statement, $planetId);
            $query -> execute();
            $query -> close();
          }
        }

        function setPlanetSize($planetId, $statement){
          if(self::checkPlanet($planetId)){
            $query = $this->db->prepare("UPDATE planets SET planetSize=? WHERE planetId=?");
            $query -> bind_param("ii",$statement, $planetId);
            $query -> execute();
            $query -> close();
          }
        }

        function setPlanetSystem($planetId, $systemId, $statement){
          if(self::checkPlanet($planetId)){
            $query = $this->db->prepare("UPDATE planets SET systemId=? WHERE planetId=?");
            $query -> bind_param("ii",$statement, $planetId);
            $query -> execute();
            $query -> close();
          }
        }

        function setPlanetTerrain($planetId, $statement){
          if(self::checkPlanet($planetId)){
            $query = $this->db->prepare("UPDATE planets SET terrain=? WHERE planetId=?");
            $query -> bind_param("si",$statement, $planetId);
            $query -> execute();
            $query -> close();
          }
        }

        function setPlanetType($planetId, $statement){
          if(self::checkPlanet($planetId)){
            $query = $this->db->prepare("UPDATE planets SET planetType=? WHERE planetId=?");
            $query -> bind_param("si",$statement, $planetId);
            $query -> execute();
            $query -> close();
          }
        }
      }
      ?>
