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
<tr>
	<td class="fvalue">Rows per page:&nbsp;&nbsp;<input type="text" size="4" maxlength="4" name="perpage" value="8">&nbsp;<input type="submit" value="Submit">
	</td>
</tr>
<tr><!--Additional button to export the report into excel format-->
	<td>Export this Report to Excel:&nbsp;<input type="submit" name="export_act" value="Export"/></td>
</tr>

</table>
</form>	
<?
}
else {
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])) {
	//This is used to export the data into excel format by JOE-25 MAY 2010
	if(isset($_POST['export_act'])=="Export"){
		$filename="Master_List".date("Ymd");
		header("Content-type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=".$filename.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	$today=getdate();
	print "<div align=right class=fname1>Printed by ".$_SESSION["sNick"]." on ".(strftime("%d %B %Y %r", $today[0])).".</div>";
		
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
	
	$newitem=array("1"=>"Arrive",
	"2"=>"Depart"
	);
	
	while (list($key, $val) = each($newitem)) {
		$newtbcount=$newtbcount+1;
		$newdisplay=array("$newtbcount"=>"$val");
		$tblist=array_merge($tblist,$newdisplay);
	}
	//echo"<pre>";var_dump($tblist);echo"</pre>";
	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
   	//end of reconstruction of the get list
	
	print "<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
	ob_start();
	print "<div align=center class=ftitle5>Activities Master List</div>";
	print "<tr>";
	$tabcounter=0;
	while (list($key, $val) = each($tblist)) {
			//echo $val."<br>";
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
   			if($val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'||$val=='depart_date'||$val=='depart_time'||$val=='depart_flight')// || $val=='Arrive' || $val=='Depart')
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
   								print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
								print  "<img src=\"images/arrow_ASC.gif\">";
								$tabcounter++;}
							else{
								print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=asc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   								print  "<img src=\"images/arrow_DESC.gif\">";
   								$tabcounter++;}
   						}
   					}
   					else{
   						print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   						$tabcounter++;}
   				}
   				else{
					/*if($val=="Arrive" || $val=="Depart"){
						print  "<td valign=top class=ftitle1 colspan='4'><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
						}*/
						print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
						$tabcounter++;
					}
   				//used to do the arrow up and down for the sort <End>
   					
   				print  "</td>";
   			}
   			$rmtotal++;
	}
		// Modified by William on April 20, 2006. Display items that is >= today
		
		/*original dosearchSQL for the activities
		$actnumresult= $User_Info->dosearchSQL("ref_act_tb","act_date>=CURDATE()","act_date","inf"); 
		original joe 15-04-2010(Thur)*/
		?>
		<td valign=top class=ftitle1 colspan="4" align=center>Arrival</td>
		<td valign=top class=ftitle1 colspan="4" align=center>Departure</td>
		<?
		$actnumresult= $User_Info->dosearchSQL("ref_act_tb","act_date","act_date","inf");
		$actnum=mysql_num_rows($actnumresult);
	?>
	<td valign=top class=ftitle1 colspan=<?=$actnum?> align=center>Activities</td>
	<?
		$acthandiresult= $User_Info->dosearchSQL("ref_handi_tb","","handi_name DESC","inf");
		$handicounter=mysql_num_rows($acthandiresult);
		
	?>
	<TD class=ftitle1 align=center vAlign=top colspan=<?=($handicounter*6)?>>Handicap Record</TD>
	<TD class=ftitle1 align=center vAlign=top>Current Handicap</TD>
	</tr>
	<TR><?
			for($k=0;$k<$tabcounter;$k++){
				?>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
               <?
			}
		// Modified by William on April 20, 2006. Display items that is >= today 
		/*original query by william
		$actdateresult= $User_Info->dosearchSQL("ref_act_tb","act_date>=CURDATE()","act_date","inf","act_date","act_date");
		*/
		//removed the condition of >= today so the admin can view the date by Joe 20 Apr 2010
		for($a=0;$a<=1;$a++){
		?>
		<!--2nd row Addition fields for the Arrival-->
		<td class='ftitle1' vAlign='top' align='center'>Date</td>
		<td class='ftitle1' vAlign='top' align='center'>Time</td>
		<td class='ftitle1' vAlign='top' align='center'>Flight</td>
		<td class='ftitle1' vAlign='top' align='center'>Airport</td>
		<?
		}
		$actdateresult= $User_Info->dosearchSQL("ref_act_tb","act_date","act_date","inf","act_date","act_date");
		while($actdaterow=mysql_fetch_array($actdateresult))
		{
			$db_act_date=$actdaterow["act_date"];
			$actlistresult= $User_Info->dosearchSQL("ref_act_tb","act_date=\"$db_act_date\"","act_date","inf","act_date");
			$actdatelist=mysql_num_rows($actlistresult);
			print "<TD class=ftitle1 vAlign=top align=center colspan=$actdatelist>".(strftime("%d", strtotime($db_act_date)))."</TD>";
			
		}
		//display the handicap
		$handicnt=array();
		while($acthandirow=mysql_fetch_array($acthandiresult))
		{
			$db_handi_name=$acthandirow["handi_name"];
			$db_handi_id=$acthandirow["handi_ref_id"];
			print "<TD class=ftitle1 vAlign=top align=center colspan=6>".($db_handi_name)."</TD>";
			$handicnt=array_merge($handicnt,array("$db_handi_id"));
		}
	?>  
	<TD class=ftitle1 align=center vAlign=top>For Guests</TD>   	
	</TR>
	<TR><?
		for($k=0;$k<$tabcounter;$k++){
				?>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
               <?
		}
		for($a=0;$a<=1;$a++){
		?>
			<!--3rd row empty space for arrival-->
			<td class=ftitle1>&nbsp;</td>
			<td class=ftitle1>&nbsp;</td>
			<td class=ftitle1>&nbsp;</td>
			<td class=ftitle1>&nbsp;</td>
		<?
		}
		while($actnumrow=mysql_fetch_array($actnumresult)){
			print "<TD class=ftitle1 vAlign=top>".$actnumrow["act_name"]."</TD>";
			$actarray=array_merge((array)$actarray,array($actnumrow["act_ref_id"]));
		}
		//display handicap title
		for($h=0;$h<$handicounter;$h++)
		{?>
                <TD class=ftitle1 vAlign=top>Div</TD>
                <TD class=ftitle1 vAlign=top>Ihcp</TD>
                <TD class=ftitle1 vAlign=top>Par</TD>
                <TD class=ftitle1 vAlign=top>Rat</TD>
                <TD class=ftitle1 vAlign=top>Sco</TD>
                <TD class=ftitle1 vAlign=top>Ahcp</TD><?
		}	
	print "<TD class=ftitle1 align=center vAlign=top>Only</TD>";	
	print "</tr>";	
	?>
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
		$orderlist="ASC";

	$pointer=$page*$list-$list;	
	

	if(($tmpstname)){
		
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
	}
	
	$tableheader=ob_get_contents();//display the content
	ob_end_flush();
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
	}
	else if(isset($_GET["Filter"])){
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
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
		$wherecol=substr($wherecol,0,-3);
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","$pointer,$list");
	}
	else{
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","inf");
	}
	
	$g=1;
	$pgcount=0;
	$pgrow=mysql_num_rows($resultx);
	while($qrow=mysql_fetch_array($resultx))
	{
		$pgcount++;
		print "<tr height=65>";
		
		for($i=0;$i<$newtbcount;$i++) 
		{
			if($tempshowitem[$i]==1)
			{
			//echo"<pre>";var_dump($tempshowitem[$i]);echo"</pre>";
				if($i<$tbcount)
   					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				else if($i>=$tbcount){
   					if($tblist[$i]=="Arrive"){
						//this is for Arrival date and time
						$adate=strftime("%d/%m/%Y", strtotime($qrow["arrival_date"]));
						$atime=strftime("%H:%M", strtotime($qrow["arrival_time"]));
   						$aflight=explode(":~:",$qrow["arrival_flight"]);
						$temptxt=(($qrow["arrival_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["arrival_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["arrival_time"]))."<br/>".$aflight[0].(isset($aflight[1])!=""?("<br/>".($aflight[1])):"").""):"");
					
   					}
   					else if($tblist[$i]=="Depart"){
						$ddate=strftime("%d/%m/%Y", strtotime($qrow["depart_date"]));
						$dtime=strftime("%H:%M", strtotime($qrow["depart_time"]));
   						$dflight=explode(":~:",$qrow["depart_flight"]);
						$temptxt=(($qrow["depart_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["depart_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["depart_time"]))."<br/>".$dflight[0].(isset($dflight[1])!=""?("<br/>".($dflight[1])):"").""):"");
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
   					$htmltxt=($temptxt);
	   			}
	   			else if($i==6){
	   				//date stay value 
					//only show guest who stays in provided hotel
   					$htmltxt=html_entity_decode($temptxt);
					if(((int)($htmltxt))<0)
   						$htmltxt="Invalid Dates";
   					if($qrow['room_short_form']=="NA")
   						$htmltxt="0";
	   			}
	   			else if($i==13){
	   				//transport
	   				$temptp=explode(":~:",$temptxt);
	   				$temptp1=explode(":~~:",$temptxt);
	   				$temptp1[1] = substr ( $temptp1[1], 0, 1 );

	   				if($temptp1[1]==1)
	   					$mode = "1-way : H -> A";
	   				else if($temptp1[1]==2)
	   				 	$mode = "1-way : A -> H";
		   			else if($temptp1[1]==3)	
		   				$mode = "2-way";
		   			else
		   				$mode = "";
   					$htmltxt=$AustGolf_Addon->get_transport_name($temptp1["0"]).(!empty($mode)?"  ".$mode:"")."  ".(!empty($temptp["1"])?$temptp["1"]:""); 					
	   			}
	   			else if($i==14){
	   				//Room type
	   				$htmltxt=($temptxt);
	   			}
	   			else if($i==15){
	   				//Comment
	   				$htmltxt=html_entity_decode($temptxt);
	   				if($htmltxt!=="")
	   					$htmltxt=$htmltxt;
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
		//Arrival and Departure detail with Date, Time, Flight and Airport
		$arr_flight=$aflight[1]; $dep_flight=$dflight[1];
		if ($arr_flight=="BI")$arr_flight = "Brisbane Internation"; 
		else if ($arr_flight=="DA")$arr_flight = "Brisbane Domestic";
		else $arr_flight = "NA";
		if($dep_flight=="BI")$dep_flight="Brisbane Internation";
		else if($dep_flight=="DA")$dep_flight="Brisbane Domestic";
		else $dep_flight="NA";
			echo "<td>".$adate."</td><td>".$atime."</td><td>".$aflight[0]."</td><td>".$arr_flight."</td>";
			echo "<td>".$ddate."</td><td>".$dtime."</td><td>".$dflight[0]."</td><td>".$dep_flight."</td>";
			
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
   				echo "<td valign=top class=fname1>".$htmltxt."<br /></td>\n";
			}
			else{
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
			}
		}
		//end Activities 
		//Handicap actions
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
			print "</table></p><p STYLE='page-break-before: always'></p><table width='100%' border='1' cellspacing='0' cellpadding='0'  valign=top>";
			print $tableheader;
			
		}
	}
	print "</table><p STYLE='page-break-before: always'></p>";
print "<div align=center class=ftitle5>Activities Master List</div>";
?><br /><br />
          <TABLE  border=0 align=center>
              <TBODY>
				<TR vAlign=top>
              <td align=center colspan=3 class=fname1><br/>Report Total <input class=LegendFooter type=button value="(Legend)" onclick="WindowsOpen('index.php?s=legend&as=view',500,350)"></td>
              </tr>
              <TR vAlign=top>
              </td>
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
<div align=center>[ <a href="javascript:history.back();">Back</a> ][ <a href="javascript:window.print();">Print</a> ]</div>
<?php

}
else
{
	$User_Info->go_page("error");
}


}
?>
