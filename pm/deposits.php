<?php
require_once  "lib/autoloader.php";

include "../lib/style/header.php";
include "..//menu.php";
include "../lib/style/logo.php";

$planets = new Planet;
$deposits = new Deposit;
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

?>
  <div class="contentContainer center">
    <div class="mainContent ui-corner-all dropShadow center textCenter">
      <div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo $_SESSION['currentVersion']; ?></span></div>

      <div style="width: 100%;" class="textLeft">
        <h3>New Deposit</h3>
      </div>
      <hr class="left">
      <?php if (isset($_GET['error'])) { ?>
        <div style="width: 100%">
          <span class="alert"><?php echo $_SESSION['error'][$_GET['error']];?></span><br /><br />
        </div>
        <?php }
        ?>
        <?php if (isset($_SESSION['userId']) && isset($id)) { ?>
          <div>
            <div class="textRight" style="position: absolute; left: 250px; top: -1px; line-height: 24px;">
              Deposit Type:<br />
              Deposit Size:<br />
              Deposit Planet:<br />
              Deposit Location:<br/>
            </div>
            <form method="POST" action="submit">
              <input type="hidden" name="addDeposit" />
              <select type="dropdown" name="depositType" class="formField dropShadow ui-corner-all" onfocus="formFocus(this);" onblur="formBlur(this);">
                <?php
                for($i=0;$i<=20;$i++){ ?>
                  <option value="<?php echo $i; ?>"><?php echo $deposits->getTypeName($i); ?>
                  </option>
                  <?php   } ?>
                </select><br />
                <input name="depositSize" class="formField dropShadow ui-corner-all" value="" onfocus="formFocus(this);" onblur="formBlur(this);">
                <br />
                <input type="hidden" name="planetId" value="<?php echo $id; ?>">
                <input name="planetName" class="formField dropShadow ui-corner-all" value="<?php echo $planets->getPlanetName($id); ?>" onfocus="formFocus(this);" onblur="formBlur(this);" disabled><br/>
                <select type="dropdown" name="location" class="formField dropShadow ui-corner-all" onfocus="formFocus(this);" onblur="formBlur(this);">
                  <?php
                  for($i=0;$i < $planets->getPlanetSize($id); $i++){
                    for($j=0;$j < $planets->getPlanetSize($id);$j++){
                      $location = $i.",".$j;
                      ?>
                      <option value="<?php echo $location; ?>"><?php echo $i.", ".$j; ?></option>
                      <?php   }
                    } ?>
                  </select>
                  <br />
                  <input type="submit" value="Submit" name="submit" class="button ui-corner-all dropShadow">
                </form>
              </div>
              <?php } else { ?>
                <span class="alert">You are not authorized to view this page.</span>
                <?php } ?>
              </div>
            </div>
            
<?php include "../lib/style/footer.php"; ?>
