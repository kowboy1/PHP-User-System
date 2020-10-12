<?php

include_once __DIR__ . '/settings.php';

$settings = new Settings();

$encryptedUsername = $settings->getOption('smtp-username');
$encryptedPassword = $settings->getOption('smtp-password');

$decryptedUsername = openssl_decrypt($encryptedUsername, 'aes-256-cbc', base64_decode($encryption_key), 0, base64_decode($iv));
$decryptedPassword = openssl_decrypt($encryptedPassword, 'aes-256-cbc', base64_decode($encryption_key), 0, base64_decode($iv));

?>

<fieldset>
	<legend><?php echo _('SMTP configuration'); ?></legend>
	
	<div class="controls checkbox">
    <label class="">
      <input type="checkbox" class="" id="smtp-active" name="smtp-active" <?php echo $settings->getOption('smtp-active', true); ?>>
      <?php echo _('Allow SMTP configuration'); ?>
    </label><br>
  </div>
  
  <div class="form-group">
    <label class="control-label" for="smtp-host-name"><?php echo _('Host name'); ?></label>
    <div class="controls">
      <input type="text" class="form-control input-xlarge" id="smtp-host-name" name="smtp-host-name" value="<?php echo $settings->getOption('smtp-host-name'); ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label" for="smtp-port-number"><?php echo _('Port number'); ?></label>
    <div class="controls">
      <input type="number" class="form-control input-large" id="smtp-port-number" name="smtp-port-number" value="<?php echo $settings->getOption('smtp-port-number'); ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label" for="smtp-username"><?php echo _('Username'); ?></label>
    <div class="controls">
      <input type="text" class="form-control input-xlarge" id="smtp-username" name="smtp-username" value="<?php echo $decryptedUsername; ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label" for="smtp-password"><?php echo _('Password'); ?></label>
    <div class="controls">
      <input type="password" class="form-control input-xlarge" id="smtp-password" name="smtp-password" value="<?php echo $decryptedPassword; ?>">
    </div>
  </div>
	
	<input type="hidden" name="encryption_key" value="<?php echo $encryption_key; ?>">
	<input type="hidden" name="iv" value="<?php echo $iv; ?>">
	
</fieldset>
