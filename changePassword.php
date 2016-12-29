<?php
include("layout.php");
head("Change Password");
$submitted = false;
if(isset($_POST['submit'])){
  $user = new User;
  if($_POST['newPassword'] == $_POST['confirmPassword']){
    if($user->changePassword($_SESSION['userId'], $_POST['currPassword'], $_POST['newPassword'])){
      $success = true;
    }
  }
  else {
    $success = false;
  }
  $submitted = true;
}
?>
<div class="contentContainer center">
  <div class="mainContent ui-corner-all dropShadow center textCenter">
    <div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo SYSTEM_VERSION; ?></span></div>

    <div style="width: 100%;" class="textLeft">
      <h3>Change Password</h3>
    </div>

    <hr class="left">
    <?php if (isset($_SESSION['userId'])) {
      if($submitted == true && $success == false){ ?>
        <div style="width: 100%">
          <span class="alert">Error: Passwords are not the same.</span><br /><br />
        </div>
        <?php }
        else if($submitted == true && $success == true) { ?>
          <span class="alert">Password changed successfully.</span>
          <?php } else {?>
            <div>
              <div class="textRight" style="position: absolute; left: 250px; top: -1px; line-height: 24px;">
                Current Password:<br />
                New Password:<br />
                Confirm Password:<br />
              </div>
              <form method="POST" action="changePassword.php">
                <input type="hidden" name="mode" value="password">
                <input type="password" name="currPassword" class="formChangePassword formField dropShadow ui-corner-all" value="" onfocus="formFocus(this);" onblur="formBlur(this);"><br />
                <input type="password" name="newPassword" class="formChangePassword formField dropShadow ui-corner-all" value="" onfocus="formFocus(this);" onblur="formBlur(this);"><br />
                <input type="password" name="confirmPassword" class="formChangePassword formField dropShadow ui-corner-all" value="" onfocus="formFocus(this);" onblur="formBlur(this);"><br /><br />
                <input type="submit" value="Submit" name="submit" class="button ui-corner-all dropShadow">
              </form>
            </div>
            <?php }
          } else { ?>
            <span class="alert">You are not authorized to view this page.</span>
            <?php }?>
          </div>
        </div>

        <script type="text/javascript">
        // Define functions
        // Executes when the page is FULLY loaded
        $(document).ready(function() {
        });
        </script>

      </body>
      </html>
