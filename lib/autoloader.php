<?php

spl_autoload_register(function ($className) {
      $dirs = array(".","./admin","./pm","./ss");
    foreach($dirs as $dir) {
      $file = dirname(__FILE__) . "/".$dir."/" . $className ."Class.php";
      if(file_exists($file)){
        require_once($file);
      }
      else {
          $className = strtolower($className);
          $file = dirname(__FILE__) . "/".$dir."/" . $className ."Class.php";
          if(file_exists($file)){
            require_once($file);
          }
      }
    }
});

?>
