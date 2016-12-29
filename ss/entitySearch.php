<?php
include('../layout.php');
head("Entity Search");

$users = new User;
$permissionLevel = isset($_SESSION['userId']) ? $users->getPermissionLevel($_SESSION['userId']) : null;
$mysqli = Database::getInstance();

	if ($permissionLevel >= 4) {
  $_POST['searchPanel'] = 1;

  $ownerList = '<option value="none">Owner Name</option>';
  $ownerListArray = array();
  $entityTypeList = '<option value="none">Entity Type</option>';
  $entityTypeListArray = array();
  $systemRetrieved = array();
  $systemList = '<option value="none">System</option>';

  $yearRetrieved = array();
  $fromYearList = '<option value="none">From (Year)</option>';
  $toYearList = '<option value="none">To (Year)</option>';
  $fromDayList = '<option value="none">From (Day)</option>';
  $toDayList = '<option value="none">To (Day)</option>';

  $querySystemsList = '';

  if ($permissionLevel >= 5) {
    $stmt = $mysqli->prepare("SELECT `system`, `years` FROM `changesbasic`");
    echo $mysqli->error;

    $stmt -> execute();
    $stmt -> store_result();
    $stmt -> bind_result($system, $year);

    while ($stmt -> fetch()) {
      if (!in_array($system, $systemRetrieved)) {
        // Adds systems to dropdown and tracks systems added to prevent duplicates
        $systemRetrieved[] = $system;
        $querySystemsList .= $system . ',';
        $systemList .= '<option value="' . $system . '">' . $system . '</option>';
      }

      if (!in_array($year, $yearRetrieved)) {
        // Adds years scanned to both dropdowns and tracks years added to prevent duplicates
        $yearRetrieved[] = $year;
        $fromYearList .= '<option value="' . $year . '">' . $year . '</option>';
        $toYearList .= '<option value="' . $year . '">' . $year . '</option>';
      }
    }

    // Creates list of days for dropdown
    for ($a = 0; $a < 365; $a ++) {
      $fromDayList .= '<option value="' . $a . '">' . $a . '</option>';
      $toDayList .= '<option value="' . $a . '">' . $a . '</option>';
    }

  } else {
    $stmt = $mysqli->prepare("SELECT `Systems` FROM `groups` WHERE `GroupID` = ?");
    echo $mysqli->error();

    $stmt -> bind_param('i', $_SESSION['userGroup']);
    $stmt -> execute();
    $stmt -> store_result();
    $stmt -> bind_result($systems);
    $stmt -> fetch();

    if (isset($systems) != 'undefined') {
      $systems = explode(';', $systems);

      foreach ($systems as $key => $value) {
        $querySystemsList .= $value . ',';
        $systemList .= '<option value="' . $value . '">' . $value . '</option>';
      }
    }
  }

  $querySystemsList = substr($querySystemsList, 0, -1);

  $query = "SELECT `entityTypeName`, `ownerName` FROM `changesbasic` WHERE FIND_IN_SET(`system`, '" . $querySystemsList . "') ORDER BY `ownerName`;";
  $result = $mysqli -> query($query);

  while ($row = $result -> fetch_assoc()) {
    if (!in_array($row['ownerName'], $ownerListArray)) {
      $ownerListArray[] = $row['ownerName'];
      $ownerList .= '<option value="' . $row['ownerName'] . '">' . $row['ownerName'] . '</option>';
    }

    if (!in_array($row['entityTypeName'], $entityTypeListArray)) {
      $entityTypeListArray[] = $row['entityTypeName'];
    }

  }

  sort($entityTypeListArray);

  foreach ($entityTypeListArray as $key => $value) {
    $entityTypeList .= '<option value="' . $value . '">' . $value . '</option>';
  }
}
?>

<div class="contentContainer center">
  <div class="mainContent ui-corner-all dropShadow center textLeft">
    <div class="textCenter" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo SYSTEM_VERSION; ?></span></div>
    <h3>Entity Search</h3>
    <hr class="left">
    <div id="reportContainer" class="textCenter">
      <?php if (($permissionLevel >= 4)) { ?>
        <div id="blockContainer" style="float: left; width: 35%" class="textCenter">
          <h4>Select Filters</h4>
          <hr>
          <select name="addFilter" id="addFilter" class="dropShadow formField ui-corner-all" style="width: 215px;">
            <option value="select">Select Filter(s)</option>
            <option value="iff">IFF</option>
            <option value="entityType">Entity Type</option>
            <option value="owner">Owner</option>
            <option value="system">System</option>
            <option value="time">Time Range</option>
          </select>
        </div>
        <div id="blockContainer" style="float: right; width: 65%" class="textCenter">
          <h4>Active Filters</h4>
          <hr>
          <div id="iffSelectContainer" class="textCenter ui-corner-all dropShadow borderAll" style="display: none; width: 100%; padding: 10px 0 10px 0;">
            <div class="filterDelete" style="z-index: 10;" filterClose="iffSelect"><span class="ui-icon ui-icon-circle-close"></span></div>
            <div class="textLeft" style="width: 100%; padding-left: 10px;">IFF</div>
            <hr>
            <div id="iffSelectButtonSet" class="buttonSet center" style="width: 100%;">
              <input type="radio" id="iffSelect1" name="iffSelect" value="all" /><label for="iffSelect1" style="width: 24%">All</label>
              <input type="radio" id="iffSelect2" name="iffSelect" value="Enemy" /><label for="iffSelect2" style="width: 24%">Enemy</label>
              <input type="radio" id="iffSelect3" name="iffSelect" value="Neutral" /><label for="iffSelect3" style="width: 24%">Neutral</label>
              <input type="radio" id="iffSelect4" name="iffSelect" value="Friend" /><label for="iffSelect4" style="width: 24%">Friendly</label>
            </div>
          </div>
          <br id="iffSelectBreak" style="display: none;" />
          <div id="entitySelectContainer" class="textCenter ui-corner-all dropShadow borderAll" style="display: none; width: 100%; padding: 10px 0 10px 0;">
            <div class="filterDelete" style="z-index: 10;" filterClose="entitySelect"><span class="ui-icon ui-icon-circle-close"></span></div>
            <div class="textLeft" style="width: 100%; padding-left: 10px;">Entity Type</div>
            <hr>
            <select id="entitySelect" name="entitySelect" class="formField ui-corner-all">
              <?php echo $entityTypeList; ?>
            </select>
          </div>
          <br id="entitySelectBreak" style="display: none;" />
          <div id="ownerSelectContainer" class="textCenter ui-corner-all dropShadow borderAll" style="display: none; width: 100%; padding: 10px 0 10px 0;">
            <div class="filterDelete" style="z-index: 10;" filterClose="ownerSelect"><span class="ui-icon ui-icon-circle-close"></span></div>
            <div class="textLeft" style="width: 100%; padding-left: 10px;">Owner</div>
            <hr>
            <select id="ownerSelect" name="ownerSelect" class="formField ui-corner-all">
              <?php echo $ownerList; ?>
            </select>
          </div>
          <br id="ownerSelectBreak" style="display: none;" />
          <div id="systemSelectContainer" class="textCenter ui-corner-all dropShadow borderAll" style="display: none; width: 100%; padding: 10px 0 10px 0;">
            <div class="filterDelete" style="z-index: 10;" filterClose="systemSelect"><span class="ui-icon ui-icon-circle-close"></span></div>
            <div class="textLeft" style="width: 100%; padding-left: 10px;">System</div>
            <hr>
            <select id="systemSelect" name="systemSelect" class="formField ui-corner-all">
              <?php echo $systemList; ?>
            </select>
          </div>
          <br id="systemSelectBreak" style="display: none;" />
          <div id="timeSelectContainer" class="textCenter ui-corner-all dropShadow borderAll" style="display: none; width: 100%; padding: 10px 0 10px 0;">
            <div class="filterDelete" style="z-index: 10;" filterClose="timeSelect"><span class="ui-icon ui-icon-circle-close"></span></div>
            <div class="textLeft" style="width: 100%; padding-left: 10px;">Time Range</div>
            <hr>
            <select id="fromYearSelect" name="fromYearSelect" class="formField ui-corner-all">
              <?php echo $fromYearList; ?>
            </select>
            <select id="fromDaySelect" name="fromDaySelect" class="formField ui-corner-all">
              <?php echo $fromDayList; ?>
            </select>
            <select id="toYearSelect" name="toYearSelect" class="formField ui-corner-all">
              <?php echo $toYearList; ?>
            </select>
            <select id="toDaySelect" name="toDaySelect" class="formField ui-corner-all">
              <?php echo $toDayList; ?>
            </select>
          </div>
          <br id="timeSelectBreak" style="display: block;" />
          <button id="searchSubmit" class="button ui-corner-all dropShadow center" onClick="search();">Search</button><br /><br />
        </div>
      </div>
      <?php } else { ?>
        <span class="alert">You are not authorized to view this page.</span>
        <?php } ?>
      </div>
    </div>

    <script type="text/javascript">
    // Define functions
    function search() {
      var iffValue = $("input[name=iffSelect]:checked").val();
      var entityValue = $("#entitySelect").val();
      var ownerValue = $("#ownerSelect").val();
      var systemValue = $("#systemSelect").val();
      var fromYearValue = $("#fromYearSelect").val();
      var fromDayValue = $("#fromDaySelect").val();
      var toYearValue = $("#toYearSelect").val();
      var toDayValue = $("#toDaySelect").val();

      $.ajax({
        type: "POST",
        url: "processSearch",
        data: {
          systemValue: systemValue,
          iffValue: iffValue,
          entityValue: entityValue,
          ownerValue: ownerValue,
          fromYearValue: fromYearValue,
          fromDayValue: fromDayValue,
          toYearValue: toYearValue,
          toDayValue: toDayValue
        },
        dataType: "JSON",
        success: function(data) {
          if (data['status'] == 'error') {
            $("#reportContainer").html('<span class="alert">' + data['error'] + '</span>');
          } else {
            var tableReport = '';
            for(var a = 0; data[a]; a++) {
              tableReport = tableReport + data[a];
            }
            $('#reportContainer').html(tableReport);
            progressBar();
          }
        }
      });
    }
    // Executes when the page is FULLY loaded
    $(document).ready(function() {
      $("body").on("click", "button.historyButton", function() {
        var entityID = $(this).attr('entityID');

        if ($(this).hasClass("expand")) {
          $(this).html("<span class='ui-icon ui-icon-minus'></span>").removeClass("expand").addClass("hide");
          $("#table"+entityID).show();
        } else {
          $(this).html("<span class='ui-icon ui-icon-plus'></span>").removeClass("hide").addClass("expand");
          $("#table"+entityID).hide();
        }
      });

      $("#iffSelectButtonSet").buttonset();

      $("div.filterDelete").click(function() {
        var filterClose = $(this).attr("filterClose");

        $("#" + filterClose + "Container").hide();
        $("#" + filterClose + "Break").hide();

        if (filterClose == "iffSelect") {
          $("#iffSelectButtonSet input").removeAttr("checked");
          $("#iffSelectButtonSet").buttonset("refresh");
        } else {
          $("#" + filterClose).val("none");
        }
      });

      $("#addFilter").change(function () {
        if ($(this).val() == "iff") {
          $("#iffSelectContainer").show();
          $("#iffSelectBreak").show();
          $("input:#iffSelect1").checked
        } else

        if ($(this).val() == "entityType") {
          $("#entitySelectContainer").show();
          $("#entitySelectBreak").show();
        }

        if ($(this).val() == "owner") {
          $("#ownerSelectContainer").show();
          $("#ownerSelectBreak").show();
        }

        if ($(this).val() == "system") {
          $("#systemSelectContainer").show();
          $("#systemSelectBreak").show();
        }

        if ($(this).val() == "time") {
          $("#timeSelectContainer").show();
          $("#timeSelectBreak").show();
        }

        $("#addFilter").val("select");
      });
    });
    </script>

  </body>
  </html>
