<?php

if (preg_match('~level-create\.php$~', $_SERVER['SCRIPT_NAME'])) {
  include_once __DIR__ . '/admin.php';
  //include_once BASE_PATH . '/admin/header.php';
}

$addLevel = new Add_level();

?>

<fieldset>
	<form method="post" class="form " id="level-add-form" action="page/level-create.php">
		
		<input type="hidden" name="add_level" value="true">
		
		<div id="level-message"></div>
		<fieldset>
			<div class="form-group">
				<label class="control-label" for="level"><?php echo _('Name'); ?></label>
				<div class="controls">
					<input type="text" class="form-control input-xlarge" id="level" name="level" value="<?php //echo $addLevel->getPost('level'); ?>">
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label" for="redirect">
          
          <?php echo _('Redirect'); ?>
          
          <a href="#" data-rel="tooltip" tabindex="99" title="<?php echo _('When logging in, this user will be redirected to the URL you specify. Leave blank to redirect to the referring page.'); ?>">
            <i class="glyphicon glyphicon-question-sign"></i>
          </a>
				</label>
				
				<div class="controls">
					<input id="redirect" class="form-control input-xlarge" name="redirect" type="url" placeholder="eg, http://google.com" value="<?php echo $addLevel->getPost('redirect'); ?>"/>
				</div>
			</div>
      
      <div class="form-actions">
        <button type="submit" name="add_level" class="btn btn-primary" id="level-add-submit"><?php echo _('Create role'); ?></button>
      </div>
		</fieldset>
	</form>
</fieldset>
