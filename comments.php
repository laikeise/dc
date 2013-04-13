<?php
//set up of the tmp structures
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
$tblist=$User_Info->getFieldName("$tmptb");
if(!isset($_GET["tp"])) {
	$_GET["tp"]="";
}

$_GET["as"]="view";
//do the access checks here
if ($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])) {
?>
<span class="ftitle3">Comments</span>
<br />
<?php 
	//$rmtotal=0;
	//print $tmptb;
	//$tblist=$User_Info->getFieldName("$tmptb");
	//print_r($tblist);
	//print $tmpcol."test";
	//print $atb[$tmpcol]."test";
	$tmpcol=$tblist[$tmpcolnum];
	//print "Check - ".ctype_alnum($tmpcolname)."<br />";
	$resultx= $User_Info->dosearchSQL("$tmptb",ctype_alnum($tmpcolname)?"$tmpcol='$tmpcolname'":"$tmpcol=$tmpcolname","","");
	print "<br />";
	//print "<form name='formupdate' method='post' action='".$_SERVER["REQUEST_URI"]."'>\n";
	print "<table cellSpacing=0 cellPadding=2 width='100%' border=1>\n";
	
	if($qrow=mysql_fetch_array($resultx)) {
		print  "<tr>\n<td valign=top class=fname1 align=left>";
		print $qrow["comment"];
		print "</td>\n</tr>\n";
	}
	
	print "</table>";
?>
<br/>
<br/>
<br/>
<?php
} else {
	$User_Info->go_page("error");
}
?>