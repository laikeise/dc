<?php
//	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	// clean up UID
	if ( isset($_POST["USERID"]) && !empty($_POST["USERID"]) ) {
		$_uid = $_POST["USERID"] ;
		$_uid = trim($_uid) ;
		$_uid = strtolower($_uid) ;
	} else {
		$_uid = "" ;
	}
	
	$_SESSION['templogin']=$_uid;
	
	// clean up PW
	if ( isset($_POST["PASS"]) && !empty($_POST["PASS"]) ) {
		$_pass = $_POST["PASS"] ;
		$_pass = trim($_pass) ;
		$_pass = md5($_pass) ;
	} else {
		$_pass = "" ;
	}
	
	// check if member ID was entered
	if ($_uid == "" || $_pass == "") {
		$User_Info->go_page("login&e=2") ;
	}
	
	
	// check password to login.(check_User() method in User Class)
	$profile = $User_Info->check_User($_uid,$_pass);

	//print $profile;
	//print $_uid;
	//print $_pass;
	if (!empty($profile) && $profile!=FALSE) {
		//print "success ";
		if ($profile) {
			$sUSERID=$User_Info->get_User($_uid);
			$_SESSION["sUSERID"]=$sUSERID;
			//print $sUSERID;
			$newdetail=array("userp_last_time"=>"NULL");
			$_PROFILE=$User_Info->authUser($_SESSION["sUSERID"],"login");
			//print_r($newdetail);
			// update last login time.(update_User() method in User Class)
			
			//print $_SERVER['SERVER_ADDR'];
			$sSITEID=$Site_Info->verify_Site($_SERVER["HTTP_HOST"]);
			$sSITEID=$Site_Info->verify_Site($_SERVER["SERVER_ADDR"]);
			//print "sSITEID: " . $sSITEID . "\n";
			//print "run";
			// query server if its the admin in this server(verify_Siteaccess() method in Site Class)
			$sCONTROLLVL=$Site_Info->verify_Siteaccess($sUSERID,$sSITEID);
			//print $sCONTROLLVL;
			
			$_SESSION["sSITEID"]=$sSITEID;
			$_SESSION["sCONTROLLVL"]=$sCONTROLLVL;
			$_SESSION["sNick"]=($_PROFILE->userp_login);
			//$_SESSION["sNick"]="xxx";
			$_SESSION["sRecord"]=($_PROFILE->userp_country);
			//print_r($_PROFILE);
			//session_register("counter");
			//print $sUSERID." - ".$_SESSION[""];
			
			setcookie("sUserID","$sUSERID",time()+31536000) ;
			//print_r($_SESSION);
			//Pages to redirect to have to be control here
			
			if($sCONTROLLVL==""){
				$User_Info->go_page("login&e=3");	
			}
			else{
				$User_Info->update_User($sUSERID,$newdetail);
				session_unregister("templogin");
				$User_Info->go_page("admin");
			}
			
		}
	} else {
		// member ID not found
		$User_Info->go_page("login&e=1");
	}
	


?>
