<?
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | API Ver 5 Site Class                                                 |
// +----------------------------------------------------------------------+
// | Copyright Synergy ITC Pte Ltd                                        |
// +----------------------------------------------------------------------+
// | Author: Poh Leng Yee <lengyee@synergyitc.com>                        |
// |         Jacky Ng <jackyng@synergyitc.com>                            |
// +----------------------------------------------------------------------+
//
// $Id: Sitebak.php,v 1.1 2006/04/20 08:11:05 devcvs Exp $


/**
* @package Site
**/
	/**
	*	Site Class Information
	*
	*	Project:     API Ver 5
	*
	*	File:        Site.php
	*
    *	Database Tables
    *
	*	# Fields usage 
	*	# site_sid - Unique identifier for site profile
	*	# site_name - site name
	*	# site_address - site web address
	*	#
	*	CREATE TABLE site_profile (
	*	site_sid int(10) unsigned NOT NULL auto_increment,
	*	site_name varchar(50) NOT NULL default '',
	*	site_address varchar(100) NOT NULL default '',
	*	primary key(site_sid)
	*	)TYPE=MyISAM;
	*	INSERT INTO site_profile VALUES (NULL,'test1','www.test1.com');
	*
	* @author Jacky Ng {@link mailto:jackyng@synergyitc.com}
	* @todo create administration functions for the grp access and the database structures.
	* @version 1.0 
	* @copyright Synergy ITC Pte Ltd
*/
class Site extends Session {

	/**
    * Create new site details
	*
    * This will create a new site to the system. 
    * Both name and address details must be included.
    * <br />Array Usage
    * <br />$newdetail = (site_name => xxxx,site_address==>xxxx);
    * 
    * @access public
    * @param array $newdetail detail of the new site
    * @return boolean
    *
    */
    function create_Site($newdetail){
    	if(isset($newdetail[site_name])&&isset($newdetail[site_address]))
    	{
    		$q="INSERT INTO site_profile VALUES (NULL,'".$newdetail[site_name]."','".$newdetail[site_address]."'";
  		if(doUpdateSQL($q)>0)
	    		return TRUE;
	    	else
	    		return FALSE;
	}
    	else
	    	return FALSE;	
    }
    
    	/**
    	* Update site details
    	* 
    	* This will a update changes in site details
    	* 
    	* @access public
    	* @param array $newdetail - changed detail of the site
	* @return boolean - 
		*
		*/
    function update_Site($newdetail) {
    	if(isset($chdetail[site_name]))
	    	$upsql="site_name='".$chdetail[site_name]."'";
    	if(isset($chdetail[site_address]))
    		$upsql="site_address='".$chdetail[site_address]."'";
    	
    	$q="update site_profile set $upsql where userp_id=$uid";
    	
    	if(doUpdateSQL($q)>0)
    		return TRUE;
    	else
    		return FALSE;
    }
    
		/**
		* Delete site details
		* 
		* This will delete in site details
		* 
		* @access public
		* @param int $sid - site id
		* @param int $cont_lvl - control level of the permission(only centre admin is allow to delete)(get value from seesion)
	* @return boolean - 
		*
		*/
    function delete_Site($sid,$cont_lvl) {
    	if($cont_lvl=='1')
    	{
    		$q="DELETE t1,t2 FROM t1.site_profile t2.usite_cont WHERE site_sid=$sid";
    		if(doUpdateSQL($q)>0)
    			return TRUE;
    		else
    			return FALSE;
    	}
    	else
    		return FALSE;
    }
    
		/**
		* Verify site access
		* 
		* This will verify the site access 
		* 
		* @access public
		* @param strint $site_name - Site name
    * @return int - reture sid, if null then site dun exists
		*
		*/
    function verify_Site($site_name) {
    	$q="select site_sid from site_profile where site_address='$site_name';";
    	$result = $this->doSQL($q);
    	if($qrow=mysql_fetch_array($result))
    	{
    		$fgen_sid=$qrow[0];
    		return $fgen_sid;
    	}
    	else
    		return FALSE;
    }
    
		/**
		* Verify site access
		* 
		* This will verify the site access 
		* 
		* @access public
		* @param int $uid - Unique identifier user
		* @param int $sid - Unique identifier site
    * @return int - the access lvl of the sid
		*
		*/
    function verify_Siteaccess($uid,$sid) {
    	$q="select grp_id from usite_cont where userp_uid='$uid' and site_sid ='$sid';";
    	//print "<br>".$q;
    	$result = $this->doSQL($q);
    	if($qrow=mysql_fetch_array($result))
    	{
    		$fgen_lvl =$qrow[0];
    		return $fgen_lvl;
    	}
    	else
    		return FALSE;
    }
    
    	/**
		* Get table access
		* 
		* This will return the table that can be accessed by the user
		* 
		* @access public
		* @param int $controlid - Unique identifier of GRPID
    * @return string - the access lvl of the grp_id
		*
		*/
    function get_TBName($controlid) {
    	$q="select tb_access from group_profile where grp_id='$controlid';";
    	//print "<br>".$q;
    	$result = $this->doSQL($q);
    	if($qrow=mysql_fetch_array($result)){
    		$ftb_name =$qrow[0];
    		return $ftb_name;
    	}
    	else
    		return FALSE;
    }
    
    	/**
		* Query if access is allow for the grp
		* 
		* This will do the checks if the requested permission is allow
		* 
		* @access public
		* @param int $controlid - Unique identifier of GRPID
		* @param int $inttb - Name of table to be checked against
		* @param int $intaction - Action to be done
    * @return boolean
		*
		*/
    function check_TBAction($controlid,$inttb,$intaction) {
    	$q="select tb_access from group_profile where grp_id='$controlid' and grp_permission like '%$intaction%' and tb_access like '%$inttb%';";
    	//print "<br>".$q;
    	$result = $this->doSQL($q);
    	if($qrow=mysql_fetch_array($result)){
    		return TRUE;
    	}
    	else
    		return FALSE;
    }
}
?>
