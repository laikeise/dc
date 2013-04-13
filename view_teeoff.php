<?php
//set up of the tmp structures
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
$tblist=$User_Info->getFieldName("$tmptb");
if(!isset($_GET["tp"]))
{
	$_GET["tp"]="";
}
$_GET["as"]="edit";
//do the access checks here
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])){

?><span class="ftitle3">Tee-off Detail</span>
<br />
<?php 
if(isset($_POST["Add_TeeOff"])){
		//print_r($_POST);
		//[bus_date] => [tee_time_hr] => X [tee_time_min] => X [venue] => [person] => 1 [Add_TeeOff] => Add New Tee Off Time
		if($_POST["bus_date"]==""||$_POST["tee_time_hr"]=="X"||$_POST["tee_time_min"]=="X"||$_POST["venue"]==""||$_POST["person"]=="")
		{
			//$_POST["bus_date"]==""||$_POST["tee_time_hr"]=="X"||$_POST["tee_time_min"]=="X"||$_POST["venue"]==""||$_POST["person"]==""	
			print "<table><tr><td width=25%>&nbsp;</td><td width=50%>";
			print "<br/><b><font color='red'>Sorry, there are missing information from this registration form. Please complete the following fields so that the registration can be successfully saved.</b>";
			print "</font></td><td width=25%>&nbsp</td></tr>";
			print "<tr><td width=25%>&nbsp;</td><td width=50%>";
			print "<table><tr><td width=30%>&nbsp;</td><td width=40%><font color='red'>";
			if($_POST["bus_date"]=="")
				print "<br/>- Bus Date";
			if($_POST["tee_time_hr"]=="X")
				print "<br/>- Tee-off time hour";
			if($_POST["tee_time_min"]=="X")
				print "<br/>- Tee-off time min";
			if($_POST["venue"]=="")
				print "<br/>- Venue";
			if($_POST["person"]=="")
				print "<br/>- Person";
					
			print "</font></td><td width=30%>&nbsp</td></tr></table>";
			print "</td><td width=25%>&nbsp</td></tr></table>";
			print "<hr><br/>";
			$showform=1;
		}
		else
		{
			//$tempsql="insert into $tmptb set ";	
			$cust_bus_list_result= $User_Info->dosearchSQL("bus_list",("bus_date=\"".$_POST["bus_date"]."\"and bus_destination=\"".$_POST["venue"]."\""),"","inf");
			if($cust_bus_list_result)
			{
				print "<form name='formupdate' method='post' action='".$_SERVER["REQUEST_URI"]."'>\n";
			?>
			<table  border='1' cellspacing='0' cellpadding='1' align=center>
				<tr>
				<td class=ftitle1 vAlign=top align=center colspan=7>Avaliable Bus
				</td>
				<tr>
				<td class=fname1 vAlign=top align=center colspan=7>You have made the selected option:- 
				<br/>Bus Date : <?=strftime("%d-%m-%Y", strtotime($_POST["bus_date"]))?>
				<br/>Bus Time : <?=strftime("%H:%M", strtotime($_POST["tee_time_hr"].":".$_POST["tee_time_min"]))?>
				<br/>Venue : <?=$AustGolf_Addon->get_venue_name($_POST["venue"])?>
				<br/>Required Seats : <?=$_POST["person"]?>
				</td>
				</tr>
				<tr>
					<td class=ftitle1 vAlign=top align=center width=15%>
					Bus Name</td>
					<td class=ftitle1 vAlign=top align=center width=15%>
					Date</td>
					<td class=ftitle1 vAlign=top align=center width=15%>
					Time</td>
					<td class=ftitle1 vAlign=top align=center width=15%>
					Space</td>
					<td class=ftitle1 vAlign=top align=center width=15%>
					Pickup</td>
					<td class=ftitle1 vAlign=top align=center width=15%>
					Destination</td>
					<td class=ftitle1 vAlign=top align=center width=15%>
					Action</td>
				</tr>
				<?
				while($cust_bus_list_row=mysql_fetch_array($cust_bus_list_result))
				{
					$diff=(strtotime($cust_bus_list_row["bus_time"])-strtotime($_POST["tee_time_hr"].":".$_POST["tee_time_min"]));
					if($diff<0)
						$diff=-($diff);
					if($diff<=1200&&($AustGolf_Addon->calculate_bus_space($cust_bus_list_row["bus_id"])>0&&$_POST["person"]<=$AustGolf_Addon->calculate_bus_space($cust_bus_list_row["bus_id"])))
						$colorclass="fname3";
					else
						$colorclass="fname1";
					print "<tr >";
					print "<td class=$colorclass vAlign=top align=center width=15% bgcolor=\"#000000\">".$cust_bus_list_row["bus_name"]."</td>";
					print "<td class=$colorclass vAlign=top align=center width=15%>".strftime("%d-%m-%Y", strtotime($cust_bus_list_row["bus_date"]))."</td>";
					print "<td class=$colorclass vAlign=top align=center width=15%>".strftime("%H:%M", strtotime($cust_bus_list_row["bus_time"]))." </td>";
					print "<td class=$colorclass vAlign=top align=center width=15%>".$AustGolf_Addon->calculate_bus_space($cust_bus_list_row["bus_id"])." / ".$cust_bus_list_row["bus_space"]."</td>";
					print "<td class=$colorclass vAlign=top align=center width=15%>".($AustGolf_Addon->get_venue_name($cust_bus_list_row["bus_pickup"]))."&nbsp;</td>";
					print "<td class=$colorclass vAlign=top align=center width=15%>".($AustGolf_Addon->get_venue_name($cust_bus_list_row["bus_destination"]))."&nbsp;</td>";
					print "<td class=$colorclass vAlign=top align=center width=15%>";
					if($AustGolf_Addon->calculate_bus_space($cust_bus_list_row["bus_id"])>0&&$_POST["person"]<=$AustGolf_Addon->calculate_bus_space($cust_bus_list_row["bus_id"]))
						print "<input name=\"choosen_bus\" type=\"radio\" value=\"".$cust_bus_list_row["bus_id"]."\" />".$_POST["person"];
					print "&nbsp;</td>";
					print "</tr>";
					//print "1</br>";
					//print $cust_bus_list_row["bus_name"].$cust_bus_list_row["bus_date"].$cust_bus_list_row["bus_time"].$cust_bus_list_row["bus_space"].$cust_bus_list_row["bus_pickup"].$cust_bus_list_row["bus_destination"];
				
				}?>
			<tr>
				<td class=ftitle1 vAlign=top align=center colspan=7>
				<?
					print "<input type=\"hidden\" name=bus_date value=\"".$_POST["bus_date"]."\">\n";
					print "<input type=\"hidden\" name=bus_time value=\"".$_POST["tee_time_hr"].":".$_POST["tee_time_min"]."\">\n";
					print "<input type=\"hidden\" name=venue value=\"".$_POST["venue"]."\">\n";
					print "<input type=\"hidden\" name=person value=\"".$_POST["person"]."\">\n";
					
				?>
				<INPUT type=Submit value="Allocate Bus" name=Allocate_Bus>
				</td>
				</tr>
			</table>
			</form>
				<?
			}
		}
		
			//print "post";
}
else if(isset($_POST["Allocate_Bus"])){
	//print "part 2";
	//print_r($_POST);
	
	//2Array ( [choosen_bus] => 1 [bus_date] => 2004-08-05 [bus_time] => 7:8 [venue] => LGC [person] => 1 [Allocate_Bus] => Allocate Bus ) 
	if(isset($_POST["bus_date"]))
	{
		$cust_add_sql=$cust_add_sql."tee_date=\"".$_POST["bus_date"]."\",";
		$cust_add_flag=1;
	}
	if(isset($_POST["bus_time"]))
	{
		$cust_add_sql=$cust_add_sql."tee_time=\"".$_POST["bus_time"]."\",";
		$cust_add_flag=1;
	}
	if(isset($_POST["venue"]))
	{
		$cust_add_sql=$cust_add_sql."tee_venue=\"".$_POST["venue"]."\",";
		$cust_add_flag=1;
	}
	if(isset($_POST["person"]))
	{
		$cust_add_sql=$cust_add_sql."person=\"".$_POST["person"]."\",";
		$cust_add_flag=1;
	}
	
	if($cust_add_flag==1)
	{
		$cust_add_sql=substr($cust_add_sql,0,-1);
		$cust_add_sql= "insert into cust_tee_tb set cust_id=$tmpcolname ,".$cust_add_sql.";";
		$updarow=($User_Info->doUpdateSQL($cust_add_sql));
		$tempinsertid=mysql_insert_id();
		//print "<br/>".$cust_add_sql."<br/>";
	}
	
	if(isset($_POST["choosen_bus"]))
	{
		$cust_tee_sql= "insert into cust_tee_bus_tb set bus_id=".$_POST["choosen_bus"].", tee_id=".$tempinsertid.", person=".$_POST["person"].";";
		
		//print $cust_tee_sql."<b>XX</b>";
		$updarow=$updarow+($User_Info->doUpdateSQL($cust_tee_sql));
	}
	
	if($updarow){
		//$AustGolf_Addon->user_control($tempinsertid);
		$AustGolf_Addon->action_log($tempinsertid,"add");
		print "<p align=center>Bus Allocated.<br/><br/>";
		print "<INPUT type=button value=\"Back to Home Page\" onclick=\"window.location='index.php?s=view_cust&tbn=cust_tb&as=view'\">";
		print "<br/><br/><INPUT type=button value=\"Back to Previous View\" onclick=\"window.location='".$_SESSION["returnURL"]."'\">";
	}
	else{
		print "Nothing allocated!";
	}
	
}
else if(isset($_GET["ltxt"])){
	print "Record has been deleted!<br/>";
	//print $tmpcolname;
	$tee_result=$User_Info->doSearchSQL("cust_tee_tb","tee_id=$tmpcolname","","inf");
	if($tee_row=mysql_fetch_array($tee_result))
	{	
		$delmainsql="delete from cust_tee_tb where tee_id=$tmpcolname";
		//print $delmainsql."<br>";
		$User_Info->sqlLog($delmainsql);
		$updarow=$updarow+($User_Info->doUpdateSQL($delmainsql));
		//print $delmainsql;
		//$updarow=$updarow+($User_Info->doUpdateSQL($delteesql));
	}
	?>
	<script type="text/javascript">
	opener.focus();
	opener.location.reload();
	window.close(); 
</script>

	
<?
}
else
	$showform=1;

if($showform==1){
	$tmpcol=$tblist[$tmpcolnum];
	$resultx= $User_Info->dosearchSQL("$tmptb",ctype_alnum($tmpcolname)?"$tmpcol='$tmpcolname'":"$tmpcol=$tmpcolname","","");
	print "<br />";
	print "<table cellSpacing=0 cellPadding=2 width='100%' border=1>\n";
	if($qrow=mysql_fetch_array($resultx))
	{
		//$cust_add_result= $User_Info->dosearchSQL("cust_add_tb",("cust_id=".$qrow["cust_id"]),"","");
		
		while (list($key, $val) = each($tblist)) {
   			//print $key.$val;
   				$temptxt=$qrow[$key];
   				//print $qrow[$key];
   				//$htmltxt=html_entity_decode($temptxt);
   				$htmltxt=$temptxt;
   			
				if($key==1||$key==2||$key==3||$key==4||$key==5){
   					//print "<input type='hidden' name='$val' value='".$htmltxt."' >";
   					print  "<tr>\n<td valign=top class=ftitle1 align=right width='20%'> " . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n<td valign=top class=fname1 align=left>";
	   				if($htmltxt==""){
						print "&nbsp;";}
					else{
					if($key==4)
						print $AustGolf_Addon->get_player_name($htmltxt);
					else
   						print $htmltxt;
					}	
					print "</td><td valign=top class=ftitle1 align=right width='10%'>&nbsp;</td>\n</tr>\n";
				}
		}
			
			
		
	}
	?>
	<tr>
	<td valign=top class=ftitle1 align=right width='20%' rowspan=2>Tee-off</td>
	<td valign=top class=fname1 align=left>
	
	<?
	$cust_tee_result= $User_Info->dosearchSQL("cust_tee_tb",("cust_id=".$qrow["cust_id"]),"","inf");
	if($cust_tee_row=mysql_fetch_array($cust_tee_result))
	{
	?>
	Current Tee-off Time Slot
	<table width=100%>
	<tr>
	<td class=ftitle1 vAlign=top align=center>Date&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center>Time&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center>Venue&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center>Seats Acquired&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center>Bus Name&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center>Action&nbsp;</td>
	</tr>
	<?
		mysql_data_seek($cust_tee_result,0);
		
		while($cust_tee_row=mysql_fetch_array($cust_tee_result)){
			$cust_bus_result= $User_Info->dosearchSQL("cust_tee_bus_tb",("tee_id=".$cust_tee_row["tee_id"]),"","inf");
			if($cust_bus_result)
			{
				while($cust_bus_row=mysql_fetch_array($cust_bus_result))
				{
					$cust_bus_info_result= $User_Info->dosearchSQL("bus_list",("bus_id=".$cust_bus_row["bus_id"]),"","inf");
					if($cust_bus_info_result)
					{
						$cust_bus_info_row=mysql_fetch_array($cust_bus_info_result);
							//print $cust_bus_info_row["bus_name"];
					}
	?>
		<tr>
		<td class=fname1 vAlign=top align=center><?=strftime("%d-%m-%Y", strtotime($cust_tee_row["tee_date"]))?>&nbsp;</td>
		<td class=fname1 vAlign=top align=center><?=strftime("%H:%M", strtotime($cust_tee_row["tee_time"]))?>&nbsp;</td>
		<td class=fname1 vAlign=top align=center><?=$AustGolf_Addon->get_venue_name($cust_tee_row["tee_venue"])?>&nbsp;</td>
		<td class=fname1 vAlign=top align=center><?=$cust_tee_row["person"]?>&nbsp;</td>
		<td class=fname1 vAlign=top align=center><?=$cust_bus_info_row["bus_name"]?>&nbsp;</td>
		<td class=fname1 vAlign=top align=center><?
		print "<a href=# onclick='return delWindow(\"s=view_teeoff&tbn=cust_tee_tb&id=".$cust_tee_row["tee_id"]."&tcol=0&as=deldata&ltxt=1&pi=1\",\"".$cust_bus_info_row["bus_name"]." on ".strftime("%d-%m-%Y", strtotime($cust_tee_row["tee_date"]))."\");return false;' >";
		print "[Delete]</a>";
		?></td>
		</tr>
	<?
				}
			}
			else
			{
				?>
			<tr>
		<td class=fname1 vAlign=top align=center>-&nbsp;</td>
		<td class=fname1 vAlign=top align=center>-&nbsp;</td>
		<td class=fname1 vAlign=top align=center>-&nbsp;</td>
		<td class=fname1 vAlign=top align=center>-&nbsp;</td>
		<td class=fname1 vAlign=top align=center>-&nbsp;</td>
		<td class=fname1 vAlign=top align=center>-&nbsp;</td>
		</tr>
			<?
			}
		}
	?>
	</table>
	<?
	}
	?><br/>&nbsp;
	</td>
	<td valign=top class=ftitle1 align=right width='10%' rowspan=2>&nbsp;</td>
	</tr>
	
	<tr>
	
	<td valign=top class=fname1 align=left>
	<?
		print "<form name='formupdate' method='post' action='".$_SERVER["REQUEST_URI"]."'>\n";
	?>
	Add New Tee-off Time Slot
	<table width=100%>
	<tr>
	<td class=ftitle1 vAlign=top align=center width=25%>Date&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center width=25%>Time&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center width=25%>Venue&nbsp;</td>
	<td class=ftitle1 vAlign=top align=center width=25%>Seats Required&nbsp;</td>
	</tr>
	<tr>
	<td class=fname1 vAlign=top align=center><?
	print "<select name='bus_date' class=fcommentsdark>";
    print "<option value='' " . (isset($_POST["bus_date"])?$_POST["bus_date"]:"") ." >--- Bus Date ---</option>";
		$rm_result=$User_Info->doSQL("select bus_id,bus_date from bus_list group by bus_date");
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_rm_name=$rm_row[1];
			$db_rm_id=$rm_row[0];
			print "<option value='" .$db_rm_name. "' " . ($db_rm_name==$_POST["bus_date"]?"selected":" ") ." >".(($db_rm_name!="0000-00-00")?strftime("%d-%m-%Y", strtotime($db_rm_name)):"nil")."</option>";
		}
		print "</select>";
	
	?></td>
	<td class=fname1 vAlign=top align=center><SELECT name=tee_time_hr><?
                  		$AustGolf_Addon->generate_hour((isset($_POST["tee_time_hr"])?$_POST["tee_time_hr"]:""));
                  ?></SELECT> : <SELECT name=tee_time_min><?
                  		$AustGolf_Addon->generate_min((isset($_POST["tee_time_min"])?$_POST["tee_time_min"]:""));
                  ?></SELECT>
                  &nbsp;</td>
	<td class=fname1 vAlign=top align=center><?=$AustGolf_Addon->generate_venue_list(isset($_POST["venue"])?$_POST["venue"]:"")?></td>
	<td class=fname1 vAlign=top align=center><input type="text" name="person" size=2 value="<?=isset($_POST["person"])?$_POST["person"]:"1"?>" />&nbsp;</td>
	</tr>
	</tr>
	<tr>
	<td class=fname1 vAlign=top align=center colspan=4><br/><INPUT type=hidden name=name_id value=<?=$qrow[0]?>><INPUT type=Submit value="Add New Tee Off Time" name=Add_TeeOff></td>
	</tr>
	</table>
	
	</form>
	&nbsp;
	</td>
	</tr>
	<?
	print "</table>";
	?>
	
		<!--<br/><div align=center><INPUT type=button value="Edit" name=edit onclick="window.location='http://<?=($_SERVER["HTTP_HOST"]).("/index.php?s=edit_cust&tbn=cust_tb&id=".$tmpcolname."&tcol=".$tmpcolnum."&as=view&tp=".$_GET["tp"])?>'"></div>-->
	<?
	print "</form>\n";
}	
	
?><br /><br />

<?php
}
else{
	$User_Info->go_page("error");
}

?>