<?
$_G["DOMAIN_ID"] = "sl" ; 				// domain id, to prefix db tables
$_G["DOMAIN_SESS"] = "shared" ;			// session table prefix to use
$_G["DOMAIN_HOMEPAGE"] = "login" ;	// default or home page
$_G["DOMAIN_NAME"] = "Mercedes Trophy Registration System" ;
$_G["DOMAIN_DESC"] = "MAIN API TESTING" ;

//Classes directory
$_G["CLASS_DIR"] = "classes/" ;

//File CSV directory
$_G["URL_DIR"] = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
$_G["UPLOAD_DIR"] = "archive/files/";
$_G["UPLOAD_URL"] = $_G["URL_DIR"]."/archive/files/";

//$_G["DOMAIN_KEYWORDS"] = "NIL" ;
$_G["ERROR_DEBUGGER"] = false ; // use of true to on and false to off - boolean
include_once ("server.php");
require_once($_G["CLASS_DIR"]."DB.php") ;
require_once($_G["CLASS_DIR"]."Session.php") ;
require_once($_G["CLASS_DIR"]."Auth.php") ;
require_once($_G["CLASS_DIR"]."User.php") ;
require_once($_G["CLASS_DIR"]."Site.php") ;

//for pro addon modules
require_once("Pro_Addon.php") ;
$Pro_Info = new Pro_Addon;

require_once($_G["CLASS_DIR"]."ErrorHandler.php") ;
$Error_Handler =new ErrorHandler();
$Error_Handler->set_error_printer($_G["ERROR_DEBUGGER"]);

//This is use to track the time the page start to load 
require_once($_G["CLASS_DIR"]."Timer.php") ;
$_G["SCRIPT_PRINT"] = "0";		// for timer print use boolean default is off
$_G["SCRIPT_ALERT"] = "0";		// for timer alert use boolean default is on
$_G["SCRIPT_ADMIN_EMAIL"] = "debug@synergyitc.com";		// for timer alert use boolean default is on

// Set the starting / ending dates of the tourney
// To be used throughout the system (search XXX)
// Please follow strictly to the format provided!!! -- marc 20080522
$match_start_date = '2009-08-19';	// starting date (YYYY-mm-dd)
$match_end_date = '2009-08-21';		// ending date (YYYY-mm-dd)
$match_first_day = '200900819';		// first day (YYYYmmdd)
$match_second_day = '20090820';		// second day (YYYYmmdd)
$match_last_day = '20090821';		// last day (YYYYmmdd)
$first_day_string = '19th August';	// first day (19th August)
$regCutOff = '03 Aug';			// registration cut off (05 Aug)
$SregCutOff = '20090803';		// registration cut off (YYYYmmdd)
$handicap_date = '5th July 2009';	// handicap cutoff (5th July 2009)
$scorecard_date = '20th July 2009';	// scorecard cutoff (20th July 2009)

//Change of naming
include_once("name.php");
//setup of default
$printlayout="";
$printlayout="";
$didi="";
$pg="";
$ls="";

//start the timer
ScriptTimer::startTimer();

//$Base = new User ;
$User_Info = new User;
$Site_Info = new Site;

$User_Info->setDBConfig( $_G["DB_HOST"],
			$_G["DB_NAME"],
			$_G["DB_USER"],
			$_G["DB_PASS"],
			$_G["DOMAIN_ID"],
			$_G["DOMAIN_SESS"] ) ;

//$User_Info->setSessionTablePrefix($_G["DOMAIN_SESS"]) ;
$User_Info->connectDB();

// start session

session_start() ;

// Added by William on April 20, 2006. Display year using SESSION['sThisYear']
session_register("sUSERID","sSITEID","sCONTROLLVL","BACKREF","sNick","sRecord","returnURL", "sThisYear");
// XXX - update the year variable -- marc
$_SESSION["sThisYear"] = "2009";
$_G["APP_VER"] = "MercedesTrophy Asian Final ". $_SESSION['sThisYear'] ." Registration System";

//Layout usage
require_once($_G["CLASS_DIR"]."HTML.php") ;
$HTML = new HTML ;
$htmltitle="";
echo"<pre>";var_dump($_SESSION);echo"</pre>";
echo $gen=$_GET['genxlsdata'];
$tblist=$User_Info->getFieldName("$gen");
//var_dump($tblist);
//Using Timer to print out
ScriptTimer::printTime($_G["SCRIPT_PRINT"],$_G["SCRIPT_ALERT"],$_G["SCRIPT_ADMIN_EMAIL"]);
?>