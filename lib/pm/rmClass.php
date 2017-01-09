<?php

class RM {
    private $id;
    private $name;

    public function __set($name, $value) {}

    public static function get($id) {
        $db = Database::getInstance();

        $query = $db->prepare("SELECT * FROM rm WHERE id = ?");
        /*$query->execute([$id]);
        return $query->fetchObject("RM");*/
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_object("RM");
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
}