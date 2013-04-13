<?php 
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
	//print "<br/><a href='".$_SERVER["PHP_SELF"]."?s=admin'>Click here to return</a><br />";
?>
<br />
<?php 
		
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	$tmphdname=((isset($_GET["hd"]))?$_GET["hd"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
	$wherecol="";
   	//print $sortlist;
   	//end of reconstruction of the get list
	$tbcount=count($tblist);
	$newtbcount=$tbcount;
	//to add a new item
	/*
	$newitem=array("1"=>"Arrival",
	"2"=>"Departure"
	
	"3"=>"Activity",
	"4"=>"Qualifying<br/>Round",
	"5"=>"Country<br/>Final",
	"6"=>"Asian<br/>Final",
	
	);
	*/
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
	if(isset($_GET["pi"])){
		$today=getdate();
		print "<div align=right class=fname1>Printed by ".$_SESSION["sNick"]." on ".(strftime("%d %B %Y %r", $today[0])).".</div>";
	}
	print "<table width='100%' border='1' cellspacing='0' cellpadding='0'><tr>";
	while (list($key, $val) = each($tblist)) {
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
   			if($val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'||$val=='depart_date'||$val=='depart_time'||$val=='depart_flight'||$val=='room_short_form'||$val=='country_name'||$val=='day_stay'||$val=="transport"||$val=="comment")
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
	?>
		<td class=ftitle1>Bus Schedule</td>
	<?
	/*
		$acthandiresult= $User_Info->dosearchSQL("ref_handi_tb","","handi_name DESC","inf");
		$handicounter=mysql_num_rows($acthandiresult);
		$handicnt=array();
		while($acthandirow=mysql_fetch_array($acthandiresult))
		{
			$db_handi_name=$acthandirow["handi_name"];
			$db_handi_id=$acthandirow["handi_ref_id"];
			print "<TD class=ftitle1 vAlign=top align=center colspan=6>".($db_handi_name)."</TD>";
			$handicnt=array_merge($handicnt,array("$db_handi_id"));
		}**/
		//print_r($handicnt);
	?>
	
	<td valign=top class=ftitle1>Action</td>
	</tr>
	
                
	<?
	
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
	
	//$resultx = $User_Info->doSQL($qt);
	//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","","");
	
	if(isset($tmphdname)){
		$searchhdcol=$tmphdname;
	}
	
	//doing order by for the main cust_tb
	if(($tmpstname)){
		//print "true";
		if($tmpstname=="Arrival"){
			$searchcol="arrival_time $orderlist";
		}
		else if($tmpstname=="Departure"){
			$searchcol="depart_time $orderlist";
		}else{
			$searchcol="$tmpstname $orderlist";}
	}
	else{
		$searchcol="$tblist[0] $orderlist";
	}
	
	
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$searchcol","$pointer,$list");
	}
	else if(isset($_GET["Filter"])){
		$wherecol="";
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
	}
	else{
		$resultx= $User_Info->dosearchSQL("$tmptb","","$searchcol","$pointer,$list");
	}
	
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
		print "<td valign=top class=fname1>";
		
	$cust_tee_result= $User_Info->dosearchSQL("cust_tee_tb",("cust_id=".$qrow["cust_id"]),"","inf");
	if($cust_tee_row=mysql_fetch_array($cust_tee_result))
	{
	?>
	<table width=100%>
	<tr>
	<td class=ftitle1 vAlign=top align=center>Date&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center>Time&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center>Venue&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center>Tee Group&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center>Action&nbsp;</td>
	</tr>
	<?
		mysql_data_seek($cust_tee_result,0);
		
		while($cust_tee_row=mysql_fetch_array($cust_tee_result)){
	?>
		<tr>
		<td class=fname1 vAlign=top align=center><?=strftime("%d-%m-%Y", strtotime($cust_tee_row["tee_date"]))?>&nbsp;</td>
		<td class=fname1 vAlign=top align=center><?=strftime("%H:%M", strtotime($cust_tee_row["tee_time"]))?>&nbsp;</td>
		<td class=fname1 vAlign=top align=center><?=$cust_tee_row["tee_venue"]?>&nbsp;</td>
		<td class=fname1 vAlign=top align=center><?=$cust_tee_row["tee_hole"]?>&nbsp;</td>
		<td class=fname1 vAlign=top align=center><?
		print "<a href=# onclick='return delWindow(\"s=view_teeoff&tbn=cust_tee_tb&id=".$cust_tee_row["tee_id"]."&tcol=0&as=deldata&ltxt=1&pi=1\",\"".$cust_bus_info_row["bus_name"]." on ".strftime("%d-%m-%Y", strtotime($cust_tee_row["tee_date"]))."\");return false;' >";
		print "[Delete]</a>";
		?></td>
		</tr>
	<?
	
		}
	?>
	</table>
	<?
	}
	?><br/>&nbsp;
		</td>
		<?
		
		print "<td valign=top class=fname1><a href=".$_SERVER["PHP_SELF"]."?s=edit_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit>[Edit]</a>";
		//if($qrow["player_type"]=="T"||$qrow["player_type"]=="G"||$qrow["player_type"]=="CC")
		//print "<br/><a href=".$_SERVER["PHP_SELF"]."?s=view_teeoff&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view>[Tee-off]</a>\n";
		print "</td>\n";
		
	}
	print "</table>";
?><br /><br />


<?php
if(!isset($_GET["pi"])){
$Paging->backpage($_GET["tbn"],$wherecol,$list);
$Paging->nextpage($_GET["tbn"],$wherecol,$list);
print "<br/>";
$Paging->listpaging($_GET["tbn"],$wherecol,$list);
}
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