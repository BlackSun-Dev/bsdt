<?php
    error_reporting(E_ALL);
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";

    if (isset($_SESSION['userId'])) {

    // Does string replace to clean up variable
        $_POST['system'] = str_replace(',', '&#44;', $_POST['system']);

    // Initiates Query
        $query = "SELECT * FROM `scansbasic`";

    // Determines the WHERE
        if ($_POST['iff'] != 'all') {
            $where = " WHERE `iffStatus` = '" . $_POST['iff'] . "'";
        } else {
            $where = "";
        }

        if ($where == '' && $_POST['system'] != 'all') {
            $where = " WHERE `system` = '" . $_POST['system'] . "'";
        } else if ($where != '' && $_POST['system'] != 'all') {
            $where .= " AND `system` = '" . $_POST['system'] . "'";
        }

        if (isset($_POST['time']) != 'undefined') {
            if ($_POST['time'] != 'all') {
            // Generates combine time
                $year = date('y');
                $month = date('n');
                $day = date('z') + 94;
                $hours = date('H') + 17;
                $minutes = date('i');
                $seconds = date('s');

                if ($_POST['time'] == '1') {
                    $timeStamp = mktime($hours - 24, $minutes, $seconds, $month, $day, $year);

                    $combineYear = (((date('y', $timeStamp) * 365) * 24) * 60) * 60;
                    $combineDay = ((date('z', $timeStamp) * 24) * 60) * 60;
                    $combineHour = (date('H', $timeStamp) * 60) * 60;
                    $combineMinute = date('i', $timeStamp) * 60;
                    $combineSecond = date('s', $timeStamp);

                    $totalSeconds = $combineYear + $combineDay + $combineHour + $combineMinute + $combineSecond;
                    $where .= " AND (((((`years` * 365) * 24) * 60) * 60) + (((`days` * 24) * 60) * 60) + ((`hours` * 60) * 60) + (`minutes` * 60) + `seconds`) >= " . $totalSeconds;
                } else if($_POST['time'] == '2') {
                    $timeStamp = mktime($hours - 48, $minutes, $seconds, $month, $day, $year);

                    $combineYear = (((date('y', $timeStamp) * 365) * 24) * 60) * 60;
                    $combineDay = ((date('z', $timeStamp) * 24) * 60) * 60;
                    $combineHour = (date('H', $timeStamp) * 60) * 60;
                    $combineMinute = date('i', $timeStamp) * 60;
                    $combineSecond = date('s', $timeStamp);

                    $totalSeconds = $combineYear + $combineDay + $combineHour + $combineMinute + $combineSecond;
                    $where .= " AND (((((`years` * 365) * 24) * 60) * 60) + (((`days` * 24) * 60) * 60) + ((`hours` * 60) * 60) + (`minutes` * 60) + `seconds`) >= " . $totalSeconds;
                } else if($_POST['time'] == '7') {
                    $timeStamp = mktime($hours, $minutes, $seconds, $month, $day - 7, $year);

                    $combineYear = (((date('y', $timeStamp) * 365) * 24) * 60) * 60;
                    $combineDay = ((date('z', $timeStamp) * 24) * 60) * 60;
                    $combineHour = (date('H', $timeStamp) * 60) * 60;
                    $combineMinute = date('i', $timeStamp) * 60;
                    $combineSecond = date('s', $timeStamp);

                    $totalSeconds = $combineYear + $combineDay + $combineHour + $combineMinute + $combineSecond;
                    $where .= " AND (((((`years` * 365) * 24) * 60) * 60) + (((`days` * 24) * 60) * 60) + ((`hours` * 60) * 60) + (`minutes` * 60) + `seconds`) >= " . $totalSeconds;
                } else if($_POST['time'] == '30') {
                    $timeStamp = mktime($hours, $minutes, $seconds, $month, $day - 30, $year);

                    $combineYear = (((date('y', $timeStamp) * 365) * 24) * 60) * 60;
                    $combineDay = ((date('z', $timeStamp) * 24) * 60) * 60;
                    $combineHour = (date('H', $timeStamp) * 60) * 60;
                    $combineMinute = date('i', $timeStamp) * 60;
                    $combineSecond = date('s', $timeStamp);

                    $totalSeconds = $combineYear + $combineDay + $combineHour + $combineMinute + $combineSecond;
                    $where .= " AND (((((`years` * 365) * 24) * 60) * 60) + (((`days` * 24) * 60) * 60) + ((`hours` * 60) * 60) + (`minutes` * 60) + `seconds`) >= " . $totalSeconds;
                }
            }
        }

        $query .= $where;

    // Creates ORDER BY ASC/DESC based on user input
        $orderBy = " ORDER BY";
        if ($_POST['order'] == 'system') {
            $orderBy .= orderBySort("`system`", $_POST['ascOrDesc']);
        } else if ($_POST['order'] == 'iff') {
            $orderBy .= orderBySort("FIELD(`iffStatus`, 'Enemy', 'Neutral', 'Friend')", $_POST['ascOrDesc']);
        } else if ($_POST['order'] == 'coords') {
            $orderBy .= orderBySort("`x`, `y`", $_POST['ascOrDesc']);
        } else if ($_POST['order'] == 'type') {
            $orderBy .= orderBySort("`typeName`", $_POST['ascOrDesc']);
        } else if ($_POST['order'] == 'id') {
            $orderBy .= orderBySort("`entityID`", $_POST['ascOrDesc']);
        } else if ($_POST['order'] == 'owner') {
            $orderBy .= orderBySort("`ownerName`", $_POST['ascOrDesc']);
        } else if ($_POST['order'] == 'name') {
            $orderBy .= orderBySort("`name`", $_POST['ascOrDesc']);
        }
        $query .= $orderBy;

        $result = $mysqli -> query($query);

        $rowCount = 1;

    // Creates the layout for the text version
        if ($_POST['display'] == 'text') {
            $reportList = '<table class="reportTable">
                <tr>
                    <td><b>Timestamp</b></td>
                    <td><b>System</b></td>
                    <td><b>IFF</b></td>
                    <td><b>Coords</b></td>
                    <td><b>Type</b></td>
                    <td><b>Entity ID</b></td>
                    <td><b>Owner</b></td>
                    <td><b>Name</b></td>
                </tr>';

            while ($row = $result -> fetch_assoc()) {
                foreach($row as $rowKey => $rowValue) {
                    if ($rowKey == 'hours' || $rowKey == 'minutes' || $rowKey == 'seconds') {
                        if (strlen($rowValue) == 1) {
                            $rowValue = '0' . $rowValue;
                        }
                    }

                    $$rowKey = $rowValue;
                }
                $timeStamp = 'Y' . $years . ' D' . $days . '<br />' . $hours . ':' . $minutes . ':' . $seconds;

                $iffStatusLower = strtolower($iffStatus);

                if ($rowCount % 2 == 0) {
                    $reportList .= '<tr class="even">';
                } else {
                    $reportList .= '<tr class="odd">';
                }

                $reportList .= '<td>' . $timeStamp . '</td>
                        <td>' . $system . '</td>
                        <td><span class="' . $iffStatusLower . '">' . $iffStatus . '</span></td>
                        <td>' . $x . ', ' . $y . '</td>
                        <td>' . $entityTypeName . '</td>
                        <td><a href="entityHistory' . $entityID . '" target="new">' . $entityID . '</a></td>
                        <td><a href="http://www.swcombine.com/members/messages/?mode=send&tHand=' . str_replace(' ', '+', $ownerName) . '" target="new">'. $ownerName . '</a></td>
                        <td>' . $name . '</td>
                    </tr>';

                $rowCount++;
            }

            $reportList .= '</table>';
        } else {
    // Creates the layout for the graphic version
            if ($_POST['display'] == 'graphic') {
                $reportList = '<table class="reportTable">';

                while ($row = $result -> fetch_assoc()) {
                    foreach($row as $rowKey => $rowValue) {
                        if ($rowKey == 'hours' || $rowKey == 'minutes' || $rowKey == 'seconds') {
                            if (strlen($rowValue) == 1) {
                                $rowValue = '0' . $rowValue;
                            }
                        }

                        $$rowKey = $rowValue;
                    }
                    $timeStamp = 'Y' . $years . ' D' . $days . ' ' . $hours . ':' . $minutes . ':' . $seconds;

                // Determines percentages for hull, shield, and ionic
                    if ($hullMax != 0) {
                        $hullPerc = floor(100*($hull/$hullMax));
                    } else {
                        $hullPerc = 0;
                    }

                    if ($shieldMax != 0) {
                        $shieldPerc = floor(100*($shield/$shieldMax));
                    } else {
                        $shieldPerc = 0;
                    }

                    if ($ionicMax != 0) {
                        $ionicPerc = floor(100*($ionic/$ionicMax));
                    } else {
                        $ionicPerc = 0;
                    }

                // Creates a lowercase version of iffStatus to be referenced for span style
                    $iffStatusLower = strtolower($iffStatus);

                // Determines whether or not the construction icon will be displayed
                    if ($underConstruction == "Yes") {
                        $constructionDisplay = "Under Construction";
                    } else {
                        $constructionDisplay = "";
                    }

                    if ($rowCount % 2 == 0) {
                        $reportList .= '<tr class="even">';
                    } else {
                        $reportList .= '<tr class="odd">';
                    }

                    $reportList .= '<td class="graphicImageContainer">
                            <img src="' . $image . '" class="center">
                        </td>

                        <td class="graphicContentContainer">
                            <div class="graphicColumnContainer">
                                ' . $typeName . '<br />
                                <span class="bold">"' . $name . '"</span><br />
                                <a href="entityHistory' . $entityID . '" target="new">ID#: ' . $entityID . '</a><br />
                                <a href="http://www.swcombine.com/members/messages/?mode=send&tHand=' . str_replace(' ', '+', $ownerName) . '" target="new">'. $ownerName . '</a><br />

                                <div class="timeStamp">
                                    ' . $timeStamp . '<br />
                                    ' . $system . ' (' . $x . ', ' . $y . ')<br />
                                    <span class="' . $iffStatusLower . '">' . $iffStatus . '</span><br />
                                    ' . $constructionDisplay . '
                                </div>
                            </div>
                        </td>

                        <td class="graphicProgressBarContainer">
                            <div class="progressBarContainer">
                                <div class="progressBar" perc="' . $hullPerc . '"></div>
                                <div class="progressBarText">Hull: ' . $hull . ' / ' . $hullMax . '</div>
                            </div>

                            <div class="progressBarContainer">
                                <div class="progressBar" perc="' . $shieldPerc . '"></div>
                                <div class="progressBarText">Shield: ' . $shield . ' / ' . $shieldMax . '</div>
                            </div>

                            <div class="progressBarContainer">
                                <div class="progressBar" perc="' . $ionicPerc . '"></div>
                                <div class="progressBarText">Ionic: ' . $ionic . ' / ' . $ionicMax . '</div>
                            </div>
                        </td>
                    </tr>';

                    $rowCount++;
                }

                $reportList .= "</table>";
            }
        }

        echo json_encode($reportList);
    }
?>
