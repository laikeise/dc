<?php
//ini_set('display_errors','1');
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | HTML Class Version 1                                              |
// +----------------------------------------------------------------------+
// | Copyright Synergy ITC Pte Ltd                                        |
// +----------------------------------------------------------------------+
// | Author: Jacky Ng <jackyng@synergyitc.com>                            |
// +----------------------------------------------------------------------+
// | Modified: marc 20070511
// +----------------------------------------------------------------------+
// $Id: HTML.php,v 1.4 2006/07/21 06:31:08 devcvs Exp $


/**
* @package HTML
*/
class HTML extends DB{

	/**
	* Displays the web page header
		* From <html> tag to <body> tag
	*
	* @access public
	*/
	function displayPageHeader() {
		global $_G;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">-->
<HTML><HEAD><TITLE>
<?
	print $_G["APP_VER"].". You are logged in as ".$_SESSION["sNick"].".";
?>
</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="theme0.css" type=text/css rel=stylesheet>

<script type="text/javascript">
var timeleft = 3600;

function AlertLogin(num) {
	AlertWindow = window.open("", "AlertWindow", 'width=350, height=180');
	str = "<p style=justify><span style=color:red><b>Your logged in session will end in " + num + " minutes. Please save all outstanding work before you are logged out.<br><br>Thank you for your attention!</b></span></p>";
	str += "<center><input type=button value=OK onClick='window.opener.focus(); window.close();'></center>";
	AlertWindow.document.write(str);
	AlertWindow.document.close();
};

if(timeleft - 15*60 > 0)
	// show popup if we have time
	self.setTimeout('AlertLogin(15)', 2699000);
	
if(timeleft - 10*60 > 0)
	// show popup if we have time
	self.setTimeout('AlertLogin(10)', 2999000);
	
if(timeleft - 5*60 > 0)
	// show popup if we have time
	self.setTimeout('AlertLogin(5)', 3299000);
	
</script>

<BODY bgColor=#FFFFFF leftMargin=0 topMargin=0 marginwidth="0" marginheight="0"><A name=pagetop></A>
<TABLE height="100%" cellSpacing=2 cellPadding=0 width="100%" align=center border=0>
<TBODY>
<TR>
	<TD vAlign=top><!-- Content Area Begin-->
<?
	}

	/**
	* Displays the web page footer
		* Closing </html> tag and </body> tag
	*
	* @access public
	*/
	function displayPageFooter() {
		Global $_G;
?>
</TD></TR>
		<TR>
		<!-- Modified by William on April 20, 2006. Display year using SESSION -->
		<TD class=ftitle align=center><? echo $_G["APP_VER"] ?></TD>
		</TR></TABLE><!-- Content Area End--></TD></TR>
<TR>
	<TD vAlign=bottom align=middle><!--<A class=TextLinkFooter href="pagetop">Back  to Top</A> -->
	
	<DIV class=TextFooter align=center>
	<FORM name=form><SPAN style="FONT-SIZE: 11px">You are logged in as 
	<B> 
<?
	print $_SESSION["sNick"];
?>
	</B></SPAN> <BR>Note : For security reasons, you will be automatically logged out in approximately
	<script type="text/javascript">
<!-- Begin
	var digclock = 3600;

	function clock() {
		digclock-- ;
		
		minute = Math.floor(digclock/60) 
		if (minute < 10 && minute > 0) minute = "0" + minute
		second = digclock % 60
		if (second < 10) second = "0" + second

		clockstr = minute + ' min and ' + second + ' sec ';

		if(minute < 0) {
			clockstr = "Session ended";
<?
	print "setTimeout('window.location = \"http://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?s=logout\";', 1000);";
?>
			
		} else
			setTimeout('clock()', 1000);
		document.form.button.value = clockstr;		
	}

	document.write("&nbsp;<input class=TimerFooter type=button value='----------------------------' name=button>");
	onError = null;
	clock();
// End -->
</script>
	</FORM>
	<!-- Modified by William on April 20, 2006. Display year using SESSION -->
	<BR>Copyright <? echo $_SESSION['sThisYear'] ?> <A href="http://www.synergyitc.com/">Synergy ITC Pte Ltd</A><BR>
	</DIV></TD></TR></TBODY></TABLE></BODY></HTML>
<?
	}
	
	/**
	* Displays the application's menu
	* Customize as necessary
	*
	* @access public
	*/
	function displayMenu() {
?>
	<script type="text/javascript" src="library.js"></script>
	<script type="text/javascript" src="Config.js"></script>
	
	<script type="text/javascript"><!--
			MENU_TYPE=1;
			WIDTH=Math.ceil((document.body.clientWidth-LEFT)/4)+1 ;
			Main_Parent_LayerColor="#014588";
	// --></script>
	<script type="text/javascript" src="Menu.js"></script>
	<BR><BR>
	<script type="text/javascript">
	<!-- Begin
	
	AddMenu("1",  "1",  "<b>Home</b> &nbsp;&nbsp;<img src=\"images/dropdown.gif\" border=0>",  "",  "", "index.php?s=admin", "");
		AddMenu("2",  "1",  "<b>View All Players</b> &nbsp;&nbsp;",  "",  "", "index.php?s=view_cust&tbn=cust_tb&as=view", "");
		AddMenu("17",  "1",  "<b>Add New Player</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  "",  "", "", "");
			AddMenu("26",  "17",  "<b>Tournament Player</b> ",  "",  "", "index.php?s=add_cust&tbn=cust_tb&as=adddata", "");
			AddMenu("35",  "17",  "<b>Guest Player</b> ",  "",  "", "index.php?s=add_cust&tbn=cust_tb&as=adddata&tp=gt", "");
		//AddMenu("22",  "1",  "<b>Tee-off Allocation</b> ",  "",  "", "index.php?s=add_mmteeoff&tbn=bus_list&as=view", "");
	
	AddMenu("4",  "4",  "<b>Reports</b> &nbsp;&nbsp;<img src=\"images/dropdown.gif\" border=0>",  "",  "", "", "");
		AddMenu("3",  "4",  "<b>Master List</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  "",  "", "", "");
			AddMenu("27",  "3",  "<b>Hotel Room Allocations</b> &nbsp;&nbsp;",  "",  "", "index.php?s=report_list3&tbn=cust_tb&as=view", "_");
			AddMenu("28",  "3",  "<b>Activities List</b> &nbsp;&nbsp;",  "",  "", "index.php?s=report_list&tbn=cust_tb&as=view", "_");
			AddMenu("29",  "3",  "<b>Handicap List</b> &nbsp;&nbsp;",  "",  "", "index.php?s=report_list2&tbn=cust_tb&as=view", "_");
			AddMenu("50",  "3",  "<b>Master Master List</b> &nbsp;&nbsp;",  "",  "", "index.php?s=report_master&tbn=cust_tb&as=view", "_");
			//AddMenu("25",  "3",  "<b>Export Master List</b> &nbsp;&nbsp;",  "",  "", "index.php?s=export_report_list&tbn=cust_tb&as=view", "_");
		AddMenu("23",  "4",  "<b>Airport Transfer</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  "",  "", "", "");
			AddMenu("32",  "23",  "<b>By Arrival / Departure Date</b> &nbsp;&nbsp;",  "",  "", "index.php?s=report_flight&tbn=cust_tb&as=view", "_");
			AddMenu("31",  "23",  "<b>By Arrival Date</b> &nbsp;&nbsp;",  "",  "", "index.php?s=report_flight3&tbn=cust_tb&as=view", "_");
			AddMenu("30",  "23",  "<b>By Departure Date</b> &nbsp;&nbsp;",  "",  "", "index.php?s=report_flight2&tbn=cust_tb&as=view", "_");
		AddMenu("5",  "4",  "<b>By Billing Group</b> &nbsp;&nbsp;",  "",  "", "index.php?s=report_group&tbn=cust_tb&as=view", "_");
		AddMenu("6",  "4",  "<b>Player Type Summary</b> &nbsp;&nbsp;",  "",  "", "index.php?s=report_player_type", "_"); 
			//AddMenu("37",  "4",  "<b>Tee-Off Report</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  ""  ,  ""  , "", "");
				//AddMenu("33",  "37",  "<b>Report By Location</b> &nbsp;&nbsp;",  ""  ,  ""  , "index.php?s=report_bus2&tbn=cust_tee_tb&as=view", "_");
				//AddMenu("34",  "37",  "<b>Report By Time</b> &nbsp;&nbsp;",  ""  ,  ""  , "index.php?s=report_bus&tbn=cust_tee_tb&as=view", "_");
			//AddMenu("4",  "4",  "<b>CSV Files &nbsp;&nbsp;&nbsp;</b> &nbsp;&nbsp;<img src=\"images/dropdown.gif\" border=0>",  ""  ,  ""  , "", "");
			//AddMenu("5",  "4",  "<b>View All CSV Files</b> &nbsp;&nbsp;",  ""  ,  ""  , "index.php?s=view_csv&tbn=csv_file&&as=view", "");
			//AddMenu("6",  "4",  "<b>View My CSV Files</b> &nbsp;&nbsp;",  ""  ,  ""  , "index.php?s=view&tbn=csv_file&as=view", "");
			//AddMenu("18",  "4",  "<b>Add New CSV</b> &nbsp;&nbsp;",  ""  ,  ""  , "index.php?s=add_csv&tbn=csv_file&as=adddata", "");
			AddMenu("36",  "4",  "<b>Blacklist</b> ",  "",  "", "index.php?s=report_blacklist&tbn=past_winners&as=view&view=blacklist", "");

	AddMenu("7",  "7",  "<b>Option</b> &nbsp;&nbsp;<img src=\"images/dropdown.gif\" border=0>",  "",  "", "", "");
		//AddMenu("8"  ,  "7",  "<b>Activity/Meal</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  "",  "", "index.php?s=view&tbn=ref_act_tb&as=view", "");
			//AddMenu("12"  ,  "8",  "<b>Add Activity/Meal</b> &nbsp;&nbsp;", "",  "", "index.php?s=add&tbn=ref_act_tb&as=adddata", "");
		//AddMenu("9"  ,  "7",  "<b>Handicapped</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  "",  "", "index.php?s=view&tbn=ref_handi_tb&as=view", "");
			//AddMenu("13"  ,  "9",  "<b>Add Handicapped</b> &nbsp;&nbsp;" ,  "",  "", "index.php?s=add&tbn=ref_handi_tb&as=adddata", "");
		//AddMenu("10"  ,  "7",  "<b>Country</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  "",  "", "index.php?s=view&tbn=ref_country_tb&as=view", "");
			//AddMenu("14"  ,  "10",  "<b>Add Country</b> &nbsp;&nbsp;" ,  "",  "", "index.php?s=add&tbn=ref_country_tb&as=adddata", "");
		//AddMenu("11"  ,  "7",  "<b>Room Type</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  "",  "", "index.php?s=view&tbn=ref_room_tb&as=view", "");
			//AddMenu("15"  ,  "11",  "<b>Add Room Type</b> &nbsp;&nbsp;" ,  "",  "", "index.php?s=add&tbn=ref_room_tb&as=adddata", "");
		AddMenu("19",  "7",  "<b>View Log</b> &nbsp;&nbsp;",  "",  "", "index.php?s=view_log&tbn=delete_log&as=view&ls=50", "");
		//AddMenu("20",  "7",  "<b>Bus Schedule</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  "",  "", "index.php?s=view&tbn=bus_list&as=view", "");
			//AddMenu("21",  "20",  "<b>Add Bus Schedule</b> ",  "",  "", "index.php?s=add&tbn=bus_list&as=adddata", "");
		//AddMenu("24",  "7",  "<b>Venue</b> &nbsp;&nbsp;<img src=\"images/dropdownright.gif\" border=0>",  "",  "", "index.php?s=view&tbn=ref_venue_tb&as=view", "");	
			//AddMenu("25",  "24",  "<b>Add Venue</b> ",  "",  "", "index.php?s=add&tbn=ref_venue_tb&as=adddata", "");
		AddMenu("38",  "7",  "<b>View Lastlog</b> &nbsp;&nbsp;",  "",  "", "index.php?s=view_lastlog&tbn=user_profile&as=view", "");
		AddMenu("39",  "7",  "<b>View Past Players</b> &nbsp;&nbsp;",  "",  "", "index.php?s=view_past_players&tbn=past_players&as=view", "");
		AddMenu("40",  "7",  "<b>View Past Winners</b> &nbsp;&nbsp;",  "",  "", "index.php?s=view_past_winners&tbn=past_winners&as=view", "");

	AddMenu("16",  "16",  "<b>Logout</b> &nbsp;&nbsp;",  "",  "", "index.php?s=logout", "");

	Build();
	//  End -->
	</script>
<?
	}

	/**
	* Displays the application's top bar
		* Customize as necessary
	*
	* @access public
	*/
	function displayAppTopBar($title="") {
?>
<TABLE cellSpacing=0 cellPadding=2 width="100%" border=1>
		<TBODY>
		<!--
		<TR>
		<TD class=ftitle>
			<TABLE cellSpacing=0 cellPadding=2 
			width="100%" border=0>
			<TBODY>
			<TR vAlign=top>
				<TD vAlign=top align=left><SPAN class=TextHeader>-->
<?
				//print $title;
?>
			<!--
				</SPAN>&nbsp;&nbsp;</TD>
				<TD vAlign=top align=right rowSpan=2></TD></TR>
			<TR>
				<TD vAlign=bottom noWrap align=left>&nbsp;<SPAN 
				class=CurrentSelectedText><B>SELECTED :</B></SPAN> <SPAN 
				class=CurrentSelectedValue>All 
			Accounts</SPAN></TD></TR></TABLE></TD></TR>
			-->
		<TR>
		<TD class=tabtable>
<?
	}

	/**
	* Search Bar
	*
	* @access public
	*/
	function displayAppTopSearchBar() {
?>
			</td>
		</tr>
			<tr><td align=center><img src="images/MercedesBenzLogo.gif"><br/>
<?
			//print_r($_GET);
				
		/*
		if(!isset($_GET["player_type"])){
				$_GET=array_merge($_GET,array("player_type"=>""));
		}
		*/
		print "<form name=\"searchform\" method=\"get\" action=\"index.php?s=".($_GET["s"])."&tbn=cust_tb&as=view\">";
		print "<input type=hidden name=s value=\"".($_GET["s"])."\">";
?>
			<input type=hidden name=tbn value='cust_tb'>
			<input type=hidden name=as value='view'>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><select name="player_type" class=fcommentsdark>
					<option value="">-- Player Type --</option>
<?
					print "<option value=\"T\" ".($_GET["player_type"]=="T"?"selected":" ").">Tournament Player</option>";
					print "<option value=\"CC\" ".($_GET["player_type"]=="CC"?"selected":" ").">Country Captain</option>";
					print "<option value=\"GPRO\" ".($_GET["player_type"]=="GPRO"?"selected":" ").">Golf Pro</option>";
					print "<option value=\"GCOM\" ".($_GET["player_type"]=="GCOM"?"selected":" ").">Golf Committee</option>";
					print "<option value=\"O\" ".($_GET["player_type"]=="O"?"selected":" ").">Others</option>";
					print "<option value=\"G\" ".($_GET["player_type"]=="G"?"selected":" ").">Guest Player</option>";
?>
				</select>
<?
			//(isset($_GET["country"])?($_GET["country"]):"")
			print "<input type=hidden name=pg value=\"".((isset($_GET["pg"]))?$_GET["pg"]:1)."\">";
			$this->generate_search_list((isset($_GET["country"])?($_GET["country"]):""),"country","ref_country_tb","country_id,country_name",1);
			$this->generate_search_list((isset($_GET["transport"])?($_GET["transport"]):""),"transport","ref_transport_tb","transport_sh,transport_name",2);
			$this->generate_search_list((isset($_GET["room_type"])?($_GET["room_type"]):""),"room_type","ref_room_tb","room_short_form,room_name",2);
			$this->generate_search_cu_list((isset($_GET["arrival_date"])?($_GET["arrival_date"]):""),"arrival_date","cust_tb","arrival_date");
			$this->generate_search_cu_list((isset($_GET["depart_date"])?($_GET["depart_date"]):""),"depart_date","cust_tb","depart_date");
			print "<input type=\"submit\" name=\"Filter\" value=\"Filter\" class=fcommentsdark>";
			print " <INPUT class=fcommentsdark type=button value=\"Previous Filter\" onclick=\"window.location='".$_SESSION["returnURL"]."'\">";
?>
					</td>
			</tr>
			</table>
		</form>
			</td></tr>
					<TR>
		<TD class=tabtable>
<?
	}

	/**
	* Search Bar
	*
	* @access public
	*/
	function displayMenuList() {
		print "<br/>";
		print "<img src=\"images/next.gif\" border=0>";
		//print_r($_GET);
		print "<a href=\"".$_SERVER["REQUEST_URI"]."&pi=1\" target=\"blank\">Print Current</a>&nbsp;";
		
		$sortlist="";
		
		$tempsortlist=$_GET;
		while (list($key, $val) = each($tempsortlist)) {
			if($key=="s")
				$sortlist=$sortlist;
			else{
			$newdisplay=("&$key=$val");
			$sortlist=$sortlist.$newdisplay;
			}
		}
		reset($tempsortlist);
		while (list($key, $val) = each($tempsortlist)) {
			if($key=="ls")
				$sortlist2=$sortlist2;
			else if($key=="s"){
				$newdisplay2=("$key=$val");
				$sortlist2=$sortlist2.$newdisplay2;
			}
			else{
			$newdisplay2=("&$key=$val");
			$sortlist2=$sortlist2.$newdisplay2;
			}
		}
		
		//print "<img src=\"images/next.gif\" border=0>";
		//print "<a href=\"index.php?s=view_cust".$sortlist."&pi=1\"> Print Current</a>&nbsp; ";
		print "<img src=\"images/next.gif\" border=0>";
		print "<a href=\"index.php?s=view_cust".$sortlist."\"> Flight Details</a>&nbsp; ";
		print "<img src=\"images/next.gif\" border=0>";
		print "<a href=\"index.php?s=view_cust2".$sortlist."\"> Golf Details </a>&nbsp; ";
		print "<img src=\"images/next.gif\" border=0>";
		print "<a href=\"index.php?s=view_cust3".$sortlist."\"> Activities</a>&nbsp; ";
		//print "<img src=\"images/next.gif\" border=0>";
		//print "<a href=\"index.php?s=view_cust4".$sortlist."\"> Tee-off Time</a>&nbsp; ";
		$delrowresult= $this->dosearchSQL("del_cust_tb","","","inf");
		$delnumrow=mysql_num_rows($delrowresult);
		print "<img src=\"images/next.gif\" border=0>";
		print "<a href=\"index.php?s=view_cust&tbn=del_cust_tb&as=view\"> View Deleted Records ".(($delnumrow>0)?"($delnumrow)":"")."</a>&nbsp; ";
		print "<img src=\"images/next.gif\" border=0>";
		print " View in <a href=\"index.php?".$sortlist2."&ls=10\">10</a> | <a href=\"index.php?".$sortlist2."&ls=20\">20</a> | <a href=\"index.php?".$sortlist2."&ls=30\">30</a> | <a href=\"index.php?".$sortlist2."&ls=40\">40</a>&nbsp; ";
		print "<br/><br/>";
	}
		
	/**
	* Generate a combo list as according using the def references table
	*
	* @param string sel use POST data
	* @param string listname name to go be given tot the select tab
	* @param string tbname name of table to use.
	* @param string colname col to be call out.
	* @param int vertype 1 for reference table without short name, 2 for reference table with short name
	* @access public
	*/
	function generate_search_list($sel="",$listname="",$tbname="",$colname="",$vertype="") {
		$htmldisrep=array("player_type" => "Player Type", "transport"=>"Transport", "room_type"=>"Room Type","country"=>"Country");
		//print $sel;
		print "<select name='$listname' class=fcommentsdark>";
		print "<option value='' $sel " . ($sel==""?"selected":" ") ." >- ". (!empty($htmldisrep["$listname"])?$htmldisrep["$listname"]:$listname) ." -</option>";
		$rm_result = $this->dosearchSQL("$tbname","","2","inf","$colname");
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_rm_name=$rm_row[1];
			$db_rm_id=$rm_row[0];
				if($vertype==1)
					echo "<option value='" .$db_rm_name. "' " . ($db_rm_name==$sel?"selected":" ") ." >".$db_rm_name."</option>\n";
				else if($vertype==2)
					echo "<option value='" .$db_rm_id. "' " . ($db_rm_id==$sel?"selected":" ") ." >".$db_rm_name."</option>\n";
		}
		
		if ($htmldisrep["$listname"] == 'Country')
			echo "<option value='Others' " . ('Others'==$sel?"selected":" ") ." >Others</option>\n";
			
		print "</select>";
	}
	
	/**
	* Generate a combo list from the customer table
	*
	* @param string sel use POST data
	* @param string listname name to go be given tot the select tab
	* @param string tbname name of table to use.
	* @param string colname col to be call out.
	* @param int vertype 1 for reference table without short name, 2 for reference table with short name
	* @access public
	*/
	function generate_search_cu_list($sel="",$listname="",$tbname="",$colname="",$vertype=1) {
		$htmldisrep=array("depart_date"=>"Departure Date", "arrival_date"=>"Arrival Date");
		//print $sel;
		print "<select name='$listname' class=fcommentsdark>";
		print "<option value='' $sel " . ($sel==""?"selected":" ") ." >- ". (!empty($htmldisrep["$listname"])?$htmldisrep["$listname"]:$listname) ." -</option>";
		//$rm_q="select country_id ,country_name from ref_country_tb";
		//print $rm_q;
		//$rm_result = $this->dosearchSQL("$tbname","","2","inf","$colname");
		$rm_result=$this->doSQL("select cust_id,$colname from $tbname group by $colname");
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_rm_name=$rm_row[1];
			$db_rm_id=$rm_row[0];
				if($vertype==1)
					print "<option value='" .$db_rm_name. "' " . ($db_rm_name==$sel?"selected":" ") ." >".(($db_rm_name!="0000-00-00")?strftime("%d-%m-%Y", strtotime($db_rm_name)):"nil")."</option>";
				else if($vertype==2)
					print "<option value='" .$db_rm_id. "' " . ($db_rm_id==$sel?"selected":" ") ." >".$db_rm_name."</option>";
		}
		print "</select>";
	}
	
	function displayPlainHeader(){
		Global $_G;
        if ($_GET['export']=="excel") return;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">-->
<HTML><HEAD><TITLE>
<?
		print $_G["APP_VER"].". You are logged in as ".$_SESSION["sNick"].".";
?>
</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="theme0.css" type=text/css rel=stylesheet>
	<script type="text/javascript" src="library.js"></script>
	<script type="text/javascript" src="Config.js"></script>
	<script type="text/javascript" src="Menu.js"></script>
</HEAD>
<BODY bgColor=#FFFFFF leftMargin=0 topMargin=0 marginwidth="0" marginheight="0"><TABLE height="100%" cellSpacing=2 cellPadding=0 width="100%" align=center border=0>
<TR><TD vAlign=top>
	<TABLE cellSpacing=0 cellPadding=2 width="100%" border=<?=(preg_match('/^report_/',$_REQUEST["s"])?"0":"1")?>>
		<TBODY>
		<TR>
		<TD class=tabtable vAlign=top>
<?
	}
	
	function displayPlainFooter(){
        if ($_GET['export']=="excel") return;
?>
</div>
		</TD></TR>
		<TR>
		<!-- Modified by William on April 20, 2006. Display year using SESSION -->
		<TD class=ftitle align=center>MercedesTrophy Asian Final <? echo $_SESSION['sThisYear'] ?> Registration System</TD>
		</TR></TABLE>
		
<?
	}
	
	function displayBasicMenu($title="") {
?>
	<script type="text/javascript" src="library.js"></script>
	<script type="text/javascript" src="Config.js"></script>
	
	<script type="text/javascript"><!--
			MENU_TYPE=1;
			WIDTH=Math.ceil((document.body.clientWidth-LEFT)/4)+1 ;
			Main_Parent_LayerColor="#014588";
	--></script>
	<script type="text/javascript" src="Menu.js"></script>
	<BR><BR>
	
	<script type="text/javascript">
	<!-- 
	AddMenu("1"  ,  "1"   ,  "<b>Back to Home Page</b> &nbsp;&nbsp;",  ""  ,  ""  , "index.php?s=admin", "");
	AddMenu("2"  ,  "2"   ,  "<b></b> &nbsp;&nbsp;"       	 ,  ""  ,  ""  , "", "");
	AddMenu("3"  ,  "3"   ,  "<b></b> &nbsp;&nbsp;"       	 ,  ""  ,  ""  , "", "");
	AddMenu("4"  ,  "4"   ,  "<b>Logout</b> &nbsp;&nbsp;" ,  ""  ,  ""  , "index.php?s=logout_confirm", "");
	//AddMenu("5"  ,  "1"   ,  "<b>Add New Customer</b> &nbsp;&nbsp;"       	 ,  ""  ,  ""  , "", "");
	Build();
	-->
	</script>        
	
	<TABLE cellSpacing=0 cellPadding=2 width="100%" border=1>
		<TBODY>
		<TR>
		<TD class=ftitle>
			</TD></TR>
			
		<TR>
		<TD class=tabtable>  
<?
	}
}
?>
