<?php

/* See method Check->protectPage() */
function protect($level) {
  
  include_once( '../classes/generic.class.php' );
  $generic = new Generic();
  
  $check = new Check();
  $check->protectPage($level);

}

?>
