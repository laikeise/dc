<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | API Ver 5 DB Class                                                   |
// +----------------------------------------------------------------------+
// | Copyright Synergy ITC Pte Ltd                                        |
// +----------------------------------------------------------------------+
// | Author: Poh Leng Yee <lengyee@synergyitc.com>                        |
// |         Jacky Ng <jackyng@synergyitc.com>                            |
// +----------------------------------------------------------------------+
//
// $Id: DB.php,v 1.4 2006/04/20 09:00:35 devcvs Exp $

/**
* @package Base
*/
class DB {

    /**#@+
    * @access private
    * @var string
    */
	var $_db_host ;		// database server hostname
	var $_db_name ;		// database name
	var $_db_user ;		// database user
	var $_db_pass ;		// database password
	var $_db_pref ;		// database table prefix
	var $_fieldreturn ;
 
	/**
	* Set database configuration
	* 
    * This method must be called before using database
    *
	* @access public
    * @param string $host Database host
    * @param string $name Database name
    * @param string $user Database user name
    * @param string $pass Database user password
    * @param string $pref Prefix to use for each database table
	*/
	function setDBConfig($host,$name,$user,$pass,$pref) 
	{
		$this->_db_host = $host ; 
		$this->_db_name = $name ; 
		$this->_db_user = $user ;
		$this->_db_pass = $pass ;
		$this->_db_pref = $pref ;
	}

	/**
	* Opens persistent connection to database server
	*
    * Use $this->setDBConfig() to set config first
    *
	* @access public
    * @return boolean
	*/
	function connectDB() 
	{		
		// try to connect to database server
		if (! $result = mysql_pconnect(
					$this->_db_host,
					$this->_db_user, 
					$this->_db_pass) ) 
		{
			return FALSE ;
		}
		// try to select the database
		if (! mysql_select_db ($this->_db_name,$result) ) {
			return FALSE ;
		}
		// all is ok
        return TRUE ;
	}

	/**
	* Executes a SQL query
	*
	* @access public
    * @param string $todo SQL statement to be executed
    * @return array
	*/
	function doSQL($todo) 
	{
		if (! $result = mysql_query($todo)) 
		{
			$this->sqlLog("EE : ".$todo." hit this ".mysql_error());
			return FALSE ;
		}
		return $result ;
	}
	
	/**
	* Executes a update SQL query
	*
	* @access public
	* @param string $todo SQL statement to be executed
    * @return boolean
	*/
	function doUpdateSQL($_todo) 
	{
		$this->sqlLog($_todo);
		if (!$result = mysql_query($_todo)) 
		{
			$this->sqlLog("EE : ".$_todo." hit this ".mysql_error());
			return FALSE ;
		}
		return $result ;
	}
	
	/**
	* Return all the column as request from the table parameter name.
	*
	* @access public
	* @param string $intbname table name to be used to query
    * @return array
	*/
	
	function getFieldName($intbname="")
	{
		$_fieldreturn=array();
		$fields = mysql_list_fields($this->_db_name, "$intbname");
		$columns = mysql_num_fields($fields);
		for ($i = 0; $i < $columns; $i++) 
		{
			$_fieldreturn=array_merge($_fieldreturn,(array)mysql_field_name($fields,$i));
		}
		return $_fieldreturn;
	}
	
	/**
	* Return all the column as request from the table parameter name.
	*
	* @access public
	* @param string $intbname table name to be used to query
	* @param string $lssearch search field parameters
	* @param string $orderby field to be set as order
	* @param string $inlimit no of limits to be release
	* @param string $selectedrow the rows tat are required to be display.
    * @return array
	*/
	function dosearchSQL($intbname="",$lssearch="",$orderby="",$inlimit="",$selectedrow="*",$grpint="")
	{
		$todo="select $selectedrow from $intbname";
		if(!empty($lssearch))
			$todo=$todo." where $lssearch";
		if(!empty($grpint))
			$todo=$todo." group by $grpint";
		if(!empty($orderby))
			$todo=$todo." order by $orderby";
		if(!empty($inlimit))
			if($inlimit=="inf")
				$todo=$todo;
			else
				$todo=$todo." limit $inlimit";
		else
			$todo=$todo." limit 0,1";
		//echo $todo."<br/>";
		if (! $result = mysql_query($todo)) 
		{
			$this->sqlLog("EE : ".$todo." hit this ".mysql_error()." - ".$_SERVER["REQUEST_URI"]);
			return FALSE ;
		}
		return $result ;
	}
	
	function sqlLog($sqltxt)
	{
		if(!(mysql_query("insert into sql_log set sql_statement='".addslashes($sqltxt)."', user_id=\"".$_SESSION["sNick"]."\" ;")))
			return FALSE;
	}
	
}
?>
