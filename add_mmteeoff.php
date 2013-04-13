<?php 
include_once("AustGolf_Addon.php");
include_once("shortbusname.php");
$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
$tblist=$User_Info->getFieldName("$tmptb");

$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;

if ($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])) {
	$entry=0;
	//print "<br/><a href='".$_SERVER["PHP_SELF"]."?s=admin'>Click here to return</a><br />";
if (isset($_POST["TeeAddMulti"])) {
		$updarow=0;
		//print "test ";
		//print_r($_POST);
		//print $_POST["teeoff_player"];
		
?>
		<br/><br/><table align=center width=400>
		<tr><td >
<?
			if($_POST["tee_time_hr"]=="X"||$_POST["tee_time_min"]=="X"||$_POST["view_date"]==""||$_POST["tee_hole"]=="") {
				if($_POST["view_date"]=="")
					print "Please select Date option.<br/>";
				if($_POST["tee_time_hr"]=="X"||$_POST["tee_time_min"]=="X")
					print "Please select both Tee Off hour and minutes option.<br/>";
				//if($_POST["location"]=="")
					//print "Please select Location option.<br/>";
				if($_POST["tee_hole"]=="")
					print "Please enter Tee Group.<br/>";
				$entry=1;
			} else if(isset($_POST["players"])){
				//print "<table align=center width=100% border=1 >";
				print "<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
				print "<tr><td width=50% class=ftitle1>";
				$date_location=explode(":~:",$_POST["view_date"]);
				print "&nbsp;Date :</td><td width=50% class=fname1>&nbsp;".strftime("%d-%m-%Y",$date_location[0])."<br/>";
				print "</td></tr>";
				print "<tr><td width=50% class=ftitle1>";
				print "&nbsp;Time :</td><td width=50% class=fname1>&nbsp;".strftime("%H:%M",strtotime($_POST["tee_time_hr"].":".$_POST["tee_time_min"]))."<br/>";;
				print "</td></tr>";
				print "<tr><td width=50% class=ftitle1>";
				print "&nbsp;Location : </td><td width=50% class=fname1>&nbsp;".$date_location[1]."<br/>";
				print "</td></tr>";
				print "<tr><td width=50% class=ftitle1>";
				print "&nbsp;Tee Group : </td><td width=50% class=fname1>&nbsp;".$_POST["tee_hole"]."<br/>";
				print "</td></tr>";
				print "<tr><td width=50% colspan=2 class=fname1>";
				print "&nbsp;The following players have been allocated :- <br/>";
				$arrplayer=$_POST["players"];
				
				while (list($key, $val) = each($arrplayer)) {
					$val=explode(":~:",$val);
					print "&nbsp;".$val[1]." ".$val[2]."<br/>";
					$teesql= "insert into cust_tee_tb set cust_id=".$val[0].", tee_date=\"".strftime("%Y-%m-%d",$date_location[0])."\", tee_time=\"".$_POST["tee_time_hr"].":".$_POST["tee_time_min"]."\", tee_venue=\"".$date_location[1]."\", tee_hole=\"".$_POST["tee_hole"]."\", player_type=\"".$val[3]."\" ;";
					$updarow=$updarow+($User_Info->doUpdateSQL($teesql));
				}
				
				print "</td></tr>";
				print "</table>";
				print "<p align=center><INPUT type=button value=\"Add another Tee Off Group\" onclick=\"window.location='index.php?s=add_mmteeoff&tbn=bus_list&as=view'\">";
			} else
				print "No players selected! Please click back and refresh again.";
		
		?></td></tr></table><?
} else if(isset($_POST["ChangeTime"])) {
	if($_POST["new_tee_time_hr"]=="X"||$_POST["new_tee_time_min"]=="X"||$_POST["time_location"]=="") {
		if($_POST["new_tee_time_hr"]=="X"||$_POST["new_tee_time_min"]=="X")
			print "Please select both Tee Off hour and minutes option.<br/>";
		if($_POST["time_location"]=="")
			print "Please select Location option.<br/>";
		$entry=1;
	} else if(isset($_POST["time_location"])) {
		//print_r($_POST);
		$temptime_loc=explode(":~:",$_POST["time_location"]);
		$timelocsql= "update cust_tee_tb set tee_time=\"".$_POST["new_tee_time_hr"].":".$_POST["new_tee_time_min"]."\" where tee_time=\"".$temptime_loc[0]."\" and tee_venue=\"".$temptime_loc[1]."\" and tee_date=\"".$temptime_loc[2]."\" ;";
		//print $timelocsql;
		$updarow=$updarow+($User_Info->doUpdateSQL($timelocsql));
		//print $updarow;
		
		print "<div align=center>Your changes have been made to all the following!";
		print "<br/>Location : ".$temptime_loc[1];
		print "<br/>Time : ".strftime("%H:%M",strtotime($temptime_loc[0]));
		print "<br/>Date : ".strftime("%d-%m-%Y",strtotime($temptime_loc[2]));
		print "</div>";
	}
	
} else {
	$entry=1;
}
//(isset($_POST["View"]))

if($entry==1) {
	print "<table align=center width=60%><tr><td align=center><br/><br/><br/><br/><br/><br/><br/><br/>";
	
	$AustGolf_Addon->generate_cust_list($qrow["bus_id"]);
	print "</td></tr></table>";
}
} else {
	$User_Info->go_page("error");
}
?>