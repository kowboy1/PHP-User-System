<?php

if (preg_match('~user-add\.php$~', $_SERVER['SCRIPT_NAME'])) {
  include_once __DIR__ . '/admin.php';
//   include_once BASE_PATH . '/admin/header.php';
}

$addUser = new Add_user();

?>

<fieldset>
	<form method="post" class="form " action="page/user-add.php" id="user-add-form">
		
		<input type="hidden" name="add_user" value="true">
		
		<div id="message"></div>
		<fieldset>
			<div class="form-group">
				<label class="control-label" for="name"><?php echo _('Name'); ?></label>
				<div class="controls">
					<input type="text" class="form-control input-xlarge" id="name" name="name">
				</div>
			</div>

			<div class="form-group" id="usrCheck">
				<label class="control-label" for="username"><?php echo _('Username'); ?></label>
				<div class="controls">
					<input type="text" class="form-control input-xlarge" id="username" name="username">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label" for="email"><?php echo _('Email'); ?></label>
				<div class="controls">
					<input type="email" class="form-control input-xlarge" id="email" name="email">
				</div>
			</div>
		<p class="help-block"><?php echo _('<b>Note</b>: A random password will be generated and emailed to the user.'); ?></p>
		</fieldset>
		<div class="form-actions">
			<button type="submit" name="add_user" class="btn btn-primary" id="user-add-submit"><?php echo _('Add user'); ?></button>
		</div>
	</form>
</fieldset>
