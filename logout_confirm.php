<?
	$_GET["tbn"] = "cust_tb";
	$_GET["as"] = "view";
	include_once('view_local.php');
?>
	<form method="post" action="index.php?s=logout">
		<input type="checkbox" name="confirm" value="true"> I confirm that all the information above are accurate and correct, and I wish to logout now.<br>
		<input type="submit" value="Logout" onclick="if(!form.confirm.checked){alert('You must confirm that the information is correct before you can logout.');return false}">
	</form>