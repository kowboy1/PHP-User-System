<?php

/**
 * Installs the PHP Login & User Management database
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

#################################################################################################### --- INCLUDE INIT & HEADER

require_once '../initialise.php';
require_once '../header.php';

#################################################################################################### --- PROCESS FORM SUBMISSION

if($_POST['action'] === 'submitInstall'){
  
  $installErrors = '';
  
#################################################################################################### --- INPUT VALIDATION
  
  // database credentials are validated only if we don't already have a config-based $dbh
  
  if (isEmpty($dbh)) {
    
    // to-do: add support for IPs too
    
    #if(isEmpty(filter_input(INPUT_POST, 'dbHost', FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) === true){ // PHP >= 7.0
    if(isEmpty(filter_input(INPUT_POST, 'dbHost', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['hostname'])))) === true){ // PHP < 7.0
      $installErrors .= '<li>' . _('You must supply a valid hostname!') . '</li>';
    }
    
    // ----------
    
    if(isEmpty((string)$_POST['dbName']) === true){
      $installErrors .= '<li>' . _('You must supply a database name!') . '</li>';
    }
    
    // ----------
    
    if(isEmpty((string)$_POST['dbUser']) === true){
      $installErrors .= '<li>' . _('You must supply a database user!') . '</li>';
    }
    
    // ----------
    
    // dbPass can be empty
    
    // ----------
    
    // try connecting to database with the supplied credentials
    // if we connect successfully, we must save the credentials in config.php, so it has to be writeable
    
    if($installErrors === ''){
      
      // only db credentials are validated above this, so any errors would be related to them
      // no errors so far, verify connection with submitted credentials
      
      $dbh = dbConnect($_POST['dbHost'], $_POST['dbName'], $_POST['dbUser'], $_POST['dbPass']); // false if can't connect
      
      if(isEmpty($dbh)){
        $installErrors .= '<li>' . _('Could not connect to the database! The database credentials you supplied are incorrect!') . '</li>';
      }else{
        
#################################################################################################### --- SAVE DATABASE CREDENTIALS TO CONFIG FILE
        
        // credentials are correct, now we have to make sure we can save them to config.php
        if(is_writable(CONFIG_FILE_PATH)){
          
          $fp = fopen(CONFIG_FILE_PATH, "w");
          
          // encryption key and initialization vector
          $encryptionKey = openssl_random_pseudo_bytes(32);
          $initializationVector = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
          
          // print each line on its own, to avoid inserting empty spaces in the config file
          
          fwrite($fp,
            '<?php' . "\n\n" .
            
            '############################################################' . "\n" .
            '## Important! These must be filled in correctly.' . "\n" .
            '## Database credentials are required to use this script.' . "\n" .
            '############################################################' . "\n\n" .
            
            '$host   = "' . $_POST['dbHost'] . '";   // if you don\'t know what your host is, it\'s safe to leave it to "localhost"' . "\n" .
            '$dbName = "' . $_POST['dbName'] . '";   // database name' . "\n" .
            '$dbUser = "' . $_POST['dbUser'] . '";   // database user' . "\n" .
            '$dbPass = "' . $_POST['dbPass'] . '";   // database user password' .  "\n\n" .
            
            '// These are used for SMTP credentials encryption' . "\n" .
            '// They are created automatically on installation' . "\n" .
            '$encryption_key = "' . base64_encode($encryptionKey) . '";' . "\n" .
            '$iv = "' . base64_encode($initializationVector) . '"; // initialisation vector' . "\n\n" .
            '?>'
          );
          
          fclose($fp);
          
        }else{
          $installErrors .= '<li>' . _('Installation is not complete! The config.php file needs to be writeable by the web server!') . '</li>';
        }
      }
    }
  }
  
  // ----------
  
  if(isEmpty(filter_input(INPUT_POST, 'scriptPath', FILTER_VALIDATE_URL)) === true){
    $installErrors .= '<li>' . _('You must supply a valid site address!') . '</li>';
  }
  
  // ----------
  
  if(isEmpty(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) === true){
    $installErrors .= '<li>' . _('You must supply a valid admin email!') . '</li>';
  }
  
  // ----------
  
  if(isEmpty(filter_input(INPUT_POST, 'adminUser', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['username'])))) === true){
    $installErrors .= '<li>' . _('You must supply a valid admin username!') . '</li>';
  }
  
  if((int)strlen($_POST['adminPass']) < MIN_PASS_LENGTH){
    $installErrors .= '<li>' . _('The admin password must be at least ' . MIN_PASS_LENGTH . ' characters long.') . '</li>';
  }
  
#################################################################################################### --- PROCESS INSTALLATION
  
  if($installErrors !== ''){
    echo '
      <div class="alert alert-danger">
        <h4 class="alert-heading">' . _('Attention!') . '</h4>' .
        $installErrors . '
      </div>
    ';
  }else{
    
    // everything is OK, proceed with installation
    
#################################################################################################### --- CLEAR EXISTING INSTALLATION
    
    // this could be commented if we don't want to clear the existing install
    
    #/*
    $pdoDB = $dbh->query('SELECT DATABASE()')->fetchColumn();
    
    if((!isEmpty($dbName) && $dbName === $pdoDB) || (!isEmpty($_POST['dbName']) && $_POST['dbName'] === $pdoDB)){
      $dbh->query("DROP TABLE IF EXISTS login_integration");
      $dbh->query("DROP TABLE IF EXISTS login_levels");
      $dbh->query("DROP TABLE IF EXISTS login_profiles");
      $dbh->query("DROP TABLE IF EXISTS login_settings");
      $dbh->query("DROP TABLE IF EXISTS login_users");
      $dbh->query("DROP TABLE IF EXISTS login_profile_fields");
      $dbh->query("DROP TABLE IF EXISTS login_timestamps");
    }
    #*/
    
#################################################################################################### --- CREATE TABLES
    
    $dbh->query("
      CREATE TABLE IF NOT EXISTS `login_integration` (
        
        `user_id`         INT(10) UNSIGNED NOT NULL,
        `facebook`        VARCHAR(255) NOT NULL,
        `twitter`         VARCHAR(255) NOT NULL,
        `google`          VARCHAR(255) NOT NULL,
        `yahoo`           VARCHAR(255) NOT NULL,
        `timestamp`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        PRIMARY KEY (`user_id`)
      ) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    ");
    
    // ----------
    
    $dbh->query("
      CREATE TABLE IF NOT EXISTS `login_levels` (
        
        `id`              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `level_name`      VARCHAR(255) NOT NULL,
        `level_disabled`  TINYINT(1) UNSIGNED NOT NULL,
        `redirect`        VARCHAR(255) DEFAULT NULL,
        `welcome_email`   TINYINT(1) UNSIGNED NOT NULL,
        
        PRIMARY KEY (`id`)
      ) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    ");
    
    // ----------
    
    $dbh->query("
      CREATE TABLE IF NOT EXISTS `login_profiles` (
        
        `p_id`            INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `pfield_id`       INT(10) UNSIGNED NOT NULL,
        `user_id`         INT(10) UNSIGNED NOT NULL,
        `profile_value`   TEXT DEFAULT NULL,
        
        PRIMARY KEY (`p_id`)
      ) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    ");
    
    // ----------
    
    $dbh->query("
      CREATE TABLE IF NOT EXISTS `login_settings` (
        
        `id`              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `option_name`     VARCHAR(255) NOT NULL,
        `option_value`    TEXT NOT NULL,
        
        PRIMARY KEY (`id`),
        UNIQUE KEY (`option_name`)
      ) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    ");
    
    // ----------
    
    // bcrypt hashes are 60 chars long
    $dbh->query("
      CREATE TABLE IF NOT EXISTS `login_users` (
        
        `user_id`             INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_level`          TEXT NOT NULL,
        `restricted`          TINYINT(1) NOT NULL,
        `username`            VARCHAR(255) NOT NULL,
        `name`                VARCHAR(255) NOT NULL,
        `email`               VARCHAR(255) NOT NULL,
        `password`            CHAR(60) NOT NULL,
        `timestamp`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `social_network_id`   VARCHAR(100) DEFAULT NULL,
        `phone`               VARCHAR(12) DEFAULT NULL,
        `tmp_auth_token`      INT(8) DEFAULT NULL,
        `use_two_factor_auth` VARCHAR(2) DEFAULT NULL,
        `sms_time`            INT(11) DEFAULT NULL,
        
        PRIMARY KEY (`user_id`),
        UNIQUE KEY (`username`)
      ) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    ");
    
    // ----------
    
    $dbh->query("
      CREATE TABLE IF NOT EXISTS `login_profile_fields` (
        
        `id`              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `section`         VARCHAR(255) NOT NULL,
        `type`            VARCHAR(25) NOT NULL,
        `label`           VARCHAR(255) NOT NULL,
        `public`          TINYINT(4) NOT NULL,
        `signup`          VARCHAR(255) NOT NULL,
        
        PRIMARY KEY (`id`)
      ) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    ");
    
    // ----------
    
    $dbh->query("
      CREATE TABLE IF NOT EXISTS `login_timestamps` (
        
        `id`              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id`         INT(10) UNSIGNED NOT NULL,
        `ip`              VARCHAR(255) NOT NULL,
        `timestamp`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        PRIMARY KEY (`id`)
      ) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    ");
    
#################################################################################################### --- POPULATE TABLES
    
    // can remove explicit declaration of ID if we truncate/drop the table before installing
    $dbh->query("
      INSERT IGNORE INTO `login_levels` (`id`, `level_name`)
      VALUES
        (1, 'Admin'),
        (2, 'Special'),
        (3, 'User'),
        (4, 'Unconfirmed')
    ");
    
    // ----------
    
    $stmt = $dbh->prepare("
      INSERT IGNORE INTO `login_settings` (`id`, `option_name`, `option_value`)
      VALUES
        (1, 'site_address', :site_address),
        (2, 'default_session', '0'),
        (3, 'admin_email', :admin_email),
        (4, 'block-msg-enable', '1'),
        (5, 'block-msg', '<h1>Sorry.</h1>\r\n\r\n<p>We have detected that your user role does not entitle you to view the page requested.</p>\r\n\r\n<p>Please contact the website administrator if you feel this is in error.</p>\r\n\r\n<h5>What to do now?</h5>\r\n<p>To see this page you must <a href=''logout.php''>logout</a> and login with sufficient privileges.</p>'),
        (6, 'block-msg-out', 'You need to login to do that.'),
        (7, 'block-msg-out-enable', '1'),
        (8, 'email-welcome-msg', 'Hello {{full_name}} !\r\n\r\nThanks for registering at {{site_address}}. Here are your account details:\r\n\r\nName: {{full_name}}\r\nUsername: {{username}}\r\nEmail: {{email}}\r\nPassword: *hidden*\r\n\r\nYou will first have to activate your account by clicking on the following link:\r\n\r\n{{activate}}'),
        (9, 'email-activate-msg', 'Hi there {{full_name}} !\r\n\r\nYour account at {{site_address}} has been successfully activated :). \r\n\r\nFor your reference, your username is <strong>{{username}}</strong>. \r\n\r\nSee you soon!'),
        (10, 'email-activate-subj', 'You''ve activated your account !'),
        (11, 'email-activate-resend-subj', 'Here''s your activation link again for Jigowatt'),
        (12, 'email-activate-resend-msg', 'Why hello, {{full_name}}. \r\n\r\nI believe you requested this:\r\n{{activate}}\r\n\r\nClick the link above to activate your account :)'),
        (13, 'email-welcome-subj', 'Thanks for signing up :)'),
        (14, 'email-forgot-success-subj', 'Your password has been reset'),
        (15, 'email-forgot-success-msg', 'Welcome back, {{full_name}} !\r\n\r\nI''m just letting you know your password at {{site_address}} has been successfully changed. \r\n\r\nHopefully you were the one that requested this password reset !\r\n\r\nCheers'),
        (16, 'email-forgot-subj', 'Lost your password at Jigowatt?'),
        (17, 'email-forgot-msg', 'Hi {{full_name}},\r\n\r\nYour username is <strong>{{username}}</strong>.\r\n\r\nTo reset your password at Jigowatt, please click the following password reset link:\r\n{{reset}}\r\n\r\nSee you soon!'),
        (18, 'email-add-user-subj', 'You''re registered !'),
        (19, 'email-add-user-msg', 'Hello {{full_name}} !\r\n\r\nYou''re now registered at {{site_address}}. Here are your account details:\r\n\r\nName: {{full_name}}\r\nUsername: {{username}}\r\nEmail: {{email}}\r\nPassword: {{password}}'),
        (20, 'pw-encrypt-force-enable', '0'),
        (21, 'pw-encryption', 'BCRYPT'),
        (22, 'phplogin_db_version', '1591920000'),
        (23, 'phplogin_version', '5.0.0'),
        (24, 'email-acct-update-subj', 'Confirm your account changes'),
        (25, 'email-acct-update-msg', 'Hi {{full_name}} !\r\n\r\nYou ( {{username}} ) requested a change to update your password or email. Click the link below to confirm this change.\r\n\r\n{{confirm}}\r\n\r\nThanks!\r\n{{site_address}}'),
        (26, 'email-acct-update-success-subj', 'Your account has been updated'),
        (27, 'email-acct-update-success-msg', 'Hello {{full_name}},\r\n\r\nYour account details at {{site_address}} has been updated. \r\n\r\nYour username: {{username}}\r\n\r\nSee you around!'),
        (28, 'guest-redirect', :guest_redirect),
        (29, 'signout-redirect-referrer-enable', 1),
        (30, 'signin-redirect-referrer-enable', 1),
        (31, 'default-level', 'a:1:{i:0;s:1:\"4\";}'),
        (32, 'new-user-redirect', :new_user_redirect),
        (33, 'user-activation-enable', '1'),
        (34, 'email-new-user-subj', 'A new user has registered !'),
        (35, 'email-new-user-msg', 'Hello,\r\n\r\nThere''s been a new registration at &lt;a href=&quot;{{site_address}}&quot;&gt;your site&lt;/a&gt;.\r\n\r\nHere''s the user''s details:\r\n\r\nName: {{full_name}}\r\nUsername: {{username}}\r\nEmail: {{email}}'),
        (36, 'default-level-admin', 'a:1:{i:0;s:1:\"3\";}'),
        (37, 'signout-redirect-url', ''),
        (38, 'signin-redirect-url', ''),
        (39, 'general-options-form', '1'),
        (40, 'notify-new-user-enable', '0'),
        (41, 'custom-avatar-enable', '0'),
        (42, 'disable-registrations-enable', '0'),
        (43, 'disable-logins-enable', '0'),
        (44, 'email-as-username-enable', '0'),
        (45, 'email-welcome-disable', '0'),
        (46, 'restrict-signups-by-email', ''),
        (47, 'denied-form', '1'),
        (48, 'two_factor_auth_number', ''),
        (49, 'two_factor_auth_sid', ''),
        (50, 'two_factor_auth_token', ''),
        (51, 'sms_life_time', ''),
        (52, 'profile-field_section', 'a:1:{i:1;s:0:\"\";}'),
        (53, 'profile-field_type', 'a:1:{i:1;s:10:\"text_input\";}'),
        (54, 'profile-field_name', 'a:1:{i:1;s:0:\"\";}'),
        (55, 'profile-field_signup', 'a:1:{i:1;s:4:\"hide\";}'),
        (56, 'user-profiles-form', '1'),
        (57, 'profile-display-email-enable', '0'),
        (58, 'profile-display-name-enable', '0'),
        (59, 'profile-public-enable', '0'),
        (60, 'is-two-factor-auth-enable', '0'),
        (61, 'profile-timestamps-admin-enable', '0'),
        (62, 'profile-timestamps-enable', '0'),
        (63, 'twitter-key', ''),
        (64, 'twitter-secret', ''),
        (65, 'integration-facebook-enable', '0'),
        (66, 'facebook-app-id', ''),
        (67, 'facebook-app-secret', ''),
        (68, 'google-id', ''),
        (69, 'google-secret', ''),
        (70, 'reCAPTCHA-public-key', ''),
        (71, 'reCAPTCHA-private-key', ''),
        (72, 'playThru-publisher-key', ''),
        (73, 'playThru-scoring-key', ''),
        (74, 'integration-form', '1'),
        (75, 'integration-google-enable', '0'),
        (76, 'integration-twitter-enable', '0'),
        (77, 'integration-yahoo-enable', '0'),
        (78, 'update-form', '1'),
        (79, 'update-check-enable', '0'),
        (80, 'smtp-active', '0'),
        (81, 'smtp-host-name', ''),
        (82, 'smtp-port-number', ''),
        (83, 'smtp-username', ''),
        (84, 'smtp-password', '');
    ");
    
    $stmt->execute([
      ':site_address'      => $_POST['scriptPath'],
      ':admin_email'       => $_POST['email'],
      ':guest_redirect'    => $_POST['scriptPath'] . 'login.php?e=1',
      ':new_user_redirect' => $_POST['scriptPath'] . 'profile.php'
    ]);
    
    // ----------
    
    $stmt = $dbh->prepare("
      INSERT IGNORE INTO `login_users` (`user_id`, `user_level`, `restricted`, `username`, `name`, `email`, `password`)
      VALUES
        (1, 'a:3:{i:0;s:1:\"3\";i:1;s:1:\"1\";i:2;s:1:\"2\";}', 0, :admin_user, 'Admin',        :admin_email,                   :admin_pass),
        
        (2, 'a:2:{i:0;s:1:\"2\";i:1;s:1:\"3\";}',               0, 'special',   'Demo Special', 'test.special@jigowatt.co.uk',  :demo_special_pass),
        (3, 'a:1:{i:0;s:1:\"3\";}',                             0, 'user',      'Demo User',    'test.user@jigowatt.co.uk',     :demo_user_pass)
    ");
    
    $stmt->execute([
      ':admin_user'         => $_POST['adminUser'],
      ':admin_email'        => $_POST['email'],
      ':admin_pass'         => hashPassword($_POST['adminPass']),
      
      ':demo_special_pass'  => hashPassword('special'),
      ':demo_user_pass'     => hashPassword('user')
    ]);
    
#################################################################################################### --- SHOW SUCCESS MESSAGE
    
    echo '
      <div class="row">
        <div class="col-md-8">
          <div class="alert alert-success">' . _('Hooray! Installation is all done! :)') . '</div>
          <p><span class="label label-danger">' . _('Important!') . '</span> ' . _('Please delete or rename the "install" folder to prevent intrusion!') . '</p>
        </div>
        
        <div class="col-md-6">
          <h5>' . _('What to do now?') . '</h5>
          <p>' . _('Check out your') . ' <a href="../home.php">' . _('home') . '</a> ' . _('page.') . '</p>
        </div>
      </div>
    ';
    
    require_once '../footer.php';
    exit;
    
  }
}

#################################################################################################### --- SHOW INSTALLATION FORM

echo '
  <div class="row">
    <div class="col-md-9">
      <form method="post">
        <input type="hidden" name="action" value="submitInstall" />';
        
        if(isEmpty($dbh)){
          echo '
            <fieldset>
              <legend>' . _('Database Info') . '</legend>
              <div class="form-group">
                <label class="control-label" for="dbHost">' . _('Host') . '</label>
                <div class="controls">
                  <input type="text" class="form-control input-xlarge" id="dbHost" name="dbHost" value="' . (isset($_POST['dbHost']) ? $_POST['dbHost'] : 'localhost') . '" placeholder="' . _('Enter host domain here. \'localhost\' is usually sufficient.') . '" >
                </div>
              </div>
              <div class="form-group">
                <label class="control-label" for="dbName">' . _('Database name') . '</label>
                <div class="controls">
                  <input type="text" class="form-control input-xlarge" id="dbName" name="dbName" value="' . $_POST['dbName'] . '" placeholder="' . _('Enter Database name here.') . '" >
                </div>
              </div>
              <div class="form-group">
                <label class="control-label" for="dbUser">' . _('Username') . '</label>
                <div class="controls">
                  <input type="text" class="form-control input-xlarge" id="dbUser" name="dbUser" value="' . $_POST['dbUser'] . '" placeholder="' . _('Enter Database Username here.') . '" >
                </div>
              </div>
              <div class="form-group">
                <label class="control-label" for="dbPass">' . _('Password') . '</label>
                <div class="controls">
                  <input type="password" class="form-control input-xlarge" id="dbPass" name="dbPass" value="' . $_POST['dbPass'] . '" placeholder="' . _('Enter Database Username Password here.') . '" >
                </div>
              </div>
            </fieldset>
          ';
        }
        
        echo '
        <fieldset>
          <legend>' . _('Site Settings') . '</legend>
          <div class="form-group">
            <label class="control-label" for="scriptPath">' . _('Site address') . '</label>
            <div class="controls">';
              if(isset($_POST['scriptPath'])){
                $scriptPath = $_POST['scriptPath'];
              }else{
                $scriptPath = "http://" . $_SERVER['HTTP_HOST'] . str_replace("install/install.php", "", str_replace("functions", "", str_replace("\\", "/", $_SERVER['SCRIPT_NAME'])));
              }
              echo '
              <input type="url" class="form-control input-xlarge" id="scriptPath" name="scriptPath" value="' . $scriptPath . '">
              <p class="help-block">' . _('This path should be set to where activate.php is located') . '</p>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label" for="email">' . _('Admin email') . '</label>
            <div class="controls">
              <input type="email" class="form-control input-xlarge" id="email" name="email" value="' . $_POST['email'] . '" placeholder="' . _('no-reply@' . $_SERVER['HTTP_HOST']) . '" >
              <p class="help-block">' . _('This email address will be visible to your users') . '</p>
            </div>
          </div>
        </fieldset>
        
        <fieldset>
          <legend>' . _('Admin Account') . '</legend>
          <div class="form-group">
            <label class="control-label" for="adminUser">' . _('Username') . '</label>
            <div class="controls">
              <input type="text" class="form-control input-xlarge" id="adminUser" name="adminUser" value="' . $_POST['adminUser'] . '" placeholder="' . _('Enter main Admin username here.') . '" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label" for="adminPass">' . _('Password') . '</label>
            <div class="controls">
              <input type="password" class="form-control input-xlarge" id="adminPass" name="adminPass" value="' . $_POST['adminPass'] . '" placeholder="' . _('Enter main Admin password here.') . '" >
            </div>
          </div>
        </fieldset>
        
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">' . _('Install') . '</button>
        </div>
      </form>
    </div>
  </div>
';

#################################################################################################### --- INCLUDE FOOTER

require_once '../footer.php';

?>
