<?php

/**
 * Couple functions, including a mammoth pagination one.
 *
 * LICENSE:
 *
 * This source file is subject to the licensing terms that
 * is available through the world-wide-web at the following URI:
 * http://codecanyon.net/wiki/support/legal-terms/licensing-terms/.
 *
 * @author       Jigowatt <info@jigowatt.co.uk>
 * @copyright    Copyright © 2009-2019 Jigowatt Ltd.
 * @license      http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 * @link         http://codecanyon.net/item/php-login-user-management/49008
 */

/* Number of rows per page. */
if ( !empty($_POST['showUsers'])) {
	$_SESSION['jigowatt']['users_page_limit'] = $_POST['showUsers'];
}

if ( !empty($_POST['showLevels'])) {
	$_SESSION['jigowatt']['levels_page_limit'] = $_POST['showLevels'];
}
