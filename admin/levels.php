<?php

require_once '../initialise.php';

#################################################################################################### --- 

protect("Admin");

#################################################################################################### --- 

$generic = new Generic();
$Edit_level = new Edit_level();

#################################################################################################### --- 

include_once 'header.php';

?>

<legend><?php echo $Edit_level->getValue('level_name'); ?> <small><?php echo _('role control'); ?></small></legend>

<form method="post" class="col-md-6">

  <fieldset>
    <div class="form-group">
      <label class="control-label" for="guest-redirect"><?php echo _('Name'); ?></label>
      <div class="controls">
        <input id="level_name" name="level_name" class="form-control" type="text" value="<?php echo $Edit_level->getValue('level_name'); ?>"/>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label" for="guest-redirect"><?php echo _('Redirect'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('When logging in, this user will be redirected to the URL you specify. Leave blank to redirect to the referring page.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
      <div class="controls">
        <input id="redirect" name="redirect" class="form-control" type="url" placeholder="eg, http://google.com" value="<?php echo $Edit_level->getValue('redirect'); ?>"/>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label"><?php echo _('Welcome email'); ?> <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('When a user is manually added to this role, that user will receive the standard welcome email automatically.'); ?>"><i class="glyphicon glyphicon-question-sign"></i></a></label>
      <div class="controls checkbox">
        <label>
        <input id="welcome_email" name="welcome_email" type="checkbox" <?php echo $Edit_level->getValue('welcome_email'); ?>/>
        <?php echo _('Send welcome email when users join this role'); ?>
        </label>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label"><?php echo _('Disable'); ?></label>
      <div class="controls checkbox">
        <label>
        <input id="disable" name="disable" type="checkbox" <?php if (!empty($Edit_level->isAdmin)) echo ' disabled '; ?> <?php echo $Edit_level->getValue('level_disabled'); ?>/>
        <?php echo _('Prevent this role from accessing any secure content'); ?>
        </label>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label"><?php echo _('Delete'); ?></label>
      <div class="controls checkbox">
        <label>
        <input id="delete" name="delete" type="checkbox" <?php if (!empty($Edit_level->isAdmin)) echo ' disabled '; ?>/>
        <?php echo _('Remove this role from the database'); ?>
        </label>
      </div>
    </div>
  </fieldset>
  
  <div class="form-actions">
    <button type="submit" name="do_edit" class="btn btn-primary"><?php echo _('Update'); ?></button>
  </div>
</form>

<?php if(!empty($_GET['lid'])) :?>
	<legend><?php echo $Edit_level->getValue('level_name'); ?> <small><?php echo _('existing users'); ?></small></legend>
	<?php in_level(); ?>
<?php endif; ?>

<?php
  include_once 'footer.php';
?>
