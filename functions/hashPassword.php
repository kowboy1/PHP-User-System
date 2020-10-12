<?php

/**
 * Hashes a password.
 * 
 * @param     string        $password    A plain-text password.
 * @return    string        Hashed password or false if not a known method.
 */

function hashPassword($password){
  return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

?>
