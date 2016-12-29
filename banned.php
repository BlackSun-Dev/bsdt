<?php

session_start();

if($_SESSION == array()){
  header("Location: login");
}
$username = $_SESSION['username'];
$_SESSION = array();
session_destroy();

?>

<html lang="en" dir="ltr">
<head>
  <title>BSDT - User Banned</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <link rel="stylesheet" type="text/css" href="style/login-style.css"/>
  <link href="style/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">
</head>
<body onload="setFocus()">
  <div class="container signin">
    <div class="login-header ui-corner-top">
      BSDT
    </div>
    <div class="body-container ui-corner-bottom">
      <div>
        <div class="messages"></div>
        <div class="section-text" style="font-size: 16px;">
          The user, <?php echo $username; ?>, is banned. You can not access BSDT. If you believe this is a mistake please see your commanding officer.
        </div>
        <br/>
        <div class="section-text">
          Copyright <?php echo date('Y'); ?> - Black Sun a <a href="http://swcombine.com">Star Wars Combine</a> faction.
        </div>
      </form>
    </div>
  </div>
</body>
</html>
