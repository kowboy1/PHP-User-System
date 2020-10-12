<?php

include_once __DIR__ . '/admin.php';

?>

<legend><?php echo _('Control users'); ?></legend>

<div class="row">

	<div class="col-md-8 pull-right">
		
		
		<form method="post" id="search-users-form" action="page/search-users.php" class="pull-right form-inline">
			<div class="form-group">
        <button id="add_new_user_btn" class="btn btn-default"><?php echo _('Add new user'); ?></button>
				<div class="input-group">
				  <span class="input-group-addon">
					<label for="username-search"><a href="#" data-rel="tooltip-bottom" title="<?php echo _('Search by Username, Name, or ID!'); ?>"><i class="glyphicon glyphicon-search"></i></a></label>
				  </span>
				  <input class="form-control" id="username-search" type="text" name="searchUsers" onkeyup="searchSuggest(event);" placeholder="<?php echo _('User search'); ?>">
				</div>
			  <input type="number" class="form-control input-mini" min="0" id="showUsers" name="showUsers" placeholder="<?php echo _('Show'); ?>" value="<?php echo !empty($_SESSION['jigowatt']['users_page_limit']) ? $_SESSION['jigowatt']['users_page_limit'] : 10; ?>">
			</div>
		</form>
	</div>

	<div class="col-md-4">
		<div class="img-thumbnail" style="display:none; width:100%;" id="search_suggest_user"></div>
	</div>

</div>

<div id="add_user" style="display:none;">
	<?php
    include_once __DIR__ . '/user-add.php';
	?>
</div>

<div id="user_list">
	<?php list_registered(); ?>
</div>
