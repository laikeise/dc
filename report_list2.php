<?php 
include_once("AustGolf_Addon.php");
include_once("shortname.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;
if(!isset($perpage))
{
?>
<form action="<? $_SERVER[PHP_SELF] ?>" method="post">
<table border="0" cellspacing="0" cellpadding="5" align="center">
<tr><td class="fvalue">Rows per page:&nbsp;&nbsp;<input type="text" size="4" maxlength="4" name="perpage" value="8">&nbsp;<input type="submit" value="Submit"></td></tr>
<!--<tr>Additional button to export the report into excel format
	<td>Export this Report to Excel:&nbsp;<input type="submit" name="export_handicap" value="Export"/></td>
</tr>-->
</table>
</form>	
<?
}
else
{
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
	$filename="Handicap_".$_SESSION["sUSERID"]."_".date("Ymd").".xls";
	//This is used to export the data into excel format by JOE-25 MAY 2010
	/*if(isset($_POST['export_handicap'])=="Export"){
		$filename="Handicap".date("Ymd");
		header("Content-type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=".$filename.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}*/
	//print "<br/><a href='".$_SERVER["PHP_SELF"]."?s=admin'>Click here to return</a><br />";
	$today=getdate();
	print "<div align=right class=fname1>Printed by ".$_SESSION["sNick"]." on ".(strftime("%d %B %Y %r", $today[0])).".</div>";
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	$r_tblist = array_flip($tblist);
        // Remove fields
	$fs = array("day_stay","blacklist","fullname","sex","dietary","hotelcheckindate","hotelcheckoutdate","pickuptime") ;
        foreach ($fs as $f) if (isset($r_tblist["$f"])) unset($tblist[$r_tblist["$f"]]);

	$tempshowitem=array();
	
	//adding of new display list
	$tbcount=count($tblist);
	$newtbcount=$tbcount;
	//to add a new item
	
	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
   	//end of reconstruction of the get list
	
	ob_start();

	print "<table width='100%' border='1' cellspacing='0' cellpadding='1'>";
	ob_start();
	print "<tr><td colspan=".($tbcount+25)."><div align=center class=ftitle5>Handicap Master List</div></td></tr>";
	print "<tr>";
	$tabcounter=0;
	while (list($key, $val) = each($tblist)) {
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
				if($val==$tmpcolnum)
				{
					$tmpcolnum=$key;
				}
				//$tempshowitem=array_merge($tempshowitem,array($key=>1));
   				
   				//used to do the arrow up and down for the sort <Start>
   				if(isset($_GET["st"]))
   				{
   					if($_GET["st"]=="$val"){
   						if(isset($_GET["od"])){
   							if($_GET["od"]=="asc"){
   								print  "<td valign=bottom class=ftitle1 rowspan=3><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
								print  "<img src=\"images/arrow_ASC.gif\">";
								$tabcounter++;}
							else{
								print  "<td valign=bottom class=ftitle1 rowspan=3><a href=\"index.php?".$sortlist."&st=$val&od=asc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   								print  "<img src=\"images/arrow_DESC.gif\">";
   								$tabcounter++;}
   						}
   					}
   					else{
   						print  "<td valign=bottom class=ftitle1 rowspan=3><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   						$tabcounter++;}
   				}
   				else{
   					print  "<td valign=bottom class=ftitle1 rowspan=3><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   					$tabcounter++;}
   				//used to do the arrow up and down for the sort <End>
   					
   				print  "</td>";
   			$rmtotal++;
	}
	
		$actnumresult= $User_Info->dosearchSQL("ref_act_tb","","act_date","inf");
		//if($qrow=mysql_fetch_array($resultx))
		$actnum=mysql_num_rows($actnumresult);

		$acthandiresult= $User_Info->dosearchSQL("ref_handi_tb","","handi_name DESC","inf");
		$handicounter=mysql_num_rows($acthandiresult);
		
	?>
	<TD class=ftitle1 align=center vAlign=top colspan=<?=($handicounter*6)?>>Handicap Record</TD>
	<TD class=ftitle1 align=center vAlign=bottom rowspan=3>Current Handicap For Guests Only</TD>
	</tr>
	<TR><!--ROWSPAN--><?
		/*
	
			for($k=0;$k<$tabcounter;$k++){
				?>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
               <?
			}
		*/
		$handicnt=array();
		while($acthandirow=mysql_fetch_array($acthandiresult))
		{
			$db_handi_name=$acthandirow["handi_name"];
			$db_handi_id=$acthandirow["handi_ref_id"];
			print "<TD class=ftitle1 vAlign=top align=center colspan=6>".($db_handi_name)."</TD>";
			$handicnt=array_merge($handicnt,array("$db_handi_id"));
		}
		
	?>
	</TR>
	<TR><!--ROWSPAN--><? /*
			for($k=0;$k<$tabcounter;$k++){
				?>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
               <?
			}
		*/
		for($h=0;$h<$handicounter;$h++)
		{?>
                <TD class=ftitle1 vAlign=top>Div</TD>
                <TD class=ftitle1 vAlign=top>Ihcp</TD>
                <TD class=ftitle1 vAlign=top>Par</TD>
                <TD class=ftitle1 vAlign=top>Rat</TD>
                <TD class=ftitle1 vAlign=top>Sco</TD>
                <TD class=ftitle1 vAlign=top>Ahcp</TD><?
		}
		
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
		if($tmpstname=="Arrive"){
			$searchcol="arrival_date $orderlist";
		}
		else if($tmpstname=="Depart"){
			$searchcol="depart_date $orderlist";
		}else{
			$searchcol="$tmpstname $orderlist";}
	}
	else{
		$searchcol="country_name ASC, player_type ASC";
		//$searchcol="$tblist[0] $orderlist";
	}
	$tableheader=preg_replace('/[\r\n]/','',ob_get_contents());//display the content
	ob_end_flush();
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
	}
	else{
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","inf");
	}
	
	//$resultx= $User_Info->dosearchSQL("$tmptb","","$tblist[0]","$pointer,$list");
	$g=1;
	$pgcount=0;
	$pgrow=mysql_num_rows($resultx);
	while($qrow=mysql_fetch_array($resultx))
	{
		$pgcount++;
		//print "test";
		//$sub=ereg_replace ("\n", "&lt;br /&gt;", $row['rob_subject']);
		print "<tr height=65>";
		
		foreach ($tblist as $i => $colname) {
		//for($i=0;$i<$newtbcount;$i++) {
			//print $i;
			//print_r($tblist);
			//if($tempshowitem[$i]==1)
			//{
				//if($i<$tbcount)
   					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				/* else if($i>=$tbcount){
   					if($tblist[$i]=="Arrive"){
   						//$val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'
   						//echo strftime("%d-%m-%y", strtotime($qrow["arrival_date"]));
   						$aflight=explode(":~:",$qrow["arrival_flight"]);
   						$temptxt=(($qrow["arrival_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["arrival_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["arrival_time"]))."<br/>".$aflight[0].(isset($aflight[1])!=""?("<br/>".($aflight[1])):"").""):"");
   						//$temptxt=(($qrow["arrival_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["arrival_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["arrival_time"]))."<br/>".$aflight[0].(isset($aflight[1])!=""?("<br/>".$AustGolf_Addon->get_airport_name($aflight[1])):"").""):"");
   					}
   					else if($tblist[$i]=="Depart"){
   						//$val=='depart_date'||$val=='depart_time'||$val=='depart_flight'
   						$dflight=explode(":~:",$qrow["depart_flight"]);
   						$temptxt=(($qrow["depart_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["depart_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["depart_time"]))."<br/>".$dflight[0].(isset($dflight[1])!=""?("<br/>".($dflight[1])):"").""):"");
   						//$temptxt=(($qrow["depart_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["depart_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["depart_time"]))."<br/>".$dflight[0].(isset($dflight[1])!=""?("<br/>".$AustGolf_Addon->get_airport_name($dflight[1])):"").""):"");
   					}
   					else
   						$temptxt="$tblist[$i]";
   				}
				*/
   				//to replace the cust id with a default no.
				if($colname=="cust_id"){
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
	   			}
				else if($colname=="player_type"){
	   				//player type
   					//$htmltxt=$AustGolf_Addon->get_player_name($temptxt);
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
				else if ($colname=="arrival_time" || $colname=="depart_time"){
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
                                        $htmltxt="<div style=\"width:150px;height:65px;overflow-y:auto;\" class=fname1>$htmltxt</div>";

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
		
		for($h=0;$h<$handicounter;$h++)
		{
			$qf_set1= $User_Info->dosearchSQL("cust_handi_tb","ref_handi_id=".($handicnt["$h"])." and cust_id=$qrow[0]","",""," cust_handi_div,cust_initial_hcp,cust_par,cust_course_rating,cust_result,cust_adj_hcp");
			if($h==0)
				$qf_set2= $User_Info->dosearchSQL("cust_act_tb","act_ref_id =3 and cust_id=$qrow[0]","","","cust_addon");
			else if($h==1)
				$qf_set2= $User_Info->dosearchSQL("cust_act_tb","act_ref_id =4 and cust_id=$qrow[0]","","","cust_addon");
			else
				$qf_set2="";
			if($qf_row1=mysql_fetch_array($qf_set1)){
				for($i=0;$i<6;$i++) {
					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qf_row1[$i]);
					if($temptxt==""||$temptxt=="0")
						$htmltxt="&nbsp;-";
					else
						$htmltxt=html_entity_decode($temptxt);
   					echo "<td valign=top class=fname1>".$htmltxt."<br /></td>\n";
				}
			}
			else if($qf_set2 && $qf_row2=mysql_fetch_array($qf_set2))
			{
				$printed_row2=0;
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;".$qf_row2[0]."<br /></td>\n";
			}
			
			else{
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
			}
		}
		
		// added new column for Current Handicap for Guest Players - arthur
		$qf_set2= $User_Info->dosearchSQL("cust_act_tb","act_ref_id=15 and cust_id=$qrow[0]","","");
		while($qf_row2=mysql_fetch_array($qf_set2))
		{
			if(!empty($qf_row2[cust_addon]))
				print "<TD valign=top align=left class=fname1>$qf_row2[cust_addon]</TD>";
			else
				print "<TD valign=top align=left class=fname1>&nbsp;-</TD>";
		}
	
		
		print "</tr>";
		
		
		if (is_int($pgcount/$perpage)&&$pgrow!=$pgcount){
			print "<!--STARTHEADER--><tr><td colspan=".($tbcount+25)." style=\"border-left-style:none;border-right-style:none;\">&nbsp;<p STYLE='page-break-before: always'></p></td></tr>$tableheader<!--ENDHEADER-->";
		}
		
	}
	
	print "</table><p STYLE='page-break-before: always'></p>";
print "<div align=center class=ftitle5>Handicap Master List</div>";
?><br /><br />
          <TABLE  border=0 align=center>
              <TBODY>
              				<TR vAlign=top>
              <td align=center colspan=3 class=fname1><br/>Report Total <input class=LegendFooter type=button value="(Legend)" onclick="WindowsOpen('index.php?s=legend&as=view',500,350)"> </td>
              </tr>
              <TR vAlign=top>
    <td>
<?
$AustGolf_Addon->room_summary($summaryarray1);
?>
</td>
<td>
<?
$AustGolf_Addon->transport_summary($summaryarray2);
?>
</td>
<td>
<?
$AustGolf_Addon->activity_summary($summaryarray2);
?>
</td>
</TR>
</TBODY></TABLE>
<?
	$_c = ob_get_contents();
        ob_end_flush();

	$_c = preg_replace('/<!--STARTHEADER-->.*<!--ENDHEADER-->/m', '' , $_c);
        $_c = preg_replace('/<\/?a[^>]*>/', '' , $_c);
        ob_start(); for($k=0;$k<$tabcounter;$k++) { ?><TD class=ftitle1 vAlign=top>&nbsp;</TD><? } $_c2=ob_get_contents(); ob_end_clean();
        $_c = preg_replace('/rowspan=3/', '' , $_c);
        $_c = preg_replace('/<!--ROWSPAN-->/', "$_c2", $_c);

        $fp = fopen("ex/$filename", "w");
        fwrite($fp, "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n$_c");
        fclose($fp);
?>
<div id="footdiv" align=center>[ <a href="javascript:history.back();">Back</a> ][ <a href="javascript:document.getElementById('footdiv').style.display='none';window.print();">Print</a> ][ <a href="ex/<?=$filename?>?r=<?=time()?>">Download Excel</a> ]</div>
<?php
/*
//if(isset($_GET["Filter"])){
$Paging->backpage($_GET["tbn"],$wherecol,$list);
$Paging->nextpage($_GET["tbn"],$wherecol,$list);
print "<br/>";
$Paging->listpaging($_GET["tbn"],$wherecol,$list);

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

}

?>
