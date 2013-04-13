<br />
<?php 
	//echo "You last logged in on " . $_PROFILE->userp_last_time . "." ;
	
	print "<br />";
	//print_r($_SESSION);
	//print $_SERVER['SERVER_ADDR'];
	//print $_SERVER['HTTP_COOKIE'];
	if(substr($_SESSION["sCONTROLLVL"],0,3)=="MGM") {
		
?>
	<br/>
	<table width=100% border=0 align=center>
	<tr align="center" valign="top">
	<td align=center>&nbsp;</td>
	<td><p align=center><img src="images/MercedesTrophyLogo1.gif"></td>
	<td align=center>&nbsp;</td>
	</tr>
	<tr align="center" valign="top">
<?
if ($_SESSION["sCONTROLLVL"]=="GRP02" || date('Ymd') < $SregCutOff) {
?>
	<td width=33%><p align=center><a href="index.php?s=add_cust&tbn=cust_tb&as=adddata&tp=gt"><img src="images/guest_but.gif" width="250" height="100" border=0 alt="Register Guest Player"></a></td>
	<td width=33%><p align=center><a href="index.php?s=add_cust&tbn=cust_tb&as=adddata"><img src="images/tournament_but.gif" width="250" height="100" border=0 alt="Register Tournment Player"></a></td>
<?
} else {
?>
	<td></td>
	<td></td>
<?
}
?>
	<td width=33%><p align=center><a href="index.php?s=view_local&tbn=cust_tb&as=view"><img src="images/view_but.gif" width="250" height="100" border=0 alt="View List of Registered Players"></a></td>
	</tr>
	<tr align="center" valign="top">
<?
if ($_SESSION["sCONTROLLVL"]=="GRP02" || date('Ymd') < $SregCutOff) {
?>
		<td width=33% valign=top><p align=center>Enter this section to register information of guest players invited by your country. The country team captain as well as companions of tournament and guest players should also be registered here.</td>
		<!-- Modified by William on April 20, 2006. Display year using SESSION -->
		<td width=33% valign=top><p align=center>Enter this section to register information on finalists competing in the MercedesTrophy Asian Final <?=$_SESSION['sThisYear'];?>.</td>

<?
} else {
?>
	<td></td>
	<td></td>
<?
}
?>
		<td width=33% valign=top><p align=center>Enter this section to review / edit information on all players/entries that you've registered.</td>
	</tr>
	<tr align="center" valign="top">
	<td colspan=3>
	<!-- Modified by William on April 20, 2006. Display year using SESSION -->
	<p align=center><b>Please note that no inputs/changes will be accepted after <? echo $regCutOff ?> <?=$_SESSION['sThisYear'];?>.</b>
<?
// XXX Update contact details -- marc 20090804
if ($_SESSION["sCONTROLLVL"]!="GRP02" && date('Ymd') >= $SregCutOff) {
?>
<br/>
<b>Please contact either Eunice Kwek at +65 6849 8025 or email  her at <a href="mailto:eunice.kwek@daimler.com">eunice.kwek@daimler.com</a> for further assistance.</b>
<?
}
?>
	</p>
	</td>
	</tr>
	
	</table>
<?
	} else if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP") {
?>
<table border=0 width=80% align=center>
	<tr><td colspan=4>&nbsp;</td></tr>
	<tr><td colspan=4 align=center><br/><img src="images/MercedesTrophyLogo1.gif"><br/><br/><br/></td></tr>
	<tr><td>&nbsp;</td>
	<!-- Modified by William on April 20, 2006. Display year using SESSION -->
	<td  colspan=2 align=center><b>Welcome to the MercedesTrophy Asian Final <? echo $_SESSION['sThisYear'] ?> Registration System</b></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align=center colspan="2"><br/><input type="hidden" name="callback" value="<?=$callback;?>">
	</td>
	</tr>
	<tr><td colspan=4 align=center><br/><img src="images/MercedesBenzLogo.gif"><br/></td></tr>
	<tr><td colspan=4>&nbsp;</td></tr>
	</table>
<?
	}
?>
<br /><br />
