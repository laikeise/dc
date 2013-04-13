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
$_GET["as"]="view";
//do the access checks here
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])){

?><span class="ftitle3">View Details</span>
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
	if($qrow=mysql_fetch_array($resultx))
	{
		$cust_add_result= $User_Info->dosearchSQL("cust_add_tb",("cust_id=".$qrow["cust_id"]),"","");
		if($cust_add_row=mysql_fetch_array($cust_add_result)){
			print "";
		}
		while (list($key, $val) = each($tblist)) {
   			//print $key.$val;
   				$temptxt=$qrow[$key];
   				//print $qrow[$key];
   				//$htmltxt=html_entity_decode($temptxt);
   				$htmltxt=$temptxt;
   			
				if($key!=0){
   					print "<input type='hidden' name='$val' value='".$htmltxt."' >";
   					print  "<tr>\n<td valign=top class=ftitle1 align=right width='20%'> " . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n<td valign=top class=fname1 align=left>";
	   				if($htmltxt==""){
						print "&nbsp;";}
					else{
   											print $htmltxt;
   						
					}	
					print "</td><td valign=top class=ftitle1 align=right width='10%'>&nbsp;</td>\n</tr>\n";
				}
		}
		
	}
	print "</table>";
	?>
	<!--<br/><div align=center><INPUT type=reset value="Edit" name=Reset onclick="window.location='http://<?=($_SERVER["HTTP_HOST"]).("/index.php?s=edit_cust&tbn=cust_tb&id=".$tmpcolname."&tcol=".$tmpcolnum."&as=view&tp=".$_GET["tp"])?>'"></div>-->
	<?
	print "</form>\n";
	
	
?><br /><br />

<?php
}
else{
	$User_Info->go_page("error");
}

?>