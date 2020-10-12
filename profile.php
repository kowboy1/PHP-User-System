<?php

require_once 'initialise.php';
require_once 'header.php';

$profile = new Profile();

?>

<h1>

	<?php if ($profile->getOption('custom-avatar-enable')): ?>
		<form class="hide" action="profile.php" method="post" enctype="multipart/form-data">
		    <input type="file" name="uploadAvatar" id="uploadAvatar" onchange="this.form.submit();">
		</form>

		<a href="#" class="a-tooltip" onclick="document.getElementById('uploadAvatar').click()" data-rel="tooltip-bottom" title="<?php echo _('Click to change your avatar'); ?>">
			<img class="gravatar img-thumbnail" style="width:54px;" src="<?php echo $profile->get_gravatar($profile->getField('email'), false, 54); ?>"/>
		</a>
	<?php else: ?>
		<a href="http://gravatar.com/emails/" class="a-tooltip" data-rel="tooltip-bottom" title="<?php echo _('Change your avatar at Gravatar.com'); ?>">
			<img class="gravatar img-thumbnail" src="<?php echo $profile->get_gravatar($profile->getField('email'), false, 54); ?>"/>
		</a>
	<?php endif ?>

	<?php echo $profile->getField('username') . ' (' . $profile->getField('name') . ')'; ?>

</h1>

<br>

<div class="tabs-left">

	<ul class="nav nav-tabs">

		<?php if ( !$profile->guest ) : ?>
			<li class="active"><a href="#usr-control" data-toggle="tab"><i class="glyphicon glyphicon-cog"></i> <?php echo _('General'); ?></a></li>
		<?php endif; ?>

		<?php $profile->generateProfileTabs($profile->guest); ?>
		<?php if (!$profile->guest && !$profile->denyAccessLogs()) : ?>
		<li><a href="#usr-access-logs" data-toggle="tab"><i class="glyphicon glyphicon-list-alt"></i> <?php echo _('Access logs'); ?></a></li>
		<?php endif; ?>

		<?php if ( !$profile->guest && !empty( $jigowatt_integration->enabledMethods ) ) : ?>
		<li><a href="#usr-integration" data-toggle="tab"><i class="glyphicon glyphicon-random"></i> <?php echo _('Integration'); ?></a></li>
		<?php endif; ?>

	</ul>

	<form class="" method="post" action="profile.php">
	<div class="tab-content">

		<?php if ( !$profile->guest ) : ?>
		<div class="tab-pane fade in active" id="usr-control">
			<fieldset>
				<legend><?php echo _('General'); ?></legend>
				<div class="form-group">
					<label class="control-label" for="CurrentPass"><?php echo _('Current password'); ?></label>
					<div class="controls">
						<input type="password" autocomplete="off" class="form-control input-xlarge" id="CurrentPass" name="CurrentPass">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="name"><?php echo _('Name'); ?></label>
					<div class="controls">
						<input type="text" class="form-control input-xlarge" id="name" name="name" value="<?php echo $profile->getField('name'); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="email"><?php echo _('Email'); ?></label>
					<div class="controls">
						<input type="email" class="form-control input-xlarge" id="email" name="email" value="<?php echo $profile->getField('email'); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="password"><?php echo _('New password'); ?></label>
					<div class="controls">
						<input type="password" autocomplete="off" class="form-control input-xlarge" id="password" name="password" placeholder="<?php echo _('Leave blank for no change'); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="confirm"><?php echo _('New password again'); ?></label>
					<div class="controls">
						<input type="password" autocomplete="off" class="form-control input-xlarge" id="confirm" name="confirm">
					</div>
				</div>

				<?php if ( $profile->getOption('profile-public-enable') ) : ?>
				<div class="form-group">
					<label class="control-label" for="confirm"><?php echo _('Your public link'); ?></label>
					<div class="controls">
						<span class="uneditable-input"><?php echo SITE_PATH . 'profile.php?uid=' . $profile->getField('user_id'); ?></span>
					</div>
				</div>
				<?php endif; ?>
                <?php if ($profile->is_two_factor_auth_enable != 0): ?>
                <legend><?php echo _('Security'); ?></legend>
                <div class="form-group">
                    <div class="controls checkbox">
                        <label class="">
                            <input class="" type="checkbox" id="use_two_factor_auth" name="use_two_factor_auth" <?php echo ($profile->getField('use_two_factor_auth')==1 ? 'checked="checked"' : '') ?>>
                            <?php echo _('Use Two-Factor Authentication'); ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="phone"><?php echo _('Your phone number'); ?></label>
                    <div class="controls">
                        <input type="text" autocomplete="off" value="<?php echo $profile->getField('phone'); ?>" class="form-control input-xlarge" id="phone" name="phone">
                    </div>
                </div>
                <?php endif; ?>
			</fieldset>
		</div>
		<?php endif; ?>

		<?php $profile->generateProfilePanels($profile->guest); ?>

		<?php if (!$profile->guest && !$profile->denyAccessLogs()) : ?>
		<div class="tab-pane fade" id="usr-access-logs">
			<fieldset>
				<legend><?php echo _('Access Logs'); ?></legend>
				<?php $profile->generateAccessLogs(); ?>
			</fieldset>
		</div>
		<?php endif; ?>

		<?php if ( !$profile->guest && !empty( $jigowatt_integration->enabledMethods ) ) : ?>
		<div class="tab-pane fade" id="usr-integration">
			<fieldset>
				<legend><?php echo _('Integration'); ?></legend><br>

				<p><?php echo _('Use your preferred social method to login the next time you visit our site.'); ?></p><br>

				<?php

					foreach ($jigowatt_integration->enabledMethods as $key ) :
						$inUse = $jigowatt_integration->isUsed($key);
						?><div class="col-md-3">
							<a class="a-tooltip" href="#" data-rel="tooltip" tabindex="99" title="<?php echo ucwords($key); ?>">
								<img src="assets/img/<?php echo $key; ?>.png" alt="<?php echo $key; ?>">
							</a>
							<a href="<?php echo $inUse ? '#' : '?link='.$key; ?>" class="btn btn-sm btn-info<?php echo $inUse ? ' disabled' : ''; ?>"><?php echo _('Link'); ?></a>
							<a href="<?php echo !$inUse ? '#' : '?unlink='.$key; ?>" class="btn btn-sm<?php echo !$inUse ? ' disabled' : ''; ?>"><?php echo _('Unlink'); ?></a>
							</div><?php

					endforeach;

				?>

			</fieldset>
		</div>
		<?php endif; ?>

		<?php if ( !$profile->guest ) : ?>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?php echo _('Save changes'); ?></button>
		</div>
		<?php endif; ?>

	</div>
	</form>
</div>

<?php

include ('footer.php');
