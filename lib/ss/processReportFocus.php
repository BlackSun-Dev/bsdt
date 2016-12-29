<?php
    error_reporting(E_ALL);
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";

    if (isset($_SESSION['userId'])) {

        $query = "SELECT * FROM `changesbasic` WHERE `entityID` = '" . $_POST['entityID'] . "' ORDER BY `years` DESC, `days` DESC, `hours` DESC, `minutes` DESC, `seconds` DESC;;";
        $result = $mysqli -> query($query);

        $rowCount = 1;

        if ($_POST['display'] == "text") {
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
                        <td>' . $entityID . '</td>
                        <td>' . $ownerName . '</td>
                        <td>' . $name . '</td>
                    </tr>';

                $rowCount++;
            }

            $reportList .= '</table>';
        } else {
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
                    $reportList .= "<tr class='even'>";
                } else {
                    $reportList .= "<tr class='odd'>";
                }

                $reportList .= '<td class="graphicImageContainer">
                        <img src="' . $image . '" class="center">
                    </td>

                    <td class="graphicContentContainer">
                        <div class="graphicColumnContainer">
                            ' . $typeName . '<br />
                            <span class="bold">"' . $name . '"</span><br />
                            ID#: ' . $entityID . '<br />
                            ' . $ownerName . '<br />

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

            $reportList .= '</table>';

        }


        $query = "SELECT * FROM `changesfocus` WHERE `entityID` = '" . $_POST['entityID'] . "';";
        $result = $mysqli -> query($query);

        if ($result -> num_rows >= 1) {
            $focusList = '<table class="reportTable">
            <tr>
                <td><b>Timestamp</b></td>
                <td><b>Passengers</b></td>
                <td><b>Ships</b></td>
                <td><b>Vehicles</b></td>
                <td><b>Materials</b></td>
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
                    $focusList .=  '<tr class="even">';
                } else {
                    $focusList .= '<tr class="odd">';
                }

                $focusList .= '<td>' . $timeStamp . '</td>
                        <td>' . $passengers . '</td>
                        <td>' . $ships . '</td>
                        <td>' . $vehicles . '</td>
                        <td>' . $materials . '</td>
                    </tr>';

                $rowCount++;
            }

            $focusList .='</table>';
        } else {
            $focusList = '<span class="alert">There are no focus scans associated with this Entity ID</span>';
        }

        $json_array = array($reportList, $focusList);
        
        echo json_encode($json_array);
    }
?>