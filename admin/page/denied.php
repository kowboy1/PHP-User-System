<?php

include_once __DIR__ . '/settings.php';

$settings = new Settings();

?>

<!-- - - - - - - - - - - - - - - - -

		Denied messages

- - - - - - - - - - - - - - - - - -->
<fieldset>
	<legend><?php echo _('Denied'); ?></legend>
	<div class="form-group">
		<label class="control-label" for="block-msg-enable"><?php echo _('Registered users'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('This controls the message a <strong>signed in</strong> user sees when accessing a protected page.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls checkbox">
			<label class="">
				<input type="checkbox" class="collapsed" id="block-msg-enable" name="block-msg-enable" <?php echo $settings->getOption('block-msg-enable', true); ?>>
				<?php echo _('Display message'); ?>
			</label>
			<div class="hidden">
			<label class="textarea">
				<textarea class="form-control input-xlarge" id="block-msg" name="block-msg" rows="5"><?php echo $settings->getOption('block-msg'); ?></textarea>
			</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="block-msg-out-enable"><?php echo _('Guests'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('Show this error after a user is redirected to the login page (user is <strong>logged out</strong>).'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls checkbox">
			<label class="">
				<input type="checkbox" class="collapsed" id="block-msg-out-enable" name="block-msg-out-enable" <?php echo $settings->getOption('block-msg-out-enable', true); ?>>
				<?php echo _('Display message'); ?>
			</label>
			<div class="hidden">
			<label class="textarea">
				<textarea class="form-control input-xlarge" id="block-msg-out" name="block-msg-out" rows="5"><?php echo $settings->getOption('block-msg-out'); ?></textarea>
			</label>
			</div>
		</div>
	</div>
	<input type="hidden" name="denied-form" value="1">
</fieldset>
