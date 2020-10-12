<?php

require_once '../initialise.php';

#################################################################################################### --- 

protect("Admin");

#################################################################################################### --- 

$generic = new Generic();
$settings = new Settings();

#################################################################################################### --- 

/* Number of rows per page. */

if ( !empty($_POST['showUsers'])) {
  $_SESSION['jigowatt']['users_page_limit'] = $_POST['showUsers'];
}

if ( !empty($_POST['showLevels'])) {
  $_SESSION['jigowatt']['levels_page_limit'] = $_POST['showLevels'];
}

#################################################################################################### --- 

include_once('header.php');

?>

	<div id="message"></div>

	  <div class="tabbable tabs-left">

		<ul class="nav nav-tabs">
			<li><a href="#general-options" data-toggle="tab"><i class="glyphicon glyphicon-cog"></i> <?php echo _('General'); ?></a></li>
			<li><a href="#denied" data-toggle="tab"><i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo _('Denied'); ?></a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-envelope"></i> <?php echo _('Emails'); ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#emails-welcome" data-toggle="tab"><?php echo _('Welcome'); ?></a></li>
                <li><a href="#emails-activate" data-toggle="tab"><?php echo _('Activate'); ?></a></li>
                <li><a href="#emails-forgot" data-toggle="tab"><?php echo _('Forgot'); ?></a></li>
                <li><a href="#emails-add-user" data-toggle="tab"><?php echo _('Add user'); ?></a></li>
                <li><a href="#emails-acct-update" data-toggle="tab"><?php echo _("'My Account' changes"); ?></a></li>
              </ul>
            </li>
			<li><a href="#user-profiles" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> <?php echo _('Profiles'); ?></a></li>
			<li><a href="#integration" data-toggle="tab"><i class="glyphicon glyphicon-random"></i> <?php echo _('Integration'); ?></a></li>
			<li><a href="#update" data-toggle="tab"><i class="glyphicon glyphicon-flag"></i> <?php echo _('Update'); ?> <?php if($settings->newUpdate()) : ?><span class="label label-info"><?php echo _('new'); ?></span><?php endif; ?></a></li>
			<li><a href="#smtp-configuration" data-toggle="tab"><i class="glyphicon glyphicon-wrench"></i> <?php echo _('SMTP config'); ?></a></li>
		</ul>

		<form class="" method="post" action="settings.php" id="settings-form">

			<div class="tab-content">

				<!-- - - - - - - - - - - - - - - - -

						General

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 display" id="general-options">
					<?php include_once('page/general-options.php'); ?>
				</div>

				<!-- - - - - - - - - - - - - - - - -

						Denied messages

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="denied">
          <?php include_once('page/denied.php'); ?>
				</div>
          
				<!-- - - - - - - - - - - - - - - - -

						Emails - Welcome

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="emails-welcome">
          <?php include_once('page/emails-welcome.php'); ?>
				</div>

				<!-- - - - - - - - - - - - - - - - -

						Emails - Activate

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="emails-activate">
          <?php include_once('page/emails-activate.php'); ?>
				</div>

				<!-- - - - - - - - - - - - - - - - -

						Emails - Forgot

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="emails-forgot">
          <?php include_once('page/emails-forgot.php'); ?>
				</div>

				<!-- - - - - - - - - - - - - - - - -

						Emails - Add User

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="emails-add-user">
          <?php include_once('page/emails-add-user.php'); ?>
				</div>

				<!-- - - - - - - - - - - - - - - - -

						Emails - Account update

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="emails-acct-update">
          <?php include_once('page/emails-acct-update.php'); ?>
				</div>

				<!-- - - - - - - - - - - - - - - - -

						Profiles

				- - - - - - - - - - - - - - - - - -->

				<div class="tab-pane col-md-10 fade" id="user-profiles">
          <?php include_once('page/user-profiles.php'); ?>
				</div>

				<!-- - - - - - - - - - - - - - - - -

						Integration

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="integration">
          <?php include_once('page/integration.php'); ?>
				</div>

				<!-- - - - - - - - - - - - - - - - -

						Update

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="update">
          <?php include_once('page/update.php'); ?>
				</div>
				
				<!-- - - - - - - - - - - - - - - - -

						SMTP configuration

				- - - - - - - - - - - - - - - - - -->
				
				<div class="tab-pane col-md-10 fade" id="smtp-configuration">
          <?php include_once('page/smtp-configuration.php'); ?>
				</div>

			</div>
			<div class="col-md-12">
				<div class="form-actions">
					<button type="submit" data-loading-text="<?php echo _('saving...'); ?>" data-complete-text="<?php echo _('Changes saved'); ?>" name="save-settings" class="btn btn-primary" id="save-settings"><?php echo _('Save changes'); ?></button>
				</div>
			</div>
		</form>
	  </div>

<?php

include_once('footer.php');
