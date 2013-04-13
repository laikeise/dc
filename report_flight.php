<?php 
include_once("AustGolf_Addon.php");
include_once("shortname.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;

?><form action="<? $_SERVER[PHP_SELF] ?>" method="post">
<table border="0" cellspacing="0" cellpadding="5" align="center">
<tr id="filterline">
	<td class="fvalue">
        <?
        $HTML->generate_search_list((isset($_POST["player_type"])?($_POST["player_type"]):""),"player_type","ref_playertype_tb","shortname,description",2);
        $HTML->generate_search_list((isset($_POST["country"])?($_POST["country"]):""),"country","ref_country_tb","country_id,country_name",1);
        $HTML->generate_search_list((isset($_POST["transport"])?($_POST["transport"]):""),"transport","ref_transport_tb","transport_sh,transport_name",2);
        $HTML->generate_search_list((isset($_POST["room_type"])?($_POST["room_type"]):""),"room_type","ref_room_tb","room_short_form,room_name",2);
        $HTML->generate_search_cu_list((isset($_POST["arrival_date"])?($_POST["arrival_date"]):""),"arrival_date","cust_tb","arrival_date");
        $HTML->generate_search_cu_list((isset($_POST["depart_date"])?($_POST["depart_date"]):""),"depart_date","cust_tb","depart_date");
        ?>
        </td>
        <td class="fcommentsdark" align=center>Rows per page:&nbsp;&nbsp;<input class="fcommentsdark" type="text" size="2" maxlength="4" name="perpage" value="<?=(isset($_POST["perpage"])?$_POST["perpage"]:8)?>">&nbsp;<input type="submit" name="submit" value="Submit">
        </td>
</tr>
</table>
</form><?

if ($_POST["submit"])
{

if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
	//print "<br/><a href='".$_SERVER["PHP_SELF"]."?s=admin'>Click here to return</a><br />";
	$filename="Flight_".$_SESSION["sUSERID"]."_".date("Ymd").".xls";
	//This is used to export the data into excel format by JOE-25 MAY 2010
	/*if(isset($_POST["export_flight"])|| $_POST["export_flight"]=='Export'){
		$filename="Flight".date("Ymd");
		header("Content-type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=".$filename.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}*/
	//print_r($_POST);		
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	$r_tblist = array_flip($tblist);
        // Remove fields
	$fs = array("day_stay","blacklist","fullname","sex","dietary","hotelcheckindate","hotelcheckoutdate") ;
        foreach ($fs as $f) if (isset($r_tblist["$f"])) unset($tblist[$r_tblist["$f"]]);

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

	ob_start();

	print "<table width='100%' border='1' cellspacing='0' cellpadding='1'>";
	ob_start();
	print "<tr><td colspan=".($tbcount)."><div align=center class=ftitle5>By Arrival / Departure Date Report";
/*
	if($_POST["arrival_date"]!="")
		print "Arrival: ".strftime("%d-%b-%y", strtotime($_POST["arrival_date"]))." ";
	if($_POST["depart_date"]!="")
		print "Departure: ".strftime("%d-%b-%y", strtotime($_POST["depart_date"]))." ";
	if ($_POST["arrival_date"]=="" && $_POST["depart_date"]=="") print "All Records " ;
	*/
	print "</div></td></tr>";
	print "<tr>";
	$tabcounter=0;
	while (list($key, $val) = each($tblist)) {
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
   			if($val=="xx")
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
   					//print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
   					print  "<td valign=top class=ftitle1>". (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</a>";
   					$tabcounter++;}
   				//used to do the arrow up and down for the sort <End>
   					
   				print  "</td>";
   			}
   			$rmtotal++;
	}
	
		$actnumresult= $User_Info->dosearchSQL("ref_act_tb","","act_date","inf");
		$actnum=mysql_num_rows($actnumresult);
	?>
	</tr>
	<?
	$tableheader=preg_replace('/[\r\n]/','',ob_get_contents());//display the content
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
		//$_POST["arrival_date"]
		//$_POST["depart_date"]
		if($tmpstname=="Arrive"){
			$searchcol="`a`.`arrival_date` $orderlist";
		}
		else if($tmpstname=="Depart"){
			$searchcol="`a`.`depart_date` $orderlist";
		}else{
			$searchcol="`a`.`arrival_date`, `a`.`arrival_time` ASC";}
	}
	
	if($_POST["arrival_date"])
		$searchcol="`a`.`arrival_date`, `a`.`arrival_time` ASC";
	else if($_POST["depart_date"])
		$searchcol="`a`.`depart_date`, `a`.`depart_time` ASC";
	else{
		$searchcol="`a`.`arrival_date`, `a`.`arrival_time` ASC";
	}
	
	//print $searchcol;
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$searchcol","$pointer,$list");
	}
	else {
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
                if(($_POST["player_type"]))
                        $wherecol=$wherecol."player_type=\"".$_POST["player_type"]."\" and";
                if(($_POST["country"]))
                        $wherecol=$wherecol." country_name=\"".$_POST["country"]."\" and";
                if(($_POST["transport"]))
                        $wherecol=$wherecol." transport like \"%".$_POST["transport"]."%\" and";
                if(($_POST["room_type"]))
                        $wherecol=$wherecol." room_short_form=\"".$_POST["room_type"]."\" and";
                if(($_POST["arrival_date"]))
                        $wherecol=$wherecol." arrival_date=\"".$_POST["arrival_date"]."\" and";
                if(($_POST["depart_date"]))
                        $wherecol=$wherecol." depart_date=\"".$_POST["depart_date"]."\" and";
                $wherecol=substr($wherecol,0,-3);
		if ($wherecol == '') $wherecol = ' 1';
                $resultx= $User_Info->dosearchSQL("$tmptb AS a","$wherecol","$searchcol","inf");

/*
		if($_POST["arrival_date"]!="")
			$wherecol=" `a`.`arrival_date`=\"".$_POST["arrival_date"]."\" and";
		if($_POST["depart_date"]!="")
		 	$wherecol.=" `a`.`depart_date`=\"".$_POST["depart_date"]."\" and";
		
		$wherecol=substr($wherecol,0,-3);
		// fix annoying SQL bug if there is no WHERE clause but dosearchSQL() will still append the WHERE to the query -- marc 20080522
		if ($wherecol == '') $wherecol = ' 1';
		
		//$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		//$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","inf");
		$resultx= $User_Info->dosearchSQL("$tmptb AS a","$wherecol","$searchcol","inf");
*/
		$summaryarray1=array("$tmptb AS `a`","$wherecol and `a`.","$searchcol","inf", "*","");
		$summaryarray2=array("$tmptb AS `a`","$wherecol ","$searchcol","inf", "*","");
	}
	
	$g=1;
	$pgcount=0;
	$pgrow=mysql_num_rows($resultx);
	while($qrow=mysql_fetch_array($resultx))
	{
		$pgcount++;
		
		
		print "<tr height=65>";
		
		foreach ($tblist as $i => $colname) {
		//for($i=0;$i<$newtbcount;$i++) {
			//print $i;
			//print_r($tblist);
			//if($tempshowitem[$i]==1)
			//{
				
				//if($i<$tbcount)
   					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				//else if($i>=$tbcount){
   				//		$temptxt="$tblist[$i]";
   				//}
   				
   				//to replace the cust id with a default no.
				if($colname=="cust_id"){
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
	   			}
				else if($colname=="player_type"){
	   				//player type
   					//$htmltxt=$AustGolf_Addon->get_player_name($temptxt);
   					$htmltxt=($temptxt);
	   			}
				else if($colname=="day_stay"){
	   				//date stay value
   					$htmltxt=html_entity_decode($temptxt);
   					if(((int)($htmltxt))<0)
   						$htmltxt="Invalid Dates";
	   			}
				else if ($colname=="hotelcheckindate" || $colname=="hotelcheckoutdate" || $colname=="arrival_date" || $colname=="depart_date"){
                                        $htmltxt=(strftime("%d-%b-%y", strtotime($temptxt)));
                                }
				else if ($colname=="arrival_time" || $colname=="depart_time" || $colname=="pickuptime"){
                                        $htmltxt=preg_replace('/:\d\d$/','',$temptxt);
                                }
                                else if($colname=="transport")
                                        $htmltxt=$AustGolf_Addon->get_transport_name($temptxt);
                                else if($colname=="transport2"){
                                        if($temptxt==1)
                                                $mode = "1-way : H -> A";
                                        else if($temptxt==2)
                                                $mode = "1-way : A -> H";
                                        else if($temptxt==3)
                                                $mode = "2-way";
                                        else
                                                $mode = "";
                                        $htmltxt=$mode;
                                }
				else if($colname=="room_short_form"){
                                        //Room type
                                        $htmltxt=($temptxt);
                                }
                                else if($colname=="comment"){
                                        //Comment
                                        $htmltxt=html_entity_decode($temptxt);
                                        if($htmltxt!=="")
                                                $htmltxt=$htmltxt;
                                        $htmltxt="<div style=\"width:200px;height:65px;overflow-y:auto;\" class=fname1>$htmltxt</div>";

                                }
	   			/*else if($i==7||$i==10){
	   				//date
	   				$htmltxt=(strftime("%d-%b-%y", strtotime($temptxt)));
	   			}
				*/
   				else
   					$htmltxt=html_entity_decode($temptxt);
   				
   				if($htmltxt=="0"||$htmltxt==""||$htmltxt=="<br/>")
   				{
   						$htmltxt="&nbsp;-";
   				}
   				echo "<td valign=top class=fname1>".$htmltxt."</td>\n";
   				
   			//}
   			
   			
		}
		
		print "</tr>";
		if (is_int($pgcount/$perpage)&&$pgrow!=$pgcount){
			//print "</table><p STYLE='page-break-after: always'></p><table width='100%' border='1' cellspacing='0' cellpadding='0'  valign=top>";
			print "<!--STARTHEADER--><tr><td colspan=".($tbcount)." style=\"border-left-style:none;border-right-style:none;\">&nbsp;<p STYLE='page-break-before: always'></p></td></tr>$tableheader<!--ENDHEADER-->";
		}
	}
	print "</table><p STYLE='page-break-before: always'></p>";
	?>
	<div align=center class=ftitle5>By Arrival / Departure Date Report</div>
          <TABLE border=0 align="center">
              <TBODY>
              <TR vAlign=top>
              <td align=center colspan=3 class=fname1><br/>Report Total <input class=LegendFooter type=button value="(Legend)" onclick="WindowsOpen('index.php?s=legend&as=view',500,350)">
              </td>
              </tr>
              <TR vAlign=top>
  <td>
<?
$AustGolf_Addon->room_summary($summaryarray1);
?>
</td>
<td>
<?
$AustGolf_Addon->transport_summary($summaryarray2);
?>
</td>
<td>
<?
$AustGolf_Addon->activity_summary($summaryarray2);
?>
</td>
</tr>
<tr>
<td colspan=3>
<?
$AustGolf_Addon->room_date_summary();
?>
</td>

</TR>
</TBODY></TABLE>
<?
        $_c = ob_get_contents();
        ob_end_flush();
        $_c = preg_replace('/<!--STARTHEADER-->.*<!--ENDHEADER-->/m', '' , $_c);
        $_c = preg_replace('/<\/?a[^>]*>/', '' , $_c);
        $fp = fopen("ex/$filename", "w");
        fwrite($fp, "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n$_c");
        fclose($fp);
?>
<div id="footdiv" align=center>[ <a href="javascript:history.back();">Back</a> ][ <a href="javascript:document.getElementById('footdiv').style.display='none';document.getElementById('filterline').style.display='none';window.print();">Print</a> ][ <a href="ex/<?=$filename?>?r=<?=time()?>">Download Excel</a> ]</div>
<?
}
else
{
	$User_Info->go_page("error");
}

}
?>
