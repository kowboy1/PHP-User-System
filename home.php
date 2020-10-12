<?php

require_once 'initialise.php';
require_once 'header.php';

#################################################################################################### --- CHECK INSTALLATION

$installed = checkInstall(); // true or false

#################################################################################################### --- SHOW HTML

?>

<div class="jumbotron">
  <h1><?php echo _('Let\'s talk about the future.'); ?></h1>
  <h2><?php echo _('Easy user management and a comprehensive admin panel.'); ?></h2>
  
  <p>
    <a href="http://goo.gl/LEqvlC" target="_TOP" class="btn btn-info btn-lg"><?php echo _('Purchase this script'); ?> &raquo;</a>
    <a href="http://eepurl.com/jNgF9" class="btn btn-default btn-lg" target="_blank"><?php echo _('Subscribe to updates'); ?></a>
  </p>
  
  <p class="info-links">
    <a href="<?php echo BASE_URL . '/documentation.php#/install-home'; ?>" target="_blank"><?php echo _('Documentation'); ?></a>
    <a href="http://goo.gl/fbBPIr" target="_blank"><?php echo _('Support'); ?></a>
  </p>
</div>

<hr>

<div class="features">
	<div class="row">
		<h1><?php echo _('Stupendously exciting login and user management.'); ?></h1>
		<p class="intro"><?php echo _('Just the right amount of tools to get your job done.'); ?></p>
		
		<div class="col-md-6">
			<h2><?php echo _('Installation'); ?>
				<?php if ($installed) : ?>
          <small><?php echo _('Complete'); ?></small>
				<?php else : ?>
          <small><?php echo _('Not complete'); ?></small>
				<?php endif; ?>
			</h2>
			
			<p><?php echo _('Get setup in minutes! Enjoy the super easy installation wizard to walk you through the setup process.'); ?></p>
			
			<?php if (!$installed) : ?>
        <p><?php echo _('Start your installation by clicking the button below!'); ?></p>
        <p><a href="install/install.php" class="btn btn-success"><?php echo _('Begin Install'); ?></a></p>
			<?php endif; ?>
		</div>

		<div class="col-md-6">
			<h2><?php echo _('Reports'); ?></h2>
			<p><?php echo _('Keep track of how your site is doing with dynamic graphs to aid you.'); ?></p>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<h2><?php echo _('Security'); ?></h2>
			<p><?php echo _('Our script has a couple default secure pages to get you started.'); ?></p>
		</div>

		<div class="col-md-6">
			<h2><?php echo _('Admin tools'); ?></h2>
			<p><?php echo _('Enjoy the luxury of having control over every aspect of your website.'); ?></p>
		</div>
	</div>
</div>

<br><br><hr>

<div class="demo features">
	<div class="row">
		<h1><?php echo _('Demo credentials.'); ?></h1>
		<p class="intro"><?php echo _('Whatcha waiting for? Login and check out the site!'); ?></p>

		<div class="col-md-3 col-md-offset-2">
			<h2><?php echo _('Administrator'); ?></h2><br>
			<code><?php echo _('Username: admin'); ?></code><br>
			<code><?php echo _('Password: admin'); ?></code>
		</div>

		<div class="col-md-3">
			<h2><?php echo _('Special user'); ?></h2><br>
			<code><?php echo _('Username: special'); ?></code><br>
			<code><?php echo _('Password: special'); ?></code>
		</div>

		<div class="col-md-3">
			<h2><?php echo _('Default privileges'); ?></h2><br>
			<code><?php echo _('Username: user'); ?></code><br>
			<code><?php echo _('Password: user'); ?></code>
		</div>
	</div>
</div>

<?php

require_once 'footer.php';
