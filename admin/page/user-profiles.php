<?php

include_once __DIR__ . '/settings.php';

$settings = new Settings();

?>

<fieldset>
	<legend><?php echo _('User profiles'); ?></legend>

	<div class="form-group">
		<label class="control-label" for="profile-public-enable"><?php echo _('Profile Control'); ?></a></label>
		<div class="controls checkbox">
			<label class="">
				<input type="checkbox" id="profile-public-enable" name="profile-public-enable" <?php echo $settings->getOption('profile-public-enable', true); ?>>
				<?php echo _('Make all profiles public'); ?>
			</label>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label"
			   for="is-two-factor-auth-enable"><a href="https://www.twilio.com" target="_blank"><?php echo _('Two-Factor Authentication'); ?></a></label>
		<div class="controls checkbox">
			<label class="">
				<input type="checkbox" id="is-two-factor-auth-enable"
					   name="is-two-factor-auth-enable" <?php echo $settings->getOption('is-two-factor-auth-enable', true); ?>>
				<?php echo _('Enable Two-Factor Authentication'); ?>
			</label>
		</div>
	</div>

	<div class="form-group">
		<div class="controls checkbox">
			<label>
				<input type="text" class="form-control input-xlarge" id="two_factor_auth_number"
					   name="two_factor_auth_number"
					   value="<?php echo $settings->getOption('two_factor_auth_number'); ?>">
				<p class="help-block"><?php echo _('Phone number'); ?></p>
			</label>
			<label>
				<input type="text" class="form-control input-xlarge" id="two_factor_auth_sid" name="two_factor_auth_sid"
					   value="<?php echo $settings->getOption('two_factor_auth_sid'); ?>">
				<p class="help-block"><?php echo _('SID'); ?></p>
			</label>
			<label>
				<input type="text" class="form-control input-xlarge" id="two_factor_auth_token"
					   name="two_factor_auth_token"
					   value="<?php echo $settings->getOption('two_factor_auth_token'); ?>">
				<p class="help-block"><?php echo _('Auth Token'); ?></p>
			</label>
            <label>
				<input type="text" class="form-control input-xlarge" id="sms_life_time"
					   name="sms_life_time"
					   value="<?php echo $settings->getOption('sms_life_time'); ?>">
				<p class="help-block"><?php echo _('SMS life time in seconds'); ?></p>
			</label>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label" for="profile-timestamps-enable"><?php echo _('Access logs'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('Log a timestamp + IP address for when a user signs in.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
		<div class="controls checkbox">
			<label class="">
				<input type="checkbox" id="profile-timestamps-enable" name="profile-timestamps-enable" <?php echo $settings->getOption('profile-timestamps-enable', true); ?>>
				<?php echo _('Enable logging'); ?>
			</label><br>
	 		<label class="">
				<input type="checkbox" id="profile-timestamps-admin-enable" name="profile-timestamps-admin-enable" <?php echo $settings->getOption('profile-timestamps-admin-enable', true); ?>>
				<?php echo _('Viewable to admin only'); ?>
			</label>
		</div>
	</div>

	<table class="table profile-field-rows">
	<thead>
		<tr>
			<th><?php echo _('Section'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('Group your fields by assinging sections to them.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></th>
			<th><?php echo _('Input type'); ?></th>
			<th><?php echo _('Input label'); ?></th>
			<th><?php echo _('Sign Up'); ?></th>
			<th><?php echo _('Public'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('Show this field on a user\'s public profile.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></th>
			<th><?php echo _('Delete'); ?></th>
		</tr>
	</thead>
	<?php echo $settings->profile_fields(); ?>
	</table>
	<input type="hidden" name="user-profiles-form" value="1">
</fieldset>

<script>
// Admin profile fields
$('button.add-field').on('click', function(e) {
	e.preventDefault();
	var size = $('table.profile-field-rows tbody .profile-field-row').size() + 1;
	// Make sure tbody exists
	var tbody_size = $('table.profile-field-rows tbody').size();
	if (tbody_size==0) $('table.profile-field-rows').append('<tbody></tbody>');
	// Add the row
	var addThis = $('<tr class="profile-field-row">\
		<td><input type="text" value="" placeholder="<?php echo _('Section name'); ?>" name="profile-field_section[' + size + ']" class="form-control input-medium" /></td>\
		<td><select name="profile-field_type[' + size + ']" class="form-control input-medium">\
			<option value="text_input"><?php echo _('Text input'); ?></option>\
			<option value="textarea"><?php echo _('Textarea'); ?></option>\
			<option value="checkbox"><?php echo _('Checkbox'); ?></option>\
		</select></td>\
		<td><input type="text" value="" placeholder="<?php echo _('Field name'); ?>"  name="profile-field_name[' + size + ']" class="form-control input-medium" /></td>\
		<td><select name="profile-field_signup[' + size + ']" class="form-control input-medium">\
			<option value="hide"><?php echo _('Hide'); ?></option>\
			<option value="require"><?php echo _('Require'); ?></option>\
			<option value="optional"><?php echo _('Optional'); ?></option>\
		</select></td>\
		<td><input type="checkbox" name="profile-field_public[' + size + ']"></td>\
		<td><input type="checkbox" name="profile-field_delete[' + size + ']"></td>\
		</tr>').fadeIn();
	$('table.profile-field-rows tr:last').before(addThis);
});
</script>
