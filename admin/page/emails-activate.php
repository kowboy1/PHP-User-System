<?php

include_once __DIR__ . '/settings.php';

$settings = new Settings();

?>

<fieldset>
	<legend><?php echo _('Activation emails'); ?></legend>
	<div class="form-group">
		<label class="control-label" for="email-activate-resend-subj"><?php echo _('Resend link'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('The email a user receives when requesting an activation link.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<label>
				<input type="text" class="form-control input-xlarge" id="email-activate-resend-subj" name="email-activate-resend-subj" value="<?php echo $settings->getOption('email-activate-resend-subj'); ?>">
				<p class="help-block"><?php echo _('Subject'); ?></p>
			</label>
			<textarea class="form-control input-xlarge" id="email-activate-resend-msg" name="email-activate-resend-msg" rows="10"><?php echo $settings->getOption('email-activate-resend-msg'); ?></textarea>
			<div class="help-block">
				<p><?php echo _('Message body'); ?></p><br>
				<p><strong><?php echo _('Shortcodes:'); ?></strong></p>
				<p><?php echo _('Site address:'); ?> <code>{{site_address}}</code></p>
				<p><?php echo _('Full name:'); ?> <code>{{full_name}}</code></p>
				<p><?php echo _('Username:'); ?> <code>{{username}}</code></p>
				<p><?php echo _('Activation link:'); ?> <code>{{activate}}</code></p>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="email-activate-subj"><?php echo _('Activated'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('The email a user receives after activating their account.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<label>
				<input type="text" class="form-control input-xlarge" id="email-activate-subj" name="email-activate-subj" value="<?php echo $settings->getOption('email-activate-subj'); ?>">
				<p class="help-block"><?php echo _('Subject'); ?></p>
			</label>
			<textarea class="form-control input-xlarge" id="email-activate-msg" name="email-activate-msg" rows="10"><?php echo $settings->getOption('email-activate-msg'); ?></textarea>
			<div class="help-block">
				<p><?php echo _('Message body'); ?></p><br>
				<p><strong><?php echo _('Shortcodes:'); ?></strong></p>
				<p><?php echo _('Site address:'); ?> <code>{{site_address}}</code></p>
				<p><?php echo _('Full name:')?> <code>{{full_name}}</code></p>
				<p><?php echo _('Username:'); ?> <code>{{username}}</code></p>
			</div>
		</div>
	</div>
</fieldset>
