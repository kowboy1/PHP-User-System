<?php

function isEmpty($value){
  
  // In contrast to empty(), this function will only consider as empty the values we specify.
  
  $empty = [
    false,
    NULL,
    [],
    ''
  ];
  
  // Third parameter TRUE checks for variable type as well.
  
  if(in_array($value, $empty, true) === true){
    return true;
  }else{
    return false;
  }
}

?>
