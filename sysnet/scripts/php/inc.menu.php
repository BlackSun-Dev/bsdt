<?php
// Populates form data for the menu
	if (varCheck($_POST['reportPanel']) == 1 || varCheck($_POST['historyPanel']) == 1) {

		$historyList = '';
		$systemList = '';
		$systemRetrieved = array();

		if ($_SESSION['userPermissions']['owner'] == 1 || $_SESSION['userPermissions']['admin'] == 1) {
			$stmt = $mysqli -> prepare("SELECT `system` FROM `scansbasic`");
				echo $mysqli -> error;

			$stmt -> execute();
			$stmt -> store_result();
			$stmt -> bind_result($system);

			while ($stmt -> fetch()) {
				if (!in_array($system, $systemRetrieved)) {
					$systemRetrieved[] = $system;
					$historyList .= '<option value="' . $system . '">' . $system . '</option>';
					$systemList .= '<option value="' . $system . '">' . $system . '</option>';
				}
			}

		} else {
			$stmt = $mysqli -> prepare("SELECT `Systems` FROM `groups` WHERE `GroupID` = ?");
				echo $mysqli -> error;

			$stmt -> bind_param('i', $_SESSION['userGroup']);
			$stmt -> execute();
			$stmt -> store_result();
			$stmt -> bind_result($systems);
			$stmt -> fetch();

			if (varCheck($systems) != 'undefined') {
				echo 'yes<br><br><br><br>';
				$systems = explode(';', $systems);
				$systemsList = '';

				foreach ($systems as $value) {
					$systemsList .= $value . ',';
				}

				$stmt = $mysqli -> prepare("SELECT `system` FROM `scansbasic` WHERE ");

				foreach ($systems as $key => $value) {
					$historyList .= '<option value="' . $value . '">' . $value . '</option>';
					$systemList .= '<option value="' . $value . '">' . $value . '</option>';
				}
			}
		}
	}
?>

<div id="menuRight">
	<div id="menuInformation">
		<div class="menuHeader ui-corner-top dropShadow">
			<span class="menuHeader center">Information</span>
		</div>

		<div class="menuContent textCenter dropShadow ui-corner-bottom">
			<?php if (isset($_SESSION['userLogged'])) { ?>
				Logged in as:<br />
				"<span style="color: #FFF;"><?php echo $_SESSION['userLogged']; ?></span>"<br />

				<form method="POST" action="home">
					<input type="submit" value="Logout" name="logout" class="button ui-corner-all dropShadow center">
				</form>
			<?php } else { ?>
				<form method="POST" action="home">
					<input id="userName" name="userName" type="text" class="formLogin formField dropShadow ui-corner-all" value="Username" onfocus="formFocus(this);" onblur="formBlur(this);"/><br />
					<input id="password" name="password" type="password" class="formLogin formField dropShadow ui-corner-all" value="Password" onfocus="formFocus(this);" onblur="formBlur(this);"/><br />
					<?php if (varCheck($notRegistered) == 1) { ?><span id="notRegistered" class="alert">You are not registered</span><?php } ?>
					<input type="submit" value="Login" name="login" class="button ui-corner-all dropShadow center">
				</form>
			<?php } ?>
		</div>
	</div>

	<div class="menuSection dropShadow">
		<div class="menuHeader ui-corner-top">
			<span class="menuHeader center">Navigation</span>
		</div>

		<div class="menuContent dropShadow ui-corner-bottom">
			<a href="home">Home</a><br />
			<?php if (varCheck($_SESSION['userPermissions']['groupAdmin']) == 1) { ?>
				<a href="userManagement">User Management</a><br />
			<?php } if (varCheck($_SESSION['userPermissions']['admin']) == 1) { ?>
				<a href="scrub">Scrub Database</a><br />
				<a href="groupManagement">Group Management</a><br />
			<?php } if (isset($_SESSION['userLogged'])) { ?>
				<a href="changePassword">Change Password</a>
				<hr>
				<a href="submitScan">Submit Scan</a><br />
			<?php } if (varCheck($_SESSION['userPermissions']['report']) == 1) { ?>
				<a href="currentScans">Current Scans</a><br />
				<a href="systemHistory">System History</a><br />
				<hr>
				<a href="entitySearch">Entity Search</a><br />
			<?php } ?>
		</div>
	</div>

<?php if (isset($_POST['reportPanel']) && $_POST['reportPanel'] == 1) { ?>
	<div class="menuSection dropShadow">
		<div class="menuHeader ui-corner-top">
			<span class="menuHeader center">Control</span>
		</div>

		<div class="menuContent ui-corner-bottom textCenter dropShadow">
			<select id="selectSystem" class="dropShadow formField ui-corner-all" style="width: 95%">
				<?php echo $systemList; ?>
			</select>

			<br />

			<div class="buttonSet">
				<input type="radio" class="dropShadow" id="graphic1" name="displayType" value="graphic" checked /><label for="graphic1" style="width: 49%" class="dropShadow">Graphic</label>
				<input type="radio" class="dropShadow" id="graphic2" name="displayType" value="text" /><label for="graphic2" style="width: 49%" class="dropShadow">Text</label>
			</div>

			<br />

			<select id="orderBy" class="dropShadow formField ui-corner-all" style="width: 95%">
				<option value="system">System Name</option>
				<option value="iff">IFF</option>
				<option value="coords">Coordinates</option>
				<option value="type">Entity Type</option>
				<option value="id">Entity ID</option>
				<option value="owner">Owner</option>
				<option value="name">Entity Name</option>
			</select>

			<br />

			<div class="buttonSet">
				<input type="radio" class="dropShadow" id="ascDesc1" name="ascDesc" value="ASC" checked /><label for="ascDesc1" style="width: 49%" class="dropShadow">Ascend.</label>
				<input type="radio" class="dropShadow" id="ascDesc2" name="ascDesc" value="DESC" /><label for="ascDesc2" style="width: 49%" class="dropShadow">Descend.</label>
			</div>

			<br />

			<div class="buttonSet">
				<input type="radio" class="dropShadow" id="iff1" name="iff" value="all" checked /><label for="iff1" style="width: 49%" class="dropShadow">All</label>
				<input type="radio" class="dropShadow" id="iff2" name="iff" value="enemy" /><label for="iff2" style="width: 49%" class="dropShadow">Enemy</label>
			</div>
			<div class="buttonSet">
				<input type="radio" class="dropShadow" id="iff3" name="iff" value="neutral" /><label for="iff3" style="width: 49%" class="dropShadow">Neutral</label>
				<input type="radio" class="dropShadow" id="iff4" name="iff" value="friend" /><label for="iff4" style="width: 49%" class="dropShadow">Friendly</label>
			</div>

			<br />

			<!-- Un-comment this if you want these features . . . they don't really serve a purpose though. It's better to leave the time filters to the entity searches. -->
			<!--<select id="time" class="formField ui-corner-all" style="width: 95%">
				<option value="all">All Timeframes</option>
				<option value="1">Past 24 Hours</option>
				<option value="2">Past 48 Hours</option>
				<option value="7">Past Week</option>
				<option value="30">Past Month</option>
			</select>

			<br /><br />-->

			<button class="button ui-corner-all dropShadow center" onClick="report();">Run Report</button>
		</div>
	</div>
<?php
}
	if (isset($_GET['entityID'])) { ?>
	<div class="menuSection dropShadow">
		<div class="menuHeader ui-corner-top">
			<span class="menuHeader">Control</span>
		</div>

		<div class="menuContent ui-corner-bottom textCenter dropShadow">

			<br />

			<div class="buttonSet">
				<input type="radio" class="dropShadow" id="graphic1" name="displayType" value="graphic" checked /><label for="graphic1" style="width: 49%" class="dropShadow">Graphic</label>
				<input type="radio" class="dropShadow" id="graphic2" name="displayType" value="text" /><label for="graphic2" style="width: 49%" class="dropShadow">Text</label>
			</div>
			<br />
			<button class="button ui-corner-all dropShadow center" onClick="report();">Run Report</button>
		</div>
	</div>
<?php }
if (isset($_POST['historyPanel']) && $_POST['historyPanel'] == 1) { ?>
	<div class="menuSection dropShadow">
		<div class="menuHeader ui-corner-top">
			<span class="menuHeader">Control</span>
		</div>

		<div class="menuContent ui-corner-bottom textCenter dropShadow">
			<select id="selectSystem" class="dropShadow formField ui-corner-all" style="width: 95%">
				<?php echo $historyList; ?>
			</select>

			<br />
			<button class="button ui-corner-all dropShadow center" onClick="reportHistory();">Run Report</button>
		</div>
	</div>
<?php } ?>
</div>
