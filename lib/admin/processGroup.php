<?php
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";

    if (isset($_SESSION['userId'])) {
		if (isset($_POST["mode"]) == "delete") {
			if ($_POST['groupID'] == 1) {
				echo json_encode(array("error" => $_SESSION['error']['011']));
			} else {
				$stmt = $mysqli -> prepare("DELETE FROM `groups` WHERE `GroupID` = ?");
					echo $mysqli -> error;
				$stmt -> bind_param('s', $_POST['groupID']);
				$stmt -> execute();			

				$stmt = $mysqli -> prepare("UPDATE `users` SET `Group` = '1' WHERE `Group` = ?");
					echo $mysqli -> error;
				$stmt -> bind_param('s', $_POST['groupID']);
				$stmt -> execute();

				echo json_encode(array("success"));
			}
		}

		if (isset($_POST["mode"]) == "update") {
			if ($_POST['groupID'] == 1) {
				echo json_encode(array("error" => $_SESSION['error']['011']));
			} else {
				$stmt = $mysqli -> prepare("UPDATE `groups` SET `Systems` = ? WHERE `GroupID` = ?");
					echo $mysqli -> error;
				$systemList = str_replace(',', '&#44;', $_POST['systemList']);
				$stmt -> bind_param('ss', $systemList, $_POST['groupID']);
				$stmt -> execute();

				echo json_encode(array("success"));
			}
		}

		if (isset($_POST["mode"]) == "new") {
			$stmt = $mysqli -> prepare("SELECT `GroupName` FROM `groups` WHERE `GroupName` = ?");
				echo $mysqli -> error;
			$stmt -> bind_param('s', $_POST['groupName']);
			$stmt -> execute();

			$stmt -> store_result();
			$stmt -> bind_result($groupName);

			if ($stmt -> num_rows == 0) {
				$stmt = $mysqli -> prepare("INSERT INTO `groups` VALUES (Null, ?, ?)");
					echo $mysqli -> error;
				$stmt -> bind_param('ss', $_POST['groupName'], $_POST['groupSystems']);
				$stmt -> execute();

				header("Location: groupManagement");
			} else {
				header("Location: groupManagement?error=010");
			}
		}
	}
?>