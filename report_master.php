<?php 
include_once("AustGolf_Addon.php");
include_once("shortname.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;

//if(!isset($perpage))
//{
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
//} 
//else 
//{
if ($_POST["submit"])
{
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])) {
	//This is used to export the data into excel format by JOE-25 MAY 2010
	$filename="Activity_".$_SESSION["sUSERID"]."_".date("Ymd").".xls";
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
	$fs = array() ;
        foreach ($fs as $f) if (isset($r_tblist["$f"])) unset($tblist[$r_tblist["$f"]]);

	$tempshowitem=array();
	//adding of new display list
	$tbcount=count($tblist);
	$newtbcount=$tbcount;
	//to add a new item
	
	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
   	//end of reconstruction of the get list
	
	$acthandiresult= $User_Info->dosearchSQL("ref_handi_tb","","handi_name DESC","inf");
	$handicounter=mysql_num_rows($acthandiresult);

	ob_start();

	print "<table width='100%' border='1' cellspacing='0' cellpadding='1'>";
	ob_start();
	print "<tr><td colspan=".($tbcount+15+($handicounter*6)+1)."><div align=center class=ftitle5>Master Master List</div></td></tr>";
	print "<tr>";
	$tabcounter=0;
	while (list($key, $val) = each($tblist)) {
		// Print header row 1
		//print "($key=$val,".$htmldisrep["$val"].")";
		//remove display of primary key and the arrival & departure time
		if($val==$tmpcolnum) $tmpcolnum=$key;
   				
   		//used to do the arrow up and down for the sort <Start>
   		if(isset($_GET["st"]))
   		{
   			if($_GET["st"]=="$val"){
   				if(isset($_GET["od"])){
   					if($_GET["od"]=="asc"){
   						print  "<td valign=bottom class=ftitle1 rowspan=3><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
						print  "<img src=\"images/arrow_ASC.gif\">";
						$tabcounter++;
					} else {
						print  "<td valign=bottom class=ftitle1 rowspan=3><a href=\"index.php?".$sortlist."&st=$val&od=asc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   						print  "<img src=\"images/arrow_DESC.gif\">";
   						$tabcounter++;
					}
   				}
   			} else {
   				print  "<td valign=bottom class=ftitle1 rowspan=3><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   				$tabcounter++;
			}
   		} else {
   			print  "<td valign=bottom class=ftitle1 rowspan=3><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   			$tabcounter++;
		}
   		//used to do the arrow up and down for the sort <End>
   		print  "</td>";
   		$rmtotal++;
	}
		
	$actnumresult= $User_Info->dosearchSQL("ref_act_tb","act_date","act_date","inf");
	$actnum=mysql_num_rows($actnumresult);
	?><td valign=top class=ftitle1 colspan=15 align=center>Activities</td>
	<TD class=ftitle1 align=center vAlign=top colspan=<?=($handicounter*6)?>>Handicap Record</TD>
        <TD class=ftitle1 align=center vAlign=bottom rowspan=3>Current Handicap For Guests Only</TD>
	</tr>
	<TR><!--ROWSPAN--><?
	// Print header row 2

	//removed the condition of >= today so the admin can view the date by Joe 20 Apr 2010
	$actdateresult= $User_Info->dosearchSQL("ref_act_tb","act_date","act_date","inf","act_date","act_date");
	while($actdaterow=mysql_fetch_array($actdateresult))
	{
		$db_act_date=$actdaterow["act_date"];
		$actlistresult= $User_Info->dosearchSQL("ref_act_tb","act_date=\"$db_act_date\"","act_date","inf","act_date");
		$actdatelist=mysql_num_rows($actlistresult);
		print "<TD class=ftitle1 vAlign=top align=center colspan=$actdatelist>".(strftime("%d", strtotime($db_act_date)))."</TD>";
	}
	$handicnt=array();
        while($acthandirow=mysql_fetch_array($acthandiresult))
        {
        	$db_handi_name=$acthandirow["handi_name"];
                $db_handi_id=$acthandirow["handi_ref_id"];
                print "<TD class=ftitle1 vAlign=top align=center colspan=6>".($db_handi_name)."</TD>";
                $handicnt=array_merge($handicnt,array("$db_handi_id"));
        }
	?></TR>
	<TR><!--ROWSPAN--><?
	// Print header row 3
	while($actnumrow=mysql_fetch_array($actnumresult)){
		print "<TD class=ftitle1 vAlign=top title=\"".str_replace('"','&quot;',$actnumrow["act_description"])."\">".$actnumrow["act_name"]."</TD>";
		$actarray=array_merge((array)$actarray,array($actnumrow["act_ref_id"]));
	}
	for($h=0;$h<$handicounter;$h++) {
        	?><TD class=ftitle1 vAlign=top>Div</TD>
                <TD class=ftitle1 vAlign=top>Ihcp</TD>
                <TD class=ftitle1 vAlign=top>Par</TD>
                <TD class=ftitle1 vAlign=top>Rat</TD>
                <TD class=ftitle1 vAlign=top>Sco</TD>
                <TD class=ftitle1 vAlign=top>Ahcp</TD><?
        }
	print "</tr>";	
	$tableheader=preg_replace('/[\r\n]/','',ob_get_contents());//display the content
	ob_end_flush();
	// END HEADER
	
	$page = isset($_GET["pg"]) ? $_GET["pg"] : 1;
	//page control listing by 10 now	
	$list = isset($_GET["ls"]) ? $_GET["ls"] : 10 ;
	$orderlist = isset($_GET["od"]) ? $_GET["od"] : "ASC" ;
	$pointer=$page*$list-$list;	
	$searchcol = $tmpstname ? "$tmpstname $orderlist" : "country_name ASC, player_type ASC" ;
	
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
		$tmpcol=$tblist[$tmpcolnum];
	else { 
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
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","inf");
	} 
	
	// Print listing
	$g=1;
	$pgcount=0;
	$pgrow=mysql_num_rows($resultx);
	while($qrow=mysql_fetch_array($resultx))
	{
		$pgcount++;
		print "<tr height=65>";
		foreach ($tblist as $i => $colname)
		{
   			$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   			//to replace the cust id with a default no.
   			if($colname=="cust_id"){
   				$htmltxt=($page-1)*$list+$g;
   				$g++;
	   		} else if($colname=="player_type") {
   				$htmltxt=($temptxt);
	   		} else if($colname=="day_stay") {
	   			//date stay value 
				//only show guest who stays in provided hotel
   				$htmltxt=html_entity_decode($temptxt);
				if(((int)($htmltxt))<0)
   					$htmltxt="Invalid Dates";
   				if($qrow['room_short_form']=="NA")
   					$htmltxt="0";
	   		} else if ($colname=="hotelcheckindate" || $colname=="hotelcheckoutdate" || $colname=="arrival_date" || $colname=="depart_date") {
                        	$htmltxt=(strftime("%d-%b-%y", strtotime($temptxt)));
                        } else if ($colname=="arrival_time" || $colname=="depart_time" || $colname=="pickuptime") {
				$htmltxt=preg_replace('/:\d\d$/','',$temptxt);
			} else if($colname=="transport")
   				$htmltxt=$AustGolf_Addon->get_transport_name($temptxt);
	   		else if($colname=="transport2") {
	   			if($temptxt==1)
	   				$mode = "1-way : H -> A";
	   			else if($temptxt==2)
	   			 	$mode = "1-way : A -> H";
		   		else if($temptxt==3)	
		   			$mode = "2-way";
		   		else
		   			$mode = "";
   				$htmltxt=$mode;
	   		} else if($colname=="room_short_form") {
	   			$htmltxt=($temptxt);
	   		} else if($colname=="comment") {
	   			//Comment
	   			$htmltxt=html_entity_decode($temptxt);
	   			if($htmltxt!=="")
	   				$htmltxt=$htmltxt;
				$htmltxt="<div style=\"width:150px;height:65px;overflow-y:auto;\" class=fname1>$htmltxt</div>";
	   		} else
   				$htmltxt=html_entity_decode($temptxt);
   				
   			if($htmltxt=="0"||$htmltxt==""||$htmltxt=="<br/>")
   				$htmltxt="&nbsp;-";
   			echo "<td valign=top class=fname1>".$htmltxt."</td>\n";
		}
		//Activities actions
		for($d=0;$d<$actnum;$d++)
		{
			$qf_set2= $User_Info->dosearchSQL("`cust_act_tb` as `a`","`act_ref_id`='".$actarray[$d]."' and `cust_id`='$qrow[0]'","","","cust_act_no");
			if($qf_row2=mysql_fetch_array($qf_set2)){
				$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qf_row2[0]);
				//echo $qf_row2[0];
				if($temptxt==""||$temptxt=="0")
					$htmltxt="&nbsp;-";
				else
					$htmltxt=html_entity_decode($temptxt);
   				echo "<td valign=top align=center class=fname1>".$htmltxt."</td>\n";
			}
			else{
				echo "<td valign=top align=center class=fname1>-</td>\n";
			}
		}
		//Handicap Record
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
                                        echo "<td valign=top class=fname1 align=center>".$htmltxt."<br /></td>\n";
                                }
                        }
                        else if($qf_set2 && $qf_row2=mysql_fetch_array($qf_set2))
                        {
                                $printed_row2=0;
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>".$qf_row2[0]."<br /></td>\n";
                        } else {
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                                echo "<td valign=top class=fname1 align=center>-</td>\n";
                        }
                }
		$qf_set2= $User_Info->dosearchSQL("cust_act_tb","act_ref_id=15 and cust_id=$qrow[0]","","");
                while($qf_row2=mysql_fetch_array($qf_set2))
                {
                        if(!empty($qf_row2[cust_addon]))
                                print "<TD valign=top align=left class=fname1>$qf_row2[cust_addon]</TD>";
                        else
                                print "<TD valign=top align=left class=fname1>&nbsp;-</TD>";
                }
		print "</tr>";
		if (is_int($pgcount/$perpage)&&$pgrow!=$pgcount) {
			print "<!--STARTHEADER--><tr><td colspan=".($tbcount+15+($handicounter*6)+1)." style=\"border-left-style:none;border-right-style:none;\">&nbsp;<p STYLE='page-break-before: always'></p></td></tr>$tableheader<!--ENDHEADER-->";
		}
		
	}
	
	print "</table><p STYLE='page-break-before: always'></p>";
	print "<div align=center class=ftitle5>Master Master List</div>";
	?><br /><br />
        <TABLE  border=0 align=center><TBODY>
	<TR vAlign=top>
              	<td align=center colspan=3 class=fname1><br/>Report Total <input class=LegendFooter type=button value="(Legend)" onclick="WindowsOpen('index.php?s=legend&as=view',500,350)"></td>
        </tr>
        <TR vAlign=top>
    		<td><?
		$AustGolf_Addon->room_summary($summaryarray1);
		?></td>
		<td><?
		$AustGolf_Addon->transport_summary($summaryarray2);
		?></td>
		<td><?
		$AustGolf_Addon->activity_summary($summaryarray2);
		?></td>
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
	<div id="footdiv" align=center>[ <a href="javascript:history.back();">Back</a> ][ <a href="javascript:document.getElementById('footdiv').style.display='none';document.getElementById('filterline').style.display='none';window.print();">Print</a> ][ <a href="ex/<?=$filename?>?r=<?=time()?>">Download Excel</a> ]</div>
	<?
}
else
{
	$User_Info->go_page("error");
}


}
?>
