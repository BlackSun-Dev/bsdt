<?php
    require "lib/autoloader.php";

    include "../lib/style/header.php";
    include "..//menu.php";
    include "../lib/style/logo.php";

$planets = new Planet;
$deposits = new Deposit;
$systems = new System;
$sectors = new Sector;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

?>
  <div class="contentContainer center">
    <div class="mainContent ui-corner-all dropShadow center textCenter">
      <div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo $_SESSION['currentVersion']; ?></span></div>

      <div style="width: 100%;" class="textLeft">
        <h3><?php if(isset($id)) { echo $systems->getSystemName($id) ." System "; } else {echo "Systems";} ?> Management</h3>
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
                <?php if(isset($id)){
                echo"<td><b>Type</b></td>
                <td><b>Size</b></td>
                <td><b>System Location</b></td>";
              } else {
                  echo "<td><b>Galaxy Location</b></td>
                  <td><b>Sector</b></td>";
                } ?>
              </tr>
              <?php
              if(isset($id)){
                $planet = $planets->getPlanetbySystem($id);
                foreach ($planet as $p): ?>
                <tr>
                  <td><a href=planet?id=<?php echo $p['planetId']; ?>><?php echo $p['planetName']; ?></a></td>
                  <td><?php echo $p['planetType']; ?></td>
                  <td><?php echo $p['planetSize'] . "x". $p['planetSize']; ?></td>
                  <td><?php echo $p['sysX'] . ", " . $p['sysY']; ?></td>
                </tr>
              <?php endforeach; }
            else {
              $planet = $systems->getSystems();
              foreach ($planet as $p): ?>
              <tr>
                <td><a href=systems?id=<?php echo $p['systemId']; ?>><?php echo $p['systemName']; ?></a></td>
                <td><?php echo $p['galX'] . ", " . $p['galY']; ?></td>
                <?php echo "<td>". $sectors->getSectorName($p['sectorId']). "</td>"; ?>
              </tr>
            <?php endforeach; } ?>
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
