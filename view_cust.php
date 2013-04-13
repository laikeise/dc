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
<br />
<?php 
		
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	$r_tblist = array_flip($tblist);
        // Remove fields
	$fs = array("fullname","dietary"/*,"hotelcheckindate","hotelcheckoutdate"*/,"pickuptime") ;
        foreach ($fs as $f) if (isset($r_tblist["$f"])) unset($tblist[$r_tblist["$f"]]);

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
	
/*
	while (list($key, $val) = each($newitem)) 
	{
		$newtbcount=$newtbcount+1;
		$newdisplay=array("$newtbcount"=>"$val");
		$tblist=array_merge($tblist,$newdisplay);
	}
*/

	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
   	//end of reconstruction of the get list
	
	//$newdisplay=array("$newtbcount"=>"Arrival");
	//$tblist=array_merge($tblist,$newdisplay);
	if(isset($_GET["pi"]))
	{
		$today=getdate();
		print "<div align=right class=fname1>Printed by ".$_SESSION["sNick"]." on ".(strftime("%d %B %Y %r", $today[0])).".</div>";
	}
	print "<table width='100%' border='1' cellspacing='0' cellpadding='0'><tr>";
	while (list($key, $val) = each($tblist)) {
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
   			/*if($val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'||$val=='depart_date'||$val=='depart_time'||$val=='depart_flight')
   			{
   				$tempshowitem=array_merge($tempshowitem,array($key=>0));
   			} else{
			*/
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
   							if($_GET["od"]=="asc")
   							{
   								print  "<td valign=top class=ftitle1><a class=ftitle1 href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
								print  "<img src=\"images/arrow_ASC.gif\">";
							}
							else
							{
								print  "<td valign=top class=ftitle1><a class=ftitle1 href=\"index.php?".$sortlist."&st=$val&od=asc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   								print  "<img src=\"images/arrow_DESC.gif\">";
   							}
   						}
   					}
   					else
   						print  "<td valign=top class=ftitle1><a class=ftitle1 href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   				}
   				else
   					print  "<td valign=top class=ftitle1><a class=ftitle1 href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   				//used to do the arrow up and down for the sort <End>
   					
   				print  "</td>";
   			//}
   			$rmtotal++;
	}
	
	print "<td valign=top class=ftitle1>Action</td>";
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

	$pointer=$page*$list-$list;	
	
	if(($tmpstname))
	{
		
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
	

	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
	}
	else if(isset($_GET["Filter"]))
	{
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		if(($_GET["player_type"]))
			$wherecol=$wherecol."player_type=\"".$_GET["player_type"]."\" and";
			
		if(($_GET["country"])) {
			if ($_GET['country'] != 'Others')
				$wherecol=$wherecol." country_name=\"".$_GET["country"]."\" and";
			else
				$wherecol=$wherecol." `country_name` NOT IN (SELECT `country_name` FROM `ref_country_tb`) and";
		}
		if(($_GET["transport"]))
			$wherecol=$wherecol." transport like \"%".$_GET["transport"]."%\" and"; 			
		if(($_GET["room_type"]))
			$wherecol=$wherecol." room_short_form=\"".$_GET["room_type"]."\" and";
		if(($_GET["arrival_date"]))
			$wherecol=$wherecol." arrival_date=\"".$_GET["arrival_date"]."\" and";
		if(($_GET["depart_date"]))
			$wherecol=$wherecol." depart_date=\"".$_GET["depart_date"]."\" and";
		$wherecol .= " `blacklist` != 1 AND";	
		$wherecol=substr($wherecol,0,-3);
	
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","$pointer,$list");
		$_SESSION["returnURL"]=$_SERVER["REQUEST_URI"];
	
	}
	else
	{
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","$pointer,$list");
		$_SESSION["returnURL"]=$_SERVER["REQUEST_URI"];
	}
	
	$summaryarray1=array("$tmptb AS a","$wherecol and a.","a.$searchcol","inf", "*","");
	$summaryarray2=array("$tmptb AS a","$wherecol ","a.$searchcol","inf", "*","");
	$g=1;
	while($qrow=mysql_fetch_array($resultx))
	{
		print "<tr height=65>";
		foreach ($tblist as $i => $colname)
		//for($i=0;$i<$newtbcount;$i++) 
		{
			//if($tempshowitem[$i]==1)
			//{
				//if($i<$tbcount)
   					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				/*else if($i>=$tbcount){
   					if($tblist[$i]=="Arrival")
   					{
   						$aflight=explode(":~:",$qrow["arrival_flight"]);
   						$temptxt=(($qrow["arrival_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["arrival_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["arrival_time"]))."<br/>".$aflight[0].(isset($aflight[1])!=""?("<br/>".$aflight[1]):"").""):"");
   					}
   					else if($tblist[$i]=="Departure")
   					{
   						$dflight=explode(":~:",$qrow["depart_flight"]);
   						$temptxt=(($qrow["depart_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["depart_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["depart_time"]))."<br/>".$dflight[0].(isset($dflight[1])!=""?("<br/>".$dflight[1]):"").""):"");
   					}
   					else
   						$temptxt="$tblist[$i]";
   				}*/
   				//to replace the cust id with a default no.
   				if($colname=="cust_id")
   				{
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
	   			}
	   			else if($colname=="player_type")
	   			{
	   				//player type
   					$htmltxt=$AustGolf_Addon->get_player_name($temptxt);
	   			}
	   			else if($colname=="day_stay")
	   			{
	   				//date stay value
   					$htmltxt=html_entity_decode($temptxt);
   					if(((int)($htmltxt))<0)
   						$htmltxt="Invalid Dates";
	   			}
				else if ($colname=="hotelcheckindate" || $colname=="hotelcheckoutdate" || $colname=="arrival_date" || $colname=="depart_date"){
                                        $htmltxt=(strftime("%d-%b-%y", strtotime($temptxt)));
                                }
				else if ($colname=="arrival_time" || $colname=="depart_time" || $colname=="pickuptime")
                                        $htmltxt=preg_replace('/:\d\d$/','',$temptxt);
	   			else if($colname=="transport")
					$htmltxt=$AustGolf_Addon->get_transport_name($temptxt) ;
	   			else if($colname=="transport2")
	   			{
	   				if($temptxt==1)
	   					$mode = "1-way : H -> A";
	   				else if($temptxt==2)
	   				 	$mode = "1-way : A -> H";
		   			else if($temptxt==3)	
		   				$mode = "2-way";
		   			else
		   				$mode = "";
		   				
   					$htmltxt=$mode;
	   			}
	   			else if($colname=="room_short_form")
	   			{
	   				//Room type
   					$htmltxt=$AustGolf_Addon->get_room_name($temptxt);
	   			}
	   			else if($colname=="comment")
	   			{
	   				//Comment
	   				$htmltxt=html_entity_decode($temptxt);
	   				if($htmltxt!==""&&$_GET["pi"]!=1)
   						$htmltxt="<a href=\"index.php?s=comments&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view\" border=0 target=\"APPLET\" onclick=\"WindowsOpen('index.php?s=comments&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view',350,250)\" title=\"$htmltxt\"><img border=0 src='images/Subscribers.gif' alt=\"".$htmltxt."\"></a>";	
	   			}
	   			else if($colname=="blacklist")
	   			{
	   				//Blacklist
   					$htmltxt="<span style='font-weight:bold;color:#f00'>$temptxt</span>";
	   			}
   				else
   					$htmltxt=html_entity_decode($temptxt);
   				
   				if($htmltxt=="0"||$htmltxt==""||$htmltxt=="<br/>")
   				{
   						$htmltxt="&nbsp;-";
   				}
   				echo "<td valign=top class=fname1>".$htmltxt."</td>\n";
   			//}
		}
		
		if(substr($_SESSION["sCONTROLLVL"],0,3)=="MGM" OR $_SESSION["sCONTROLLVL"]=="GRP02")
		{
			print "<td valign=top class=fname1><a href=".$_SERVER["PHP_SELF"]."?s=view_record&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view>[View]</a>";
			if ($_SESSION["sCONTROLLVL"]=="GRP02" || date('Ymd') < $SregCutOff) {
				print "<br/><a href=".$_SERVER["PHP_SELF"]."?s=edit_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit>[Edit]</a>\n";
				print "<br/><br/><a href=# onclick='return delWindow(\"s=delete_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\",\"".urlencode($qrow["family_name"]." ".$qrow["first_name"])."\");return false;' >";
				print "[Delete]</a>";
			}
			print "<br /></td>\n";
			print "</tr>";
		}
		else
		{
			print "<td valign=top class=fname1><a href=".$_SERVER["PHP_SELF"]."?s=view_record&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view>[View]</a></td>\n";
			print "</tr>";	
		}	
	}
	print "</table>";

?><br /><br />
        <TABLE border=0 align="center">
              <TBODY>
              <TR vAlign=top>
              <td class=fname1 colspan=3 align=center>Overall Total <input class=LegendFooter type=button value="(Legend)" onclick="WindowsOpen('index.php?s=legend&as=view',500,350)"></td>
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

</TR>
</TBODY></TABLE>

<?php

if(!isset($_GET["pi"]))
{
	$Paging->backpage($_GET["tbn"],$wherecol,$list);
	$Paging->nextpage($_GET["tbn"],$wherecol,$list);
	print "<br/><br/>";
	$Paging->listpaging($_GET["tbn"],$wherecol,$list);
}

}
else
{
	$User_Info->go_page("error");
}
?>
