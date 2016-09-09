<?php
	if (isset($_POST['databaseName'])) {
		$text = '<?php
			session_start();
			$mysqli = new mysqli("' . $_POST['server'] . '", "' . $_POST['username'] . '", "' . $_POST['password'] . '", "' . $_POST['databaseName'] . '");
			if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
		?>';

		$fp = fopen('scripts/php/req.conn.php', 'w');
		fwrite($fp, $text);
		fclose($fp);

		require  'scripts/php/req.conn.php';

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

		$usersInsert = 'INSERT INTO `users` (`IndexNo`, `Username`, `Password`, `Group`, `Permissions`) VALUES
			(NULL ,  "Owner",  "8460235f9104a551f680c1417e081d11",  "0",  "1,1,1,1");';

		$mysqli -> query($changesBasic);
		$mysqli -> query($changesFocus);
		$mysqli -> query($groups);
		$mysqli -> query($groupsInsert);
		$mysqli -> query($scansBasic);
		$mysqli -> query($scansFocus);
		$mysqli -> query($users);
		$mysqli -> query($usersInsert);

		$installed = true;
	} else {
		session_start();
		$installed = false;
	}

	$_SESSION['currentVersion'] = 'v2.0';

	include "scripts/php/fnc.myFunctions.php";
	include "scripts/php/inc.header.php";
?>

<body>
	<?php
		include "scripts/php/inc.logo.php";
	?>
	
	<div class="contentContainer center">
		<div class="mainContent ui-corner-all dropShadow center textCenter">
			<div class="textCenter" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo $_SESSION['currentVersion']; ?></span></div>
			<div class="textLeft" style="width: 100%;"><h3>SYSNET Install</h3></div>
			<hr class="left">

			<?php if ($installed == true) { ?>
				<span class="alert">SYSNET Has been Installed.</span>

				<ol start="3">
					<li>As an optional 3rd step you can delete the install.zip and sysnet/install.php</li>
				</ol>
				<br><br>
			<?php } else { ?>
				<ol>
					<li>Create a database specifically for SYSNET with full admin priviledges</li>

					<li>Fill out form below and click "Install"<br><br>
						<form method="POST" action="install.php">
							<div style="width: 325px;" class="center textRight">
								Server (default is localhost): <input type="text" name="server" value="localhost" class="formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);"><br />
								Database Name: <input type="text" name="databaseName" value="Database Name" class="formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);"><br />
								Database Username: <input type="text" name="username" value="Database Username" class="formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);"><br />
								Database User Password: <input type="text" name="password" value="Database User Password" class="formField ui-corner-all dropShadow" onfocus="formFocusPassword(this);" onblur="formBlurPassword(this);"><br />
							</div>
							<input type="submit" value="Install" name="install" class="button ui-corner-all dropShadow">
						</form>
					</li>
				</ol>
			<?php } ?>
		</div>
	</div>

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