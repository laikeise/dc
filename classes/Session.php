<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | API Ver 5 Session Class                                              |
// +----------------------------------------------------------------------+
// | Copyright Synergy ITC Pte Ltd                                        |
// +----------------------------------------------------------------------+
// | Author: Poh Leng Yee <lengyee@synergyitc.com>                        |
// |         Jacky Ng <jackyng@synergyitc.com>                            |
// +----------------------------------------------------------------------+
//
// $Id: Session.php,v 1.1 2006/04/20 08:11:05 devcvs Exp $

/**
* @package Base
*/
class Session extends DB {

    /**
    * @access private
    * @var string
    */
	var $_db_sess ;		// session table prefix


	/**
	* Set session table prefix to use
	*
    * Defaults to "shared" if not specified
	*
	* @access public
    * @param string $prefix Prefix for database table to use for sessions
	*/
	function setSessionTablePrefix($prefix="shared") {
		$this->_db_sess = $prefix . "_sessions"; 
	}


	/**
	* Handler to open session
	*
    * The following db table should be created:
    * CREATE TABLE shared_sessions (
    *   ses_id varchar(32) NOT NULL default '',
    *   ses_time int(11) NOT NULL default '0',
    *   ses_start int(11) NOT NULL default '0',
    *   ses_value text NOT NULL,
    *   PRIMARY KEY  (ses_id)
    * ) TYPE=MyISAM;
    *
	* @access private
    * @return boolean
	*/
    function _open($path, $name) { 
        if ($this->connectDB()) { 
            return TRUE;
        } else {
            return FALSE;
        }
    } 

	/**
	* Handler to close session
	*
	* @access private
    * @return boolean
	*/
    function _close() { 
        /* This is used for a manual call of the 
           session gc function */ 
        $this->_gc(0); 
        return TRUE; 
    } 

	/**
	* Handler to read session info
	*
	* @access private
    * @return string
	*/
    function _read($ses_id) { 
        $session_sql = "SELECT * FROM " . $this->_db_sess 
                     . " WHERE ses_id = '$ses_id'"; 
		//print $session_sql."<br />";
        $session_res = @mysql_query($session_sql); 
        if (!$session_res) { 
            return ''; 
        } 

        $session_num = @mysql_num_rows ($session_res); 
        if ($session_num > 0) { 
            $session_row = mysql_fetch_assoc ($session_res); 
            $ses_data = $session_row["ses_value"]; 
            return $ses_data; 
        } else { 
            return ''; 
        } 
    } 

	/**
	* Handler to write session info to database
	*
	* @access private
    * @return boolean
	*/
    function _write($ses_id, $data) { 
        $session_sql = "UPDATE " . $this->_db_sess 
                     . " SET ses_time='" . time() 
                     . "', ses_value='$data' WHERE ses_id='$ses_id'"; 
        $session_res = @mysql_query ($session_sql); 
        if (!$session_res) { 
            return FALSE; 
        } 
        if (mysql_affected_rows ()) { 
            return TRUE; 
        } 
        $session_sql = "INSERT INTO " . $this->_db_sess 
                     . " (ses_id, ses_time, ses_start, ses_value)" 
                     . " VALUES ('$ses_id', '" . time() 
                     . "', '" . time() . "', '$data')"; 
        $session_res = @mysql_query ($session_sql); 
        if (!$session_res) {     
            return FALSE; 
        }         else { 
            return TRUE; 
        } 
    } 

	/**
	* Handler to destroy session info in database
	*
	* @access private
    * @return boolean
	*/
    function _destroy($ses_id) { 
        $session_sql = "DELETE FROM " . $this->_db_sess 
                     . " WHERE ses_id = '$ses_id'"; 
        $session_res = @mysql_query ($session_sql); 
        if (!$session_res) { 
            return FALSE; 
        }         else { 
            return TRUE; 
        } 
    } 

	/**
	* Handler to do garbage collection for session info
	*
	* @access private
    * @return boolean
	*/
    function _gc($life) { 
        $ses_life = strtotime("-60 minutes"); 
        $session_sql = "DELETE FROM " . $this->_db_sess 
                     . " WHERE ses_time < $ses_life"; 
        $session_res = @mysql_query ($session_sql); 
        if (!$session_res) { 
            return FALSE; 
        }         else { 
            return TRUE; 
        } 
    } 
}

?>
