<?php

class Database {
  static $instance;

  private $host = 'localhost';
  private $dbuser = 'root';
  private $dbpass = 'pass';
  private $dbname = 'bsdt';

  public function __construct() {
    $this->connect();
  }

  private function connect(){
    self::$instance = new mysqli($this->host, $this->dbuser, $this->dbpass, $this->dbname);
    return self::$instance;
  }

  public static function getInstance() {
    if(!self::$instance) {
      $db = new self();
      self::$instance = $db->connect();
    }

    return self::$instance;
  }

  public static function __callStatic ($name, $args) {
    $callback = array(self::getInstance(), $name);
    return call_user_func_array($callback , $args);
  }

  public static function installCheck(){
    $db = self::getInstance();
    $query = $db->query("SHOW TABLES LIKE 'users'");
    if($query->num_rows > 0){
      return true;
    }
    else return false;
  }
}

?>
