<?
//xdebug_start_trace();

//ini_set('display_errors','1');
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
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
$match_start_date = '2012-08-08';	// starting date (YYYY-mm-dd)
$match_end_date = '2012-08-10';		// ending date (YYYY-mm-dd)
$match_first_day = '20120808';		// first day (YYYYmmdd)
$match_second_day = '20120809';		// second day (YYYYmmdd)
$match_last_day = '20120810';		// last day (YYYYmmdd)
$first_day_string = '8th August';	// first day (18th August)
$regCutOff = '12 Jul';			// registration cut off (31 Jul)
$SregCutOff = '20120712';		// registration cut off (YYYYmmdd)
$handicap_date = '8th August 2012';	// handicap cutoff (22nd July 2011)
$scorecard_date = '8th August 2012';	// scorecard cutoff (22nd July 2011)
$scorecard_date = '8th August 2012';	// scorecard cutoff (22nd July 2011)

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

/*
session_set_save_handler (array(&$User_Info, '_open'), 
   	                      array(&$User_Info, '_close'), 
       	                  array(&$User_Info, '_read'), 
           	              array(&$User_Info, '_write'), 
               	          array(&$User_Info, '_destroy'), 
                   	      array(&$User_Info, '_gc')); 
*/


// start session

session_start() ;

// Added by William on April 20, 2006. Display year using SESSION['sThisYear']
session_register("sUSERID","sSITEID","sCONTROLLVL","BACKREF","sNick","sRecord","returnURL", "sThisYear");
// XXX - update the year variable -- marc
$_SESSION["sThisYear"] = "2012";
$_G["APP_VER"] = "MercedesTrophy Asian Final ". $_SESSION['sThisYear'] ." Registration System";
//print $_SESSION["sCONTROLLVL"];

/*
if ($_SESSION["sCONTROLLVL"]=="MGM05") {
$regCutOff = '11 Aug';			// registration cut off (31 Jul)
$SregCutOff = '20120811';		// registration cut off (YYYYmmdd)
}
*/

/*
if ($User_Info->isLoggedIn()) {
	//query system via seesion id
	$PROFILE = $User_Info->query_UserID($_SESSION["sUSERID"]) ;
	//$s="admin";
	//$User_Info->go_page("admin");
	//print $PROFILE->userp_uid;
} 
else {
	$PROFILE = FALSE ;
}
*/

//Layout usage
require_once($_G["CLASS_DIR"]."HTML.php") ;
$HTML = new HTML ;
$htmltitle="";

if (isset($_GET["s"]) && $_GET["s"] != "") {
	//defaults links
	$takepage=$_GET["s"];
	$htpi=(isset($_GET["pi"])?$_GET["pi"]:"");
	$plainlayout=0;
	if($takepage=="edit"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$pagetogo="edit";}
	else if($takepage=="login"){
		if (isset($_SESSION["sUSERID"])&&isset($_SESSION["sSITEID"])&&isset($_SESSION["sCONTROLLVL"])) { 
			$User_Info->go_page("admin");
		}
		$pagetogo="login";
		//$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"admin");
		$plainlayout=1;
		}
	else if($takepage=="login_verify"){
		//$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"");
		$pagetogo="login_verify";
		$printlayout=1;}
	else if($takepage=="view"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$pagetogo="view";}
	else if($takepage=="error"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$htmltitle="Error";
		$pagetogo="error";}
	else if($takepage=="admin"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//echo "<br><br><pre>"; var_dump($_PROFILE); echo "<pre/>";
		$htmltitle="Main";
		$pagetogo="admin";}
	else if($takepage=="add"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$pagetogo="add";}
	else if($takepage=="logout_confirm"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$plainlayout=0;
		$pagetogo="logout_confirm";}
	else if($takepage=="logout"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//session_unset();
		session_destroy();
		$plainlayout=1;
		$pagetogo="logout";}
	else if($takepage=="delete"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=0;
		$pagetogo="delete";}
	//Special Request
	else if($takepage=="add_cust"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$htmltitle="Customer";
		$pagetogo="add_cust";}
	else if($takepage=="showcalendar"){
		//$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$pagetogo="showcalendar";
		$plainlayout=1;}
	else if($takepage=="view_cust"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$htmltitle="Customer";
		$pagetogo="view_cust";}
	else if($takepage=="edit_cust"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$htmltitle="CSV";
		$pagetogo="edit_cust";}
	else if($takepage=="add_csv"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$pagetogo="add_csv";
		$htmltitle="CSV";}
	else if($takepage=="sync_csv"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$pagetogo="sync_csv";
		$htmltitle="CSV";}
	else if($takepage=="view_csv"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$htmltitle="CSV";
		$pagetogo="view_csv";}
	else if($takepage=="view_cust2"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$htmltitle="Customer";
		$pagetogo="view_cust2";}
	else if($takepage=="view_cust3"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$htmltitle="Customer";
		$pagetogo="view_cust3";}
	else if($takepage=="view_cust4"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$htmltitle="Customer";
		$pagetogo="view_cust4";}
	else if($takepage=="delete_cust"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$htmltitle="Customer";
		$pagetogo="delete_cust";}
	else if($takepage=="view_record"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$pagetogo="view_record";}
	else if($takepage=="view_reg_record"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$pagetogo="view_reg_record";}
	else if($takepage=="view_local"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$pagetogo="view_local";}
	else if($takepage=="view_local2"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="view_local2";}
	else if($takepage=="view_local3"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="view_local3";}
	else if($takepage=="report_list"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_list";}
	else if($takepage=="report_master"){
                $_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
                //$htmltitle="Customer";
                $plainlayout=1;
                $pagetogo="report_master";}
	else if($takepage=="export_report_list"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="export_report_list";}
	else if($takepage=="report_list2"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_list2";}
	else if($takepage=="report_list3"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_list3";}
	else if($takepage=="report_group"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_group";}
	else if($takepage=="report_flight"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_flight";}
	else if($takepage=="report_hotelroom"){
                $_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
                //$htmltitle="Customer";
                $plainlayout=1;
                $pagetogo="report_hotelroom";}
	else if($takepage=="report_bus"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_bus";}
	else if($takepage=="view_teeoff"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=0;
		$pagetogo="view_teeoff";}
	else if($takepage=="report_player_type"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_player_type";}
	else if($takepage=="report_blacklist"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=0;
		$pagetogo="report_blacklist";}
	else if($takepage=="add_mmteeoff"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=0;
		$pagetogo="add_mmteeoff";}
	else if($takepage=="meal_update"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=0;
		$pagetogo="meal_update";}
	else if($takepage=="legend"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="legend";}
	else if($takepage=="view_log"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=0;
		$pagetogo="view_log";}
	else if($takepage=="view_lastlog"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=0;
		$pagetogo="view_lastlog";}
	else if($takepage=="view_past_players"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=0;
		$pagetogo="view_past_players";}
	else if($takepage=="view_past_winners"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=0;
		$pagetogo="view_past_winners";}
	else if($takepage=="comments"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="comments";}
	else if($takepage=="report_flight2"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_flight2";}
	else if($takepage=="report_flight3"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_flight3";}
	else if($takepage=="report_bus2"){
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		//$htmltitle="Customer";
		$plainlayout=1;
		$pagetogo="report_bus2";}
	else{
		$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
		$pagetogo="admin";}

//	print_r($_PROFILE);
//	var_dump($_SESSION);
//echo "<pre>";var_dump($_SESSION);echo"<pre/>";
	if(substr($_SESSION["sCONTROLLVL"],0,3)=="MGM")
	{
		if($plainlayout==1||$htpi==1){
			//displayPageHeader
			$HTML->displayPlainHeader() ;
			//$HTML->displayPageHeader() ;
			include($pagetogo.".php") ;
			$HTML->displayPlainFooter() ;
			//$HTML->displayPageFooter() ;
			//displayPageFooter
		}
		else {
		//$HTML->displayPlainHeader() ;
		$HTML->displayPageHeader() ;
		$HTML->displayBasicMenu() ;
		include($pagetogo.".php") ;
		//$HTML->displayPlainFooter() ;
		$HTML->displayPageFooter() ;
		}
	}
	else if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP")
	{
		if($plainlayout==1||$htpi==1){
			$HTML->displayPlainHeader() ;
			include($pagetogo.".php") ;
			$HTML->displayPlainFooter() ;
		}
		else
		{		
			$HTML->displayPageHeader() ;
			$HTML->displayMenu() ;
			$HTML->displayAppTopBar($htmltitle) ;
			//if($pagetogo=="view_cust")
			if($pagetogo=="view_cust"||$pagetogo=="view_cust2"||$pagetogo=="view_cust3"||$pagetogo=="view_cust4"){
				$HTML->displayAppTopSearchBar() ;
				$HTML->displayMenuList() ;
			}
			include($pagetogo.".php") ;
			if($pagetogo=="view_cust"||$pagetogo=="view_cust2"||$pagetogo=="view_cust3"||$pagetogo=="view_cust4"){
				//$HTML->displayAppTopSearchBar() ;
				$HTML->displayMenuList() ;
			}
			$HTML->displayPageFooter() ;
		}
	}
	else if($printlayout==1){
			include($pagetogo.".php") ;
		}
	else{
			$HTML->displayPlainHeader() ;
			include($pagetogo.".php") ;
			$HTML->displayPlainFooter() ;
	}
	
} else {
	$HTML->displayPlainHeader() ;  
	include($_G["DOMAIN_HOMEPAGE"] . ".php") ; // default page to load
	$HTML->displayPlainFooter() ;
}

//Using Timer to print out
ScriptTimer::printTime($_G["SCRIPT_PRINT"],$_G["SCRIPT_ALERT"],$_G["SCRIPT_ADMIN_EMAIL"]);
//print xdebug_memory_usage();
//xdebug_dump_function_trace();
?>

