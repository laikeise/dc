<?php 
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
//set up of the tmp structures
$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
$tblist=$User_Info->getFieldName("$tmptb");
if(!isset($_GET["tp"]))
{
	$_GET["tp"]="";
}
$_GET["as"]="edit";

isset($_POST["HotelCheckinDateY"]) ? $HotelCheckinDateY = $_POST["HotelCheckinDateY"] : $HotelCheckinDateY = null;
isset($_POST["HotelCheckinDateM"]) ? $HotelCheckinDateM = $_POST["HotelCheckinDateM"] : $HotelCheckinDateM = null;
isset($_POST["HotelCheckinDateD"]) ? $HotelCheckinDateD = $_POST["HotelCheckinDateD"] : $HotelCheckinDateD = null;
isset($_POST["HotelCheckoutDateY"]) ? $HotelCheckoutDateY = $_POST["HotelCheckoutDateY"] : $HotelCheckoutDateY = null;
isset($_POST["HotelCheckoutDateM"]) ? $HotelCheckoutDateM = $_POST["HotelCheckoutDateM"] : $HotelCheckoutDateM = null;
isset($_POST["HotelCheckoutDateD"]) ? $HotelCheckoutDateD = $_POST["HotelCheckoutDateD"] : $HotelCheckoutDateD = null;
isset($_POST["depart_pickuptime_hr"]) ? $depart_pickuptime_hr = $_POST["depart_pickuptime_hr"] : $depart_pickuptime_hr = null;
isset($_POST["depart_pickuptime_min"]) ? $depart_pickuptime_min = $_POST["depart_pickuptime_min"] : $depart_pickuptime_min = null;

// Checkin and Checkout date time validation
isset($_POST["CheckinDateY"]) ? $CheckinDateY = $_POST["CheckinDateY"] : $CheckinDateY = null;
isset($_POST["CheckinDateM"]) ? $CheckinDateM = $_POST["CheckinDateM"] : $CheckinDateM = null;
isset($_POST["CheckinDateD"]) ? $CheckinDateD = $_POST["CheckinDateD"] : $CheckinDateD = null;
isset($_POST["arrival_time_hr"]) ? $arrival_time_hr = $_POST["arrival_time_hr"] : $arrival_time_hr = null;
isset($_POST["arrival_time_min"]) ? $arrival_time_min = $_POST["arrival_time_min"] : $arrival_time_min = null;

isset($_POST["CheckoutDateY"]) ? $CheckoutDateY = $_POST["CheckoutDateY"] : $CheckoutDateY = null;
isset($_POST["CheckoutDateM"]) ? $CheckoutDateM = $_POST["CheckoutDateM"] : $CheckoutDateM = null;
isset($_POST["CheckoutDateD"]) ? $CheckoutDateD = $_POST["CheckoutDateD"] : $CheckoutDateD = null;
isset($_POST["depart_time_hr"]) ? $depart_time_hr = $_POST["depart_time_hr"] : $depart_time_hr = null;
isset($_POST["depart_time_min"]) ? $depart_time_min = $_POST["depart_time_min"] : $depart_time_min = null;

$CheckinDateTime = (strlen($CheckinDateY)==2 && is_numeric($CheckinDateY) ? "20".$CheckinDateY : $CheckinDateY);
$CheckinDateTime .= "-".(strlen($CheckinDateM)==1 && is_numeric($CheckinDateM)? "0".$CheckinDateM : $CheckinDateM);
$CheckinDateTime .= "-".(strlen($CheckinDateD)==1 && is_numeric($CheckinDateD)? "0".$CheckinDateD : $CheckinDateD);
$CheckinDateTime .= " ".(strlen($arrival_time_hr)==1 && is_numeric($arrival_time_hr)? "0".$arrival_time_hr : $arrival_time_hr);
$CheckinDateTime .= ":".((strlen($arrival_time_min)==1) && is_numeric($arrival_time_min)? "0".$arrival_time_min : $arrival_time_min);

$CheckoutDateTime = (strlen($CheckoutDateY)==2 && is_numeric($CheckoutDateY)? "20".$CheckoutDateY : $CheckoutDateY);
$CheckoutDateTime .= "-".(strlen($CheckoutDateM)==1 && is_numeric($CheckoutDateM)? "0".$CheckoutDateM : $CheckoutDateM);
$CheckoutDateTime .= "-".(strlen($CheckoutDateD)==1 && is_numeric($CheckoutDateD)? "0".$CheckoutDateD : $CheckoutDateD);
$CheckoutDateTime .= " ".(strlen($depart_time_hr)==1 && is_numeric($depart_time_hr)? "0".$depart_time_hr : $depart_time_hr);
$CheckoutDateTime .= ":".((strlen($depart_time_min)==1) && is_numeric($depart_time_min)? "0".$depart_time_min : $depart_time_min);
// ***

//do the access checks here
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])){
	if(isset($_POST["Update"])) {
		$_POST["family_name"] = preg_replace('/\s+/', ' ', trim($_POST["family_name"]));
                $_POST["first_name"] = preg_replace('/\s+/', ' ', trim($_POST["first_name"]));
		$searchff=$User_Info->doSearchSQL("$tmptb",
                        "(fullname='".addslashes($_POST["family_name"]." ".$_POST["first_name"])."' OR
                         fullname='".addslashes($_POST["first_name"]." ".$_POST["family_name"])."') AND
			 $tblist[0]!=$tmpcolname"
                        );

                if($searchrow=mysql_fetch_array($searchff)) {
                        print "A similar record of the Family Name : - <b>".$_POST["family_name"]."</b> , First Name : - <b>".$_POST["first_name"]. "</b> exists! Duplicated entry is not allowed.";
                        $post_error=1;
                } else if($_POST["family_name"]==""||$_POST["first_name"]==""||$_POST["player_type"]==""||$_POST["sex"]==""||$_POST["dietary"]==""||
			($_POST["country_name"]=="" && $_POST["country_oth"]=="")||
			 $_POST["room_short_form"]==""||$_POST["transport"]==""||$_POST["depart_port"]==""||
			 $_POST["arrival_port"]==""||(substr($_SESSION["sCONTROLLVL"],0,3)=="MGM"&&$_GET["tp"]!="gt"&&
			 ($_POST["2-div"]==""||eregi("[^0-9.]",$_POST["inithcp2"])||$_POST["inithcp2"]==""||
			 eregi("[^0-9.]",$_POST["adjhcp2"])||$_POST["adjhcp2"]==""||eregi("[^0-9.]",$_POST["2-par"])||
			 $_POST["2-par"]==""||$_POST["2-course_rating"]==""||(eregi("[^0-9.]",$_POST["2-result_sp"])||
			 $_POST["2-result_sp"]==""))) || ($_POST["transport2"]=="" && $_POST["transport"]!="NT") ||
			 ($_POST["transport2"]=="" && $_POST["transport"]!="NT") ||
			 (strlen($CheckinDateTime) == 16 &&  strlen($CheckoutDateTime) == 16 && 
			 (strcmp($CheckoutDateTime, $CheckinDateTime) == -1 || strcmp($CheckoutDateTime, $CheckinDateTime) == 0)) ){
			print "<table><tr><td width=25%>&nbsp;</td><td width=50%>";
			print "<br/><b><font color='red'>Previous data entry will not be saved due to missing information in the following fields:</b>";
			print "</font></td><td width=25%>&nbsp</td></tr>";
			print "<tr><td width=25%>&nbsp;</td><td width=50%>";
			print "<table width=100% border=0><tr><td width=20%>&nbsp;</td><td width=60%><font color='red'>";
			if($_POST["salutation"]=="")
				print "<br/>- Salutation";
			if($_POST["sex"]=="")
                                print "<br/>- Sex";
			if($_POST["family_name"]=="")
				print "<br/>- Family Name";
			if($_POST["first_name"]=="")
				print "<br/>- First Name";
			if($_POST["player_type"]=="")
				print "<br/>- Player Type";
			if($_POST["country_name"]=="" && $_POST["country_oth"]=="")
				print "<br/>- Country";
			if($_POST["dietary"]=="")
                                print "<br/>- Dietary";
			if($_POST["room_short_form"]=="")
				print "<br/>- Room Type";
			if($_POST["transport"]=="") {
				print "<br/>- Transport Type";
			} else {
				if($_POST["transport2"]=="" && $_POST["transport"]!="NT")
					print "<br/>- Transport Type > Transport Requirement";
			}
			
			if($_POST["arrival_port"]!="NA") {
				if($_POST["CheckinDateD"]=="00")
					print "<br/>- Arrival Day";
				if($_POST["CheckinDateM"]=="00")
					print "<br/>- Arrival Month";
				if($_POST["HotelCheckinDateD"]=="00")
                                        print "<br/>- Hotel Check-In Day";
                                if($_POST["HotelCheckinDateM"]=="00")
                                        print "<br/>- Hotel Check-In Month";
				if($_POST["arrival_time_hr"]=="X")
					print "<br/>- Arrival Hour";
				if($_POST["arrival_time_min"]=="X")
					print "<br/>- Arrival Minute";
				if($_POST["arrival_flight"]=="")
					print "<br/>- Arrival Flight";
				if($_POST["arrival_port"]=="")
					print "<br/>- Arrival Airport";
			} else {
				if($_POST["CheckinDateD"]=="00")
					print "<br/>- Arrival Day";
				if($_POST["CheckinDateM"]=="00")
					print "<br/>- Arrival Month";
				if($_POST["HotelCheckinDateD"]=="00")
                                        print "<br/>- Hotel Check-In Day";
                                if($_POST["HotelCheckinDateM"]=="00")
                                        print "<br/>- Hotel Check-In Month";
			}
			
			if($_POST["depart_port"]!="NA") {
				if($_POST["CheckoutDateD"]=="00")
					print "<br/>- Departure Day";
				if($_POST["CheckoutDateM"]=="00")
					print "<br/>- Departure Month";
				if($_POST["HotelCheckoutDateD"]=="00")
                                        print "<br/>- Hotel Check-Out Day";
                                if($_POST["HotelCheckoutDateM"]=="00")
                                        print "<br/>- Hotel Check-Out Month";
				if($_POST["depart_time_hr"]=="X")
					print "<br/>- Departure Hour";
				if($_POST["depart_time_min"]=="X")
					print "<br/>- Departure Minute";
				if($_POST["depart_flight"]=="")
					print "<br/>- Departure Flight";
				if($_POST["depart_port"]=="")
					print "<br/>- Departure Airport";
			} else {
				if($_POST["CheckoutDateD"]=="00")
					print "<br/>- Departure Day";
				if($_POST["CheckoutDateM"]=="00")
					print "<br/>- Departure Month";
				if($_POST["HotelCheckoutDateD"]=="00")
                                        print "<br/>- Hotel Check-Out Day";
                                if($_POST["HotelCheckoutDateM"]=="00")
                                        print "<br/>- Hotel Check-Out Month";
			}
			
			if (strlen($CheckinDateTime) == 16 &&  strlen($CheckoutDateTime) == 16 && (strcmp($CheckoutDateTime, $CheckinDateTime) == -1 || strcmp($CheckoutDateTime, $CheckinDateTime) == 0))
					print "<br/>- Departure date time is smaller or equal than the arrival date time";
			
			if($_GET["tp"]!="gt"){
				if($_POST["2-div"]=="")
					print "<br/>- Country Final Division";
					
				if(eregi("[^0-9.]",$_POST["inithcp2"])||$_POST["inithcp2"]=="")
					print "<br/>- Country Final Inital Handicap";
					
				if(eregi("[^0-9.]",$_POST["adjhcp2"])||$_POST["adjhcp2"]=="")
					print "<br/>- Country Final Adjusted Handicap";
					
				if(eregi("[^0-9.]",$_POST["2-par"])||$_POST["2-par"]=="")
					print "<br/>- Country Final Par";
					
				if($_POST["2-course_rating"]=="")
					print "<br/>- Country Final Course Rating";
					
				if(eregi("[^0-9.]",$_POST["2-result_sp"])||$_POST["2-result_sp"]=="")
					print "<br/>- Country Final Results";
			}
			
			print "</font></td><td width=20%>&nbsp</td></tr></table>";
			print "</td><td width=25%>&nbsp</td></tr></table>";
			print "<hr><br/>";
			$post_error=1;
		} else {
			if ($_POST['player_type'] == 'T') {
				$_POST['ck3'] = 1;
				$_POST['ck4'] = 1;
			}
			
			$tempsql="Update $tmptb set ";
			//"xxx 
			$tmptb=(isset($_GET["tbn"])?$_GET["tbn"]:"");
			
			while (list($key, $val) = each($tblist)) {
				if($val=="salutation") {
					if($_POST["salutation_oth"]=="")
						$tempsql=$tempsql."$val='".$_POST["salutation"]."', ";
					else
						$tempsql=$tempsql."$val='".addslashes($_POST["salutation_oth"])."', ";
				} else if($val=="country_name") {
					if($_POST["country_oth"]=="")
						$tempsql=$tempsql."$val='".$_POST["country_name"]."', ";
					else
						$tempsql=$tempsql."$val='".addslashes($_POST["country_oth"])."', ";
				} else if($val=="arrival_date") {
					if($_POST["CheckinDateM"]==0||$_POST["CheckinDateD"]==0)
						$tempsql=$tempsql."$val=\"0000-00-00\" ,";
					else
						$tempsql=$tempsql."$val='".$_POST["CheckinDateY"]."-".$_POST["CheckinDateM"]."-".$_POST["CheckinDateD"]."', ";
				} else if($val=="depart_date") {
					if($_POST["CheckoutDateM"]==0||$_POST["CheckoutDateD"]==0)
						$tempsql=$tempsql."$val=\"0000-00-00\" ,";
					else
						$tempsql=$tempsql."$val='".$_POST["CheckoutDateY"]."-".$_POST["CheckoutDateM"]."-".$_POST["CheckoutDateD"]."', ";
				} else if($val=="hotelcheckindate") {
                                                if($_POST["HotelCheckinDateM"]=="0"||$_POST["HotelCheckinDateD"]=="0")
                                                        $tempsql=$tempsql."$val=\"0000-00-00\" ,";
                                                else
                                                        $tempsql=$tempsql."$val='".$_POST["HotelCheckinDateY"]."-".$_POST["HotelCheckinDateM"]."-".$_POST["HotelCheckinDateD"]."',";
                                } else if($val=="hotelcheckoutdate") {
                                                if($_POST["HotelCheckoutDateM"]=="0"||$_POST["HotelCheckoutDateD"]=="0")
                                                        $tempsql=$tempsql."$val=\"0000-00-00\" ,";
                                                else
                                                        $tempsql=$tempsql."$val='".$_POST["HotelCheckoutDateY"]."-".$_POST["HotelCheckoutDateM"]."-".$_POST["HotelCheckoutDateD"]."',";
                                } else if($val=="fullname") {
                                                $fullname = $_POST['family_name'] . " " . $_POST['first_name'];
                                                $tempsql=$tempsql."$val='".addslashes($fullname)."',";
				} else if($val=="arrival_time") {
					$tempsql=$tempsql."$val='".$_POST["arrival_time_hr"].":".$_POST["arrival_time_min"]."',";
				} else if($val=="depart_time") {
					$tempsql=$tempsql."$val='".$_POST["depart_time_hr"].":".$_POST["depart_time_min"]."',";
				} else if($val=="pickuptime") {
					$tempsql=$tempsql."$val='".$_POST["depart_pickuptime_hr"].":".$_POST["depart_pickuptime_min"]."',";
				/*} else if($val=="transport") {
					if($_POST["transport"]=="NT")
						$tempsql=$tempsql."$val='".$_POST["transport"].":~~::~:".$_POST["transport_no"]."',";
					else
						$tempsql=$tempsql."$val='".$_POST["transport"].":~~:".$_POST["transport2"].":~:".$_POST["transport_no"]."',";	
				*/
				} else if($val=="blacklist") {
					if ($_POST['blacklist']) $tempsql=$tempsql."$val='".addslashes($_POST['blacklist'])."', ";
					else $tempsql=$tempsql."$val='', ";
				/*} else if($val=="arrival_flight") {
					$tempsql=$tempsql."$val='".addslashes(strtoupper(substr($_POST["arrival_flight"],0,6))).":~:".$_POST["arrival_port"]."', ";
				} else if($val=="depart_flight") {
					$tempsql=$tempsql."$val='".addslashes(strtoupper(substr($_POST["depart_flight"],0,6))).":~:".$_POST["depart_port"]."', ";
				*/
				} else if($val=="day_stay") {
						if($_POST["HotelCheckoutDateM"]==0||$_POST["HotelCheckoutDateD"]==0||$_POST["HotelCheckinDateM"]==0||$_POST["HotelCheckinDateD"]==0) {
							$tempsql=$tempsql."$val='0',";
						} else {
							$temparrival=strtotime($_POST["HotelCheckinDateY"]."-".$_POST["HotelCheckinDateM"]."-".$_POST["HotelCheckinDateD"]);
							$tempdepart=strtotime($_POST["HotelCheckoutDateY"]."-".$_POST["HotelCheckoutDateM"]."-".$_POST["HotelCheckoutDateD"]);
							$diffday=(($tempdepart-$temparrival)/86400);
							$tempsql=$tempsql."$val='$diffday',";
						}
				} else {
					$tempstring=$_POST["$val"];
					$tempsql=$tempsql."$val='".addslashes($tempstring)."', ";
				}
			}
			
			$tempstrlen=strlen($tempsql)-2;
			$tempsql=substr($tempsql,0,$tempstrlen);
			$tempsql=$tempsql." where $tblist[0]=$tmpcolname;";		
			$Error_Handler->print_error($tempsql);
//echo $tempsql;	
			//temp disable need to restructure to search for exist then update else insert.
			$updarow=$User_Info->doUpdateSQL($tempsql);
			if (!$updarow) {
				echo "<span style=color:red;>Error update, please contact administrator!<br>$tempsql</span>" ;
				$post_error=1;
			}
			
			if($_GET["tp"]!="gt") {
				if(isset($_POST["handi1"]))
					$div1sql= "insert into `cust_handi_tb` set `cust_id`='$tmpcolname', `ref_handi_id`='1', `cust_handi_div`=\"".strtoupper($_POST["1-div"])."\" , `cust_initial_hcp`='".$_POST["inithcp1"]."', `cust_adj_hcp`='".$_POST["adjhcp1"]."' , `cust_par`='".$_POST["1-par"]."' , `cust_course_rating`=\"".$_POST["1-course_rating"]."\" , `cust_result`='".$_POST["1-result_sp"]."';";
				else
					$div1sql= "update `cust_handi_tb` set `cust_handi_div`=\"".strtoupper($_POST["1-div"])."\" , `cust_initial_hcp`='".$_POST["inithcp1"]."', `cust_adj_hcp`='".$_POST["adjhcp1"]."' , `cust_par`='".$_POST["1-par"]."' , `cust_course_rating`=\"".$_POST["1-course_rating"]."\" , `cust_result`='".$_POST["1-result_sp"]."' where `cust_id`='$tmpcolname' and `ref_handi_id`='1';";
					
				$updarow=$updarow+($User_Info->doUpdateSQL($div1sql));
				
				if(isset($_POST["handi2"]))
					$div2sql= "insert into `cust_handi_tb` set `cust_id`='$tmpcolname', `ref_handi_id`='2', `cust_handi_div`=\"".strtoupper(addslashes($_POST["2-div"]))."\" , `cust_initial_hcp`='".$_POST["inithcp2"]."', `cust_adj_hcp`='".$_POST["adjhcp2"]."' , `cust_par`='".$_POST["2-par"]."' , `cust_course_rating`=\"".$_POST["2-course_rating"]."\" , `cust_result`='".$_POST["2-result_sp"]."';";
				else
					$div2sql= "update `cust_handi_tb` set `cust_handi_div`=\"".strtoupper(addslashes($_POST["2-div"]))."\" , `cust_initial_hcp`='".$_POST["inithcp2"]."', `cust_adj_hcp`='".$_POST["adjhcp2"]."' , `cust_par`='".$_POST["2-par"]."' , `cust_course_rating`=\"".$_POST["2-course_rating"]."\" , `cust_result`='".$_POST["2-result_sp"]."' where `cust_id`='$tmpcolname' and `ref_handi_id`='2';";
				
				$updarow=$updarow+($User_Info->doUpdateSQL($div2sql));
			}
			
			if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP") {
				if(isset($_POST["handi3"]))
					$div3sql= "insert into `cust_handi_tb` set `cust_id`='$tmpcolname', `ref_handi_id`='3', `cust_handi_div`=\"".$AustGolf_Addon->division_name($_POST["adjhcp3"])."\" , `cust_initial_hcp`='".$_POST["inithcp3"]."', `cust_adj_hcp`='".$_POST["adjhcp3"]."' , `cust_par`='".$_POST["3-par"]."' , `cust_course_rating`=\"".$_POST["3-course_rating"]."\" , `cust_result`='".$_POST["3-result_sp"]."';";
				else
					$div3sql= "update `cust_handi_tb` set `cust_handi_div`=\"".$AustGolf_Addon->division_name($_POST["adjhcp3"])."\" , `cust_initial_hcp`='".$_POST["inithcp3"]."', `cust_adj_hcp`='".$_POST["adjhcp3"]."' , `cust_par`='".$_POST["3-par"]."' , `cust_course_rating`=\"".$_POST["3-course_rating"]."\" , `cust_result`='".$_POST["3-result_sp"]."' where `cust_id`='$tmpcolname' and `ref_handi_id`='3';";
			
				$updarow=$updarow+($User_Info->doUpdateSQL($div3sql));
				if(isset($_POST["handi4"]))
					$div4sql= "insert into `cust_handi_tb` set `cust_id`='$tmpcolname', `ref_handi_id`='4', `cust_handi_div`=\"".$AustGolf_Addon->division_name($_POST["adjhcp4"])."\" , `cust_initial_hcp`='".$_POST["inithcp4"]."', `cust_adj_hcp`='".$_POST["adjhcp4"]."' , `cust_par`='".$_POST["4-par"]."' , `cust_course_rating`=\"".$_POST["4-course_rating"]."\" , `cust_result`='".$_POST["4-result_sp"]."';";
				else
					$div4sql= "update `cust_handi_tb` set `cust_handi_div`=\"".$AustGolf_Addon->division_name($_POST["adjhcp4"])."\" , `cust_initial_hcp`='".$_POST["inithcp4"]."', `cust_adj_hcp`='".$_POST["adjhcp4"]."' , `cust_par`='".$_POST["4-par"]."' , `cust_course_rating`=\"".$_POST["4-course_rating"]."\" , `cust_result`='".$_POST["4-result_sp"]."' where `cust_id`='$tmpcolname' and `ref_handi_id`='4';";
				$updarow=$updarow+($User_Info->doUpdateSQL($div4sql));
				if(isset($_POST["handi5"]))
					$div5sql= "insert into `cust_handi_tb` set `cust_id`='$tmpcolname', `ref_handi_id`='5', `cust_handi_div`=\"".$AustGolf_Addon->division_name($_POST["adjhcp5"])."\" , `cust_initial_hcp`='".$_POST["inithcp5"]."', `cust_adj_hcp`='".$_POST["adjhcp5"]."' , `cust_par`='".$_POST["5-par"]."' , `cust_course_rating`=\"".$_POST["5-course_rating"]."\" , `cust_result`='".$_POST["5-result_sp"]."';";
				else
					$div5sql= "update `cust_handi_tb` set `cust_handi_div`=\"".$AustGolf_Addon->division_name($_POST["adjhcp5"])."\" , `cust_initial_hcp`='".$_POST["inithcp5"]."', `cust_adj_hcp`='".$_POST["adjhcp5"]."' , `cust_par`='".$_POST["5-par"]."' , `cust_course_rating`=\"".$_POST["5-course_rating"]."\" , `cust_result`='".$_POST["5-result_sp"]."' where `cust_id`='$tmpcolname' and `ref_handi_id`='5';";
				$updarow=$updarow+($User_Info->doUpdateSQL($div5sql));
			}

			if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP"||$_GET["tp"]=="gt") {
				$act_q="select act_ref_id, act_name, act_description,act_default,act_date from ref_act_tb;";
				$act_result = $User_Info->doSQL($act_q);
				while($rm_rowx=mysql_fetch_array($act_result)) {
					$db_act_name=$rm_rowx["act_name"];
					
					$db_act_ref_id=$rm_rowx["act_ref_id"];
					
					$act_in_q="select * from cust_act_tb where cust_id=$tmpcolname and act_ref_id=$db_act_ref_id;";
					
					$act_in_result = $User_Info->doSQL($act_in_q);
					if($act_row=mysql_fetch_array($act_in_result)) {
						if(isset($_POST["ck$db_act_ref_id"]) || $db_act_ref_id==15)
							$actsql= "update cust_act_tb set cust_act_no=".(!isset($_POST["ck$db_act_ref_id"])?"0":($_POST["txt$db_act_ref_id"]+1))." ,cust_addon=\"".$_POST["{$db_act_ref_id}-addon"]."\" where cust_id=".$tmpcolname." and act_ref_id=".$db_act_ref_id.";";
						else
							$actsql= "update cust_act_tb set cust_act_no=0 ,cust_addon=\"\" where cust_id=".$tmpcolname." and act_ref_id=".$db_act_ref_id.";";
					} else {
						if(isset($_POST["ck$db_act_ref_id"]) || $db_act_ref_id==15)
							$actsql= "insert into cust_act_tb values (NULL,$db_act_ref_id,".(!isset($_POST["ck$db_act_ref_id"])?"0":($_POST["txt$db_act_ref_id"]+1)).",$tmpcolname,\"".$_POST["{$db_act_ref_id}-addon"]."\");";
						else
							$actsql= "insert into cust_act_tb values (NULL,$db_act_ref_id,0,$tmpcolname,\"\");";
					}
					
					$updarow=$updarow+($User_Info->doUpdateSQL($actsql));
				}
			}
			
			$cust_add_sql="";
			$cust_add_flag=0;
			if(isset($_POST["cust_coy"])) {
				$cust_add_sql=$cust_add_sql."cust_coy='".addslashes($_POST["cust_coy"])."',";
				$cust_add_flag=1;
			}
			
			if(isset($_POST["cust_dest"])) {
				$cust_add_sql=$cust_add_sql."cust_dest='".addslashes($_POST["cust_dest"])."',";
				$cust_add_flag=1;
			}
			
			if(isset($_POST["cust_tel"])) {
				$cust_add_sql=$cust_add_sql."cust_tel='".addslashes($_POST["cust_tel"])."',";
				$cust_add_flag=1;
			}
			
			if(isset($_POST["cust_fax"])) {
				$cust_add_sql=$cust_add_sql."cust_fax='".addslashes($_POST["cust_fax"])."',";
				$cust_add_flag=1;
			}
			
			if(isset($_POST["cust_email"])) {
				$cust_add_sql=$cust_add_sql."cust_email='".addslashes($_POST["cust_email"])."',";
				$cust_add_flag=1;
			}
			if(isset($_POST["cust_club"])) {
				$cust_add_sql=$cust_add_sql."cust_club='".str_replace("'", "\'", $_POST["cust_club"])."',";
				$cust_add_flag=1;
			}
			
			if($cust_add_flag==1) {
				$cust_add_sql=substr($cust_add_sql,0,-1);
				$cust_add_sql= "update cust_add_tb set ".$cust_add_sql." where cust_id=".$tmpcolname.";";
				$updarow=$updarow+($User_Info->doUpdateSQL($cust_add_sql));
			}
			
			
			if($updarow) {
				$AustGolf_Addon->action_log($tmpcolname,"edit");
				
				// send alert email to admins -- marc 20070521
				$AustGolf_Addon->alert_email(date('Y-m-d g:i A'), $_POST['family_name'] . " " . $_POST['first_name'], $_POST['country_name']. $_POST['country_oth'], $_SESSION['sNick'], "Participant data edited");
				
				print "<span class=\"ftitle3\"><p align=\"center\">Record has been updated</span><br>";
			} else
				print "Nothing updated!";
				
			print "<p align=\"center\"><input type=button value=\"Back to Home Page\" onclick=\"window.location='index.php?s=admin'\">";
			if (substr($_SESSION["sCONTROLLVL"],0,3)=="GRP") 
				print "<br/><p align=\"center\"><input type=button value=\"Back to Previous Filter\" onclick=\"window.location='".$_SESSION["returnURL"]."'\">";
		}
	} else if(isset($_POST["Change"])) {
		include_once("grp_name.php");
?>
		<div align="center" class="fname1">
<?
		if($_POST["group"]=="")
			print "You did not choose any group! No changes made.";
		else {
			print "You have choosen <b>".$grp_name[$_POST["group"]]."</b> to be the new group!<br/>";
			$grpupsql= "update cust_holder set grp_id=\"".$_POST["group"]."\" where user_holder=".$_POST["holder"].";";
			$updarow=$User_Info->doUpdateSQL($grpupsql);
		}
	} else
		$post_error=1;
	
	if($post_error==1) {
?>
<br />
<?php 
	$tmpcol=$tblist[$tmpcolnum];
	$resultx= $User_Info->dosearchSQL("$tmptb",ctype_alnum($tmpcolname)?"$tmpcol='$tmpcolname'":"$tmpcol=$tmpcolname","","");
	print "<br />";

	if ($qrow=mysql_fetch_array($resultx)) {
		$cust_add_result= $User_Info->dosearchSQL("cust_add_tb",("cust_id=".$qrow["cust_id"]),"","");
		if($cust_add_row=mysql_fetch_array($cust_add_result))
			print "";
		
		$oth=0;
?>
<FORM name=formupdate action=<?=$_SERVER["REQUEST_URI"];?> method=POST>
            <TABLE cellSpacing=0 cellPadding=2 width="100%" border=1>
            <input type=hidden name=cust_id value="<?=$qrow["cust_id"];?>">
              <TBODY>
              <tr><td class="ftitle1" align="center" colspan=4>
<?
		if($_GET["tp"]=="gt")
			print "<span style=\"font-size:14px\" >Register Guest Player</span>";
		else if(substr($_SESSION["sCONTROLLVL"],0,3)=="MGM")
			print "<span style=\"font-size:14px\">Register Tournament Player</span>";
		else if (substr($_SESSION["sCONTROLLVL"],0,3)=="GRP")
			print "<span style=\"font-size:14px\">Edit Full Player Details</span>";
?>
              </td><td class="ftitle1"><span style="font-size:10px"><img src="images/tick.gif">indicates a mandatory field</tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Sal</td>
                <td class="fname1" vAlign="top" align="left" width="30%"><span class="fvalue">
                  <select name=salutation class="fname1norm" id="salutation">
					<!-- <option value="" selected>--- Select One ---</option> -->
					<option value="Tan Sri" <?=$qrow["salutation"]=="Tan Sri"?"selected":" "?> <?=$qrow["salutation"]=="Tan Sri"?$oth=1:""?>>Tan Sri</option>
					<option value="Tan Seri" <?=$qrow["salutation"]=="Tan Seri"?"selected":" "?> <?=$qrow["salutation"]=="Tan Seri"?$oth=1:""?>>Tan Seri</option>
					<option value=Datuk <?=$qrow["salutation"]=="Datuk"?"selected":" "?> <?=$qrow["salutation"]=="Datuk"?$oth=1:""?>>Datuk</option>
					<option value=Datin <?=$qrow["salutation"]=="Datin"?"selected":" "?> <?=$qrow["salutation"]=="Datin"?$oth=1:""?>>Datin</option>
					<option value=Dr <?=$qrow["salutation"]=="Dr"?"selected":" "?> <?=$qrow["salutation"]=="Dr"?$oth=1:""?>>Dr</option>
					<option value=Madam <?=$qrow["salutation"]=="Madam"?"selected":" "?> <?=$qrow["salutation"]=="Madam"?$oth=1:""?>>Madam</option>
					<option value=Mr <?=$qrow["salutation"]=="Mr"?"selected":" "?> <?=$qrow["salutation"]=="Mr"?$oth=1:""?>>Mr</option>
					<option value=Mrs <?=$qrow["salutation"]=="Mrs"?"selected":" "?> <?=$qrow["salutation"]=="Mrs"?$oth=1:""?>>Mrs</option>
					<option value=Ms <?=$qrow["salutation"]=="Ms"?"selected":" "?> <?=$qrow["salutation"]=="Ms"?$oth=1:""?>>Ms</option>
					<option value=Master <?=$qrow["salutation"]=="Master"?"selected":" "?> <?=$qrow["salutation"]=="Master"?$oth=1:""?>>Master</option>
					<option value=Prof <?=$qrow["salutation"]=="Prof"?"selected":" "?> <?=$qrow["salutation"]=="Prof"?$oth=1:""?>>Prof</option>
					<option value=Mdm <?=$qrow["salutation"]=="Mdm"?"selected":" "?> <?=$qrow["salutation"]=="Mdm"?$oth=1:""?>>Mdm</option>
					<option value=Messrs <?=$qrow["salutation"]=="Messrs"?"selected":" "?> <?=$qrow["salutation"]=="Messrs"?$oth=1:""?>>Messrs</option>
					<option value=Messer <?=$qrow["salutation"]=="Messer"?"selected":" "?> <?=$qrow["salutation"]=="Messer"?$oth=1:""?>>Messer</option>
					<option value=Professor <?=$qrow["salutation"]=="Professor"?"selected":" "?> <?=$qrow["salutation"]=="Professor"?$oth=1:""?>>Professor</option>
                  </select><input size=6 name=salutation_oth value="<?=$oth==1?"":str_replace('"', '&quot;', $qrow["salutation"])?>">
                </span></td>
		<td class="ftitle1" vAlign="top" align="right" width="10%">Sex</td>
                <td class="fname1" vAlign="top" align="left" width="30%"><span class="fvalue">
                                <select name="sex" class="fname1norm" id="sex">
                                <option value="">--- Select One ---</option>
                                <option value="M"<?=$qrow["sex"]=="M"?" selected":""?>>Male</option>
                                <option value="F"<?=$qrow["sex"]=="F"?" selected":""?>>Female</option>
                                </select></span></td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td>
			</tr>
			<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Family and First Name</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=family_name value="<?=str_replace('"', '&quot;', $qrow["family_name"]);?>">
                <input size="40" name=first_name value="<?=str_replace('"', '&quot;', $qrow["first_name"]);?>"></td>
                <td class="ftitle1" vAlign="top" align="left" width="10%" >&nbsp;<img src="images/tick.gif"></td>
			</tr>
              
<?
		if($_GET["tp"]=="gt" ||substr($_SESSION["sCONTROLLVL"],0,3)=="GRP") {
?>
			<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Company</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size=40 name=cust_coy value="<?=str_replace('"', '&quot;', $cust_add_row["cust_coy"]);?>"><br>&nbsp;Please enter in proper title casing, for example, "DaimlerChrysler South East Asia Pte Ltd".</td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
			<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Designation</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=cust_dest value="<?=str_replace('"', '&quot;', $cust_add_row["cust_dest"]);?>"></td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
			<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Contact Tel</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=cust_tel value="<?=str_replace('"', '&quot;', $cust_add_row["cust_tel"]);?>">&nbsp;Please include country and area codes in this format: (country_code) area_code tel_number</td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
			<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Contact Fax</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=cust_fax value="<?=str_replace('"', '&quot;', $cust_add_row["cust_fax"]);?>">&nbsp;Please include country and area codes in this format: (country_code) area_code tel_number</td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
			<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Email Address</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=cust_email value="<?=str_replace('"', '&quot;', $cust_add_row["cust_email"]);?>"></td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
<?
		}
?>
			<tr>
				<td class="ftitle1" vAlign="top" align="right" width="20%">Player</td>
				<td class="fname1" vAlign="top" align="left"><span class="fvalue">
					<select name=player_type class="fname1norm" id="player_type" onchange="">
					<option value="">--- Select One ---</option>
<?
       	if($_GET["tp"]=="gt"){
?>
					<option value="G" <?=$qrow["player_type"]=="G"?"selected":" "?>>Guest Player</option>
                    <option value="CC" <?=$qrow["player_type"]=="CC"?"selected":" "?>>Country Captain</option>
                    <option value="O" <?=$qrow["player_type"]=="O"?"selected":" "?>>Others</option>
<?
		} else {
?>
					<option value="T" <?=$qrow["player_type"]=="T"?"selected":" "?>>Tournament Player</option>
<?
		}
		
        if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP") {
?>
                    <option value="CC" <?=$qrow["player_type"]=="CC"?"selected":" "?>>Country Captain</option>
                    <option value="G" <?=$qrow["player_type"]=="G"?"selected":" "?>>Guest Player</option>
                    <!--<option value="OL" <?=$qrow["player_type"]=="OL"?"selected":" "?>>Opinion Leader</option>-->
                    <option value="GPRO" <?=$qrow["player_type"]=="GPRO"?"selected":" "?>>Golf Pro</option>
                    <option value="GCOM" <?=$qrow["player_type"]=="GCOM"?"selected":" "?>>Golf Committee</option>
                    <option value="O" <?=$qrow["player_type"]=="O"?"selected":" "?>>Others</option>
<?
		}
?>
				</select>
                </span></td>
		<td class="ftitle1" vAlign="top" align="right">Dietary</td>
                <td class="fname1" vAlign="top" align="left"><span class="fvalue">
                        <input size=40 name=dietary value="<?=(isset($qrow["dietary"])?str_replace('"','&quot;',$qrow["dietary"]):"");?>">
                        </span></td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td></tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Country</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><span class="fvalue">
                  <?
                	$AustGolf_Addon->generate_country_list($qrow["country_name"],(isset($_POST["country_oth"])?"":$_POST["country_oth"]));
                  ?></span></td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td></tr>
                <tr>
                <td class="ftitle4" vAlign="top" align="right" width="20%" colspan="5">&nbsp;</td>
                </tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Arrival Date</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><SPAN class=fcomments></SPAN>
				<select name=CheckinDateD>
					<? $AustGolf_Addon->generate_day(($qrow["arrival_date"]!="0000-00-00")?strftime("%d", strtotime($qrow["arrival_date"])):""); ?>
				</select>/
				<select name=CheckinDateM>
					<? $AustGolf_Addon->generate_month(($qrow["arrival_date"]!="0000-00-00")?strftime("%m", strtotime($qrow["arrival_date"])):""); ?>
				</select>/ 
                  <!-- Modified by William on April 20, 2006. Display year using SESSION -->
                  <input type="hidden" name=CheckinDateY value="<?=$_SESSION["sThisYear"];?>"> <?=$_SESSION["sThisYear"];?> <b>Time :</b>
                    <select name=arrival_time_hr>
						<? $AustGolf_Addon->generate_hour(strftime("%H", strtotime($qrow["arrival_time"]))); ?>
					</select> : 
					<select name=arrival_time_min>
						<? $AustGolf_Addon->generate_min(strftime("%M", strtotime($qrow["arrival_time"]))); ?>
					</select> (24 hour format)
                  <b>&nbsp; Flight : </b>
                  <input size="20" maxlength="6" name=arrival_flight 
<?
		//$tempaf=explode(":~:",$qrow["arrival_flight"]);
		print "value=\"".str_replace('"', '&quot;', $qrow["arrival_flight"])."\" >";
		//if(!isset($tempaf[1]))
		//	$tempaf[1]="";
?>            
            <select name="arrival_port">
				<!-- <option value=""  selected>Select Airport</option> -->
				<option value="BI" <?=$qrow["arrival_port"]=="BI"?"selected":" "?>>Brisbane International</option>
				<option value="DA" <?=$qrow["arrival_port"]=="DA"?"selected":" "?>>Brisbane Domestic</option>
				<option value="NA" <?=$qrow["arrival_port"]=="NA"?"selected":" "?>>Not Applicable</option>
			</select>
				</td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td>
			</tr>
			<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Departure Date</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
					<select name=CheckoutDateD>
						<? $AustGolf_Addon->generate_day(($qrow["depart_date"]!="0000-00-00")?strftime("%d", strtotime($qrow["depart_date"])):""); ?>
					</select>/
					<select name=CheckoutDateM>
						<? $AustGolf_Addon->generate_month(($qrow["depart_date"]!="0000-00-00")?strftime("%m", strtotime($qrow["depart_date"])):""); ?>
					</select>/ 
                  <!-- Modified by William on April 20, 2006. Display year using SESSION -->
					<input type="hidden" name=CheckoutDateY value="<?=$_SESSION["sThisYear"];?>"> <?=$_SESSION["sThisYear"];?>
                    <b>Time : </b>
					<select name=depart_time_hr>
						<? $AustGolf_Addon->generate_hour(strftime("%H", strtotime($qrow["depart_time"]))); ?>
					</select> : 
					<select name=depart_time_min>
						<? $AustGolf_Addon->generate_min(strftime("%M", strtotime($qrow["depart_time"]))); ?>
					</select> (24 hour format)
                  &nbsp;
                  <b>Flight : </b>
                  <input size="20" maxlength="6" name=depart_flight 
<?
		//$tempdf=explode(":~:",$qrow["depart_flight"]);
		print "value=\"".str_replace('"', '&quot;', $qrow["depart_flight"])."\" >";
		//if(!isset($tempdf[1]))
		//	$tempdf[1]="";
?>            
            <select name="depart_port">
				<!-- <option value="" selected>Select Airport</option> -->
				<option value="BI" <?=$qrow["depart_port"]=="BI"?"selected":" "?>>Brisbane International</option>
				<option value="DA" <?=$qrow["depart_port"]=="DA"?"selected":" "?>>Brisbane Domestic</option>
				<option value="NA" <?=$qrow["depart_port"]=="NA"?"selected":" "?>>Not Applicable</option>
			</select>

		<? if (substr($_SESSION["sCONTROLLVL"],0,3)=="GRP") { ?>
			&nbsp;&nbsp;<b>Pick-Up Time : </b>
                                        <select name=depart_pickuptime_hr>
                                                <? $AustGolf_Addon->generate_hour(strftime("%H", strtotime($qrow["pickuptime"]))); ?>
                                        </select> :
                                        <select name=depart_pickuptime_min>
                                                <? $AustGolf_Addon->generate_min(strftime("%M", strtotime($qrow["pickuptime"]))); ?>
                                        </select> 
		<? } ?>
                  </td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td></tr>
              
<?
		//if (substr($_SESSION["sCONTROLLVL"],0,3)=="GRP") {
?>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Hotel Stay</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><table cellspacing=0 cellpadding=0 border=0><tr valign=top>
		<td rowspan=2><input size=1 name=day_stay value='<?=$qrow["day_stay"]?>' readonly><?/*<input name="day_generate" type="checkbox" value="1" checked> Auto Generate*/?></td>
		<td width=302 rowspan=2><br></td>
		<td><b>Hotel Check-In Date : </b></td>
                        <td><select name=HotelCheckinDateD>
				<? $AustGolf_Addon->generate_day(($qrow["hotelcheckindate"]!="0000-00-00")?strftime("%d", strtotime($qrow["hotelcheckindate"])):""); ?>
                                </select>/
                                <select name=HotelCheckinDateM>
				<? $AustGolf_Addon->generate_month(($qrow["hotelcheckindate"]!="0000-00-00")?strftime("%m", strtotime($qrow["hotelcheckindate"])):""); ?>
                                </select>/
                                <input type="hidden" name=HotelCheckinDateY value="<?=$_SESSION['sThisYear'];?>"> <?=$_SESSION['sThisYear'];?>
                        </td></tr>
                        <tr>
                        <td><b>Hotel Check-Out Date : </b>&nbsp;</td>
                        <td><select name=HotelCheckoutDateD>
				<? $AustGolf_Addon->generate_day(($qrow["hotelcheckoutdate"]!="0000-00-00")?strftime("%d", strtotime($qrow["hotelcheckoutdate"])):""); ?>
                                </select>/
                                <select name=HotelCheckoutDateM>
				<? $AustGolf_Addon->generate_month(($qrow["hotelcheckoutdate"]!="0000-00-00")?strftime("%m", strtotime($qrow["hotelcheckoutdate"])):""); ?>
                                </select>/
                                <input type="hidden" name=HotelCheckoutDateY value="<?=$_SESSION['sThisYear'];?>"> <?=$_SESSION['sThisYear'];?>
                        </td>
                        </tr></table>
			</td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;   </td></tr>
<?
		//}
?>
				<script type="text/javascript">
					function autoDoPostBack(e)
					{
						e.form.transport2.style.visibility = (e.value == "SB" || e.value == "HL" ) ? "visible" : "hidden";
					}
					
					function doPostBack(e)
					{
						e.form.transport2.style.visibility = (e.value == "SB" || e.value == "HL" ) ? "visible" : "hidden";
						
						/*if (e.form.transport_no.value != "")
							if (!confirm("Do you want to keep the transport type comment?\n ["+e.form.transport_no.value+"]"))
								e.form.transport_no.value = "";
*/
					}
					
					function doTransportRequirement(e)
					{
						/*if (e.form.transport_no.value != "")
							if (!confirm("Do you want to keep the transport type comment?\n ["+e.form.transport_no.value+"]"))
								e.form.transport_no.value = "";
*/
					}
				</script>				           
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Room Type</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
					<? $AustGolf_Addon->generate_room_list($qrow["room_short_form"]); ?>
				</td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td></tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Transport Type</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
<?
/*
		$temptp=explode(":~:",$qrow["transport"]);
		$temptp1=explode(":~~:",$qrow["transport"]);
		$temptp[0] = substr ( $temptp[0], 0, 2 );
		$temptp1[1] = substr ( $temptp1[1], 0, 1 );
*/
		$AustGolf_Addon->generate_tranport_list($qrow["transport"]);
		$AustGolf_Addon->generate_tranport2_list($qrow["transport2"]);
?> 
                <? /* <br>&nbsp;<input size="20" name=transport_no value="<?=isset($temptp[1])?$temptp[1]:""?>"> */ ?>
                <br/><b>Transport Service will be made available for <u>group pick-up</u> only. Should individuals be arriving separately, please make your own transportation arrangement to and/or from the airport.
                Hotel Limo requested is chargeable.</b>
                </td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td></tr>
				<script type="text/javascript">
					autoDoPostBack(document.all.transport)
				</script> 
<?
		if ($_SESSION["sCONTROLLVL"] == 'GRP02') {
?>
				<tr>
                   <td class="ftitle1" valign="top" align="right">Blacklist</td>
                   <td class="fname1" valign="top" align="left" colspan="3">
                       <input type="radio" name="blacklist" value="" <? if($qrow["blacklist"]=="") echo "checked" ?>> Off
                       <input type="radio" name="blacklist" value="Asian Final" <? if($qrow["blacklist"]=="Asian Final") echo "checked" ?>> Asian Final
                       <input type="radio" name="blacklist" value="World Final" <? if($qrow["blacklist"]=="World Final") echo "checked" ?>> World Final
                   </td>
                   <td class="ftitle1" valign="top" align="right" width="10%">&nbsp;</td>
                 </tr>
<?
		}
		if ($_GET["tp"]!="gt") {
?>   			
    			<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Home Club</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
                <input size="60" name="cust_club" value="<?=str_replace('"', '&quot;', $cust_add_row["cust_club"]);?>" >
                </td>
    			<td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td></tr>
<?
		}
		if ($_GET["tp"]!="gt") {
			$ref_handi_result=$User_Info->doSearchSQL("ref_handi_tb","","","inf");
?>
			<tr>
                <td class="ftitle4" vAlign="top" align="center" colspan="5">Personal Golf Record</td>
              </tr>
              <tr><td class="ftitle1" vAlign="top" align="right" width="20%">&nbsp;</td>
              <td colspan="3">
              <table cellSpacing=0 cellPadding=2 width="100%" border=1>
                <td class="ftitle1" width=16%>&nbsp;</td>
                <td class="ftitle1" vAlign="top" align="center" width="14%">Division</td>
                <td class="ftitle1" vAlign="top" align="center" width="14%">Initial Handicap</td>
                <td class="ftitle1" vAlign="top" align="center" width="14%">Par</td>
                <td class="ftitle1" vAlign="top" align="center" width="14%">Course Rating</td>
                <td class="ftitle1" vAlign="top" align="center" width="14%">Result (Stableford Points)</td>
                <td class="ftitle1" vAlign="top" align="center" width="14%">Adjusted Handicap</td>
                <td class="ftitle1" vAlign="top" align="center" width="14%">&nbsp;</td>
                </tr>
<?
			while($ref_handi_row=mysql_fetch_array($ref_handi_result)) {
				$db_ref_id=$ref_handi_row["handi_ref_id"];
				$db_ref_name=$ref_handi_row["handi_name"];
				
				if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP"||(substr($_SESSION["sCONTROLLVL"],0,3)=="MGM"&&($db_ref_id==1||$db_ref_id==2))) {
?>
			<tr>
                <td class="ftitle4" vAlign="top" align="center"><?=$db_ref_name?></td>
  <?
	              	// Qualifying Round use 1
	              	$db1_cust_handi_div="";
					$db1_cust_initial_hcp ="0";
					$db1_cust_adj_hcp ="0";
					$db1_cust_par ="0";
					$db1_cust_course_rating ="0";
					$db1_cust_result ="0";
	              	$handi_result=$User_Info->doSearchSQL("cust_handi_tb",("cust_id=".$qrow["cust_id"]." and ref_handi_id='$db_ref_id'"),"","inf");
					if($rm_row=mysql_fetch_array($handi_result)) {
						$db1_cust_handi_div=$rm_row["cust_handi_div"];
						$db1_cust_initial_hcp =$rm_row["cust_initial_hcp"];
						$db1_cust_adj_hcp =$rm_row["cust_adj_hcp"];
						$db1_cust_par =$rm_row["cust_par"];
						$db1_cust_course_rating =$rm_row["cust_course_rating"];
						$db1_cust_result =$rm_row["cust_result"];
					} else {
						$db1_cust_handi_div="-";
						$db1_cust_initial_hcp ="";
						$db1_cust_adj_hcp ="";
						$db1_cust_par ="";
						$db1_cust_course_rating ="";
						$db1_cust_result ="";
						echo "<input type=hidden name=handi".$db_ref_id." value=1>";
					}
?>
                 <td class="fname1" vAlign="top" align="left"><input name=<?=$db_ref_id?>-div  size="10" value="<?=str_replace('"', '&quot;', $db1_cust_handi_div);?>"></td>	
              	 <td class="fname1" vAlign="top" align="left"><input name=inithcp<?=$db_ref_id?>  size="10" value="<?=str_replace('"', '&quot;', $db1_cust_initial_hcp);?>"></td>	
              	 <td class="fname1" vAlign="top" align="left"><input name=<?=$db_ref_id?>-par size="10" value="<?=str_replace('"', '&quot;', $db1_cust_par);?>"></td>
              	 <td class="fname1" vAlign="top" align="left"><input name=<?=$db_ref_id?>-course_rating size="10" value="<?=str_replace('"', '&quot;', $db1_cust_course_rating);?>"></td>
              	 <td class="fname1" vAlign="top" align="left"><input name=<?=$db_ref_id?>-result_sp size="10" value="<?=str_replace('"', '&quot;', $db1_cust_result);?>"></td>
              	 <td class="fname1" vAlign="top" align="left"><input name=adjhcp<?=$db_ref_id?> size="10" value="<?=str_replace('"', '&quot;', $db1_cust_adj_hcp);?>">
<?
		              	if($_SESSION["sCONTROLLVL"]=="GRP02") {
		                 echo "<input type=\"button\" value=\"copy\" onClick=\"if(formupdate.adjhcp".$db_ref_id.".value.length==0) formupdate.adjhcp".$db_ref_id.".value = formupdate.inithcp".$db_ref_id.".value;\">";
						}
?>
              	 </td>
              	 <td class="fname1" vAlign="top" align="center" width="14%">&nbsp;<?=$db_ref_id==2?"<img src=\"images/tick.gif\">":"";?></td>
             </tr>
<?
				}
			}
?>
    			</table>
                </td><td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td></tr>
			<tr>
                <td class="ftitle1" vAlign="top" align="right">&nbsp;</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><b><u>ATTENTION</u></b>
                <ul>
                <li>Minimum age for participation : 18 years old<br/></li>
                <!-- Modified by William on April 20, 2006. Display year using SESSION -->
                <li>Participants must include a copy of a valid membership card which lists their official handicap ( must be dated later than <?=$handicap_date;?> ) and original scorecards of each tournament round are also required to be submitted before <?=$scorecard_date;?> to be eligible to participate in the Asian Final.<br/></li>
                <li>Participant's handicap will be adjusted at every tournament level throughout to World Final.<br/></li>
                <li>All handicap revisions will be reflected in the scorecard for each round of play.<br/></li>
                <li>No participant will be allowed a handicap higher than when first entering MercedesTrophy competitions in his country final this year.<br/></li>
                <li>Participant's handicap on the day of play and their performance in the country final will be taken into consideration.</li>
                </ul>
                </td>
                <td class="ftitle1" vAlign="top" align="right">&nbsp;</td>
              </tr>
<?
		}
		
		if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP" ||$_GET["tp"]=="gt") {
?>
              <tr>
                <td height="22" colspan="5" align="center" vAlign="top" class="ftitle4">Activity / Meal</td>
              </tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right">&nbsp;</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><u>MercedesTrophy Programme Attendance</u><br/>
                <b>Warm Up Round</b> and <b>Safety Driving Training programmes </b> on <? echo $first_day_string ?> are optional. Guests are, however, strongly encouraged to attend. <br><font color=#ffff66 style=background-color:#014588><b>For activities that the guest will not be  participating in, please remove the relevant ticks.</b></font>
				<? $AustGolf_Addon->generate_activity_tick_ver2($qrow["cust_id"]); ?>
                <br/><br/>
                <td class="ftitle1" vAlign="top" align="right">&nbsp;</td>
              </tr>
<?
		}
?>
             <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Comments</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><textarea name="comment" cols="100" rows="6" ><?=html_entity_decode($qrow["comment"]);?></textarea><br/>
<?
		if($_GET["tp"]=="gt") {
?>
                Please indicate name of companion(s) if any. Please also note that it is compulsory to register companions regardless of whether they are participating in golf or not.
<?
		}
?>
                </td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td></tr>
             <tr>
             	<td class="ftitle1" vAlign="top" align="right" width="20%">&nbsp;</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
		<div align="center"><input type=submit value=Update name=Update>
            &nbsp;
<? if (preg_match('/MGM/', $_SESSION["sCONTROLLVL"])) { ?>
            <input type=button value=Cancel name=Cancel onclick="window.location='index.php?s=view_local&tbn=cust_tb&as=view'"></div>
<? } else { ?>
            <input type=button value=Cancel name=Cancel onclick="window.location='<?=$_SESSION["returnURL"]?><?/*index.php?s=view_local&tbn=cust_tb&as=view*/?>'"></div>
<? } ?>
                </td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td></tr>
</FORM>
<?
		if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP") {
?>
<br><br>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Additional Controls</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
                <FORM name=formgroup action=<?=$_SERVER["REQUEST_URI"];?> method=POST>
                <table border=1 width=400 align="center">
<?
			$control_result=$User_Info->doSearchSQL("cust_holder",("cust_id=".$qrow["cust_id"]),"","inf");
			if($cm_row=mysql_fetch_array($control_result)) {
				include_once("grp_name.php");
?>
                <tr>
                <td height="22" colspan="3" align="center" vAlign="top" class="ftitle4">Current Billing Group
                </td>
                <td ><?=$grp_name[$cm_row["grp_id"]]?>
                </td>
                </tr>
                <tr>
                <td height="22" colspan="3" align="center" vAlign="top" class="ftitle4">Change New Billing Group
				</td>
                <td ><input name=holder type=hidden value="<?=$cm_row["user_holder"]?>"><?$AustGolf_Addon->generate_group_list($cm_row["grp_id"]);?> &nbsp;<input type=submit value=Change name=Change>
                </td>
                </tr>
<?
		}
?>
                </table>
                </form>
                </td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td></tr>
<?
		}
?>
</tbody></table>
<?
	}
	}
?>
<br />
<br />
<?php
} else {
	$User_Info->go_page("error");
}
?>
