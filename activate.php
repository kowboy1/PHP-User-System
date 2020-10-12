<?php

include_once ('initialise.php');
include_once ('header.php');

class Activate extends Generic {

	private $key;
	private $user;
	private $error;

	function __construct() {
    
		// Assign their username to a variable
		if(isset($_SESSION['jigowatt']['username']))
			$this->user = preg_replace('/[^A-Za-z0-9\-]/', '', $_SESSION['jigowatt']['username']);

		if(!isEmpty(filter_input(INPUT_GET, 'key', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '~^[a-z0-9]{40}$~'))))){
      
			$this->key = $_GET['key'];
			$this->checkKey();
			
		} else if(isset($_GET['resend']) && $_GET['resend'] == '1') {
      
      // they want the key resent
			
			$this->resendActivationKey();
			
		} else if(isset($this->user) && !isset($this->key)) {
      
      // they already signed in without a key
			$this->checkUserActivation();
			
		} else {
			header('Location: home.php');
			exit;
		}

		if ( $this->error ) {
		    echo $this->error;
		}

	}
	
	
	private function checkKey() {
  
    $params = array( ':key' => $this->key );
    $stmt   = parent::query("
      SELECT *
      FROM   `login_users`
      WHERE  SHA1(`email`) = :key
      AND `user_level` = 'a:1:{i:0;s:1:\"4\";}'
      ",
      $params
    );
    
    if($stmt->rowCount() !== 1) {
      $this->error = '<div class="alert alert-danger">' . _('Your activation link is incorrect.') . '</div>
          <h5>' . _('What to do now?') . '</h5>
          <p>' . sprintf(_('Go to the <a href="%s"> homepage</a>'), 'home.php') . '</p>';
      return false;
    }
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $row['username'];
    $to = $row['email'];
    
    // Activate by changing the user level from "4"
    
    parent::query("
      UPDATE `login_users`
      SET `user_level`   = 'a:1:{i:0;s:1:\"3\";}'
      WHERE SHA1(`email`) = :key
      AND `user_level` = 'a:1:{i:0;s:1:\"4\";}'
      LIMIT 1
      ",
      $params
    );
    
    // Set user's activate session to false
    if(!empty($_SESSION['jigowatt']['activate'])) unset($_SESSION['jigowatt']['activate']);

    echo "<div class=\"alert alert-success\">"._('Your account has been activated!')."</div>" ._('You can now see the default access granted to new users.')."
       <p>"._('If you require more access please contact the site admin at')." " . address . "</p>
       <h5>"._('What to do now?')."</h5>
       <p>" . sprintf(_('Go to the <a href="%s"> homepage</a>'), 'home.php') . "</p>";
    
    $shortcodes = array(
      'site_address' => SITE_PATH,
      'full_name'   =>  $row['name'],
      'username'    =>  $username
    );

    $msg = parent::getOption('email-activate-msg');
    $subj = parent::getOption('email-activate-subj');


    if (!sendEmail($to, $subj, $msg, $shortcodes)) {
      $this->error = "ERROR. Mail not sent";
    }
  
  }
	
	private function resendActivationKey() {

		$clear_username= $new = htmlspecialchars($this->user, ENT_QUOTES);
		$params = array( ':username' => $clear_username );
		
		$stmt   = parent::query("
      SELECT `username`, `name`, `email`
      FROM    `login_users`
      WHERE   `username`  = :username
      AND `user_level` = 'a:1:{i:0;s:1:\"4\";}';
    ",
		$params);

		$row = $stmt->fetch();
		
		$key = sha1($row['email']);

		if ( empty($key) ) {
			$this->error = '<div class="alert alert-danger">' . _('You do not have an activation key!') . '</div>
						    <p>' . _('Please contact an admin: ') . address . '</p>';
			return false;
		}

		$shortcodes = array(
			'site_address'	=>	SITE_PATH,
			'full_name'		=>	$row['name'],
			'username'		=>	$this->user,
			'activate'		=>	SITE_PATH . "activate.php?key=$key"
		);

		$subj = parent::getOption('email-activate-resend-subj');
		$msg = parent::getOption('email-activate-resend-msg');
		$to = $row['email'];
		
		// --- stimulate the send of the activation key link
		
// 		echo $shortcodes['activate'];
// 		exit;
		
		// ----------------------
		
		
		// --- this is the real send email activation link code
		
		if(sendEmail($to, $subj, $msg, $shortcodes)) {
			$this->error = '<div class="alert alert-success">' . _('Activation link resent to email.') . '</div>
					  <h5>' . _('What to do now?') . '</h5>'
					  . _('Click the link in your email to activate your account.');
		} else {
      $this->error = _('ERROR. Mail not sent');
		}
    
	}
	
	private function checkUserActivation() {

		// Check if user needs activation
		
		$clear_username = $new = htmlspecialchars($this->user, ENT_QUOTES);
		$params = array( ':username' => $clear_username );
		
		$stmt = parent::query("
      SELECT `user_level`
      FROM `login_users`
      WHERE `username` = :username
      AND `user_level` = 'a:1:{i:0;s:1:\"4\";}';
		",
		$params);
		
		if ($stmt->rowCount() !== 1) {
		
			unset($_SESSION['jigowatt']['activate']);
			$this->error = '<div class="alert alert-danger">'._('Your account has already been activated.').'</div>
        <h5>'._('What to do now?').'</h5>
        <p>' . sprintf(_('Go to the <a href="%s"> homepage</a>'), 'home.php') . '</p>';
		
		}else {
		
			$this->error = '<div class="alert alert-danger">'._('You have not activated your account yet.').'</div>
					  <h5>'._('What to do now?').'</h5>'
					 . '<p>' . _('Please follow the link in your email to activate your account.') . '</p>'
					 . '<p>' . sprintf(_('Would you like us to <a href="%s">resend</a> the link?'), 'activate.php?resend=1') . '</p>';
		}
	}
}

$generic = new Generic();
$activate = new Activate();

include ('footer.php');
