<?php 
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
	//print "<br/><a href='".$_SERVER["PHP_SELF"]."?s=admin'>Click here to return</a><br />";
?>
View Page
<br />
<?php
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	$tempshowitem=array();
	
	//adding of new display list
	$tbcount=count($tblist);
	$newtbcount=$tbcount;
	//to add a new item
	
	$newitem=array(
/*	
	"1"=>"File ",
	"2"=>"Departure"
	"3"=>"Activity",
	"4"=>"Qualifying<br/>Round",
	"5"=>"Country<br/>Final",
	"6"=>"Asian<br/>Final",
	*/
	);
	//print_r($newitem);
	
	while (list($key, $val) = each($newitem)) {
		$newtbcount=$newtbcount+1;
		$newdisplay=array("$newtbcount"=>"$val");
		$tblist=array_merge($tblist,$newdisplay);
	}
	
	//$newdisplay=array("$newtbcount"=>"Arrival");
	//$tblist=array_merge($tblist,$newdisplay);
	
	print "<table width='100%' border='1' cellspacing='0' cellpadding='0'><tr>";
	while (list($key, $val) = each($tblist)) {
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
   			if($val=='filetype')
   			{
   				$tempshowitem=array_merge($tempshowitem,array($key=>0));
   			}
   			else{
				if($val==$tmpcolnum)
				{
					$tmpcolnum=$key;
				}
				$tempshowitem=array_merge($tempshowitem,array($key=>1));
   				print  "<td valign=top class=ftitle1>" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</td>";
   			}
   			$rmtotal++;
	}
	//print_r($tempshowitem);
	print "<td valign=top class=ftitle1>Action</td>";
/*
	print "</tr><tr>";
	for($g=0;$g<=$rmtotal;$g++){
		if($g==$rmtotal)
			print  "<td valign=top class=ftitle1>last</td>";
		else if($tempshowitem[$g]==1)
			if($tblist[$g]=="Qualifying<br/>Round"){
				print  "<td valign=top class=ftitle1><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td valign=top class=ftitle1>DIV</td><td valign=top class=ftitle1>Init HCP</td><td valign=top class=ftitle1>Adj HCP</td></tr></table></td>";
			}
			else if($tblist[$g]=="Country<br/>Final"){
				print  "<td valign=top class=ftitle1>X2</td>";
			}
			else if($tblist[$g]=="Asian<br/>Final"){
				print  "<td valign=top class=ftitle1>X3</td>";
			}
			else
				print  "<td valign=top class=ftitle1>&nbsp;</td>";
		
	}
	//print $rmtotal;
	*/
	print "</tr>";	
	
	if(isset($_GET["pg"]))
		$page=$_GET["pg"];
	else
		$page=1;

	//page control listing by 10 now	
	if(isset($_GET["ls"]))
		$list=$_GET["ls"];
	else
		$list=10;
	/*
	if(!isset($page))
		$page=1;

	if(!isset($list))
		$list=10;
	*/
	$pointer=$page*$list-$list;	
	
	//$qt="select * from rob_arena_board order by rob_text_id limit $pointer,$list";
	//$resultx = $User_Info->doSQL($qt);
	//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","","");
	
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$tblist[0] ASC","$pointer,$list");
	}
	else{
		$resultx= $User_Info->dosearchSQL("$tmptb","","$tblist[0] ASC","$pointer,$list");
	}
	
	//$resultx= $User_Info->dosearchSQL("$tmptb","","$tblist[0]","$pointer,$list");
	$g=1;
	while($qrow=mysql_fetch_array($resultx))
	{
		//print "test";
		//$sub=ereg_replace ("\n", "&lt;br /&gt;", $row['rob_subject']);
		print "<tr>";
		
		for($i=0;$i<$newtbcount;$i++) {
			//print $i;
			//print_r($tblist);
			if($tempshowitem[$i]==1)
			{
				if($i<$tbcount)
   					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				else if($i>=$tbcount){
   					/*
   					if($tblist[$i]=="Arrival"){
   						//$val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'
   						//echo strftime("%d-%m-%y", strtotime($qrow["arrival_date"]));
   						$temptxt=strftime("%d-%m", strtotime($qrow["arrival_date"]))."<br/>".strftime("%H:%M", strtotime($qrow["arrival_time"]))."<br/>$qrow[arrival_flight]";}
   					else if($tblist[$i]=="Departure"){
   						//$val=='depart_date'||$val=='depart_time'||$val=='depart_flight'
   						$temptxt=strftime("%d-%m", strtotime($qrow["depart_date"]))."<br/>".strftime("%H:%M", strtotime($qrow["depart_time"]))."<br/>$qrow[depart_flight]";}
   					else
   						$temptxt="$tblist[$i]";
   					*/
   				}
   				//to replace the cust id with a default no.
   				if($i==0){
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
	   			}
	   			
	   			else if($i==1){
   					$htmltxt=$temptxt." &nbsp;<a target=_new href='".$_G["UPLOAD_URL"].$qrow[0]."'><img src=/images/viewfile.gif alt='View this file.' border=0 ></a>";
	   			}
	   			else if($i==5){
	   				//$temptxt=strftime("%d-%m", strtotime($qrow["depart_date"]))."<br/>".strftime("%H:%M", strtotime($qrow["depart_time"]))."<br/>$qrow[depart_flight]";}
   					//strftime("%d-%m", strtotime($qrow["depart_date"]))
   					//print $temptxt."<br/>";
   					//print substr($temptxt, 0, 8)." ".substr($temptxt, 8, 4);
   					$htmltxt=strftime("%d-%b-%Y %H:%M", strtotime((substr($temptxt, 0, 8)." ".substr($temptxt, 8, 4))));
   					//strtotime($temptxt)
   					//$htmltxt=$temptxt;
   					//$htmltxt=strftime("%d-%m", strtotime($temptxt));
   					//$htmltxt=$AustGolf_Addon->get_transport_name($temptxt);
	   			}
	   			else if($i==6){
					$htmltxt=strftime("%d-%b-%Y %H:%M", strtotime((substr($temptxt, 0, 8)." ".substr($temptxt, 8, 4))));
				}
	   			/*
	   			else if($i==13){
   					$htmltxt=$AustGolf_Addon->get_room_name($temptxt);
	   			}
	   			else if($i==14){
	   				$htmltxt=html_entity_decode($temptxt);
	   				if($htmltxt!=="")
   						$htmltxt="<img src='/images/Subscribers.gif' alt=\"".$htmltxt."\">";
	   			}
	   			*/
   				else
   					$htmltxt=html_entity_decode($temptxt);
   				echo "<td valign=top class=fname1>".$htmltxt."<br /></td>\n";
   			}
		}
		print "<td valign=top class=fname1><!--<a href=".$_SERVER["PHP_SELF"]."?s=edit_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit><img src='images/edit.gif' border=0 /></a><br />-->&nbsp;</td>\n";
		print "</tr>";
	}
	
	print "</table>";

?><br /><br />


<?php

$Paging->backpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
$Paging->nextpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
print "<br/>";
$Paging->listpaging($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
/*
$Paging->backpage($_GET["tbn"],"rob_type='blog'",$list);
$Paging->nextpage($_GET["tbn"],"rob_type='blog'",$list);
*/
//print "test";

}
else
{
	$User_Info->go_page("error");
}
?>