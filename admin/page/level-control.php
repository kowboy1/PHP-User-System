<?php

include_once __DIR__ . '/admin.php';

?>

<fieldset>
	<legend><?php echo _('Modify roles'); ?></legend>

	<div class="row">

		<div class="col-md-8 pull-right">
			<form method="post" id="search-levels-form" class="pull-right form-inline" action="page/search-levels.php">
			<div class="form-group">
			  <button id="create_new_level_btn" class="btn btn-default"><?php echo _('Create new role'); ?></button>
				<div class="input-group">
				  <span class="input-group-addon">
				    <label for="levelSearch"><a href="#" data-rel="tooltip-bottom" title="<?php echo _('Search by Name, Role, ID, or Redirect URL.'); ?>"><i class="glyphicon glyphicon-search"></i></a></label>
				  </span>
				  <input class="form-control" type="text" placeholder="<?php echo _('Role search'); ?>" onkeyup="searchSuggest(event);" name="searchLevels" id="searchLevels">
				</div>
				  <input type="number" class="form-control input-mini" min="0" id="showLevels" name="showLevels" placeholder="<?php echo _('Show'); ?>" value="<?php echo !empty($_SESSION['jigowatt']['levels_page_limit']) ? $_SESSION['jigowatt']['levels_page_limit'] : 10; ?>">
			  </div>
			</form>
		</div>

		<div class="col-md-4">
			<div class="img-thumbnail" style="display:none; width:100%;" id="search_suggest_level"></div>
		</div>

	</div>
  
	<div id="create_level" style="display:none;">
		<?php
      include_once __DIR__ . '/level-create.php';
		?>
	</div>

	<?php
    user_levels();
	?>
</fieldset>


