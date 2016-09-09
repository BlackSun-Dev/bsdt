<?php
    require "lib/autoloader.php";

    include "../lib/style/header.php";
    include "..//menu.php";
    include "../lib/style/logo.php";
$planets = new Planet;
$deposits = new Deposit;
$systems = new System;
?>
  <div class="contentContainer center">
    <div class="mainContent ui-corner-all dropShadow center textCenter">
      <div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo $_SESSION['currentVersion']; ?></span></div>

      <div style="width: 100%;" class="textLeft">
        <h3>Planets Management</h3>
      </div>
      <hr class="left">
      <?php if (isset($_GET['error'])) { ?>
        <div style="width: 100%">
          <span class="alert"><?php echo $_SESSION['error'][$_GET['error']];?></span><br /><br />
        </div>
      <?php }
      ?>
      <?php if (isset($_SESSION['userId'])) {
      ?>
      <div>
        <table class="reportTable" cellspacing="0">
            <tr>
                <td><b>Name</b></td>
                <td><b>Type</b></td>
                <td><b>Size</b></td>
                <td><b>System Location</b></td>
                <td><b>System</b></td>
            </tr>
              <?php
                $planet = $planets->getPlanets();
              foreach ($planet as $p): ?>
              <tr>
                <td><a href=planet?id=<?php echo $p['planetId']; ?>><?php echo $p['planetName']; ?></a></td>
                <td><?php echo $p['planetType']; ?></td>
                <td><?php echo $p['planetSize'] . "x". $p['planetSize']; ?></td>
                <td><?php echo $p['sysX'] . ", " . $p['sysY']; ?></td>
                <?php echo "<td><a href=systems?id=".$p['systemId'].">". $systems->getSystemName($p['systemId']). "</a></td>"; ?>
              </tr>
            <?php endforeach; ?>
          </table>
      </div>
      <?php
  }
     else { ?>
        <span class="alert">You are not authorized to view this page.</span>
      <?php } ?>
    </div>
  </div>

<?php include "../lib/style/footer.php"; ?>
