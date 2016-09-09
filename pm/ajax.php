<?php
  require "functions/depositClass.php";
  $id = $_REQUEST['id'];
  $page = $_REQUEST['page'];
  if($page == 'planet'){
  Deposit::deleteDeposit($id);
}
/*if($page == 'planets'){
  Planet::deletePlanet($id);
}
if($page == 'systems'){
  System::deleteSystem($id);
}*/
 ?>
