<?php 
include_once("AustGolf_Addon.php");
include_once("printname.php");
//include_once("shortname.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;

?><form action="<? $_SERVER[PHP_SELF] ?>" method="post">
<table border="0" cellspacing="0" cellpadding="5" align="center">
<tr id="filterline">
        <td class="fvalue">
        <?
        $HTML->generate_search_list((isset($_POST["player_type"])?($_POST["player_type"]):""),"player_type","ref_playertype_tb","shortname,description",2);
        $HTML->generate_search_list((isset($_POST["country"])?($_POST["country"]):""),"country","ref_country_tb","country_id,country_name",1);
        $HTML->generate_search_list((isset($_POST["transport"])?($_POST["transport"]):""),"transport","ref_transport_tb","transport_sh,transport_name",2);
        $HTML->generate_search_list((isset($_POST["room_type"])?($_POST["room_type"]):""),"room_type","ref_room_tb","room_short_form,room_name",2);
        $HTML->generate_search_cu_list((isset($_POST["arrival_date"])?($_POST["arrival_date"]):""),"arrival_date","cust_tb","arrival_date");
        $HTML->generate_search_cu_list((isset($_POST["depart_date"])?($_POST["depart_date"]):""),"depart_date","cust_tb","depart_date");
        ?>
        </td>
        <td class="fcommentsdark" align=center>Rows per page:&nbsp;&nbsp;<input class="fcommentsdark" type="text" size="2" maxlength="4" name="perpage" value="<?=(isset($_POST["perpage"])?$_POST["perpage"]:8)?>">&nbsp;<input type="submit" name="submit" value="Submit">
        </td>
</tr>
</table>
</form><?

if ($_POST["submit"])
{
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
{	
	$filename="ArrivalFlight_".$_SESSION["sUSERID"]."_".date("Ymd").".xls";
	//This is used to export the data into excel format by JOE-25 MAY 2010
	/*if(isset($_POST["export_arrival"])=='Export'){
		$filename="Arrival".date("Ymd");
		header("Content-type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=".$filename.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}*/
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	$r_tblist = array_flip($tblist);
        // Remove fields
	$fs = array("blacklist", "player_type","fullname","sex","dietary","hotelcheckindate","hotelcheckoutdate","day_stay","depart_date","depart_time","depart_flight","depart_port","pickuptime","room_short_form","arrival_port","comment") ;
        foreach ($fs as $f) if (isset($r_tblist["$f"])) unset($tblist[$r_tblist["$f"]]);

	$tempshowitem=array();
	
	//adding of new display list
	$tbcount=count($tblist);
	$newtbcount=$tbcount;
	
	$sortlist=$AustGolf_Addon->link_construction();

	
	$today=getdate();
	print "<div align=right class=fname1>Printed by ".$_SESSION["sNick"]." on ".(strftime("%d %B %Y %r", $today[0])).".</div>";

	ob_start();

	print "<table width='100%' border='1' cellspacing='0' cellpadding='1'>";
	print "<tr><td colspan=".($tbcount)."><div align=center class=ftitle5>By Arrival Date Report ";
	print "</div></td></tr>";
	ob_start();
	
	print "<tr>";
	$tabcounter=0;
	while (list($key, $val) = each($tblist))
	{
			//remove display of primary key and the arrival & departure time
   			/*if($val=="room_short_form"||$val=="comment"||$val=="player_type"||$val=="day_stay"||$val=="depart_date"||$val=="depart_time"||$val=="depart_flight")
   			{
   				$tempshowitem=array_merge($tempshowitem,array($key=>0));
   			}
   			else{
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
   							if($_GET["od"]=="asc"){
   								print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
								print  "<img src=\"images/arrow_ASC.gif\">";
								$tabcounter++;}
							else{
								print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=asc\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
   								print  "<img src=\"images/arrow_DESC.gif\">";
   								$tabcounter++;}
   						}
   					}
   					else{
   						print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
   						$tabcounter++;}
   				}
   				else
   				{
   					print  "<td valign=top class=ftitle1>". (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
   					$tabcounter++;
   				}
   				
   					
   				print  "</td>";
   			//}
   			$rmtotal++;
	}
	//print  "<td valign=top class=ftitle1>Remarks</td>";	
		
	?>
	</tr>
	<?
	$tableheader=preg_replace('/[\r\n]/','',ob_get_contents());//display the content
	ob_end_clean();

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
		$orderlist="ASC";
	
	$pointer=$page*$list-$list;	

	if(($tmpstname))
	{
		if($tmpstname=="Arrive"){
			$searchcol="arrival_date $orderlist";
		}
		else if($tmpstname=="Depart"){
			$searchcol="depart_date $orderlist";
		}
	}
	else
	{
		$searchcol="arrival_date ASC, arrival_time ASC, arrival_flight ASC";
	}
	
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
	}
	else{
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
                if(($_POST["player_type"]))
                        $wherecol=$wherecol."player_type=\"".$_POST["player_type"]."\" and";
                if(($_POST["country"]))
                        $wherecol=$wherecol." country_name=\"".$_POST["country"]."\" and";
                if(($_POST["transport"]))
                        $wherecol=$wherecol." transport like \"%".$_POST["transport"]."%\" and";
                if(($_POST["room_type"]))
                        $wherecol=$wherecol." room_short_form=\"".$_POST["room_type"]."\" and";
                if(($_POST["arrival_date"]))
                        $wherecol=$wherecol." arrival_date=\"".$_POST["arrival_date"]."\" and";
                if(($_POST["depart_date"]))
                        $wherecol=$wherecol." depart_date=\"".$_POST["depart_date"]."\" and";
                $wherecol=substr($wherecol,0,-3);
                if ($wherecol == '') $wherecol = ' 1';
                $resultx= $User_Info->dosearchSQL("$tmptb AS a","$wherecol","$searchcol","inf");

/*
		if($_POST["arrival_date"]!="")
			$wherecol=" a.arrival_date=\"".$_POST["arrival_date"]."\" and";
		if($_POST["depart_date"]!="")
		 	$wherecol=$wherecol." a.depart_date=\"".$_POST["depart_date"]."\" and";
		
		$wherecol=$wherecol." a.arrival_flight NOT LIKE \"%NA%\" and";
		
		$wherecol=substr($wherecol,0,-3);
		$resultx= $User_Info->dosearchSQL("$tmptb AS a","$wherecol","a.$searchcol","inf");
*/
		$summaryarray1=array("$tmptb AS a","$wherecol and a.","a.$searchcol","inf", "*","");
		$summaryarray2=array("$tmptb AS a","$wherecol ","a.$searchcol","inf", "*","");
	}
	
	$g=1;
	$pgcount=0;
	$pgrow=mysql_num_rows($resultx);
	
	if($qrow=mysql_fetch_array($resultx))
	{
		$printerheader=0;
		$pgcount++;

		if(!isset($tempdate)){
			$tempdate=$qrow["arrival_date"];
			}
		if(!isset($temptime)){
			$temptime=$qrow["arrival_time"];
			}
		if(!isset($tempflight)){
			$tempflight=$qrow["arrival_flight"];
		}
		print "<tr height=60 valign=bottom><td colspan=".($tbcount).">";
		print "<b>Date : ".strftime("%d-%b-%y", strtotime($tempdate))."</b><br/>";
		$tempnflight=explode(":~:",$tempflight);
		print "<b>Flight : ".$qrow["arrival_flight"]."</b><br/>";
		print "<b>Airport : ".$AustGolf_Addon->get_airport_name($qrow["arrival_port"])."</b>";
		print "</td></tr>" ;
		print $tableheader;
	}
	mysql_data_seek ($resultx,0);
	
	
	while($qrow=mysql_fetch_array($resultx))
	{
		$printerheader=0;
		$pgcount++;
		if(!isset($tempdate)){
			$tempdate=$qrow["arrival_date"];
			}
		if(!isset($temptime)){
			$temptime=$qrow["arrival_time"];
			}
		if(!isset($tempflight)){
			$tempflight=$qrow["arrival_flight"];
		}
		
		if($tempdate!=$qrow["arrival_date"]){
			$tempdate=$qrow["arrival_date"];
			$printerheader=1;
			}
		if($temptime!=$qrow["arrival_time"]){
			$temptime=$qrow["arrival_time"];
			$printerheader=1;
			}
		if($tempflight!=$qrow["arrival_flight"]){
			$tempflight=$qrow["arrival_flight"];
			$printerheader=1;
		}
		
		if($printerheader==1)
		{
			if (is_int($pgcount/$perpage)&&$pgrow!=$pgcount) {
				print "<!--STARTHEADER--><tr><td colspan=".($tbcount)." style=\"border-left-style:none;border-right-style:none;\">&nbsp;<p STYLE='page-break-before: always'></p></td></tr><!--ENDHEADER-->";
				$pgcount++;
			}
			$tbcounter++;
			print "<tr height=60 valign=bottom><td colspan=".($tbcount).">";
			print "<b>Date : ".strftime("%d-%b-%y", strtotime($tempdate))."</b><br/>";
			$tempnflight=explode(":~:",$tempflight);
			print "<b>Flight : ".$qrow["arrival_flight"]."</b><br/>";
			print "<b>Airport : ".$AustGolf_Addon->get_airport_name($qrow["arrival_port"])."</b></td></tr>" ;
			print $tableheader;
			$g=1;
			$pgcount++;
			$_pg=0;
		} else if ($_pg) {
			print "<!--STARTHEADER-->$tableheader<!--ENDHEADER-->" ;
			$_pg=0;
		}
		print "<tr height=65>";

		foreach ($tblist as $i => $colname) {
   				$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				
   				//to replace the cust id with a default no.
				if($colname=="cust_id"){
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
   					$size=20;
	   			}
				else if($colname=="player_type"){
	   				//player type
   					$htmltxt=($temptxt);
	   			}
				else if($colname=="day_stay"){
	   				//date stay value
   					$htmltxt=html_entity_decode($temptxt);
   					if(((int)($htmltxt))<0)
   						$htmltxt="Invalid Dates";
	   			}
				else if ($colname=="hotelcheckindate" || $colname=="hotelcheckoutdate" || $colname=="arrival_date" || $colname=="depart_date"){
					$htmltxt=(strftime("%d-%b-%y", strtotime($temptxt)));
				}
				else if ($colname=="arrival_time" || $colname=="depart_time" || $colname=="pickuptime"){
                                        $htmltxt=preg_replace('/:\d\d$/','',$temptxt);
                                }
                                else if($colname=="transport")
                                        $htmltxt=$AustGolf_Addon->get_transport_name($temptxt);
                                else if($colname=="transport2"){
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
                                else if($colname=="room_short_form"){
                                        //Room type
                                        $htmltxt=($temptxt);
                                }
                                else if($colname=="comment"){
                                        //Comment
                                        $htmltxt=html_entity_decode($temptxt);
                                        if($htmltxt!=="")
                                                $htmltxt=$htmltxt;
                                        $htmltxt="<div style=\"width:300px;height:65px;overflow-y:auto;\" class=fname1>$htmltxt</div>";

                                }
   				else{
   					$htmltxt=html_entity_decode($temptxt);
   					$size=70;
   				}
   				
   				if($htmltxt=="0"||$htmltxt==""||$htmltxt=="<br/>")
   				{
   						$htmltxt="&nbsp;-";
   				}
   				echo "<td valign=top class=fname1 width=$size>".$htmltxt."</td>\n";
   			
		}
		print "</tr>";
		if (is_int($pgcount/$perpage)&&$pgrow!=$pgcount)
		{	
			print "<!--STARTHEADER--><tr><td colspan=".($tbcount)." style=\"border-left-style:none;border-right-style:none;\">&nbsp;<p STYLE='page-break-before: always'></p></td></tr><!--ENDHEADER-->";
			$_pg++;
		}
		
	}
	
	print "<table><tr><td class=fname1>Note :</td></tr>";
			print "<tr><td class=fname1>Shuttle Bus</td><td class=fname1>: Provided by Aries Tour</td></tr>";
			print "<tr><td class=fname1>Hotel Limo</td><td class=fname1>: Provided by Hyatt</td></tr>";
			print "<tr><td class=fname1>No Transport</td><td class=fname1>: No transport arrangements required</td></tr></table>";
	print "<p STYLE='page-break-before: always'></p>";
	
	?>

</TBODY></TABLE>
<?
        $_c = ob_get_contents();
        ob_end_flush();
        $_c = preg_replace('/<!--STARTHEADER-->.*<!--ENDHEADER-->/m', '' , $_c);
        $_c = preg_replace('/<\/?a[^>]*>/', '' , $_c);
        $fp = fopen("ex/$filename", "w");
        fwrite($fp, "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n$_c");
        fclose($fp);
?>
<div id="footdiv" align=center>[ <a href="javascript:history.back();">Back</a> ][ <a href="javascript:document.getElementById('footdiv').style.display='none';window.print();">Print</a> ][ <a href="ex/<?=$filename?>?r=<?=time()?>">Download Excel</a> ]</div>
<?
}
}
else
{
	$User_Info->go_page("error");
}
}
?>
