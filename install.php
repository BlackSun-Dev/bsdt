<?php
require "lib/autoloader.php";

include "lib/style/header.php";
include "lib/style/logo.php";

$success = false;
$installed = Database::installCheck();

if(!$installed){
	if(isset($_POST['username'])){
		$mysqli = Database::getInstance();

		$changesBasic = 'CREATE TABLE IF NOT EXISTS `changesbasic` (
			`IndexNo` int(11) NOT NULL AUTO_INCREMENT,
			`years` mediumint(4) NOT NULL,
			`days` mediumint(4) NOT NULL,
			`hours` mediumint(4) NOT NULL,
			`minutes` mediumint(4) NOT NULL,
			`seconds` mediumint(4) NOT NULL,
			`scannedBy` varchar(50) NOT NULL,
			`system` varchar(50) NOT NULL,
			`name` varchar(50) NOT NULL,
			`typeName` varchar(50) NOT NULL,
			`entityID` mediumint(9) NOT NULL,
			`entityTypeName` varchar(50) NOT NULL,
			`hull` smallint(6) NOT NULL,
			`hullMax` smallint(6) NOT NULL,
			`shield` smallint(6) NOT NULL,
			`shieldMax` smallint(6) NOT NULL,
			`ionic` smallint(6) NOT NULL,
			`ionicMax` smallint(6) NOT NULL,
			`underConstruction` varchar(3) NOT NULL,
			`sharingSensors` varchar(3) NOT NULL,
			`x` tinyint(4) NOT NULL,
			`y` tinyint(4) NOT NULL,
			`travelDirection` varchar(5) NOT NULL,
			`travelDirDescription` varchar(5) NOT NULL,
			`ownerName` varchar(50) NOT NULL,
			`iffStatus` varchar(7) NOT NULL,
			`image` text NOT NULL,
			PRIMARY KEY (`IndexNo`),
			KEY `IndexNo` (`IndexNo`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

		$changesFocus = 'CREATE TABLE IF NOT EXISTS `changesfocus` (
			`years` mediumint(4) NOT NULL,
			`days` mediumint(4) NOT NULL,
			`hours` mediumint(4) NOT NULL,
			`minutes` mediumint(4) NOT NULL,
			`seconds` mediumint(4) NOT NULL,
			`scannedBy` varchar(50) NOT NULL,
			`entityID` mediumint(9) NOT NULL,
			`hyperspeed` varchar(10) NOT NULL,
			`hyperspeedMax` varchar(10) NOT NULL,
			`sublightspeed` varchar(10) NOT NULL,
			`sublightspeedMax` varchar(10) NOT NULL,
			`manoeuvrability` varchar(10) NOT NULL,
			`manoeuvrabilityMax` varchar(10) NOT NULL,
			`sensors` varchar(10) NOT NULL,
			`sensorsMax` varchar(10) NOT NULL,
			`sensorRange` varchar(10) NOT NULL,
			`sensorRangeMax` varchar(10) NOT NULL,
			`ECM` varchar(10) NOT NULL,
			`weapons` text NOT NULL,
			`passengers` mediumint(10) NOT NULL,
			`ships` mediumint(9) NOT NULL,
			`vehicles` mediumint(9) NOT NULL,
			`materials` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';

		$groups = 'CREATE TABLE IF NOT EXISTS `groups` (
			`GroupID` int(11) NOT NULL AUTO_INCREMENT,
			`GroupName` varchar(50) NOT NULL,
			`Systems` text NOT NULL,
			PRIMARY KEY (`GroupID`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

		$groupsInsert = 'INSERT INTO `groups` (`GroupID`, `GroupName`, `Systems`) VALUES
		(1, "No Group", "(Default Group)"),
		(2, "Unknown", "Unknown");';

		$scansBasic = 'CREATE TABLE IF NOT EXISTS `scansbasic` (
			`years` mediumint(4) NOT NULL,
			`days` mediumint(4) NOT NULL,
			`hours` mediumint(4) NOT NULL,
			`minutes` mediumint(4) NOT NULL,
			`seconds` mediumint(4) NOT NULL,
			`scannedBy` varchar(50) NOT NULL,
			`system` varchar(50) NOT NULL,
			`name` varchar(50) NOT NULL,
			`typeName` varchar(50) NOT NULL,
			`entityID` mediumint(9) NOT NULL,
			`entityTypeName` varchar(50) NOT NULL,
			`hull` smallint(6) NOT NULL,
			`hullMax` smallint(6) NOT NULL,
			`shield` smallint(6) NOT NULL,
			`shieldMax` smallint(6) NOT NULL,
			`ionic` smallint(6) NOT NULL,
			`ionicMax` smallint(6) NOT NULL,
			`underConstruction` varchar(3) NOT NULL,
			`sharingSensors` varchar(3) NOT NULL,
			`x` tinyint(4) NOT NULL,
			`y` tinyint(4) NOT NULL,
			`travelDirection` varchar(5) NOT NULL,
			`travelDirDescription` varchar(5) NOT NULL,
			`ownerName` varchar(50) NOT NULL,
			`iffStatus` varchar(7) NOT NULL,
			`image` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';

		$scansFocus = 'CREATE TABLE IF NOT EXISTS `scansfocus` (
			`years` mediumint(4) NOT NULL,
			`days` mediumint(4) NOT NULL,
			`hours` mediumint(4) NOT NULL,
			`minutes` mediumint(4) NOT NULL,
			`seconds` mediumint(4) NOT NULL,
			`scannedBy` varchar(50) NOT NULL,
			`entityID` mediumint(9) NOT NULL,
			`hyperspeed` varchar(10) NOT NULL,
			`hyperspeedMax` varchar(10) NOT NULL,
			`sublightspeed` varchar(10) NOT NULL,
			`sublightspeedMax` varchar(10) NOT NULL,
			`manoeuvrability` varchar(10) NOT NULL,
			`manoeuvrabilityMax` varchar(10) NOT NULL,
			`sensors` varchar(10) NOT NULL,
			`sensorsMax` varchar(10) NOT NULL,
			`sensorRange` varchar(10) NOT NULL,
			`sensorRangeMax` varchar(10) NOT NULL,
			`ECM` varchar(10) NOT NULL,
			`weapons` text NOT NULL,
			`passengers` mediumint(10) NOT NULL,
			`ships` mediumint(9) NOT NULL,
			`vehicles` mediumint(9) NOT NULL,
			`materials` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';

		$users = 'CREATE TABLE IF NOT EXISTS `users` (
			`IndexNo` int(11) NOT NULL AUTO_INCREMENT,
			`Username` varchar(25) NOT NULL,
			`Password` text NOT NULL,
			`Group` varchar(50) DEFAULT NULL,
			`Permissions` text NOT NULL,
			PRIMARY KEY (`IndexNo`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

		$mysqli->query($changesBasic);
		$mysqli->query($changesFocus);
		$mysqli->query($groups);
		$mysqli->query($groupsInsert);
		$mysqli->query($scansBasic);
		$mysqli->query($scansFocus);
		$mysqli->query($users);

		$password = md5('prefix' . md5($_POST['password']) . 'suffix');
		$username = $_POST['username'];

	  $query = $mysqli->stmt_init();
		if($query -> prepare('INSERT INTO `users` (`Username`, `Password`, `Group`, `Permissions`) VALUES(?, ?, "0", "1,1,1,1")')){
			$query -> bind_param("ss", $username, $password);
			$query -> execute();
			$mysqli->close();
			$success = true;
		}
	}
	if($success == true){
		?>
		<div class="contentContainer center">
			<div class="mainContent ui-corner-all dropShadow center textCenter">
				<div class="textLeft" style="width: 100%;"><h3>BSDT Install</h3></div>
				<hr class="left">
				<span class="alert">BSDT has been installed.</span>
				<p>Please delete this file for security precautions. <a href="login.php">Navigate to login page</a>.</p>
			</div>
		</div>
		<?php
	}
	else {
		?>

		<body>
			<div class="contentContainer center">
				<div class="mainContent ui-corner-all dropShadow center textCenter">
					<div class="textLeft" style="width: 100%;"><h3>BSDT Install</h3></div>
					<hr class="left">
					<ol>
						<li>Create a user with full admin priviledges. Database credentials need to be completed manually.</li>

						<li>Fill out form below and click "Install"<br><br>
							<form method="POST" action="install.php">
								<div style="width: 325px;" class="center textRight">
									Admin Username: <input type="text" name="username" value="Admin Username" class="formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);"><br />
									Admin Password: <input type="text" name="password" value="Admin Password" class="formField ui-corner-all dropShadow" onfocus="formFocusPassword(this);" onblur="formBlurPassword(this);"><br />
								</div>
								<input type="submit" value="Install" name="install" class="button ui-corner-all dropShadow">
							</form>
						</li>
					</ol>
				</div>
			</div>
			<?php
		}
	}
	else { ?>
		<div class="contentContainer center">
			<div class="mainContent ui-corner-all dropShadow center textCenter">
				<div class="textLeft" style="width: 100%;"><h3>BSDT Install</h3></div>
				<hr class="left">
				<span class="alert">BSDT is already installed.</span>
				<p>Please delete this file for security precautions. <a href="login.php">Navigate to login page</a>. If this is false contact your web admin.</p>
			</div>
		</div>
		<?php } ?>
		<script type="text/javascript">
		// Define functions
		function formFocusPassword(field) {
			if (field.value == field.defaultValue) {
				field.value = '';
				$(field).attr("type", "password");
			}
		}

		function formBlurPassword(field) {
			if (field.value == '') {
				field.value = field.defaultValue;
				$(field).attr("type", "text");
			}
		}
		// Executes when the page is FULLY loaded
		$(document).ready(function() {
		});
		</script>

	</body>
	</html>
