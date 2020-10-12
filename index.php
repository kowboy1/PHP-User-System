<?php

/**
 * This page currently redirects to home.php, which is a welcome page for the script.
 * To use your own index page, replace the "homepage content" block below with your own.
 */

#################################################################################################### --- INITIALISE & INCLUDE HEADER

require_once 'initialise.php';
require_once 'header.php';

#################################################################################################### --- HOMEPAGE CONTENT

header('Location: home.php');
exit;

#################################################################################################### --- INCLUDE FOOTER

require_once 'footer.php';

?>
