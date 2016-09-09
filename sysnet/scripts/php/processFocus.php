<?php
    error_reporting(E_ALL);
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";
		//removeFriend($array); nameFix($dir);

// Enables or disables debug mode.
// To enable debug mode, set $debug to 1
// Debug mode prevents all calls to the database unless purely to retrieve data
//      No changes to the DB can be made!
    if (isset($_SESSION['userLogged'])) {
        $debug = 0;

        if ($debug == 1) {
            echo "DEBUG MODE ENABLED <br /><br />";
        }

        $scannedBy = $_POST['scannedBy'];
        $system = $_POST['systemInput'];
        $xml = $_POST['xmlInput'];
        $xml = stripslashes($xml);

        file_put_contents('tempXML/test.xml', $xml);

        $xml = file_get_contents('tempXML/test.xml');
        $xmlArray = xml2array($xml);

        if (varCheck($xmlArray['rss']['channel']['link']) == 'http://www.swcombine.com') {

        // Sets timestamp
            $y = $xmlArray['rss']['channel']['cgt']['years'];
            $d = $xmlArray['rss']['channel']['cgt']['days'];
            $h = $xmlArray['rss']['channel']['cgt']['hours'];
            $m = $xmlArray['rss']['channel']['cgt']['minutes'];
            $s = $xmlArray['rss']['channel']['cgt']['seconds'];

        // List of values in focus scan stored in array form for later use
            $focusValues = array('hyperspeed', 'hyperspeedMax', 'sublightspeed', 'sublightspeedMax', 'manoeuvrability', 'manoeuvrabilityMax', 'sensors', 'sensorsMax', 'sensorRange', 'sensorRangeMax', 'ECM', 'weapons', 'passengers', 'ships', 'vehicles', 'materials');

        // Gets entity array for easy access
            $entityArray = $xmlArray['rss']['channel']['item'];

        // Does a quick check for duplicate entries. If found to be duplicate, re-directs user to scan page and generates an error
            $stmt = $mysqli -> prepare("SELECT `entityID` FROM `scansFocus` WHERE (`years` = ? AND `days` = ? AND `hours` = ? AND `minutes` = ? AND `seconds` = ? AND `entityID` = ?");
                echo $mysqli -> error;

            $stmt -> bind_param('ssssss', $y, $d, $h, $m, $s, $entityArray['entityID']);
            $stmt -> execute();
            $stmt -> store_result();
            $stmt -> bind_result($entityID);

            if ($stmt -> num_rows > 0) {
                echo json_encode($_SESSION['error']['005']);
            } else {
            // Pulls entities from weapons array and converts them to one string under "weapons"
                if (is_array($entityArray['weapons'])) {
                // Initiates variables to store weapon information in
                    $weapons = '';

                // Loops through each item in the weapons array
                    foreach ($entityArray['weapons'] as $weaponKey => $weaponValue) {
                        $weapons .= $weaponKey . ':' . $weaponValue . '-';
                        unset($entityArray['weapons'][$weaponKey]);
                    }

                    $weapons = substr($weapons, 0, -1);
                    $entityArray['weapons'] = $weapons;
                }

            // Pull entities from cargo and adds them to the item individually
                foreach ($entityArray['cargo'] as $cargoKey => $cargoValue) {
                    $entityArray[$cargoKey] = $cargoValue;
                }
                unset($entityArray['cargo']);

            // Initiates queries for inserting focus scans into database
                $changesStmt = $mysqli -> prepare("INSERT INTO `changesfocus` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
                    echo $mysqli -> error;

                $scanStmt = $mysqli -> prepare("INSERT INTO `scansfocus` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
                    echo $mysqli -> error;

            // Gets last entry from `changesFocus`
                $stmt = $mysqli -> prepare("SELECT `years`, `days`, `hours`, `minutes`, `seconds`, `scannedBy`, `entityID`, `hyperspeed`, `hyperspeedMax`, `sublightspeed`, `sublightspeedMax`, `manoeuvrability`, `manoeuvrabilityMax`, `sensors`, `sensorsMax`, `sensorRange`, `ECM`, `weapons`, `passengers`, `ships`, `vehicles`, `materials` FROM `changesfocus` WHERE `entityID` = ? ORBER BY `years`, `days`, `hours`, `minutes`, `seconds` DESC LIMIT 1");
                    echo $mysqli -> error;

                $stmt -> bind_param('s', $entityArray['entityID']);
                $stmt -> execute();
                $stmt -> store_result();
                $stmt -> bind_result($hyperspeed, $hyperspeedMax, $sublightspeed, $sublightspeedMax, $manoeuvrability, $manoeuvrabilityMax, $sensors, $sensorsMax, $sensorRange, $ECM, $weapons, $passengers, $ships, $vehicles, $materials);

            // Checks if row is returned and then if a change is required
                $newChange = 0;
                $compareKeys = array(`hyperspeed`, `hyperspeedMax`, `sublightspeed`, `sublightspeedMax`, `manoeuvrability`, `manoeuvrabilityMax`, `sensors`, `sensorsMax`, `sensorRange`, `ECM`, `weapons`, `passengers`, `ships`, `vehicles`, `materials`);

                if ($stmt -> num_rows == 1) {
                    while ($stmt -> fetch()) {
                        foreach ($compareKeys as $value) {
                            if (${$value} != $entityArray[$value]) {
                                $newChange = 1;
                            }
                        }
                    }
                } else {
                    $newChange = 1;
                }

            // If no rows are returned or a difference is found, the query is updated and sent
                if ($newChange == 1) {
                    foreach ($entityArray as $entityKey => $entityValue) {
                        if (in_array($entityKey, $focusValues)) {
                            $queryChangesFocus .= "'" . $entityValue . "', ";
                        }
                    }

                    $changesStmt -> bind_param('sssssssssssssssssssssss', $y, $d, $h, $m, $s, $scannedBy, $entityArray['entityID'], $entityArray['hyperspeed'], $entityArray['hyperspeedMax'], $entityArray['sublightspeed'], $entityArray['sublightspeedMax'], $entityArray['manoeuvrability'], $entityArray['manoeuvrabilityMax'], $entityArray['sensors'], $entityArray['sensorsMax'], $entityArray['sensorRange'], $entityArray['sensorRangeMax'], $entityArray['ECM'], $entityArray['weapons'], $entityArray['passengers'], $entityArray['ships'], $entityArray['vehicles'], $entityArray['materials']);
                    $changesStmt -> execute();
                }

            // Updates and submits scanfocus then deletes old entires with the same entityID

                $delStmt = $mysqli -> prepare("DELETE FROM `scansfocus` WHERE `entityID` = ?");
                    echo $mysqli -> error;

                $delStmt -> bind_param('s', $entityArray['entityID']);
                $delStmt -> execute();

                $scansStmt -> bind_param('sssssssssssssssssssssss', $y, $d, $h, $m, $s, $scannedBy, $entityArray['entityID'], $entityArray['hyperspeed'], $entityArray['hyperspeedMax'], $entityArray['sublightspeed'], $entityArray['sublightspeedMax'], $entityArray['manoeuvrability'], $entityArray['manoeuvrabilityMax'], $entityArray['sensors'], $entityArray['sensorsMax'], $entityArray['sensorRange'], $entityArray['sensorRangeMax'], $entityArray['ECM'], $entityArray['weapons'], $entityArray['passengers'], $entityArray['ships'], $entityArray['vehicles'], $entityArray['materials']);
                $scansStmt -> execute();

                echo json_encode($_SESSION['error']['006']);
            }

        } else {
            if ($debug == 0) {
                echo json_encode($_SESSION['error']['007']);
            } else {
                echo $_SESSION['error']['007'];
            }
        }
    }
?>