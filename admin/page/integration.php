<?php

include_once __DIR__ . '/settings.php';

$settings = new Settings();

?>

<fieldset>
	<legend><?php echo _('Social login'); ?></legend><br>

	<p><?php echo _('Enable any of these methods to permit your users logging in with them.'); ?></p>

	<div class="form-group">
		<label class="control-label" for="integration-twitter-enable"><?php echo _('Twitter'); ?></label>
		<div class="controls checkbox">

			<label class="">
				<input type="checkbox" class="input-xlarge collapsed" id="integration-twitter-enable" name="integration-twitter-enable" <?php echo $settings->getOption('integration-twitter-enable', true); ?>>
				<?php echo _('Enable'); ?>
			</label>

			<div class="hidden">

			<label>
				<input type="text" class="form-control input-xlarge" id="twitter-key" name="twitter-key" value="<?php echo $settings->getOption('twitter-key'); ?>">
				<p class="help-block"><?php echo _('Consumer key'); ?></p>
			</label>

			<!--<label>
				<input type="text" class="form-control input-xlarge" id="twitter-secret" name="twitter-secret" value="<?php echo $settings->getOption('twitter-secret'); ?>">
				<p class="help-block"><?php /*echo _('Consumer secret');*/ ?></p>
			</label>-->

			<p><?php echo sprintf(_('You must first <a href="%s">setup a Twitter App</a>.'), 'https://dev.twitter.com/apps/new'); ?></p>
			<p><?php echo sprintf(_('When setting up your app, for <i>Callback URL</i>, use <code>%s</code>'),  SITE_PATH . "classes/integration/hybridauth/?hauth.done=Twitter"); ?></p>

			</div>

		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="integration-facebook-enable"><?php echo _('Facebook'); ?></label>
		<div class="controls checkbox">

			<label class="">
				<input type="checkbox" class="input-xlarge collapsed" id="integration-facebook-enable" name="integration-facebook-enable" <?php echo $settings->getOption('integration-facebook-enable', true); ?>>
				<?php echo _('Enable'); ?>
			</label>

			<div class="hidden">

			<label>
				<input type="text" class="form-control input-xlarge" id="facebook-app-id" name="facebook-app-id" value="<?php echo $settings->getOption('facebook-app-id'); ?>">
				<p class="help-block"><?php echo _('App ID'); ?></p>
			</label>

			<!--<label>
				<input type="text" class="form-control input-xlarge" id="facebook-app-secret" name="facebook-app-secret" value="<?php echo $settings->getOption('facebook-app-secret'); ?>">
				<p class="help-block"><?php /*echo _('App Secret');*/ ?></p>
			</label>-->

			<p><?php echo sprintf(_('You must first <a href="%s">setup a Facebook App</a>.'), 'https://developers.facebook.com/apps'); ?></p>

			</div>

		</div>
	</div>

	<div class="form-group">
		<label class="control-label" for="integration-google-enable"><?php echo _('OpenID Networks'); ?></label>
		<div class="controls checkbox">

			<label class="">
				<input type="checkbox" class="input-xlarge collapsed" id="integration-google-enable" name="integration-google-enable" <?php echo $settings->getOption('integration-google-enable', true); ?>>
				<?php echo _('Google'); ?>
			</label><br>
			<div class="hidden">

				<!--<label>
					<input type="text" class="form-control input-xlarge" id="google-id" name="google-id" value="<?php /*echo $settings->getOption('google-id');*/ ?>">
					<p class="help-block"><?php /*echo _('App ID');*/ ?></p>
				</label>

				<label>
					<input type="text" class="form-control input-xlarge" id="google-secret" name="google-secret" value="<?php /*echo $settings->getOption('google-secret');*/ ?>">
					<p class="help-block"><?php /*echo _('App Secret');*/ ?></p>
				</label>-->

				<p><?php echo sprintf(_('You must first <a href="%s">setup a Google App</a>.'), 'https://console.developers.google.com/home/dashboard'); ?></p>

			</div>
		</div>
		<!--<div class="controls checkbox">
		<label class="">
			<input type="checkbox" class="input-xlarge" id="integration-yahoo-enable" name="integration-yahoo-enable" <?php /*echo $settings->getOption('integration-yahoo-enable', true);*/ ?>>
			<?php /*echo _('Yahoo');*/ ?>
		</label>
		</div>-->

	</div>
</fieldset>

<fieldset>
	<legend><?php echo _('Captcha signup'); ?></legend><br>
	<?php $selectedCaptcha = $settings->getOption('integration-captcha'); ?>

	<p><?php echo _('Require human verification on the registration form.'); ?></p>

	<div class="form-group">
		<label class="control-label" for="integration-disableCaptcha-enable"><?php echo _('Disable captcha'); ?></label>
		<div class="controls radio">


			<label class="">
				<input type="radio" class="input-xlarge collapsed" id="integration-disableCaptcha-enable" name="integration-captcha" value="disableCaptcha" <?php if ($selectedCaptcha == 'disableCaptcha') echo 'checked="checked"'; ?>>
			</label>

		</div>
	</div>

	<div class="form-group">
		<label class="control-label" for="integration-reCAPTCHA-enable"><a href="http://www.google.com/recaptcha"><?php echo _('reCAPTCHA V2'); ?></a></label>
		<div class="controls radio">

			<label class="">
				<input type="radio" class="input-xlarge collapsed" id="integration-reCAPTCHA-enable" name="integration-captcha" value="reCAPTCHA" <?php if ($selectedCaptcha == 'reCAPTCHA') echo 'checked="checked"'; ?>>
				<?php echo _('Enable'); ?>
			</label>

			<div class="hidden">

			<label>
				<input type="text" class="form-control input-xlarge" id="reCAPTCHA-public-key" name="reCAPTCHA-public-key" value="<?php echo $settings->getOption('reCAPTCHA-public-key'); ?>">
				<p class="help-block"><?php echo _('Public key'); ?></p>
			</label>

			<label>
				<input type="text" class="form-control input-xlarge" id="reCAPTCHA-private-key" name="reCAPTCHA-private-key" value="<?php echo $settings->getOption('reCAPTCHA-private-key'); ?>">
				<p class="help-block"><?php echo _('Private key'); ?></p>
			</label>

			<p><?php echo sprintf(_('You must first <a href="%s">create a reCAPTCHA key</a>.'), 'https://www.google.com/recaptcha/admin#list'); ?></p>

			</div>

		</div>
	</div>

	<div class="form-group">
		<label class="control-label" for="integration-playThru-enable"><a href="http://areyouahuman.com/?utm_source=Jigowatt&utm_medium=Jigowatt&utm_campaign=Jigowatt"><?php echo _('PlayThru'); ?></a></label>
		<div class="controls radio">

			<label class="">
				<input type="radio" class="input-xlarge collapsed" id="integration-playThru-enable" name="integration-captcha" value="playThru" <?php if ($selectedCaptcha == 'playThru') echo 'checked="checked"'; ?>>
				<?php echo _('Enable'); ?>
			</label>

			<div class="hidden">

			<label>
				<input type="text" class="form-control input-xlarge" id="playThru-publisher-key" name="playThru-publisher-key" value="<?php echo $settings->getOption('playThru-publisher-key'); ?>">
				<p class="help-block"><?php echo _('Publisher key'); ?></p>
			</label>

			<label>
				<input type="text" class="form-control input-xlarge" id="playThru-scoring-key" name="playThru-scoring-key" value="<?php echo $settings->getOption('playThru-scoring-key'); ?>">
				<p class="help-block"><?php echo _('Scoring key'); ?></p>
			</label>

			<p><?php echo sprintf(_('You must first <a href="%s">signup to get a site key</a>.'), 'http://portal.areyouahuman.com/signup?utm_source=Jigowatt&utm_medium=Jigowatt&utm_campaign=Jigowatt'); ?></p>

			</div>

		</div>
	</div>

	<input type="hidden" name="integration-form" value="1">
</fieldset>
