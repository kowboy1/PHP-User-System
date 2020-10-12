<?php

/**
 * Process and validate the sign up form.
 *
 * LICENSE:
 *
 * This source file is subject to the licensing terms that
 * is available through the world-wide-web at the following URI:
 * http://codecanyon.net/wiki/support/legal-terms/licensing-terms/.
 *
 * @author       Jigowatt <info@jigowatt.co.uk>
 * @copyright    Copyright © 2009-2019 Jigowatt Ltd.
 * @license      http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 * @link         http://codecanyon.net/item/php-login-user-management/49008
 */

#include_once( 'generic.class.php' );
// include_once( 'config.php' );
// include_once( 'functions/dbConnect.php' );

class SignUp extends Generic {
  
	private $token;
	private $error;
	private $captchaError;
	private $settings = array();

	public $user_emails = false;

	function __construct() {

		// Only allow guests to view this page
		parent::guestOnly();

		/* Has the admin disabled user registrations? */
		$disable = parent::getOption('disable-registrations-enable');
		if ( $disable ) {
			$this->error = sprintf( '<div class="alert alert-block alert-danger">%s</div>', _('<h4 class="alert-heading">Registrations disabled.</h4><p>Already have an account? <a href="login.php">Sign in here</a>!</p>') );
			parent::displayMessage($this->error, true);
		}

		$this->use_emails = parent::getOption('email-as-username-enable');
		$this->username_type = ( $this->use_emails ) ? 'email' : 'username';

		// jQuery form validation
		parent::checkExists();

		// Generate a unique token for security purposes
		parent::generateToken();
    
		// Has the form been submitted?
		if(!empty($_POST)) {

			// Sign up form post data
			foreach ($_POST as $field => $value)
				$this->settings[$field] = parent::secure($value);

			$this->process();

		}
		
		if (isset($_GET['new_social']))
			$this->error = sprintf( '<div class="alert alert-success">%s</div>', _('We don\'t see you as a registered user. Perhaps you\'d like to sign up :)') );

		if ( $this->error ) {
		    echo $this->error;
		}
	}
  
	public function profileSignUpFields() {

		$sql = 'SELECT * FROM `login_profile_fields` WHERE `signup` <> "hide";';
		$stmt = parent::query($sql);

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
			$option = array(
				'name'  => $row['label'],
				'id'    => $row['id'],
				'type'  => $row['type'],
				'class' => $row['signup'] === 'require' ? 'required' : '',
			);
			parent::profileFieldTypes($option, true);
		endwhile;

	}

	public function doCaptcha( $display = true ) {

		$captcha = parent::getOption('integration-captcha');

		switch ( $captcha ) :

			case 'reCAPTCHA' :
				$this->captchaError = true;
				$publickey  = parent::getOption('reCAPTCHA-public-key');
				$privatekey = parent::getOption('reCAPTCHA-private-key');
				if ( $display ) {
 					/*?>
						<input type="hidden" id="recaptcha_result" name="recaptcha_result" value="1"/>
						<script type="text/javascript">
							var onloadCallback = function() {
								grecaptcha.render('html_element', {
									'sitekey' : "<?php echo $publickey; ?>",
									'callback' : function( result ) { document.getElementById('recaptcha_result').value = 0; },
									'error-callback' : function( result ) { document.getElementById('recaptcha_result').value = 1; }
								});
							};
						</script>
						<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
						<div id="html_element"></div>
					<?php*/
				}
				if ( isset( $_POST['recaptcha_result'] ) && isset( $_POST['g-recaptcha-response'] ) ) {
					$fields_string = '';
					$fields = array(
						'secret' => urlencode( $privatekey ),
						'response' => urlencode( $_POST['g-recaptcha-response'] ),
					);
					foreach ( $fields as $key => $value ) { $fields_string .= $key . '=' . $value . '&'; }
					rtrim( $fields_string, '&' );
					$ch = curl_init();
					curl_setopt( $ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify" );
					curl_setopt( $ch, CURLOPT_POST, count( $fields ) );
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					$server_output = curl_exec( $ch );
					curl_close ( $ch );
					$verify_result = json_decode( $server_output );
					if ( true === $verify_result->success ) {
						$this->captchaError = false;
					} else {
						$this->captchaError = true;
					}
				}
				break;

			case 'playThru' :

				if ( !defined ('AYAH_PUBLISHER_KEY') )    define( 'AYAH_PUBLISHER_KEY'   , parent::getOption('playThru-publisher-key') );
				if ( !defined ('AYAH_SCORING_KEY') )      define( 'AYAH_SCORING_KEY'     , parent::getOption('playThru-scoring-key') );
				if ( !defined ('AYAH_WEB_SERVICE_HOST') ) define( 'AYAH_WEB_SERVICE_HOST', 'ws.areyouahuman.com' );

				require_once('captcha/ayah-1.0.2/ayah.php');

				$integration = new AYAH();

				if ( (!$display && !empty($_POST)) && !$integration->scoreResult() )
					$this->captchaError = true;

				/* Show the captcha form. */
				if ( $display )
					echo $integration->getPublisherHTML();

				break;

		endswitch;

	}
  
	private function process() {
    
		// Check that the token is valid, prevents exploits
		if(!parent::valid_token($this->settings['token']))
			$this->error = '<div class="alert alert-danger">'._('Invalid signup attempt').'</div>';

		// Check the captcha response.
		$this->doCaptcha(false);

		// See if all the values are correct
		$this->validateInput();
		
		// Sign um up!
		$this->register();

	}
	
	public function validateInput() {
    global $dbh;
    
    $minPassLength = 5;
    
    $patterns = [
      'token'     => '~^[a-z0-9]{32}$~', // md5
      'name'      => '~^[\p{L}\p{M} \'-]+$~u', // unicode letters, marks, space, apostrophe and dash
      'username'  => '~^[A-Za-z0-9_-]+$~' // could do this unicode as well
    ];
    
    // ----------
    
    if(
      (isEmpty(filter_input(INPUT_POST, 'token', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $patterns['token'])))) === true) ||
      ($_POST['token'] !== $_SESSION['jigowatt']['token'])
    ){
      $this->error .= '<div class="alert alert-danger">'._('Invalid signup attempt').'</div>';
    }
    // ----------
    
    if(isEmpty(filter_input(INPUT_POST, 'name', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $patterns['name'])))) === true){
      $this->error .= '<li>'._('You must enter a valid name!').'</li>';
    }
    
    // ----------
    
    if ( !$this->use_emails ) {
      if(isEmpty(filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $patterns['username'])))) === true){
        $this->error .= '<li>'._('You must enter a valid username!').'</li>';
      }else{
        
        $stmt = $dbh->prepare("
          SELECT *
          FROM login_users
          WHERE username = :username
        ");
        
        $stmt->execute([
          ':username' => $this->settings['username']
        ]);
        
        if ($stmt->rowCount() !== 0) {
          $this->error .= '<li>Sorry, that username is already taken!</li>';
        }
      }
    }
    
    // ----------
    
    if(isEmpty(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) === true){
      $this->error .= '<li>'._('You have entered an invalid e-mail address, please try again!').'</li>';
    }else{
      // See if this email is allowed
      $allowed = parent::getOption('restrict-signups-by-email');
      
      if ( $allowed ) {
        $allowed = unserialize($allowed);
        $domain = array_pop(explode('@', $this->settings['email']));
        
        if (in_array($domain, $allowed)) {
          $this->error .= '<li>'._('That email address is not allowed.').'</li>';
        }
      }
      
      // ----------
      
      // Check for a taken email address
      $params = array( ':email' => $this->settings['email'] );
      $stmt = parent::query("SELECT * FROM login_users WHERE email = :email;", $params);
      
      if ($stmt->rowCount() !== 0){
        $this->error .= '<li>'._('Sorry, that email address has already been taken!').'</li>';
      }
    }
    
    // ----------
    
    if((int)strlen($_POST['password']) < $minPassLength || (int)strlen($_POST['password_confirm']) < $minPassLength){
      $this->error .= '<li>'._('Your password must be at least ' . $minPassLength . ' characters.').'</li>';
    }
    
    if($_POST['password'] !== $_POST['password_confirm']){
      $this->error .= '<li>'._('Your passwords did not match.').'</li>';
    }
    
    // ----------
    
    /* Check the captcha response. */
    $this->doCaptcha(false);
    
    if($this->captchaError === true) {
      $this->error .= '<li>'._('Please enter the correct captcha!').'</li>';
    }
    
    // Checkbox handling
		$sql = "SELECT * FROM `login_profile_fields` WHERE `signup` <> 'hide';";
		$stmt = parent::query($sql);

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :

			$name = 'p-' . $row['id'];

			if( $row['type'] == 'checkbox' )
				$this->settings[$name] = !empty($this->settings[$name]) ? 1 :0;

			/* Required signup fields validation. */
			if( $row['signup'] == 'require' && empty($this->settings[$name]) )
				$this->error .= '<li>' . sprintf('The field "%s" is required!', $row['label']) . '</li>';

		endwhile;
    
    // Output the errors in a pretty format :]
		$this->error = (isset($this->error)) ? "<div class='alert alert-danger alert-block'><h4 class='alert-heading'>"._('Attention!')."</h4>" . $this->error . "</div>" : '';
  }
	
	// Return a value if it exists
	public function getPost($var) {

		$socialLogin = array(
			'twitter',
			'facebook',
			'google',
			'yahoo'
		);
		foreach ($socialLogin as $value) {
			if( !empty($_SESSION['jigowatt'][$value . 'Misc']) ) {
				$misc = $_SESSION['jigowatt'][$value . 'Misc'];
			}
		}

		if ( !empty($misc) ) :

			switch ($var) :

				case 'email' :
					return $misc['email'];
					break;

				case 'name' :
					return $misc['firstName'] . ' ' . $misc['lastName'];
					break;

				case 'username' :
					if( !empty($misc['username']) ) return $misc['username'];
					if( !empty($misc['displayName']) ) return $misc['displayName'];
					break;

			endswitch;

		endif;

		return empty($this->settings[$var]) ? '' : $this->settings[$var];
	}

	
	// Once everything's filled out
	private function register() {
    global $dbh;
    
		if(empty($this->error)) {

			/* See if the admin requires new users to activate */
			$requireActivate = parent::getOption('user-activation-enable');

			/* Log user in when they register */
			$_SESSION['jigowatt']['username'] = $this->settings[$this->username_type];

			/* Apply default user_level */
			$_SESSION['jigowatt']['user_level'] = unserialize(parent::getOption('default-level'));

			if ( $requireActivate )
				$_SESSION['jigowatt']['activate'] = 1;

			$_SESSION['jigowatt']['gravatar'] = parent::get_gravatar($this->settings['email'], true, 26);
      
			/* Create their account */
			$sql = "INSERT INTO login_users (user_level, restricted, name, email, username, password)
						VALUES (:user_level, 0, :name, :email, :{$this->username_type}, :password);";
			$params = array(
				':user_level' => parent::getOption('default-level'),
				':name'       => $this->settings['name'],
				':email'      => $this->settings['email'],
				':username'   => $this->settings['username'],
				':password'   => hashPassword($this->settings['password'])
			);

			if ( $this->use_emails ) unset($params[':username']);
// 			$stmt = $dbh->prepare($sql);
// 			$stmt->execute($params);
			parent::query($sql, $params);
			
			$user_id = $dbh->lastInsertId();
			$_SESSION['jigowatt']['user_id'] = $user_id;

			/* Social integration. */
			if ( !empty($_SESSION['jigowatt']['facebookMisc']) ) {
				$link = 'facebook';
				$id = $_SESSION['jigowatt']['facebookMisc']['id'];
			}

			if ( !empty($_SESSION['jigowatt']['openIDMisc']) ) {
				$link = $_SESSION['jigowatt']['openIDMisc']['type'];
				$id = $_SESSION['jigowatt']['openIDMisc'][$link];
			}

			if ( !empty($_SESSION['jigowatt']['twitterMisc']) ) {
				$link = 'twitter';
				$id = $_SESSION['jigowatt']['twitterMisc']['id'];
			}

			if ( !empty($link) ) {

				$params = array(
					':user_id' => $user_id,
					':id'      => $id,
				);
				parent::query("INSERT INTO `login_integration` (`user_id`, `$link`) VALUES (:user_id, :id);", $params);

			}

			// Update profile fields
			foreach($this->settings as $field => $value) :
				if(strstr($field,'p-')) {
					$field = str_replace('p-', '', $field);
					parent::updateOption($field, $value, true, $user_id);
				}
			endforeach;

			/* Create the activation key */
			if ( $requireActivate ) {
        $key = sha1($this->settings['email']);
			}
      
			$disable_welcome_email = parent::getOption('email-welcome-disable');
			if ( !$disable_welcome_email ) {

				/* Send welcome email to new user. */
				$msg  = parent::getOption('email-welcome-msg');
				$subj = parent::getOption('email-welcome-subj');

				$shortcodes = array(
					'site_address' => SITE_PATH,
					'full_name'    => $this->settings['name'],
					'username'     => $this->settings[$this->username_type],
					'email'        => $this->settings['email'],
					'activate'     => $requireActivate ? SITE_PATH . "activate.php?key=$key" : ''
				);

				if(!sendEmail($this->settings['email'], $subj, $msg, $shortcodes))
					$this->error = _('ERROR. Mail not sent');

			}

			/* Admin notification of new user. */
			$notifyNewUsers = parent::getOption('notify-new-user-enable');
			if ( !empty($notifyNewUsers) ) :

				$msg  = parent::getOption('email-new-user-msg');
				$subj = parent::getOption('email-new-user-subj');
				unset($shortcodes['activate']);

				$userGroup = parent::getOption('notify-new-users');
				if ( !empty($userGroup) ) :
					$userGroup = unserialize($userGroup);

					/* Variable to store all the email addresses of each chosen group. */
					$emails = array();

					foreach ( $userGroup as $level_id ) :

						/* Grab all users within the user group. */
						$params = array( ':level_id' => '%:"' . $level_id . '";%' );
						$sql = "SELECT * FROM `login_users` WHERE `user_level` LIKE :level_id";
						$stmt = parent::query($sql, $params);

						/* Send email to each user in group. */
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
							$emails[] = $row['email'];
						endwhile;

					endforeach;

					/* Remove duplicates for users with multiple user groups. */
					$emails = array_unique($emails);

					if(!sendEmail($emails, $subj, $msg, $shortcodes, true))
						$this->error = _('ERROR. Mail not sent');

				endif;

			endif;

			unset(
				$_SESSION['jigowatt']['referer'],
				$_SESSION['jigowatt']['token'],
				$_SESSION['jigowatt']['facebookMisc'],
				$_SESSION['jigowatt']['twitterMisc'],
				$_SESSION['jigowatt']['openIDMisc']
			);

			/* After registering, redirect to the page the admin has set in Settings > General > Redirect Options. */
			header('Location: ' . parent::getOption('site_address') );
			exit();

		}

	}

}

?>
