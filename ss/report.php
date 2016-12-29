<?php
include('../layout.php');
head();


	if (isset($_SESSION['userPermissions']['report']) == 1) {
		$_POST['reportPanel'] = 1;
	}
?>

	<div class="contentContainer center">
		<div class="mainContent ui-corner-all dropShadow center textCenter">
			<div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo $_SESSION['currentVersion']; ?></span></div>

			<div style="width: 100%;" class="textLeft">
				<h3>Current Scans</h3>
			</div>

			<hr class="left">
			<div id="reportContainer">
				<?php if (isset($_SESSION['userPermissions']) && $_SESSION['userPermissions']['report'] == 1) { ?>
					<span class="alert">Use the control panel to filter and generate a report of the most recent scans.</span>
				<?php } else { ?>
					<span class="alert">You are not authorized to view this page.</span>
				<?php } ?>
			</div>
		</div>
	</div>

<script type="text/javascript">
// Define functions
	function report() {
		smoothToTop();

		var time = $('#time').val();
		var selectSystem = $('#selectSystem').val();
		var displayType = $('input[type="radio"][name="displayType"]:checked').val();
		var orderBy = $('#orderBy').val();
		var iffType = $('input[type="radio"][name="iff"]:checked').val();
		var ascDesc = $('input[type="radio"][name="ascDesc"]:checked').val();

		$.ajax({
			type: "POST",
			url: "processReport",
			data: {
				time: time,
				system: selectSystem,
				display: displayType,
				iff: iffType,
				order: orderBy,
				ascOrDesc: ascDesc
			},
			dataType: "JSON",
			success: function(data) {
				var tableReport = '';
				for(var a = 0; data[a]; a++) {
					tableReport = tableReport + data[a];
				}
				$('#reportContainer').html(tableReport);
				progressBar();
			}
		});
	}

// Executes when the page is FULLY loaded
	$(document).ready(function() {
	});
</script>

</body>
</html>
