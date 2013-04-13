<?php 
include_once("AustGolf_Addon.php");
include_once("printname.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
	//print "<br/><a href='".$_SERVER["PHP_SELF"]."?s=admin'>Click here to return</a><br />";
//if(isset($_POST["View"]))
{
?>
<?php 
	//print_r($_POST);		
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	$tempshowitem=array();
	
	//adding of new display list
	$tbcount=count($tblist);
	$newtbcount=$tbcount;
	
	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
   	//end of reconstruction of the get list
	
	//$newdisplay=array("$newtbcount"=>"Arrival");
	//$tblist=array_merge($tblist,$newdisplay);
	$today=getdate();
	print "<div align=right class=fname1>Printed by ".$_SESSION["sNick"]." on ".(strftime("%d %B %Y %r", $today[0])).".</div>";
	print "<table width='80%' border='1' cellspacing='0' cellpadding='0' align=center>";
	//print "<div align=center class=ftitle5>By Arrival Date Report ";
	print "<div align=center><p align=center><img src=\"images/MercedesTrophyLogo1.gif\">";
	print "</div>";
	ob_start();

	
	print "<tr>";
	$tabcounter=0;
	while (list($key, $val) = each($tblist)) {
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
   			if($val=="tee_venue"||$val=="tee_date"||$val=="player_type")
   			{
   				$tempshowitem=array_merge($tempshowitem,array($key=>0));
   			}
   			else{
				if($val==$tmpcolnum)
				{
					$tmpcolnum=$key;
				}
				$tempshowitem=array_merge($tempshowitem,array($key=>1));
   				
   				//used to do the arrow up and down for the sort <Start>
   				if(isset($_GET["st"]))
   				{
   					if($_GET["st"]=="$val"){
   						if(isset($_GET["od"])){
   							if($_GET["od"]=="asc"){
   								print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
								print  "<img src=\"images/arrow_ASC.gif\">";
								$tabcounter++;}
							else{
								print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=asc\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
   								print  "<img src=\"images/arrow_DESC.gif\">";
   								$tabcounter++;}
   						}
   					}
   					else{
   						print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
   						$tabcounter++;}
   				}
   				else{
   					if($key==1){
   						print  "<td valign=top class=ftitle1>Golfer Name</td><td valign=top class=ftitle1>Country</td><td valign=top class=ftitle1>P.T</td><td valign=top class=ftitle1>Handicap";
   					}
   					else
   						print  "<td valign=top class=ftitle1>". (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "";
   					//print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
   					$tabcounter++;}
   				//used to do the arrow up and down for the sort <End>
   					
   				print  "</td>";
   			}
   			$rmtotal++;
	}
	//print  "<td valign=top class=ftitle1>Remarks</td>";	
		
	?>
	</tr>
	<?
	$tableheader=ob_get_contents();
	ob_end_flush();
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
		$orderlist="ASC";
	
	$pointer=$page*$list-$list;	
	

	if(($tmpstname)){
		//print "true";
		//$_POST["tee_date"]
		//$_POST["depart_date"]
		if($tmpstname=="Arrive"){
			$searchcol="arrival_date $orderlist";
		}
		else if($tmpstname=="Depart"){
			$searchcol="depart_date $orderlist";
		}
	}
	else{
		$searchcol="tee_date ASC, tee_hole ASC , tee_time ASC,player_type DESC";}
		//$searchcol="arrival_date ASC, arrival_time ASC, arrival_flight ASC";}
	
	//print $searchcol;
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$searchcol","$pointer,$list");
	}
	else{
		//$wherecol=$wherecol." a.arrival_flight NOT LIKE \"%NA%\" and";
		//$wherecol=substr($wherecol,0,-3);
		//$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		//$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","inf");
		$resultx= $User_Info->dosearchSQL("$tmptb AS a","$wherecol","a.$searchcol","inf");
		$summaryarray1=array("$tmptb AS a","$wherecol and a.","a.$searchcol","inf", "*","");
		$summaryarray2=array("$tmptb AS a","$wherecol ","a.$searchcol","inf", "*","");
		//print_r($summaryarray);
	}
	
	$g=1;
	$pgcount=0;
	$pgrow=mysql_num_rows($resultx);
	
	if($qrow=mysql_fetch_array($resultx))
	{
		$printerheader=0;
		$pgcount++;
		if(!isset($tempdate)){
			$tempdate=$qrow["tee_date"];
			}
		if(!isset($temptime)){
			$temptime=$qrow["tee_time"];
			}
		if(!isset($tempvenue)){
			$tempvenue=$qrow["tee_venue"];
			if ($tempvenue=="Lakelands Golf Club")
				$tempfooter= "<p class=fname2>Registration is Miss Amelia Heseltine at Clubhouse Area";
			else if ($tempvenue=="Hope Island Golf Course")
				$tempfooter= "<p class=fname2>Registration is Miss Claudia Kiss at Clubhouse Foyer";
		}
			print "<b>Date : ".strftime("%d-%b-%y", strtotime($tempdate))."</b><br/>";
			//print "Time".$temptime."<br/>";
			//$tempnflight=explode(":~:",$tempflight);
			print "<b>Venue : ".$tempvenue."</b><br/>";
	}
	mysql_data_seek ($resultx,0);
	
	while($qrow=mysql_fetch_array($resultx))
	{
		$printerheader=0;
		$pgcount++;
		/*
		if(!isset($tempdate)){
			$tempdate=$qrow["tee_date"];
			}
		
		if(!isset($temptime)){
			$temptime=$qrow["tee_time"];
			}
		
		if(!isset($tempvenue)){
			$tempvenue=$qrow["tee_venue"];
		}
		
		if(!isset($tempcountry)){
			$tempcountry=$qrow["country_name"];
			}
*/
		if($tempdate!=$qrow["tee_date"]){
			$tempdate=$qrow["tee_date"];
			$printerheader=1;
			}
		/*
		if($temptime!=$qrow["tee_time"]){
			$temptime=$qrow["tee_time"];
			$printerheader=1;
			}
			*/
		if($tempvenue!=$qrow["tee_venue"]){
			$tempvenue=$qrow["tee_venue"];
			$printerheader=1;
			//print $tempvenue;
			/*
			if ($tempvenue=="Lakelands Golf Club")
				$tempfooter= "<p class=fname2>Registration is Miss Amelia Heseltine at Clubhouse Area";
			else if ($tempvenue=="Hope Island Golf Course")
				$tempfooter= "<p class=fname2>Registration is Miss Claudia Kiss at Clubhouse Foyer";
			*/
		}

		if($printerheader==1){
			$pgcount=1;
			//$tbcounter=4;
			print "</table><br/>";
			print $tempfooter;
			print "<p STYLE='page-break-after: always'></p>";
			print "<div align=center><p align=center><img src=\"images/MercedesTrophyLogo1.gif\">";
			print "</div>";
			print "<table width='80%' border='1' cellspacing='0' cellpadding='0' align=center>";
			print "<b>Date : ".strftime("%d-%b-%y", strtotime($tempdate))."</b><br/>";
			print "<b>Venue : ".$tempvenue."</b><br/>";
			print $tableheader;
			$g=1;
		}
		print "<tr>";
		
		for($i=0;$i<$newtbcount;$i++) {
			//print $i;
			//print_r($tblist);
			if($tempshowitem[$i]==1)
			{
				
				if($i<$tbcount)
   					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				else if($i>=$tbcount){
   						$temptxt="$tblist[$i]";
   				}
   				
   				//to replace the cust id with a default no.
   				if($i==0){
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
   					$size=20;
	   			}
	   			else if($i==1){
	   				$db_cust_handi="-";
	   				//$rm_q="select * from cust_tb where cust_id='$temptxt'";
    					$rm_result = $User_Info->dosearchSQL("cust_tb","cust_id='$temptxt'","","inf");
					if($rm_row=mysql_fetch_array($rm_result)){
						$db_cust_fam_name =$rm_row["family_name"];
						$db_cust_fir_name =$rm_row["first_name"];
						$db_cust_cty_name =$rm_row["country_name"];
						$db_cust_pt =$rm_row["player_type"];
						$db_cust_name=$rm_row["family_name"]." ".$rm_row["first_name"] ;
						$hand_result = $User_Info->dosearchSQL("cust_handi_tb","cust_id='$temptxt' and ref_handi_id=2","","inf");
						$qf_set2= $User_Info->dosearchSQL("cust_act_tb","act_ref_id =4 and cust_id=$temptxt","","","cust_addon");
						$qf_set3= $User_Info->dosearchSQL("cust_act_tb","act_ref_id =3 and cust_id=$temptxt","","","cust_addon");
						if($hand_row=mysql_fetch_array($hand_result)){
							$db_cust_handi =$hand_row["cust_adj_hcp"];
						}
						else if($qf_row2=mysql_fetch_array($qf_set2))
						{
							$db_cust_handi=$qf_row2[0];
						}
						if($db_cust_handi==0||$db_cust_handi=="")
						{
							if($qf_row3=mysql_fetch_array($qf_set3))
							{
								$db_cust_handi=$qf_row3[0];
							}
						}
					}
					
					//$handi=1;
					$htmltxt=$db_cust_name."</td><td valign=top class=fname1 width=50>".$db_cust_cty_name."</td><td valign=top class=fname1 width=20>".$db_cust_pt."</td><td valign=top class=fname1 width=20>".($db_cust_handi==""?"-":$db_cust_handi)."&nbsp;";
	   				$size=150;
	   			}
	   			else if($i==3){
	   				//date
	   				//strftime("%H:%M", strtotime($qrow["arrival_time"]))
	   				$size=40;
	   				$htmltxt=strftime("%H:%M", strtotime($temptxt));
	   				//." - ".$tempvenue." ~ ".$qrow["tee_venue"];
	   			}
   				else{
   					$htmltxt=strtoupper(html_entity_decode($temptxt));
   					//print $qrow[4];
   					if ($qrow["tee_venue"]=="Lakelands Golf Club")
						$tempfooter= "<p class=fname2>Registration is Miss Amelia Heseltine at Clubhouse Area";
					else if ($qrow["tee_venue"]=="Hope Island Golf Course")
						$tempfooter= "<p class=fname2>Registration is Miss Claudia Kiss at Clubhouse Foyer";
					
   					$size=50;
   				}
   				
   				if($htmltxt=="0"||$htmltxt==""||$htmltxt=="<br/>")
   				{
   						$htmltxt="&nbsp;-";
   				}
   				echo "<td valign=top class=fname1 width=$size>".$htmltxt."</td>\n";
   				
   			}
   			
   			
		}
		//print "<td width=150>&nbsp;</td>";
		print "</tr>";
		if(($tbcounter>=3&&$pgcount>=20)||($tbcounter>=5&&$pgcount>=10)||($tbcounter>=0&&$pgcount>=40)){
			$tbmaxcounter=1;
			$tbcounter=0;
			$pgcount=0;
		}
		//print $tbcounter."--".$pgcount;
		
		if ($tbmaxcounter==1){
			$tbmaxcounter=0;
			//$tbcounter=0;
			$pgcount=0;
		/*
		if(is_int($pgcount/40)){
			$tbcounter=4;
			$pgcount=0;
		}
		//print $pgcount;
		if ($tbcounter>3){
			$tbcounter=0;
			$pgcount=0;
			*/
			print "</table><br/>";
			
			print $tempfooter;
			print "<p STYLE='page-break-after: always'></p>";
			print "<div align=center><p align=center><img src=\"images/MercedesTrophyLogo1.gif\">";
			print "</div>";
			//$htmltxt=(strftime("%d-%b-%y", strtotime($temptxt)));
			print "<table width='80%' border='1' cellspacing='0' cellpadding='0' align=center>";
			
			print "<b>Date : ".strftime("%d-%b-%y", strtotime($tempdate))."</b><br/>";
			print "<b>Venue : ".$tempvenue."</b><br/>";
			
			print $tableheader;
			//ob_end_flush();
		}
		
	}
	
	print "</table>";
	/*
	if ($tempvenue=="Hope Island Golf Course")
		print "<p class=fname2>Registration is Miss Claudia Kiss at Clubhouse Foyer";
	else if ($tempvenue=="Lakelands Golf Club")
		print "<p class=fname2>Registration is Miss Amelia Heseltine at Clubhouse Area";
	*/
	print $tempfooter;
	print "<p STYLE='page-break-before: always'></p>";
	?>

<tr>
<td colspan=3>
<div align=center>[ <a href="javascript:history.back();">Back</a> ][ <a href="javascript:window.print();">Print</a> ]</div>
</td>
</tr>
</TBODY></TABLE>


<?
	
}


}
else
{
	$User_Info->go_page("error");
}
?>