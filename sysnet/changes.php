<?php
	//error_reporting(E_ERROR);
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";

	if (varCheck($_GET['scan']) != 'undefined') {
		$reportType = $_GET['scan'];
	} else {
		$reportType = '';
	}

	include "scripts/php/inc.header.php";
?>

<body>
	<?php
		include "scripts/php/inc.menu.php";
		include "scripts/php/inc.logo.php";
	?>
	
	<div class="contentContainer center">
		<div id="reportContainer" class="mainContent ui-corner-all dropShadow center textCenter">
			<div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo $_SESSION['currentVersion']; ?></span></div>
			<h3>Entity History</h3>
			<hr class="left">
		<?php if (isset($_SESSION['userPermissions']) && $_SESSION['userPermissions']['report'] == 1) { ?>
		
		<div id="tabHolder">
			<div id="tabBasic" class="floatLeft tab tabActive ui-corner-top" onClick="activeTab('Basic', 'Focus');">Entity History</div>
			<div id="tabFocus" class="floatRight tab ui-corner-top" onClick="activeTab('Focus', 'Basic');">Focus Data</div>
		</div>
		<div class="clear">
		<div id="reportBasic"></div>
		<div id="reportFocus">Focus Data</div>


		<?php } else { ?>
			<span class="alert">You are not authorized to view this page.</span>
		<?php } ?>
		</div>
	</div>

	<script type="text/javascript">
	// Define functions
		function reportFocus() {

			var entityID = <?php echo $_GET['entityID']; ?>;
			var display = $('input[type="radio"][name="displayType"]:checked').val();

			$.ajax({
				type: "POST",
				url: "processReportFocus",
				data: { 
					entityID: entityID,
					display: display
				},
				dataType: "JSON",
				success: function(data) {
					var tableReport = '';
					$('#reportBasic').html(data[0]);
					$('#reportFocus').html("<br />"+data[1]);
					progressBar();
				}
			});

			activeTab('Basic', 'Focus');
		}

		function activeTab(active, inactive) {
			$("#report" + active).css("display", "block");
			$("#report" + inactive).css("display", "none");
			$("#tab" + active).addClass("tabActive");
			$("#tab" + inactive).removeClass("tabActive");

		}

	// Executes when the page is FULLY loaded
		$(document).ready(function() {
			$.ajaxSetup({
				beforeSend:function() {
					$('#reportBasic').show().html('<div class="center ajaxLoading"></div>');
				}
			});

			reportFocus();
		});
	</script>

</body>
</html>