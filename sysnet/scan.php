<?php
    require "scripts/php/req.conn.php";

    include "scripts/php/fnc.myFunctions.php";
    include "scripts/php/fnc.progSpecific.php";
    include "scripts/php/fnc.xml2Array.php";

	include "scripts/php/inc.header.php";
?>

<body>
	<?php
		include "scripts/php/inc.menu.php";
		include "scripts/php/inc.logo.php";
	?>
	
	<div class="contentContainer center">
		<div class="mainContent ui-corner-all textCenter center dropShadow" id="reportContainer">
			<div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo $_SESSION['currentVersion']; ?></span></div>

			<div style="width: 100%;" class="textLeft">
				<h3>Submit Scan</h3>
			</div>

			<hr class="left">
			<?php if (isset($_SESSION['userLogged'])) { ?>
				<div id="scanFields">
					<div id="error"></div>
					<input id="scannedBy" type="hidden" value="<?php echo $_SESSION['userLogged']; ?>">
					<input id="systemInput" type="text" value="System Name" class="formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);"><br id="systemBreak"/>
					<textarea id="xmlInput" value="test" class="formField ui-corner-all dropShadow" onfocus="formFocus(this);" onblur="formBlur(this);">Paste XML File Here</textarea><br />
					<select id="scanType" class="formField ui-corner-all dropShadow">
						<option value="system">System Scan</option>
						<option value="grid">Grid Scan</option>
						<option value="focus">Focus Scan</option>
					</select>

					<button onClick="processScan();" class="button ui-corner-all dropShadow">Process</button><br />
				</div>
				<div id="loading" class="center ajaxLoading" style="display: none;"></div>
			<?php } else { ?>
				<span class="alert">You are not authorized to view this page.</span><br />
				<span class="alert">Please Login</span>
			<?php } ?>
		</div>
	</div>

<script type="text/javascript">
// Define functions
	function processScan() {
		var xmlInput = $('#xmlInput').val();
		var systemInput = $('#systemInput').val();
		var scannedBy = $('#scannedBy').val();
		var scanType = $('#scanType').val();

		if (scanType == 'focus') {
			var divContents = $("#reportContainer").html();

			$.ajax({
				type: "POST",
				url: "processFocus",
				data: {scannedBy: scannedBy, xmlInput: xmlInput, systemInput: systemInput},
				dataType: "JSON",
				success: function(data) {
					$("#systemInput").val('System Name').addClass('greyed');
					$("#xmlInput").val('Paste XML File Here').addClass('greyed');
					$("#loading").hide();
					$("#scanFields").show();

					var dataCombined = "";
					for(var a = 0; data[a]; a++) {
						dataCombined = dataCombined + data[a];
					}

					$("#error").html("<span class='alert'>"+dataCombined+"</span><br /><br />");
				}
			});
		} else {
			if (systemInput == 'System Name') {
				$("#error").html("<span class='alert'>You must enter a system name</span><br /><br />");
			} else {
				$.ajax({
					type: "POST",
					url: "processScan",
					data: {scanType: scanType, scannedBy: scannedBy, xmlInput: xmlInput, systemInput: systemInput},
					dataType: "JSON",
					success: function(data) {
						$("#systemInput").val('System Name').addClass('greyed');
						$("#xmlInput").val('Paste XML File Here').addClass('greyed');
						$("#loading").hide();
						$("#scanFields").show();

						var dataCombined = "";
						for(var a = 0; data[a]; a++) {
							dataCombined = dataCombined + data[a];
						}

						$("#error").html("<span class='alert'>"+dataCombined+"</span><br /><br />");
					}
				});
			}
		}
	}

// Executes when the page is FULLY loaded
	$(document).ready(function() {
		$.ajaxSetup({
			beforeSend:function() {
				$("#scanFields").hide();
				$("#loading").show();
			}
		});

		$('#scanType').change(function() {
			if ($(this).val() == 'focus') {
				$('#systemInput').css('display', 'none');
				$('#systemBreak').css('display', 'none');
			} else {
				$('#systemInput').css('display', 'inline');
				$('#systemBreak').css('display', 'inline');
			}
		});
	});
</script>

</body>
</html>