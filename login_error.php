<?
if(isset($_GET["e"]))
{
if($_GET["e"]=="1")
	$login_err="<b>The login has failed. Please re-enter your ID and Password.</b><br><br>";
else if($_GET["e"]=="2")
	$login_err="<b>The login has failed due to your login and password not being entered. Please re-enter your ID and Password.</b><br><br>";
else if($_GET["e"]=="3")
	$login_err="<b>The login has fail as u are not a current user in for this system module.</b><br><br>";
else
	$login_err="Please enter your user login and password";
}	
?>


