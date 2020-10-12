<?php

/**
 * Modify this script's options.
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

// include_once(dirname(dirname(dirname(__FILE__))) . '/classes/generic.class.php');

use PHPMailer\PHPMailer\SMTP;
include_once dirname(dirname(dirname(__FILE__))) . '/external-libraries/phpmailer/src/SMTP.php';

class Settings extends Generic {

	private $error;
	private $options = array();

	function __construct() {

		// Once the form has been processed
		if(!empty($_POST)) {

			foreach ($_POST as $key => $value)
				$this->options[$key] = parent::secure($value);
      
			// Validate fields
			$this->validate();
      
			// Process form
			echo empty($this->error) ? $this->process() : $this->error;

			exit();

		}

	}

	public function get_domains($json = false, $array = false) {

		$option = parent::getOption('restrict-signups-by-email');

		if ( !$option ) return '';
		$option = unserialize($option);
		if ($array === true) {
			return $option;
		}
		$options = '';
		foreach ( $option as $value ) :

			$options .= $json ? "'$value', " : "$value, ";;

		endforeach;

		$options = rtrim($options, ', ');

		echo $options;

	}
  
	// Validate the submitted information
	private function validate() {

		if(!is_numeric($this->options['default_session']))
			$this->error = _('You must enter a default session (numeric value only).');

		if(!parent::isEmail($this->options['admin_email']))
			$this->error = _('You have entered an invalid e-mail address, try again.');

		if(empty($this->options['site_address']))
			$this->error = _('Please enter your site address.');

		if(substr($this->options['site_address'], -1) != '/')
			$this->options['site_address'] = $this->options['site_address'] . '/';
    
    // smtp validation
    
    //Create a new SMTP instance
    $smtp = new SMTP;
    
    //Enable connection-level debug output
//     $smtp->do_debug = SMTP::DEBUG_CONNECTION;
    
    if (   !isEmpty($this->options['smtp-host-name'])
        || !isEmpty($this->options['smtp-port-number'])
        || !isEmpty($this->options['smtp-username'])
        || !isEmpty($this->options['smtp-password'])
     ) {
      
      try {
          //Connect to an SMTP server
          if (!$smtp->connect($this->options['smtp-host-name'], $this->options['smtp-port-number'])) {
              throw new Exception('Connect failed');
          }
          //Say hello
          if (!$smtp->hello(gethostname())) {
              throw new Exception('EHLO failed: ' . $smtp->getError()['error']);
          }
          //Get the list of ESMTP services the server offers
          $e = $smtp->getServerExtList();
          //If server can do TLS encryption, use it
          if (is_array($e) && array_key_exists('STARTTLS', $e)) {
              $tlsok = $smtp->startTLS();
              if (!$tlsok) {
                  throw new Exception('Failed to start encryption: ' . $smtp->getError()['error']);
              }
              //Repeat EHLO after STARTTLS
              if (!$smtp->hello(gethostname())) {
                  throw new Exception('EHLO (2) failed: ' . $smtp->getError()['error']);
              }
              //Get new capabilities list, which will usually now include AUTH if it didn't before
              $e = $smtp->getServerExtList();
          }
          //If server supports authentication, do it (even if no encryption)
          if (is_array($e) && array_key_exists('AUTH', $e)) {
              if ($smtp->authenticate($this->options['smtp-username'], $this->options['smtp-password'])) {
                  echo '';
              } else {
                  throw new Exception('Authentication failed: ' . $smtp->getError()['error']);
              }
          }
      } catch (Exception $e) {
          $this->error = _('SMTP error: ' . $e->getMessage());
      }
    }
    
		if(!empty($this->error)) $this->error = '<div class="alert alert-danger fade in"><a class="close" data-dismiss="alert" href="#">&times;</a>' . $this->error . '</div>';
    
    // ----------
    
		$checkboxes = array();
		if(!empty($_POST['denied-form'])) {
			$checkboxes[] = 'block-msg-enable';
			$checkboxes[] = 'block-msg-out-enable';
		}
		if(!empty($_POST['general-options-form'])) {
			$checkboxes[] = 'user-activation-enable';
			$checkboxes[] = 'notify-new-user-enable';
			$checkboxes[] = 'custom-avatar-enable';
			$checkboxes[] = 'disable-registrations-enable';
			$checkboxes[] = 'disable-logins-enable';
			$checkboxes[] = 'email-as-username-enable';
			$checkboxes[] = 'pw-encrypt-force-enable';
			$checkboxes[] = 'signin-redirect-referrer-enable';
			$checkboxes[] = 'signout-redirect-referrer-enable';
			$checkboxes[] = 'email-welcome-disable';
		}
		if(!empty($_POST['integration-form'])) {
			$checkboxes[] = 'integration-facebook-enable';
			$checkboxes[] = 'integration-google-enable';
			$checkboxes[] = 'integration-twitter-enable';
			$checkboxes[] = 'integration-yahoo-enable';
		}
		if(!empty($_POST['update-form'])) {
			$checkboxes[] = 'update-check-enable';
		}
		if(!empty($_POST['user-profiles-form'])) {
			$checkboxes[] = 'profile-display-email-enable';
			$checkboxes[] = 'profile-display-name-enable';
			$checkboxes[] = 'profile-public-enable';
			$checkboxes[] = 'is-two-factor-auth-enable';
			$checkboxes[] = 'profile-timestamps-admin-enable';
			$checkboxes[] = 'profile-timestamps-enable';
		}
		
    $checkboxes[] = 'smtp-active';
		

		foreach($checkboxes as $label)
			$this->options[$label] = !empty($this->options[$label]) ? 1 : 0;

		$this->options['default-level'] = !empty($this->options['default-level']) ? serialize($this->options['default-level']) : serialize(array('3'));
		$this->options['restrict-signups-by-email'] = !empty($this->options['restrict-signups-by-email']) ? serialize($this->options['restrict-signups-by-email']) : '';

	}

	/** Insert setting values into the database */
	private function process() {
    
		if(!empty($this->error))
			return false;

		/** Saves the profile fields, first checks if it exists */
		if (!empty($this->options['profile-field_section'])) :
			foreach($this->options['profile-field_section'] as $key => $value) :

				if(empty($value)) continue;

				/** Deletes a profile field if Delete is checked */
				if( isset($this->options['profile-field_delete'][$key]) ) :
					$params = array(
						':section' => $value,
						':type'    => $this->options['profile-field_type'][$key],
						':label'   => $this->options['profile-field_name'][$key]
					);
					$sql = "DELETE FROM `login_profile_fields` WHERE `section` = :section AND `type` = :type AND `label` = :label;";
					parent::query($sql, $params);
					continue;
				endif;

				/** Adds profile fields */
				$params = array( ':id' => $key );
				$stmt = parent::query("SELECT `id` FROM `login_profile_fields` WHERE `id` = :id;", $params);

				$params = array(
					':section' => $value,
					':type'    => $this->options['profile-field_type'][$key],
					':label'   => $this->options['profile-field_name'][$key],
					':public'  => !empty($this->options['profile-field_public'][$key]) ? 1 : 0,
					':signup'  => $this->options['profile-field_signup'][$key],
					':id'      => $key
				);

				if ( $stmt->rowCount() < 1 )
					parent::query("INSERT INTO `login_profile_fields` (`id`, `section`, `type`, `label`, `public`, `signup`) VALUES (:id, :section, :type, :label, :public, :signup);", $params);
				else
					parent::query("UPDATE `login_profile_fields` SET `section` = :section, `type` = :type, `label` = :label, `public` = :public, `signup` = :signup WHERE `id` = :id", $params);

			endforeach;
		endif;
    
    // smtp username and password encryption
    
    $username = $this->options['smtp-username'];
    $password = $this->options['smtp-password'];
    
    $encryptedUsername = openssl_encrypt($username, 'aes-256-cbc', base64_decode($this->options['encryption_key']), 0, base64_decode($this->options['iv']));
    $encryptedPassword = openssl_encrypt($password, 'aes-256-cbc', base64_decode($this->options['encryption_key']), 0, base64_decode($this->options['iv']));
    
    unset($this->options['encryption_key']);
    unset($this->options['iv']);
    
    $this->options['smtp-username'] = $encryptedUsername;
    $this->options['smtp-password'] = $encryptedPassword;
    
		/** Save every other field */
		foreach ( $this->options as $option => $newvalue ) {
			if ( ! is_array($option) ) {
				parent::updateOption( $option, $newvalue );
			}
		}
    
		return "<div class='alert alert-success fade in'><a class='close' data-dismiss='alert' href='#'>&times;</a>"._('Settings updated.' . $this->options['encryption_key'] . '' . $iv)."</div>";

	}

	public function profile_fields() {

		$field_types = array(
			'text_input'=> _('Text Input'),
			'textarea'  => _('Textarea'),
			'checkbox'  => _('Checkbox'),
		);

		$signup_types = array(
			'hide'     => _('Hide'),
			'require'  => _('Require'),
			'optional' => _('Optional'),
		);

		$sql = "SELECT * FROM `login_profile_fields`";
		$stmt = parent::query($sql);

		$i = 1;

		?><tbody><?php
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :

			?><tr class="profile-field-row">
				<td><input type="text" value="<?php echo $row['section']; ?>" name="profile-field_section[<?php echo $i; ?>]" placeholder="<?php echo _('Section name'); ?>" class="form-control input-medium"></td>
				<td>
					<select name="profile-field_type[<?php echo $i; ?>]" class="form-control input-medium">
					<?php foreach ($field_types as $field_type => $field_label) :
					$selected = (strstr($row['type'], $field_type)) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $field_type; ?>" <?php echo $selected; ?>><?php echo $field_label; ?></option>
					<?php endforeach;?>
					</select>
				</td>
				<td><input type="text" value="<?php echo $row['label']; ?>" name="profile-field_name[<?php echo $i; ?>]" placeholder="<?php echo _('Field name'); ?>" class="form-control input-medium"></td>
				<td>
					<select name="profile-field_signup[<?php echo $i; ?>]" class="form-control input-medium">
					<?php foreach ($signup_types as $field_type => $field_label) :
					$selected = (strstr($row['signup'], $field_type)) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $field_type; ?>" <?php echo $selected; ?>><?php echo $field_label; ?></option>
					<?php endforeach;?>
					</select>
				</td>
				<td><input type="checkbox" name="profile-field_public[<?php echo $i; ?>]" <?php if ( !empty($row['public']) ) echo 'checked="checked"'; ?>></td>
				<td><input type="checkbox" name="profile-field_delete[<?php echo $i; ?>]"></td>
			</tr><?php
			$i++;
		endwhile;

		?>
		<tr><td colspan="5"><button class="add-field btn btn-default"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo _('Add field'); ?></button></td></tr>
		</tbody>
		<?php

	}

	/**
	 * Checks for updates.
	 *
	 * Used in admin settings page.
	 */
	private function grabUpdate() {

// 		if( !ini_get('allow_url_fopen') || !parent::getOption('update-check-enable') )
// 			return false;
//     
//     return parent::getOption('site_address');
    
//     $url = parent::getOption('site_address');
//     $urlArray = explode('/', $url);
//     array_pop($urlArray);
//     array_pop($urlArray);
//     $versionFileUrl = implode('/', $urlArray);
//     $versionFileUrl .= '/php-login-version.txt';
    
    $versionFileUrl = 'http://phplogin.approveyourweb.site/php-login-version.txt';
    
    if ( !$t = file_get_contents($versionFileUrl) )
			return false;
    
		$t = explode(';',$t);
    
		return $t;

	}

	public function newUpdate() {

		$t = $this->grabUpdate();
		return ($t[0] > phplogin_version);

	}

	public function newVersion() {

		$version = $this->grabUpdate();
		
		return $version[0];

	}

	public function newChangelog() {

		$changelog = $this->grabUpdate();
		return $changelog[1];

	}

	/* @TODO: This function is repeated once in edit_user.class.php. Obliterate that repeat. */
	public function returnLevels($id = 'default-level') {

		$option = parent::getOption($id);

		$ids = !empty( $option ) ? unserialize($option) : array('');
		$placeholder = array_fill(0, count($ids), '?');

		$sql   = "SELECT level_name, id FROM login_levels WHERE level_disabled != 1 AND id NOT IN (" . implode(',', $placeholder) . ")";
		$stmt2 = parent::query($sql, $ids);

		$sql = "SELECT level_name, id FROM login_levels WHERE id IN (" . implode(',', $placeholder) . ")";
		$stmt3 = parent::query($sql, $ids);

		?>
		<select class="form-control chzn-select" data-placeholder="<?php echo _('Select your roles'); ?>" multiple="multiple" id="<?php echo $id; ?>" name="<?php echo $id; ?>[]">
			<?php while($level = $stmt3->fetch()) : ?>
			<?php echo $level['id'];  ?>
			<option selected="selected" value="<?php echo $level['id']; ?>"><?php echo $level['level_name']; ?></option>
			<?php endwhile; ?>
			<?php while($level = $stmt2->fetch()) : ?>
			<option value="<?php echo $level['id']; ?>"><?php echo $level['level_name']; ?></option>
			<?php endwhile; ?>
		</select>
		<?php

	}

}
