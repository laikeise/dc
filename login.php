<?php 
//print $_SESSION["sUSERID"];
//print_r ($_PROFILE);
//print_r($_SESSION); 
//print $_SERVER["SERVER_ADDR"];
//$_SESSION[sUSERID]=1;
//print isset($_SESSION[sUSERID]);
session_register("templogin");
/*
if (isset($_SESSION["sUSERID"])&&isset($_SESSION["sSITEID"])&&isset($_SESSION["sCONTROLLVL"])) { 
	$User_Info->go_page("admin");
}
*/
	require_once("login_error.php");
	
	//print_r($_SESSION); 
?>
<div align=center>
<form method="post" action="index.php?s=login_verify">
	<table border=0 width=83%>
	<tr><td colspan=4>&nbsp;</td></tr>
	<tr><td colspan=4 align=center><br/><img src="images/MercedesTrophyLogo1.gif"><br/><br/><br/></td></tr>
	<tr><td>&nbsp;</td>
	<td  colspan=2 align=center>
	<!-- Modified by William on April 20, 2006. Display year using SESSION -->
	<b>Welcome to the MercedesTrophy Asian Final <? echo $_SESSION['sThisYear'] ?> Registration System</b><br/><br/>
	<?
	if (isset($_GET["e"]))
		print $login_err."";
	?>
	</td>
	<td>&nbsp;</td>
	</tr>
	<tr><td rowspan=3 width=30% align=center>&nbsp;</td>
	<td align=right>
	ID<br>
	</td>
	<td align=left><table width="200" align=center><tr><td>
	<input type="text" name="USERID" size="20" maxlength="20" value="<?=$_SESSION['templogin']?>"><br>
	</td></tr></table>
	</td><td rowspan=3 width=30% align=center>&nbsp;</td>
	</tr>
	<tr>
	<td align=right>Password<br></td>
	<td align=left>
	<table width="200" align=center><tr><td><input type="password" name="PASS" value="" maxlength="20" size="20">&nbsp;&nbsp;&nbsp;<input type="submit" name="action" value="Enter">
	</td></tr></table>
	</td>
	</tr>
	<tr>
	<td align=center colspan="2"><br/><input type="hidden" name="callback" value="<?=$callback;?>">
	</td>
	</tr>
	<tr><td colspan=4 align=center><br/><img src="images/MercedesBenzLogo.gif"><br/></td></tr>
	<tr><td colspan=4>&nbsp;</td></tr>
	</table>
	</form>
	<?php 
/*
} else {
	$User_Info->go_page("admin");
} 

	*/
?>

