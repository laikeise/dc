<?
class AustGolf_Addon extends DB 
{
	function generate_room_list($sel="") 
	{
		print "<select name='room_short_form' class=fname1norm >";
		print "<option value=''>Select Room</option>";
		$rm_q="select room_id ,room_name, room_short_form from ref_room_tb order by room_id";
		$rm_result = $this->doSQL($rm_q);
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_rm_name=$rm_row["room_name"];
			$db_rm_sh_name=$rm_row["room_short_form"];
			$db_rm_id=$rm_row["room_id"];
			print "<option value='" .$db_rm_sh_name. "' " . ($db_rm_sh_name=="$sel"?"selected":" ") ." >".$db_rm_name."</option>";
		}
		print "</select>";
	}
	
	function generate_country_list($sel="",$short="") 
	{
		$oth=0;
		$matchup=1;
		print "<select name='country_name' class=fname1norm>";
		$rm_q="select country_id ,country_name from ref_country_tb  order by country_name";
		$rm_result = $this->doSQL($rm_q);
		if($short=="" || $sel=="")
		{
			$oth=1;
		}
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_rm_name=$rm_row["country_name"];
			$db_rm_id=$rm_row["country_id"];
			if($db_rm_name==$sel)
				$matchup=0;
			print "<option value='" .$db_rm_name. "' " . ($db_rm_name==$sel?"selected":" ")." >".$db_rm_name."</option>";
		}
		print "<option value='' $sel " . ($matchup==1?"selected":" ") ." >-- Select Country --</option>";
		print "</select>";
		
		print " Others: <INPUT size=6 name=country_oth ";
		if($oth==1 && $matchup==0 && $short=="")
		{
			print "";
		}
		else{
			/*if($matchup==1)
				print "value=\"".str_replace('"', '&quot;', $sel)."\"";
			else*/
				print "value=\"".str_replace('"', '&quot;', $short)."\"";
		}
		print ">";
	}
	
	function generate_activity_tick() 
	{
		$edittype=1;
		// Modified by William on April 20, 2006. Display items that is >= today
		$rm_q="SELECT act_ref_id, act_name, act_description,act_default,act_date FROM ref_act_tb WHERE act_date>=CURDATE() ORDER BY act_date ASC";
		$tempdate="";
		$rm_result = $this->doSQL($rm_q);
		print "<table border=0>";
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_act_name=$rm_row["act_name"];
			$db_act_date=$rm_row["act_date"];
			$db_act_date=$rm_row["act_date"];
			$db_act_description=$rm_row["act_description"];
			$db_act_ref_id=$rm_row["act_ref_id"];
			$db_act_default=$rm_row["act_default"];
			
			if($tempdate!=$db_act_date)
			{
				$tempdate=$db_act_date;
				print "<tr>\n";
// change starting date -- marc 20080521
				if($db_act_date == $match_start_date)
				{
					print "<td align=left><b>". strftime("%d-%b-%y", strtotime($db_act_date))."</b></td>\n";	
				}
				else
				{
					print "<td colspan=3 align=left><b>". strftime("%d-%b-%y", strtotime($db_act_date))."</b></td>\n";
				}
			}
			
			if($db_act_ref_id != '3' && $db_act_ref_id != '4') {
				print "<tr valign=\"top\"><td><input name='ck$db_act_ref_id' type='checkbox' value='1'". ($db_act_default==1?"checked":" ").">$db_act_description \n";
			}
			
			/*
			if($db_act_ref_id == '4' || $db_act_ref_id == '3')
			{
				print "<td width=75><br/>&nbsp;</td>";
				print "<td align=right colspan=2>Current handicap : <input name=\"{$db_act_ref_id}-addon\" type=\"text\" size=2 value=\"". (isset($_POST["{$db_act_ref_id}-addon"])?$_POST["{$db_act_ref_id}-addon"]:"")."\"></td>\n";
			}
			*/
			// change current handicap to only accept one value - arthur
			print "</tr>\n";
			if($db_act_ref_id == '15')
			{
				print "<tr>";
				print "<td align=left colspan=2><br><br><b>Current handicap : </b><input name=\"{$db_act_ref_id}-addon\" type=\"text\" size=2 value=\"". (isset($_POST["{$db_act_ref_id}-addon"])?$_POST["{$db_act_ref_id}-addon"]:"")."\"></td>\n";
				print "</tr>\n";
			}
		}
		print "</table>";
	}
	
	function generate_activity_tick_ver2($sel=0,$edittype=1)
	{
		$marked=0;
		//$rm_q="select act_ref_id, act_name, act_description,act_default,act_date from ref_act_tb order by act_date ASC";
		$rm_q="SELECT act_ref_id, act_name, act_description,act_default,act_date FROM ref_act_tb WHERE act_date>CURDATE() ORDER BY act_date ASC";
		$rm_result = $this->doSQL($rm_q);
		$tempdate="";
		print "<table>";
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_act_name=$rm_row["act_name"];
			$db_act_date=$rm_row["act_date"];
			$db_act_ref_id=$rm_row["act_ref_id"];
			$db_act_default=$rm_row["act_default"];
			$db_act_description=$rm_row["act_description"];
			
			if($tempdate!=$db_act_date)
			{
				$tempdate=$db_act_date;
				print "<tr>\n";
				print "<td colspan=2 align=center><br/><b>". strftime("%d-%b-%y", strtotime($db_act_date))." </b></td>\n";
// change starting date -- marc 20080521
				if($db_act_date == $match_start_date)
					print "<td><br>&nbsp;</b></td>\n";
				else
					print "<td>&nbsp;</td>\n";
				print "<td>&nbsp;</td></tr>\n";
			}
			
			if($sel!=0)
			{
				$db_cust_act_no=0;
				$act_q="select * from cust_act_tb where cust_id=$sel and act_ref_id=$db_act_ref_id";
				
				$act_result = $this->doSQL($act_q);
				if($act_row=mysql_fetch_array($act_result))
				{
					$db_act_addon=$act_row["cust_addon"];
					$db_cust_act_no=$act_row["cust_act_no"];
					if($db_act_ref_id != '3' && $db_act_ref_id != '4') {
					print "<tr  valign=\"top\"><td><input name='ck$db_act_ref_id' type='checkbox' value='1'". ($db_cust_act_no>0?"checked":"")." ".($edittype==0?"Disabled":"").">$db_act_description</td>\n";
					}
					//print "<td>";
					/*
					if($db_act_ref_id == '4' || $db_act_ref_id == '3')
					{
						print "<td width=75><br/>&nbsp;</td>";
						print "<td>Current handicap : <input name=\"{$db_act_ref_id}-addon\" type=\"text\" size=2 value=\"". $db_act_addon ."\" ".($edittype==0?"Disabled":"")."></td>\n";
					}*/
					
					print "</tr>\n";
					
					print "</tr>\n";
					if($db_act_ref_id == '15')
					{
						print "<tr>";
						print "<td align=left><br><br><b>Current handicap : </b><input name=\"{$db_act_ref_id}-addon\" type=\"text\" size=2 value=\"". $db_act_addon ."\" ".($edittype==0?"Disabled":"")."></td>\n";
						print "</tr>\n";
					}
					//arthur- changing both the current handicaps
				} else {
					$db_act_addon="";
					if($db_act_ref_id != '3' && $db_act_ref_id != '4') {
					print "<tr  valign=\"top\"><td><input name='ck$db_act_ref_id' type='checkbox' value='1' ".($edittype==0?"Disabled":"").">$db_act_description</td>\n";
					}
					//print "<td>";
					/*
					if($db_act_ref_id == '4' || $db_act_ref_id == '3')
					{
						print "<td width=75><br/>&nbsp;</td>";
						print "<td>Current handicap : <input name=\"{$db_act_ref_id}-addon\" type=\"text\" size=2 value=\"". $db_act_addon ."\" ".($edittype==0?"Disabled":"")."></td>\n";
					}*/
					print "</tr>\n";
					print "</tr>\n";
					if($db_act_ref_id == '15')
					{
							print "<tr>";
							print "<td align=left><br><br><b>Current handicap :</b> <input name=\"{$db_act_ref_id}-addon\" type=\"text\" size=2 value=\"". $db_act_addon ."\" ".($edittype==0?"Disabled":"")."></td>\n";
							print "</tr>\n";
					}
				}
			}
			$marked=0;
		}
		print "</table>";
	}
	
	function generate_tranport_list($sel="") 
	{
		print "<select name='transport' class=fname1norm onchange=\"doPostBack(this)\">";
		print "<option value=''>Select Transport Mode</option>";
		$rm_q="select * from  ref_transport_tb";
		$rm_result = $this->doSQL($rm_q);
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_rm_name=$rm_row["transport_name"];
			$db_sh_name=$rm_row["transport_sh"];
			$db_rm_id=$rm_row["transport_id"];
			print "<option value='" .$db_sh_name. "' " . ($db_sh_name==$sel?"selected":" ") ." >".$db_rm_name."</option>";
		}
		print "</select>";
	}
	
	function generate_tranport2_list($sel="") 
	{	
		//print "&nbsp;&nbsp;";
		print "<select name='transport2' class=fname1norm onchange=\"doTransportRequirement(this)\">";		
	print "<option value=''>Select Transport Requirement</option>";
		print "<option value='1' " . (1==$sel?"selected":" ") ." >1-way : Hotel -> Airport</option>";
		print "<option value='2' " . (2==$sel?"selected":" ") ." >1-way : Airport -> Hotel</option>";
		print "<option value='3' " . (3==$sel?"selected":" ") ." >2-way</option>";
		print "</select>";
		print "&nbsp;&nbsp;";
	}
	
	function generate_venue_list($sel="") 
	{
		print "<select name='venue' class=fname1norm >";
		print "<option value=''".($sel==""?"selected":" ").">----- Select Venue -----</option>";
		$rm_q="select * from  ref_venue_tb";
		$rm_result = $this->doSQL($rm_q);
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_rm_name=$rm_row["ref_venue_name"];
			$db_sh_name=$rm_row["ref_venue_sf"];
			$db_rm_id=$rm_row["ref_venue_id"];
			print "<option value='" .$db_sh_name. "' " . ($db_sh_name==$sel?"selected":" ") ." >".$db_rm_name."</option>";
		}
		print "</select>";
	}
	
	function generate_bus_list($sel="") 
	{
		print "<select name='bus_type' class=fname1norm >";
		print "<option value='' ".($sel==""?"selected":" ").">----- Select Bus Type -----</option>";
		$rm_q="select * from bus_list";
		$rm_result = $this->doSQL($rm_q);
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_rm_name=$rm_row["bus_name"];
			$db_bus_date=strftime("%d-%m-%Y", strtotime($rm_row["bus_date"]));
			$db_bus_time=strftime("%H:%M", strtotime($rm_row["bus_time"]));
			$db_bus_space=$rm_row["bus_space"];
			$db_bus_destination=$rm_row["bus_destination"];
			$db_sh_name=$rm_row["bus_id"];
			$db_rm_id=$rm_row["bus_id"];
			print "<option value='" .$db_rm_id. "' " . ($db_sh_name==$sel?"selected":" ") ." >".$db_rm_name." / ".$db_bus_date." - ".$db_bus_time." / ".$db_bus_destination."</option>";
		} 
		print "</select>";
	}
	
	function get_customer_name($input_name="")
	{
		$rm_q="select * from cust_tb where cust_id='$input_name'";
		$rm_result = $this->doSQL($rm_q);
		if($rm_row=mysql_fetch_array($rm_result))
		{
			$db_cust_fam_name =$rm_row["family_name"];
			$db_cust_fir_name =$rm_row["first_name"];
			$db_cust_name=$rm_row["family_name"]." ".$rm_row["first_name"]." / ".$rm_row["country_name"] ;
		}
		else
			$db_cust_name="";
		return $db_cust_name;
	}
	
	function get_venue_name($input_name="")
	{
		$rm_q="select * from ref_venue_tb where ref_venue_sf='$input_name'";
		$rm_result = $this->doSQL($rm_q);
		if($rm_row=mysql_fetch_array($rm_result))
			$db_transport_name =$rm_row["ref_venue_name"];
		else
			$db_transport_name="";
		return $db_transport_name;
	}
	
	function get_transport_name($input_name="")
	{
		$rm_q="select * from ref_transport_tb where  transport_sh='$input_name'";
		$rm_result = $this->doSQL($rm_q);
		if($rm_row=mysql_fetch_array($rm_result))
			$db_transport_name =$rm_row["transport_name"];
		else
			$db_transport_name="";
		return $db_transport_name;
	}
	
	function get_room_name($input_name="")
	{
		$rm_q="select * from ref_room_tb where room_short_form='$input_name'";
		$rm_result = $this->doSQL($rm_q);
		if($rm_row=mysql_fetch_array($rm_result))
			$db_room_name =$rm_row["room_name"];
		else
			$db_room_name="";
		return $db_room_name;
	}

	function get_player_name($input_name="")
	{
		$listname=array(
		"T" => "Tournament Player",
		"CC" => "Country Captain",
		"GPRO" => "Golf Pro",
		"GCOM" => "Golf Committee",
		"OL" => "Opinion Leader",
		"O" => "Others",
		"G" => "Guest Player");
		if($input_name!="")
		{
			$input_name=strtoupper($input_name);
			$tb_player_name=$listname["$input_name"];
		}
		else
			$tb_player_name="";
		return $tb_player_name;
	}
	
	function get_airport_name($input_name="")
	{
		$listname=array(
		"DA" => "Brisbane Domestic",
		"BI" => "Brisbane International");
		if($input_name!="")
		{
			$input_name=strtoupper($input_name);
			$tb_player_name=$listname["$input_name"];
		}
		else
			$tb_player_name="";
		return $tb_player_name;
	}
		
	function generate_day($input_name="")
	{
		print $input_name;
		print "<OPTION value=0 ".($input_name==""?"selected":" ")."> </OPTION>\n";
		for($i=1;$i<=31;$i++)
		{
			print "<OPTION value=$i ".($input_name=="$i"?"selected":" ").">$i</OPTION>\n";
		}
	}
	
	function generate_month($input_name="")
	{
		print "<OPTION value=0 ".($input_name==""?"selected":" ")."> </OPTION>\n";
		for($i=8;$i<=9;$i++)
		{
// change starting year -- marc 20080521
			print "<OPTION value=$i ".($input_name=="$i"?"selected":" ").">".strftime("%b", strtotime($_SESSION['sThisYear'] . "-$i-01"))."</OPTION>\n";
		}
	}
	
	function generate_hour($input_name="")
	{
		print "<OPTION value=X ".(($input_name=="")?"selected":" ")."> </OPTION>";
		for($i=0;$i<=9;$i++)
		{
			print "<OPTION value=$i ".($input_name=="$i"?"selected":" ").">0".$i."</OPTION>\n";
		}
		for($i=10;$i<=23;$i++)
		{
			print "<OPTION value=$i ".($input_name=="$i"?"selected":" ").">".$i."</OPTION>\n";
		}
	}
	
	function generate_min($input_name="")
	{
		print "<OPTION value=X ".($input_name==""?"selected":" ")."> </OPTION>";
		for($i=0;$i<=9;$i++)
		{
			print "<OPTION value=$i ".($input_name=="$i"?"selected":" ").">0".$i."</OPTION>\n";
		}
		for($i=10;$i<=59;$i++)
		{
			print "<OPTION value=$i ".($input_name=="$i"?"selected":" ").">".$i."</OPTION>\n";
		}
	}
	
	function generate_year($input_name="")
	{
		print "<OPTION value=".$_SESSION['sThisYear']." selected>".$_SESSION['sThisYear']."</OPTION>";
	}
	
	function generate_log($input_name="")
	{
		print "<OPTION value=".$_SESSION['sThisYear']." selected>".$_SESSION['sThisYear']."</OPTION>";
	}
	
	function link_construction()
	{
		$sortlist="";
		$tempsortlist=$_GET;
		while (list($key, $val) = each($tempsortlist)) 
		{
			if($key=="od")
				$sortlist=$sortlist;
			else if($key=="st")
				$sortlist=$sortlist;
			else{
			$newdisplay=("&$key=$val");
			$sortlist=$sortlist.$newdisplay;
			}
		}
		return $sortlist;
	}
	/**
	*
	* Update the control table of the user. normally used by adding of records as it the only area which request which record to be control by who.
	* Record will stay even if its being deleted to the deleted table so tat the access rites are not change.
	*	
	* @param int record the control ID
	* @param string user name of user login
	* @access public
	*
	*/
	function user_control($record="")
	{
		$this->doUpdateSQL("insert into cust_holder set cust_id=$record , userp_uid=".$_SESSION["sUSERID"]." ,grp_id=\"".$_SESSION["sCONTROLLVL"]."\" ;");
	}

	function action_log($record="",$type="")
	{
		$this->doUpdateSQL("insert into delete_log set dlog_flag=\"".$type."\", dlog_tb_id=$record, dlog_user=\"".$_SESSION["sNick"]."\" , dlog_action=\"".$_GET["as"]."\", tb_name=\"".$_GET["tbn"]."\" ;");
	}

	function division_name($points)
	{
		if($points>0&&$points<=12)
			$division_nm="A";
		else if($points>=13&&$points<=20)
			$division_nm="B";
		else if($points>=21&&$points<=24)
			$division_nm="C";
		else
			$division_nm="-";
		return $division_nm;
	}
	
	function room_summary($querysqlarray="")
	{
		$totalcount=0;
		print "<TABLE border=0>";
		print "<TBODY>";
		print "<TR>";
		print "<TD class=ftitle1 align=right colspan=2><B>Room Total:</B></TD></TR>";
		$roomresult=$this->dosearchSQL("ref_room_tb","","room_short_form","inf");
		while($roomrow=mysql_fetch_array($roomresult))
		{
			if($querysqlarray=="")
				$roomcountresult=$this->dosearchSQL("cust_tb","room_short_form='".$roomrow["room_short_form"]."'","","inf");
			else
				$roomcountresult=$this->dosearchSQL($querysqlarray[0],$querysqlarray[1]."room_short_form='".$roomrow["room_short_form"]."'",$querysqlarray[2],$querysqlarray[3],$querysqlarray[4],$querysqlarray[5]);
			print "<TR>";	
			print "<TD class=fname1 align=right>".$roomrow["room_short_form"]." -</TD>";
			print "<TD class=fname1>".($roomcountresult?mysql_num_rows($roomcountresult):0)."&nbsp;</TD></TR>";
			print "<TR>";
			$totalcount=$totalcount+(($roomcountresult?mysql_num_rows($roomcountresult):0));
			if ($roomcountresult) mysql_free_result($roomcountresult);
		}
		print "<TD class=fname1 align=right><B>(Total) :</B></TD>";
		print "<TD class=fname1>$totalcount&nbsp;</TD></TR></TBODY></TABLE>";
	}

	function transport_summary($querysqlarray="")
	{
		$totalcount=0;
		print "<TABLE border=0>";
		print "<TBODY>";
		print "<TR>";
		print "<TD class=ftitle1 align=right colspan=2><B>Transport Total:</B></TD></TR>";
		$roomresult=$this->dosearchSQL("ref_transport_tb","","transport_sh","inf");
		while($roomrow=mysql_fetch_array($roomresult))
		{
			$temptp=array();
			$tempcount=0;
			if($querysqlarray=="")
				$roomcountresult=$this->dosearchSQL("cust_tb","","","inf");
			else
				$roomcountresult=$this->dosearchSQL($querysqlarray[0],$querysqlarray[1],$querysqlarray[2],$querysqlarray[3],$querysqlarray[4],$querysqlarray[5]);
			if ($roomcountresult)
				while($transportrow=mysql_fetch_array($roomcountresult))
				{
					$temptp=explode(":~~:",$transportrow["transport"]);
					if($temptp[0]==$roomrow["transport_sh"])
						$tempcount++;
				}
			print "<TR>";	
			print "<TD class=fname1 align=right>".$roomrow["transport_sh"]." -</TD>";
			print "<TD class=fname1>".$tempcount."&nbsp;</TD></TR>";
			print "<TR>";
			$totalcount=$totalcount+($tempcount);
			if ($roomcountresult) mysql_free_result($roomcountresult);
		}
		print "<TD class=fname1 align=right><B>(Total) :</B></TD>";
		print "<TD class=fname1>$totalcount&nbsp;</TD></TR></TBODY></TABLE>";
	}
		
	function activity_summary($querysqlarray="")
	{
		$totalcount=0;
		$actarray=array();
		$actnamearray=array();
		print "<TABLE border=0>";
		print "<TBODY>";
		print "<TR>";
		print "<TD class=ftitle1 align=right colspan=2><B>Activity Total:</B></TD></TR>";
		//$roomresult=$this->dosearchSQL("ref_act_tb","act_date>CURDATE()","act_name","inf");
		$roomresult=$this->dosearchSQL("ref_act_tb","act_date","act_name","inf");
		while($roomrow=mysql_fetch_array($roomresult))
		{
			$tempid=$roomrow["act_ref_id"];
			$tempname=$roomrow["act_name"];
			$actarray=array_merge($actarray,(array)array("x$tempid"=>0));
			$actnamearray=array_merge($actnamearray,(array)array("x$tempid"=>"$tempname"));
		}
				
		if($querysqlarray=="")
			$roomcountresult=$this->dosearchSQL("cust_tb","","","inf");
		else
			$roomcountresult=$this->dosearchSQL($querysqlarray[0],$querysqlarray[1],$querysqlarray[2],$querysqlarray[3],$querysqlarray[4],$querysqlarray[5]);
			
		if ($roomcountresult)
			while($transportrow=mysql_fetch_array($roomcountresult))
			{
				$tempcustid=$transportrow["cust_id"];
				$actcounterresult=$this->dosearchSQL("cust_act_tb","cust_id=$tempcustid","","inf");
				while($actcounterrow=mysql_fetch_array($actcounterresult))
				{
					$tempno=$actcounterrow["cust_act_no"];
					$tempactid=$actcounterrow["act_ref_id"];
					$actarray["x$tempactid"]=$actarray["x$tempactid"]+$tempno;
					$tempcount++;
				}
			}
		while (list($key, $val) = each($actnamearray)) {
			print "<TR>";	
			print "<TD class=fname1 align=right>".$val." -</TD>";
			print "<TD class=fname1>".$actarray["$key"]."&nbsp;</TD></TR></TD>";
		}
		print "</TBODY></TABLE>";
	}
	
	function room_date_summary($querysqlarray="")
	{
		$totalcount=0;
		$actarray=array();
		print "<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
		print "<TBODY>";
		print "<TR>";
		print "<TD class=ftitle1 align=center colspan=16><B>Room Summary ((Today BookIn) + (Current) = (Total for Today)):</B></TD></TR>";
		$arrival_dateresult=$this->dosearchSQL("cust_tb","","arrival_date ASC","inf","arrival_date","arrival_date");
		if($arrivalrow=mysql_fetch_array($arrival_dateresult))
		{
			$firstdate=strtotime($arrivalrow["arrival_date"]);
		}
		$depart_dateresult=$this->dosearchSQL("cust_tb","","depart_date DESC","inf","depart_date","depart_date");
		if($departrow=mysql_fetch_array($depart_dateresult))
		{
			$lastedate=strtotime($departrow["depart_date"]);
		}
		while($firstdate < ($lastedate+86400))
		{
			$rangesum_total=0;
			$numsum_total=0;
			ob_start();
			print "<TR>";
			print "<TD class=fname1 align=center colspan=2>&nbsp;";
			print strftime("%d-%m-%Y", $firstdate)."&nbsp;<br/></td>";
			$roomresult=$this->dosearchSQL("ref_room_tb","","room_name","inf");
			
			$_ttl = 0 ;
			while($roomrow=mysql_fetch_array($roomresult))
			{
				print "<TD class=fname1 align=right>&nbsp;";
				print $roomrow["room_short_form"]." -&nbsp;</td>";
				$numsum_result=$this->dosearchSQL("cust_tb",("arrival_date=\"".strftime("%Y-%m-%d", $firstdate)."\" and room_short_form=\"".$roomrow["room_short_form"]."\""),"","inf");
				$numsum_total=mysql_num_rows($numsum_result);
				$rangesum_result=$this->dosearchSQL("cust_tb",("(TO_DAYS('".strftime("%Y-%m-%d", $firstdate)."')- TO_DAYS(depart_date)) < 0 and TO_DAYS(arrival_date) - TO_DAYS('".strftime("%Y-%m-%d", $firstdate)."') <= 0 and room_short_form=\"".$roomrow["room_short_form"]."\""),"","inf");
				$rangesum_total=mysql_num_rows($rangesum_result);
				print "<TD class=fname1 align=left>&nbsp;";
				print $numsum_total." + ".($rangesum_total-$numsum_total) ." = ".$rangesum_total ;
				$_ttl += $rangesum_total ;
				print "&nbsp;</td>";
			}
			$firstdate=$firstdate+86400;
			print "</TR>";
			$_c = ob_get_contents();
			ob_end_clean();
			if ($_ttl>0) echo "$_c" ;
		}
			print "</TBODY></TABLE>";
	}
	
	function calculate_bus_space($bus_input)
	{
		$bus_total=0;
		$busresult=$this->dosearchSQL("bus_list","bus_id=$bus_input","","inf");
		if($busresult)
		{
			$busrow=mysql_fetch_array($busresult);
			$bus_taken_result=$this->dosearchSQL("cust_tee_bus_tb","bus_id=$bus_input","","inf");
			if($bus_taken_result)
			{
				while($bus_taken_row=mysql_fetch_array($bus_taken_result))
				{
					$bus_total=	$bus_total+ $bus_taken_row["person"];
				}
			}
			
			return ($busrow["bus_space"]-$bus_total);
		}
		else 
			return "0";
	}

	function generate_group_list($sel)
	{
// Update billing groups -- marc 20080521
		print "<select name='group' class='fname1norm' >\n";
		print "<option value=''".($sel==""?"selected":" ").">----- Select Group -----</option>\n";
		print "<option value='MGM01'".($sel=="MGM01"?"selected":" ").">Thailand</option>\n";
		print "<option value='MGM02'".($sel=="MGM02"?"selected":" ").">Indonesia</option>\n";
		//print "<option value='MGM03'".($sel=="MGM03"?"selected":" ").">Korea</option>\n";
		print "<option value='MGM04'".($sel=="MGM04"?"selected":" ").">Malaysia</option>\n";
		print "<option value='MGM05'".($sel=="MGM05"?"selected":" ").">Singapore</option>\n";
		print "<option value='MGM06'".($sel=="MGM06"?"selected":" ").">Sri Lanka</option>\n";
		print "<option value='MGM07'".($sel=="MGM07"?"selected":" ").">Taiwan</option>\n";
		print "<option value='MGM08'".($sel=="MGM08"?"selected":" ").">Vietnam</option>\n";
		print "<option value='MGM09'".($sel=="MGM09"?"selected":" ").">Australia</option>\n";
		print "<option value='MGM10'".($sel=="MGM10"?"selected":" ").">Germany</option>\n";
		print "<option value='MGM11'".($sel=="MGM11"?"selected":" ").">Philippines</option>\n";
		print "<option value='GRP02'".($sel=="GRP02"?"selected":" ").">Admins</option>\n";
		print "</select>";
	}

	function generate_cust_list($bus)
	{
?>
		<script type="text/javascript">
		var UseFirst_Solution=1;

		function MyListBox_Solution_selectAll(Fm, Target)
		{
			var Field = 'Fm.' + Target
			var len = eval(Field + '.length')
			var tmpstr = ','
			for (i=UseFirst_Solution ; i < len; i++)
			{
				eval(Field + '.options[i].selected=true')
				tmpstr += eval(Field + '.options[i].value') + ','
			}	
			eval('Fm.' + Target + '_result.value = "' + tmpstr + '"')
		}

		function getMySelectionBox_Solution_PickFrom(From, To)
		{
			// 1: There is first option
			// 0: There is no first option

			var len = From.length
			var j=0, ind;
			for (i=UseFirst_Solution ; i < len; i++)
			{
				if (From.options[i].selected==true)
				{
					To.options[To.options.length] = new Option(From.options[i].text, From.options[i].value)
					From.options[i].text = ''
					j++;
				}
			}

			ind = UseFirst_Solution;
			for (i=UseFirst_Solution ; i < len; i++)
			{
				ind++;
				if (From.options[i].text == '')
				{
					StopLoop=0
					while (ind < len && !StopLoop)
					{ 
						if (From.options[ind].text != '')
						{
							From.options[i].text = From.options[ind].text
							From.options[i].value = From.options[ind].value
							From.options[ind].text = ''
							StopLoop=1
						}
						else
							ind++;
					}
				}
			}
			From.options.length -= j
		}
	</script>
	<form name="AddTee" method="post" action="">
	<table>
	<tr><td colspan=3 class=fname1norm>Please select the date, time, tee group, players and click on <b>Add Tee-Off</b> button.<br/><br/></td></tr>
	<tr>
	<td class=fname1norm valign=top colspan=3>
	Date : <select name='view_date' class=fcommentsdark>
	<option value=''  selected >--------- Date ---------</option>
<?
// change date accordingly -- marc 20080521
		$startcounter=strtotime($match_first_day);
		$endcounter=strtotime($match_last_day);
		while($startcounter<$endcounter)
		{
			if($endcounter==strtotime($match_first_day))
				$showlocation="Hope Island Golf Course";
			if($endcounter==strtotime($match_second_day))
				$showlocation="Lakelands Golf Club";
			if($endcounter==strtotime($match_last_day))
				$showlocation="Hope Island Golf Course";
			print "<option value='".$endcounter.":~:$showlocation'>".strftime("%d-%m-%Y -%a",$endcounter)." - ".$showlocation."</option>\n";
			$endcounter=$endcounter-86400;
		}
?>
	</select>&nbsp;
	Tee Off Time : <select name=tee_time_hr><?$this->generate_hour((isset($_POST["tee_time_hr"])?$_POST["tee_time_hr"]:""));
	?></select> : <select name=tee_time_min><?$this->generate_min((isset($_POST["tee_time_min"])?$_POST["tee_time_min"]:""));?></select>&nbsp;
	<!--
	Location : <select name=location>
	<option value='' selected>  ----------  Location  ---------- </option>
	<option value='Hope Island Golf Course' >Hope Island Golf Course</option>
	<option value='Lakelands Golf Club' >Lakelands Golf Club</option>
	</select>
	-->
	<br/><br/>
	Tee Group: <input name=tee_hole type=text size=1 maxlength=3>
	</td>
	</tr>
	<tr><td>&nbsp;<td></tr>
	<tr>
	<td class=fname1norm valign=top>Golf Players</td>
	<td class=fname1norm valign=top>&nbsp;</td>
	<td class=fname1norm valign=top>&nbsp;</td>
	</tr>
	<tr>
	<td class=fname1norm valign=top>
<?
		print "<select id=\"teeoff_player\" style=\"WIDTH: 250px\" class=fname1norm size=\"10\" multiple ><OPTION>--- Players ---</OPTION>";
		$cus_q="select * from cust_tb order by country_name ASC,player_type ASC, family_name ASC, first_name ASC";
		$cus_result = $this->doSQL($cus_q);
		while($cus_row=mysql_fetch_array($cus_result))
		{
			$db_family_name=$cus_row["family_name"];
			$db_first_name=$cus_row["first_name"];
			$db_player_type=$cus_row["player_type"];
			$db_country_name=$cus_row["country_name"];
			$db_cust_id=$cus_row["cust_id"];
			print "<option value=\"" .$db_cust_id.":~:".$db_family_name.":~:".$db_first_name. ":~:".$db_player_type."\" " . ($db_cust_id==$sel?"selected":" ") ." > ".$db_country_name." / ".$db_family_name." ".$db_first_name." - ".$db_player_type." </option>";
		} 
		print "</select>";
?>
	<td class=fname1norm valign=top align=center >
		<A href="javascript:getMySelectionBox_Solution_PickFrom(document.AddTee.teeoff_player, document.AddTee.id_players);">Select &gt;&gt;</A> <BR><BR><BR>
		<A href="javascript:getMySelectionBox_Solution_PickFrom(document.AddTee.id_players, document.AddTee.teeoff_player);">&lt;&lt; Remove</A></td>
	<td class=fname1norm valign=top align=right>
	<select style="WIDTH: 200px" multiple size=6 id=id_players name=players[]>
	<OPTION>--- SELECTED ---</OPTION></select></td>
	</tr>
	<tr>
	<td>
	<br/>
	<input type="submit" name="TeeAddMulti" value="Add Tee-Off" onclick="MyListBox_Solution_selectAll(AddTee, 'id_players');">
	</td>
	</tr>
	</table>
	</form>
	<hr>
	Please select the time of the venue and click on <b>Change Time</b> button.
	<form name="ChangeTee" method="post" action="">
	<table>
	<tr>
	<td class=fname1norm>
<?
		print "<select name=\"time_location\" style=\"WIDTH: 250px\" class=fname1norm ><OPTION>--- Time & Location ---</OPTION>";
		$teetime_loc= $this->doSearchSQL("cust_tee_tb","tee_venue=\"Lakelands Golf Club\"","tee_date","inf","*","tee_time");
		while($teeloc_result=mysql_fetch_array($teetime_loc))
		{
			$tttime=$teeloc_result["tee_time"];
			$ttloc=$teeloc_result["tee_venue"];
			$ttdate=$teeloc_result["tee_date"];
			print "<option value=\"" .$tttime.":~:".$ttloc.":~:".$ttdate."\" " . ($db_cust_id==$sel?"selected":" ") ." > ".strftime("%H:%M",strtotime($tttime))." / ".$ttloc." / " .strftime("%d-%m-%Y",strtotime($ttdate))."</option>";
		}
		$teetime_loc= $this->doSearchSQL("cust_tee_tb","tee_venue=\"Hope Island Golf Course\"","tee_date","inf","*","tee_time");
		while($teeloc_result=mysql_fetch_array($teetime_loc))
		{
			$tttime=$teeloc_result["tee_time"];
			$ttloc=$teeloc_result["tee_venue"];
			$ttdate=$teeloc_result["tee_date"];
			print "<option value=\"" .$tttime.":~:".$ttloc.":~:".$ttdate."\" " . ($db_cust_id==$sel?"selected":" ") ." > ".strftime("%H:%M",strtotime($tttime))." / ".$ttloc." / ".strftime("%d-%m-%Y",strtotime($ttdate))." </option>";
		}
?>
	</select>&nbsp;<br/><br/>
	New Tee Off Time : <select name=new_tee_time_hr><?$this->generate_hour((isset($_POST["tee_time_hr"])?$_POST["tee_time_hr"]:""));
	?></select> : <select name=new_tee_time_min><?$this->generate_min((isset($_POST["tee_time_min"])?$_POST["tee_time_min"]:""));?></select>&nbsp;
	<input type="submit" name="ChangeTime" value="Change Time">
	</td>
	</tr>
	</table>
	</form>
<?
	}
	
	/*
	* send alert email for $state changes done by $admin at $time to player $name
	* marc -- 20070521
	*/
	function alert_email ($time, $name, $country, $admin, $state)
	{
// XXX Update email addresses for the alerts -- marc 20080521
		$recepient = "Eunice Kwek<eunicekwek18@gmail.com>,Eunice Kwek<eunice.kwek@daimler.com>,Nicolas Katz <nicholas.katz@daimler.com>";
		//$recepient = "terence.lai@synergyitc.com";

		$subject = "Email Alert - Mercedes Trophy Asian Final " . $_SESSION['sThisYear'];
		
		$header = "From: Mercedes Trophy Asian Final <debug@synergyitc.com>
Bcc: Synergy ITC <debug@synergyitc.com>, TL <terence.lai@synergyitc.com>
Date: " . date('r') . "
MIME-Version: 1.0
Content-Type: text/html; charset=utf-8
Content-Transfer-Encoding: 7bit
X-Mailer: PHP/" . phpversion();

		$message = "<html>
<head><title></title>
<style>
body {
color : #258;
background-color : #fff;
font-size : 11px;
font-family : verdana, arial;
}
.footer {
color : #666;
font-size : 10px;
font-style : italic;
border-top-style : solid;
border-top-width : 1px;
botder-top-color : #666;
}
</style>
</head>
<body>
<p><b>Participant</b>: $name<br>
<b>Country</b>: $country</p>

<p><b>Action</b>: $state by $admin at $time</p>
<div class='footer'>This email has been sent by MercedesTrophy Asian Final {$_SESSION['sThisYear']} Registration System</div>
</body>
</html>";
		
		mail($recepient, $subject, $message, $header);
	}
}
?>
