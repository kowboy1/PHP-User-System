<?php

include_once __DIR__ . '/settings.php';

$settings = new Settings();

?>

<fieldset>
	<legend><?php echo _("'My Account' changes"); ?></legend>
	<p><?php echo _('<b>Note:</b> Only sent when a user changes his or her Email / Password.'); ?></p>
	<div class="form-group">
		<label class="control-label" for="email-acct-update-subj"><?php echo _('Verify change'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('The email a user receives when updating an email or password.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<label>
				<input type="text" class="form-control input-xlarge" id="email-acct-update-subj" name="email-acct-update-subj" value="<?php echo $settings->getOption('email-acct-update-subj'); ?>">
				<p class="help-block"><?php echo _('Subject'); ?></p>
			</label>
			<textarea class="form-control input-xlarge" id="email-acct-update-msg" name="email-acct-update-msg" rows="10"><?php echo $settings->getOption('email-acct-update-msg'); ?></textarea>
			<div class="help-block">
				<p><?php echo _('Message body'); ?></p><br>
				<p><strong><?php echo _('Shortcodes:'); ?></strong></p>
				<p><?php echo _('Site address:'); ?> <code>{{site_address}}</code></p>
				<p><?php echo _('Full name:')?> <code>{{full_name}}</code></p>
				<p><?php echo _('Username:'); ?> <code>{{username}}</code></p>
				<p><?php echo _('Confirmation link:'); ?> <code>{{confirm}}</code></p>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="email-acct-update-success-subj"><?php echo _('Updated'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('The email a user receives after confirming the account change.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<label>
				<input type="text" class="form-control input-xlarge" id="email-acct-update-success-subj" name="email-acct-update-success-subj" value="<?php echo $settings->getOption('email-acct-update-success-subj'); ?>">
				<p class="help-block"><?php echo _('Subject'); ?></p>
			</label>
			<textarea class="form-control input-xlarge" id="email-acct-update-success-msg" name="email-acct-update-success-msg" rows="10"><?php echo $settings->getOption('email-acct-update-success-msg'); ?></textarea>
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
