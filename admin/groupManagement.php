<?php
include('../layout.php');
head("Group Management");

$users = new User;
$permissionLevel = isset($_SESSION['userId']) ? $users->getPermissionLevel($_SESSION['userId']) : null;
$mysqli = Database::getInstance();
	if ($permissionLevel >= 4) {
	// Gets all the group information
		$stmt = $mysqli->prepare("SELECT `GroupID`, `GroupName`, `Systems` FROM `groups`");
			echo $mysqli-> error;
		$stmt -> execute();
		$stmt -> store_result();
		$stmt -> bind_result($groupID, $groupName, $systems);

		$groupRowCount = 0;
		$groupList = '<table class="reportTable">';

		while ($stmt -> fetch()) {
			if ($groupRowCount % 2 == 0) {
				$groupList .= '<tr class="even">';
			} else {
				$groupList .= '<tr class="odd">';
			}
			$groupList .= '<td style="position: relative; display: block;" class="userDisplay" id="td_' . $groupID . '">
						<h3>' . $groupName . '</h3>
						<br />
						<span class="inlineEditSpan" groupID="' . $groupID . '">' . $systems . '</span>
						<div class="groupDelete">
							<span groupID="' . $groupID . '" groupName = "' . $groupName . '" style="margin: 2px 2px 0 0;" class="delete ui-icon ui-icon-circle-close"></span>
						</div>
					</td>
				</tr>';

			$groupRowCount++;
		}

		$groupList .= '</table>';
	}
	?>

	<div class="contentContainer center">
		<div class="mainContent ui-corner-all dropShadow center textLeft">
			<div class="textCenter" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo SYSTEM_VERSION; ?></span></div>
			<h3>Group Management</h3>
			<hr class="left">
		<?php if ($permissionLevel >= 4) { ?>
			<div style="float: left; width: 35%" class="textCenter">
				<div class="textLeft" style="width: 100%">New Group</div>
				<hr>

				<form method="POST" action="processGroup">
					<input type="hidden" name="mode" value="new">

					<input type="text" name="groupName" value="Group Name" class="formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);" style="padding-left: 5px; width: 75%;">
					<br />
					<textarea name="groupSystems" class="formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);" style="position: relative; left: 1px; padding-left: 5px; width: 202px; height: 150px;">Enter systems accessible by this group separated by a semi-colon and no space.
						Ex: Coruscant;Naboo;Tatoo; etc...</textarea>
					<br />


					<input type="submit" name="submit" value="Create" class="button ui-corner-all dropShadow center">
				</form>

				<br />
				<span class="alert"><?php if (isset($_GET['error'])) { echo $_SESSION['error'][$_GET['error']]; } ?></span>
			</div>

			<div style="float: right; width: 65%" class="textCenter">
				<div class="textLeft" style="width: 100%">Existing Groups</div>
				<hr>
				<div id="deleteConfirmContainer" class="deleteConfirm ui-corner-all dropShadow" style="display: none;">
					This will permanently delete the group<br />"<span id="deleteConfirmGroup"></span>"<br /> Are you sure you wish to continue?
					<br />
					<button id="deleteConfirm" class="button ui-corner-all dropShadow">Delete Group</button>
					<button id="deleteCancel" class="button ui-corner-all dropShadow">Cancel</button>
				</div>

				<span class="alert" id="alert"></span>

				<?php echo $groupList; ?>
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
		$("body").on("click", "span.inlineEditSpan", function() {
			var groupID = $(this).attr('groupID');
			var groupList = $(this).html();

			$(this).replaceWith("<input id='input_" + groupID + "' groupID = '" + groupID + "' class='inlineEdit formField ui-corner-all dropShadow'></input>");
			$("#input_" + groupID).focus().val(groupList);
		});

		$("body").on("blur", "input.inlineEdit", function() {
			var groupID = $(this).attr('groupID');
			var systemList = $(this).val();
			var elementID = $(this);

			$.ajax({
				type: "POST",
				url: "processGroup",
				data: {
					mode: "update",
					groupID: groupID,
					systemList: systemList
				},
				dataType: "JSON",
				success: function(data) {
					if (data == 'success') {
						elementID.replaceWith('<span class="inlineEditSpan" id="inlineSpan_' + groupID + '" groupID="' + groupID + '">' + systemList + '</span>');
					} else {
						elementID.replaceWith('<span class="inlineEditSpan" id="inlineSpan_' + groupID + '" groupID="' + groupID + '">(Default Group)</span>');
						$("#alert").html(data['error']);
					}
				}
			});
		});

		$("body").on("click", "span.delete", function() {
			var groupName = $(this).attr('groupName');
			var groupID = $(this).attr('groupID');
			y = $(this).offset().top;

			$("#deleteConfirmContainer").show();
			$("#deleteConfirmGroup").html(groupName);
			$("#deleteConfirm").attr('groupID', groupID);
			$("#deleteConfirmContainer").css("top", (y - 235));
		});

		$("body").on("click", "#deleteCancel", function() {
			$("#deleteConfirmContainer").hide();
		});

		$("body").on("click", "#deleteConfirm", function() {
 			var groupID = $(this).attr('groupID');

			$.ajax({
				type: "POST",
				url: "processGroup",
				data: {
					mode: "delete",
					groupID: groupID
				},
				dataType: "JSON",
				success: function(data) {
					if (data == 'success') {
						$("#td_" + groupID).slideUp("fast");
						$("#deleteConfirmContainer").hide();
					} else {
						$("#alert").html(data['error']);
						$("#deleteConfirmContainer").hide();
					}
				}
			});
		});

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
				$("#addGroup").css("display", "inline");
			} else {
				$("#addGroup").css("display", "none");
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
		})

		$("#userConfirm").click(function() {
			if ($(this).is(":checked")) {
				$("#userSubmit").addClass("button").removeClass("buttonDisabled").removeAttr("disabled");
			} else {
				$("#userSubmit").addClass("buttonDisabled").removeClass("button").attr("disabled", "disabled");
			}
		});
	});
</script>

</body>
</html>
