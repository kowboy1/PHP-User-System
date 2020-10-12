<?php

/**
 * User profile edit page.
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

// include_once(dirname(__FILE__) . '/check.class.php');
// include_once(dirname(__FILE__) . '/integration.class.php');
// $check = new Check(false);

class Profile extends Generic {

    private $settings = array();
    private $post;
	  private $error;

    public $is_two_factor_auth_enable;

	public $guest;

	function __construct() {

        $this->post = $_POST;

        $this->is_two_factor_auth_enable = parent::getOption('is-two-factor-auth-enable');
        
        if ( isset( $this->post['phone'] ) && $this->getField( 'use_two_factor_auth' ) == 1 ) {
            $this->validatePhoneNumber();
        }

		/* Prevent guests if the admin hasn't enabled public profiles. */
		if ( !parent::getOption('profile-public-enable') )
			protect('*');

		/* If the admin requires users to update their password. */
		if ( ! empty( $_SESSION['jigowatt']['forcePwUpdate'] ) ) {
			$msg = "<div class='alert alert-warning'>" . _('<strong>Alert</strong>: The administrator has requested all users to update their passwords.') . "</div>";
    }

		// Save the username
		$this->username = !empty($_SESSION['jigowatt']['username']) ? $_SESSION['jigowatt']['username'] : _('Guest');

		$this->use_emails = parent::getOption('email-as-username-enable');
		$this->username_type = ( $this->use_emails ) ? 'email' : 'username';

		/* Check if the user is a guest to this profile. */
		$this->determineGuest();

		// Upload avatar
		if ( ! empty( $_FILES['uploadAvatar'] ) ) {
			$input_file = $_FILES['uploadAvatar']['tmp_name'];
			$image_data = getimagesize( $input_file );
			if ( empty( $image_data ) ) {
				$this->error = sprintf('<div class="alert alert-warning">%s</div>', _('Sorry, that file is not accepted.') );
			} else {
				$mime_type = explode( '/', $image_data['mime'] ); /* not used, not always valid to prevent hacks */
				// remove any 'php' extensions from within filenames to try to prevent hacks
				$new_name = str_replace( '.php', '', $_FILES['uploadAvatar']['name'] );
				$uploaddir  = dirname(dirname(__FILE__)) . '/assets/uploads/avatar/';
				// resize uploads to remove any embedded php within an image to prevent hacks
				// replace original upload with newly created image
				if ( ! smart_resize_image(
					$input_file,							/* input filename with full path */
					file_get_contents( $input_file ),		/* image data as a string */
					$image_data[0]-1,						/* image width */
					$image_data[1]-1,						/* image height */
					false,									/* should the resize be proportional */
					$input_file,							/* output filename with full path (for now, replace the original) */
					false,									/* whether to delete the original file */
					false,									/* use linux commands or PHP to delete the file */
					100										/* resize quality */
				) ) {
					$this->error = sprintf('<div class="alert alert-warning">%s</div>', _('Sorry, that file is not accepted.') );
				} else {
					// rename the final filename to a hashed value with extension already created ( no .php )
					$final_file = $uploaddir . md5( $_SESSION['jigowatt']['user_id'] . $_SESSION['jigowatt']['email'] ) . '.' . pathinfo( $new_name, PATHINFO_EXTENSION );
					// move the resized and exif stripped file to a final location
					if ( move_uploaded_file( $input_file, $final_file ) ) {
						$this->error = sprintf('<div class="alert alert-success">%s</div>', _('Avatar change success!') );
						$_SESSION['jigowatt']['gravatar'] = parent::get_gravatar($_SESSION['jigowatt']['email'], true, 26);
					} else {
						$this->error = sprintf('<div class="alert alert-warning">%s</div>', _('Sorry, that file is not accepted.') );
					}
				}
			}
		}

		if (!$this->guest && !empty($_POST)) {
			$this->retrieveFields();

			foreach ($_POST as $field => $value)
				$this->settings[$field] = parent::secure($value);

			// Validate fields
			$this->validate();

			// Process form
			if(empty($this->error)){
        $this->process();
        if ( isset( $this->post['phone'] ) && $this->getField( 'use_two_factor_auth' ) == 1 ) {
          $this->updateSecurityFields();
        }
      }
		}
    
		$this->retrieveFields();

		if(!$this->guest && isset($_GET['key']) && strlen($_GET['key']) == 32) {
			$this->key = parent::secure($_GET['key']);
// 			$this->updateEmailorPw();
			$this->retrieveFields();
		}
		
		if ( !empty ( $this->error ) || !empty ( $msg ) )
			parent::displayMessage( !empty($this->error) ? $this->error : (!empty($msg) ? $msg : ''), false);

	}

    private function validatePhoneNumber()
    {
        $this->post['phone'] = preg_replace('~[^0-9]+~','',$this->post['phone']);
        if (strlen($this->post['phone']) < 11) {
            $this->error = sprintf('<div class="alert alert-warning">%s</div>', _('Sorry, phone number is invalid.') );
        }
    }

    private function updateSecurityFields()
    {

        if (isset($this->post['use_two_factor_auth']) AND (parent::secure($this->post['use_two_factor_auth']) == 'on')) {
            $use = 1;
        } else {
            $use = 0;
        }
        $params = array (
            ':use_two_factor_auth' =>$use,
            ':phone' => parent::secure($this->post['phone']),
            ':user_id' =>$this->settings['user_id']
        );

        $sql = "UPDATE `login_users` SET `phone` = :phone, `use_two_factor_auth` = :use_two_factor_auth WHERE user_id = :user_id";
        $stmt   = parent::query($sql, $params);

    }

	private function determineGuest() {

		if ( !empty($_SESSION['jigowatt']['user_id']) && empty($_GET['uid']) )
			$this->user_id = $_SESSION['jigowatt']['user_id'];

		else if ( !empty($_GET['uid']) )
			$this->user_id = (int) $_GET['uid'];

		else
			$this->user_id = _('Guest');

		$this->guest = !( !empty($_SESSION['jigowatt']['user_id']) && $_SESSION['jigowatt']['user_id'] == $this->user_id );

	}

	// Retrieve name, email, user_id
	private function retrieveFields() {
    
    global $dbh;
    
    $stmt = $dbh->prepare("
      SELECT *
      FROM `login_users`
      WHERE `user_id` = :user_id
    ");
    
    $stmt->execute([
      ':user_id' => $this->user_id
    ]);
    
		if ( $stmt->rowCount() < 1 ) {
			$this->error = sprintf('<div class="alert alert-warning">%s</div>', _('Sorry, that user does not exist.') );
			parent::displayMessage($this->error, true);
			return false;
		}

		foreach ($stmt->fetch(PDO::FETCH_ASSOC) as $field => $value) :
			$this->settings[$field] = parent::secure($value);
		endforeach;

	}

	// Return a form field
	public function getField($field) {

		if (!empty($this->settings[$field]))
			return $this->settings[$field];

	}

	// Validate form inputs
	private function validate() {

		//If demo, check that user being edited isn't any demo user accounts
		if($this->is_demo()){
			if($this->settings['username'] == 'admin' || $this->settings['username'] == 'special' || $this->settings['username'] == 'user'){
				$this->error = '<div class="alert alert-danger">You cannot edit this user because it is a demo user. Please create a new user to test out this functionality.</div>';
				return false;
			}
		}

		if(empty($this->settings['CurrentPass'])) {
			$this->error = '<div class="alert alert-danger">'._('You must enter the current password to make changes.').'</div>';
			return false;
		}
		
		global $dbh;
		
		$stmt = $dbh->prepare("
      SELECT `password`
      FROM `login_users`
      WHERE {$this->username_type} = :username
    ");
    
    $stmt->execute([
      ':username' => $this->username
    ]);
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ( !validatePassword($this->settings['CurrentPass'], $row['password']) ) {
			$this->error = '<div class="alert alert-danger">'._('You entered the wrong current password.').'</div>';
			return false;
		}

		if (empty($this->settings['name']))
				$this->error .= '<div class="alert alert-danger">'._('You must enter a name.').'</div>';

		if (!parent::isEmail($this->settings['email']))
				$this->error .= '<div class="alert alert-danger">'._('You have entered an invalid e-mail address, try again.').'</div>';

		if (!empty($this->settings['password'])) {

			if ($this->settings['password'] != $this->settings['confirm'])
				$this->error .= '<div class="alert alert-danger">'._('Your passwords did not match.').'</div>';

			if (strlen($this->settings['password']) < 5)
				$this->error = '<div class="alert alert-danger">'._('Your password must be at least 5 characters.').'</div>';

		}
		
		
		// Checkbox handling
		$stmt = $dbh->query("
      SELECT *
      FROM `login_profile_fields`
    ");
    
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
			$name = 'p-' . $row['id'];
			if($row['type'] == 'checkbox')
				$this->settings[$name] = !empty($this->settings[$name]) ? 1 :0;
		endwhile;

	}

	/** @todo: This is extremely ugly, needs refractored. */
// 	private function updateEmailorPw() {
//     
//     global $dbh;
// 		
// 		$stmt = $dbh->prepare("
//       SELECT *
//       FROM `login_confirm`
//       WHERE `key` = :key
//       AND `type` = 'update_emailPw'
//     ");
//     
//     $stmt->execute([
//       ':key' => $this->key
//     ]);
//     
// 		if ($stmt->rowCount() < 1) {
// 			$this->error = "<div class='alert alert-danger'>Incorrect confirmation link</div>";
// 			return false;
// 		}
// 		
// 		$row = $stmt->fetch();
// 		
// 		if ( !empty($row['data']) ) :
// 			$params = array(
// 				':password' => $row['data'],
// 				':email'    => $row['email'],
// 				':username' => $this->username
// 			);
// 			$sql = "UPDATE `login_users` SET `password` = :password, `email` = :email WHERE $this->username_type = :username;";
// 		else :
// 			$params = array(
// 				':email'    => $row['email'],
// 				':username' => $this->username
// 			);
// 			$sql = "UPDATE `login_users` SET `email` = :email WHERE $this->username_type = :username;";
// 		endif;
// 
// 		parent::query($sql, $params);
// 
// 		$params = array( ':key' => $this->key );
// 		parent::query("DELETE FROM `login_confirm` WHERE `key` = :key AND `type` = 'update_emailPw'", $params);
// 
// 		if(!empty($_SESSION['jigowatt']['forcePwUpdate'])) unset($_SESSION['jigowatt']['forcePwUpdate']);
// 
// 		$this->error = "<div class='alert alert-success'>Account details successfully changed.</div>";
// 
// 		$shortcodes = array (
// 			'site_address'  =>  SITE_PATH,
// 			'full_name'     =>  $this->settings['name'],
// 			'username'      =>  $this->username
// 		);
// 
// 		$subj = parent::getOption('email-acct-update-success-subj');
// 		$msg  = parent::getOption('email-acct-update-success-msg');
// 
// 		// Send an email with key
// 		if ( !sendEmail($row['email'], $subj, $msg, $shortcodes) )
// 			$this->error = '<div class="alert alert-danger">'._('ERROR. Mail not sent').'</div>';
// 
// 	}

	private function process() {
    
    if (!empty($this->settings['password'])) {
      $params = array (
        ':name'     => $this->settings['name'],
        ':username' => $this->username,
        ':email'    => $this->settings['email'],
        ':password' => hashPassword($this->settings['password'])
      );
		} else {
      $params = array (
        ':name'     => $this->settings['name'],
        ':username' => $this->username,
        ':email'    => $this->settings['email']
      );
		}
		
		$param = array( ':username' => $this->username );
		$stmt = parent::query("SELECT `email`, `password` FROM `login_users` WHERE $this->username_type = :username;", $param);
		$email = $stmt->fetch();
		$email = $email[0];
		
		if (!empty($this->settings['password'])) {
      parent::query("UPDATE `login_users` SET `name` = :name, `email` = :email, `password` = :password WHERE $this->username_type = :username", $params);
		} else {
      parent::query("UPDATE `login_users` SET `name` = :name, `email` = :email WHERE $this->username_type = :username", $params);
		}
    
		$this->error = "<div class='alert alert-success'>"._('User information updated for')." <b>".$this->settings['name']."</b> ($this->username).</div>";
    
    // ----------
    
    $subj = parent::getOption('email-acct-update-success-subj');
    
    if ($this->settings['email'] != $email && !empty($this->settings['password'])) {
      $msg = 'Your email and password have been updated. Contact us if it wasn\'t you.';
    } elseif ($this->settings['email'] != $email) {
      $msg = 'Your email has been updated. Contact us if it wasn\'t you.';
    } elseif (!empty($this->settings['password'])) {
      $msg = 'Your password has been updated. Contact us if it wasn\'t you.';
    }
    
    if ($this->settings['email'] != $email || !empty($this->settings['password'])) {
      if (!sendEmail($email, $subj, $msg)) {
				$this->error = '<div class="alert alert-danger">'._('ERROR. Mail not sent').'</div>';
			} else {
				$this->error = "<div class='alert alert-success'>" . _('Your account has been updated.') . '</div>';
			}
    }
    
		// Update profile fields
		foreach($this->settings as $field => $value) {
			if(strstr($field,'p-')) {
				$field = str_replace('p-', '', $field);
				parent::updateOption($field, $value, true, $this->settings['user_id']);
			}
		}

	}

}

#$profile = new Profile();


/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/blog/resizing-images-with-php/
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/

class SimpleImage {

   var $image;
   var $image_type;

   public function load( $filename )
   {
      $image_info = getimagesize( $filename );

      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg( $filename );
      } elseif ( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif( $filename );
      } elseif ( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng( $filename );
      }
   }

   public function save( $filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null )
   {
      if ( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif ( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif ( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }
      if ( $permissions != null) {
         chmod($filename,$permissions);
      }
   }

   public function output( $image_type=IMAGETYPE_JPEG )
   {
      if ( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif ( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);
      } elseif ( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }
   }

   public function getWidth()
   {
      return imagesx($this->image);
   }

   public function getHeight()
   {
      return imagesy($this->image);
   }

   public function resizeToHeight( $height )
   {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }

   public function resizeToWidth( $width )
   {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }

   public function scale( $scale )
   {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }

   public function resize( $width,$height )
   {
      $new_image = imagecreatetruecolor( $width, $height );
      imagecopyresampled( $new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight() );
      $this->image = $new_image;
   }

}


/**
 * easy image resize function
 * @param  $file - file name to resize
 * @param  $string - The image data, as a string
 * @param  $width - new image width
 * @param  $height - new image height
 * @param  $proportional - keep image proportional, default is no
 * @param  $output - name of the new file (include path if needed)
 * @param  $delete_original - if true the original image will be deleted
 * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
 * @param  $quality - enter 1-100 (100 is best quality) default is 100
 * @return boolean|resource
 */
function smart_resize_image(
		$file,
		$string             = null,
		$width              = 0,
		$height             = 0,
		$proportional       = false,
		$output             = 'file',
		$delete_original    = true,
		$use_linux_commands = false,
		$quality			= 100
	)
{

	if ( $height <= 0 && $width <= 0 ) return false;
	if ( $file === null && $string === null ) return false;

	# Setting defaults and meta
	$info							= $file !== null ? getimagesize( $file ) : getimagesizefromstring( $string );
	$image							= '';
	$final_width					= 0;
	$final_height					= 0;
	list( $width_old, $height_old )	= $info;
	$cropHeight = $cropWidth		= 0;

	# Calculating proportionality
	if ( $proportional ) {
		if      ( $width  == 0 )  $factor = $height/$height_old;
		elseif  ( $height == 0 )  $factor = $width/$width_old;
		else                    $factor = min( $width/$width_old, $height/$height_old );

		$final_width  = round( $width_old * $factor );
		$final_height = round( $height_old * $factor );
	}
	else {
		$final_width = ( $width <= 0 ) ? $width_old : $width;
		$final_height = ( $height <= 0 ) ? $height_old : $height;
		$widthX = $width_old / $width;
		$heightX = $height_old / $height;

		$x = min( $widthX, $heightX );
		$cropWidth = ( $width_old - $width * $x ) / 2;
		$cropHeight = ( $height_old - $height * $x ) / 2;
	}

	# Loading image to memory according to type
	switch ( $info[2] ) {
		case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
		case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
		case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
		default: return false;
	}


	# This is the resizing/resampling/transparency-preserving magic
	$image_resized = imagecreatetruecolor( $final_width, $final_height );
	if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
		$transparency = imagecolortransparent( $image );
		$palletsize = imagecolorstotal( $image );

		if ( $transparency >= 0 && $transparency < $palletsize ) {
			$transparent_color  = imagecolorsforindex( $image, $transparency );
			$transparency       = imagecolorallocate( $image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue'] );
			imagefill( $image_resized, 0, 0, $transparency );
			imagecolortransparent( $image_resized, $transparency );
		}
		elseif ( $info[2] == IMAGETYPE_PNG ) {
			imagealphablending( $image_resized, false );
			$color = imagecolorallocatealpha( $image_resized, 0, 0, 0, 127 );
			imagefill( $image_resized, 0, 0, $color );
			imagesavealpha( $image_resized, true );
		}
	}
	imagecopyresampled( $image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight );


	# Taking care of original, if needed
	if ( $delete_original ) {
		if ( $use_linux_commands ) exec( 'rm '.$file );
		else @unlink( $file );
	}

	# Preparing a method of providing result
	switch ( strtolower( $output ) ) {
		case 'browser':
			$mime = image_type_to_mime_type( $info[2] );
			header("Content-type: $mime");
			$output = NULL;
			break;
		case 'file':
			$output = $file;
			break;
		case 'return':
			return $image_resized;
			break;
		default:
			break;
	}

	# Writing image according to type to the output destination and image quality
	switch ( $info[2] ) {
		case IMAGETYPE_GIF:   imagegif( $image_resized, $output );    break;
		case IMAGETYPE_JPEG:  imagejpeg( $image_resized, $output, $quality );   break;
		case IMAGETYPE_PNG:
			$quality = 9 - (int)((0.9*$quality)/10.0);
			imagepng( $image_resized, $output, $quality );
			break;
		default: return false;
	}

	return true;
}
