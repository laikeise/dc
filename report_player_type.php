<?if (!isset($_POST['export_summary'])){?>
<form name="ViewGroup" method="post" action="">
	<table border="0" cellspacing="0" cellpadding="5" align="center">
	<!--<tr><td>
	Export this Report into Excel:&nbsp;<input type="submit" name="export_summary" value="Export"/></td></tr>-->
	</table>
</form>
<?}
/*if(isset($_POST['export_summary']) && $_POST['export_summary']=="Export"){
	$filename="Player_Summary".date("Ymd");
	header("Content-type: application/vnd.ms-excel"); 
	header("Content-Disposition: attachment; filename=".$filename.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}*/
?>
<br>
<div class="ftitle5" align="center">Player Type Summary</div>
<br>
<table border="1" cellspacing="0" cellpadding="2" align="center" width="100%">
<?	
	$TotalCount = 0;
	$aPlayerType = array("CC" => "Country Captain", "GPRO" => "Golf Pro", "GCOM" => "Golf Committee", "G" => "Guest Player", "O" => "Others", "T" => "Tournament Player");
	
	echo '<tr>';
	echo '<td class="ftitle1" align="left">Country Name</td>';
	foreach($aPlayerType as $value)
	{		
		echo '<td class="ftitle1" align="left">' .$value. '</td>';		
	}
	echo '</tr>';
	
	// Select countries
	$query = "select country_id ,country_name from ref_country_tb order by country_name";
	$Country = $User_Info->doSQL($query);
	while($CountryRow = mysql_fetch_array($Country))
	{		
		$aMyCustList[$CountryRow['country_name']] = '';
	}
	$aMyCustList['Others'] = '';

	$CustList = $User_Info->doSQL("SELECT * FROM cust_tb");
	while($CustListRow = mysql_fetch_array($CustList))
	{
		if (isset($aMyCustList[$CustListRow['country_name']]))
		{
			if (isset($aMyCustList[$CustListRow['country_name']][$CustListRow['player_type']]))
				$aMyCustList[$CustListRow['country_name']][$CustListRow['player_type']] = ($aMyCustList[$CustListRow['country_name']][$CustListRow['player_type']] + 1);
			else
				$aMyCustList[$CustListRow['country_name']][$CustListRow['player_type']] = 1;
		}
		else // Others countries
		{			
			if (isset($aMyCustList['Others'][$CustListRow['player_type']]))
				$aMyCustList['Others'][$CustListRow['player_type']] = ($aMyCustList['Others'][$CustListRow['player_type']] + 1);
			else
				$aMyCustList['Others'][$CustListRow['player_type']] = 1;
		}
	}
	
	
	foreach ($aMyCustList as $key => $value) 
	{
		$EachTotal = 0;
		foreach($aPlayerType as $thiskey => $this1value)
		{	
			$EachTotal = ($EachTotal + $value[$thiskey]);
		}

		echo '</tr>';
		echo '<td><strong>' .$key. '</strong> (Total: ' .$EachTotal. ')</td>';
		foreach($aPlayerType as $key2 => $value2)
		{
			if (!isset($Total[$key2])) $Total[$key2] = 0;
			if (!isset($value[$key2])) $value[$key2] = 0;
			$Total[$key2] = ($Total[$key2] + $value[$key2]);
			echo '<td class="fname1" align="center">' .$value[$key2]. '</td>';
		}
		echo '</tr>';	
	}
	
	$TotalType = 0;
	foreach($aPlayerType as $key3 => $value3)
	{
		$TotalType = ($TotalType + $Total[$key3]);
	}

	echo '</tr>';
	echo '<td>(Total): ' . $TotalType . '</td>';
	foreach($aPlayerType as $key4 => $value4)
	{
		echo '<td class="fname1" align="center">' .$Total[$key4]. '</td>';
	}
	echo '</tr>';
?>
</table>
<br>