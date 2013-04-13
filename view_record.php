<?php
//set up of the tmp structures
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
$tblist=$User_Info->getFieldName("$tmptb");
if(!isset($_GET["tp"]))
{
	$_GET["tp"]="";
}
if(!isset($_GET["hd"]))
{
	$_GET["hd"]=0;
}
$_GET["as"]="view";
//do the access checks here
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])){

?><span class="ftitle3">View Details</span>
<br />
<?php 

	$tmpcol=$tblist[$tmpcolnum];
	$resultx= $User_Info->dosearchSQL("$tmptb",ctype_alnum($tmpcolname)?"$tmpcol='$tmpcolname'":"$tmpcol=$tmpcolname","","");
	print "<br />";
	print "<table cellSpacing=0 cellPadding=2 width='100%' border=1>\n";
	if($qrow=mysql_fetch_array($resultx))
	{
		$cust_add_result= $User_Info->dosearchSQL("cust_add_tb",("cust_id=".$qrow["cust_id"]),"","");
		if($cust_add_row=mysql_fetch_array($cust_add_result)){
			print "";
		}
		while (list($key, $val) = each($tblist)) {
				if ($_SESSION["sCONTROLLVL"] != 'GRP02' && $val == 'blacklist') continue; // display blacklist to admin group only -- marc
   				$temptxt=$qrow[$key];
   				$htmltxt=$temptxt;
   			
				if($key!=0){
   					
   					print  "<tr>\n<td valign=top class=ftitle1 align=right width='20%'> " . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n<td valign=top class=fname1 align=left>";
	   				if($htmltxt==""){
						print "&nbsp;";}
					else{
						if($key==4)
							print $AustGolf_Addon->get_player_name($htmltxt);
   						else if($key==9||$key==12){
		   					
		   					$tempaf=explode(":~:",$qrow[$key]);
		   					print ($tempaf[0]==""?"&nbsp":$tempaf[0]);
		   					if($tempaf[1]=="BI"){
		   						print " &nbsp;<b>Airport :</b> "." Brisbane International";
		   						}
		   					else if($tempaf[1]=="DA"){
		   						print " &nbsp;<b>Airport :</b> "." Brisbane Domestic";
		   						}
		   					else if ($tempaf[1]=="NA"){
		   						print " &nbsp;<b>Airport :</b> "." Not Applicable";
		   						}
   						}
   						else if($key==7||$key==10){
   							if ($qrow[$key]!="0000-00-00")
   								print strftime("%d-%b-%Y", strtotime($qrow[$key]));
   							else
   								print "&nbsp;";
   						}
   						else if($key==8||$key==11){
   							if ($qrow[$key]!="00:00:00")
   								print strftime("%H:%M", strtotime($qrow[$key]))." (24 hour format)";
   							else
   								print "&nbsp;";
   						}
   						else if($key==13){
	   						
   							$temptp=explode(":~:",$qrow[$key]);
   							$temptp1=explode(":~~:",$qrow[$key]);
   							$temptp1[1] = substr ( $temptp1[1], 0, 1 );
   							
   							if($temptp1[1]==1)
	   							$mode = "(1-way : H -> A)";
	   						else if($temptp1[1]==2)
	   				 			$mode = "(1-way : A -> H)";
		   					else if($temptp1[1]==3)	
		   						$mode = "(2-way)";
		   					else
		   						$mode = "";
		   				
							print $AustGolf_Addon->get_transport_name($temptp1[0]);
							print "&nbsp;&nbsp;".$mode;
							print " <br><b>Comments :</b> ".$temptp[1];
   						}
   						else if ($key==14){
   							print $AustGolf_Addon->get_room_name($temptxt);
   						}
	   					else{
   							print $htmltxt;
   						}
					}	
					print "</td><td valign=top class=ftitle1 align=right width='10%'>&nbsp;</td>\n</tr>\n";
				}
		}
			if($_GET["tp"]=="gt")
			{
	?>   			
    		<TR>
                <TD class=ftitle1 vAlign=top align=right width="20%">Company</TD>
                <TD class=fname1 vAlign=top align=left><?=html_entity_decode($cust_add_row["cust_coy"]);?></TD>
                <TD class=ftitle1 vAlign=top align=right width="10%">&nbsp;</TD>
			</TR>
			<TR>
                <TD class=ftitle1 vAlign=top align=right width="20%">Designation</TD>
                <TD class=fname1 vAlign=top align=left><?=html_entity_decode($cust_add_row["cust_dest"]);?></TD>
                <TD class=ftitle1 vAlign=top align=right width="10%">&nbsp;</TD>
			</TR>
			<TR>
                <TD class=ftitle1 vAlign=top align=right width="20%">Contact Tel</TD>
                <TD class=fname1 vAlign=top align=left><?=html_entity_decode($cust_add_row["cust_tel"]);?></TD>
                <TD class=ftitle1 vAlign=top align=right width="10%">&nbsp;</TD>
			</TR>
			<TR>
                <TD class=ftitle1 vAlign=top align=right width="20%">Contact Fax</TD>
                <TD class=fname1 vAlign=top align=left><?=html_entity_decode($cust_add_row["cust_fax"]);?></TD>
                <TD class=ftitle1 vAlign=top align=right width="10%">&nbsp;</TD>
			</TR>
			<TR>
                <TD class=ftitle1 vAlign=top align=right width="20%">Email Address</TD>
                <TD class=fname1 vAlign=top align=left><?=html_entity_decode($cust_add_row["cust_email"]);?></TD>
                <TD class=ftitle1 vAlign=top align=right width="10%">&nbsp;</TD>
			</TR>
    <?
		}
	
		if($_GET["hd"]==0)
		{
			if($_GET["tp"]!="gt")
			{
              	$ref_handi_result=$User_Info->doSearchSQL("ref_handi_tb","","","inf");
?>
		<TR>
                <TD class=ftitle1 vAlign=top align=right width="20%">Home Club</TD>
                <TD class=fname1 vAlign=top align=left>
                <?=html_entity_decode($cust_add_row["cust_club"]);?>
                </TD>
    			<TD class=ftitle1 vAlign=top align=right width="10%">&nbsp;</TD>
    	</TR>
		<TR>
                <TD class=ftitle4 vAlign=top align=center colspan="3">Personal Golf Record</TD>
              </TR>
              <tr><TD class=ftitle1 vAlign=top align=right width="20%">&nbsp;</TD>
              <td>
              <table cellSpacing=0 cellPadding=2 width="100%" border=1>
                <td width=16%>&nbsp;</td>
                <td class=ftitle1 vAlign=top align=center width=14%>Division</td>
                <td class=ftitle1 vAlign=top align=center width=14%>Initial Handicap</td>
                <td class=ftitle1 vAlign=top align=center width=14%>Par</td>
                <td class=ftitle1 vAlign=top align=center width=14%>Course Rating</td>
                <td class=ftitle1 vAlign=top align=center width=14%>Result (Stableford Points)</td>
                <td class=ftitle1 vAlign=top align=center width=14%>Adjusted Handicap</td>
                </tr>
<?
              	while($ref_handi_row=mysql_fetch_array($ref_handi_result))
              	{
              		$db_ref_id=$ref_handi_row["handi_ref_id"];
              		$db_ref_name=$ref_handi_row["handi_name"];
              		if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP"||(substr($_SESSION["sCONTROLLVL"],0,3)=="MGM"&&($db_ref_id==1||$db_ref_id==2)))
              {
              ?>
              
              <TR>
                <TD class=ftitle4 vAlign=top align=center><?=$db_ref_name?></TD>
              <?
              	// Qualifying Round use 1
              	$db1_cust_handi_div="";
				$db1_cust_initial_hcp ="0";
				$db1_cust_adj_hcp ="0";
				$db1_cust_par ="0";
				$db1_cust_course_rating ="0";
				$db1_cust_result ="0";
              	$handi_result=$User_Info->doSearchSQL("cust_handi_tb",("cust_id=".$qrow["cust_id"]." and ref_handi_id='$db_ref_id'"),"","inf");
    			if($rm_row=mysql_fetch_array($handi_result))
				{
					$db1_cust_handi_div=$rm_row["cust_handi_div"];
					$db1_cust_initial_hcp =$rm_row["cust_initial_hcp"];
					$db1_cust_adj_hcp =$rm_row["cust_adj_hcp"];
					$db1_cust_par =$rm_row["cust_par"];
					$db1_cust_course_rating =$rm_row["cust_course_rating"];
					$db1_cust_result =$rm_row["cust_result"];
				}
				else{
					//print "Missing 1";
					$db1_cust_handi_div="-";
					$db1_cust_initial_hcp ="";
					$db1_cust_adj_hcp ="";
					$db1_cust_par ="";
					$db1_cust_course_rating ="";
					$db1_cust_result ="";
				}

              ?>
        
              <TD class=fname1 vAlign=top align=left> <?=$db1_cust_handi_div?> </TD>
              	 <TD class=fname1 vAlign=top align=left><INPUT name=<?=$db_ref_id?>-inithcp  size=10 value="<?=html_entity_decode($db1_cust_initial_hcp);?>" Disabled></TD>	
              	 <TD class=fname1 vAlign=top align=left><INPUT name=<?=$db_ref_id?>-par size=10 value="<?=html_entity_decode($db1_cust_par);?>" Disabled></TD>
              	 <TD class=fname1 vAlign=top align=left><INPUT name=<?=$db_ref_id?>-course_rating size=10 value="<?=html_entity_decode($db1_cust_course_rating);?>" Disabled></TD>
              	 <TD class=fname1 vAlign=top align=left><INPUT name=<?=$db_ref_id?>-result_sp size=10 value="<?=html_entity_decode($db1_cust_result);?>" Disabled></TD>
              	 <TD class=fname1 vAlign=top align=left><INPUT name=<?=$db_ref_id?>-adjhcp size=10 value="<?=html_entity_decode($db1_cust_adj_hcp);?>" Disabled></TD>
             </tr>
              <?
              }
            }	
            
            
    		?>
    		</table>
                </td><TD class=ftitle1 vAlign=top align=right width="10%">&nbsp;</TD></tr>
		   <TR>
		   <TR>
                <TD class=ftitle1 vAlign=top align=right>&nbsp;</TD>
                <TD class=fname1 vAlign=top align=left>
                <ul>
                <li>Minimum age for participation : 18 years old<br/></li>
                <li>Participants must include a copy of a valid membership card which lists their official handicap ( must be dated later than <?=$handicap_date;?> ) and original scorecards of each tournament round are also required to be submitted before <?=$scorecard_date;?> to be eligible to participate in the Asian Final.<br/></li>
                <li>Participant's handicap will be adjusted at every tournament level throughout to World Final.<br/></li>
                <li>All handicap revisions will be reflected in the scorecard for each round of play.<br/></li>
                <li>No participant will be allowed a handicap higher than when first entering MercedesTrophy competitions in his country final this year.<br/></li>
                <li>Participant's handicap on the day of play and their performance in the country final will be taken into consideration.</li>
                </ul>
                
                </TD>
                <TD class=ftitle1 vAlign=top align=right>&nbsp;</TD>
              </TR>
		<?        
		}            
		}
		
		if($_GET["hd"]==0)
		{
            if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP" ||$_GET["tp"]=="gt")
            {
              ?>
              
              <TR>
                <TD height="22" colspan="3" align=center vAlign=top class=ftitle4>Activity / Meal</TD>
            
              </TR>
              <TR>
                <TD class=ftitle1 vAlign=top align=right>&nbsp;</TD>
                <TD class=fname1 vAlign=top align=left>
                <?
                	$AustGolf_Addon->generate_activity_tick_ver2($qrow["cust_id"],0);
                ?>
                <TD class=ftitle1 vAlign=top align=right>&nbsp;</TD>
              </TR><?
			}
		}
	}
	print "</table>";

	if ($_SESSION["sCONTROLLVL"]=="GRP02" || date('Ymd') < $SregCutOff) {
?>
	<br/><div align=center><INPUT type=reset value="Edit" name=Reset onclick="window.location='http://<?=($_SERVER["HTTP_HOST"]).$_SERVER["SCRIPT_NAME"].("?s=edit_cust&tbn=cust_tb&id=".$tmpcolname."&tcol=".$tmpcolnum."&as=view&tp=".$_GET["tp"])?>'"></div>
<?
	}
	print "</form>\n";
	
?><br /><br />

<?php
}
else{
	$User_Info->go_page("error");
}

?>
