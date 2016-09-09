<?php
    require "lib/autoloader.php";

    include "../lib/style/header.php";
    include "..//menu.php";
    include "../lib/style/logo.php";

$planets = new Planet;
$deposits = new Deposit;
$systems = new System;
$planetId = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
?>
  <div class="contentContainer center">
    <div class="mainContent ui-corner-all dropShadow center textCenter">
      <div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo $_SESSION['currentVersion']; ?></span></div>

      <div style="width: 100%;" class="textLeft">
        <h3><?php if(isset($planetId)){ echo $planets->getPlanetName($planetId) . " - "; } ?>Planet Details</h3>
      </div>
      <hr class="left">
      <?php if (isset($_GET['error'])) { ?>
        <div style="width: 100%">
          <span class="alert"><?php echo $_SESSION['error'][$_GET['error']];?></span><br /><br />
        </div>
        <?php }
        if (isset($_SESSION['userId']) && isset($planetId)) {
          $depositRef = $deposits->getDepositsPlanet($planetId);
          ?></br/>
          <div>
            <a href="deposits?id=<?php echo $planetId; ?>" class="buttonLink"><button class="button ui-corner-all dropShadow" style="display:inline-block;">New Deposit</button></a>&nbsp;<button id="toggleButton" class="button ui-corner-all dropShadow" style="display:inline-block;">Show Map</button>
          </div><br/>
          <div id="depositTable" class="center">
            <table class="reportTable" cellspacing="0">
              <?php
              if(!is_null($depositRef)){ ?>
                <tr>
                  <td><b>Type</b></td>
                  <td><b>Size</b></td>
                  <td><b>Planet Location</b></td>
                </tr>
                <?php foreach ($depositRef as $d):
                  echo  "<tr>".
                  "<td>".$deposits->getTypeName($d['depositType'])."</td>".
                  "<td>".number_format($d['depositSize'])."</td>".
                  "<td>".$d['plaX'] . ", " . $d['plaY']."</td>".
                  '<td><a id="deleteRecord">X</a><input id="deleteRecordValue" type="hidden" value="'.$d['depositId'].'"/></td>'.
                  "</tr>";
                endforeach;
              } else{ ?>
                <span class="alert">No deposit records exist for the planet <?php echo $planets->getPlanetName($planetId); ?>.</span>
                <?php }  ?>
              </table>
            </div>
            <div id="depositMap" style="display:none;" class="center">
              <?php  if($planets->checkPlanetTerrain($planetId)){ ?>
                <div style="position:absolute;">
                  <?php
                  $size = $planets->getPlanetSize($planetId);
                  for($i = 0; $i < $size; $i++){
                    for($j = 0; $j < $size; $j++){ ?>
                      <img src="pm/images/terrain/<?php echo $planets->getPlanetTerrainName($i, $j, $planetId); ?>.gif" style="top: <?php echo(25*$i); ?>px; left: <?php echo(25*$j); ?>px; height: 25px; width: 25px; z-index: 3; position: absolute;" />
                      <?php  if($deposits->checkDepositLocation($i, $j, $planetId)){
                        $deposit = $deposits->getDepositByLocation($i, $j, $planetId);
                        if(!is_null($deposit)){                        ?>
                          <img src="pm/images/material/<?php echo $deposits->getTypeName($deposit['depositType']); ?>.gif" style="top: <?php echo(25*$i); ?>px; left: <?php echo(25*$j); ?>px; z-index:4; position: absolute;" />
                          <?php  }
                        }
                      }
                    }?>
                  </div>
                  <?php
                } else { ?>
                  <form method="REQUEST" action="addTerrainMap">
                    <div id="terrainInput">
                      <input type="hidden" value="<?php echo $planetId ?>" name="planetId" />
                      <?php $size = $planets->getPlanetSize($planetId);
                      $count= 0;
                      for($i = 0; $i < $size; $i++){
                        for($j = 0; $j < $size; $j++){
                          if($count % 6 == 1){
                            ?>
                            <br/>
                            <?php
                          }
                          ?>
                          <span id="terrainInputCell">
                            <label id="terrainInputLabel"><?php echo $i.", ". $j; ?></label><select name="terrainInput[<?php echo $count ?>]" class="formField dropShadow ui-corner-all">
                              <?php for($k=0; $k < 14; $k++){ ?>
                                <option value="<?php echo $planets->listTerrainName($k); ?>"><?php echo $planets->listTerrainName($k); ?></option>
                                <?php } ?>
                              </select>
                            </span>
                            <?php
                            $count++;
                          }
                        } ?>
                      </div>
                    </br/>
                    <button type="submit" class="button ui-corner-all dropShadow">Submit Terrain</button>
                  </form>
                  <?php
                } ?>
              </div>
              <?php
            }
            else { ?>
              <span class="alert">You are not authorized to view this page.</span>
              <?php } ?>
            </div>
          </div>

        <script type="text/javascript">
        $("#toggleButton").click(function(){
          var display = $("#depositMap").css("display");
          if(display == "none"){
            $("#depositTable").css("display","none");
            $("#depositMap").css("display","");
            $("#toggleButton").text("Show Table");
          } else {
            $("#depositTable").css("display","");
            $("#depositMap").css("display","none");
            $("#toggleButton").text("Show Map");
          }
        });
        </script>
        
<?php include "../lib/style/footer.php"; ?>
