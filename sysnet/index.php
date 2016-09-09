<?php
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";

	$_SESSION['error'] = array(
		"001" => "Must only select one user when transferring program ownership.",
		"002" => "Current password did not match this account.",
		"003" => "New passwords did not match.",
		"004" => "Your password was sucessfully changed.",
		"005" => "This scan has already been entered.",
		"006" => "Your scan has been successfully submitted.",
		"007" => "You have not entered properly formatted XML.",
		"008" => "That Username already exists.",
		"009" => "Admins cannot be assigned to a group.<br />All other non-admins selected were updated appropriately",
		"010" => "That Group Name already exists.",
		"011" => "You cannot modify this group.",
		"012" => "You must select at least one filter before searching.",
		"013" => "The database has been scrubbed"
		);

	$_SESSION['currentVersion'] = 'v2.02';

	$notRegistered = 0;

	if (isset($_POST['login'])) {
		$password = md5('prefix' . md5($_POST['password']) . 'suffix');
		$username = $_POST['userName'];

		$stmt = $mysqli -> prepare("SELECT `IndexNo`, `Group`, `Permissions` FROM `users` WHERE `Username` = ? AND `Password` = ?");
			echo $mysqli -> error;
		$stmt -> bind_param('ss', $username, $password);

		$stmt -> execute();
		$stmt -> store_result();

		$stmt -> bind_result($IndexNo, $group, $permissions);

		if ($stmt -> num_rows == 1) {
			$_SESSION['userLogged'] = $username;
			
			while ($stmt -> fetch()) {
				$_SESSION['userIndex'] = $IndexNo;
				$tempPermissions = explode(',', $permissions);
					$_SESSION['userPermissions']['owner'] = $tempPermissions[0];
					$_SESSION['userPermissions']['admin'] = $tempPermissions[1];
					$_SESSION['userPermissions']['groupAdmin'] = $tempPermissions[2];
					$_SESSION['userPermissions']['report'] = $tempPermissions[3];
				$_SESSION['userGroup'] = $group;
			}

			$_SESSION['userSystems'] = array();

			if ($_SESSION['userPermissions']['owner'] == 1 || $_SESSION['userPermissions']['admin'] == 1) {
				$stmt2 = $mysqli -> prepare("SELECT `system` FROM `changesbasic`");
					echo $mysqli -> error;

				$stmt2 -> execute();
				$stmt2 -> store_result();
				$stmt2 -> bind_result($system);

				while ($stmt2 -> fetch()) {
					if (in_array($system, $_SESSION['userSystems'])) {} else {
						$_SESSION['userSystems'][] = $system;
					}
				}

			} else {
				$stmt2 = $mysqli -> prepare("SELECT `Systems` FROM `groups` WHERE `GroupID` = ?");
					echo $mysqli -> error;

				$stmt2 -> bind_param('i', $_SESSION['userGroup']);
				$stmt2 -> execute();
				$stmt2 -> store_result();

				$stmt2 -> bind_result($systems);

				while ($stmt2 -> fetch()) {
					$tempSystems = $systems;
				}

				$tempSystems = explode(',', $tempSystems);
				foreach ($tempSystems as $key => $value) {
					$_SESSION['userSystems'][] = $value;
				}
			}

		} else {
			$notRegistered = 1;
		}
	}

	if (isset($_POST['logout'])) {
		$tempVersion = $_SESSION['currentVersion'];
		session_unset();
		session_destroy();
		session_start();
		$_SESSION['currentVersion'] = $tempVersion;
	}

	include "scripts/php/inc.header.php";
?>

<body>
	<?php
		include "scripts/php/inc.menu.php";
		include "scripts/php/inc.logo.php";
	?>
	
	<div class="contentContainer center">

		<div class="mainContent ui-corner-all dropShadow textLeft">
			<div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo $_SESSION['currentVersion']; ?></span></div>	
			<h3>Version History</h3>
			<hr class="left">
			<table width="100%" cellspacing="0" cellpadding="5" border="0">
				<tr class="even">
					<td width="100%">v2.02</td>
				</tr>

				<tr class="even">
					<td width="100%" valign="top">
						<ul class="version">
							<li>Styling update to fix layout issues from browser upgrades.</li>
						</ul>
					</td>
				</tr>

				<tr class="odd">
					<td width="100%">v2.01</td>
				</tr>

				<tr class="odd">
					<td width="100%" valign="top">
						<ul class="version">
							<li>Security fix to prevent exploitation by those with the source code</li>
						</ul>
					</td>
				</tr>

				<tr class="even">
					<td width="100%">v2.0</td>
				</tr>

				<tr class="even">
					<td width="100%" valign="top">
						<ul class="version">
							<li>Converted all MySQL to MySQLi for security purposes</li>
							<li>Added option to define time window for reports</li>
							<li>Changed group system separation from comma to semi-colon to allow for deep-space scans to be named as their coordinates</li>
							<li>Fixed bug that didn't update system list without logging out and back in</li>
						</ul>
					</td>
				</tr>

				<tr class="odd">
					<td width="100%">v1.02</td>
				</tr>

				<tr class="odd">
					<td width="100%" valign="top">
						<ul class="version">
							<li>Fixed bug that allowed submitting scans without entering a system name</li>
							<li>Fixed bug that allowed submitting blank focus scans</li>
						</ul>
					</td>
				</tr>

				<tr class="even">
					<td width="100%">v1.01</td>
				</tr>

				<tr class="even">
					<td width="100%" valign="top">
						<ul class="version">
							<li>Added "scroll to top" feature when generating new reports to reduce scrolling</li>
						</ul>
					</td>
				</tr>
			</table>

			<!--<br /><br />

			<h3>Permission Levels</h3>
			<hr class="left">
			The different levels of permission are Owner, Admin, Group Admin, Report, Basic. Use the login info provided below to test each level of permissions.<br /><br />
			Login: Owner<br />
			Pass: test<br />
			There can be only one owner per program instance. An Owner has the same priviledges as an Admin but also has the power to transfer ownership to another user.<br /><br />

			Login: Admin<br />
			Pass: test<br />
			Admins have full administrative powers that allow them to create new user accounts, modify permissions, and remove users. Admins do not belong to groups. The only power that an Admin lacks is the ability to transfer ownership of the program.<br /><br />

			Login: Group Admin<br />
			Pass: test<br />
			Group Admins have limited administrative powers that allow them to create new user accounts, modify permissions, and remove users within groups that they belong to.<br /><br />

			Login: Report<br />
			Pass: test<br />
			Report priviledges allow users to input scans as well as generate reports. They have no administrative powers.<br /><br />

			Login: Basic<br />
			Pass: test<br />
			Basic priviledges allow users to input scans only. They have no administrative powers and cannot generate reports.-->
		</div>
	</div>

	<div class="clear" />

	<script type="text/javascript">
	// Define functions
	// Executes when the page is FULLY loaded
		$(document).ready(function() {
		});
	</script>

</body>
</html>