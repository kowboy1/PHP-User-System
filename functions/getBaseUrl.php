<?php

function getBaseUrl ($basePath = false) {
  
  if ( ! $basePath) {
    // get the base path, assuming it is located one level above the present directory ("functions")
    $basePath = preg_replace('~/functions$~', '', __DIR__);
  }
  
  // remove possible front slash
  $basePath = preg_replace('~^/~', '', $basePath);
  
  // ----------
  
  $pathBits = explode('/', $basePath);
  $urlBits  = explode('/', preg_replace('~^/~', '', $_SERVER['SCRIPT_NAME']));
  
  // ----------
  
  $result = [];

  for ($i = count($urlBits) - 1; $i >= 0; --$i) {
    
    if(basename($basePath) === $urlBits[$i]){
      
      $result[$i] = [];
      
      for ($a = count($pathBits) - 1; $a >= 0; --$a) {
        
        for ($x = $i; $x >= 0; --$x) {
          
          if($pathBits[$a] === $urlBits[$x]){
            
            $result[$i][] = $pathBits[$a];
          }
        }
      }
    }
  }

  // ----------
  
  foreach($result as $res){
    
    krsort($res);
    
    $common = implode('/', $res);
    
    if(preg_match('~' . $common . '$~', $basePath)){
      
      return preg_replace('~(.*' . $common . ').*~', '$1', $_SERVER['SCRIPT_NAME']);
    }
  }
  
  # Base path is empty when installed directly in the public_html dir or similar (subdomains, etc).
  return '';
}

?>
