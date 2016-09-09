<?php
session_start();
?>
<html lang="en" dir="ltr">
<head>
  <title>BSDT - Login</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <link rel="stylesheet" type="text/css" href="lib/style/login-style.css"/>
  <link href="lib/style/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">
</head>
<body onload="setFocus()">
  <div class="container signin">
    <div class="login-header ui-corner-top">
      BSDT Login
    </div>
    <!-- .header -->
    <div class="body-container ui-corner-bottom">
      <div>
        <form method="POST" action="process-login.php">
          <div class="messages">

          </div>

          <div class="input-container">
            <input id="username" class="formField" type="text" name="username" placeholder="Username" /><!---->
          </div>

          <div class="input-container">
            <input id="password" class="formField" type="password" name="password"  placeholder="Password" />
          </div>

          <div class="section-text" style="font-size: 16px;">
            For access or a password reset, see your Commanding Officer.
          </div>
          <br/>
          <div class="buttons">
            <input type="submit" value="Login" name="login" class="button ui-corner-all dropShadow center">
          </div><!-- .buttons -->
          <br/>
            <div class="section-text">
              Copyright <?php echo date('Y'); ?> - Black Sun a <a href="http://swcombine.com">Star Wars Combine</a> faction.
            </div>
        </form>
      </div><!-- .body -->
    </div><!-- .container -->
  </body>
  </html>
