<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | API Ver 5 Auth Class                                                 |
// +----------------------------------------------------------------------+
// | Copyright Synergy ITC Pte Ltd                                        |
// +----------------------------------------------------------------------+
// | Author: Poh Leng Yee <lengyee@synergyitc.com>                        |
// |         Jacky Ng <jackyng@synergyitc.com>                            |
// +----------------------------------------------------------------------+
//
// $Id: Auth.php,v 1.1 2006/04/20 08:11:05 devcvs Exp $

/**
* @package Base
*/
class Auth extends Session {

	/**
	* @access private 
	* @var string
	*/
	var $_callback ;
	/**
	* @access private 
	* @var string
	*/
	var $_db_user ;		// user table prefix

	/** 
	* Sets the callback URL
	*
	* Sets the URL of the page to return to 
    * after login sequence
    *
    * @param string $c Callback URL, e.g. http://www.example.com/somewhere
	*/ 
	function setCallback($c) {
		$this->_callback = $c ;
	}


	/**
	* Set user table prefix to use
	*
	* @access public
    * @param string $prefix Defaults to "shared" if not specified
	*/
	function setUserTablePrefix($prefix="shared") {
		$this->_db_user = $prefix . "_sessions"; 
	}


	/**
	* Checks and returns one of 3 login status.
	*
	* If $req==0, LOGIN_NOT_REQUIRED is returned
	* If $req==1, either LOGIN_REQ_AND_LOGGED_IN  or 
	*		LOGIN_REQUIRED_BUT_NOT_OK is returned
	* Requires $this->isLoggedIn()
	*
	* @access public
    * @param int $req 0: login not required, 1: login required
	* @return int
	*/
	function getPageLogInStatus($req) {
		if ($req==1 && $this->isLoggedIn()==TRUE) {
			return LOGIN_REQ_AND_LOGGED_IN  ;
		} else if ($req==1 && $this->isLoggedIn()==FALSE) {
			return LOGIN_REQ_BUT_NOT_LOGGED_IN;
		} else if ($req==0 && $this->isLoggedIn()==TRUE) {
			return LOGIN_NOT_REQ_BUT_LOGGED_IN ;
		} else if ($req==0 && $this->isLoggedIn()==FALSE) {
			return LOGIN_NOT_REQ_AND_NOT_LOGGED_IN ;
		}
	}

	/**
	* Checks if user is logged in based on global $_SESSION["UID"]
	*
	* @access public
	* @return boolean
	*/
	function isLoggedIn() {
		if (isset($_SESSION["UID"]) && !empty($_SESSION["UID"])) {
			// logged in
			return TRUE ;
		} else {
			// not logged in
			return FALSE ;
		}
	}


	/**
	* Displays HTML log in box
	*
	* @access public
	* @return boolean
	*/
	function displayLoginBox() {
		$_callback = $this->_callback ;
		if (include_once("inc/loginbox.inc")) {
			return TRUE ;
		} else {
			return FASLE ;
		}
	}


	/**
	* Get the profile of the logged in user
	*
	* @access public
    * @param string $id UID of user to obtain profile for
    * @param string $ext Prefix for database table to get extended info of user 
	* @return boolean
	* 
	* remarks- pending to remove this functions as
	* it used to query a fixed table check for access
	*/
	
	function getProfileInfo($id,$ext="") {
		if (empty($ext)) {
			$q = "SELECT * FROM shared_users WHERE UID='$id'" ;
		} else {
			$q = "SELECT * FROM shared_users AS t1, $ext" . "_users_ext AS t2 WHERE t1.UID='$id' AND t1.ID=t2.ID" ;
		}
		$result = $this->doSQL($q) ;
		if (mysql_num_rows($result)==1) {
			return mysql_fetch_object($result) ;
		} else {
			return FALSE ;
		}
	}
	
    	
}


?>
