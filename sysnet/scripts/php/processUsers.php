<?php
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";

    if (isset($_SESSION['userLogged'])) {

		if (varCheck($_POST['mode']) == "update" && varCheck($_POST["actions"]) == "remove") {
			foreach($_POST["users"] as $value) {
				$stmt = $mysqli -> prepare("DELETE FROM `users` WHERE `IndexNo` = ?");
					echo $mysqli -> error;
				$stmt -> bind_param('s', $value);
				$stmt -> execute();
			}

			header("Location: userManagement");
		} else if (varCheck($_POST['mode']) == "update" && varCheck($_POST["actions"]) == "transfer") {
			if (count($_POST["users"]) > 1) {
				header("Location: userManagement?error=001");
			} else {
				$stmt = $mysqli -> prepare("UPDATE `users` SET `Permissions` = '1,1,1,1' WHERE `IndexNo` = ?");
					echo $mysqli -> error;
				$stmt -> bind_param('s', $_POST['users'][0]);
				$stmt -> execute();

				$stmt = $mysqli -> prepare("UPDATE `users` SET `Permissions` = '0,1,1,1' WHERE `IndexNo` = ?");
					echo $mysqli -> error;
				$stmt -> bind_param('s', $_SESSION['userIndex']);
				$stmt -> execute();

				$_SESSION['userPermissions']['owner'] = 0;

				header("Location: userManagement");
			}
		} else if (varCheck($_POST['mode']) == 'update' && varCheck($_POST["actions"]) == 'group') {
			$errorOut = 0;

			foreach ($_POST['users'] as $value){
				$stmt = $mysqli -> prepare("SELECT `Permissions` FROM `users` WHERE `IndexNo` = ?");
					echo $mysqli -> error;
				$stmt -> bind_param('s', $value);
				$stmt -> execute();
				$stmt -> store_result();
				$stmt -> bind_result($stmt_permissions);

				while ($stmt -> fetch()) {
					$permPieces = explode(',', $stmt_permissions);
				}

				if ($permPieces[1] != 1) {
					$stmt = $mysqli -> prepare("UPDATE `users` SET `Group` = ? WHERE `IndexNo` = ?");
						echo $mysqli -> error;
					$stmt -> bind_param('ss', $_POST['addGroup'], $value);
					$stmt -> execute();
				} else {
					$errorOut = 1;
				}

				if ($errorOut == 1) {
					header("Location: userManagement?error=009");
				} else {
					header("Location: userManagement");
				}
			}
		} else if (varCheck($_POST['mode']) == 'update') {
			foreach ($_POST["users"] as $value) {
				$stmt = $mysqli -> prepare("UPDATE `users` SET `Permissions` = ? WHERE `IndexNo` = ?");
					echo $mysqli -> error;
				$stmt -> bind_param('ss', $_POST['actions'], $value);
				$stmt -> execute();
			}

			header("Location: userManagement");
		}


		if (varCheck($_POST['mode']) == 'new') {
			$stmt = $mysqli -> prepare("SELECT `Username` FROM `users` WHERE `Username` = ?");
				echo $mysqli -> error;
			$stmt -> bind_param('s', $_POST['userName']);
			$stmt -> execute();
			$stmt -> store_result();

			if ($stmt -> num_rows == 0) {
				if ($_POST['newUserPrivs'] == '0,1,1,1') {
					$_POST['newUserGroup'] = 0;
				}

				$stmt = $mysqli -> prepare("INSERT INTO `users` VALUES (Null, ?, ?, ?, ?)");
					echo $mysqli -> error;
					$tempPassword = md5('prefix' . md5($_POST['password']) . 'suffix');
				$stmt -> bind_param('ssss', $_POST['userName'], $tempPassword, $_POST['newUserGroup'], $_POST['newUserPrivs']);
				$stmt -> execute();

				header("Location: userManagement");
			} else {
				header("Location: userManagement?error=008");
			}
		}

		if (varCheck($_POST['mode']) == 'password') {
			$password = md5('prefix' . md5($_POST['currentPassword']) . 'suffix');

			$stmt = $mysqli -> prepare("SELECT `Username` FROM `users` WHERE `IndexNo` = ? AND `Password` = ?");
				echo $mysqli -> error;
			$stmt -> bind_param('ss', $_SESSION['userIndex'], $password);
			$stmt -> execute();
			$stmt -> store_result();
			
			if ($stmt -> num_rows == 1) {
				if ($_POST['newPassword'] == $_POST['confirmPassword']) {
					$password = md5('prefix' . md5($_POST['newPassword']) . 'suffix');

					$stmt = $mysqli -> prepare("UPDATE `users` SET `Password` = ? WHERE `IndexNo` = ?");
						echo $mysqli -> error;
					$stmt -> bind_param('ss', $password, $_SESSION['userIndex']);
					$stmt -> execute();

					header("Location: changePassword?error=004");
				} else {
					header("Location: changePassword?error=003");
				}
			} else {
				header("Location: changePassword?error=002");
			}
		}
	}
?>