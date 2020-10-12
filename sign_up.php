<?php

require_once 'initialise.php';
require_once 'header.php';


$generic = new Generic();
$signUp = new SignUp();

?>

<div class="row">
	<div class="col-md-6">
		<form class="" method="post" action="sign_up.php" id="sign-up-form">
      <input type="hidden" name="action" value="signUp" />
			<fieldset>
				<div class="form-group">
					<label class="control-label" for="name"><?php echo _('Full name'); ?></label>
					<div class="controls">
						<input type="text" class="form-control input-xlarge" id="name" name="name" value="<?php echo $signUp->getPost('name'); ?>" placeholder="<?php echo _('Full name'); ?>">
					</div>
				</div>

				<?php if (empty($signUp->use_emails)) : ?>

				<div class="form-group" id="usrCheck">
					<label class="control-label" for="username"><?php echo _('Username') ?></label>
					<div class="controls">
						<input type="text" class="form-control input-xlarge" id="username" name="username" value="<?php echo $signUp->getPost('username'); ?>" placeholder="<?php echo _('Choose your username'); ?>">
					</div>
				</div>
				<?php endif; ?>

				<div class="form-group">
					<label class="control-label" for="email"><?php echo _('Email'); ?></label>
					<div class="controls">
						<input type="email" class="form-control input-xlarge" id="email" name="email" value="<?php echo $signUp->getPost('email'); ?>" placeholder="<?php echo _('Email'); ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label" for="password"><?php echo _('Password'); ?></label>
					<div class="controls">
						<input type="password" class="form-control input-xlarge" id="password" name="password" placeholder="<?php echo _('Create a password'); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="password_confirm"><?php echo _('Password again'); ?></label>
					<div class="controls">
						<input type="password" class="form-control input-xlarge" id="password_confirm" name="password_confirm" placeholder="<?php echo _('Confirm your password'); ?>">
					</div>
				</div>

				<div class="form-group">
					<?php $signUp->profileSignUpFields(); ?>
				</div>

				<div class="form-group">
					<?php $signUp->doCaptcha(true); ?>
				</div>

			</fieldset>
			<input type="hidden" name="token" value="<?php echo $_SESSION['jigowatt']['token']; ?>"/>
			<button type="submit" class="btn btn-primary"><?php echo _('Create my account'); ?></button>
		</form>
	</div>
	
	<div class="col-md-6">
		<h1><?php echo _('Create a new account'); ?></h1>
		<p><?php echo _('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris cursus rhoncus tristique. Mauris ornare ipsum a leo molestie id porttitor justo bibendum. Pellentesque magna augue, sollicitudin ut ornare pretium, mattis imperdiet augue. Morbi semper sapien sit amet velit interdum eu commodo erat fringilla. Nulla et ipsum orci, ac varius nulla. Nam vehicula, mi quis euismod consectetur, magna dui porttitor sem, vel venenatis felis nunc eu diam. Integer vitae est at nunc varius viverra sit amet at magna. Vestibulum mi diam, pharetra id malesuada ac, venenatis nec turpis. Vestibulum metus nisl, pharetra non laoreet eu, laoreet a eros. Suspendisse ut arcu in mauris dapibus sodales. Vestibulum commodo congue elit at mollis. Fusce semper auctor odio, ut pharetra justo faucibus blandit. Fusce in pellentesque elit. Nunc adipiscing neque eu odio tincidunt ac mollis erat porta.'); ?></p>
		<h2><?php echo _('Features'); ?></h2>
		<p><?php echo _('Cras placerat scelerisque vehicula. Fusce eu ipsum vel mi convallis dapibus. Cras ut nibh metus, quis malesuada augue. Aenean a nisi nec sem accumsan gravida in in turpis. Nulla euismod lorem non sem imperdiet vestibulum. Donec blandit aliquet turpis sed dapibus. Duis fermentum facilisis diam, sit amet ultrices neque dictum eget.'); ?></p>
	</div>
</div>

<?php include_once('footer.php'); ?>

