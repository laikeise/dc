<?php 
require_once("classes/Addon.php");
$Paging = new Addon;
$_GET["as"]="view";
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
	
?>
View Page
<br />
<?php 
		
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");

	print "<table  width='100%' border='1' cellspacing='0' cellpadding='0'><tr>";
	while (list($key, $val) = each($tblist)) {
			if($key==0)
   			{
   				//print "<input type='hidden' name='$val' value='".$htmltxt."' >";
   			}
   			else{
   						
				if($val==$tmpcolnum)
				{
					$tmpcolnum=$key;
				}
   				print  "<td valign=top class=ftitle1>" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</td>";
   			}
   			$rmtotal++;
	}
	//print $rmtotal;
	
	print "<td valign=top class=ftitle1>Action</td>";
	print "</tr>";
	
	if(isset($_GET["pg"]))
		$page=$_GET["pg"];
	else
		$page=1;

	//page control listing by 10 now	
	if(isset($_GET["ls"]))
		$list=$_GET["ls"];
	else
		$list=10;
	
	/*
	if(!isset($page))
		$page=1;

	if(!isset($list))
		$list=10;
	*/
	$pointer=$page*$list-$list;	
	
	//$qt="select * from rob_arena_board order by rob_text_id limit $pointer,$list";
	//$resultx = $User_Info->doSQL($qt);
	//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","","");
	
	
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$tblist[0] DESC","$pointer,$list");
	}
	else{
		$resultx= $User_Info->dosearchSQL("$tmptb","","$tblist[0] DESC","$pointer,$list");
	}
	
	//$resultx= $User_Info->dosearchSQL("$tmptb","","$tblist[0]","$pointer,$list");
	
	while($qrow=mysql_fetch_array($resultx))
	{
		//print "test";
		//$sub=ereg_replace ("\n", "&lt;br /&gt;", $row['rob_subject']);
		print "<tr>";
		for($i=1;$i<$rmtotal;$i++) {
   			$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   			$htmltxt=html_entity_decode($temptxt);
   			echo "<td valign=top class=fname1>".$htmltxt."<br /></td>\n";
		}
		
		print "<td valign=top class=fname1>";
		if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],view))
			print "<a href=".$_SERVER["PHP_SELF"]."?s=view_reg_record&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view><!--<img src='images/filesearch.gif' border=0 />-->[View]</a>";
		if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],edit))
			print "<a href=".$_SERVER["PHP_SELF"]."?s=edit&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit><!--<img src='images/edit.gif' border=0 />-->[Edit]</a><br />\n";
		if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],deldata)){
		print "<br/><a href=# onclick='return delWindow(\"s=delete&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\",\"".urlencode($qrow[1])."\");return false;' >";
		//print "<a href=# onclick='return delWindow(\"s=delete_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\",\""." a  "."\");return false;' >";
		print "<!--<img src=\"images/delete.gif\" border=\"0\" alt=\"Delete this Player\" >-->[Delete]</a>";
		}
		print "</td>\n";
		print "</tr>";
	}
	
	print "</table>";

?><br /><br />


<?php

$Paging->backpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
$Paging->nextpage($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
print "<br/>";
$Paging->listpaging($_GET["tbn"],$tmpcolnum!=""&&$tmpcolname!=""?("$tblist[$tmpcolnum]='$tmpcolname'"):(""),$list);
/*
$Paging->backpage($_GET["tbn"],"rob_type='blog'",$list);
$Paging->nextpage($_GET["tbn"],"rob_type='blog'",$list);
*/
//print "test";

}
else
{
	$User_Info->go_page("error");
}
?>
