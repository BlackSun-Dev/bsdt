<?php

function autoload($className) {
    $dirs = array("classes","classes/admin","classes/pm","classes/ss");
    foreach($dirs as $dir) {
      $file = dirname(__FILE__) . "/".$dir."/" . $className ."Class.php";
      if(file_exists($file)){
        require_once($file);
      }
    }
}

spl_autoload_register('autoload');

?>
