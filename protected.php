<?php

require_once 'initialise.php';
require_once 'header.php';

?>

<div class="row">

	<div class="col-md-6">
	<?php if( protectThis("Admin") ) : ?>
		<h1 class="page-header"><?php echo _('Admin only text <small>User role: 1</small>'); ?></h1>
		<p><?php echo _('You will only be able to see this content if you have an <span class="label label-danger">administrator</span> user role. ')?></p>
		<pre>Super secret code that only admin can view</pre>
	<?php else : ?>
		<div class="alert alert-warning"><?php echo _('Only admins can view this content.'); ?></div>
	<?php endif; ?>
	</div>

	<div class="col-md-6">
	<?php if(protectThis("Admin, Special")) : ?>
		<h1 class="page-header"><?php echo _('Why hello, special user! <small>User role: 2</small>'); ?></h1>
		<p><?php echo _('You will only be able to see this content if you have a <span class="label label-info">special</span> user role. ')?></p>
		<pre>Only special users can view this</pre>
	<?php else : ?>
		<div class="alert alert-warning"><?php echo _('Only admins or special users can view this content.'); ?></div>
	<?php endif; ?>
	</div>

</div>

<div class="row">

	<div class="col-md-6">
	<?php if(protectThis("*")) : ?>
		<h1 class="page-header"><?php echo _('All registered users <small>User role: * </small>'); ?></h1>
		<p><?php echo _('Any user role in the entire world can see this! All that matters is that you\'re logged in.')?></p>
		<pre>All signed in users can view this</pre>
	<?php else : ?>
		<div class="alert alert-warning"><?php echo _('Only signed in users can view what\'s hidden here!'); ?></div>
	<?php endif; ?>
	</div>

	<div class="col-md-6">
		<h1 class="page-header"><?php echo _('Public content. <small>No sign in required.</small>'); ?></h1>
		<p><?php echo _('When visiting this page, anyone that is not signed in will be able to view your markup. ')?></p>
		<pre>Not-so super secret code, let's let everyone view this</pre>
	</div>

</div>

<?php include_once('footer.php'); ?>
