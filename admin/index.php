<?php

require_once '../initialise.php';

#################################################################################################### --- 

$generic = new Generic();

if ( !isset($_POST['add_user']) && !isset($_POST['add_level']) && !isset($_POST['searchUsers']) ) {
  require_once 'header.php';
}

protect("Admin");

?>

<div class="row">
  <div class="tabbable tabs-left">
    <ul class="nav nav-tabs">
      <li><a href="#user-control" data-toggle="tab"><i class="glyphicon glyphicon-list"></i> <?php echo _('Users'); ?></a></li>
      <li><a href="#level-control" data-toggle="tab"><i class="glyphicon glyphicon-list"></i> <?php echo _('Roles'); ?></a></li>
      <li><a href="#reports" data-toggle="tab"><i class="glyphicon glyphicon-folder-open"></i> <?php echo _('Reports'); ?></a></li>
      <li><a href="#send-email" data-toggle="tab"><i class="glyphicon glyphicon-envelope"></i> <?php echo _('Send email'); ?></a></li>
      <li><a href="settings.php"><i class="glyphicon glyphicon-cog"></i> <?php echo _('Settings'); ?></a></li>
    </ul>

    <div class="tab-content">

      <!-- - - - - - - - - - - - - - - - -

          Control users

      - - - - - - - - - - - - - - - - - -->
      <div class="tab-pane col-md-10 display" id="user-control">
        <?php include_once('page/user-control.php'); ?>
      </div>

      <!-- - - - - - - - - - - - - - - - -

          Modify roles

      - - - - - - - - - - - - - - - - - -->

      <div class="tab-pane col-md-10 fade" id="level-control">
        <?php include_once('page/level-control.php'); ?>
      </div>
      <!-- - - - - - - - - - - - - - - - -

          Reports

      - - - - - - - - - - - - - - - - - -->
      <div class="tab-pane col-md-10 fade" id="reports">
        <?php include_once('page/reports.php'); ?>
      </div>

      <!-- - - - - - - - - - - - - - - - -

          Send email

      - - - - - - - - - - - - - - - - - -->
      <div class="tab-pane col-md-10 fade" id="send-email">
        <?php include_once('page/send-email.php'); ?>
      </div>

    </div>
  </div>
</div>

<?php

require_once 'footer.php';
