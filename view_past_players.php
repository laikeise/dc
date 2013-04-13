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
<div class="ftitle5" align="center">Participants from Previous Events</div>
<br>
<div align="center" class="fname1">
	<b>
<?
	$query = "SELECT `year` FROM `past_players` GROUP BY `year` ORDER BY `year`";
	$result = $User_Info->doSQL($query);

	while($row = mysql_fetch_array($result)) {
		if ( (!isset($year) && $row['year'] == date('Y') - 1) || $year == $row['year']) {
?>
		[ <? echo $row['year'] ?> ]
<?
		} else {
?>
		<a href="?s=view_past_players&tbn=past_players&as=view&year=<? echo $row['year'] ?>">[ <? echo $row['year'] ?> ]</a>
<?
		}
	}
?>
	</b>
</div>
<br>
<table border="1" cellspacing="0" cellpadding="2" align="center" width="55%">
	<tr>
		<td class="ftitle1" width="3%">&nbsp;</td>
		<td class="ftitle1" width="25%">Country</td>
		<td class="ftitle1">Participant Name</td>
		<td class="ftitle1" width="20%">Year Participated</td>
		<td class="ftitle1" width="10%">Player Type</td>
	</tr>
<?
	if (!isset($year)) $year = date('Y') - 1;
	$query = "SELECT * FROM `past_players` WHERE `year` = '$year' ORDER BY `country`, `name`, `year`";
	$WinList = $User_Info->doSQL($query);

	while($WinRow = mysql_fetch_array($WinList)) {
		$i++;
	?>
	<tr>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $i ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['country'] ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['name'] ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['year'] ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['player_type'] ?></td>
	</tr>
	<?
	}
?>
</table>
<br/>
<div style="text-align:center">[ <a href="javascript:window.print();">Print</a> ]</div>
<br/>
<?
} // end admin content
?>