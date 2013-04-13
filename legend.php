<?php 
//include_once("AustGolf_Addon.php");
//$AustGolf_Addon=new AustGolf_Addon();
///require_once("classes/Addon.php");
//$Paging = new Addon;
$_GET["as"]="view";
//if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
//{
	//print "<br/><a href='".$_SERVER["PHP_SELF"]."?s=admin'>Click here to return</a><br />";
?>

<br />
<?php
		
	?>
        <TABLE border=1 align="center" width=450>
              <TBODY>
              <TR vAlign=top>
              <td class=ftitle1 colspan=3 align=center >Legend</td>
              </tr>
              <TR vAlign=top>
				<td class=ftitle1>Room</td>
				<td class=ftitle1 >Transport</td>
				<td class=ftitle1>Activities</td>

			</TR>
			<tr>
			<td valign=top class=fname1 width=25%><?
			$roomresult=$User_Info->dosearchSQL("ref_room_tb","","room_short_form","inf");
    		while($roomrow=mysql_fetch_array($roomresult))
    		{
    			print "<b>".$roomrow["room_short_form"]."</b> - ".$roomrow["room_name"]."<br/> ";
				//transport_name  transport_sh 
			}
			?>
			</td>
			<td valign=top class=fname1 width=25%>
			<?
			$tranresult=$User_Info->dosearchSQL("ref_transport_tb","","transport_sh","inf");
    		while($tranrow=mysql_fetch_array($tranresult))
    		{
    			print "<b>".$tranrow["transport_sh"]."</b> - ".$tranrow["transport_name"]."<br/> ";
				//transport_name  transport_sh 
			}
			?>
			</td>
			<td valign=top class=fname1 width=25%><?
			// Modified by William on April 20, 2006. Display items that is >= today
			$actresult=$User_Info->dosearchSQL("ref_act_tb","act_date>=CURDATE()","act_name","inf");			
    		while($actrow=mysql_fetch_array($actresult))
    		{
    			print "<b>".$actrow["act_name"]."</b> - ".$actrow["act_description"]."<br/> ";
				//transport_name  transport_sh 
			}
			?>
			</td>
			</tr>
		</TBODY></TABLE>

<?php
/*
}

}
else{
$Paging->backpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
$Paging->nextpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
print "<br/>";
$Paging->listpaging($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
}
*/
/*
}
else
{
	$User_Info->go_page("error");
}
*/
?>
