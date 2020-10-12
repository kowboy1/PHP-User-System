<?php

/* See method Check->protectThis() */
function protectThis($level) {

  $generic = new Generic(false);
  $check = new Check(false);
  return $check->protectThis($level);

}

?>
