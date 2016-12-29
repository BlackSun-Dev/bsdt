<?php
include('../layout.php');
head("System History");

$users = new User;
$permissionLevel = isset($_SESSION['userId']) ? $users->getPermissionLevel($_SESSION['userId']) : null;

if ($permissionLevel >= 4) {
  $_POST['historyPanel'] = 1;
}

?>
<div class="contentContainer center">
  <div class="mainContent ui-corner-all dropShadow center textCenter">
    <div class="textRight" style="position: absolute; top: 10px; right: 15px;"><span class="alert"><?php echo SYSTEM_VERSION; ?></span></div>

    <div style="width: 100%;" class="textLeft">
      <h3>System History</h3>
    </div>

    <hr class="left">
    <div id="reportContainer">
      <?php if ($permissionLevel >= 4)  { ?>
        <span class="alert">Use the control panel to select the system you want to view.</span>
        <?php } else { ?>
          <span class="alert">You are not authorized to view this page.</span>
          <?php } ?>
        </div>
      </div>
    </div>

    <script type="text/javascript">
    // Define functions
    function reportHistory() {
      var selectSystem = $('#selectSystem').val();

      $.ajax({
        type: "POST",
        url: "processHistory",
        data: {
          system: selectSystem,
        },
        dataType: "JSON",
        success: function(data) {
          //$('#reportContainer').css("display", "block");
          var tableReport = '';
          for(var a = 0; data[a]; a++) {
            tableReport = tableReport + data[a];
          }
          $('#reportContainer').html(tableReport);
          progressBar();
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
    });
    </script>

  </body>
  </html>
