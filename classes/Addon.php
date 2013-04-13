<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | API Ver 5 Addon Class                                              |
// +----------------------------------------------------------------------+
// | Copyright Synergy ITC Pte Ltd                                        |
// +----------------------------------------------------------------------+
// | Author: Jacky Ng <jackyng@synergyitc.com>                            |
// +----------------------------------------------------------------------+
//
// $Id: Addon.php,v 1.1 2006/04/20 08:11:05 devcvs Exp $

/**
* @package Addon
*/
class Addon extends DB{

	/**
    * Create page listing list as according to 
	*
    * >> 1 2 3 4 5 6 7
	* 
    * @access public
    * @param array $tbname name of the table to retrive
    * @param array $searchtag sql query parameters for the where section.
    * @param array $range the range of values to get
    *
    */

	function listpaging($tbname,$searchtag,$range)
	{
	if($searchtag!=""){
		$tempsql_page="select count(*) from $tbname where $searchtag;";
	}
	else{
		$tempsql_page="select count(*) from $tbname;";
	}
	
	//$tempsql_page="select count(*) from rob_arena_board where rob_type='$msg_type' and month(rob_date)=$dm group by rob_date;";
	
	$page_result=$this->doSQL($tempsql_page,"select");
	
	if ($page_row = mysql_fetch_array($page_result))
	{
		$page_count=0;
		$page_name=0;
		$page_total=$page_row[0];
//		use this if want to make display more than one page
//		print ($page_total/$range);

		//print ">> ";
		if(isset($_GET["pg"]))
			$currentpage=$_GET["pg"];
		else
			$currentpage=1;
				
		while($page_total>$page_count)
		{
			$togopage=$_SERVER["REQUEST_URI"];
			$page_name++;
			$sortlist="";
			$tempsortlist=$_GET;
			while (list($key, $val) = each($tempsortlist)) {
				if($key=="pg")
					$sortlist=$sortlist;
				else{
					$newdisplay=("&$key=$val");
					$sortlist=$sortlist.$newdisplay;
				}
			}
			
			if($currentpage!=$page_name)
				print "[<a href='".$_SERVER["SCRIPT_NAME"]."?".$sortlist."&pg=$page_name'>".$page_name."</a>]&nbsp;&nbsp;";
			//print "<a href='index.php?t=$t&dm=$dm&pg=$page_name'>".$page_name."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
			$page_count=$page_name*$range;
		}
		print "<br/><br/>";
		print "Displaying page ". $currentpage ." of ".$page_name." pages in total.<br/>";
	}
}

	/**
    * Create the next page pointer.
	*
    * next
	* 
    * @access public
    * @param array $tbname name of the table to retrive
    * @param array $searchtag sql query parameters for the where section.
    * @param array $range the range of values to get
    *
    */

function nextpage($tbname,$searchtag,$range)
{	
	if(isset($_GET["pg"]))
		$currentpage=$_GET["pg"];
	else
		$currentpage=1;
		
	settype($currentpage, "integer");
	
	if($currentpage==0)
	{$currentpage++;}
	$page_count=0;
	$page_name=0;
	$page_total=0;
	//print $currentpage;
	if($searchtag!=""){
		$tempsql_npage="select * from $tbname where $searchtag;";
	}
	else{
		$tempsql_npage="select * from $tbname;";
	}
	//print $tempsql_npage;
	$npage_result=$this->doSQL($tempsql_npage);
	//print $npage_result;
	$page_total = mysql_num_rows($npage_result);
	$page_count=$currentpage*$range;
	
	if($page_total>$page_count)
	{
		$page_name=$currentpage+1;
		$sortlist="";
		$tempsortlist=$_GET;
		while (list($key, $val) = each($tempsortlist)) {
			if($key=="pg")
				$sortlist=$sortlist;

			else{
			$newdisplay=("&$key=$val");
			$sortlist=$sortlist.$newdisplay;
			}
		}
		print "<a href='".$_SERVER["SCRIPT_NAME"]."?".$sortlist."&pg=$page_name'>[next page]</a>&nbsp;&nbsp;&nbsp;&nbsp;";

	}
}

	/**
    * Create the prev page pointer.
	*
    * back
	* 
    * @access public
    * @param array $tbname name of the table to retrive
    * @param array $searchtag sql query parameters for the where section.
    * @param array $range the range of values to get
    *
    */

function backpage($tbname,$searchtag,$range)
{
	if(isset($_GET["pg"]))
		$currentpage=$_GET["pg"];
	else
		$currentpage=1;
		
	settype($currentpage, "integer");
	
	$page_count=0;
	$page_name=0;
	$page_total=0;
	if($searchtag!=""){
		$tempsql_bpage="select * from $tbname where $searchtag;";
	}
	else{
		$tempsql_bpage="select * from $tbname;";
	}
	
	$bpage_result=$this->doSQL($tempsql_bpage);
	$page_total = mysql_num_rows($bpage_result);
	$page_count=$currentpage*$range;
	//print $page_total." ".$page_count;
	if($page_total>=$page_count||$page_total<=$page_count)
	{
		$page_name=$currentpage-1;
		if($page_name>0)
		{
			$sortlist="";
			$tempsortlist=$_GET;
			while (list($key, $val) = each($tempsortlist)) {
				if($key=="pg")
					$sortlist=$sortlist;
				else{
					$newdisplay=("&$key=$val");
					$sortlist=$sortlist.$newdisplay;
				}
			}
			print "<a href='".$_SERVER["SCRIPT_NAME"]."?".$sortlist."&pg=$page_name'>[previous page]</a>&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	}
}

function showmdate()
{
	$ctoday=mktime (date("G")+13,date("i")+8,0,date("m")  ,date("d"),date("Y"));
	$xdate=strftime ("%Y-%m-%d", $ctoday);
	//echo $xdate;
	return $xdate;
}

function showmtime()
{
	$ctoday=mktime (date("G")+13,date("i")+8,0,date("m")  ,date("d"),date("Y"));
	$xtime=strftime ("%H:%M", $ctoday);
	//$ctoday=getdate();
	//echo $xtime;
	return $xtime;
}



}

?>
