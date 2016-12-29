<?php
$users = new User;
$permissionLevel = isset($_SESSION['userId']) ? $users->getPermissionLevel($_SESSION['userId']) : null;
$urlPath = $_SERVER["HTTP_HOST"].'/bsdt';
if($users->isBanned($_SESSION['userId'])){
	header("Location: banned");
}
?>

<div id="menuRight">
	<div id="menuInformation">
		<div class="menuContent dropShadow ui-corner-all">
			<span class="menuHeader">Information</span><hr>
			<div class="textCenter">
			<?php
			if (isset($_SESSION['userId'])) { ?>
				Logged in as:<br />
				<span style="color: #FFF;"><?php echo $_SESSION['username']; ?></span><br />

				<form method="POST" action="http://<?php echo $urlPath; ?>/logout.php">
					<input type="submit" value="Logout" name="logout" class="button ui-corner-all dropShadow center">
				</form>
				<?php } else { ?>
					<form method="POST" action="process-login.php">
						<input id="userName" name="username" type="text" class="formLogin formField dropShadow ui-corner-all" value="Username" onfocus="formFocus(this);" onblur="formBlur(this);"/><br />
						<input id="password" name="password" type="password" class="formLogin formField dropShadow ui-corner-all" value="Password" onfocus="formFocus(this);" onblur="formBlur(this);"/><br />
						<input type="submit" value="Login" name="login" class="button ui-corner-all dropShadow center">
					</form>
					<?php } ?>
				</div>
			<?php if (isset($_SESSION['userId'])) { ?>
					<span class="menuHeader">Navigation</span><hr>
					<a href="http://<?php echo $urlPath; ?>/index">Home</a><br />
					<?php if ($permissionLevel >= $users->permissionIndex("Consiglio")) { ?>
						<a href="http://<?php echo $urlPath; ?>/admin/userManagement">User Management</a><br />
						<?php } if ($permissionLevel >= $users->permissionIndex("Throne")) { ?>
							<a href="http://<?php echo $urlPath; ?>/admin/scrubDatabase">Scrub Database</a><br />
							<?php } if ($permissionLevel >= $users->permissionIndex("Vigo")) { ?>
								<a href="http://<?php echo $urlPath; ?>/admin/groupManagement">Group Management</a><br />
								<?php } ?>
									<a href="http://<?php echo $urlPath; ?>/changePassword">Change Password</a><br/>
									<br/><span class="menuHeader">Scanning</span><hr>
									<a href="http://<?php echo $urlPath; ?>/ss/scan">Submit Scan</a><br />
									<?php if ($permissionLevel >= $users->permissionIndex("Assistant")) { ?>
										<a href="http://<?php echo $urlPath; ?>/ss/report">Current Scans</a><br />
										<a href="http://<?php echo $urlPath; ?>/ss/history">System History</a><br />
										<a href="http://<?php echo $urlPath; ?>/ss/entitySearch">Entity Search</a><br />
									<?php } ?><br/>
										<span class="menuHeader">Prospecting</span><hr>
										<?php if($permissionLevel >= $users->permissionIndex("Consiglio")){ ?>
											<a href="http://<?php echo $urlPath; ?>/pm">Systems Management</a><br/>
											<a href="http://<?php echo $urlPath; ?>/pm">Planets Management</a><br/>
											<?php } ?>
											<a href="http://<?php echo $urlPath; ?>/pm">Add Deposits XML</a><br/>
										</div>
									</div>
<?php } ?>
									<?php if ($permissionLevel >= $users->permissionIndex("User")) { ?>
										<br />
										<div class="menuSection dropShadow">
											<div class="menuContent ui-corner-all dropShadow">
												<span class="menuHeader">Control</span><hr>
												<div class="textCenter">
												<select id="selectSystem" class="dropShadow formField ui-corner-all" style="width: 95%">
													<?php //echo $systemList; ?>
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
