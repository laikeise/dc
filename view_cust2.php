<?php 
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
	//print "<br/><a href='".$_SERVER["PHP_SELF"]."?s=admin'>Click here to return</a><br />";
?>

<br />
<?php 
		
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	$tmphdname=((isset($_GET["hd"]))?$_GET["hd"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
	$wherecol="";
   	//print $sortlist;
   	//end of reconstruction of the get list
	if(isset($_GET["pi"])){
		$today=getdate();
		print "<div align=right class=fname1>Printed by ".$_SESSION["sNick"]." on ".(strftime("%d %B %Y %r", $today[0])).".</div>";
	}
	//print "<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
	?>
	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
	<tr>
	<td valign=top class=ftitle1>##</td>
	<td valign=top class=ftitle1>
	<a href="index.php?<?=$sortlist;?>&st=salutation"><span class="ftitle1">Sal</span></a></td>
	<td valign=top class=ftitle1>
	<a href="index.php?<?=$sortlist;?>&st=family_name"><span class="ftitle1">Family Name</span></a></td>
	<td valign=top class=ftitle1>
	<a href="index.php?<?=$sortlist;?>&st=first_name"><span class="ftitle1">First Name</span></a></td>
	<td valign=top class=ftitle1>
	<a href="index.php?<?=$sortlist;?>&st=player_type"><span class="ftitle1">Player Type</span></a></td>
	<?
		$acthandiresult= $User_Info->dosearchSQL("ref_handi_tb","","handi_name DESC","inf");
		$handicounter=mysql_num_rows($acthandiresult);
		$handicnt=array();
		while($acthandirow=mysql_fetch_array($acthandiresult))
		{
			$db_handi_name=$acthandirow["handi_name"];
			$db_handi_id=$acthandirow["handi_ref_id"];
			print "<TD class=ftitle1 vAlign=top align=center colspan=6>".($db_handi_name)."</TD>";
			$handicnt=array_merge($handicnt,array("$db_handi_id"));
		}
		//print_r($handicnt);
	?>
	<td valign=top class=ftitle1>Cur</td>
	<td valign=top class=ftitle1>Action</td>
	</tr>
	<TR>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
                <?for($h=0;$h<$handicounter;$h++)
                {?>
                <TD class=ftitle1 vAlign=top>Div</TD>
                <TD class=ftitle1 vAlign=top>Ihcp</TD>
                <TD class=ftitle1 vAlign=top>Par</TD>
                <TD class=ftitle1 vAlign=top>Rat</TD>
                <TD class=ftitle1 vAlign=top>Sco</TD>
                <TD class=ftitle1 vAlign=top>Ahcp</TD><?
				}?>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
                <TD class=ftitle1 vAlign=top>&nbsp;</TD>
                </TR>
                
	<?
	
	if(isset($_GET["pg"]))
		$page=$_GET["pg"];
	else
		$page=1;

	//page control listing by 10 now	
	if(isset($_GET["ls"]))
		$list=$_GET["ls"];
	else
		$list=10;
		
	if(isset($_GET["od"]))
		$orderlist=$_GET["od"];
	else
		$orderlist="DESC";
	
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
	
	if(isset($tmphdname)){
		$searchhdcol=$tmphdname;
	}
	
	//doing order by for the main cust_tb
	if(($tmpstname)!=""){
		$searchcol="$tmpstname $orderlist";
	}
	else{
		$searchcol="$tblist[0] $orderlist";
	}
	
	
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$searchcol","$pointer,$list");
	}
	else if(isset($_GET["Filter"])){
		$wherecol="";
		//print_r($HTTP_GET_VARS);
		//print $HTTP_GET_VARS["country"];
		//[player_type] => CC [country] => Malaysia [transport] => HL [room_type] => D 
		if(($_GET["player_type"]))
			$wherecol=$wherecol."player_type=\"".$_GET["player_type"]."\" and";
		if(($_GET["country"]))
			$wherecol=$wherecol." country_name=\"".$_GET["country"]."\" and";
		if(($_GET["transport"]))
			$wherecol=$wherecol." transport like \"%".$_GET["transport"]."%\" and"; 			
		if(($_GET["room_type"]))
			$wherecol=$wherecol." room_short_form=\"".$_GET["room_type"]."\" and";
		if(($_GET["arrival_date"]))
			$wherecol=$wherecol." arrival_date=\"".$_GET["arrival_date"]."\" and";
		if(($_GET["depart_date"]))
			$wherecol=$wherecol." depart_date=\"".$_GET["depart_date"]."\" and";
		//$wherecol=$wherecol."family_name!=\"\"";
		$wherecol=substr($wherecol,0,-3);
		//print $wherecol;
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","$pointer,$list");
		$_SESSION["returnURL"]=$_SERVER["REQUEST_URI"];
	}
	else{
		$resultx= $User_Info->dosearchSQL("$tmptb","","$searchcol","$pointer,$list");
	}
	
	$g=1;
	while($qrow=mysql_fetch_array($resultx))
	{
		//print "test";
		//$sub=ereg_replace ("\n", "&lt;br /&gt;", $row['rob_subject']);
		print "<tr>";
		for($i=0;$i<=4;$i++) {
if ($i==4) $qrow[$i] = $qrow[9] ;
			$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
			if($i==0){
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
	   			}
	   			else if($i==4){
   					$htmltxt=$AustGolf_Addon->get_player_name($temptxt);
	   			}
   				else
   					$htmltxt=html_entity_decode($temptxt);
   			echo "<td valign=top class=fname1>".$htmltxt."<br /></td>\n";
		}
		
		//Round columns
		for($h=0;$h<$handicounter;$h++)
		{
			$qf_set1= $User_Info->dosearchSQL("cust_handi_tb","ref_handi_id=".($handicnt["$h"])." and cust_id=$qrow[0]","",""," cust_handi_div,cust_initial_hcp,cust_par,cust_course_rating,cust_result,cust_adj_hcp");
			if($qf_row1=mysql_fetch_array($qf_set1)){
				for($i=0;$i<6;$i++) {
					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qf_row1[$i]);
					if($temptxt==""||$temptxt=="0")
						$htmltxt="&nbsp;-";
					else
						$htmltxt=html_entity_decode($temptxt);
   					echo "<td valign=top class=fname1>".$htmltxt."<br /></td>\n";
				}
			}
			else{
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
			}
		}
		
		/*
		// Country Round & Asian Round & Guest Tournament (2 columns)
		for($d=0;$d<3;$d++)
		{
			$qf_set2= $User_Info->dosearchSQL("cust_handi_tb","ref_handi_id=".($d+2)." and cust_id=$qrow[0]","","");
			if($qf_row2=mysql_fetch_array($qf_set2)){
				for($i=0;$i<=1;$i++) {
					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qf_row2[$i]);
					if($temptxt==""||$temptxt=="0")
						$htmltxt="&nbsp;- ";
					else
						$htmltxt=html_entity_decode($temptxt);
   					echo "<td valign=top class=fname1>".$htmltxt."<br /></td>\n";
				}
			}
			else{
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
				echo "<td valign=top class=fname1>&nbsp;-<br /></td>\n";
			}
		}
		*/
	
		// added new column for Current Handicap for Guest Players - arthur
		$qf_set2= $User_Info->dosearchSQL("cust_act_tb","act_ref_id=15 and cust_id=$qrow[0]","","");
		while($qf_row2=mysql_fetch_array($qf_set2))
		{
			if(!empty($qf_row2[cust_addon]))
				print "<TD valign=top align=left class=fname1>$qf_row2[cust_addon]</TD>";
			else
				print "<TD valign=top align=left class=fname1>&nbsp;-</TD>";
		}
	
		
		print "<td valign=top class=fname1><a href=".$_SERVER["PHP_SELF"]."?s=view_record&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view>[View]</a>";
		print "<br/><a href=".$_SERVER["PHP_SELF"]."?s=edit_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit>[Edit]</a><br /></td>\n";
		
	}
	print "</table>";
?><br /><br />


<?php

if(!isset($_GET["pi"])){
$Paging->backpage($_GET["tbn"],$wherecol,$list);
$Paging->nextpage($_GET["tbn"],$wherecol,$list);
print "<br/>";
$Paging->listpaging($_GET["tbn"],$wherecol,$list);
}
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
