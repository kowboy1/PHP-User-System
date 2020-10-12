<?php

require_once '../initialise.php';

protect("Admin");

$generic = new Generic();

$errors = '';

if (isEmpty($dbh)) {
  $errors .= '<li>' . _('Database connection missing!') . '</li>';
}

if($errors !== ''){
  echo '
    <div class="alert alert-danger">
      <h4 class="alert-heading">' . _('Attention!') . '</h4>' .
      $errors . '
    </div>
  ';
}else{
  
  function jigowatt_upgrade() {
    
    $generic = new Generic();
    
    $phplogin_db_version = $generic->getOption('phplogin_db_version');
    $phplogin_db_version = (int) $phplogin_db_version;
    
//     if( $phplogin_db_version == phplogin_db_version ) {
//       return false;
//     }
    
    if ($phplogin_db_version < 1591930000) {
      upgrade_5_0_1();
    }
    
    if ($phplogin_db_version < 1595808000) {
      upgrade_5_0_2();
    }
    
    if ($phplogin_db_version < 1597164000) {
      upgrade_5_0_3();
    }
    
    if ($phplogin_db_version < 1598313600) {
      upgrade_5_0_4();
    }
    
    return true;
  }
  
  function upgrade_5_0_1() {
    global $dbh;
    $generic = new Generic();
    
    $generic->updateOption('phplogin_db_version', 1591930000);
    $generic->updateOption('phplogin_version', '5.0.1');
  }
  
  function upgrade_5_0_2() {
    global $dbh;
    $generic = new Generic();
    
    $generic->updateOption('phplogin_db_version', 1595808000);
    $generic->updateOption('phplogin_version', '5.0.2');
  }
  
  function upgrade_5_0_3() {
    global $dbh;
    $generic = new Generic();
    
    $generic->updateOption('phplogin_db_version', 1597164000);
    $generic->updateOption('phplogin_version', '5.0.3');
  }
  
  function upgrade_5_0_4() {
    global $dbh;
    $generic = new Generic();
    
    $generic->updateOption('phplogin_db_version', 1598313600);
    $generic->updateOption('phplogin_version', '5.0.4');
  }
  
  $result = jigowatt_upgrade();
  
  if ($result === true) {
    
    header('Location: ' . $generic->getOption('site_address') . 'admin/page/update.php');
    echo '<div class="alert alert-success">' . _('Your database has been successfully upgraded!') . '</div>';
  } else {
    echo '<div class="alert alert-danger">' . _('Failed database update!') .'</div>';
  }
  
}

?>
