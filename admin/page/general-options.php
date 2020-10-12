<?php

include_once __DIR__ . '/settings.php';

$settings = new Settings();

?>

<fieldset>
	<legend><?php echo _('General Options'); ?></legend>
	<div class="form-group">
		<label class="control-label" for="admin_email"><?php echo _('Admin email'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('This email will be used to send all emails.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<input type="email" class="form-control input-xlarge" id="admin_email" name="admin_email" value="<?php echo $settings->getOption('admin_email'); ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="site_address"><?php echo _('Site address'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('This path should be set to where activate.php is located.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<input type="url" class="form-control input-xlarge" id="site_address" name="site_address" value="<?php echo $settings->getOption('site_address'); ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="default_session"><?php echo _('Default session'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('Default time in minutes a user can be logged in. Enter 0 to log the user out when they close their browser.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<input type="number" min=0 class="form-control input-mini" id="default_session" name="default_session" value="<?php echo $settings->getOption('default_session'); ?>" placeholder="0">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="default-level"><?php echo _('Default role'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('The default role a new user will have when signing up, or by being created through the admin panel.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<?php $settings->returnLevels('default-level'); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="email-as-username-enable"><?php echo _('Site control'); ?></label>
		<div class="controls checkbox">
 		<label class="">
			<input type="checkbox" class="" id="custom-avatar-enable" name="custom-avatar-enable" <?php echo $settings->getOption('custom-avatar-enable', true); ?>>
			<?php echo _('Allow custom avatar uploads'); ?>
		</label><br>
		<label class="">
			<input type="checkbox" class="" id="email-as-username-enable" name=email-as-username-enable <?php echo $settings->getOption('email-as-username-enable', true); ?>>
			<?php echo _('Use email addresses instead of usernames'); ?>
		</label><br>
 		<label class="">
			<input type="checkbox" class="" id="disable-registrations-enable" name="disable-registrations-enable" <?php echo $settings->getOption('disable-registrations-enable', true); ?>>
			<?php echo _('Disable registrations'); ?>
		</label><br>
 		<label class="">
			<input type="checkbox" class="" id="disable-logins-enable" name="disable-logins-enable" <?php echo $settings->getOption('disable-logins-enable', true); ?>>
			<?php echo _('Disable logins'); ?>
		</label>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="user-activation-enable"><?php echo _('New users'); ?></label>
		<div class="controls checkbox">
		<label class="">
			<input type="checkbox" class="" id="user-activation-enable" name="user-activation-enable" <?php echo $settings->getOption('user-activation-enable', true); ?>>
			<?php echo _('Require email activation for new users'); ?>
		</label><br>
 		<label class="">
			<input type="checkbox" class="" id="email-welcome-disable" name="email-welcome-disable" <?php echo $settings->getOption('email-welcome-disable', true); ?>>
			<?php echo _('Do not send the welcome email when a new user registers'); ?>
		</label><br>
 		<label class="">
			<input type="checkbox" class=" collapsed" id="notify-new-user-enable" name="notify-new-user-enable" <?php echo $settings->getOption('notify-new-user-enable', true); ?>>
			<?php echo _('Notify a user group on new registrations'); ?>
		</label>
		<div class="hidden">
			<label class="textarea">
				<?php $settings->returnLevels('notify-new-users'); ?>
			</label>
		</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="restrict-signups-by-email"><?php echo _('Restrict email domains'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('Restrict registrations to the emails specified. Eg, kent.edu'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<select multiple="multiple" class="input-large" id="restrict-signups-by-email" name="restrict-signups-by-email[]" data-placeholder="Domains, eg: kent.edu, gmail.com">
				<?php $domains = $settings->get_domains(false, true); if (is_array($domains)): foreach ( $domains as $domain): ?>
				<option selected="selected" value="<?php echo $domain; ?>"><?php echo $domain; ?></option>
				<?php endforeach; endif; ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="pw-encrypt-force-enable"><?php echo _('Password encryption'); ?></label>
		<div class="controls checkbox">
		<label class="">
			<input type="checkbox" class="" id="pw-encrypt-force-enable" name="pw-encrypt-force-enable" <?php echo $settings->getOption('pw-encrypt-force-enable', true); ?>>
			<?php echo _('Force user to update password if not using selected encryption method'); ?>
		</label>
		<?php $pw_encryption = $settings->getOption('pw-encryption'); ?>
		<?php $e = array('BCRYPT'); ?>
		<?php foreach ($e as $value) : ?>
			<label class="radio">
				<input type="radio" name="pw-encryption" id="<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($pw_encryption == $value) echo 'checked'; ?> > <?php echo $value; ?>
			</label>
		<?php endforeach; ?>
		</div>
	</div>

	<legend><?php echo _('Redirect Options'); ?></legend><br>

	<div class="form-group">
		<label class="control-label" for="guest-redirect"><?php echo _('Guests'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('Where to redirect guests when attempting to access a secured page.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<input type="url" class="form-control input-xlarge" id="guest-redirect" name="guest-redirect" placeholder="<?php echo SITE_PATH . 'login.php'; ?>" value="<?php echo $settings->getOption('guest-redirect'); ?>">
			<p class="help-block"><?php echo _('Default: <code>login.php?e=1</code>'); ?></p>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label" for="new-user-redirect"><?php echo _('New users'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('After a new user registers, where should the user be redirected to? By default the user is sent to the My Account page.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls">
			<input type="url" class="form-control input-xlarge" id="new-user-redirect" name="new-user-redirect" placeholder="<?php echo SITE_PATH . 'profile.php'; ?>" value="<?php echo $settings->getOption('new-user-redirect'); ?>">
			<p class="help-block"><?php echo _('Default: <code>profile.php</code>'); ?></p>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label" for="signout-redirect-referrer-enable"><?php echo _('Sign out'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('When the user logs out of your site through logout.php.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls checkbox">
			<label class="">
				<input type="checkbox" class="uncollapsed" id="signout-redirect-referrer-enable" name="signout-redirect-referrer-enable" <?php echo $settings->getOption('signout-redirect-referrer-enable', true); ?>>
				<?php echo _('Redirect to referring page'); ?>
			</label>
			<input type="url" class="form-control input-xlarge" id="signout-redirect-url" name="signout-redirect-url" placeholder="<?php echo SITE_PATH; ?>" value="<?php echo $settings->getOption('signout-redirect-url'); ?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label" for="signin-redirect-referrer-enable"><?php echo _('Sign in'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('The default page to load when a user logs in.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls checkbox">
			<label class="">
				<input type="checkbox" class="uncollapsed" id="signin-redirect-referrer-enable" name="signin-redirect-referrer-enable" <?php echo $settings->getOption('signin-redirect-referrer-enable', true); ?>>
				<?php echo _('Redirect to referring page'); ?>
			</label>
			<input type="url" class="form-control input-xlarge" id="signin-redirect-url" name="signin-redirect-url" placeholder="<?php echo SITE_PATH; ?>" value="<?php echo $settings->getOption('signin-redirect-url'); ?>">
		</div>
	</div>

	<p class="help-block"><?php echo _('<b>Note:</b> Role specific redirects can be set on their respective role edit page and will override the options configured above.'); ?></p>

	<input type="hidden" name="general-options-form" value="1">
</fieldset>
