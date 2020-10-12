<?php

function dbConnect($dbHost, $dbName, $dbUser, $dbPass){
  
  try {
    $dbh = new PDO("mysql:host=" . $dbHost . ";dbname=" . $dbName, $dbUser, $dbPass);
    $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    // unicode support
    $dbh->query("
      SET NAMES 'utf8'
      COLLATE 'utf8_unicode_ci'"
    );
  } catch (PDOException $e) {
    
    return false;
  }
  
  return $dbh;
}

?>
