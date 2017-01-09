<?php

class System {
    private $id;
    private $name;
    private $sector;

    public function __set($name, $value) {}

    public static function get($id) {
        $db = Database::getInstance();

        $query = $db->prepare("SELECT * FROM systems WHERE id = ?");
        /*$query->execute([$id]);
        return $query->fetchObject("System");*/
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_object("System");
    }

    public static function find($name) {
        $db = Database::getInstance();

        $query = $db->prepare("SELECT * FROM systems WHERE name = ?");
        /*$query->execute([$name]);
        if ($query->rowCount() != 0) {
            return $query->fetchObject("System");
        }*/
        $query->bind_param("s", $name);
        $query->execute();
        if ($query->num_rows != 0) {
            $result = $query->get_result();
            return $result->fetch_object("System");
        }

        return new self();
    }

    public function getPlanets() {
        $db = Database::getInstance();

        $planets = array();
        $query = $db->prepare("SELECT * FROM planets WHERE system = ? ORDER BY name");
        $query->bind_param("i", $this->id);
        $query->execute();
        $result = $query->get_result();
        while($planet = $result->fetch_object("Planet")) {
            $planets[] = $planet;
        }

        return $planets;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getSector() {
        return Sector::get($this->sector)->getName();
    }
}