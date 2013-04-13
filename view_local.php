<!--
<style type="text/css">
	a.nav:link {text-decoration: none; #FFFFFF;  }
	a.nav:visited {text-decoration: none; color:  #FFFFFF  }
	a.nav:hover {text-decoration: none; color: #CCCCCC;  }
</style>
-->
<?php 
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{
	//print "<br/><a href='".$_SERVER["PHP_SELF"]."?s=admin'>Click here to return</a><br />";
?>
<br><span class="ftitle3">Tournament Players</span>

<br />
<?php 
		
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	$tblist=$User_Info->getFieldName("$tmptb");
	$tblist=$User_Info->getFieldName("$tmptb");
        $r_tblist = array_flip($tblist);
        // Remove fields
        $fs = array("fullname","dietary"/*,"hotelcheckindate","hotelcheckoutdate"*/,"pickuptime") ;
        foreach ($fs as $f) if (isset($r_tblist["$f"])) unset($tblist[$r_tblist["$f"]]);
	
	$tempshowitem=array();
	
	//adding of new display list
	$tbcount=count($tblist);
	$newtbcount=$tbcount;
	//to add a new item
	
	$newitem=array("1"=>"Arrival",
	"2"=>"Departure"
	/*
	"3"=>"Activity",
	"4"=>"Qualifying<br/>Round",
	"5"=>"Country<br/>Final",
	"6"=>"Asian<br/>Final",
	*/
	);
	//print_r($newitem);
	
/*
	while (list($key, $val) = each($newitem)) 
	{
		$newtbcount=$newtbcount+1;
		$newdisplay=array("$newtbcount"=>"$val");
		$tblist=array_merge($tblist,$newdisplay);
	}
*/
	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
   	//end of reconstruction of the get list
	
	//$newdisplay=array("$newtbcount"=>"Arrival");
	//$tblist=array_merge($tblist,$newdisplay);
	
	print "<table width='100%' border='1' cellspacing='0' cellpadding='1'>";
	print "<tr>";
	
	while (list($key, $val) = each($tblist)) {
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
			//remove blacklist from display
   			/*if($val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'||$val=='depart_date'||$val=='depart_time'||$val=='depart_flight' || $val == 'blacklist')
   			{
   				$tempshowitem=array_merge($tempshowitem,array($key=>0));
   			}
   			else{
*/
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
   								print  "<td valign=top class=ftitle1><a class=\"nav\" href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
								print  "<img src=\"images/arrow_ASC.gif\">";}
							else{
								print  "<td valign=top class=ftitle1><a class=\"nav\" href=\"index.php?".$sortlist."&st=$val&od=asc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   								print  "<img src=\"images/arrow_DESC.gif\">";}
   						}
   					}
   					else
   						print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\" class=\"nav\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   				}
   				else
   					print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\" class=\"nav\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   				//used to do the arrow up and down for the sort <End>
   					
   				print  "</td>";
   			//}
   			$rmtotal++;
	}
	
	//print_r($tempshowitem);
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
	//doing order by
	//$searchcol="$tblist[0]";
	if(($tmpstname)){
		//print "true";
		if($tmpstname=="Arrival"){
			$searchcol="arrival_date $orderlist";
		}
		else if($tmpstname=="Departure"){
			$searchcol="depart_date $orderlist";
		}else{
			$searchcol="$tmpstname $orderlist";}
	}
	else{
		$searchcol="$tblist[0] $orderlist";
	}
	
	//print $searchcol;
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$searchcol","inf");
	}
	else if(isset($_GET["Filter"])){
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		//$wherecol="";
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
		$resultx= $User_Info->dosearchSQL("$tmptb","$wherecol","$searchcol","inf");
	}
	else{
		//$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		//$wherecol=$wherecol." and ( a.player_type=\"CC\" || a.player_type=\"G\" ) and b.cust_id=\"".$_SESSION["sCONTROLLVL"]."\"";
		//$resultx= $User_Info->dosearchSQL("$tmptb AS a, cust_holder AS b","$wherecol","a.$searchcol","inf");
		//$wherecol=" (  a.player_type=\"T\" )  and a.cust_id=b.cust_id";
		$wherecol=" (  a.player_type=\"T\" )  and a.cust_id=b.cust_id and b.grp_id=\"".$_SESSION["sCONTROLLVL"]."\"";
		
		$resultx= $User_Info->dosearchSQL("$tmptb AS a, cust_holder AS b","$wherecol","a.$searchcol","inf");
	}
	
	//$resultx= $User_Info->dosearchSQL("$tmptb","","$tblist[0]","$pointer,$list");
	$g=1;
	if($qrow=mysql_fetch_array($resultx))
	{
		mysql_data_seek($resultx, 0);
	while($qrow=mysql_fetch_array($resultx))
	{
		//print "test";
		//$sub=ereg_replace ("\n", "&lt;br /&gt;", $row['rob_subject']);
		print "<tr height=65>";
		foreach ($tblist as $i => $colname)
		//for($i=0;$i<$newtbcount;$i++) {
		{
			//print $i;
			//print_r($tblist);
			//if($tempshowitem[$i]==1)
			//{
				//if($i<$tbcount)
   					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				/*else if($i>=$tbcount){
   					if($tblist[$i]=="Arrival"){
   						//$val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'
   						//echo strftime("%d-%m-%y", strtotime($qrow["arrival_date"]));
   						$aflight=explode(":~:",$qrow["arrival_flight"]);
   						$temptxt=(($qrow["arrival_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["arrival_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["arrival_time"]))."<br/>".$aflight[0].(isset($aflight[1])!=""?("<br/>".$AustGolf_Addon->get_airport_name($aflight[1])):"").""):"");
   					}
   					else if($tblist[$i]=="Departure"){
   						//$val=='depart_date'||$val=='depart_time'||$val=='depart_flight'
   						$dflight=explode(":~:",$qrow["depart_flight"]);
   						$temptxt=(($qrow["depart_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["depart_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["depart_time"]))."<br/>".$dflight[0].(isset($dflight[1])!=""?("<br/>".$AustGolf_Addon->get_airport_name($dflight[1])):"").""):"");
   					}
   					else
   						$temptxt="$tblist[$i]";
   				}*/

   				//to replace the cust id with a default no.
				if($colname=="cust_id") 
				{
   					$htmltxt=($page-1)*$list+$g;
   					$g++;
	   			}
				else if($colname=="player_type")
				{
	   				//player type
   					//$htmltxt=$AustGolf_Addon->get_player_name($temptxt);
   					$htmltxt=$temptxt;
	   			}
				else if($colname=="day_stay")
				{
	   				//date stay value
   					$htmltxt=html_entity_decode($temptxt);
   					if(((int)($htmltxt))<0)
   						$htmltxt="Invalid Dates";
	   			}
				else if ($colname=="hotelcheckindate" || $colname=="hotelcheckoutdate" || $colname=="arrival_date" || $colname=="depart_date"){
                                        $htmltxt=(strftime("%d-%b-%y", strtotime($temptxt)));
                                }
                                else if ($colname=="arrival_time" || $colname=="depart_time" || $colname=="pickuptime")
                                        $htmltxt=preg_replace('/:\d\d$/','',$temptxt);
                                else if($colname=="transport")
                                        $htmltxt=$AustGolf_Addon->get_transport_name($temptxt) ;
                                else if($colname=="transport2")
                                {
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
                                else if($colname=="room_short_form")
                                {
                                        //Room type
                                        $htmltxt=$AustGolf_Addon->get_room_name($temptxt);
                                }
                                else if($colname=="comment")
                                {
                                        //Comment
                                        $htmltxt=html_entity_decode($temptxt);
                                        if($htmltxt!==""&&$_GET["pi"]!=1)
                                                $htmltxt="<a href=\"index.php?s=comments&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view\" border=0 target=\"APPLET\" onclick=\"WindowsOpen('index.php?s=comments&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view',350,250)\" title=\"$htmltxt\"><img border=0 src='images/Subscribers.gif' alt=\"".$htmltxt."\"></a>";
                                }
                                else if($colname=="blacklist")
                                {
                                        //Blacklist
                                        $htmltxt="<span style='font-weight:bold;color:#f00'>$temptxt</span>";
                                }
   				else
   					$htmltxt=html_entity_decode($temptxt);
   				
   				if($htmltxt=="0"||$htmltxt==""||$htmltxt=="<br/>")
   				{
   						$htmltxt="&nbsp;-";
   				}
   				echo "<td valign=top class=fname1>".$htmltxt."</td>\n";
   			//}
		}
		print "<td valign=top class=fname1>\n";
		print "<a href=".$_SERVER["PHP_SELF"]."?s=view_record&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit><!--<img src='images/filesearch.gif' border=0 alt=\"View this Player\" />-->[View]</a><br/>\n";
		//if ($_SESSION["sCONTROLLVL"]=="GRP02" || date('Ymd') < $SregCutOff) {
			//print "<a href=".$_SERVER["PHP_SELF"]."?s=edit_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit><!--<img src='images/edit.gif' border=0 alt=\"Edit this Player\" />-->[Edit]</a><br/>\n";
			if ($_SESSION["sCONTROLLVL"]=="GRP02" || date('Ymd') < $SregCutOff) {
			print "<a href=".$_SERVER["PHP_SELF"]."?s=edit_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit><!--<img src='images/edit.gif' border=0 alt=\"Edit this Player\" />-->[Edit]</a><br/>\n";
			print "<br/><a href=# onclick='return delWindow(\"s=delete_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\",\"".urlencode($qrow["family_name"]." ".$qrow["first_name"])."\");return false;' >";
			//print "<a href=# onclick='return delWindow(\"s=delete_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\",\""." a  "."\");return false;' >";
			print "<!--<img src=\"images/delete.gif\" border=\"0\" alt=\"Delete this Player\" >-->[Delete]</a>";
		}
		print "<br /></td>\n";
		print "</tr>";
	}
	}
	//print $g;
	else
	{	
		print "<tr>";
		for($h=0;$h<=23;$h++){
		print "<td valign=top class=fname1>&nbsp;-</td>\n";	
		}
		print "</tr>";
	}
	print "<tr>";
	print "<td valign=top class=fname1 colspan=24 align=center>";
	print "<a  href='javascript:WindowsOpen(\"index.php?s=view_local2&tbn=cust_tb&as=view\",800,500)'>View Handicap Summary</a></td>\n";
	print "</tr>";
	print "</table>";

?>
<b>Note: To view comments, please place your cursor over the respective comments icon</b> <img src="images/Subscribers.gif">.
<br /><br />

<br><span class="ftitle3">Guest Players</span>
<br />

<?php 
		/*
	$rmtotal=0;
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
	$tmpstname=((isset($_GET["st"]))?$_GET["st"]:"");
	*/
	$tblist=$User_Info->getFieldName("$tmptb");
        $r_tblist = array_flip($tblist);
        // Remove fields
        $fs = array("fullname","dietary"/*,"hotelcheckindate","hotelcheckoutdate"*/,"pickuptime") ;
        foreach ($fs as $f) if (isset($r_tblist["$f"])) unset($tblist[$r_tblist["$f"]]);

	
	$tempshowitem=array();
	
	//adding of new display list
	$tbcount=count($tblist);
	$newtbcount=$tbcount;
	//to add a new item
	
	$newitem=array("1"=>"Arrival",
	"2"=>"Departure"
	/*
	"3"=>"Activity",
	"4"=>"Qualifying<br/>Round",
	"5"=>"Country<br/>Final",
	"6"=>"Asian<br/>Final",
	*/
	);
	//print_r($newitem);
	
/*
	while (list($key, $val) = each($newitem)) {
		$newtbcount=$newtbcount+1;
		$newdisplay=array("$newtbcount"=>"$val");
		$tblist=array_merge($tblist,$newdisplay);
	}
*/
	//reconstruction of the get list
	$sortlist=$AustGolf_Addon->link_construction();
   	//end of reconstruction of the get list
	
	//$newdisplay=array("$newtbcount"=>"Arrival");
	//$tblist=array_merge($tblist,$newdisplay);
	
	print "<table width='100%' border='1' cellspacing='0' cellpadding='1'>";
	
	print "<tr>";
	while (list($key, $val) = each($tblist)) {
			//print $key.$val;
			//remove display of primary key and the arrival & departure time
			//remove blacklist from display
   			/*if($val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'||$val=='depart_date'||$val=='depart_time'||$val=='depart_flight' || $val == 'blacklist')
   			{
   				$tempshowitem=array_merge($tempshowitem,array($key=>0));
   			}
   			else{
	*/
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
   								print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
								print  "<img src=\"images/arrow_ASC.gif\">";}
							else{
								print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=asc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   								print  "<img src=\"images/arrow_DESC.gif\">";}
   						}
   					}
   					else
   						print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   				}
   				else
   					print  "<td valign=top class=ftitle1><a href=\"index.php?".$sortlist."&st=$val&od=desc\"><span class=\"ftitle1\">" . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val) . "</span></a>";
   				//used to do the arrow up and down for the sort <End>
   					
   				print  "</td>";
   			//}
   			$rmtotal++;
	}
	
	//print_r($tempshowitem);
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
	//doing order by
	//$searchcol="$tblist[0]";
	if(($tmpstname)){
		//print "true";
		if($tmpstname=="Arrival"){
			$searchcol="arrival_date $orderlist";
		}
		else if($tmpstname=="Departure"){
			$searchcol="depart_date $orderlist";
		}else{
			$searchcol="$tmpstname $orderlist";}
	}
	else{
		$searchcol="$tblist[0] $orderlist";
	}
	
	//print $searchcol;
	if(!empty($tmpcolnum)&&!empty($tmpcolname))
	{
		$tmpcol=$tblist[$tmpcolnum];
		//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol='$tmpcolname'","$searchcol","inf");
	}
	else if(isset($_GET["Filter"])){
		$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		//$wherecol="";
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
	}
	else{
		//$wherecol=($_SESSION["sRecord"]=="ALL"?"":("country_name=\"".$_SESSION["sRecord"]."\""));
		$wherecol=" ( a.player_type=\"CC\" || a.player_type=\"G\" || a.player_type=\"O\" || a.player_type=\"\") and a.cust_id=b.cust_id and b.grp_id=\"".$_SESSION["sCONTROLLVL"]."\"";
		$resultx= $User_Info->dosearchSQL("$tmptb AS a, cust_holder AS b","$wherecol","a.$searchcol","inf");
	}
	
	//$resultx= $User_Info->dosearchSQL("$tmptb","","$tblist[0]","$pointer,$list");
	$g=1;
	if($qrow=mysql_fetch_array($resultx))
	{
		mysql_data_seek($resultx, 0);
	
	while($qrow=mysql_fetch_array($resultx))
	{
		//print "test";
		//$sub=ereg_replace ("\n", "&lt;br /&gt;", $row['rob_subject']);
		print "<tr height=65>";
		foreach ($tblist as $i => $colname)
		{
		//for($i=0;$i<$newtbcount;$i++) {
			//print $i;
			//print_r($tblist);
			//if($tempshowitem[$i]==1)
			//{
				//if($i<$tbcount)
   					$temptxt=ereg_replace ("\n", "&lt;br /&gt;", $qrow[$i]);
   				/*else if($i>=$tbcount){
   					if($tblist[$i]=="Arrival"){
   						//$val=='arrival_date'||$val=='arrival_time'||$val=='arrival_flight'
   						//echo strftime("%d-%m-%y", strtotime($qrow["arrival_date"]));
   						$aflight=explode(":~:",$qrow["arrival_flight"]);
   						$temptxt=(($qrow["arrival_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["arrival_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["arrival_time"]))."<br/>".$aflight[0].(isset($aflight[1])!=""?("<br/>".$AustGolf_Addon->get_airport_name($aflight[1])):"").""):"");
   					}
   					else if($tblist[$i]=="Departure"){
   						//$val=='depart_date'||$val=='depart_time'||$val=='depart_flight'
   						$dflight=explode(":~:",$qrow["depart_flight"]);
   						$temptxt=(($qrow["depart_date"]!="0000-00-00")?((strftime("%d-%m", strtotime($qrow["depart_date"])))."<br/>".strftime("%H:%M", strtotime($qrow["depart_time"]))."<br/>".$dflight[0].(isset($dflight[1])!=""?("<br/>".$AustGolf_Addon->get_airport_name($dflight[1])):"").""):"");
   					}
   					else
   						$temptxt="$tblist[$i]";
   				}
				*/
   				//to replace the cust id with a default no.
				if($colname=="cust_id")
                                {
                                        $htmltxt=($page-1)*$list+$g;
                                        $g++;
                                }
                                else if($colname=="player_type")
                                {
                                        //player type
                                        $htmltxt=$AustGolf_Addon->get_player_name($temptxt);
                                }
                                else if($colname=="day_stay")
                                {
                                        //date stay value
                                        $htmltxt=html_entity_decode($temptxt);
                                        if(((int)($htmltxt))<0)
                                                $htmltxt="Invalid Dates";
                                }
                                else if ($colname=="hotelcheckindate" || $colname=="hotelcheckoutdate" || $colname=="arrival_date" || $colname=="depart_date"){
                                        $htmltxt=(strftime("%d-%b-%y", strtotime($temptxt)));
                                }
                                else if ($colname=="arrival_time" || $colname=="depart_time" || $colname=="pickuptime")
                                        $htmltxt=preg_replace('/:\d\d$/','',$temptxt);
                                else if($colname=="transport")
                                        $htmltxt=$AustGolf_Addon->get_transport_name($temptxt) ;
                                else if($colname=="transport2")
                                {
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
				else if($colname=="room_short_form")
                                {
                                        //Room type
                                        $htmltxt=$AustGolf_Addon->get_room_name($temptxt);
                                }
                                else if($colname=="comment")
                                {
                                        //Comment
                                        $htmltxt=html_entity_decode($temptxt);
                                        if($htmltxt!==""&&$_GET["pi"]!=1)
                                                $htmltxt="<a href=\"index.php?s=comments&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view\" border=0 target=\"APPLET\" onclick=\"WindowsOpen('index.php?s=comments&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view',350,250)\" title=\"$htmltxt\"><img border=0 src='images/Subscribers.gif' alt=\"".$htmltxt."\"></a>";
                                }
                                else if($colname=="blacklist")
                                {
                                        //Blacklist
                                        $htmltxt="<span style='font-weight:bold;color:#f00'>$temptxt</span>";
                                }
                                else
   					$htmltxt=html_entity_decode($temptxt);
   				
   				if($htmltxt=="0"||$htmltxt==""||$htmltxt=="<br/>")
   				{
   						$htmltxt="&nbsp;-";
   				}
   				echo "<td valign=top class=fname1>".$htmltxt."</td>\n";
   			//}
		}
		print "<td valign=top class=fname1>\n";
		print "<a href=\"".$_SERVER["PHP_SELF"]."?s=view_record&tbn=$tmptb&id=$qrow[0]&tcol=0&as=view&tp=gt\"><!--<img src='images/filesearch.gif' border=0 alt=\"View this Player\" />-->[View]</a><br/>\n";
		//if ($_SESSION["sCONTROLLVL"]=="GRP02" || date('Ymd') < $SregCutOff) {
			if ($_SESSION["sCONTROLLVL"]=="GRP02" || date('Ymd') < $SregCutOff) {
			print "<a href=\"".$_SERVER["PHP_SELF"]."?s=edit_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=edit&tp=gt\"><!--<img src='images/edit.gif' border=0 alt=\"Edit this Player\" />-->[Edit]</a><br/>\n";
			$fullname=html_entity_decode($qrow["family_name"])." ".html_entity_decode($qrow["first_name"]);
			//$fullname=htmlentities($qrow["family_name"])." ".htmlentities($qrow["first_name"]);
			$fullname=htmlspecialchars($fullname,ENT_QUOTES);
			//$fullname=str_replace("'","",$fullname);
			//$fullname=str_replace("\"","",$fullname);
			print "<br/><a href=# onclick='return delWindow(\"s=delete_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\",\"".urlencode($fullname)."\");return false;' >";
			//print "<a href=# onclick='return delWindow(\"s=delete_cust&tbn=$tmptb&id=$qrow[0]&tcol=0&as=deldata&pi=1\");return false;' >";
			print "<!--<img src=\"images/delete.gif\" border=\"0\" alt=\"Delete this Player\" >-->[Delete]</a>";
		}
		print "<br /></td>\n";
		print "</tr>";
	}
	}
	else
	{	
		print "<tr>";
		for($h=0;$h<=23;$h++){
		print "<td valign=top class=fname1>&nbsp;-</td>\n";	
		}
		print "</tr>";
	}
	print "<tr>";
	print "<td valign=top class=fname1 colspan=24 align=center>";
	print "<a  href='javascript:WindowsOpen(\"index.php?s=view_local3&tbn=cust_tb&as=view\",800,500)'>View Activities Summary</a></td>\n";
	print "</tr>";
	print "</table>";

?>
<b>Note: To view comments, please place your cursor over the respective comments icon</b> <img src="images/Subscribers.gif">.
<br /><br />

<?
/*
//if(isset($_GET["Filter"])){
$Paging->backpage($_GET["tbn"],$wherecol,$list);
$Paging->nextpage($_GET["tbn"],$wherecol,$list);
print "<br/>";
$Paging->listpaging($_GET["tbn"],$wherecol,$list);
*/


}
else
{
	$User_Info->go_page("error");
}
?>
