<?php 
//set up of the tmp structures
$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
$tblist=$User_Info->getFieldName("$tmptb");
$_GET["as"]="edit";
//do the access checks here
if ($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])) {
	if (isset($_POST["Update"])) {
		//print "post";
		//print_r($HTTP_POST_VARS);
		
		$tempsql="Update $tmptb set ";
		//"xxx 
		$tmptb=(isset($_GET["tbn"])?$_GET["tbn"]:"");
		
		while (list($key, $val) = each($tblist)) {
			$tempstring=htmlentities($HTTP_POST_VARS["$val"]);
			//$tempstring=$HTTP_POST_VARS["$val"];
			/*
			//do a manual pass change. functions to use.
			if($val=="userp_pass"){
				$tempstring=md5($tempstring);}
			*/
			$tempsql=$tempsql."$val='".$tempstring."', ";
		}
		
		$tempstrlen=strlen($tempsql)-2;
		$tempsql=substr($tempsql,0,$tempstrlen);
		$tempsql=$tempsql." where $tblist[0]=$tmpcolname;";
		$Error_Handler->print_error($tempsql);
		//print_r ($tblist);
		//$sqlpost="";
		$updarow=$User_Info->doUpdateSQL($tempsql);
		//print $uupdarow;
		if($updarow)
			print "Record has been updated!";
		else
			print "Nothing updated!";
		//print "<br/><a href='".$_SESSION["BACKREF"]."'>Click here to return</a>";
	} else {
?>
Edit Page
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
		print "<form name='formupdate' method='post' action='".$_SERVER["REQUEST_URI"]."'>\n";
		print "<table cellSpacing=0 cellPadding=2 width='100%' border=1>\n";
		if($qrow=mysql_fetch_array($resultx)) {
			while (list($key, $val) = each($tblist)) {
	   			//print $key.$val;
				$temptxt=$qrow[$key];
				//print $qrow[$key];
				//$htmltxt=html_entity_decode($temptxt);
				$htmltxt=$temptxt;
	   			
	   			if($key==0) {
	   				print "<input type='hidden' name='$val' value='".$htmltxt."' >";
	   			} else {
	   				print  "<tr>\n<td valign=top class=ftitle1 align=right width='20%'> " . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n<td valign=top class=fname1 align=left>";
	   				//$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$rmtotal]);
	   				//print strlen($htmltxt);
	   				//$htmllen=strlen($htmltxt);
	   				if($htmllen<80)
	   					print "<input type='text' name='$val' value=\"".$htmltxt."\" size='100'>";
	   				else
	   					print "<textarea name='$val' cols='50' rows='5'>".$htmltxt."</textarea>";
	   				print "</td><td valign=top class=ftitle1 align=right width='10%'>&nbsp;</td>\n</tr>\n";
	   				//$rmtotal++;
	   			}
			}
		}
		
		print "<tr><td colspan=3 align=center><br/><input type='submit' name='Update' value='Submit'>";
	    print "&nbsp;&nbsp;<input type='reset' name='Resetxxx' value='Reset'>";
		print "<br/><br/></td></tr></table>";
		print "</form>\n";
		/*
		$_SESSION["BACKREF"]=$_SERVER["HTTP_REFERER"];
		print "<br/><a href='".$_SESSION["BACKREF"]."'>Click here to return</a>";
		*/
	}
?>
<br />
<br />
<?php
} else {
	$User_Info->go_page("error");
}
?>