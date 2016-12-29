<?php
define("PATH_ROOT", dirname(__FILE__));
define("STYLE_ROOT", 'http://'.$_SERVER['SERVER_NAME'].'/bsdt');
define("SYSTEM_VERSION", 'v1.0');
require_once PATH_ROOT."/lib/autoloader.php";
if(!isset($_SESSION)){
  session_start();
}

function head($page = null){
  ?>
  <html>
  <head>
    <link href="<?php echo STYLE_ROOT; ?>/style/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo STYLE_ROOT; ?>/style/style.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="<?php echo STYLE_ROOT; ?>/js/formFocus.js"></script>

    <script type="text/javascript" src="<?php echo STYLE_ROOT; ?>/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?php echo STYLE_ROOT; ?>/js/jquery-ui-1.10.3.custom.js"></script>

    <script type="text/javascript" src="<?php echo STYLE_ROOT; ?>/js/common.js"></script>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>BSDT <?php if(!$page == null) echo " - ". $page; ?></title>
  </head>
  <body>
    <br /><div id="header" class="center"></div><br />
    <?php
    if(!($page == "Install")){
      include PATH_ROOT."/menu.php";
    }
  }

  ?>
