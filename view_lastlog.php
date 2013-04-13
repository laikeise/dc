<?
/*
 * view_lastlog.php - list last login times of system users -- marc 20070521
*/
//do the access checks here
if(!$Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],view)) {
	$User_Info->go_page("error");        
} else { // start admin content
?>
<br>
<div class="ftitle5" align="center">LastLog</div>
<br>
<table border="1" cellspacing="0" cellpadding="2" align="center" width="55%">
	<tr>
		<td class="ftitle1" width="3%">&nbsp;</td>
		<td class="ftitle1" width="25%">Userid</td>
		<td class="ftitle1">Name</td>
		<td class="ftitle1" width="20%">Lastlog</td>
	</tr>
<?
	$query = "SELECT `userp_login`, `userp_name`, `userp_last_time` FROM `user_profile` ORDER BY `userp_uid`";
	$WinList = $User_Info->doSQL($query);

	while($WinRow = mysql_fetch_array($WinList)) {
		$i++;
	?>
	<tr>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $i ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['userp_login'] ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['userp_name'] ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['userp_last_time'] ?></td>
	</tr>
	<?
	}
?>
</table>
<br>
<?
} // end admin content
?>
