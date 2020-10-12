<?php

/**
 * Generic functions used throughout the script.
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

// date_default_timezone_set('GMT');
// 
// include_once( 'translate.class.php' );
// include_once( 'connect.class.php' );

class Generic extends Connect {
  
	private $error;

	function __construct() {

		// Check to make sure install is complete
		$this->error = parent::checkInstall();

		// Start the session. Important.
		if ( ! session_id() ) {
			$minutes = self::getOption('default_session');
			ini_set('session.cookie_lifetime', 60 * $minutes);
			session_start();
		}
        if ( ! isset( $_SESSION['jigowatt'] ) ) {
            $_SESSION['jigowatt'] = array();
        }

		include( 'prereqs.php' );

		// Call the connection
		if(empty($this->error)) $this->error = parent::dbConn();

		// define paths and other constants
		if (!defined('cINC'))
			define( 'cINC', dirname($_SERVER['SCRIPT_FILENAME']) . '/' );
		if (!defined('address'))
			define( 'address', $this->getOption('admin_email') );
		if (!defined('SITE_PATH'))
			define( 'SITE_PATH', $this->getOption('site_address') );
		// Used for keeping track of necessary db updates
	   	// Follows format - Year : Month : Day : Revision
		if (!defined('phplogin_db_version'))
			define( 'phplogin_db_version', $this->getOption('phplogin_db_version'));
		if (!defined('phplogin_version'))
			define( 'phplogin_version', $this->getOption('phplogin_version'));

// 		// Check if an upgrade is required
// 		if(empty($this->error)) {
// 			include_once( 'upgrade.class.php' );
// 		}

	}

	/**
	 * Get the current error message if any.  Used for displayMessage.
	 *
	 * @return string|null the error message.
	 */
	public function get_error()
	{
		return $this->error;
	}

	/**
	 * Returns a mySQL query.
	 *
	 * @param     string      $query    An SQL statement.
	 * @param     array       $params   The binded variables to an SQL statement.
	 * @return    resource    Returns the query's execution.
	 */
	public function query($query, $params = array(), $format = array()) {

		if ( !is_array( $params ) ) return false;

		global $dbh;

		if ( isEmpty($dbh) ) return false;

		$stmt = $dbh->prepare($query);

		if ( !empty($format)) {
			$values = array_values($params);
			foreach ( $format as $key => $bind ) {
				switch ($bind) {
					case '%d':
						$stmt->bindValue($key + 1, $values[$key], PDO::PARAM_INT);
						break;
					case '%s':
						$stmt->bindValue($key + 1, $values[$key], PDO::PARAM_STR);
						break;
					default:
						$stmt->bindValue($key + 1, $values[$key], PDO::PARAM_STR);
						break;
				}

			}
		}

		$stmt->execute($params);


		return $stmt;

	}

	/**
	 * Retrieves an option value based on option name.
	 *
	 * @param     string    $option    Name of option to retrieve.
	 * @param     bool      $check     Whether the option is a checkbox.
	 * @param     bool      $profile   Whether to return a profile field, or an admin setting.
	 * @param     int       $id        Required if profile is true; the user_id of a user.
	 * @return    string    The option value.
	 */
	public function getOption($option, $check = false, $profile = false, $id = '') {

		if (empty($option)) return false;

		$option = trim($option);

		if ( $profile ) {
			$params = array(
				':option' => $option,
				':id'     => $id
			);
			
			$sql = "SELECT `profile_value` FROM `login_profiles` WHERE `pfield_id` = :option AND `user_id` = :id LIMIT 1;";
		} else {
			
			$params = array( ':option' => $option );
			$sql = "SELECT `option_value` FROM `login_settings` WHERE `option_name` = :option LIMIT 1;";
		}

		global $dbh;
		
		if (isEmpty($dbh)) return false;
		
    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);

		if(!$stmt) return false;

		$result = $stmt->fetch(PDO::FETCH_NUM);
		$result = $result ? $result[0] : false;

		if($check)
			$result = !empty($result) ? 'checked="checked"' : '';

		return $result;

	}

	/**
	 * Updates an option in the database.
	 *
	 * If an option exists in the database, it will be updated. If it does not exist,
	 * the option will be created.
	 *
	 * @param     string    $option      Name of option to retrieve.
	 * @param     bool      $newvalue    Option's new value to set.
	 * @param     bool      $profile     Whether to update a profile field, or an admin setting.
	 * @param     int       $id          Required if profile is true; the user_id of a user.
	 * @return    bool      Whether the update was successful or not.
	 */
	public function updateOption($option, $newvalue, $profile = false, $id = '') {
    
    global $dbh;

		$option = trim($option);
		
		if ( isEmpty($option) || !isset($newvalue) ) {
			return false;
		}

		if ($profile) {
      $oldvalue = $this->getOption($option, false, true, $id);
		} else {
      $oldvalue = $this->getOption($option);
		}

		if ( $newvalue === $oldvalue ){
			return false;
		}

		// ----------

		$params = [
			':option' => $option,
			':newvalue' => is_array($newvalue) ? serialize($newvalue) : html_entity_decode($newvalue, ENT_QUOTES)
		];

		if ($oldvalue === false) {

			if ($profile) {
				
				$params[':id'] = $id;
				
				$sql = "
          INSERT INTO `login_profiles` (`user_id`, `pfield_id`, `profile_value`)
          VALUES (:id, :option, :newvalue);
				";
				
			} else {
        $sql = "
          INSERT INTO `login_settings` (`option_name`, `option_value`)
          VALUES (:option, :newvalue)
        ";
			}
			
			/*
      $stmt = $dbh->prepare($sql);
      $stmt->execute($params);
      */

			return $this->query($sql, $params);
		}

		if ($profile) {
			$params[':id'] = $id;
			$sql = "UPDATE `login_profiles` SET `profile_value` = :newvalue WHERE `pfield_id` = :option AND `user_id` = :id";
		} else {
			$sql = "UPDATE `login_settings` SET `option_value` = :newvalue WHERE `option_name` = :option";
		}

		return $this->query($sql, $params);
	}

	/**
	 * Sanitizes titles intended for SQL queries.
	 *
	 * Specifically, HTML and PHP tag are stripped. The return value
	 * is not intended as a human-readable title.
	 *
	 * @param     string    $title    The string to be sanitized.
	 * @return    string    The sanitized title.
	 */
	public function sanitize_title($title) {

		$title = strtolower($title);
		$title = preg_replace('/&.+?;/', '', $title); // kill entities
		$title = str_replace('.', '-', $title);
		$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
		$title = preg_replace('/\s+/', '-', $title);
		$title = preg_replace('|-+|', '-', $title);
		$title = trim($title, '-');

		return $title;

	}

	/**
	 * Sends HTML emails with optional shortcodes.
	 *
	 * @param     string    $to            Receiver of the mail.
	 * @param     string    $subj          Subject of the email.
	 * @param     string    $msg           Message to be sent.
	 * @param     array     $shortcodes    Shortcode values to replace.
	 * @param     bool      $bcc           Whether to send the email using Bcc: rather than To:
	 *                                     Useful when sending to multiple recepients.
	 * @return    bool      Whether the mail was sent or not.
	 */
	
// 	public function sendEmail($to, $subj, $msg, $shortcodes = '', $bcc = false) {
//     
// 		if ( !empty($shortcodes) && is_array($shortcodes) ) :
// 
// 			foreach ($shortcodes as $code => $value)
// 				$msg = str_replace('{{'.$code.'}}', $value, $msg);
// 
// 		endif;
// 
// 		/* Multiple recepients? */
// 		if ( is_array( $to ) )
// 			$to = implode(', ', $to);
//     
//     $mail = new PHPMailer(TRUE);
//     
//     $mail->CharSet = "UTF-8";
//     
//     $mail->setFrom(address);
//     $mail->addAddress($to);
//     $mail->Subject = $subj;
//     
//     $mail->isHTML(TRUE);
//     
//     $mail->Body = nl2br(html_entity_decode($msg));
//     
//     return $mail->send();
//     
// // 		$headers  = 'MIME-Version: 1.0' . "\r\n";
// // 		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// // 		$headers .= 'From: ' . address . "\r\n";
// // 
// // 		/* BCC address. */
// // 		if ( $bcc ) {
// // 			$headers .= 'Bcc: ' . $to . "\r\n";
// // 			$to = null;
// // 		}
// // 
// // 		$headers .= 'Reply-To: ' . address . "\r\n";
// // 		$headers .= 'Return-Path: ' . address . "\r\n";
// // 
// // 		/*
// // 		 * If running postfix, need a fifth parameter since Return-Path doesn't always work.
// // 		 */
// // 		// $optionalParams = '-r' . address;
// // 		$optionalParams = '';
// // 
// // 		return mail($to, $subj, nl2br(html_entity_decode($msg)), $headers, $optionalParams);
// 
// 	}

	/**
	 * Generate profile fields.
	 *
	 * Will populate the returned fields with data from the current user.
	 */
	public function generateProfile($section = '') {

		$params = array( ':section' => $section );
		$sql    = "SELECT * FROM `login_profile_fields` WHERE `section` = :section;";
		$stmt   = $this->query($sql, $params);

		$user_id = $this->getField('user_id');

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
			$option = array(
				'name' => $row['label'],
				'id' => $row['id'],
				'type' => $row['type'],
			);
			self::profileFieldTypes($option, false, $user_id);
		endwhile;

	}

	public function profileFieldTypes($option, $signup = false, $user_id = '') {

		extract($option);

		$p_id = 'p-' . $id;

		if ( isEmpty($type) || isEmpty($id) )
			return false;

		?>
		<div class="form-group">
			<label class="control-label" for="<?php echo $p_id; ?>"><?php echo $name; ?></label>
			<div class="controls">
		<?php

		switch ($type) :
				case 'text_input' : ?>
					<input type="text"
						   class="form-control input-xlarge <?php echo !empty($class) ? $class : ''; ?>"
						   id="<?php echo $p_id; ?>"
						   name="<?php echo $p_id; ?>"
						   value="<?php echo htmlspecialchars(!empty($_POST[$p_id]) ? $_POST[$p_id] : ( $signup ? '' : $this->getOption($id, false, true, $user_id) ), ENT_COMPAT|ENT_QUOTES); ?>"
					>
				<?php break;
				case 'checkbox' : ?>
					<input type="checkbox"
						   class="input-xlarge <?php echo !empty($class) ? $class : ''; ?>"
						   id="<?php echo $p_id; ?>"
						   name="<?php echo $p_id; ?>"
						   <?php echo !empty($_POST[$p_id]) ? 'checked="checked"' : ( $signup ? '' : $this->getOption($id, true, true, $user_id) ); ?>
					>
				<?php break;
				case 'textarea' : ?>
					<textarea class="form-control input-xlarge <?php echo !empty($class) ? $class : ''; ?>"
							  id="<?php echo $p_id; ?>"
							  name="<?php echo $p_id; ?>"
							  rows="5"><?php echo htmlspecialchars(!empty($_POST[$p_id]) ? $_POST[$p_id] : ( $signup ? '' : $this->getOption($id, false, true, $user_id) ), ENT_COMPAT|ENT_QUOTES); ?></textarea>
				<?php break;
		endswitch;

		?>
			</div>
		</div>
		<?php

	}

	public function generateProfileTabs($edit = false) {
		
		# todo: find a solution about overall $dbh scope and availability
		global $dbh;
		
		$stmt = $dbh->query("
      SELECT `section`
      FROM `login_profile_fields`
      GROUP BY `section`
		");

		$i = 0;

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			?><li class="<?php if ( $i === 0 && $edit ) echo 'active'; ?>">
				<a href="#usr-<?php echo $this->sanitize_title($row['section']); ?>" data-toggle="tab">
					<i class="glyphicon glyphicon-user"></i> <?php echo $row['section']; ?></a>
			  </li><?php
			$i++;
		}

	}

	public function generateProfilePanels($edit = false) {

		global $dbh;
		
		$stmt = $dbh->query("
      SELECT `section`
      FROM `login_profile_fields`
      GROUP BY `section`
		");

		$i = 0;

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) :

			?><div class="tab-pane <?php if ( $i === 0 && $edit ) echo 'active'; else echo 'fade'; ?>" id="usr-<?php echo $this->sanitize_title($row['section']); ?>">
				<fieldset>
					<legend><?php echo $row['section']; ?></legend>
					<?php $this->generateProfile($row['section']); ?>
				</fieldset>
			</div><?php
			$i++;
		endwhile;

	}

	/**
	 * Checks if a user has access to view their own access log
	 *
	 * @return    bool    Whether the user can view access logs or not
	 */
	public function denyAccessLogs() {

		return ( ($this->getOption('profile-timestamps-admin-enable') && !in_array(1, $_SESSION['jigowatt']['user_level'])) || !$this->getOption('profile-timestamps-enable') );

	}

	/** Generates the access logs for a particular user in table format */
	public function generateAccessLogs() {

		$user_id = $this->getField('user_id');

		$params = array( ':user_id' => $user_id );
		$sql = "SELECT `ip`, `timestamp` FROM `login_timestamps` WHERE `user_id` = :user_id ORDER BY `timestamp` DESC LIMIT 0,10";
		$stmt = $this->query($sql, $params);

	?>
	<table class="table table-condensed col-md-6">
		<thead>
			<tr>
				<th><?php echo _('Last Login'); ?></th>
				<th><?php echo _('Location'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if($stmt->rowCount() > 0) : ?>
		<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
			<tr>
				<td><?php echo date('M d, Y', strtotime($row['timestamp'])) . ' ' . _('at') . ' ' . date('h:i a', strtotime($row['timestamp'])); ?></td>
				<td><?php echo $row['ip']; ?></td>
			</tr>
		<?php endwhile; ?>
		<?php else : ?>
		<tr>
			<td><?php echo _('Has not logged in yet'); ?></td>
		</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php

	}

	/**
	 * Only allows guests to view page.
	 *
	 * A logged in user will be shown an error and denied from viewing the page.
	 */
	public function guestOnly() {

		if ( ! isEmpty($_SESSION['jigowatt']['username'])) {
			$this->error = "
							<div class='alert alert-danger'>"._('You\'re already logged in.')."</div>
							<h5>"._('What to do now?')."</h5>
							<p>" . sprintf(_('Go <a href="%s">back</a> to the page you were viewing before this.'), 'javascript:history.go(-1)') . "</p>
							";
		}

		$this->displayMessage($this->error);
	}

	/**
	 * Generates a unique token.
	 *
	 * Intended for form validation to prevent exploit attempts.
	 */
	public function generateToken() {

		if(empty($_SESSION['jigowatt']['token']))
			$_SESSION['jigowatt']['token'] = md5(uniqid(mt_rand(),true));

	}

	/**
	 * Prevents invalid form submission attempts.
	 *
	 * @param     string    $token    The POST token with a form.
	 * @return    bool      Whether the token is valid.
	 */
	public function valid_token($token) {

		if (empty($_SESSION['jigowatt']['token']))
			return false;

		if ($_SESSION['jigowatt']['token'] != $token)
			return false;

		return true;

	}

	/**
	 * Secures any string intended for SQL execution.
	 *
	 * @param     string    $string
	 * @return    string    The secured value string.
	 */
	public function secure($string) {

		// Because some servers still use magic quotes
		if ( get_magic_quotes_gpc() ) :

			if ( ! is_array($string) ) :
				# todo: this was ran on passwords and corrupted them
        # removed the modifications, see how it affects things
				$string = htmlspecialchars(stripslashes(trim($string)));
			else :
				foreach ($string as $key => $value) :
					$string[$key] = htmlspecialchars(stripslashes(trim($value)));
				endforeach;
			endif;

			return $string;

		endif;


		if ( ! is_array($string) ) :
			$string = htmlspecialchars(trim($string));
		else :
			foreach ($string as $key => $value) :
				$string[$key] = htmlspecialchars(trim($value));
			endforeach;
		endif;

		return $string;

	}

	/**
	 * Validates an email address.
	 *
	 * @param     string    $email    The email address.
	 * @return    bool      Whether the email address is valid or not.
	 */
	public function isEmail($email) {

		if ( !empty($email) )
			$email = (string) $email;
		else
			return false;

		return filter_var( $email, FILTER_VALIDATE_EMAIL );
		//return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
	}

	/**
	 * Displays an error and optionally quits the script.
	 *
	 * @param     string    $error    The error message to display.
	 * @param     bool      $exit     Whether to exit after the error and prevent the
	 *                                page from loading any further.
	 */
	public function displayMessage($error, $exit = true) {

		if( !empty($error) ) :

			// The error itself
			echo $error;

			// Shall we exit or not?
			if( $exit ) {
				include_once(cINC . 'footer.php');
				exit();
			}

		endif;

	}

	/**
	 * Ajax validation.
	 *
	 * Used on forms that check for duplicate email, username, or role.
	 */
	public function checkExists() {

		if(!empty($_POST['email']) && !empty($_POST['checkemail'])) {
			$params = array( ':email' => $_POST['email'] );
			$sql = "SELECT `email` FROM `login_users` WHERE `email` = :email";
		}

		else if(!empty($_POST['username']) && !empty($_POST['checkusername'])) {
			$params = array( ':username' => $_POST['username'] );
			$sql = "SELECT `username` FROM `login_users` WHERE `username` = :username";
		}

		else if(!empty($_POST['level']) && !empty($_POST['checklevel'])) {
			$params = array( ':level' => $_POST['level'] );
			$sql = "SELECT `level_name` FROM `login_levels` WHERE `level_name` = :level";
		}

		else return false;

		$stmt = $this->query($sql, $params);
		echo ( $stmt->rowCount() > 0 ) ? "false" : "true";
		exit();

	}

	/**
	 * Finds the current IP address of a visiting user.
	 *
	 * @return    string    The IP address
	 */
	public function getIPAddress() {

		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) :
			$ipAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else :
			$ipAddress = isset($_SERVER["HTTP_CLIENT_IP"]) ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"];
		endif;

		return $ipAddress;
	}



	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @param boole $img True to return a complete IMG tag False for just the URL
	 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
	 * @return String containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	public function get_gravatar( $email, $img = false, $s = 80, $d = 'mm', $r = 'g', $atts = array() ) {
		if ($this->getOption('custom-avatar-enable')) {
			$params = array( ':email' => $email );
			$sql = "SELECT `user_id` FROM `login_users` WHERE `email` = :email";
			$stmt = $this->query($sql, $params);
			if(!$stmt) return false;

			$result = $stmt->fetch(PDO::FETCH_NUM);
			$result = $result ? $result[0] : false;
			if(!$result) return false;

			$uploaddir  = dirname(dirname(__FILE__)) . '/assets/uploads/avatar/';
			$name = md5($result . $email);
			$temp = $name . '.*';
			$files = glob($uploaddir . $temp);
			usort( $files, function( $x, $y ) {
				return filemtime( $x ) < filemtime( $y );
			});
			$path = !empty($files[0]) ? pathinfo($files[0]) : '';
			$url = !empty($files[0]) ? SITE_PATH . 'assets/uploads/avatar/' . $path['basename'] : SITE_PATH . 'assets/img/default_avatar.jpg';

			if ( $img ) {
				$url = '<img style="width:'.$s.'px;" class="gravatar img-thumbnail" src="' . $url . '"';
				foreach ( $atts as $key => $val )
					$url .= ' ' . $key . '="' . $val . '"';
				$url .= ' />';
			}

			return $url;
		}

		$http = ( ! isEmpty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ) ? 'https://' : 'http://';
		$url = $http . 'gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img class="gravatar img-thumbnail" src="' . $url . '"';
			foreach ( $atts as $key => $val )
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}

	public function is_demo(){

		include ( dirname(dirname(__FILE__)) . '/config.php' );

		if(isset($isDemo)) return $isDemo;

		return FALSE;

	}

	/**
	 * $generic->get_user_field_by_name('labelname')
	 *
	 * @param bool $label
	 * @return bool
     */
	public function get_user_field_by_name($label = FALSE)
	{
		if ($label === FALSE) {return FALSE;}

		$params = array( ':label' => $label );
		$sql    = "SELECT * FROM login_profile_fields JOIN login_profiles ON login_profiles.pfield_id = login_profile_fields.id  WHERE login_profile_fields.label = :label;";
		$stmt   = $this->query($sql, $params);

		$user_id =$_SESSION['jigowatt']['user_id'];
		$result = FALSE;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))  {
				if ($row['user_id'] == $user_id) {
					$result = $row;
				}
		}

		if ($result !== FALSE ) {return $result['profile_value'];}
		return $result;
	}
}
