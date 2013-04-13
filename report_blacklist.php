<?
/*
 * report_blacklist.php - flag for previous year participants and asian final winners -- marc 20070521
*/
//do the access checks here
if(!$Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],view)) {
	$User_Info->go_page("error");        
} else { // start admin content
	if ($_POST['whitelist']) {
		$query = "UPDATE `cust_tb` SET `blacklist` = '' WHERE `cust_id` = '{$_POST['cust_id']}'";
//echo $query;
		$result = $User_Info->doSQL($query);
		echo "<div>{$_POST['name']} has been removed from the blacklist.</div>";
	} else if ($_POST['blacklist'] == "World Final") {
		$query = "UPDATE `cust_tb` SET `blacklist` = 'World Final' WHERE `cust_id` = '{$_POST['cust_id']}'";
//echo $query;
		$result = $User_Info->doSQL($query);
		echo "<div>{$_POST['name']} has been added to the blacklist.</div>";
	} else if ($_POST['blacklist'] == "Asian Final") {
		$query = "UPDATE `cust_tb` SET `blacklist` = 'Asian Final' WHERE `cust_id` = '{$_POST['cust_id']}'";
//echo $query;
		$result = $User_Info->doSQL($query);
		echo "<div>{$_POST['name']} has been added to the blacklist.</div>";
	}
?>
<style>
form, input, button {
border-width : 0;
padding : 0;
margin : 0;
}
button {
background-color : #ccf;
border-style : solid;
border-width : 1px;
border-color : #99f;
font-size : 11px;
font-family : arial;
}
</style>
<br>
<div class="ftitle5" align="center">
<?
	if ($view == "blacklist") echo "Blacklisted Participants";
	else if ($view == "matchwinner") echo "Participant Matches for Past Winners";
	else if ($view == "matchplayer") echo "Participant Matches for Previous Year Players";
	else echo "Past Winners";
?>
</div>
<br>
<div align="center" class="fname1">
	<b>
		<a href="?s=report_blacklist&tbn=past_winners&as=view&view=blacklist">View Blacklist</a> |
		<a href="?s=report_blacklist&tbn=past_winners&as=view&view=matchwinner">View Winner Matches</a> |
		<a href="?s=report_blacklist&tbn=past_winners&as=view&view=matchplayer">View Participant Matches</a> <!--|
		<a href="?s=report_past_winners&tbn=past_winners&as=view">View Past Winners</a>-->
	</b>
</div>
<br>
<?
if ($view == "blacklist") { // start blacklist
?>
<table border="1" cellspacing="0" cellpadding="2" align="center" width="55%">
	<tr>
		<td class="ftitle1" width="3%">&nbsp;</td>
		<td class="ftitle1" width="20%">Country</td>
		<td class="ftitle1">Participant Name</td>
		<td class="ftitle1" width="20%">Details</td>
		<td class="ftitle1" width="10%">Event</td>
		<td class="ftitle1" width="3%">&nbsp;</td>
	</tr>
<?
	$query = "SELECT `country_name`, `family_name`, `first_name`, `cust_id`, `blacklist` FROM `cust_tb` WHERE `blacklist` != '' ORDER BY `country_name`, `family_name`, `first_name`";
	$result = $User_Info->doSQL($query);

	while($row = mysql_fetch_array($result)) {
		$i++;
?>
	<tr>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $i ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $row['country_name'] ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $row['family_name'] . " " . $row['first_name'] ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>">
		<a href="index.php?s=view_record&tbn=cust_tb&id=<? echo $row['cust_id'] ?>&tcol=0&as=view">View</a>
		</td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $row['blacklist'] ?></td>
		<td class="fname1" align="center"  style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>">
			<form style="border-width:0;padding:0;margin:0" action="" method="post">
				<input type="hidden" name="name" value="<? echo $row['family_name'] . ' ' . $row['first_name'] ?>" />
				<input type="hidden" name="cust_id" value="<? echo $row['cust_id'] ?>" />
				<input type="hidden" name="whitelist" value="true" />
				<button type="submit">Whitelist</button>
			</form>
		</td>
	</tr>
<?
	}
?>
</table>
<br>
<?
} else if ($view == "matchwinner") { // start winner list
?>
<table border="1" cellspacing="0" cellpadding="2" align="center" width="60%">
	<tr>
		<td class="ftitle1" width="3%">&nbsp;</td>
		<td class="ftitle1" width="10%">Country</td>
		<td class="ftitle1">Winner Name</td>
		<td class="ftitle1" width="8%">Year Won</td>
		<td class="ftitle1">Match Results for Current Participants</td>
		<td class="ftitle1" width="3%">&nbsp;</td>
	</tr>
<?
	$query = "SELECT * FROM `past_winners` ORDER BY `country`, `year`, `name`";
	$WinList = $User_Info->doSQL($query);

	while($WinRow = mysql_fetch_array($WinList)) {
		$tmp_str = preg_replace('/,/', ' ', $WinRow['name']);
		$searchitems = explode(" ", $tmp_str);

		$sql_str = "";
		foreach ($searchitems as $val) {
			$sql_str .= " AND CONCAT(`family_name`, ' ',`first_name`) LIKE '%$val%'";
		}

		$query = "SELECT `country_name`, `family_name`, `first_name`, `cust_id`, `blacklist`,
				CONCAT(`family_name`, ' ',`first_name`) AS `fullname`
				FROM `cust_tb`
				WHERE 1
				AND `blacklist` = ''
				$sql_str";

		$result = $User_Info->doSQL($query);
		if (mysql_num_rows($result) == 0) continue;
		$i++;
		
		while($row = mysql_fetch_array($result)) { // start matching
?>
	<tr>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $i ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['country'] ?></td>
		<td class="fname1" align="center"  style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['name'] ?></td>
		<td class="fname1" align="center"  style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['year'] ?></td>
		<td class="fname1" align="center"  style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>">&nbsp;
			<a href="index.php?s=view_record&tbn=cust_tb&id=<? echo $row['cust_id'] ?>&tcol=0&as=view" title="View Details"><? echo $row['family_name'] . " " . $row['first_name'] ?></a> (<? echo $row['country_name'] ?>)<br>
		</td>
		<td class="fname1" align="center"  style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>">
			<form style="border-width:0;padding:0;margin:0" action="" method="post">
				<input type="hidden" name="name" value="<? echo $row['family_name'] . ' ' . $row['first_name'] ?>" />
				<input type="hidden" name="cust_id" value="<? echo $row['cust_id'] ?>" />
				<input type="hidden" name="blacklist" value="World Final" />
				<button type="submit">Blacklist</button>
			</form>
		</td>
	</tr>
<?
		}
	} // end matching
?>
</table>
<br>
<?
} else if ($view == "matchplayer") { // start player list
?>
<table border="1" cellspacing="0" cellpadding="2" align="center" width="60%">
	<tr>
		<td class="ftitle1" width="3%">&nbsp;</td>
		<td class="ftitle1" width="10%">Country</td>
		<td class="ftitle1">Participant Name</td>
		<td class="ftitle1" width="10%">Year Particpated</td>
		<td class="ftitle1">Match Results for Current Participants</td>
		<td class="ftitle1" width="3%">&nbsp;</td>
	</tr>
<?
	$prev_year = $_SESSION['sThisYear']-1;
	$query = "SELECT * FROM `past_players` WHERE `year` = '$prev_year' AND `player_type` = 'T' ORDER BY `country`, `year`, `name`";
	$WinList = $User_Info->doSQL($query);

	while($WinRow = mysql_fetch_array($WinList)) {
		$tmp_str = preg_replace('/,/', ' ', $WinRow['name']);
		$searchitems = explode(" ", $tmp_str);
		
		$sql_str = "";
		foreach ($searchitems as $val) {
			$sql_str .= " AND CONCAT(`family_name`, ' ',`first_name`) LIKE '%$val%'";
		}

		$query = "SELECT `country_name`, `family_name`, `first_name`, `cust_id`, `blacklist`,
				CONCAT(`family_name`, ' ',`first_name`) AS `fullname`
				FROM `cust_tb`
				WHERE 1
				AND `blacklist` = ''
				$sql_str";

		$result = $User_Info->doSQL($query);
		if (mysql_num_rows($result) == 0) continue;
		
		while($row = mysql_fetch_array($result)) { // start matching
			$i++;
?>
	<tr>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $i ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['country'] ?></td>
		<td class="fname1" align="center"  style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['name'] ?></td>
		<td class="fname1" align="center"  style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['year'] ?></td>
		<td class="fname1" align="center"  style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>">&nbsp;
			<a href="index.php?s=view_record&tbn=cust_tb&id=<? echo $row['cust_id'] ?>&tcol=0&as=view" title="View Details"><? echo $row['family_name'] . " " . $row['first_name'] ?></a> (<? echo $row['country_name'] ?>)<br>
		</td>
		<td class="fname1" align="center"  style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>">
			<form style="border-width:0;padding:0;margin:0" action="" method="post">
				<input type="hidden" name="name" value="<? echo $row['family_name'] . ' ' . $row['first_name'] ?>" />
				<input type="hidden" name="cust_id" value="<? echo $row['cust_id'] ?>" />
				<input type="hidden" name="blacklist" value="Asian Final" />
				<button type="submit">Blacklist</button>
			</form>
		</td>
	</tr>
<?
		}
	} // end matching
?>
</table>
<br>
<?
} else { // start fulllist
?>
<table border="1" cellspacing="0" cellpadding="2" align="center" width="55%">
	<tr>
		<td class="ftitle1" width="3%">&nbsp;</td>
		<td class="ftitle1" width="10%">Country</td>
		<td class="ftitle1" width="25%">Participant Name</td>
		<td class="ftitle1" width="5%">Year Won</td>
	</tr>
<?
	$query = "SELECT * FROM `past_winners` ORDER BY `country`, `year`, `name`";
	$WinList = $User_Info->doSQL($query);

	while($WinRow = mysql_fetch_array($WinList)) {
		$i++;
	?>
	<tr>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $i ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['country'] ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['name'] ?></td>
		<td class="fname1" align="center" style="background-color:#<? if ($i%2) echo "eef"; else echo "ddf" ?>"><? echo $WinRow['year'] ?></td>
	</tr>
	<?
	}
?>
</table>
<br>
<?
} // end fulllist
} // end admin content
?>
