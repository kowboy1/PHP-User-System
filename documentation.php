<?php

require_once 'initialise.php';
require_once 'header.php';

?>

<p style="margin:10px 0">Current version is 5.0.4</p>

<ul class="nav nav-tabs">
  <li><a href="#install-protect" data-toggle="tab">Protect</a></li>
  <li class="active"><a href="#install-home" data-toggle="tab">Installation</a></li>
  <li><a href="#install-functions" data-toggle="tab">Functions</a></li>
  <li><a href="#install-languages" data-toggle="tab">Languages</a></li>
  <li><a href="#faq" data-toggle="tab">FAQ</a></li>
</ul>
<div class="tab-content">

<div class="tab-pane" id="install-protect">
<legend>Access only to certain roles</legend>
<ol>
  <li>Open the file you wish to protect.</li>
  <li>Include our check class at the top of your file:
    <pre class="prettyprint lang-prepro" style="width:400px">&lt;?php include_once('classes/check.class.php'); ?&gt;</pre>
  </li>
  <li>Call the protect function, also at the top:
    <pre class="prettyprint lang-prepro" style="width:400px">&lt;?php protect(&quot;Admin, Special, User&quot;); ?&gt;</pre>
    <p>With this, your page will only be visible to users that belong to those roles.</p>
  </li>
</ol>

<legend>Access to all roles</legend>
<p>See <code>profile.php</code> for an example.</p>
<p>Use a wildcard to require signing in before viewing a page.</p>
<pre class="prettyprint lang-prepro" style="width:400px">&lt;?php include_once('classes/check.class.php'); ?&gt;
&lt;?php protect(&quot;*&quot;); ?&gt;</pre>
<p>This will show your page to all signed in members.</p>

<legend>Partial access</legend>
<p>See <code>protected.php</code> for an example.</p>
<p></p>
<pre class="prettyprint lang-prepro" style="width:600px">&lt;?php include_once('classes/check.class.php'); ?&gt;
&lt;?php if( protectThis(&quot;Admin, Special, User&quot;) ) : ?>
&lt;p&gt;This html text is viewable only to these named roles&lt;/p&gt;
&lt;?php endif; ?&gt;
&lt;p&gt;The text here can be seen by any user, guest or not!&lt;/p&gt;</pre>

</div>

<div class="tab-pane active" id="install-home">
  <legend>Requirements</legend>
  <p>
    In order to have a functioning installation, all files must be uploaded
    to the server and a valid database connection must be established.
  </p>
  <p>
    The database connection credentials are stored in the <code>config.php</code> file,
    and they can be created automatically or typed-in manually, depending on the
    installation method.
  </p>
  
  <legend>Automatic Installation</legend>
  <p>
    Before starting the installation, make a copy of <code>/config.sample.php</code> in the
    same directory, and name it <code>config.php</code>. Make sure the web server has "write"
    permissions on <code>config.php</code>. In your file manager, permissions for this file
    should look like this: <code>-rw-rw-rw-</code>.
  </p>
  <p>
    After this, simply run the <i>Install Wizard</i>.
  </p>
  <p>
    A link to the wizard can be found on the <code>home.php</code> page, or just open
    the <code>/install/</code> folder on your server.
  </p>
  
  <legend>Manual Installation</legend>
  <p>
    Before starting the installation, make a copy of <code>/config.sample.php</code> in the
    same directory, and name it <code>config.php</code>. Edit <code>config.php</code>
    and fill-in the database connection info.
  </p>
  <p>
    After this, simply run the <i>Install Wizard</i>.
  </p>
  <p>
    A link to the wizard can be found on the <code>home.php</code> page, or just open
    the <code>/install/</code> folder on your server.
  </p>
  <p>
    <strong>Note regarding SMTP configuration:</strong> When editing <code>config.php</code> manually,
    you also have to manually set the variables <strong>$encryption_key</strong> and
    <strong>$iv</strong> within it (only needed for sending email via SMTP). These are created
    automatically when doing an automatic installation, but not in a manual one.
    Below is the code we use to set these, so you should use a similar method:
    
    <pre>
      // PHP code
      // encryption key and initialization vector
      
      $encryptionKey = openssl_random_pseudo_bytes (32);
      $initializationVector = openssl_random_pseudo_bytes (openssl_cipher_iv_length ('aes-256-cbc'));
      
      $encryption_key = base64_encode ($encryptionKey);
      $iv = base64_encode ($initializationVector);</pre>
  </p>
  
  <legend>Secure your script after the installation</legend>
  <p>
    To improve security, after the installation is completed successfully,
    make sure to restrict access permissions to <code>config.php</code>. The only
    access requirement is that the web server can read this file.
  </p>
  <p>
    You can change the permissions of <code>config.php</code> by right clicking on it
    in your file manager and choosing to see the file's properties, then selecting "read"
    and "write" permissions for the <i>owner</i>, only "read" permissions for
    <i>group</i> and no permissions or "forbidden" for <i>others</i>. The permissions
    for <code>config.php</code> in your file manager should now look like this:
    <code>-rw-r-----</code>.
  </p>
  <p>
    On some web servers the above might not work, and it
    might be needed to set "read" permissions for <i>others</i> as well. In that case,
    the permissions for <code>config.php</code> in your file manager should look
    like this: <code>-rw-r--r--</code>. Normally this is safe, but you should contact
    your hosting provider to make sure, just in case!
  </p>
</div>

<div class="tab-pane" id="install-functions">

<legend>Session data?</legend>
<p>To call one of these, you could do: <code>&lt;?php echo $_SESSION['jigowatt']['email']; ?&gt;</code></p>

<pre class="prettyprint lang-prepro" style="width:600px">$_SESSION['jigowatt']['email']      /* Eg: info@jigowatt.co.uk */
$_SESSION['jigowatt']['gravatar']   /* Eg: &lt;img class=&quot;gravatar thumbnail&quot; src=&quot;http://www.gravatar.com/avatar/acc132?s=26&amp;d=mm&amp;r=g&quot; /&gt; */
$_SESSION['jigowatt']['username']   /* Eg: admin */
$_SESSION['jigowatt']['user_id']    /* Eg: 1 */
$_SESSION['jigowatt']['user_level'] /* Eg: array('Admin', 'Special', 'User'); */ </pre>

		<legend>Logged in?</legend>
		<p>Checks if the user is logged in</p>
		<p><pre class="prettyprint lang-prepro" style="width:600px">&lt;?php
if ( ! session_id() ) {
	$minutes = Generic::getOption('default_session');
	ini_set('session.cookie_lifetime', 60 * $minutes);
	session_start();
}

if(isset($_SESSION['jigowatt']['username'])) {
    echo &quot;You're logged in!&quot;;
}
?&gt;</pre></p>

		<legend>Current username</legend>
		<p>Returns the logged in user's username</p>
		<p><pre class="prettyprint lang-prepro" style="width:600px">&lt;?php
if (!isset($_SESSION)) session_start();

if(isset($_SESSION['jigowatt']['username'])) {
    echo &quot;You're username is: &quot; . $_SESSION['jigowatt']['username'];
}
?&gt;</pre></p>

		<legend>Is admin?</legend>
		<p>Checks if the current user is an admin</p>
		<p><pre class="prettyprint lang-prepro" style="width:600px">&lt;?php
if (!isset($_SESSION)) session_start();

if(in_array(1, $_SESSION['jigowatt']['user_level'])) {
    echo &quot;You're an admin! Howdy&quot;;
}
?&gt;</pre></p>

	  </div>

  	  <div class="tab-pane" id="install-languages">
		<legend>Creating translations</legend>
			<p>In our example, we will create a translation for German (de_DE)</p>
			<p><strong>Note:</strong> You might have troubles using translations on a Windows environment. It is recommended that you use a Linux server.</p>
			<ol>
				<li><a href="http://www.poedit.net/download.php">Download</a> and install Poedit.</li>
				<li>In Poedit, go to File > New Catalog from POT , and select the <code>phplogin.pot</code> file, located in <code>/php-login-user-manage/language/</code>.
					<br><img src="http://i.imgur.com/hyPVf.png">
				</li>
				<li>Fill out the information on the Project Info tab and press OK
					<br><img src="http://i.imgur.com/0Me1d.png">
				</li>
				<li>A prompt will ask you to save the file, save it as <code>phplogin.po</code> in the following directory: <pre class="prettyprint lang-prepro" style="width:600px">/php-login-user-manage/languages/<strong>de_DE</strong>/LC_MESSAGES/</pre>
				We saved it under de_DE for German. <a href="http://www.roseindia.net/tutorials/I18N/locales-list.shtml">Click here for your language's abbreviation</a>.</li>
				<li>Start translating! Click on a line and enter your translated text in the huge white box on the bottom of the window.
					<br><img src="http://i.imgur.com/9xBvv.png">
				</li>
				<li>Once you're done, just save it and it should automatically generate a <code>phplogin.mo</code> file.
				<li><i>(Optional)</i> To set German as the default language, open <code>/php-login-user-manage/classes/translate.class.php</code> and change en_US to de_DE.</li>

			</ol>


	  </div>

  	  <div class="tab-pane" id="faq">

			<fieldset>

				<legend>Share logins across subdomains?</legend>
				<p>If you want users to be able to login once and access their login from any subdomain on your website, simply add the following to login.class.php</p>

				<ol>
					<li>Open <code>classes/login.php</code></li>
					<li>Find <code>ini_set('session.cookie_lifetime', 60 * $minutes);</code></li>
					<li>Add below <code>ini_set('session.cookie_domain', '.yourdomain.com');</code></li>
				</ol>

			</fieldset>
			<fieldset>

				<legend>Manually approve new users?</legend>
				<p>If you want to moderate all user registrations, you can set their default role to a restricted role. When they sign up, they won't be able to access anything until you move them to a non-restricted role.</p>
				<ol>
					<li>Create a new role, call it "Pending". Check the box to disable this role.</li>
					<li>Go to Settings > General and set your default role to the Pending role you just created.</li>
					<li>Optional: Go to Settings > General and turn off email activation for new users.</li>
				</ol>

			</fieldset>
	  </div>


<?php include_once('footer.php'); ?>
