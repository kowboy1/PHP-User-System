<?php

include_once __DIR__ . '/settings.php';

$settings = new Settings();

?>

<fieldset>
	<legend><?php echo _('Add user'); ?></legend>
	<div class="form-group">
		<label class="control-label" for="email-add-user-subj"><?php echo _('Add user'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('When the admin creates a new user through the admin panel, the user will receive this email.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<label>
				<input type="text" class="form-control input-xlarge" id="email-add-user-subj" name="email-add-user-subj" value="<?php echo $settings->getOption('email-add-user-subj'); ?>">
				<p class="help-block"><?php echo _('Subject'); ?></p>
			</label>
			<textarea class="form-control input-xlarge" id="email-add-user-msg" name="email-add-user-msg" rows="10"><?php echo $settings->getOption('email-add-user-msg'); ?></textarea>
			<div class="help-block">
				<p><?php echo _('Message body'); ?></p><br>
				<p><strong><?php echo _('Shortcodes:'); ?></strong></p>
				<p><?php echo _('Site address:'); ?> <code>{{site_address}}</code></p>
				<p><?php echo _('Full name:'); ?> <code>{{full_name}}</code></p>
				<p><?php echo _('Username:'); ?> <code>{{username}}</code></p>
				<p><?php echo _('Password:'); ?> <code>{{password}}</code></p>
			</div>
			<p class="help-block"><strong><?php echo _('Note:'); ?></strong> <?php echo _('The password is randomly generated and should be included in the email'); ?></p>
		</div>
	</div>
</fieldset>
