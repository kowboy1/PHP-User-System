<?php

/**
 * If PHP Gettext isn't enabled, we'll still want to display content.
 */

if ( ! function_exists('_')) {
  
  function _($text) {
    
    return $text;
  }
}

?>
