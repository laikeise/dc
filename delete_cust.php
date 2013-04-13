<?php 
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
//set up of the tmp structures
$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
$tblist=$User_Info->getFieldName("$tmptb");
$tmpcol=$tblist[$tmpcolnum];

//do the access checks here
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])) {
	if(isset($_POST["Delete"])) {
		//print "post";
		//print_r($HTTP_POST_VARS);
		$updarow=0;
		$tempsql="insert del_$tmptb set ";
		//"xxx 
		$tmptb=(isset($_GET["tbn"])?$_GET["tbn"]:"");
		
		if($_POST["reason"]!="") {
			$inforesult = $User_Info->dosearchSQL("cust_tb","cust_id='$tmpcolname'","","inf");
			$inforow=mysql_fetch_array($inforesult);
			$ssresult= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'");
			
			if($ssrow=mysql_fetch_array($ssresult)) {
				while (list($key, $val) = each($tblist)) {
					$tempstring=htmlentities($ssrow["$val"]);
					$tempsql=$tempsql."$val=\"".$tempstring."\", ";
				}
			}
			
			$tempstrlen=strlen($tempsql)-2;
			$tempsql=substr($tempsql,0,$tempstrlen);
			//$tempsql=$tempsql." where $tblist[0]=$tmpcolname;";
			//$Error_Handler->print_error($tempsql);
			print $tempsql;
		
//insertion of data from actual tb to the deleted tb
			$updarow=$updarow+($User_Info->doUpdateSQL($tempsql));
			$delsqllog="insert delete_log set  tb_name=\"$tmptb\",  dlog_user=\"".$_SESSION["sNick"]."\", dlog_action=\"deldata\", dlog_flag=\"deleted\" , dlog_tb_id=\"".$tmpcolname."\",  dlog_reason=\"".$_POST["reason"]."\";";
			$Error_Handler->print_error($delsqllog);
			$updarow=$updarow+($User_Info->doUpdateSQL($delsqllog));
			
			$actresult= $User_Info->dosearchSQL("cust_act_tb","cust_id='$tmpcolname'","","inf");
			
			while($actrow=mysql_fetch_array($actresult)) {
				$tempsql="insert del_cust_act_tb set ";
				$tblist1=$User_Info->getFieldName("cust_act_tb");
				while (list($key, $val) = each($tblist1)){
					$tempstring=htmlentities($actrow["$val"]);
					$tempsql=$tempsql."$val=\"".$tempstring."\", ";
				}
				
				$tempstrlen=strlen($tempsql)-2;
				$tempsql=substr($tempsql,0,$tempstrlen);
				$Error_Handler->print_error($tempsql);
				$updarow=$updarow+($User_Info->doUpdateSQL($tempsql));
			}
			
			$actresult= $User_Info->dosearchSQL("cust_handi_tb","cust_id='$tmpcolname'","","inf");
			
			while($actrow=mysql_fetch_array($actresult)) {
				$tempsql="insert del_cust_handi_tb set ";
				$tblist2=$User_Info->getFieldName("cust_handi_tb");
				
				while (list($key, $val) = each($tblist2)){
					$tempstring=htmlentities($actrow["$val"]);
					$tempsql=$tempsql."$val=\"".$tempstring."\", ";
				}
				
				$tempstrlen=strlen($tempsql)-2;
				$tempsql=substr($tempsql,0,$tempstrlen);
				$Error_Handler->print_error($tempsql);
				$updarow=$updarow+($User_Info->doUpdateSQL($tempsql));
			}
			
			$actresult= $User_Info->dosearchSQL("cust_add_tb","cust_id='$tmpcolname'","","inf");
			
			while($actrow=mysql_fetch_array($actresult)) {
				$tempsql="insert del_cust_add_tb set ";
				$tblist2=$User_Info->getFieldName("cust_add_tb");
				
				while (list($key, $val) = each($tblist2)){
					$tempstring=htmlentities($actrow["$val"]);
					$tempsql=$tempsql."$val=\"".$tempstring."\", ";
				}
				
				$tempstrlen=strlen($tempsql)-2;
				$tempsql=substr($tempsql,0,$tempstrlen);
				$Error_Handler->print_error($tempsql);
				print $tempsql;
				//$updarow=$updarow+($User_Info->doUpdateSQL($tempsql));
			}
//delete main data
			$delmainsql="delete from $tmptb where cust_id=$tmpcolname";
			$User_Info->sqlLog($delmainsql);
			$Error_Handler->print_error($delmainsql);
			$updarow=$updarow+($User_Info->doUpdateSQL($delmainsql));
			$delmainsql="delete from cust_act_tb where cust_id=$tmpcolname";
			$User_Info->sqlLog($delmainsql);
			$Error_Handler->print_error($delmainsql);
			$updarow=$updarow+($User_Info->doUpdateSQL($delmainsql));
			$delmainsql="delete from cust_handi_tb where cust_id=$tmpcolname";
			$User_Info->sqlLog($delmainsql);
			$Error_Handler->print_error($delmainsql);
			$updarow=$updarow+($User_Info->doUpdateSQL($delmainsql));
			$delmainsql="delete from cust_add_tb where cust_id=$tmpcolname";
			$User_Info->sqlLog($delmainsql);
			$Error_Handler->print_error($delmainsql);
			$updarow=$updarow+($User_Info->doUpdateSQL($delmainsql));
			
			//print_r ($tblist);
			//$sqlpost="";
			//$updarow=$User_Info->doUpdateSQL($tempsql);
			//print $uupdarow;
			if($updarow) {
				print "$updarow records have been updated!";
				$AustGolf_Addon->alert_email(date('Y-m-d g:i A'), $inforow['family_name'] . " " . $inforow['first_name'], $inforow['country_name'] . $inforow['country_oth'], $_SESSION['sNick'], "Participant deleted");

			} else
				print "Nothing updated!";
		?>
		<input type=button class=ButtonStyle name=cancel value=Close onclick="opener.focus();window.close()">
		<script type="text/javascript">
			opener.focus();
			opener.location.reload();
			window.close(); 
		</script>
<?
		}
	} else {
?>
Delete Record
<?php 
		//$rmtotal=0;
		//print $tmptb;
		//$tblist=$User_Info->getFieldName("$tmptb");
		//print_r($tblist);
		//print $tmpcol."test";
		//print $atb[$tmpcol]."test";
		//print "Check - ".ctype_alnum($tmpcolname)."<br />";
		$resultx= $User_Info->dosearchSQL("$tmptb",ctype_alnum($tmpcolname)?"$tmpcol='$tmpcolname'":"$tmpcol=$tmpcolname","","");
		print "<br />";
		/*
		print "<form name='formupdate' method='post' action='".$_SERVER["REQUEST_URI"]."'>\n";
		print "<table cellSpacing=0 cellPadding=2 width='100%' border=1>\n";
		*/
		if($qrow=mysql_fetch_array($resultx)) {
			$oth=0;
?>
		<table border=0 width=100%>
			<form action=<?=$_SERVER["REQUEST_URI"];?> method=post>
			<tr>
				<td>Enter reason for deleting the record:<br></td>
			</tr>
			<tr>
				<td>
					<textarea cols='37' rows='2' style='width:100%; height:100px;' name=reason></textarea>
			        <script type="text/javascript">
					<!--
						document.forms[0][0].focus();
					// -->
					</script>
				</td>
			</tr>
			<tr>
				<td>
					<br>
					<input type=submit class=ButtonStyle name=Delete value=Submit>
					<input type=button name=cancel class=ButtonStyle value=Cancel onclick="opener.focus();window.close()">
				</td>
			</tr>
			</form>
<?
		}
	}
?>
<br />
<br />
<?php
} else {
	$User_Info->go_page("error");
}
?>
