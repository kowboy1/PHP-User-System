<?php
/**
 * Validates a password.
 *
 * A plain-text password is compared against the hashed version.
 *
 * @param     string    $password       A plain-text password.
 * @param     string    $correctHash    The hashed version of a correct password.
 * @return    bool      Whether or not the plain-text matches the correct hash.
 */

function validatePassword($password, $correctHash){
  return password_verify((string)$password, $correctHash); // true on success, false otherwise
}

?>
