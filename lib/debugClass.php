<?php

class Debug {

  function __construct($vars){
    foreach ($vars as $v){
      echo'<div>';
      var_dump($v);
      echo'</div>';
    }
    echo'<hr/>';
  }
}

?>
