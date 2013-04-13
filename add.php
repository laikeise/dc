<?php 
//set up of the tmp structures
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
//print $tmpcolnum." ".$tmpcolname;
$tblist=$User_Info->getFieldName("$tmptb");
//print $_GET["tbn"]."b<br/>";
//do the access checks here
$_GET["as"]="adddata";
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])){
	//print_r($HTTP_POST_VARS);
	if(isset($_POST["Add"])){
		require_once("classes/Addon.php");
		$DateFormat= new Addon;
		//print "post";
		//print_r($HTTP_POST_VARS);
		
		
		//$tempsql_customer="insert into rob_arena_board values( NULL,'robrob','$subject','$message','$msg_date','$msg_time','$posttype')";
		$tempsql="insert into $tmptb values( ";
		//$tmptb=$_GET["tbn"];
		
		while (list($key, $val) = each($tblist)) {
			$tempstring=htmlentities($HTTP_POST_VARS["$val"]);
			if(empty($tempstring)){
				$tempsql=$tempsql."'NULL', ";
			}
			else{
				$tempsql=$tempsql."'".$tempstring."', ";
			}
		}
		
		$tempstrlen=strlen($tempsql)-2;
		$tempsql=substr($tempsql,0,$tempstrlen);
		$tempsql=$tempsql." );";
		$Error_Handler->print_error($tempsql);
		$updarow=$User_Info->doUpdateSQL($tempsql);
		//print $updarow;
		//print "$updarow records have been added!";
		if($updarow)
			print "Record have been added!";
		else
			print "Nothing added!";
		//print "test";
	}
	else{
?>

Add Details Page
<br />
<?php 
	require_once("classes/Addon.php");
	$DateFormat= new Addon;
	$rmtotal=0;
	//print $tmptb;
	//$tblist=$User_Info->getFieldName("$tmptb");
	//print_r($tblist);
	//print $tmpcol."test";
	//print $atb[$tmpcol]."test";
	//print $tmpcol;
	//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol=$tmpcolname","","");
	print "<br />";
	print "<form name='formupdate' method='post' action='".$_SERVER["REQUEST_URI"]."'>\n";
	print "<table cellSpacing=0 cellPadding=2 width='100%' border=1>\n";
	//print "<input type='hidden' name='$tmpcolnum' value='".$tmpcolname."' size='20'>";
	
	while (list($key, $val) = each($tblist)) {
			
   			if($key==0)
   			{
   				print "<input type='hidden' name='$val' value='' >";
   			}
   			else{
   			
   				if($val==$tmpcolnum){
   					//print  "<tr>\n<td valign=top> " . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n<td valign=top>";
   					print "<input type='hidden' name='$val' value='".$tmpcolname."' >";}
   				else{
   					print  "<tr>\n<td valign=top class=ftitle1 align=right width='20%'> " . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n<td valign=top class=fname1 align=left>";
   					//print "<input type='text' name='$val' value='".$htmltxt."' size='20'>";}
   					print "<input type='text' name='$val' size='100'>";
   				}
   			
   				//print "<textarea name='$val' cols='50' rows='5'>".$htmltxt."</textarea>";
   				print "</td>\n<td valign=top class=ftitle1 align=right width='10%'>&nbsp;</td></tr>\n";
   			}
   	}
   	
	print "</table>";
	print "<br/><br/><div align=center>";
	print "<input type='submit' name='Add' value='Add'>";
    //print "<input type='reset' name='Resetxxx' value='Reset'>";
    print "</div>";
	print "</form>\n";
	/*
	$_SESSION["BACKREF"]=$_SERVER["HTTP_REFERER"];
	print "<br/><a href='".$_SESSION["BACKREF"]."'>Click here to return</a>";
	*/
	}
?><br /><br />


<?php
}
else{
	$User_Info->go_page("error");
}

?>