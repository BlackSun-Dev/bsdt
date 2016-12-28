<?php
require "lib/autoloader.php";

include "lib/style/header.php";
include "lib/style/logo.php";

$success = false;
$installed = Database::installCheck();

if(!$installed){
	if(isset($_POST['username'])){
		$mysqli = Database::getInstance();
		$sql = file_get_contents("lib/schema.sql");
		$mysqli->multi_query($sql);

		while ($mysqli->next_result()){
			if (!$mysqli->more_results()) break;
		}

		$password = md5('prefix' . md5($_POST['password']) . 'suffix');
		$username = $_POST['username'];

	  $query = $mysqli->stmt_init();
		if($query -> prepare('INSERT INTO `users` (`userHandle`, `userPass`, `userGroup`, `userLevel`) VALUES(?, ?, 0, 2)')){
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
