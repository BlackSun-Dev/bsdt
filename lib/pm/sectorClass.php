<?php

class Sector {
    private $id;
    private $name;

    public function __set($name, $value) {}

    public static function get($id) {
        $db = Database::getInstance();

        $query = $db->prepare("SELECT * FROM sectors WHERE id = ?");
        /*$query->execute([$id]);
        return $query->fetchObject("Sector");*/
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_object("Sector");
    }

    public static function find($name) {
        $db = Database::getInstance();

        $query = $db->prepare("SELECT * FROM sectors WHERE name = ?");
        /*$query->execute([$name]);
        if ($query->rowCount() != 0) {
            return $query->fetchObject("Sector");
        }*/
        $query->bind_param("s", $name);
        $query->execute();
        if ($query->num_rows != 0) {
            $result = $query->get_result();
            return $result->fetch_object("Sector");
        }

        return new self();
    }

    public function getSystems() {
        $db = Database::getInstance();

        $systems = array();
        $query = $db->prepare("SELECT * FROM systems WHERE sector = ? ORDER BY name");
        $query->bind_param("i", $this->id);
        $query->execute();
        $result = $query->get_result();
        while($system = $result->fetch_object("System")) {
            $systems[] = $system;
        }

        return $systems;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
}