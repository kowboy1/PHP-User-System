<?php

/**
 * Login for main site.
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

require_once 'initialise.php';
require_once 'header.php';

$generic = new Generic();
$login = new Login();
$jigowatt_integration = new Jigowatt_integration();

$generic->displayMessage( $login->error, false );

if(
      !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
  AND (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
  &&  ($login->error OR $login->msg)
) {
  exit;
}

?>

<div id="forgot-form" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _('Account Recovery'); ?></h4>
      </div>
      <div class="modal-body">
		<div id="message"></div>
		<form action="forgot.php" method="post" name="forgotform" id="forgotform" class="form-stacked forgotform normal-label">
			<div class="controlgroup forgotcenter">
			<label for="usernamemail"><?php echo _('Username or Email Address'); ?></label>
				<div class="control">
					<input id="usernamemail" name="usernamemail" type="text"/>
				</div>
			</div>
			<input type="submit" class="hidden" name="forgotten">
		</form>
      </div>
      <div class="modal-footer">
		<button data-complete-text="<?php echo _('Done'); ?>" class="btn btn-primary pull-right" id="forgotsubmit"><?php echo _('Submit'); ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="row">
  <div class="main login col-md-4">
    <form method="post" class="form normal-label">
            <?php if ($login->sms_form !== FALSE): ?>
                <?php echo $login->sms_form; ?>
            <?php else: ?>
    <fieldset>
    <h4><?php echo _('Sign in:'); ?></h4>
      <div class="form-group">
        <label for="username" class="login-label"><?php echo $login->use_emails ? _('Email address') : _('Username'); ?></label>
        <input class="form-control" id="username" name="username" placeholder="<?php echo _('Username'); ?>" type="text"/>
      </div>

      <div class="form-group">
        <label for="password" class="login-label"><?php echo _('Password'); ?></label>
        <input class="form-control" id="password" name="password" size="30" placeholder="<?php echo _('Password'); ?>" type="password"/>
      </div>
    </fieldset>

    <input type="hidden" name="token" value="<?php echo $_SESSION['jigowatt']['token']; ?>"/>
    <input type="submit" value="<?php echo _('Sign in'); ?>" class="btn btn-default login-submit" id="login-submit" name="login"/>
    
    <div id="socialLogin">
      <!--<fb:login-button scope="public_profile,email" id="login" onlogin="fbLogin();" data-size="medium"></fb:login-button>-->
      
      <?php
        
        if ($generic->getOption('integration-facebook-enable') === '1') {
          echo '<div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="login_with" data-use-continue-as="true" onlogin="fbLogin();">Log in</div>';
        }
        
        if ($generic->getOption('integration-google-enable') === '1') {
          echo '<div class="g-signin2" onclick="clickLogin()" data-onsuccess="onSignIn"></div>';
        }
        
        if ($generic->getOption('integration-twitter-enable') === '1') {
          echo '
            <a id="twitter-button" class="btn btn-block btn-social btn-twitter">
              <i class="fa fa-twitter"></i> Sign in
            </a>
          ';
        }
        
      ?>
      
    </div>
    
    <span class="forgot"><a data-toggle="modal" href="#" data-target="#forgot-form" id="forgotlink" tabindex=-1><?php echo _('Trouble signing in'); ?></a>?</span>
    <label class="remember" for="remember">
      <input type="checkbox" id="remember" name="remember"/><span><?php echo _('Stay signed in'); ?></span>
    </label>
    
    <p class="signup"><a href="<?php echo BASE_URL . '/sign_up.php'; ?>"><?php echo _('New to our site? <strong>Join today!</strong>'); ?></a></p>
    
    <?php /*if ( !empty($jigowatt_integration->enabledMethods) ) : ?>
    
    <div class="">
      <?php foreach ($jigowatt_integration->enabledMethods as $key ) : ?>
        <p><a href="<?php echo BASE_URL . '/login.php?login=' . $key; ?>"><img src="assets/img/<?php echo $key; ?>_signin.png" alt="<?php echo $key; ?>"></a></p>
      <?php endforeach; ?>
    </div>
    
    <?php endif;*/ ?>
      <?php endif; ?>
    </form>
    
  </div>
  
</div>

<?php

include_once('footer.php');

# todo: move the social login scripts somewhere else
?>

<script>

// ################################################################################################# --- FACEBOOK LOGIN

var appId = "<?php echo $generic->getOption('facebook-app-id'); ?>";

window.fbAsyncInit = function(){
  FB.init({
    appId      : appId,
    cookie     : true,  // enable cookies to allow the server to access the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use graph api version 2.5
  });
};

// Load the SDK asynchronously
(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function fbLogin(){
  FB.api('/me','GET', {fields: 'name,email,id,picture.width(150).height(150)'}, function(response){
    var loginData = "name=" + response.name + "&email=" + response.email + "&fbId=" + response.id;
    
    //ajax reqest to server.
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "social-login/facebookLogin.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xmlhttp.onreadystatechange = function(){
      
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
        
        var jsonResponse = JSON.parse(xmlhttp.responseText);
        
        if (jsonResponse.success === true) {
          
          window.location.href = jsonResponse.redirectUrl;
          
        } else if (jsonResponse.success === false) {
          
          var errorsList = document.createElement("ul");
          errorsList.classList.add("alert", "alert-danger");
          
          var errors = jsonResponse.msg;
          
          for (var i = 0; i < errors.length; i++) {
            var singleError = document.createElement("li");
            singleError.innerHTML = errors[i];
            errorsList.appendChild(singleError);
          }
          
          var loginCont = document.querySelector('.container .col-md-12');
          var refElement = document.querySelector('#forgot-form');
          loginCont.insertBefore(errorsList, refElement);
        }
        
      };
    }
    xmlhttp.send(loginData);
  });
}

// ################################################################################################# --- TWITTER LOGIN

$('#twitter-button').on('click', function() {
  
  var publicKey = "<?php echo $generic->getOption('twitter-key'); ?>";
  
  // Initialize with your OAuth.io app public key
  OAuth.initialize(publicKey);
  // Use popup for OAuth
  OAuth.popup('twitter').then(twitter => {
    
    twitter.get('/1.1/account/verify_credentials.json?include_email=true').then(data => {
      
      var loginData = "name=" + data.name + "&email=" + data.email + "&twitterId=" + data.id;
      
      //ajax reqest to server.
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("POST", "social-login/twitterLogin.php", true);
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      
      xmlhttp.onreadystatechange = function(){
        
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
          
          var jsonResponse = JSON.parse(xmlhttp.responseText);
          
          if (jsonResponse.success === true) {
            
            window.location.href = jsonResponse.redirectUrl;
            
          } else if (jsonResponse.success === false) {
            
            var errorsList = document.createElement("ul");
            errorsList.classList.add("alert", "alert-danger");
            
            var errors = jsonResponse.msg;
            
            for (var i = 0; i < errors.length; i++) {
              var singleError = document.createElement("li");
              singleError.innerHTML = errors[i];
              errorsList.appendChild(singleError);
            }
            
            var loginCont = document.querySelector('.container .col-md-12');
            var refElement = document.querySelector('#forgot-form');
            loginCont.insertBefore(errorsList, refElement);
          }
          
        };
      }
      xmlhttp.send(loginData);
    })
  });
})

// ################################################################################################# --- GOOGLE LOGIN

var clicked=false;//Global Variable

function clickLogin() {
  clicked = true;
}

function onSignIn(googleUser){
  
  if (clicked) {
    
    profile = googleUser.getBasicProfile();
    
    var loginData = "name=" + profile.getName() + "&email=" + profile.getEmail() + "&googleId=" + profile.getId();
    
    //ajax reqest to server.
    var xmlhttp = new XMLHttpRequest();
    
    xmlhttp.open("POST", "social-login/googleLogin.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xmlhttp.onreadystatechange = function(){
      
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
        
        var jsonResponse = JSON.parse(xmlhttp.responseText);
        
        if (jsonResponse.success === true) {
          
          window.location.href = jsonResponse.redirectUrl;
          
        } else if (jsonResponse.success === false) {
          
          var errorsList = document.createElement("ul");
          errorsList.classList.add("alert", "alert-danger");
          
          var errors = jsonResponse.msg;
          
          for (var i = 0; i < errors.length; i++) {
            var singleError = document.createElement("li");
            singleError.innerHTML = errors[i];
            errorsList.appendChild(singleError);
          }
          
          var loginCont = document.querySelector('.container .col-md-12');
          var refElement = document.querySelector('#forgot-form');
          loginCont.insertBefore(errorsList, refElement);
          
        }
        
      }
    }
    xmlhttp.send(loginData);
  }
}

</script>
