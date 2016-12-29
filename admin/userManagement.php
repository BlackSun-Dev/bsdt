<?php
include('../layout.php');
head("User Management");

$users = new User;
$permissionLevel = isset($_SESSION['userId']) ? $users->getPermissionLevel($_SESSION['userId']) : null;
$mysqli = Database::getInstance();


if (isset($_POST['mode']) == 'new') {
	$users->addUser($_POST['username'], $_POST['password'], $_POST['newUserGroup'], $_POST['newUserLevel']);
}

	if ($permissionLevel >= 4) {
	// Gets all the group information to populate user's group fields
		$stmt = $mysqli->prepare("SELECT `GroupID`, `GroupName`, `Systems` FROM `groups` ORDER BY `GroupName`");
			echo $mysqli->error;
		$stmt -> execute();
		$stmt -> store_result();
		$stmt -> bind_result($stmt_GroupID, $stmt_GroupName, $stmt_Systems);

		$groups = array();

		while ($stmt -> fetch()) {
			$groups[$stmt_GroupID] = array('GroupID' => $stmt_GroupID, 'GroupName' => $stmt_GroupName);

			$systemPieces = explode(',', $stmt_Systems);
			$groups[$stmt_GroupID]['Systems'] = array();

			foreach ($systemPieces as $value) {
				$groups[$stmt_GroupID]['Systems'][] = $value;
			}
		}

	// Adds each group as a select option for assigning users
		$groupsList = '';
		foreach ($groups as $key => $value) {
			$groupsList .= '<option value="' . $groups[$key]['GroupID'] . '">' . $groups[$key]['GroupName'] . '</option>';
		}

		$stmt = $mysqli->prepare("SELECT `userId`, `userHandle`, `userGroup`, `userLevel` FROM `users` WHERE `userId` <> ? ORDER BY `userHandle`");
			echo $mysqli->error;
		$stmt -> bind_param('s', $_SESSION['userIndex']);
		$stmt -> execute();
		$stmt -> store_result();
		$stmt -> bind_result($stmt_IndexNo, $stmt_Username, $stmt_Group, $stmt_Permissions);

		$adminList = '<table class="reportTable">';
		$groupAdminList = '<table class="reportTable">';
		$reportList = '<table class="reportTable">';
		$basicList = '<table class="reportTable">';

		$adminRowCount = 0;
		$groupAdminRowCount = 0;
		$reportRowCount = 0;
		$basicRowCount = 0;

		$inGroup = 0;

		while ($stmt -> fetch()) {
			$permPieces = explode(',', $stmt_Permissions);
			$groupPieces = explode(',', $stmt_Group);

			if (isset($permPieces[1]) && $permPieces[1] == 1) {
				if ($adminRowCount % 2 == 0) {
					$adminList .= '<tr class="even">';
				} else {
					$adminList .= '<tr class="odd">';
				}
				$adminRowCount++;

				$adminList .= '<td class="userSelect"><input type="checkbox" name="users[]" value="' . $stmt_IndexNo . '"></td>
					<td class="userDisplay">' . $stmt_Username . '</td>
					</tr>';

			} else if (isset($permPieces[2]) && $permPieces[2] == 1) {
				if ($groupAdminRowCount % 2 == 0) {
					$groupAdminList .= '<tr class="even">';
				} else {
					$groupAdminList .= '<tr class="odd">';
				}
				$groupAdminRowCount++;

				$groupAdminList .= '<td class="userSelect"><input type="checkbox" name="users[]" value="' . $stmt_IndexNo . '"></td>
					<td class="userDisplay">' . $stmt_Username . '<div style="float: right;">' . $groups[$stmt_Group]['GroupName'] . '</div></td>
					</tr>';

			} else if (isset($permPieces[3]) && $permPieces[3] == 1) {
				if (in_array($_SESSION['userGroup'], $groupPieces)) {
					$inGroup = 1;
				} else {
					$inGroup = 0;
				}

				if (($inGroup == 1) || (isset($_SESSION['userPermissions']) && ($_SESSION['userPermissions']['owner'] == 1 || $_SESSION['userPermissions']['admin'] == 1))) {
					if ($reportRowCount % 2 == 0) {
						$reportList .= '<tr class="even">';
					} else {
						$reportList .= '<tr class="odd">';
					}
					$reportRowCount++;

					$reportList .= '<td class="userSelect"><input type="checkbox" name="users[]" value="' . $stmt_IndexNo . '"></td>
						<td class="userDisplay">' . $stmt_Username . '<div style="float: right;">' . $groups[$stmt_Group]['GroupName'] . '</div></td>
						</tr>';
				}

				$inGroup = 0;

			} else {
				if (in_array($_SESSION['userGroup'], $groupPieces)) {
					$inGroup = 1;
				} else {
					$inGroup = 0;
				}

				if (($inGroup == 1) || ($permissionLevel >=4)) {
					if ($basicRowCount % 2 == 0) {
						$basicList .= '<tr class="even">';
					} else {
						$basicList .= '<tr class="odd">';
					}
					$basicRowCount++;

					$basicList .= '<td class="userSelect"><input type="checkbox" name="users[]" value="' . $stmt_IndexNo . '"></td>
						<td class="userDisplay">' . $stmt_Username . '<div style="float: right;">' . $groups[$stmt_Group]['GroupName'] . '</div></td>
						</tr>';
				}

				$inGroup = 0;
			}
		}

		$adminList .= '</table>';
		$groupAdminList .= '</table>';
		$reportList .= '</table>';
		$basicList .= '</table>';
	}

// Does checks on permissions to keep the tabs displaying properly
	if ($permissionLevel >= 5) {
		$tabPercent = 24;
	} else if ($permissionLevel >=4) {
		$tabPercent = 32;
	} else {
		$tabPercent = 49;
	}

	?>

	<div class="contentContainer center">
		<div class="mainContent ui-corner-all dropShadow center textLeft">
			<div class="textCenter" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo SYSTEM_VERSION; ?></span></div>
			<h3>User Management</h3>
			<hr class="left">
		<?php if ($permissionLevel >= 4) { ?>
			<div style="float: left; width: 35%" class="textCenter">
				<div class="textLeft" style="width: 100%">New Account</div>
				<hr>

				<form method="POST" action="userManagement">
					<input type="hidden" name="mode" value="new">
					<input type="text" name="username" placeholder="Handle" class="userManagement formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);"><br />
					<input type="password" name="password" placeholder="Temp Password" class="userManagement formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);"><br />
					<select id="newUserPrivs" name="newUserLevel" class="dropShadow userManagement formField ui-corner-all">
						<option value="" disabled selected>Set user permissions</option>
						<?php if ($permissionLevel >= 5) { ?>
							<option value="4">Assign Vigo</option>
						<?php } if ($permissionLevel >= 4) { ?>
							<option value="3">Assign Consigliere</option>
						<?php } ?>
						<option value="2">Assign Faction Assistant</option>
						<option value="1">Assign General User</option>
					</select><br />

					<?php if ($permissionLevel >= 4) { ?>
						<select id="newUserGroup" name="newUserGroup" class="dropShadow userManagement formField ui-corner-all">
							<option value="" disabled selected>Set user group</option>
							<?php echo $groupsList; ?>
						</select><br id="newUserGroupBreak" />
					<?php } else { ?>
						<input type="hidden" name="newUserGroup" value=<?php echo '"' . $_SESSION['userGroup'] . '"'; ?> >
					<?php } ?>


					<input type="submit" name="submit" value="Create" class="button ui-corner-all dropShadow center" style="margin-top: 5px;">
				</form>

				<br />
				<span class="alert"><?php if (isset($_GET['error'])) { echo $_SESSION['error'][$_GET['error']]; } ?></span>
			</div>

			<div style="float: right; width: 65%" class="textCenter">
				<form method="POST" action="userManagement.php">
					<input type="hidden" name="mode" value="update">
					<?php if ($permissionLevel >= 5) { ?>
						<div class="tab ui-corner-top" id="adminTab" tabLink="adminDisplay" style="width: <?php echo $tabPercent; ?>%;">Admin</div>
					<?php } ?>

					<?php if ($permissionLevel >= 4) { ?>
						<div class="tab ui-corner-top" id="groupAdminTab" tabLink="groupAdminDisplay" style="width: <?php echo $tabPercent; ?>%;">Group Admin</div>
					<?php } ?>

					<div class="tab ui-corner-top tabActive" id="reportTab" tabLink="reportDisplay" style="width: <?php echo $tabPercent; ?>%;">Report</div>

					<div class="tab ui-corner-top" id="basicTab" tabLink="basicDisplay" style="width: <?php echo $tabPercent; ?>%;">Basic</div>

					<div class="clear"></div>

					<?php if ($permissionLevel >= 5) { ?>
						<div id="adminDisplay" style="display: none;" class="tabDisplay">
							<?php echo $adminList; ?>
						</div>
					<?php } ?>

					<?php if ($permissionLevel >= 4) { ?>
						<div id="groupAdminDisplay" style="display: none;" class="tabDisplay">
							<?php echo $groupAdminList; ?>
						</div>
					<?php } ?>

					<div id="reportDisplay" style="display: block;" class="tabDisplay">
						<?php echo $reportList; ?>
					</div>

					<div id="basicDisplay" style="display: none;" class="tabDisplay">
						<?php echo $basicList; ?>
					</div>
                    <div>
    					<!--<div class="dropShadow ui-corner-all" data-shadowFor="userAction"></div>-->
    					<select id="userAction" name="actions" class="dropShadow formField ui-corner-all">
    						<?php if ($permissionLevel >= 5) { ?>
    							<option value="transfer">Transfer Ownership</option>
    							<option value="0,1,1,1">Assign Admin</option>
    						<?php } if ($permissionLevel >= 4) { ?>
    							<option value="0,0,1,1">Assign Group Admin</option>
    						<?php } ?>
    						<?php if ($permissionLevel >= 4) { ?>
    							<option value="group">Assign Group</option>
    						<?php } ?>
    						<option value="0,0,0,1">Assign Reports</option>
    						<option value="0,0,0,0" selected>Assign General Privs</option>
    						<option value="remove">Remove User</option>
    					</select><br />
    					<select id="addGroup" name="addGroup" class="center formField ui-corner-all">
    						<?php echo $groupsList; ?>
    					</select>
    				</div>
                        <br id="addGroupBreak" style="display: none;">

					<div id="userConfirmContainer" style="display: none;">
						<input id="userConfirm" type="checkbox" value="confirm" name="confirm">
						Click to confirm transfer of ownership.
					</div>

					<input id="userSubmit" type="submit" value="Process" name="submit" class="button ui-corner-all dropShadow center" style="margin-top: 5px;"><br />
				</form>
			</div>
		<?php } else { ?>
			<span class="alert">You are not authorized to view this page.</span>
		<?php } ?>
		</div>
	</div>

<script type="text/javascript">
// Define functions

// Executes when the page is FULLY loaded
	$(document).ready(function() {
		$("#userAction").change(function() {
			if ($(this).val() == "transfer") {
				$("#userSubmit").addClass("buttonDisabled").removeClass("button").attr("disabled", "disabled");
				$("#userConfirmContainer").css("display", "block");
			} else if ($(this).val() != "transfer" && ($("#userSubmit").hasClass("buttonDisabled") || ($("#userConfirm").is(":checked")))) {
				$("#userSubmit").addClass("button").removeClass("buttonDisabled").removeAttr("disabled");
				$("#userConfirmContainer").css("display", "none");
				$("input[name=confirm]").attr("checked", false);
			}

			if ($(this).val() == "group") {
				$("#addGroup").css("display", "block");
				$("addGroupBreak").css("display", "block");
			} else {
				$("#addGroup").css("display", "none");
				$("#addGroupBreak").css("display", "none");
			}
		});

		$("#newUserPrivs").change(function () {
			if ($(this).val() == "0,1,1,1") {
				$("#newUserGroup").css("display", "none");
				$("#newUserGroupShadow").css("display", "none");
				$("#newUserGroupBreak").css("display", "none");
			} else {
				$("#newUserGroup").css("display", "inline");
				$("#newUserGroupShadow").css("display", "block");
				$("#newUserGroupBreak").css("display", "block");
			}
		});

		$("#userConfirm").click(function() {
			if ($(this).is(":checked")) {
				$("#userSubmit").addClass("button").removeClass("buttonDisabled").removeAttr("disabled");
			} else {
				$("#userSubmit").addClass("buttonDisabled").removeClass("button").attr("disabled", "disabled");
			}
		});

		$("div.tab").click(function() {
			var tabShow = $(this).attr('tabLink');
			var activeTab = $(this).attr("id");

			$("div.tab").each(function () {
				if ($(this).attr("id") == activeTab) {
					$(this).addClass("tabActive");
				} else {
					$(this).removeClass("tabActive");
				}
			});

			$("div.tabDisplay").each(function () {
				if ($(this).attr("id") == tabShow) {
					$(this).css("display", "block");
				} else {
					$(this).css("display", "none");
				}
			});
		});
	});
</script>

</body>
</html>
