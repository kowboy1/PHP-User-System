<?php

include_once '../../initialise.php';

#################################################################################################### --- 

$generic = new Generic();

protect("Admin");

/* Number of rows per page. */
if ( !empty($_POST['showUsers'])) {
	$_SESSION['jigowatt']['users_page_limit'] = $_POST['showUsers'];
}

if ( !empty($_POST['showLevels'])) {
	$_SESSION['jigowatt']['levels_page_limit'] = $_POST['showLevels'];
}
