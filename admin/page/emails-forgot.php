<?php

include_once __DIR__ . '/settings.php';

$settings = new Settings();

?>

<fieldset>
	<legend><?php echo _('Account recovery emails'); ?></legend>
	<div class="form-group">
		<label class="control-label" for="email-forgot-subj"><?php echo _('Recover request'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('The email a user receives when requesting their username / password.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<label>
				<input type="text" class="form-control input-xlarge" id="email-forgot-subj" name="email-forgot-subj" value="<?php echo $settings->getOption('email-forgot-subj'); ?>">
				<p class="help-block"><?php echo _('Subject'); ?></p>
			</label>
			<textarea class="form-control input-xlarge" id="email-forgot-msg" name="email-forgot-msg" rows="10"><?php echo $settings->getOption('email-forgot-msg'); ?></textarea>
			<div class="help-block">
				<p><?php echo _('Message body'); ?></p><br>
				<p><strong><?php echo _('Shortcodes:'); ?></strong></p>
				<p><?php echo _('Site address:'); ?> <code>{{site_address}}</code></p>
				<p><?php echo _('Full name:'); ?> <code>{{full_name}}</code></p>
				<p><?php echo _('Username:'); ?> <code>{{username}}</code></p>
				<p><?php echo _('Reset link:'); ?> <code>{{reset}}</code></p>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="email-forgot-success-subj"><?php echo _('Recovered'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('The email a user receives after successfully resetting their password.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<label>
				<input type="text" class="form-control input-xlarge" id="email-forgot-success-subj" name="email-forgot-success-subj" value="<?php echo $settings->getOption('email-forgot-success-subj'); ?>">
				<p class="help-block"><?php echo _('Subject'); ?></p>
			</label>
			<textarea class="form-control input-xlarge" id="email-forgot-success-msg" name="email-forgot-success-msg" rows="10"><?php echo $settings->getOption('email-forgot-success-msg'); ?></textarea>
			<div class="help-block">
				<p><?php echo _('Message body'); ?></p><br>
				<p><strong><?php echo _('Shortcodes:'); ?></strong></p>
				<p><?php echo _('Site address:'); ?> <code>{{site_address}}</code></p>
				<p><?php echo _('Full name:'); ?> <code>{{full_name}}</code></p>
				<p><?php echo _('Username:'); ?> <code>{{username}}</code></p>
			</div>
		</div>
	</div>
</fieldset>
