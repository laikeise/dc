<?php
	/**
	*	User Class Information
	*
	*	Project:     API Ver 5
	*
	*/
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | API Ver 5 User Class                                                 |
// +----------------------------------------------------------------------+
// | Copyright Synergy ITC Pte Ltd                                        |
// +----------------------------------------------------------------------+
// | Author: Poh Leng Yee <lengyee@synergyitc.com>                        |
// |         Jacky Ng <jackyng@synergyitc.com>                            |
// +----------------------------------------------------------------------+
//

/* $Id: User.php,v 1.1 2006/04/20 08:11:05 devcvs Exp $ User.php,v 1.0 2004/01/08 $ */

/**
	*	User Class Information
	*
	*	Project:     API Ver 5
	*
	*	File:        User.php
	*
    *	Database Tables
    *
    *	The following db table should be created:
    *	# Fields usage 
    *	# userp_uid - Unique identifier for user profile
    *	# userp_login - user login id
    *	# userp_pass - password
    *	# userp_name - name of user
    *	# userp_last_time - timestamp of user last login
    *	# userp_last_sid - last site id entered
    *	# userp_status - account status
    *	# 1 - active - Running account
    *	# 2 - locked - Freeze account
    *	# 3 - removed - Remove account. (Will be remove by cron jobs on time expiry)
    *
    *	CREATE TABLE user_profile (
    *	userp_uid int(10) unsigned NOT NULL auto_increment,
    *	userp_login varchar(25) NOT NULL default '',
    *	userp_pass varchar(32) NOT NULL default '',
    *	userp_name varchar(50) NOT NULL default '',
    *	userp_last_time timestamp NOT NULL default 'NULL',
    *	userp_last_sid int(10) NOT NULL default 0,
    *	userp_status tinyint NOT NULL default 0,
    *	primary key(userp_uid)
    *	)TYPE=MyISAM;
    *	
    *	INSERT INTO user_profile VALUES (NULL,'robrob','c33367701511b4f6020ec61ded352059','Jacky',NULL,0,1);
    *
    *	# Fields usage - 
    *	# userp_uid - Unique identifier for user profile
    *	# site_sid - Unique identifier for site profile
    *	# usite_lvl - Example of different access level (Minimum 2 levels)
    *	# 1 - administor - Main Admin of centres
    *	# 2 - site administor - Site Admin of centres
    *	# 3 - advanced user - Back end admin of sites 
    *	# 4 - normal user - normal users of sites
    *	# 5 - guest user - visitor access to view private site contents
    *
    *	CREATE TABLE usite_cont (
    *	userp_uid int(10) NOT NULL default 0,
    *	site_sid int(10) NOT NULL default 0,
    *	usite_lvl tinyint(4) NOT NULL default 0,
    *	#primary key(site_id)
    *	)TYPE=MyISAM;
    *
    *	INSERT INTO usite_cont VALUES (1,1,1);
    *
	* @author Jacky Ng {@link mailto:jackyng@synergyitc.com}
	* @version 1.0 
	* @copyright Synergy ITC Pte Ltd
	* @package User
	**/
    
class User extends Session {  
		
		/**
		* Create new user details
		*
		* This will a new user to the system. It is a must to set all the 3 following variables into the array.
		* <br />Array usage
		* <br />$newdetail = (userp_login => xxxx,userp_pass==>xxxx, userp_name =>xxx);
		* @access public
		* @param array $newdetail detail of the new user
		* @return $boolean
		*
    	*/
	function create_User($newdetail) {
		if(isset($newdetail[userp_login])&&isset($newdetail[userp_pass])&&isset($newdetail[userp_name]))
    	{
    		$q="INSERT INTO user_profile VALUES (NULL,'".$newdetail[userp_login]."','".$newdetail[userp_pass]."','".$newdetail[userp_name]."',NULL,0,1);";
  			
  			if(doUpdateSQL($q)>0)
	    		return TRUE;
	    	else
	    		return FALSE;
		}
    	else
	    	return FALSE;	
    }
    
    	/**
    	* Check user login & password
    	*
    	* This will auth the user to the system by 
    	* using the user login and password that is key from the login screen.
    	* It will also check if the account is active. Currently only 3 type
    	*<ul>
    	*	#<li> 1 - active - Running account</li>
    	*	#<li> 2 - locked - Freeze account</li>
    	*	#<li> 3 - removed - Remove account. (Will be remove by cron jobs on time expiry)</li>
    	*</ul>
    	*
    	* @todo Plan to return account lvl or error msg for another function or a direct print out to tell the error msg.
    	* @access public
    	* @param string $_user_login  user login to be checked
    	* @param string $_user_pass  password to be checked
    	* @return boolean return TRUE if user account is active ,else FALSE
    	*
    	*/
    function check_User($_user_login,$_user_pass) {
    	$q="select userp_status from user_profile where userp_login='$_user_login' and userp_pass='$_user_pass'";
    	$result = $this->doSQL($q);
    	if($qrow=mysql_fetch_array($result))
    	{
    		if($qrow["userp_status"]==1)
    			return TRUE;
    		else
    			return FALSE;
    	}
    }
    
    	/**
    	* Update user details
    	*
    	* This will update details of the user to the system. Strict array names to be used.
    	* Only variables that are set will be updated.
    	* <br />Array use 
		* <br />$chdetail = (userp_login => xxxx,userp_pass==>xxxx, userp_name =>xxx,userp_last_time=>xxx, userp_last_sid );
    	* 
    	* @access public
    	* @param int $_uid  Unique identifier of login user
    	* @param array $_chdetail - detail of the row to be changed
    	* @return boolean return TRUE if field is updated, else FALSE
		*
		*/
	function update_User($_uid,$_chdetail) {
    	$_upsql='';
    	$_result='';
    	if(isset($_chdetail["userp_login"]))
	    	$_upsql="userp_login='".$_chdetail["userp_login"]."'";
    	if(isset($_chdetail["userp_pass"]))
	    	$_upsql="userp_pass='".$_chdetail["userp_pass"]."'";
    	if(isset($_chdetail["userp_name"]))
	    	$_upsql="userp_name='".$_chdetail["userp_name"]."'";
    	if(isset($_chdetail["userp_last_time"]))
	    	$_upsql="userp_last_time=".$_chdetail["userp_last_time"]."";
    	if(isset($_chdetail["userp_last_sid"]))
    		$_upsql="userp_last_sid='".$_chdetail["userp_last_sid"]."'";
    	
    	$_q="update user_profile set $_upsql where userp_uid=$_uid;";
    	$_result=$this->doUpdateSQL($_q);
    	if($_result>0)
    		return TRUE;
    	else
    		return FALSE;
    }
    
    	/**
    	* Get user id 
    	*
    	* This will retrieve the user ID using the user login. 
    	* 
    	* @access public
    	* @param string $_user_login - user login
		* @return mixed return unique user ID else return FALSE
    	*
    	*/
    function get_User($_user_login) {
    	
    	$q="select userp_uid from user_profile where userp_login='$_user_login'";
    	$result = $this->doSQL($q);
    	if($qrow=mysql_fetch_array($result))
    	{
    		return $qrow["userp_uid"];
    		
    	}
    	else
    		return FALSE;
    }
    
    	/**
    	* Init user data
    	*
    	* run this function to init the session
    	* 
    	* This will verify the user to the system and set the necessary permissions.
    	*
    	* Note*
    	* Pending Session standards  
    	*
    	* @todo pending on how to work on this to do a global call up for others.
    	* @access public
    	* @param string $userid - user id to be checked
    	* @param string $pass - password to be checked
    	* @return boolean return true if user is found, else FALSE
    	*
    	*/
    
    function init_Authuser($_userid,$_password){
    	//$cuser - user id for use in class
    	//$csite - user id for use in class
    	//$cu_cont - control lvl for use in class
    	$cuser=check_user($_userid,$_password);
    	
    	if($cuser)
    	{
    		$csite=verify_Site($_site_name);
    		if(isset($csite))
    		{
			$cu_cont=verify_Siteaccess($cuser,$ssite);
			//will set to session with suser,csite and cu_cont
    		}
    	}
    }
    
        /**
    	* Query user ID
    	*
    	* This will verify the user using the system cookie via the server database.
    	*
    	* *Note
    	* <br /> DATE_FORMAT is used via the sql query %e %M %Y - 7 January 2004
    	* 
    	* @access public
    	* @todo Need to finalize the date format to be use via UNIX or mysqlformat when being query.
    	* @param string $_sess_id user id to be check with the database table user_profile
    	* @return mixed return object if results are found else return FALSE
    	*/
    function query_UserID($_sess_id) {
    	//select DATE_FORMAT(userp_last_time, '%W %e %M %Y') AS userp_time from user_profile where userp_uid='1';
    	$q="select DATE_FORMAT(userp_last_time, '%e %M %Y %T') AS userp_last_time,userp_uid,userp_name,userp_pass,userp_login,userp_last_sid,userp_status,userp_country from user_profile where userp_uid='$_sess_id';";
    	//print $q;
    	$result = $this->doSQL($q);
    	if (mysql_num_rows($result)==1) {
    		$_result_object=mysql_fetch_object($result) ;
			return $_result_object;
		} else {
			return FALSE ;
		}
    }
    
    	/**
    	* Login in Status
    	*
		* Checks if user is logged in based on global $_SESSION["sUSERID"]
		*
		* @access public
		* @return boolean return true if session is found else false
		*/
	function isLoggedIn() {
		if (isset($_SESSION["sUSERID"]) && !empty($_SESSION["sUSERID"])) {
			// logged in
			return TRUE ;
		} else {
			// not logged in
			return FALSE ;
		}
	}
	
		/**
    	* Redirecting page
    	*
    	* This is use to rewrite the page header. 
    	* 
    	* Note*
    	* <br /> Now the default page is set to "index?s=", do change if needed
    	*
    	* @access public
    	* @todo might need to set this default page to a global var to set when being init
    	* @param string $_authsess user id to be check with the database table user_profile.
    	* @param string $_page page name that will be redirected to.
    	* @return mixed return object if results are found else return FALSE
    	*/
    	
	function go_page($_page) {
		$URL = "index.php?s=" . $_page ;
		if (!headers_sent()) {
			header("Location: $URL");
			exit ;
		}
		else {
			include_once("$_page.php");
		}
	}
	
		/**
    	* Auth User
    	*
    	* This can be use at any pages that require login. If not login, 
    	* it will be directed to the default page.
    	* 
    	* @access public
    	* @param string $_authsess user id to be check with the database table user_profile.
    	* @param string $_page page name that will be redirected to.
    	* @return mixed return object if results are found else return FALSE
    	*/
    	
    function authUser($_authsess,$_page) {
    	//$_profile=$this->query_UserID($_authsess) ;
    	if ($this->isLoggedIn()) {
			//query system via seesion id
			$_profile= $this->query_UserID($_authsess) ;
			//$this->go_page($_page);
			return $_profile;
		} 
		else {
			$_profile = "" ;
			if(!empty($_page))
				$this->go_page($_page);
		}
    }
    
    
}

?>
