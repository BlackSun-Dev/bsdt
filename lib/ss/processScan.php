<?php
    error_reporting(E_ALL);
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";

// Enables or disables debug mode.
// To enable debug mode, set $debug to 1
// Debug mode prevents all calls to the database unless purely to retrieve data
//      No changes to the DB can be made!

    if (isset($_SESSION['userId'])) {

        $debug = 0;

        if ($debug == 1) {
            echo "DEBUG MODE ENABLED <br /><br />";
        }

        $scanType = $_POST['scanType'];
        $scannedBy = $_POST['scannedBy'];
        $system = str_replace(',', '&#44;', $_POST['systemInput']);
        $xml = $_POST['xmlInput'];

        $xml = preg_replace('/&[^; ]{0,6}.?/e', "((substr('\\0',-1) == ';') ? '\\0' : '&amp;'.substr('\\0',1))", $xml);

        file_put_contents('tempXML/temp.xml', $xml);

        $xml = file_get_contents('tempXML/temp.xml');
        $xmlArray = xml2array($xml);

        if (isset($xmlArray['rss']['channel']['link']) == 'http://www.swcombine.com') {

    // If the input is a single grid only scan, this forces the data into the correct format to be read by the scanner.
            if ($scanType == 'grid') {
                $title = $xmlArray['rss']['channel']['title'];
                $titlePieces = explode(' ', $title);
                $x = substr($titlePieces['7'], 0, -1);
                $y = $titlePieces['8'];

                if (isset($xmlArray['rss']['channel']['item'][0])) {
                    foreach ($xmlArray['rss']['channel']['item'] as $itemKey => $itemValue) {
                        $tempArray = $xmlArray['rss']['channel']['item'][$itemKey];
                        unset($xmlArray['rss']['channel']['item'][$itemKey]);

                        foreach ($tempArray as $tempKey => $tempValue) {
                            if($tempKey == 'sharingSensors') {
                                $xmlArray['rss']['channel']['item'][$itemKey][$tempKey] = $tempValue;
                                $xmlArray['rss']['channel']['item'][$itemKey]['x'] = $x;
                                $xmlArray['rss']['channel']['item'][$itemKey]['y'] = $y;
                            } else {
                                $xmlArray['rss']['channel']['item'][$itemKey][$tempKey] = $tempValue;
                            }
                        }
                    }
                } else {
                    $tempArray = $xmlArray['rss']['channel']['item'];
                    unset($xmlArray['rss']['channel']['item']);

                    foreach ($tempArray as $tempKey => $tempValue) {
                        if ($tempKey == 'sharingSensors') {
                            $xmlArray['rss']['channel']['item'][0][$tempKey] = $tempValue;
                            $xmlArray['rss']['channel']['item'][0]['x'] = $x;
                            $xmlArray['rss']['channel']['item'][0]['y'] = $y;
                        } else {
                            $xmlArray['rss']['channel']['item'][0][$tempKey] = $tempValue;
                        }
                    }
                }
            }

        // Creates array of entities already tracked in current system and a master list of ALL entities for later comparison
            $systemEntities = array();
            $masterEntities = array();
            $changedEntities = array();
            $changedCompare = array();

            $query = "SELECT `entityID`, `System` FROM `scansbasic` ORDER BY `entityID`;";
            $result = $mysqli -> query($query);

            while ($row = $result -> fetch_assoc()) {
                if ($row['System'] == $system) {
                    $systemEntities[] = $row['entityID'];
                    $masterEntities[] = $row['entityID'];
                } else {
                    $masterEntities[] = $row['entityID'];
                }
            }

        // Gets all the change scans
            $query = "SELECT * FROM `changesbasic` ORDER BY `entityID`;";
            $result = $mysqli -> query($query);

            while ($row = $result -> fetch_assoc()) {
            // Gets list of Entities in change table for quick reference
                $changedEntities[] = $row['entityID'];
                $changeCounter = 0;

                if (isset($changedCompare[$row['entityID']][0])) {
                    $changeCounter = count($changedCompare[$row['entityID']]);
                    foreach ($row as $key => $value) {
                        $changedCompare[$row['entityID']][$changeCounter][$key] = $value;
                    }
                } else {
                    foreach ($row as $key => $value) {
                        $changedCompare[$row['entityID']][$changeCounter][$key] = $value;
                    }
                }
            }

        // Pulls all the items into their own array for quicker reference
            $entityArray = $xmlArray['rss']['channel']['item'];

        // Corrects bug with travel diection empty arrays and removes special chars from specific fields
            foreach ($entityArray as $key => $value) {
                if (is_array($value['travelDirection'])) {
                    $entityArray[$key]['travelDirection'] = '';
                    $entityArray[$key]['travelDirDescription'] = '';
                }
            }

        // Initiates queries to be sent
            $queryScans = "INSERT INTO `scansbasic` " .
                "(`years`, `days`, `hours`, `minutes`, `seconds`, `scannedBy`, `system`, `name`, `typeName`, `entityID`, `entityTypeName`, `hull`, `hullMax`, `shield`, `shieldMax`, `ionic`, `ionicMax`, `underConstruction`, `sharingSensors`, `x`, `y`, `travelDirection`, `travelDirDescription`, `ownerName`, `iffStatus`, `image`) VALUES ";

            $queryChanges = "INSERT INTO `changesbasic` " .
                "(`IndexNo`, `years`, `days`, `hours`, `minutes`, `seconds`, `scannedBy`, `system`, `name`, `typeName`, `entityID`, `entityTypeName`, `hull`, `hullMax`, `shield`, `shieldMax`, `ionic`, `ionicMax`, `underConstruction`, `sharingSensors`, `x`, `y`, `travelDirection`, `travelDirDescription`, `ownerName`, `iffStatus`, `image`) VALUES ";
            // Will track whether or not a change has been made. Will later be checked to see whether or not the query will be sent
                $hasChanged = 0;

            $queryDeleteOld = "";

        // Sets timestamp
            $y = $xmlArray['rss']['channel']['cgt']['years'];
            $d = $xmlArray['rss']['channel']['cgt']['days'];
            $h = $xmlArray['rss']['channel']['cgt']['hours'];
            $m = $xmlArray['rss']['channel']['cgt']['minutes'];
            $s = $xmlArray['rss']['channel']['cgt']['seconds'];

        // Does a quick check for duplicate entries. If found to be duplicate, re-directs user to scan page and generates an error
            $query = "SELECT `entityID` FROM `scansbasic` WHERE " .
                "(`years` = '" . $y . "' AND " .
                "`days` = '" . $d . "' AND " .
                "`hours` = '" . $h . "' AND " .
                "`minutes` = '" . $m . "' AND " .
                "`seconds` = '" . $s . "') AND " .
                "`entityID` = '" . $entityArray[0]['entityID'] . "'";

            $result = $mysqli -> query($query);

            if ($result -> num_rows != 0) {
                echo json_encode($_SESSION['error']['005']);
            } else {
            // Prepares statement to delete database entries;
                $stmtDeleteOld = $mysqli -> prepare("DELETE FROM `scansbasic` WHERE `entityID` = ? AND (`years` <> ? OR `days` <> ? OR `hours` <> ? OR `minutes` <> ? OR `seconds` <> ?)");
                    echo $mysqli -> error;

            // Loops through Entity Array and adds info to queries
                foreach ($entityArray as $value) {
                // Adds entity ID to query to Delete Old Entries - Keeps `scansbasic` current
                    if (in_array($value['entityID'], $systemEntities)) {
                    // Removes current entities from systemEntities if the entity is already present in the system
                        $key = array_search($value['entityID'], $systemEntities);
                        unset($systemEntities[$key]);
                    }

                    if (in_array($value['entityID'], $masterEntities)) {
                    // Deletes the old record if the entity has ever been scanned
                        /*$queryDeleteOld = "DELETE FROM `scansbasic` WHERE `entityID` = " .
                            "'" . $value['entityID'] . "' " .
                            "AND (`years` <> '" . $y . "' " .
                            "OR `days` <> '" . $d . "' " .
                            "OR `hours` <> '" . $h . "' " .
                            "OR `minutes` <> '" . $m . "' " .
                            "OR `seconds` <> '" . $s . "');";
                        if ($debug == 0) {
                            $mysqli -> query($queryDeleteOld);
                        }*/

                        if ($debug == 0) {
                            $stmtDeleteOld -> bind_param('ssssss', $value['entityID'], $y, $d, $h, $m, $s);
                            $stmtDeleteOld -> execute();
                        }
                    }

                // Compares scanned entities to entities in `changesbasic`. If they are different, the current scan of the entity will be added to `changesbasic` as a running document of all  tracked changes
                    if (in_array($value['entityID'], $changedEntities)) {
                    // Gets data from `changesbasic` ($changedCompare) of the most recent scan of this entity to compare.
                        $updateChange = 0;

                        if ($system != $changedCompare[$value['entityID']][count($changedCompare[$value['entityID']]) - 1]['system']) {
                            $updateChange = 1;
                        } else {
                            foreach ($value as $compareKey => $compareValue) {
                                if ($value[$compareKey] != $changedCompare[$value['entityID']][0][$compareKey]) {
                                    $updateChange = 1;
                                    if ($debug == 1) {
                                        echo $value['entityID'] . ':<br />' . $changedCompare[$value['entityID']][0][$compareKey] . ' = ' . $value[$compareKey] . '<br /><br />';
                                    }
                                }
                            }
                        }
                        
                        if ($updateChange == 1) {
                            $queryChanges .= "(NULL, '" . $y . "', '" . $d . "', '" . $h . "', '" . $m . "', '" . $s . "', '" . $scannedBy . "', '" . $system . "', ";
                            foreach ($value as $key => $value2) {
                                $queryChanges .= "'" . addslashes($value2) . "', ";
                            }
                            $queryChanges = substr($queryChanges, 0, -2) . '), ';
                            $hasChanged = 1;
                        }

                    } else {
                        $queryChanges .= "(NULL, '" . $y . "', '" . $d . "', '" . $h . "', '" . $m . "', '" . $s . "', '" . $scannedBy . "', '" . $system . "', ";
                        foreach ($value as $key => $value2) {
                            $queryChanges .= "'" . addslashes($value2) . "', ";
                        }
                        $queryChanges = substr($queryChanges, 0, -2) . '), ';
                        $hasChanged = 1;
                    }

                    $queryScans .= "('" . $y . "', '" . $d . "', '" . $h . "', '" . $m . "', '" . $s . "', '" . $scannedBy . "', '" . $system . "', ";
                    foreach ($value as $key => $value2) {
                        $queryScans .= "'" . addslashes($value2) . "', ";
                    }
                    $queryScans = substr($queryScans, 0, -2) . '), ';
                }

                $queryScans = substr($queryScans, 0, -2) . ';';

                $queryChanges = substr($queryChanges, 0, -2) . ';';

                if ($debug == 0) {
                    $mysqli -> query($queryScans);
                }

                if ($hasChanged == 1) {
                    if ($debug == 0) {
                        $mysqli -> query($queryChanges);
                    }
                }

                $queryLeftOver = '';
                foreach ($systemEntities as $value) {
                    $queryLeftOver = "UPDATE `scansbasic` SET `years` = '" . $y . "', `days` = '" . $d . "', `hours` = '" . $h . "', `minutes` = '" . $m . "', `seconds` = '" . $s . "', `system` = 'Unknown', `x` = '-', `y` = '-' WHERE `entityID` = '" . $value . "';";
                    if ($debug == 0) {
                        $mysqli -> query($queryLeftOver);
                    }

                    $queryLeftOver = "INSERT INTO `changesbasic` SELECT NULL, `scansbasic`.* FROM `scansbasic` WHERE `entityID` = '" . $value . "';";
                    if ($debug == 0) {
                        $mysqli -> query($queryLeftOver);
                    }
                }

                if ($debug == 0) {
                    echo json_encode($_SESSION['error']['006']);
                } else {
                echo $_SESSION['error']['006'];
                }
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