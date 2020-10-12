<?php

#ini_set('display_errors', 1);
#error_reporting(E_ALL);

session_start();

include_once( '../config.php' );
include_once( '../initialise.php' );

// include_once( dirname(dirname(__FILE__)) . '/settings.php' );
// include_once( dirname(__FILE__) . '/dbConnect.php' );
// include_once( dirname(__FILE__) . '/isEmpty.php' );
// include_once( dirname(__FILE__) . '/getOption.php' );

$dbh = dbConnect($host, $dbName, $dbUser, $dbPass);

$errors = [];

#################################################################################################### --- INPUT VALIDATION

if($_SERVER["REQUEST_METHOD"] !== "POST"){
  $errors[] = _('Wrong request method!');
}

if(isEmpty(filter_input(INPUT_POST, 'name', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['safe']))))){
  $errors[] = _('Invalid name!');
}

if(isEmpty(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) === true) {
  $errors[] = _('Invalid email!');
}

if(isEmpty(filter_input(INPUT_POST, 'googleId', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['anyNumeric']))))){
  $errors[] = _('Invalid google id!');
}

#################################################################################################### --- USER REGISTRATION

if (isEmpty($errors)) {
  $checkUserRegistrationQ = $dbh->query("
    SELECT *
    FROM login_users
    WHERE social_network_id = " . $dbh->quote('google_' . $_POST['googleId'])
  );
  
  if($checkUserRegistrationQ->rowCount() === 0){
    
    // not registered
    
    $query = $dbh->query("
      INSERT INTO login_users (user_level, restricted, name, email, password, username, social_network_id)
      VALUES (
        'a:1:{i:0;s:1:\"3\";}',
        '0',
        " . $dbh->quote($_POST['name']) . ",
        " . $dbh->quote($_POST['email']) . ",
        '',
        " . $dbh->quote($_POST['name']) . ",
        " . $dbh->quote('google_' . $_POST['googleId']) . "
      )
    ");
    
    if($query){
      $_SESSION['jigowatt']['user_level'][] = '3';
      $_SESSION['jigowatt']['username'] = $_POST['name'];
      
      echo json_encode([
        'success' => true,
        'redirectUrl' => getOption('site_address')
      ]);
      
    }else{
      $errors[] = _('Unsuccessful entry!');
    }
  }else{
    
    // already registered
    
    $userLevelQ = $dbh->query("
      SELECT user_level
      FROM login_users
      WHERE social_network_id = " . $dbh->quote('google_' . $_POST['googleId'])
    );
    
    $userLevelR = $userLevelQ->fetch(PDO::FETCH_ASSOC);
    
    $_SESSION['jigowatt']['user_level'][] = unserialize($userLevelR['user_level']);
    $_SESSION['jigowatt']['username'] = $_POST['name'];
    
    echo json_encode([
      'success' => true,
      'redirectUrl' => getOption('site_address')
    ]);
  }
}

if ($errors) {
  echo json_encode([
    'success' => false,
    'msg' => $errors
  ]);
}

?>
