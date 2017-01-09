<?php

class Planet {
    private $id;
    private $name;
    private $size;
    private $system;

    public function __set($name, $value) {}

    public static function get($id) {
        $db = Database::getInstance();

        $query = $db->prepare("SELECT * FROM planets WHERE id = ?");
        /*$query->execute([$id]);
        return $query->fetchObject("Planet");*/
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_object("Planet");
    }

    public static function find($name) {
        $db = Database::getInstance();

        $query = $db->prepare("SELECT * FROM planets WHERE name = ?");
        /*$query->execute([$name]);
        if ($query->rowCount() != 0) {
            return $query->fetchObject("Planet");
        }*/
        $query->bind_param("s", $name);
        $query->execute();
        if ($query->num_rows != 0) {
            $result = $query->get_result();
            return $result->fetch_object("Planet");
        }

        return new self();
    }

    public static function createPlanet($name, $size, $system) {
        $db = Database::getInstance();

        $insert = $db->query("INSERT INTO planets VALUES (NULL, ?, ?, ?)");
        /*$insert->execute([$name, $size, $system]);
        if ($insert) {
            $id = $db->lastInsertId();
            return self::get($id);
        }*/
        $insert->bind_param("sii", $name, $size, $system);
        $insert->execute();
        if ($insert) {
            $id = $insert->insert_id;
            return self::get($id);
        }

        return new self();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getSize() {
        return $this->size;
    }

    public function getSystem() {
        return System::get($this->system)->getName();
    }
}