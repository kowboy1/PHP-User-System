<?php

function checkConfig ($checkWritable = false) {
  
  if( ! is_file(CONFIG_FILE_PATH)){
    // config does not exist or is not a regular file
    return _('The config.php file seems to be missing! The application cannot work without it!');
  }
  
  // ----------
  
  if( ! is_readable(CONFIG_FILE_PATH)) {
    // config file exists but is not readable
    return _('The config.php file needs to be readable by the web server!');
  }
  
  // ----------
  
  if ($checkWritable) {
    if( ! is_writable(CONFIG_FILE_PATH)) {
      // config file exists but is not writable
      return _('The config.php file needs to be writable by the web server!');
    }
  }
  
  // ----------
  
  return true;
}

?>
