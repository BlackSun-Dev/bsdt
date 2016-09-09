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

// Enables or disables debug mode.
// To enable debug mode, set $debug to 1
// Debug mode prevents all calls to the database unless purely to retrieve data
//      No changes to the DB can be made!
    
    if (isset($_SESSION['userLogged'])) {

        $debug = 0;

        if ($debug == 1) {
            echo "DEBUG MODE ENABLED <br /><br />";
        }

    // Gets entity IDs for retrieving entity data
        if (varCheck($_POST['iffValue']) == 'undefined' && $_POST['entityValue'] == 'none' && $_POST['ownerValue'] == 'none' && $_POST['systemValue'] == 'none' && $_POST['fromYearValue'] == 'none') {
            echo json_encode(array("status" => "error", "error" => $_SESSION['error']['012']));
        } else {
            $query = "SELECT `entityID` FROM `changesbasic` WHERE ";

            if (varCheck($_POST['iffValue']) == 'all' || varCheck($_POST['iffValue']) == 'undefined') {
                $query .= "`iffStatus` IS NOT NULL";
            } else {
                $query .= "`iffStatus` = '" . $_POST['iffValue'] . "'";
            }

            if ($_POST['entityValue'] != "none") {
                $query .= " && `entityTypeName` = '" . $_POST['entityValue'] . "'";
            }

            if ($_POST['ownerValue'] != "none") {
                $query .= " && `ownerName` = '" . $_POST['ownerValue'] . "'";
            }

            if ($_POST['systemValue'] != "none") {
                $query .= " && `system` = '" . $_POST['systemValue'] . "'";
            }

            if ($_POST['fromYearValue'] != "none") {
                $fromTime = (((($_POST['fromYearValue'] * 365) * 24) * 60) * 60) + ((($_POST['fromDayValue'] * 24) * 60) * 60);
                $toTime = (((($_POST['toYearValue'] * 365) * 24) * 60) * 60) + ((($_POST['toDayValue'] * 24) * 60) * 60);
                $query .= " && ((((((`years` * 365) * 24) * 60) * 60) + (((`days` * 24) * 60) * 60)) > " . $fromTime . ")";
                $query .= " && ((((((`years` * 365) * 24) * 60) * 60) + (((`days` * 24) * 60) * 60)) < " . $toTime . ")";

                if ($debug == 1) {
                    echo '$fromTime = ' . $fromTime . '<br>$toTime = ' . $toTime . '<br>';
                }
            }

            if ($debug == 1) {
                echo '$_POST[\'entityValue\']: ' . $_POST['entityValue'] . '<br>$_POST[\'ownerValue\']: ' . $_POST['ownerValue'] . '<br>$_POST[\'systemValue\']: ' . $_POST['systemValue'] . '<br>$query: ' . $query . '<br><br>';
            }

            $result = $mysqli -> query($query);

            $entityList = '';

            while ($row = $result -> fetch_assoc()) {
                $entityList .= $row['entityID'] . ',';
            }

            $entityList = substr($entityList, 0, -1);

            $entities = array();
            $entityCount = 0;
            $entityHistory = array();
            $historyCount = 0;

            $query = "SELECT * FROM `changesbasic` WHERE FIND_IN_SET(`entityID`, '" . $entityList . "') ORDER BY FIELD(`iffStatus`, 'Enemy', 'Neutral', 'Friend'), `entityTypeName`, `entityID` ASC, `years` DESC, `days` DESC, `hours` DESC, `minutes` DESC, `seconds` DESC;";
            $result = $mysqli -> query($query);
            while ($row = $result -> fetch_assoc()) {
                $timeStamp = 'Y' . $row['years'] . ' D' . $row['days'] . '<br />' . $row['hours'] . ':' . $row['minutes'] . ':' . $row['seconds'];

                if (isset($entityHistory[$row['entityID']])) {
                    $historyCount = count($entityHistory[$row['entityID']]);
                } else {
                    $historyCount = 0;
                }

                $entityHistory[$row['entityID']][$historyCount]['timeStamp'] = $timeStamp;
                $entityHistory[$row['entityID']][$historyCount]['system'] = $row['system'];
                $entityHistory[$row['entityID']][$historyCount]['iff'] = $row['iffStatus'];
                $entityHistory[$row['entityID']][$historyCount]['x'] = $row['x'];
                $entityHistory[$row['entityID']][$historyCount]['y'] = $row['y'];
                $entityHistory[$row['entityID']][$historyCount]['type'] = $row['typeName'];
                $entityHistory[$row['entityID']][$historyCount]['entityID'] = $row['entityID'];
                $entityHistory[$row['entityID']][$historyCount]['owner'] = $row['ownerName'];
                $entityHistory[$row['entityID']][$historyCount]['name'] = $row['name'];

                if (multi_in_array($row['entityID'], $entities) == false) {
                    $entities[$entityCount]['timeStamp'] = $timeStamp;
                    $entities[$entityCount]['iff'] = $row['iffStatus'];
                    $entities[$entityCount]['type'] = $row['typeName'];
                    $entities[$entityCount]['entityID'] = $row['entityID'];
                    $entities[$entityCount]['owner'] = $row['ownerName'];
                    $entities[$entityCount]['name'] = $row['name'];

                    $entityCount++;
                }
            }

            $rowCount = 0;

            $reportList = '<table class="reportTable" cellspacing="0">
                <tr>
                    <td style="width: 50px;"><b>+ / -</b></td>
                    <td><b>IFF</b></td>
                    <td><b>Type</b></td>
                    <td><b>Entity ID</b></td>
                    <td><b>Owner</b></td>
                    <td><b>Name</b></td>
                </tr>';

            foreach ($entities as $value) {
                if ($rowCount % 2 == 0) {
                    $background = "even";
                } else {
                    $background = "odd";
                }

                $iffLower = strtolower($value['iff']);

        //<button style="width: 30px;" class="button ui-corner-all dropShadow" id="button{$value['entityID']}" entityID="{$value['entityID']}">+</button>

                $reportList .= '<tr class="' . $background . '">
                        <td><button style="width: 30px;" class="historyButton expand button ui-corner-all dropShadow" entityID="' . $value['entityID'] . '"><span class="ui-icon ui-icon-plus"></span></button></td>
                        <td style="padding: 0 5px 0 5px;"><span class="$iffLower">' . $value['iff'] . '</span></td>
                        <td>' . $value['type'] . '</td>
                        <td><a href="entityHistory' . $value['entityID'] . '" target="new">' . $value['entityID'] . '</a></td>
                        <td>' . $value['owner'] . '</td>
                        <td>' . $value['name'] . '</td>
                    </tr>';

                $reportList .= '<tr>
                        <td style="display: none;" id="table' . $value['entityID'] . '" colspan="6">
                            <table class="reportTable" cellspacing="0">
                                <tr>
                                    <td class="$background"><b>Timestamp</b></td>
                                    <td class="$background"><b>System</b></td>
                                    <td class="$background"><b>IFF</b></td>
                                    <td class="$background"><b>Coords</b></td>
                                    <td class="$background"><b>Type</b></td>
                                    <td class="$background"><b>Entity ID</b></td>
                                    <td class="$background"><b>Owner</b></td>
                                    <td class="$background"><b>Name</b></td>
                                </tr>';
                
                for($a = 0; $a < count($entityHistory[$value['entityID']]); $a++) {
                    $timeStamp = $entityHistory[$value['entityID']][$a]['timeStamp'];
                    $system = $entityHistory[$value['entityID']][$a]['system'];
                    $iffStatus = $entityHistory[$value['entityID']][$a]['iff'];
                        $iffStatusLower = strtolower($iffStatus);
                    $x = $entityHistory[$value['entityID']][$a]['x'];
                    $y = $entityHistory[$value['entityID']][$a]['y'];
                    $type = $entityHistory[$value['entityID']][$a]['type'];
                    $entityID = $entityHistory[$value['entityID']][$a]['entityID'];
                    $owner = $entityHistory[$value['entityID']][$a]['owner'];
                    $name = $entityHistory[$value['entityID']][$a]['name'];

                    $reportList .= '<tr>
                                <td class="$background">' . $timeStamp . '</td>
                                <td class="$background">' . $system . '</td>
                                <td class="$background"><span class="' . $iffStatusLower . '">' . $iffStatus . '</span></td>
                                <td class="$background">' . $x . ', ' . $y . '</td>
                                <td class="$background">' . $type . '</td>
                                <td class="$background"><a href="entityHistory' . $entityID . '" target="new">' . $entityID . '</a></td>
                                <td class="$background">' . $owner . '</td>
                                <td class="$background">' . $name . '</td>
                            </tr>';
                }

                $reportList .= '</table></td>';

                    $rowCount++;
            }

            $reportList .= '</tr></table>';

            if ($debug == 0) {
                echo json_encode(array($reportList));
            }
        }
    }
?>