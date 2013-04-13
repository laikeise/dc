<?php 
include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$DateFormat= new Addon;
$post_error=0;
//set up of the tmp structures
$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
//ini_set('display_errors', 1);

$tblist=$User_Info->getFieldName("$tmptb");

//do the access checks here
if(!isset($_GET["tp"])) {
	$_GET["tp"]="";
}

isset($_POST["HotelCheckinDateY"]) ? $HotelCheckinDateY = $_POST["HotelCheckinDateY"] : $HotelCheckinDateY = null;
isset($_POST["HotelCheckinDateM"]) ? $HotelCheckinDateM = $_POST["HotelCheckinDateM"] : $HotelCheckinDateM = null;
isset($_POST["HotelCheckinDateD"]) ? $HotelCheckinDateD = $_POST["HotelCheckinDateD"] : $HotelCheckinDateD = null;
isset($_POST["HotelCheckoutDateY"]) ? $HotelCheckoutDateY = $_POST["HotelCheckoutDateY"] : $HotelCheckoutDateY = null;
isset($_POST["HotelCheckoutDateM"]) ? $HotelCheckoutDateM = $_POST["HotelCheckoutDateM"] : $HotelCheckoutDateM = null;
isset($_POST["HotelCheckoutDateD"]) ? $HotelCheckoutDateD = $_POST["HotelCheckoutDateD"] : $HotelCheckoutDateD = null;

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

if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])) {
	if(isset($_POST["Add"])) {	
		$_POST["family_name"] = preg_replace('/\s+/', ' ', trim($_POST["family_name"]));
		$_POST["first_name"] = preg_replace('/\s+/', ' ', trim($_POST["first_name"]));
		//Duplicate First and Second Name Rejection
		$searchff=$User_Info->doSearchSQL("$tmptb",
			"(fullname='".addslashes($_POST["family_name"]." ".$_POST["first_name"])."' OR 
			 fullname='".addslashes($_POST["first_name"]." ".$_POST["family_name"])."')"
			);
		
		if($searchrow=mysql_fetch_array($searchff)) {
			print "A similar record of the Family Name : - <b>".$_POST["family_name"]."</b> , First Name : - <b>".$_POST["first_name"]. "</b> exists! Duplicated entry is not allowed.";
			$post_error=1;
		} else if($_POST["family_name"]==""||$_POST["first_name"]==""||$_POST["player_type"]==""||$_POST["sex"]==""||$_POST["dietary"]==""||
						$_POST["room_short_form"]==""||$_POST["transport"]==""||$_POST["depart_port"]==""||
						($_POST["country_name"]=="" && $_POST["country_oth"]=="")||
						$_POST["arrival_port"]==""||($_GET["tp"]!="gt"&&($_POST["2-div"]==""||eregi("[^0-9.]",$_POST["inithcp2"])||
						$_POST["inithcp2"]==""||eregi("[^0-9.]",$_POST["adjhcp2"])||$_POST["adjhcp2"]==""||
						eregi("[^0-9.]",$_POST["2-par"])||$_POST["2-par"]==""||$_POST["2-course_rating"]==""||
						(eregi("[^0-9.]",$_POST["2-result_sp"])||$_POST["2-result_sp"]==""))) ||
						($_POST["transport2"]=="" && $_POST["transport"]!="NT") ||
						(strlen($CheckinDateTime) == 16 &&  strlen($CheckoutDateTime) == 16 && 
						(strcmp($CheckoutDateTime, $CheckinDateTime) == -1 || strcmp($CheckoutDateTime, $CheckinDateTime) == 0)) ) {
			print "<table border='0'><tr><td width=25%>&nbsp;</td><td width=50%>";
			print "<br/><b><font color='red'>Sorry, there are missing information from this registration form. Please complete the following fields so that the registration can be successfully saved.</b>";
			print "</font></td><td width=25%>&nbsp</td></tr>";
			print "<tr><td width=25%>&nbsp;</td><td width=50%>";
			print "<table border='0' width='400'><tr><td width=10%>&nbsp;</td><td width=80%><font color='red'>";
			if($_POST["salutation"]=="" && $_POST["salutation_oth"]=="")
				print "<br/>- Salutation";
			if($_POST["sex"]=="")
                                print "<br/>- Sex";
			if($_POST["family_name"]=="")
				print "<br/>- Family Name";
			if($_POST["first_name"]=="")
				print "<br/>- First Name";
			if($_POST["country_name"]=="" && $_POST["country_oth"]=="")
                                print "<br/>- Country";
			if($_POST["player_type"]=="")
				print "<br/>- Player Type";
			if($_POST["dietary"]=="")
                                print "<br/>- Dietary";
			if($_POST["room_short_form"]=="")
				print "<br/>- Room Type";
			if($_POST["transport"]=="")
				print "<br/>- Transport Type";
			else {
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
			
			if($_GET["tp"]!="gt") {
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
			
			print "</font></td><td width=10%>&nbsp</td></tr></table>";
			print "</td><td width=25%>&nbsp</td></tr></table>";
			print "<hr><br/>";
			$post_error=1;
		} else {
			$tempsql="insert into $tmptb set ";
			while (list($key, $val) = each($tblist)) {
				$tempstring=(isset($_POST["$val"])?($_POST["$val"]):"");
				
				if(empty($tempstring)) {
					if($val=="arrival_date") {
						if($_POST["CheckinDateM"]=="0"||$_POST["CheckinDateD"]=="0")
							$tempsql=$tempsql."$val=\"0000-00-00\" ,";
						else
							$tempsql=$tempsql."$val='".$_POST["CheckinDateY"]."-".$_POST["CheckinDateM"]."-".$_POST["CheckinDateD"]."',";
					} else if($val=="depart_date") {
						if($_POST["CheckinDateM"]=="0"||$_POST["CheckinDateD"]=="0")
							$tempsql=$tempsql."$val=\"0000-00-00\" ,";
						else
							$tempsql=$tempsql."$val='".$_POST["CheckoutDateY"]."-".$_POST["CheckoutDateM"]."-".$_POST["CheckoutDateD"]."',";
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
					} else if($val=="country_name") {
						if($_POST["country_oth"]==""){
							$tempsql=$tempsql."$val='".$_POST["country_name"]."', ";
						} else {
							$tempsql=$tempsql."$val='".addslashes($_POST["country_oth"])."', ";		
						}
					} else if($val=="salutation") {
						$tempsql=$tempsql."$val='".addslashes($_POST["salutation_oth"])."', ";
					/*} else if($val=="transport") {
						$tempsql=$tempsql."$val='".$_POST["transport"].":~~:".$_POST["transport2"].":~:".$_POST["transport_no"]."',";
					} else if($val=="arrival_flight") {
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
					} else
						$tempsql=$tempsql."$val='', ";
				} else {
					/*if($val=="transport") {
						$tempsql=$tempsql."$val='".$_POST["transport"].":~~:".$_POST["transport2"].":~:".$_POST["transport_no"]."',";
					} else if($val=="arrival_flight") {
						$tempsql=$tempsql."$val='".addslashes(strtoupper(substr($_POST["arrival_flight"],0,6))).":~:".$_POST["arrival_port"]."', ";
					} else if($val=="depart_flight") {
						$tempsql=$tempsql."$val='".addslashes(strtoupper(substr($_POST["depart_flight"],0,6))).":~:".$_POST["depart_port"]."', ";
					
					} else*/ if($val=="country_name") {
						if($_POST["country_oth"]=="") {
							$tempsql=$tempsql."$val='".$_POST["country_name"]."', ";
						} else {
							$tempsql=$tempsql."$val='".addslashes($_POST["country_oth"])."', ";
						}
					} else
						$tempsql=$tempsql."$val='".addslashes($tempstring)."', ";
				}
			}
			
			$tempstrlen=strlen($tempsql)-2;
			$tempsql=substr($tempsql,0,$tempstrlen);
			$tempsql=$tempsql." ;";
			$Error_Handler->print_error($tempsql);
			//temp disable
			$updarow=$User_Info->doUpdateSQL($tempsql);
			if (!$updarow) {
                                echo "<span style=color:red;>Error update, please contact administrator!<br>$tempsql</span>" ;
                                $post_error=1;
                        }

			$tempinsertid=mysql_insert_id();
			if($_GET["tp"]!="gt") {	
				$div1sql= "insert into `cust_handi_tb` set `cust_id`='$tempinsertid', `ref_handi_id`='1', `cust_handi_div`=\"".strtoupper($_POST["1-div"])."\" , `cust_initial_hcp`='".$_POST["inithcp1"]."', `cust_adj_hcp`='".$_POST["adjhcp1"]."' , `cust_par`='".$_POST["1-par"]."' , `cust_course_rating`=\"".$_POST["1-course_rating"]."\" , `cust_result`='".$_POST["1-result_sp"]."';";
				$div2sql= "insert into `cust_handi_tb` set `cust_id`='$tempinsertid', `ref_handi_id`='2', `cust_handi_div`=\"".strtoupper(addslashes($_POST["2-div"]))."\" , `cust_initial_hcp`='".$_POST["inithcp2"]."', `cust_adj_hcp`='".$_POST["adjhcp2"]."' , `cust_par`='".$_POST["2-par"]."' , `cust_course_rating`=\"".$_POST["2-course_rating"]."\" , `cust_result`='".$_POST["2-result_sp"]."';";
				$updarow=$updarow+($User_Info->doUpdateSQL($div1sql))+($User_Info->doUpdateSQL($div2sql));
			}
			
			if($_GET["tp"]=="gt") {
				$act_q="select act_ref_id, act_name, act_description,act_default,act_date from ref_act_tb";
				$act_result = $User_Info->doSQL($act_q);
				while($rm_row=mysql_fetch_array($act_result)) {
					$db_act_name=$rm_row["act_name"];
					$db_act_ref_id=$rm_row["act_ref_id"];
					if(isset($_POST["ck$db_act_ref_id"])) {
						if(isset($_POST["{$db_act_ref_id}-addon"])){
							$actsql= "insert into cust_act_tb values (NULL,$db_act_ref_id,".($_POST["txt$db_act_ref_id"]+1).",$tempinsertid,\"".$_POST["{$db_act_ref_id}-addon"]."\");";
						} else {
							$actsql= "insert into cust_act_tb values (NULL,$db_act_ref_id,".($_POST["txt$db_act_ref_id"]+1).",$tempinsertid,\"\");";
						}
							
						$updarow=$updarow+($User_Info->doUpdateSQL($actsql));
					} else {
						$actsql= "insert into cust_act_tb values (NULL,$db_act_ref_id,0,$tempinsertid,\"\");";
						$updarow=$updarow+($User_Info->doUpdateSQL($actsql));
					}
				}
			} else {
				$act_q="select act_ref_id, act_name, act_description,act_default,act_date from ref_act_tb";
				$act_result = $User_Info->doSQL($act_q);
				while($rm_row=mysql_fetch_array($act_result)) {
					$db_act_name=$rm_row["act_name"];
					$db_act_ref_id=$rm_row["act_ref_id"];
					$actsql= "insert into cust_act_tb values (NULL,$db_act_ref_id,1,$tempinsertid,\"\");";
					
					// * Note Not to insert for GTD and LGD when is Tournament Player
					if (strtoupper($db_act_name) != "GTD" && strtoupper($db_act_name) != "LGD")
						$updarow=$updarow+($User_Info->doUpdateSQL($actsql));	
				}
			}
			
			$cust_add_sql="";
			$cust_add_flag=0;
			$POST = $_POST ;
	
			if(isset($POST["cust_coy"])) {
				$cust_add_sql=$cust_add_sql."cust_coy='".addslashes($POST["cust_coy"])."',";
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
				// not sure why addslashes always failed here...
				$cust_add_sql=$cust_add_sql."cust_club='".str_replace("'", "\'", $_POST["cust_club"])."',";
				$cust_add_flag=1;
			}
			if($cust_add_flag==1) {
				$cust_add_sql=substr($cust_add_sql,0,-1);
				$cust_add_sql= "insert into cust_add_tb set cust_id=$tempinsertid ,".$cust_add_sql.";";
				$updarow=$updarow+($User_Info->doUpdateSQL($cust_add_sql));
			}
				
			if($updarow) {
				// send alert email to admins -- marc 20070521
				$prev_year = $_SESSION['sThisYear']-1;
				$fullname = $_POST['family_name'] . " " . $_POST['first_name'];
				$fullname2 = $_POST['first_name'] . " " . $_POST['family_name'];
				
				$query = "SELECT `name`, `year`
                                          FROM `past_winners`
                                          WHERE year='$prev_year' AND 
						(`name` = '".addslashes($fullname)."' OR `name` = '".addslashes($fullname2)."')";
                                $result = $User_Info->doSQL($query);
				if (mysql_num_rows($result) != 0) {
                                	$query = "UPDATE `cust_tb` SET `blacklist` = 'World Final' WHERE `cust_id` = '$tempinsertid'";
                                        $User_Info->doUpdateSQL($query);

                                        $AustGolf_Addon->alert_email(date('Y-m-d g:i A'), $_POST['family_name'] . " " . $_POST['first_name'], $_POST['country_name'] . $_POST['country_oth'], $_SESSION['sNick'], "World Final participant added");
                                        echo "<br/><div style='font-weight:bold;text-align:center;color:#f00'>Warning: You have added a participant that has won in the previous year's event.<br/>The admins have been notified accordingly.</div>";
                                } else {
					$query = "SELECT `name`, `year`, `player_type`
                                        	FROM `past_players`
                                        	WHERE `year` = '$prev_year'
                                        		AND `player_type` = 'T'
                                        		AND (`name` = '".addslashes($fullname)."' OR `name` = '".addslashes($fullname2)."')";
                                	$result = $User_Info->doSQL($query);
                                	if (mysql_num_rows($result) != 0) {
                                        	$query = "UPDATE `cust_tb` SET `blacklist` = 'Asian Final' WHERE `cust_id` = '$tempinsertid'";
                                        	$User_Info->doUpdateSQL($query);

                                        	$AustGolf_Addon->alert_email(date('Y-m-d g:i A'), $_POST['family_name'] . " " . $_POST['first_name'], $_POST['country_name'] . $_POST['country_oth'], $_SESSION['sNick'], "Previous year participant added");
                                        	echo "<br/><div style='font-weight:bold;text-align:center;color:#f00'>Warning: You have added a participant that has played in the previous year's event.<br/>The admins have been notified accordingly.</div>";
					} else {
                                		if ($_POST["player_type"] == 'G')
                                                	$ptype = 'Guest';
                                        	else if ($_POST["player_type"] == 'T')
                                        		$ptype = 'Tournament';
                                        	$AustGolf_Addon->alert_email(date('Y-m-d g:i A'), $_POST['family_name'] . " " . $_POST['first_name'], $_POST['country_name'] . $_POST['country_oth'], $_SESSION['sNick'], "New $ptype participant added");
					}
                                }

				$AustGolf_Addon->user_control($tempinsertid);
				$AustGolf_Addon->action_log($tempinsertid,"add");
				if($_GET[tp] == "gt") {
					print "<span class=\"ftitle3\"><p align=\"center\">Guest Player/Country Captain Registered</span><br>";
				} else 
					print "<span class=\"ftitle3\"><p align=\"center\">Tournament Player Registered</span><br>";
				
				print "<p align=\"center\"><input type=button value=\"Register another Guest Player/Country Captain\" onclick=\"window.location='index.php?s=add_cust&tbn=cust_tb&as=adddata&tp=gt'\">";
				print "<p align=\"center\"><input type=button value=\"Register another Tournament Player\" onclick=\"window.location='index.php?s=add_cust&tbn=cust_tb&as=adddata'\">";
				print "<p align=\"center\"><input type=button value=\"Back to Home Page\" onclick=\"window.location='index.php?s=admin'\">";
			} else
				print "Nothing added!";
		}
	} else
		$post_error=1;
	
if($post_error==1) {
?>
<br />
<?php 
	$rmtotal=0;
	print "<br />";
?>
	<form name=formupdate action=<?=$_SERVER["REQUEST_URI"]; ?> method=post>
		<table cellSpacing=0 cellPadding=2 width="100%" border=1><input type=hidden name=cust_id>
		  <tbody>
		  <tr><td class="ftitle1" align="center" colspan=4>
<?
	if($_GET["tp"]=="gt")
		print "<span style=\"font-size:14px\">Register Guest Player</span>";
	else
		print "<span style=\"font-size:14px\">Register Tournament Player</span>";
?>
		  </td><td class="ftitle1"><span style="font-size:10px"><img src="images/tick.gif">indicates a mandatory field</td></tr>
              
		  <tr>
		  <td colspan="5" align="center" class="ftitle1"><span style="font-size:12px">&nbsp;&nbsp;&nbsp;<b>Note: It is compulsory to register companions regardless of whether they are participating in golf or not.</b></span></td></tr>
              
		  <tr>
			<td class="ftitle1" vAlign="top" align="right" width="20%">Sal</td>
			<td class="fname1" vAlign="top" align="left" width="30%"><span class="fvalue">
<?
	if(!isset($_POST["salutation"]))
		$_POST["salutation"]="";
?>
			  <select name=salutation class="fname1norm" id="salutation">
				<option value="" <?=$_POST["salutation"]==""?"selected":" "?>>--- Select One ---</option> 
				<option value="Tan Sri" <?=$_POST["salutation"]=="Tan Sri"?"selected":" "?>>Tan Sri</option>
				<option value="Tan Seri" <?=$_POST["salutation"]=="Tan Seri"?"selected":" "?>>Tan Seri</option>
				<option value=Datuk <?=$_POST["salutation"]=="Datuk"?"selected":" "?>>Datuk</option>
				<option value=Datin <?=$_POST["salutation"]=="Datin"?"selected":" "?>>Datin</option>
				<option value=Dr <?=$_POST["salutation"]=="Dr"?"selected":" "?>>Dr</option>
				<option value=Madam <?=$_POST["salutation"]=="Madam"?"selected":" "?>>Madam</option>
				<option value=Mr <?=$_POST["salutation"]=="Mr"?"selected":" "?>>Mr</option>
				<option value=Mrs <?=$_POST["salutation"]=="Mrs"?"selected":" "?>>Mrs</option>
				<option value=Ms <?=$_POST["salutation"]=="Ms"?"selected":" "?>>Ms</option>
				<option value=Master <?=$_POST["salutation"]=="Master"?"selected":" "?>>Master</option>
				<option value=Prof <?=$_POST["salutation"]=="Prof"?"selected":" "?>>Prof</option>
				<option value=Mdm <?=$_POST["salutation"]=="Mdm"?"selected":" "?>>Mdm</option>
				<option value=Messrs <?=$_POST["salutation"]=="Messrs"?"selected":" "?>>Messrs</option>
				<option value=Messer <?=$_POST["salutation"]=="Messer"?"selected":" "?>>Messer</option>
				<option value=Professor <?=$_POST["salutation"]=="Professor"?"selected":" "?>>Professor</option>
				</select>
				 Others: <input size=6 name=salutation_oth value="<?=(isset($_POST["salutation_oth"])?str_replace('"','&quot;',$_POST["salutation_oth"]):"");?>">
			</span></td>
			<td class="ftitle1" vAlign="top" align="right" width="10%">Sex</td>
			<td class="fname1" vAlign="top" align="left" width="30%"><span class="fvalue">
				<select name="sex" class="fname1norm" id="sex">
				<option value="">--- Select One ---</option>
				<option value="M"<?=$_POST["sex"]=="M"?" selected":""?>>Male</option>
				<option value="F"<?=$_POST["sex"]=="F"?" selected":""?>>Female</option>
				</select></span></td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td></tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Family and First Name</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=family_name value="<?=(isset($_POST["family_name"])?str_replace('"','&quot;',$_POST["family_name"]):"");?>">
                <input size="40" name=first_name value="<?=(isset($_POST["first_name"])?str_replace('"','&quot;',$_POST["first_name"]):"");?>"></td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td></tr>
<?
	if($_GET["tp"]=="gt") {
?>
		<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Company</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size=80 name=cust_coy value="<?=(isset($_POST["cust_coy"])?str_replace('"','&quot;',$_POST["cust_coy"]):"");?>"><br>&nbsp;Please enter in proper title casing, for example, "DaimlerChrysler South East Asia Pte Ltd".</td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
		<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Designation</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=cust_dest value="<?=(isset($_POST["cust_dest"])?str_replace('"','&quot;',$_POST["cust_dest"]):"");?>"></td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
		<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Contact Tel</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=cust_tel value="<?=(isset($_POST["cust_tel"])?str_replace('"','&quot;',$_POST["cust_tel"]):"");?>">&nbsp;Please include country and area codes in this format: (country_code) area_code tel_number</td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
		<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Contact Fax</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=cust_fax value="<?=(isset($_POST["cust_fax"])?str_replace('"','&quot;',$_POST["cust_fax"]):"");?>">&nbsp;Please include country and area codes in this format: (country_code) area_code tel_number</td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
		<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Email Address</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><input size="40" name=cust_email value="<?=(isset($_POST["cust_email"])?str_replace('"','&quot;',$_POST["cust_email"]):"");?>"></td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td>
			</tr>
<?
	}
?>
              <tr>
               <td class="ftitle1" vAlign="top" align="right" width="20%">Player</td>
                <td class="fname1" vAlign="top" align="left"><span class="fvalue">
<?
	if(!isset($_POST["player_type"]))
		$_POST["player_type"]="";
?>
<?
	if($_GET["tp"]=="gt") {   
?>
				<select name=player_type class="fname1norm" id="player_type">
					<option value="" <?=$_POST["player_type"]==""?"selected":" "?>>--- Select One ---</option>
                    <option value="G" <?=$_POST["player_type"]=="G"?"selected":" "?>>Guest Player</option>
                    <option value="CC" <?=$_POST["player_type"]=="CC"?"selected":" "?>>Country Captain</option>
                    <option value="O" <?=$_POST["player_type"]=="O"?"selected":" "?>>Others</option>
<?
	if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP") {
?>
					<!--<option value="OL" <?=$_POST["player_type"]=="OL"?"selected":" "?>>Opinion Leader</option>-->
					<option value="GPRO" <?=$_POST["player_type"]=="GPRO"?"selected":" "?>>Golf Pro</option>
					<option value="GCOM" <?=$_POST["player_type"]=="GCOM"?"selected":" "?>>Golf Committee</option>
<?
	}
?>
					</select>
<?
	} else {
?>
		<input type="hidden" name="player_type" value="T">
		<SPAN class="fname1"><b>Tournament Player</b></span>
<? 
	} 
?>
                </span></td>
		<td class="ftitle1" vAlign="top" align="right">Dietary</td>
		<td class="fname1" vAlign="top" align="left"><span class="fvalue">
			<input size=40 name=dietary value="<?=(isset($_POST["dietary"])?str_replace('"','&quot;',$_POST["dietary"]):"");?>">
			</span></td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td>
				</tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Country</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><span class="fvalue">
				<? $AustGolf_Addon->generate_country_list(($_SESSION["sRecord"]=="ALL"?$_POST["country_name"]:$_SESSION["sRecord"]),$_POST["country_oth"]); ?>
				</span></td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td>
				</tr>
                <tr>
                <td class="ftitle4" vAlign="top" align="right" width="20%" colspan="5">&nbsp;</td>
                </tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Arrival Date</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><SPAN class=fcomments></span>
                	<select name=CheckinDateD>
					<? $AustGolf_Addon->generate_day((isset($_POST["CheckinDateD"])?$_POST["CheckinDateD"]:"")); ?>
					</select>/
					<select name=CheckinDateM>
					<? $AustGolf_Addon->generate_month((isset($_POST["CheckinDateM"])?$_POST["CheckinDateM"]:"")); ?>
                    </select>/ 
                    <!-- Modified by William on April 20, 2006. Display year using SESSION -->
                    <input type="hidden" name=CheckinDateY value="<?=$_SESSION['sThisYear'];?>"> <?=$_SESSION['sThisYear'];?>
                  <b>Time :</b>
					<select name=arrival_time_hr>
					<? $AustGolf_Addon->generate_hour((isset($_POST["arrival_time_hr"])?$_POST["arrival_time_hr"]:"")); ?>
					</select> : 
					<select name=arrival_time_min>
					<? $AustGolf_Addon->generate_min((isset($_POST["arrival_time_min"])?$_POST["arrival_time_min"]:"")); ?>
					</select> (24 hour format)
                  <b>&nbsp; Flight : </b>
                  <input size=10 maxlength="6" name=arrival_flight value="<?=(isset($_POST["arrival_flight"])?str_replace('"','&quot;',$_POST["arrival_flight"]):"");?>"> 
					<select name=arrival_port>
                	<option value="" selected>Select Airport</option>
                	<option value="BI" <?=($_POST["arrival_port"]=="BI"?"selected":"");?>>Brisbane International</option>
                	<option value="DA" <?=($_POST["arrival_port"]=="DA"?"selected":"");?>>Brisbane Domestic</option>
                	<option value="NA" <?=($_POST["arrival_port"]=="NA"?"selected":"");?>>Not Applicable</option>
                	</select>
                  </td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td>
				</tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Departure Date</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
				<select name=CheckoutDateD>
				<? $AustGolf_Addon->generate_day((isset($_POST["CheckoutDateD"])?$_POST["CheckoutDateD"]:"")); ?>
				</select>/
				<select name=CheckoutDateM>
				<? $AustGolf_Addon->generate_month((isset($_POST["CheckoutDateM"])?$_POST["CheckoutDateM"]:"")); ?>
				</select>/
                  <!-- Modified by William on April 20, 2006. Display year using SESSION -->
                  <input type="hidden" name=CheckoutDateY value="<?=$_SESSION['sThisYear'];?>"> <?=$_SESSION['sThisYear'];?> 
                    <b>Time : </b>
                    <select name=depart_time_hr>
					<? $AustGolf_Addon->generate_hour((isset($_POST["depart_time_hr"])?$_POST["depart_time_hr"]:"")); ?>
					</select> : <select name=depart_time_min>
					<? $AustGolf_Addon->generate_min((isset($_POST["depart_time_min"])?$_POST["depart_time_min"]:"")); ?>
					</select> (24 hour format)
                  &nbsp;
                  <b>Flight : </b>
                  <input size=10 maxlength="6" name=depart_flight value="<?=(isset($_POST["depart_flight"])?str_replace('"','&quot;',$_POST["depart_flight"]):"");?>">
                <select name=depart_port>
                	<option value="" selected>Select Airport</option>
                	<option value="BI" <?=($_POST["depart_port"]=="BI"?"selected":"");?>>Brisbane International</option>
                	<option value="DA" <?=($_POST["depart_port"]=="DA"?"selected":"");?>>Brisbane Domestic</option>
                	<option value="NA" <?=($_POST["depart_port"]=="NA"?"selected":"");?>>Not Applicable</option>
				</select>
				</td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td>
				</tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Room Type</td>
                <td class="fname1" vAlign="top" align="left" colspan="3"><table cellspacing=0 cellpadding=0 border=0><tr valign=top>
			<td rowspan=2>
                <? $AustGolf_Addon->generate_room_list((isset($_POST["room_short_form"])?$_POST["room_short_form"]:"")); ?>
                <br><b>Note:</b> * Requested room type subject to Hotel's availability.
			</td>
			<td width=23 rowspan=2><br></td>
			<td><b>Hotel Check-In Date : </b></td>
			<td><select name=HotelCheckinDateD>
                                <? $AustGolf_Addon->generate_day((isset($_POST["HotelCheckinDateD"])?$_POST["HotelCheckinDateD"]:"")); ?>
                                </select>/
                                <select name=HotelCheckinDateM>
                                <? $AustGolf_Addon->generate_month((isset($_POST["HotelCheckinDateM"])?$_POST["HotelCheckinDateM"]:"")); ?>
                                </select>/
                  		<input type="hidden" name=HotelCheckinDateY value="<?=$_SESSION['sThisYear'];?>"> <?=$_SESSION['sThisYear'];?>
			</td></tr>
			<tr>
			<td><b>Hotel Check-Out Date : </b>&nbsp;</td>
			<td><select name=HotelCheckoutDateD>
                                <? $AustGolf_Addon->generate_day((isset($_POST["HotelCheckoutDateD"])?$_POST["HotelCheckoutDateD"]:"")); ?>
                                </select>/
                                <select name=HotelCheckoutDateM>
                                <? $AustGolf_Addon->generate_month((isset($_POST["HotelCheckoutDateM"])?$_POST["HotelCheckoutDateM"]:"")); ?>
                                </select>/
                                <input type="hidden" name=HotelCheckoutDateY value="<?=$_SESSION['sThisYear'];?>"> <?=$_SESSION['sThisYear'];?>
			</td>
			</tr></table>
                </td>
                <td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif"></td></tr>

                <script type="text/javascript">
					function doPostBack(e)
					{
	  					e.form.transport2.style.visibility = (e.value == "SB" || e.value == "HL" ) ? "visible" : "hidden";
	  					e.form.transport_img2.style.visibility = (e.value == "SB" || e.value == "HL" ) ? "visible" : "hidden";  					
					}
					function doTransportRequirement(e)
					{
	  					return '';
					}
				</script>
                
              <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Transport Type</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
                <? $AustGolf_Addon->generate_tranport_list((isset($_POST["transport"])?$_POST["transport"]:"")); ?>
				<br>
				<? $AustGolf_Addon->generate_tranport2_list((isset($_POST["transport2"])?$_POST["transport2"]:"")); ?>
				<?/*<br>&nbsp;Transport Comments : <input size="20" name=transport_no value="<?=(isset($_POST["transport_no"])?str_replace('"','&quot;',$_POST["transport_no"]):"");?>" >*/?>
                <br/><b>Transport Service will be made available for <u>group pick-up</u> only. Should individuals be arriving separately, please make your own transportation arrangement to and/or from the airport.
                Hotel Limo requested is chargeable.</b>
                </td>
    			<td class="ftitle1" vAlign="top" align="left" width="10%">&nbsp;<img src="images/tick.gif">
    			<br><img name="transport_img2" src="images/tick.gif">
    			</td></tr>
    			<script type="text/javascript">
    				doPostBack(document.all.transport)
    			</script> 
    			
<?
			if($_GET["tp"]!="gt") {
?>   			
    			<tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Home Club</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
					<input size="60" name=cust_club value="<?=(isset($_POST["cust_club"])?str_replace('"','&quot;',$_POST["cust_club"]):"");?>">
                </td>
    			<td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td></tr>
<?
			}
?>   			
<?
			if($_GET["tp"]!="gt") {
				$ref_handi_result=$User_Info->doSearchSQL("ref_handi_tb","","","inf");
?>              
              <tr>
                <td class="ftitle4" vAlign="top" align="center" colspan="5">Personal Golf Record</td>
              </tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right"></td>
                <td class="fname1" vAlign="top" align="center" colspan="3">
                <table cellSpacing=0 cellPadding=2 width="100%" border=1>
                <tr>
                <td class="ftitle1" width=16%>&nbsp;</td>
                <td class="ftitle1" vAlign="top" align="center" width=14%>Division</td>
                <td class="ftitle1" vAlign="top" align="center" width=14%>Initial Handicap</td>
                <td class="ftitle1" vAlign="top" align="center" width=14%>Par</td>
                <td class="ftitle1" vAlign="top" align="center" width=14%>Course Rating</td>
                <td class="ftitle1" vAlign="top" align="center" width=14%>Result (Stableford Points)</td>
                <td class="ftitle1" vAlign="top" align="center" width=14%>Adjusted Handicap</td>
                <td class="ftitle1" vAlign="top" align="center" width=14%>&nbsp;</td>
                </tr>
<?
				while($ref_handi_row=mysql_fetch_array($ref_handi_result)) {
              		$db_ref_id=$ref_handi_row["handi_ref_id"];
              		$db_ref_name=$ref_handi_row["handi_name"];
              		if(substr($_SESSION["sCONTROLLVL"],0,3)=="GRP"||(substr($_SESSION["sCONTROLLVL"],0,3)=="MGM"&&($db_ref_id==1||$db_ref_id==2))) {
?>
                <tr>
                <td class="ftitle1" vAlign="top" align="center"><?=$db_ref_name?></td>
                <td class="fname1" vAlign="top" align="center"><input name=<?=$db_ref_id?>-div size=10 value="<?=(isset($_POST["$db_ref_id-div"])?$_POST["$db_ref_id-div"]:"");?>">
                </td>
                <td class="fname1" vAlign="top" align="center"><input name=inithcp<?=$db_ref_id?> size=10 value="<?=(isset($_POST["inithcp$db_ref_id"])?$_POST["inithcp$db_ref_id"]:"");?>">
                </td>
                <td class="fname1" vAlign="top" align="center"><input name=<?=$db_ref_id?>-par size=10 value="<?=(isset($_POST["$db_ref_id-par"])?$_POST["$db_ref_id-par"]:"");?>">
                </td>
                <td class="fname1" vAlign="top" align="center"><input name=<?=$db_ref_id?>-course_rating size=10 value="<?=(isset($_POST["$db_ref_id-course_rating"])?$_POST["$db_ref_id-course_rating"]:"");?>">
                </td>
                <td class="fname1" vAlign="top" align="center"><input name=<?=$db_ref_id?>-result_sp size=10 value="<?=(isset($_POST["$db_ref_id-result_sp"])?$_POST["$db_ref_id-result_sp"]:"");?>">
                </td>
                <td class="fname1" vAlign="top" align="center"><input name=adjhcp<?=$db_ref_id?> size=10 value="<?=(isset($_POST["adjhcp$db_ref_id"])?$_POST["adjhcp$db_ref_id"]:"");?>">
<? // hide copy button from non-admins
                if($_SESSION["sCONTROLLVL"]=="GRP02") {
					echo "<input type=\"button\" value=\"copy\" onClick=\"if(formupdate.adjhcp".$db_ref_id.".value.length==0) formupdate.adjhcp".$db_ref_id.".value = formupdate.inithcp".$db_ref_id.".value;\">";
				}
?>
                </td>
                <td class="fname1" vAlign="top" align="center" width=14%>&nbsp;<?=$db_ref_id==2?"<img src=\"images/tick.gif\">":"";?></td>
                </tr>
<?
                	}
				}
?>
                </table>
                </td>
                <td class="ftitle1" vAlign="top" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="ftitle1" vAlign="top" align="right">&nbsp;</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>ATTENTION</u></b>
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
			
  			if($_GET["tp"]=="gt") {
?>
              <tr>
                <td height="22" colspan="5" align="center" vAlign="top" class="ftitle4">Activity / Meal</td>            
              </tr>                          
              <tr>
                <td class="ftitle1" vAlign="top" align="right">&nbsp;</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
                <u>MercedesTrophy Programme Attendance</u><br/>
                <b>Warm Up Round</b> and <b>Safety Driving Training programmes </b> on <? echo $first_day_string ?> are optional. Guests are, however, strongly encouraged to attend. <br><font color=#ffff66 style=background-color:#014588><b>For activities that the guest will not be  participating in, please remove the relevant ticks.</b></font>
                <? $AustGolf_Addon->generate_activity_tick(); ?>
               	<br/><br/>
                <td class="ftitle1" vAlign="top" align="right"> &nbsp;</td>
              </tr>
<?
            }
?>
           <tr>
                <td class="ftitle1" vAlign="top" align="right" width="20%">Comments</td>
                <td class="fname1" vAlign="top" align="left" colspan="3">
                <input name="day_generate" type="hidden" value="1" >
                <textarea name="comment" cols="100" rows="6" id=" comment"><?=(isset($_POST["comment"])?html_entity_decode($_POST["comment"]):"");?></textarea><br/>
<?
                if($_GET["tp"]=="gt") {
?>
                Please indicate name of companion(s) if any. Please also note that it is compulsory to register companions regardless of whether they are participating in golf or not.
<?
				}
?>
                </td>
                <td class="ftitle1" vAlign="top" align="right" width="10%">&nbsp;</td></tr>
              </tbody></table>
              <br/><div align="center">
              <input type=submit value="Register Player" name=Add > &nbsp; &nbsp; &nbsp; &nbsp;<input type=button value="Cancel Registration" name=Cancel onclick="onCancelClick()"></FORM>
              </div>
<?
	}
?><br /><br />

<?php
} else {
	$User_Info->go_page("error");
}
?>

<script type="text/javascript">
	function onCancelClick() {
		result = window.confirm("Please note that the information entered here will not be saved if you cancel the registration. Are you sure you want to cancel?");
		if (result) {
			window.location = 'index.php?s=admin';
		}
		else
			return false;
	}
</script>
