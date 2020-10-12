<?php

#################################################################################################### --- ERROR REPORTING

// ini_set('display_errors', 1);
error_reporting(E_ERROR);

#################################################################################################### --- INIT OB & SESSION

ob_start();
session_start();

#################################################################################################### --- SETTINGS

const MIN_PASS_LENGTH   = 5;

// set default timezone for php date()
if ( ! ini_get('date.timezone')) {
  date_default_timezone_set('GMT');
}

#################################################################################################### --- SYSTEM REGEX PATTERNS

# Standard regex patterns, some of which are used by the script.

const SYSTEM_REGEX = [
  'safe'                  => '~^[\p{L}\p{M}\p{N}\p{P}\p{Sm}\p{Sc}\p{Z}\n\r]+$~u',
  'anyNumeric'            => '~^[0-9]+$~',
  'anyNumericOptional'    => '~^[0-9]*$~',
  'positiveNumeric'       => '~^[1-9][0-9]*$~',
  
  'hostname'              => '~^(([a-z0-9]|[a-z0-9][a-z0-9\-]*[a-z0-9])\.)*([a-z0-9]|[a-z0-9][a-z0-9\-]*[a-z0-9])$~',
  'username'              => '~^[A-Za-z0-9_-]+$~' // could do this unicode as well
];

#################################################################################################### --- SET PATHS

const BASE_PATH           = __DIR__;

const ADMIN_CLASSES_PATH  = BASE_PATH . '/admin/classes';
const CLASSES_PATH        = BASE_PATH . '/classes';
const FUNCTIONS_PATH      = BASE_PATH . '/functions';

const CONFIG_FILE_PATH    = BASE_PATH . '/config.php';

#################################################################################################### --- LOAD FUNCTIONS

require_once FUNCTIONS_PATH . '/_.php';
require_once FUNCTIONS_PATH . '/checkConfig.php';
require_once FUNCTIONS_PATH . '/checkInstall.php';
require_once FUNCTIONS_PATH . '/dbConnect.php';
require_once FUNCTIONS_PATH . '/displayUsers.php';
require_once FUNCTIONS_PATH . '/getBaseUrl.php';
require_once FUNCTIONS_PATH . '/getOption.php';
require_once FUNCTIONS_PATH . '/hashPassword.php';
require_once FUNCTIONS_PATH . '/in_level.php';
require_once FUNCTIONS_PATH . '/isEmpty.php';
require_once FUNCTIONS_PATH . '/list_registered.php';
require_once FUNCTIONS_PATH . '/pagination.php';
require_once FUNCTIONS_PATH . '/protect.php';
require_once FUNCTIONS_PATH . '/protectThis.php';
require_once FUNCTIONS_PATH . '/sendEmail.php';
require_once FUNCTIONS_PATH . '/user_levels.php';
require_once FUNCTIONS_PATH . '/validatePassword.php';

#################################################################################################### --- LOAD CLASSES

require_once CLASSES_PATH . '/connect.class.php';
require_once CLASSES_PATH . '/generic.class.php'; /* sets SITE_PATH define */

require_once CLASSES_PATH . '/check.class.php';
require_once CLASSES_PATH . '/forgot.class.php';
require_once CLASSES_PATH . '/integration.class.php';
require_once CLASSES_PATH . '/login.class.php';
require_once CLASSES_PATH . '/profile.class.php';
require_once CLASSES_PATH . '/signup.class.php';
require_once CLASSES_PATH . '/translate.class.php';

require_once ADMIN_CLASSES_PATH . '/add_level.class.php';
require_once ADMIN_CLASSES_PATH . '/add_user.class.php';
require_once ADMIN_CLASSES_PATH . '/edit_level.class.php';
require_once ADMIN_CLASSES_PATH . '/edit_user.class.php';
require_once ADMIN_CLASSES_PATH . '/reports.class.php';
require_once ADMIN_CLASSES_PATH . '/send_email.class.php';
require_once ADMIN_CLASSES_PATH . '/settings.class.php';

#################################################################################################### --- GET BASE URL

$baseUrl = getBaseUrl();

define('BASE_URL', $baseUrl);

#################################################################################################### --- CONNECT TO DATABASE

# The connection here is optional, no error if connection fails.

$config = checkConfig(); // true or string message

if ($config === true) { // type check
  
  include CONFIG_FILE_PATH; // todo: check other locations from where this is loaded
  
  $dbh = dbConnect($host, $dbName, $dbUser, $dbPass); // false if can't connect
}

#################################################################################################### --- CHECK CONFIG FILE

$setTranslate = new Translate();

/*
$generic = new Generic();
$login = new Login();
$jigowatt_integration = new Jigowatt_integration();
$profile = new Profile();
$signUp = new SignUp();

$addLevel = new Add_level();
$settings = new Settings();
$sendEmail = new Send_email();
$jigowatt_reports = new Jigowatt_reports();
$edit_user = new Edit_user();
$Edit_level = new Edit_level;
$addUser = new Add_user();
*/

#################################################################################################### --- CHECK CONFIG FILE

/*
if ($config !== true) {
  
  if ($page === 'index') {
    
    header('Location: ' . BASE_URL . '/home.php');
    exit;
    
  } else if ($page !== 'home') {
    
    require_once BASE_PATH . '/header.php';
    
    echo '
      <div class="alert alert-danger">' .
        _($config) . '
      </div>
    ';
    
    require_once BASE_PATH . '/footer.php';
  }
}
*/


/*
  // check DB connection
  if(isEmpty($dbh)){
    
    if (($page !== 'index') && ($page !== 'home')) {
      
      require_once BASE_PATH . '/header.php';
      
      echo '
        <div class="alert alert-danger">' .
          _('Could not connect to the database! The database credentials you supplied are incorrect!') . '
        </div>
      ';
      
      require_once BASE_PATH . '/footer.php';
    }
  }
  */

?>
