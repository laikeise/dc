<?php 
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;
$_GET["as"]="view";
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
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	$tempshowitem=array();
	
	//adding of new display list
	$tbcount=count($tblist);
	$newtbcount=$tbcount;
	//to add a new item
	
	$newitem=array("1"=>"Arrival",
	"2"=>"Departure"
	/*
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
	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
   	//end of reconstruction of the get list
	
	//$newdisplay=array("$newtbcount"=>"Arrival");
	//$tblist=array_merge($tblist,$newdisplay);
	
	print "<table width='100%' border='1' cellspacing='0' cellpadding='0'><tr>";
	while (list($key, $val) = each($tblist)) {
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
   			if($val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'||$val=='depart_date'||$val=='depart_time'||$val=='depart_flight')
   			{
   				$tempshowitem=array_merge($tempshowitem,array($key=>0));
   			}
   			else{
				if($val==$tmpcolnum)
				{
					$tmpcolnum=$key;
				}
				$tempshowitem=array_merge($tempshowitem,array($key=>1));
   				
   				//used to do the arrow up and down for the sort <Start>
   				if(isset($_GET["st"]))
   				{
   					if($_GET["st"]=="$val"){
   						if(isset($_GET["od"])){
   							if($_GET["od"]=="asc"){
   								print  "<td valign=top class=ftitle1><a class=ftitle1 href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
								print  "<img src=\"images/arrow_ASC.gif\">";}
							else{
								print  "<td valign=top class=ftitle1><a class=ftitle1 href=\"index.php?".$sortlist."&st=$val&od=asc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   								print  "<img src=\"images/arrow_DESC.gif\">";}
   						}
   					}
   					else
   						print  "<td valign=top class=ftitle1><a class=ftitle1 href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   				}
   				else
   					print  "<td valign=top class=ftitle1><a class=ftitle1 href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   				//used to do the arrow up and down for the sort <End>
   					
   				print  "</td>";
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
	
	if(isset($_GET["od"]))
		$orderlist=$_GET["od"];
	else
		$orderlist="DESC";
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
	//doing order by
	//$searchcol="$tblist[0]";
	if(($tmpstname)){
		//print "true";
		if($tmpstname=="Arrival"){
			$searchcol="arrival_date $orderlist";
		}
		else if($tmpstname=="Departure"){
			$searchcol="depart_date $orderlist";
		}else{
			$searchcol="$tmpstname $orderlist";}
	}
	else{
		$searchcol="$tblist[0] $orderlist";
	}
	
	//print $searchcol;
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$searchcol","$pointer,$list");
	}
	else if(isset($_GET["Filter"])){
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		//$wherecol="";
		//print_r($HTTP_GET_VARS);
		//print $HTTP_GET_VARS["country"];
		//[player_type] => CC [country] => Malaysia [transport] => HL [room_type] => D 
		if(($_GET["player_type"]))
			$wherecol=$wherecol."player_type=\"".$_GET["player_type"]."\" and";
		if(($_GET["country"]))
			$wherecol=$wherecol." country_name=\"".$_GET["country"]."\" and";
		if(($_GET["transport"]))
			$wherecol=$wherecol." transport like \"%".$_GET["transport"]."%\" and"; 			
		if(($_GET["room_type"]))
			$wherecol=$wherecol." room_short_form=\"".$_GET["room_type"]."\" and";
		if(($_GET["arrival_date"]))
			$wherecol=$wherecol." arrival_date=\"".$_GET["arrival_date"]."\" and";
		if(($_GET["depart_date"]))
			$wherecol=$wherecol." depart_date=\"".$_GET["depart_date"]."\" and";
		//$wherecol=$wherecol."family_name!=\"\"";
		$wherecol=substr($wherecol,0,-3);
		//print $wherecol;
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","$pointer,$list");
		$_SESSION["returnURL"]=$_SERVER["REQUEST_URI"];
		//print ;
	}
	else{
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		//$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","$pointer,$list");
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","inf");
		$_SESSION["returnURL"]=$_SERVER["REQUEST_URI"];
	}
	$summaryarray1=array("$tmptb AS a","$wherecol and a.","a.$searchcol","inf", "*","");
	$summaryarray2=array("$tmptb AS a","$wherecol ","a.$searchcol","inf", "*","");
	//$summaryarray1=array("$tmptb","$wherecol","$searchcol","inf","*");
	//$summaryarray2=array("$tmptb","$wherecol ","$searchcol","inf","*");
	//$resultx= $User_Info->dosearchSQL("$tmptb","","$tblist[0]","$pointer,$list");
	$g=1;
	while($qrow=mysql_fetch_array($resultx))
	{
		//print "test";
		//$sub=ereg_replace ("\n", "&lt;br /&gt;", $row['rob_subject']);
		print "<tr height=65>";
		
		for($i=0;$i<$newtbcount;$i++) {
			//print $i;
			//print_r($tblist);
			if($tempshowitem[$i]==1)
			{
				if($i<$tbcount)
   					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				else if($i>=$tbcount){
   					if($tblist[$i]=="Arrival"){
   						//$val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'
   						//echo strftime("%d-%m-%y", strtotime($qrow["arrival_date"]));
   						$aflight=explode(":~:",$qrow["arrival_flight"]);
   						$temptxt=(($qrow["arrival_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["arrival_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["arrival_time"]))."<br/>".$aflight[0].(isset($aflight[1])!=""?("<br/>".$AustGolf_Addon->get_airport_name($aflight[1])):"").""):"");
   					}
   					else if($tblist[$i]=="Departure"){
   						//$val=='depart_date'||$val=='depart_time'||$val=='depart_flight'
   						$dflight=explode(":~:",$qrow["depart_flight"]);
   						$temptxt=(($qrow["depart_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["depart_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["depart_time"]))."<br/>".$dflight[0].(isset($dflight[1])!=""?("<br/>".$AustGolf_Addon->get_airport_name($dflight[1])):"").""):"");
   					}
   					else
   						$temptxt="$tblist[$i]";
   				}
   				//to replace the cust id with a default no.
   				if($i==0){
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
	   			}
	   			else if($i==4){
	   				//player type
   					$htmltxt=$AustGolf_Addon->get_player_name($temptxt);
	   			}
	   			else if($i==6){
	   				//date stay value
   					$htmltxt=html_entity_decode($temptxt);
   					if(((int)($htmltxt))<0)
   						$htmltxt="Invalid Dates";
	   			}
	   			else if($i==13){
	   				//transport
	   				$temptp=explode(":~:",$temptxt);
   					$htmltxt=$AustGolf_Addon->get_transport_name($temptp["0"])."<br/>".(isset($temptp["1"])?$temptp["1"]:"");
	   			}
	   			else if($i==14){
	   				//Room type
   					$htmltxt=$AustGolf_Addon->get_room_name($temptxt);
	   			}
	   			else if($i==15){
	   				//Comment
	   				$htmltxt=html_entity_decode($temptxt);
	   				if($htmltxt!=="")
   						$htmltxt="<img src='images/Subscribers.gif' alt=\"".$htmltxt."\">";
   					
	   			}
   				else
   					$htmltxt=html_entity_decode($temptxt);
   				
   				if($htmltxt=="0"||$htmltxt==""||$htmltxt=="<br/>")
   				{
   						$htmltxt="&nbsp;-";
   				}
   				echo "<td valign=top class=fname1>".$htmltxt."</td>\n";
   			}
		}
		
		print "<td valign=top class=fname1><a href=".$_SERVER["PHP_SELF"]."?s=view_record&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view>[View]</a>";
		print "<br/><a href=".$_SERVER["PHP_SELF"]."?s=edit_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit>[Edit]</a>\n";
		//print "<a href=# onclick='return delWindow(\"s=delete_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\");return false;' >";
		if($qrow["player_type"]=="T"||$qrow["player_type"]=="G"||$qrow["player_type"]=="CC")
			print "<br/><a href=".$_SERVER["PHP_SELF"]."?s=view_teeoff&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view>[Tee-off]</a>\n";
		print "<br/><br/><a href=# onclick='return delWindow(\"s=delete_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\",\"".urlencode($qrow["family_name"]." ".$qrow["first_name"])."\");return false;' >";
		print "[Delete]</a><br/>";
		$act_q="select act_ref_id, act_name, act_description,act_default,act_date from ref_act_tb;";
			//$act_result = $User_Info->dosearchSQL("ref_act_tb","","","inf");
    		//print $act_q."<br>";
			$act_result = $User_Info->doSQL($act_q);
			while($rm_rowx=mysql_fetch_array($act_result))
			{
				$db_act_name=$rm_rowx["act_name"];
				//$db_act_date=$rm_rowx["act_date"];
				$db_act_ref_id=$rm_rowx["act_ref_id"];
				//$db_act_default=$rm_row["act_default"];
				//<input name="warmup" type="checkbox" id="warmup" value="checkbox">
				//$act_in_q="select * from cust_act_tb where cust_id=$tmpcolname and act_ref_id=$db_act_ref_id;";
				$act_in_q="select * from cust_act_tb where cust_id=".$qrow['cust_id']." and act_ref_id=$db_act_ref_id;";
				
				//print $act_q;
				$act_in_result = $User_Info->doSQL($act_in_q);
				if($act_row=mysql_fetch_array($act_in_result))
				{
					print $db_act_ref_id." is inited!<br/>";
				}
				else
				{
					if($db_act_ref_id>4){
						$actsql= "insert into cust_act_tb values (NULL,$db_act_ref_id,1,".$qrow['cust_id'].",\"\");";
						print $actsql."<br/>";
						$updarow=$updarow+($User_Info->doUpdateSQL($actsql));
					}
				}
				
			}
		print "<br /></td>\n";
		print "</tr>";
	}
	
	print "</table>";

?><br /><br />
        <TABLE border=0 align="center">
              <TBODY>
              <TR vAlign=top>
              <td class=fname1 colspan=3 align=center >Overall Total</td>
              <!--<td class=fname1 colspan=3 align=center >Current Page Total</td>-->
              </tr>
              <TR vAlign=top>
              
  <td>
<?
$AustGolf_Addon->room_summary();
?>
</td>
<td>
<?
$AustGolf_Addon->transport_summary();
?>
</td>
<td>
<?
$AustGolf_Addon->activity_summary();
?>
</td>
<!--
  <td>
<?
//$AustGolf_Addon->room_summary($summaryarray1);
?>
</td>
<td>
<?
//$AustGolf_Addon->transport_summary($summaryarray2);
?>
</td>
<td>
<?
//$AustGolf_Addon->activity_summary($summaryarray2);
?>
</td>
-->
</TR>
</TBODY></TABLE>

<?php

if(!isset($_GET["pi"])){
$Paging->backpage($_GET["tbn"],$wherecol,$list);
$Paging->nextpage($_GET["tbn"],$wherecol,$list);
print "<br/><br/>";
$Paging->listpaging($_GET["tbn"],$wherecol,$list);
}
/*
}
else{
$Paging->backpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
$Paging->nextpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
print "<br/>";
$Paging->listpaging($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
}
*/

}
else
{
	$User_Info->go_page("error");
}
?>
