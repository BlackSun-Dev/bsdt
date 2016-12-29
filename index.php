<?php
include("layout.php");

head();

if (!isset($_SESSION['userId'])) {
	header("Location: login");
}
?>

<div class="contentContainer center">
	<div class="mainContent ui-corner-all dropShadow textLeft">
		<div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo SYSTEM_VERSION; ?></span></div>
		<h3>Information</h3>
		<hr class="left">
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras purus tellus, fermentum at lacus et, dignissim finibus velit. Nullam eleifend ex non semper pretium. Cras ullamcorper, mi eu ultrices egestas, massa risus vestibulum diam, sit amet eleifend augue nunc eget sem. Proin sit amet elit lacus. Ut tempor, lorem non porttitor aliquam, felis neque elementum ante, in elementum sapien neque ut dolor. Cras eget pharetra leo. Nullam cursus velit lobortis accumsan facilisis. Nulla sit amet velit tincidunt, rhoncus urna a, pharetra est. Mauris vitae nunc rhoncus quam facilisis tincidunt. Quisque erat nisl, mollis sed odio a, interdum semper ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
		</p>
		<p>Curabitur eget vehicula lorem. Morbi in erat ex. Aenean posuere ullamcorper semper. Proin vitae mi vestibulum, eleifend massa quis, porttitor libero. Nunc quis hendrerit risus. Suspendisse placerat feugiat commodo. Phasellus a justo dui. Nullam tincidunt mauris in lectus consectetur placerat. Integer ut lacinia mauris. Curabitur euismod vel nulla quis pharetra. Vivamus nec lectus vitae ligula mollis suscipit. Duis eu urna tincidunt, aliquam purus quis, efficitur tortor. Vestibulum neque ante, interdum vitae justo sed, rhoncus auctor ipsum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc et tortor sollicitudin, vestibulum massa at, semper magna. Quisque in leo hendrerit, suscipit nisi quis, dignissim ligula.
		</p>
		<p>Nulla mollis ullamcorper dolor, porta ornare justo. Donec posuere magna dolor, at lacinia augue ultricies vel. Morbi finibus ligula ac elit faucibus, rutrum aliquam urna porttitor. Donec ultricies lorem justo, nec fringilla orci hendrerit ac. Donec feugiat urna ut metus feugiat, non consequat risus convallis. Mauris mollis nulla ipsum, vel facilisis erat luctus at. Maecenas ut lacus metus.
		</p>
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
