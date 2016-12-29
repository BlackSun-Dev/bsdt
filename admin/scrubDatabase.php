<?php
include('../layout.php');
head();

$users = new User;
$permissionLevel = isset($_SESSION['userId']) ? $users->getPermissionLevel($_SESSION['userId']) : null;

?>

	<div class="contentContainer center">
		<div class="mainContent ui-corner-all dropShadow center textLeft">
			<div class="textCenter" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo SYSTEM_VERSION; ?></span></div>
			<h3>Scrub Database</h3>
			<hr class="left">
		<?php if ($permissionLevel >= 5) { ?>
			<div style="float: left; width: 35%" class="textCenter">
				<div class="textLeft" style="width: 100%">Note:</div>
				<hr>
				<span class="alert">Completion of the scrub<br>is irreverisble. Once content is<br>deleted, it cannot be retrieved.</span>
			</div>

			<div style="float: right; width: 65%" class="textCenter">
				<div class="textLeft" style="width: 100%">Scrub Settings</div>
				<hr>
				<div id="reportContainer"></div>
				<select id="timeFrameSelect" class="dropShadow formField ui-corner-all">
					<option value="none">Entire Database</option>
					<option value="1">Older than 24hrs</option>
					<option value="2">Older than 48hrs</option>
					<option value="7">Older than 1 Week</option>
					<option value="30">Older than 1 Month</option>
					<option value="182">Older than 6 Months</option>
					<option value="364">Older than 1 Year</option>
				</select>

				<br>

				<div id="iffSelectButtonSet1" class="buttonSet center">
					<input type="radio" id="iffSelect1" name="iffSelect" value="all" checked/><label for="iffSelect1" style="width: 24%">All</label>
					<input type="radio" id="iffSelect2" name="iffSelect" value="Enemy" /><label for="iffSelect2" style="width: 24%">Enemy</label>
				</div>
				<div id="iffSelectButtonSet2" class="buttonSet center">
					<input type="radio" id="iffSelect3" name="iffSelect" value="Neutral" /><label for="iffSelect3" style="width: 24%">Neutral</label>
					<input type="radio" id="iffSelect4" name="iffSelect" value="Friend" /><label for="iffSelect4" style="width: 24%">Friendly</label>
				</div>
				<br>
				<button id="searchSubmit" class="button ui-corner-all dropShadow center" onClick="scrub();">Scrub</button><br /><br />
			</div>

			<div id="reportContainer" class="textCenter"></div>
		<?php } else { ?>
			<span class="alert">You are not authorized to view this page.</span>
		<?php } ?>
		</div>
	</div>

	<script type="text/javascript">
	// Define functions
		function scrub() {
			var iffValue = $("input[name=iffSelect]:checked").val();
			var timeFrameValue = $("#timeFrameSelect").val();

			$.ajax({
				type: "POST",
				url: "processScrub",
				data: {
					iffValue: iffValue,
					timeFrameValue: timeFrameValue
				},
				dataType: "JSON",
				success: function(data) {
					$("#reportContainer").html('<span class="alert">' + data + '</span>');
				}
			});
		}
	// Executes when the page is FULLY loaded
		$(document).ready(function() {
			$(".giveShadow").each(function() {
				var shadowWidth = $(this).width();
				var shadowHeight = $(this).height();
				var shadowPosition = $(this).position();

				$('<div class="dropShadow ui-corner-all" style="z-index: 0; position: absolute; top: ' + (shadowPosition.top+1) + 'px; left: ' + (shadowPosition.left+5) +'px; width: ' + shadowWidth + 'px; height: ' + shadowHeight + 'px;"></div>').insertBefore($(this));
			});

			$("#iffSelectButtonSet1").buttonset();
			$("#iffSelectButtonSet2").buttonset();
		});
	</script>

</body>
</html>
