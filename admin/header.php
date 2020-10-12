<?php

/**
 * Header file for Admin area.
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHP Login and User Management Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP Login and User Management script">
    <meta name="author" content="Jigowatt">

    <!-- latest stable bootstrap framework via CDN as of 24/05/2017 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <link rel="stylesheet" href="<?php echo BASE_URL . '/assets/css/datepicker.css'; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . '/assets/js/select2/select2.min.css'; ?>" >
    <link rel="stylesheet" href="<?php echo BASE_URL . '/assets/css/jigowatt.css'; ?>">
    
    <link rel="shortcut icon" href="<?php echo BASE_URL . '/favicon.ico'; ?>">
    
    <!-- latest stable jquery framework via CDN as of 24/05/2017 -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
    
    <!-- needed for the datepicker to work correctly, 02/10/2019 -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- latest stable bootstrap javascript framework via CDN as of 24/05/2017 -->
<!--     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
    
    <script src="<?php echo BASE_URL . '/assets/js/jquery.ba-hashchange.min.js'; ?>"></script>
    <script src="<?php echo BASE_URL . '/assets/js/jquery.validate.min.js'; ?>"></script>
    
    <script src="<?php echo BASE_URL . '/assets/js/jquery.placeholder.min.js'; ?>"></script>
    
    <script src="<?php echo BASE_URL . '/assets/js/select2/select2.min.js'; ?>"></script>
    
</head>

<body>

<!-- Navigation
================================================== -->

	<nav class="navbar navbar-default navbar-fixed-top">
	  <div class="container">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="<?php echo BASE_URL . '/index.php'; ?>"><?php echo _('Home'); ?></a>
	    </div>



	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav" id="findme">
						<li><a href="<?php echo BASE_URL . '/admin/index.php'; ?>"><?php echo _('Control Panel'); ?></a></li>
						<li><a href="<?php echo BASE_URL . '/admin/settings.php'; ?>"><?php echo _('Settings'); ?></a></li>
					</ul>
					
		<?php if(isset($_SESSION['jigowatt']['username'])) { ?>
		
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<p class="navbar-text dropdown-toggle" data-toggle="dropdown" id="userDrop"><?php echo $_SESSION['jigowatt']['gravatar']; ?>
          <a href="#"><?php echo $_SESSION['jigowatt']['username']; ?></a><b class="caret"></b>
				</p>
				
				<ul class="dropdown-menu">
		<?php if(in_array(1, $_SESSION['jigowatt']['user_level'])) { ?>
					<li><a href="<?php echo BASE_URL . '/admin/index.php'; ?>"><i class="glyphicon glyphicon-home"></i> <?php echo _('Control Panel'); ?></a></li>
					<li><a href="<?php echo BASE_URL . '/admin/settings.php'; ?>"><i class="glyphicon glyphicon-cog"></i> <?php echo _('Settings'); ?></a></li> <?php } ?>
					<li><a href="<?php echo BASE_URL . '/profile.php'; ?>"><i class="glyphicon glyphicon-user"></i> <?php echo _('My Account'); ?></a></li>
					<li><a href="<?php echo BASE_URL . '/documentation.php'; ?>"><i class="glyphicon glyphicon-info-sign"></i> <?php echo _('Help'); ?></a></li>
					<li class="divider"></li>
					<li><a href="<?php echo BASE_URL . '/logout.php'; ?>"><?php echo _('Sign out'); ?></a></li>
				</ul>
			</li>
		</ul>
		<?php } else { ?>
		<ul class="nav navbar-nav navbar-right">
			<li>
        <a href="<?php echo BASE_URL . '/login.php'; ?>" class="signup-link">
          <em><?php echo _('Have an account?'); ?></em>
          <strong><?php echo _('Sign in!'); ?></strong>
        </a>
			</li>
		</ul>
		<?php } ?>
   </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>

<!-- Main content
================================================== -->
		<div class="container">
			<div class="row">

				<div class="col-md-12">

					<div>
						<?php #$generic->displayMessage( $generic->get_error() ); # todo: uncomment this line ?>
					</div>

					<ol class="breadcrumb">
						<?php
              echo '
                <li>
                  <a href="' . BASE_URL . '/admin/' . (strstr($_SERVER['SCRIPT_NAME'], 'settings') !== false ? 'settings.php' : 'index.php') . '">' .
                    _(strstr($_SERVER['SCRIPT_NAME'], 'settings') !== false ? 'Settings' : 'Control Panel') . '
                  </a>
                </li>
              ';
              
              if (strstr($_SERVER['SCRIPT_NAME'], 'users.php') !== false) {
                echo '
                  <li><a href="' . BASE_URL . '/admin/index.php#/user-control">' . _('Users') . '</a></li>
                  <li class="active">' . (!empty($_GET['uid']) ? $_GET['uid'] : -1) . '</li>
                ';
              }
              
              if (strstr($_SERVER['SCRIPT_NAME'], 'levels.php') !== false) {
                echo '
                  <li><a href="' . BASE_URL . '/admin/index.php#/level-control">' . _('Roles') . '</a></li>
                  <li class="active">' . (!empty($_GET['lid']) ? $_GET['lid'] : -1) . '</li>
                ';
              }
              
						?>
					</ol>
