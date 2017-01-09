<?php

class Deposit {
    private $db;

    private $id;
    private $amount;
    private $rm;
    private $planet;
    private $terrain;
    private $x;
    private $y;

    /*public function __construct(){
        $this->db = Database::getInstance();
    }*/

    public function __set($name, $value) {}

    public function get($id) {
        $db = Database::getInstance();

        $query = $db->prepare("SELECT * FROM deposits WHERE id = ?");
        /*$query->execute([$id]);
        return $query->fetchObject("Deposit");*/
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_object("Deposit");
    }

    public static function findDeposit($planet, $x, $y) {
        $db = Database::getInstance();

        $query = $db->prepare("SELECT * FROM deposits WHERE planet = ? AND x = ? AND y = ?");
        /*$query->execute([$planet, $x, $y]);
        if ($query->rowCount() != 0) {
            return $query->fetchObject("Deposit");
        }*/
        $query->bind_param("iii", $planet, $x, $y);
        $query->execute();
        if ($query->num_rows != 0) {
            $result = $query->get_result();
            return $result->fetch_object("Deposit");
        }

        return new self();
    }

    public static function createDeposit($amount, $rm, $planet, $terrain, $x, $y) {
        $db = Database::getInstance();

        $insert =  $db->prepare("INSERT INTO deposits VALUES (NULL, ?, ?, ?, ?, ?, ?)");
        /*$insert->execute([$amount, $rm, $planet, $terrain, $x, $y]);
        if ($insert) {
            $id = $db->lastInsertId();
            return self::get($id);
        }*/
        $insert->bind_param("iiiiii", $amount, $rm, $planet, $terrain, $x, $y);
        $insert->execute();
        if ($insert) {
            $id = $insert->insert_id;
            return self::get($id);
        }

        return new self();
    }

    public function editDeposit($amount, $rm, $planet, $terrain, $x, $y) {

    }

    public function getId() {
        return $this->id;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getRM() {
        return RM::get($this->rm)->getName();
    }

    public function getPlanet() {
        return Planet::get($this->planet)->getName();
    }

    public function getTerrain() {
        return Terrain::get($this->terrain)->getName();
    }

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    public function getCoordinates() {
        return $this->x . ", " . $this->y;
    }
}