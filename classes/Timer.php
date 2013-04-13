<?
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | API Ver 5 ScriptTimer Class                                                 |
// +----------------------------------------------------------------------+
// | Copyright Synergy ITC Pte Ltd                                        |
// +----------------------------------------------------------------------+
// | Author: Poh Leng Yee <lengyee@synergyitc.com>                        |
// |         Jacky Ng <jackyng@synergyitc.com>                            |
// +----------------------------------------------------------------------+
//

/* $Id: Timer.php,v 1.1 2006/04/20 08:11:05 devcvs Exp $ ScriptTimer.php,v 1.0 2004/01/08 $ */

/**
* @package ScriptTimer
*/

/**
	* ScriptTimer Class Information
	*
	* This class is use to track the time taken for a script to run.
	* It will then report the page and the refering page.
	*
	* To use this class the following have to be put at the init
	* <br />require_once("classes/Timer.php") ;
	* <br />$_G["SCRIPT_PRINT"] = "0";		// for timer print use boolean default is off
	* <br />$_G["SCRIPT_ALERT"] = "1";		// for timer alert use boolean default is on
	* <br />$_G["SCRIPT_ADMIN_EMAIL"] = "jackyng@synergitc.com";		// for timer alert use boolean default is on
	* <br />ScriptTimer::startTimer();
	* <br />This part the end of everything
	* <br />ScriptTimer::printTime($_G["SCRIPT_PRINT"],$_G["SCRIPT_ALERT"],$_G["SCRIPT_ADMIN_EMAIL"]);
	*
	* @author Jacky Ng {@link mailto:jackyng@synergyitc.com}
	* @todo the mailing function mail()
	* @version 1.0 
	* @copyright Synergy ITC Pte Ltd       
    **/
class ScriptTimer
{
	var $_default_email="nil@nil.com";
	var $_print=0;
	var $_alert=1;
	/**
    * Start Timer for Script
	*
    * The script timer will start clocking when this is called.
    *
    * 
    * @access public
    * @return boolean
    *
    */
	function startTimer ()
	{
		define ("TIMER_START_TIME", microtime());
		return true;
	}
   
   /**
    * Start Timer for Script
	*
    * The script timer will start clocking when this is called.
    *
    * 
    * @access public
    * @return boolean
    *
		*/
	function getTime ($decimals=2)
	{
		// $decimals will set the number of decimals you want for your milliseconds.
		// format start time
		$start_time = explode (" ", TIMER_START_TIME);
		$start_time = $start_time[1] + $start_time[0];
		// get and format end time
		$end_time = explode (" ", microtime());
		$end_time = $end_time[1] + $end_time[0];
		$_timeTaken=number_format ($end_time - $start_time, $decimals);
		return $_timeTaken;
	}
   
	function printTime($_print=0,$_alert=1,$_admin_email)
	{
		$_timetaken=ScriptTimer::getTime(3);
		//Timer test
		//$_timetaken=6;
		if($_print==1)
			print "<br />Time taken to load this page = ".$_timetaken." .<br />";
		if(empty($_admin_email))
			$_admin_email="";
			//$this->$_default_email;
		
		if($_timetaken>=6&&$_alert==1)
			ScriptTimer::alertAdmin($_timetaken,$_admin_email);
		
	}
   
	function alertAdmin($_time,$_admin_email)
	{
       	if($_time>=6)
			print"<br />Page : http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]." has taken <b>$_time</b> secs to load from ".$_SERVER["HTTP_REFERER"]."<br />";
		print $_admin_email;
	}
}
?>
