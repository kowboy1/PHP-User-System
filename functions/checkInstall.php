<?php

/**
 * Checks whether the script has been installed.
 */

function checkInstall () {
  
  global $dbh;
  
  if (!isEmpty($dbh)) {
    
    $query = $dbh->query("
      SHOW TABLES
      LIKE 'login_settings'
    ");
    
    if ($query->rowCount() === 1) {
      
      // if table "login_settings" exists, installation is considered complete
      return true;
    }
  }
  
  return false;
  
  /*
  if ( ! file_exists(dirname(dirname(__FILE__)) . '/config.php')) {
    
    return "
      <div class='alert alert-warning'>"._('Installation has not yet been ran!')."</div>
      <h1>" . _('Woops!') . "</h1>
      <p>" . _('You\'re missing a config.php file preventing a database connection from being made.') . "</p>
      <p>" . _('Please click the green ') . " <a href='/home.php'>"._('Begin Install')."</a> " . _('button on the home page to create a config file.') . "</p>
    ";
  }
  */
}

?>
