<?php

#ini_set('display_errors', 1);
#error_reporting(E_ALL);

session_start();

include_once( '../config.php' );
include_once( '../initialise.php' );

// include_once( dirname(dirname(__FILE__)) . '/settings.php' );
// include_once( dirname(__FILE__) . '/dbConnect.php' );
// include_once( dirname(__FILE__) . '/isEmpty.php' );
// // include_once( dirname(__FILE__) . '/getOption.php' );

$dbh = dbConnect($host, $dbName, $dbUser, $dbPass);

$errors = [];

#################################################################################################### --- INPUT VALIDATION

if($_SERVER["REQUEST_METHOD"] !== "POST"){
  $errors[] = _('Wrong request method!');
}

if(isEmpty(filter_input(INPUT_POST, 'name', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['safe']))))){
  $errors[] = _('Invalid name!');
}
/*
if(isEmpty(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) === true) {
  $errors[] = _('Invalid email!');
}*/

if(isEmpty(filter_input(INPUT_POST, 'twitterId', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['anyNumericOptional']))))){
  $errors[] = _('Invalid twitter id!');
}

#################################################################################################### --- USER REGISTRATION

if (isEmpty($errors)) {
  
  $checkUserRegistrationQ = $dbh->query("
    SELECT *
    FROM login_users
    WHERE social_network_id = " . $dbh->quote('twitter_' . $_POST['twitterId'])
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
        " . $dbh->quote('twitter_' . $_POST['twitterId']) . "
      )
    ");
    
    if($query){
      $_SESSION['jigowatt']['user_level'][] = 3;
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
      WHERE social_network_id = " . $dbh->quote('twitter_' . $_POST['twitterId'])
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






/*
private function social_login($provider) {

  $params = array( ':session' => $_SESSION['jigowatt'][$provider] );
  $stmt = parent::query("SELECT `user_id` FROM `login_integration` WHERE `$provider` = :session;", $params);

  if ($stmt->rowCount() > 0) {

    $result = $stmt->fetch();

    $params = array( ':user_id' => $result['user_id'] );
    $stmt = parent::query("SELECT * FROM `login_users` WHERE `user_id` = :user_id;", $params);

    $this->result = $stmt->fetch();

    $username = $this->username_type;
    $this->user = $this->result[$username];

    #$this->doLogin(); // leaving the current method for now
    $this->login();

  } else {

    unset(
      $_SESSION['jigowatt']['ot'],
      $_SESSION['jigowatt']['ots'],
      $_SESSION['jigowatt'][$provider]
    );

    header('Location: sign_up.php?new_social');
    exit();

  }

}
*/

?>
