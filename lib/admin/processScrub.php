<?php
    error_reporting(E_ALL);
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";

    if (isset($_SESSION['userId'])) {

        $query = "SELECT `entityID` FROM `changesBasic` WHERE ";

        $_POST['iffValue'] = 'all';
        $_POST['timeFrameValue'] = '364';

    // Adds IFF check to query
        if (isset($_POST['iffValue']) == 'all') {
            $query .= "`iffStatus` IS NOT NULL";
        } else {
            $query .= "`iffStatus` = '" . $_POST['iffValue'] . "'";
        }

    // Gets the length of 1 day in seconds
        $currYear = date('y');
        $currMonth = date('n');
        $currDay = date('z') + 94;

    // Adds time conversion to query to prevent redundancy
        $query .= " && (((((`years` * 365) * 24) * 60) * 60) + (((`days` * 24) * 60) * 60)) <";

        if (isset($_POST['timeFrameValue']) == '1') {
            $query .= " " . mktime(0, 0, 0, $currMonth, $currDay - 1, $currYear);
        } else if (isset($_POST['timeFrameValue']) == '2') {
            $query .= " " . mktime(0, 0, 0, $currMonth, $currDay - 2, $currYear);
        } else if (isset($_POST['timeFrameValue']) == '7') {
            $query .= " " . mktime(0, 0, 0, $currMonth, $currDay - 7, $currYear);
        } else if (isset($_POST['timeFrameValue']) == '30') {
            $query .= " " . mktime(0, 0, 0, $currMonth, $currDay - 30, $currYear);
        } else if (isset($_POST['timeFrameValue']) == '182') {
            $query .= " " . mktime(0, 0, 0, $currMonth, $currDay - 182, $currYear);
        } else if (isset($_POST['timeFrameValue']) == '364') {
            $query .= " " . mktime(0, 0, 0, $currMonth, $currDay, $currYear - 1);
        }

        $result = $mysqli -> query($query);
        $resultArray = array();

    // Adds entity IDs to an array to prevent adding duplicates
        while ($row = $result -> fetch_assoc()) {
            if (!in_array($row['entityID'], $resultArray)) {
                $resultArray[] = $row['entityID'];
            }
        }

    // Sets up the query to delete the selected entities
        $delChangesBasic = "DELETE FROM `changesbasic` WHERE (";
        $delChangesFocus = "DELETE FROM `changesfocus` WHERE (";
        $delScansBasic = "DELETE FROM `scansbasic` WHERE (";
        $delScansFocus = "DELETE FROM `scansfocus` WHERE (";

    // Loops through list of entity IDs and builds the queries
        foreach ($resultArray as $value) {
            $delChangesBasic .= "`entityID` = '" . $value . "' || ";
            $delChangesFocus .= "`entityID` = '" . $value . "' || ";
            $delScansBasic .= "`entityID` = '" . $value . "' || ";
            $delScansFocus .= "`entityID` = '" . $value . "' || ";
        }

    // Trims trailing "OR" from queries and adds the right parenthesis
        $delChangesBasic = substr($delChangesBasic, 0, -4);
            $delChangesBasic .= ")";
        $delChangesFocus = substr($delChangesFocus, 0, -4);
            $delChangesFocus .= ")";
        $delScansBasic = substr($delScansBasic, 0, -4);
            $delScansBasic .= ")";
        $delScansFocus = substr($delScansFocus, 0, -4);
            $delScansFocus .= ")";

        $mysqli -> query($delChangesBasic);
        $mysqli -> query($delChangesFocus);
        $mysqli -> query($delScansBasic);
        $mysqli -> query($delScansFocus);

        echo json_encode($_SESSION['error']['013']);
    }
?>