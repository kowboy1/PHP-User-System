<?php

include_once __DIR__ . '/settings.php';
include_once '../../header.php';
include_once '../../initialise.php';

$generic = new Generic();
$settings = new Settings();
$newUpdate = $settings->newUpdate();

?>

<fieldset>
	<legend><?php echo _('Update'); ?></legend>
		<br><div class="row">
			<?php if (!$settings->newChangelog()) : ?>
				<div class="col-md-12 alert alert-block alert-warning fade in"><a class="close" data-dismiss="alert" href="#">&times;</a><h4 class="alert-warning"><?php echo _('Updates disabled'); ?></h4>
				<p><?php echo _('Two thing may have happened:'); ?></p>
				<ol>
					<li><?php echo _('Update checking is disabled'); ?></li>
					<li><?php echo _('Could not connect to server to fetch latest update details. Please make sure the PHP setting `allow_url_fopen` is enabled.'); ?></li>
				</ol>
				</div>
				<?php elseif($newUpdate) : ?>
				<div class="col-md-12 alert alert-block alert-info fade in"><a class="close" data-dismiss="alert" href="#">&times;</a><h4 class="alert-info"><?php echo _('Update available!'); ?></h4>
				<?php /*echo _('There\'s a new update available! Please visit your CodeCanyon profile to download the new version.');*/ ?>
				<?php echo _('There\'s a new update available!'); ?></div>
				<?php else : ?>
				<div class="col-md-12 alert alert-block alert-success fade in"><a class="close" data-dismiss="alert" href="#">&times;</a><h4 class="alert-success"><?php echo _('You\'re up to date!'); ?></h4>
				<?php echo _('There are no new updates available. When a new update is released this message will change accordingly.'); ?></div>
			<?php endif; ?>
		</div>
  
  <?php
  
  if ($newUpdate) {
    echo '<a href="' . $generic->getOption('site_address') . 'admin/upgrade.php" class="btn btn-primary" id="updateNowBtn">' . _('Update now') . '</a>';
  }
  
  ?>
  
	<div class="form-group">
		<label class="control-label" for="update-check-enable"><?php echo _('Check for updates'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('Disabling this may improve speed on the Settings page'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<label class="checkbox">
				<input type="checkbox" id="update-check-enable" name="update-check-enable" <?php echo $settings->getOption('update-check-enable', true); ?>>
				<?php echo _('Enable to automatically check for updates each time you load this page'); ?>
			</label>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label"><?php echo _('Current version'); ?></label>
		<div class="controls">
			<span class="uneditable-input"><?php echo phplogin_version ?></span>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label"><?php echo _('Latest version'); ?></label>
		<div class="controls">
			<span class="uneditable-input"><?php echo $settings->newVersion(); ?></span>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label"><?php echo _('Latest changelog'); ?></label>
		<div class="controls">
			<textarea rows="15" class="col-md-12" disabled><?php echo $settings->newChangelog(); ?></textarea>
		</div>
	</div>
		<input type="hidden" name="update-form" value="1">
</fieldset>
