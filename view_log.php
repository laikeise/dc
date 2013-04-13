<?php 
require_once("classes/Addon.php");
$Paging = new Addon;
$_GET["as"]="view";
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
	
?>
<br />
<?php 
		
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	
if(isset($_POST["View"]))
{	
	print "<table  width='100%' border='1' cellspacing='0' cellpadding='0'>";
	//<tr>
	//print $rmtotal;
	
	//print "<td valign=top class=ftitle1>Action</td>";
	//print "</tr>";
	
	if(isset($_GET["pg"]))
		$page=$_GET["pg"];
	else
		$page=1;

	//page control listing by 10 now	
	if(isset($_GET["ls"]))
		$list=$_GET["ls"];
	else
		$list=10;
	
	$pointer=$page*$list-$list;	
	
	//print ;
	if(isset($_POST["view_date"])&&$_POST["view_date"]!="")
	{
		print "Records changed since ".strftime("%d-%m-%Y",$_POST["view_date"]).".<br/><br/>";
		//dlog_tb_id 
		//header
		$tblist=$User_Info->getFieldName("cust_tb");
		ob_start();
		print "<tr>";
		print "<td valign=top class=ftitle1 align=left>Last Changed</td>";
		print "<td valign=top class=ftitle1 align=left>Change Action</td>";
		print "<td valign=top class=ftitle1 align=left>By</td>";
		print "<td valign=top class=ftitle1 align=left>Reason</td>";
		while (list($key, $val) = each($tblist)) {
   			//print $key.$val;
   				$temptxt=$qrow[$key];
   				//print $qrow[$key];
   				//$htmltxt=html_entity_decode($temptxt);
   				$htmltxt=$temptxt;
   			
   			if($key==0)
   			{
   				//print "<input type='hidden' name='$val' value='".$htmltxt."' >";
   			}
   			else{
   				print  "\n<td valign=top class=ftitle1 align=left> " . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n";
   				
   			}
   			
		}
		
		print "</tr>";
		$tableheader=ob_get_contents();
		ob_end_flush();
		
		$resultx= $User_Info->dosearchSQL("$tmptb",("UNIX_TIMESTAMP( dlog_time ) >= ".($_POST["view_date"]-86400)." and tb_name='cust_tb'"),"dlog_time DESC","inf","UNIX_TIMESTAMP(dlog_time) ,dlog_tb_id,dlog_action,dlog_reason,dlog_user ");
		
		while($qrow=mysql_fetch_array($resultx))
		{
			if(!isset($tempdate)){
				$tempdate=strftime("%d-%m-%Y", $qrow[0]);
				print "<tr><td valign=top class=fname1 align=center colspan=18><b>Changes made on $tempdate</b></td></tr>";
			}
			if($tempdate!=(strftime("%d-%m-%Y", $qrow[0])))
			{
				$tempdate=strftime("%d-%m-%Y", $qrow[0]);
				//print "xx";
				print "<tr><td valign=top class=fname1 align=center colspan=18><br/><br/></td></tr>";
				print $tableheader;
				print "<tr><td valign=top class=fname1 align=center colspan=18><b>Changes made on $tempdate</b></td></tr>";
			}
			//print_r($qrow);
			//print "<br>";
			if($qrow["dlog_action"]=="deldata")
				$record_sea= $User_Info->dosearchSQL("del_cust_tb",("cust_id=".$qrow["dlog_tb_id"]),"","inf");
			else
				$record_sea= $User_Info->dosearchSQL("cust_tb",("cust_id=".$qrow["dlog_tb_id"]),"","inf");
			
			print "<tr>";
			
			print "<td valign=top class=fname1 align=left>".strftime("%d-%m-%Y", $qrow[0])."</td>";
			print "<td valign=top class=fname1 align=left>".($qrow["dlog_action"])."</td>";
			print "<td valign=top class=fname1 align=left>".($qrow["dlog_user"])."</td>";
			print "<td valign=top class=fname1 align=left>".($qrow["dlog_reason"])."&nbsp;</td>";
			 
			if($record_row=mysql_fetch_array($record_sea))
			{
				
				//print $record_row["cust_id"];reset 
				reset($tblist);
				while (list($key, $val) = each($tblist)) {
   					$temptxt=$record_row[$key];
   					$htmltxt=$temptxt;
   					
   					if($key==0)
   					{
   						//print "<input type='hidden' name='$val' value='".$htmltxt."' >";
   					}
   					else{
   					$i=$key;
   						if($i==0){
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
	   			}
	   			else if($i==4){
	   				//player type
   					//$htmltxt=$AustGolf_Addon->get_player_name($temptxt);
   					$htmltxt=($temptxt);
	   			}
	   			else if($i==6){
	   				//date stay value
   					$htmltxt=html_entity_decode($temptxt);
   					if(((int)($htmltxt))<0)
   						$htmltxt="Invalid Dates";
	   			}
	   			else if($i==7||$i==10){
	   				//date
	   				$htmltxt=(strftime("%d-%b-%y", strtotime($temptxt)));
	   			}
	   			else if($i==8||$i==11){
	   				//date
	   				//strftime("%H:%M", strtotime($qrow["arrival_time"]))
	   				$htmltxt=strftime("%H:%M", strtotime($temptxt));
	   			}
	   			else if($i==9||$i==12){
	   				//date
	   				$aflight=explode(":~:",$temptxt);
	   				//strftime("%H:%M", strtotime($qrow["arrival_time"]))
	   				$htmltxt=$aflight[0].(isset($aflight[1])!=""?("<br/>".($aflight[1])):"");
	   			}
	   			else if($i==13){
	   				//transport
	   				$temptp=explode(":~:",$temptxt);
	   				$htmltxt=($temptp["0"])."<br/>".(isset($temptp["1"])?$temptp["1"]:"");
   					//$htmltxt=$AustGolf_Addon->get_transport_name($temptp["0"])."<br/>".(isset($temptp["1"])?$temptp["1"]:"");
	   			}
	   			else if($i==14){
	   				//Room type
	   				$htmltxt=($temptxt);
   					//$htmltxt=$AustGolf_Addon->get_room_name($temptxt);
	   			}
	   			else if($i==15){
	   				//Comment
	   				$htmltxt=html_entity_decode($temptxt);
	   				if($htmltxt!=="")
	   					$htmltxt=$htmltxt;
   						//$htmltxt="<img src='images/Subscribers.gif' alt=\"".$htmltxt."\">";
	   			}
   				else
   					$htmltxt=html_entity_decode($temptxt);
   				
   				if($htmltxt=="0"||$htmltxt==""||$htmltxt=="<br/>")
   				{
   						$htmltxt="&nbsp;-";
   				}
   				
   						//print  "\n<td valign=top class=ftitle1 align=left>" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n";
   						if($htmltxt=="")
   							print  "\n<td valign=top class=fname1 align=left>&nbsp;</td>\n";
   						else
   							print  "\n<td valign=top class=fname1 align=left>" . $htmltxt. "</td>\n";
		   			}
				}
				//print "<td valign=top class=fname1 align=left>&nbsp;</td>";
				
			}
			else
			{
				$view_delete= $User_Info->dosearchSQL("del_cust_tb",("cust_id=".$qrow["dlog_tb_id"]),"","inf");
				if($view_row=mysql_fetch_array($view_delete))
				{
					print  "\n<td valign=top class=fname1 align=center colspan=15><b>(".$view_row["family_name"]." ".$view_row["first_name"].")- Record have been deleted!</b></td>\n";
				}
			}
			//print "<td valign=top class=fname1 align=left>".($qrow["dlog_action"])."</td>";
			//print "<td valign=top class=fname1 align=left>".strftime("%d-%m-%Y", $qrow[0])."</td>";
			print "</tr>";
			
			
			//print $qrow[0]."<br>";
		}	
		
		
		
	}
	else
	{
		print "No selection was made! Click back and try again!";	
	}
/*	
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$tblist[0] DESC","$pointer,$list");
	}
	else{
		$resultx= $User_Info->dosearchSQL("$tmptb","tb_name=\"cust_tb\"","$tblist[0] DESC","$pointer,$list");
	}

	while($qrow=mysql_fetch_array($resultx))
	{
		print "<tr>";
		for($i=1;$i<$rmtotal;$i++) {
			if($i==0||$i==1||$i==4||$i==6)
			{
				//	
			}
			else if($i==3){
				echo "<td valign=top class=fname1>".(strftime("%d %B %Y", strtotime(substr($qrow[$i],0,8))))." ".substr($qrow[$i],8,2).":".substr($qrow[$i],10,2)."<br /></td>\n";
				//substr($qrow[$i],0,8)
				//(strftime("%d %B %Y %r", ($qrow[$i])))
			}
			else
			{
   			$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   			$htmltxt=html_entity_decode($temptxt);
   			echo "<td valign=top class=fname1>".$htmltxt."<br /></td>\n";
   			}
		}
		
		print "<td valign=top class=fname1>";
		if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],view)&&$qrow["dlog_flag"]!="deleted")
			print "&nbsp;<a href=".$_SERVER["PHP_SELF"]."?s=view_record&tbn=cust_tb&id=".$qrow["dlog_tb_id"]."&tcol=0&as=view&hd=1><!--<img src='images/filesearch.gif' border=0 />--> [View Record]</a>";
		if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],edit))
			print "<a href=".$_SERVER["PHP_SELF"]."?s=edit&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit><!--<img src='images/edit.gif' border=0 />-->[Edit]</a><br />\n";
		if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],deldata)){
		print "<br/><a href=# onclick='return delWindow(\"s=delete&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\",\"".urlencode($qrow[1])."\");return false;' >";
		print "<!--<img src=\"images/delete.gif\" border=\"0\" alt=\"Delete this Player\" >-->[Delete]</a>";
		}
		print "&nbsp;</td>\n";
		print "</tr>";
	}
*/
	print "</table>";


?><br /><br />


<?php
/*
$Paging->backpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
$Paging->nextpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
print "<br/>";
$Paging->listpaging($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
*/
//print "test";
}

else
{
	/*
	print strtotime("20040621")."- ".(strftime("%d-%m-%Y",strtotime("20040621")))."<br/>";
	print strtotime("20040622")."<br/>";
	print (strtotime("20040622")-strtotime("20040621"))."<br>";
	print strtotime("20040722")."<br/>";
	print strftime("%d-%m-%Y",time())."<br/>";
*/
?>
<br/><br/><br/><br/><br/><br/><br/><br/>
<div align=center>
View modifications made from present(today) to the selection date
<form name="ViewGroup" method="post" action="">
<select name='view_date' class=fcommentsdark>
<option value=''  selected >- Changes made since -</option>
<?
// change date accordingly -- marc 20080521
$startcounter=strtotime($_SESSION['sThisYear'] . '0101');
$endcounter=time();
while($startcounter<$endcounter)
{
	print "<option value='".$endcounter."'>".strftime("%d-%m-%Y -%a",$endcounter)."</option>\n";
	$endcounter=$endcounter-86400;
}

?>

</select>

<input type="submit" name="View" value="Submit">

</form>
</div>
<?
}

}
else
{
	$User_Info->go_page("error");
}
?>
