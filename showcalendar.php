<?
function ShowError($pErrStr, $pAddStr=""){
?>
    <p class=ErrorMsg align=center>Error: <?=$pErrStr;?></p>
    <p align=center><b><?=$pAddStr;?></b></p>
    <br><br>
    <div align=center>
        <input type="button" class=ButtonStyle onclick="window.opener.focus(); window.close();" value="Close">&nbsp;&nbsp;
    </div>
<?
	exit;
}

//api_startup($AppID, $L3_Protected,$L3_Perm, false);
//ui_startup(false);

$DaysLabel = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
$DaysFullLabel = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
$Weekend = array(0, 6);
$MonthLabel = array(1=>"January", 2=>"February", 3=>"March", 4=>"April", 5=>"May", 6=>"June", 7=>"July", 8=>"August", 9=>"September", 10=>"October", 11=>"November", 12=>"December") ;

if(!$selectedDay || $selectedDay==0)
  	$selectedDay = date('d');
if(!$selectedMonth || $selectedMonth==0)
	$selectedMonth = date('m');
if(!$selectedYear || $selectedYear==0)
	$selectedYear = date('Y');
?>

<fieldset style="width: 350px" align=center>
<legend align="left"><b>&nbsp;<?=date('l, j M Y', mktime(0,0,0,$selectedMonth,$selectedDay,$selectedYear));?>&nbsp;<?
echo ($selectedDay==date('d') && $selectedMonth==date('m') && $selectedYear==date('Y'))?"(Today)":"(Selected)";
?></b></legend>
<br>

<script type="text/javascript">
<!--
	var ObjectsType;

	if(window.opener.document.getElementById)
		ObjectsType="DOM2"
	else
		if(window.opener.document.all)
			ObjectsType="IEDOM1"
		else
			if(window.opener.document.layers)
				ObjectsType="NCDOM1";

	function GetObjectById(IdName){
		switch(ObjectsType){
			case "DOM2":
				return window.opener.document.getElementById(IdName);
			break;
			case "IEDOM1":
				return window.opener.document.all[IdName];
			break;
			case "NCDOM1":
				return window.opener.document.layers[IdName];
			break;
		}
	}

	function ReloadMonth(val){
		window.location='index.php?s=showcalendar&name=<?=$name?>&selectedDay=<?=$selectedDay;?>&selectedYear=<?=$selectedYear?>&selectedMonth='+val;
	}

	function ReloadYear(){
		var Year=parseInt(InputYear.value);
		var Month=parseInt(InputMonth.value);
		if(Year){
			if(Year>=1970 && Year<=2020)
				window.location='index.php?s=showcalendar&name=<?=$name?>&selectedDay=<?=$selectedDay;?>&selectedMonth='+Month+'&selectedYear='+Year;
			else
				alert('Year must be between 1970 - 2020!');
		}else
			alert('Enter valid year!');
	}

	function ScrollYear(AddVal){
		var Year=parseInt(InputYear.value);
		if(Year){
			if((Year+AddVal)>=1970 && (Year+AddVal)<=2020){
				InputYear.value=Year+AddVal;
			}else{
				if(Year==2020)
					InputYear.value=1970;
				else
					if(Year==1970)
						InputYear.value=2020;
			}
			ReloadYear();
		}
	}

	function ScrollMonth(AddVal){
		var Month=parseInt(InputMonth.value);
		if((Month+AddVal)>=1 && (Month+AddVal)<=12){
			InputMonth.value=Month+AddVal;
			ReloadMonth(Month+AddVal);
		}else{
			if(Month==12)
				InputMonth.value=1;
			else
				InputMonth.value=12;
			ScrollYear(AddVal);
		}

	}
	
	function SetNow(){
		today = new Date();
		window.location='index.php?s=showcalendar&name=<?=$name?>&selectedDay='+today.getDate()+'&selectedMonth='+(today.getMonth()+1)+'&selectedYear='+today.getFullYear();
	}
	
	function DataSelected(Day, Month, Year){
		var DayStr="";
		var MonthStr="";
		if(Day<10)
			DayStr="0";
		DayStr=DayStr+Day;
		if(Month<10)
			MonthStr="0";
		MonthStr=MonthStr+Month;
		window.opener.focus();
		GetObjectById('<?=$name?>D').value=DayStr;
		GetObjectById('<?=$name?>M').value=MonthStr;
		GetObjectById('<?=$name?>Y').value=Year;
		window.close();
	}
// -->
</script>
<?

echo "<table align=center border=0 cellspacing=0 cellpadding=0>\n";
echo "<tr>\n";
echo "<td rowspan=\"2\">\n";

echo "<select name=\"InputMonth\" onchange=\"javascript: ReloadMonth(value);\">\n";
foreach($MonthLabel as $MonthNumber => $MonthName)
	echo "<option value=\"".$MonthNumber."\"".(($selectedMonth==$MonthNumber)?" SELECTED":"").">".$MonthName."</option>\n";
echo "</select>&nbsp;\n";

echo "</td>";
echo "<td><a href=\"javascript: ScrollMonth(-1);\"><img src=\"images/up.gif\" border=0 alt='Show Previous Month'></a></td>";
echo "<td rowspan=\"2\" width=\"25px\">&nbsp;</td><td rowspan=\"2\">\n";
echo "<input type=\"text\" name=\"InputYear\" style=\"width: 80px\" value=\"".$selectedYear."\" onchange=\"javascript: ReloadYear();\">&nbsp;\n";

echo "</td><td><a href=\"javascript: ScrollYear(-1);\"><img src=\"images/up.gif\" border=0 alt='Show Previous Year'></a></td>\n";
echo "<td rowspan=\"2\" width=\"25px\">&nbsp;</td><td rowspan=\"2\">\n";
echo "<a href=\"javascript: SetNow();\"><img src=\"images/clock.gif\" border=0 alt=\"Show Today's Date\"></a></td></tr>\n";
echo "<tr><td><a href=\"javascript: ScrollMonth(1);\"><img src=\"images/down.gif\" border=0 alt='Show Next Month'></a></td><td><a href=\"javascript: ScrollYear(1);\"><img src=\"images/down.gif\" border=0 alt='Show Next Year'></a></td></tr>";
echo "</table>\n";

echo "<br>";

$firstday = date('w', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));

$lastday = 31;

do{
	$monthOrig = date('m', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));
	$monthTest = date('m',mktime(0, 0, 0, $selectedMonth, $lastday, $selectedYear));
	if($monthTest!=$monthOrig)
		$lastday-=1;
}while($monthTest!=$monthOrig);

$monthName = date('F', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));

$dayRow = 0;
echo "<table align=\"center\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\" width=\"350px\">\n";

echo "<tr class=ftitle align=\"center\">\n";
for($i=0; $i<=6; $i++)
	echo "<td width=\"14%\">".$DaysLabel[$i]."</td>\n";
echo "</tr>\n";

$j=0;
while($dayRow < $firstday) {
	$j++;
	if ($j==1) echo "<tr class=\"fvalue\" align=\"right\">\n";
	$class=(in_array($dayRow % 7, $Weekend))?" class=\"fname\"":" class=\"fvalue\"";
	echo "<td".$class."><!-- This day in last month --></td>";
	$dayRow += 1;
}

$day = 0;

while($day < $lastday) {
	if(($dayRow % 7)==0) {
		if ($j>0) echo "</tr>\n" ;
		echo "<tr class=\"fvalue\" align=\"right\">\n";
	}
	$adjusted_day = $day+1;
	$class=($adjusted_day==date('d') && $selectedMonth==date('m') && $selectedYear==date('Y'))?" class=\"cal_Today\"":"";
	$wclass=(in_array($dayRow % 7, $Weekend))?" class=\"fname\"":"";
	$CurDayStyle=($adjusted_day==$selectedDay)?" style=\"font-style: italic; color:red;font-weight: bold\"":"";

	echo "<td".($class?$class:$wclass)."><a".$CurDayStyle." href=\"javascript: DataSelected(".$adjusted_day.",".$selectedMonth.",".$selectedYear.");\">$adjusted_day</a></td>";

	$day+=1;
	$dayRow+=1;
}

while(($dayRow % 7)!=0){
	$class=(in_array($dayRow % 7, $Weekend))?" class=\"fname\"":" class=\"fvalue\"";
	echo "<td".$class."><!-- This day in next month --></td>";
	$dayRow += 1;
}

  echo "\n</tr>\n</table>\n";
?>

<br>
</fieldset>

<?
//api_shutdown();
?>
