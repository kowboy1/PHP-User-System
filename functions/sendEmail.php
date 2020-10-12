<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require dirname(dirname(__FILE__)) . '/classes/integration/hybridauth/src/autoload.php';

include_once dirname(dirname(__FILE__)) . '/external-libraries/phpmailer/src/PHPMailer.php';
include_once dirname(dirname(__FILE__)) . '/external-libraries/phpmailer/src/Exception.php';
include_once dirname(dirname(__FILE__)) . '/external-libraries/phpmailer/src/SMTP.php';

include_once dirname(dirname(__FILE__)) . '/config.php';

function sendEmail($to, $subj, $msg, $shortcodes = '', $bcc = false) {
  
  if ( !empty($shortcodes) && is_array($shortcodes) ) {
    
    foreach ($shortcodes as $code => $value) {
      $msg = str_replace('{{'.$code.'}}', $value, $msg);
    }
    
  }
  
  $mail = new PHPMailer(TRUE);
  
  $generic = new Generic();
  
  if ($generic->getOption('smtp-active') === '1') {
    
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    
    //Set the hostname of the mail server
    $mail->Host = $generic->getOption('smtp-host-name');
    
    //Set the SMTP port number - likely to be 25, 465 or 587
    $mail->Port = $generic->getOption('smtp-port-number');
    
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    
    $encryptedUsername = $generic->getOption('smtp-username');
    $encryptedPassword = $generic->getOption('smtp-password');
    
    global $encryption_key;
    global $iv;
    
    $decryptedUsername = openssl_decrypt($encryptedUsername, 'aes-256-cbc', base64_decode($encryption_key), 0, base64_decode($iv));
    $decryptedPassword = openssl_decrypt($encryptedPassword, 'aes-256-cbc', base64_decode($encryption_key), 0, base64_decode($iv));
    
    //Username to use for SMTP authentication
    $mail->Username = $decryptedUsername;
    
    //Password to use for SMTP authentication
    $mail->Password = $decryptedPassword;
    
  }
  
  $mail->CharSet = "UTF-8";
  
  if ($generic->getOption('smtp-active') === '1') {
    $mail->setFrom($mail->Username); // SMTP settings
  } else {
    $mail->setFrom(address); // admin email
  }
  
  /* Multiple recepients? */
  if ( is_array( $to ) ) {
    foreach ($to AS $recepient) {
      if ($bcc) {
        $mail->AddBCC($recepient);
      } else {
        $mail->addAddress($recepient);
      }
    }
  } else {
    $mail->addAddress($to);
  }
  
  $mail->Subject = $subj;
  
  $mail->isHTML(TRUE);
  
  $mail->Body = nl2br(html_entity_decode($msg));
  
  return $mail->send();
  
}

?>
