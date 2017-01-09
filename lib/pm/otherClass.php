<?php

class Other {
    public static function allSectors() {
        $db = Database::getInstance();

        $sectors = array();
        $query = $db->query("SELECT * FROM sectors ORDER BY name");
        while ($result = $query->fetch_object("Sector")) {
            $sectors[] = $result;
        }

        return $sectors;
    }

    public static function allTerrains() {
        $db = Database::getInstance();

        $terrains = array();
        $query = $db->query("SELECT * FROM terrain ORDER BY name");
        while ($result = $query->fetch_object("Terrain")) {
            $terrains[] = $result;
        }

        return $terrains;
    }

    public static function allRMs() {
        $db = Database::getInstance();

        $rms = array();
        $query = $db->query("SELECT * FROM rm ORDER BY name");
        while ($result = $query->fetch_object("RM")) {
            $rms[] = $result;
        }

        return $rms;
    }
}