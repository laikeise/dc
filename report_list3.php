<?php 
include_once("AustGolf_Addon.php");
include_once("shortname.php");
$AustGolf_Addon=new AustGolf_Addon();
require_once("classes/Addon.php");
$Paging = new Addon;

if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"]))
{ 
//if (!isset($_POST['export_hotel_room'])){ ?>
	<!--<form name="ViewGroup" method="post" action="">
	<table border="0" cellspacing="0" cellpadding="5" align="center"><tr><td>
	<center>Export this Report into Excel:&nbsp;<input type="submit" name="export_hotel_room" value="Export"/></center>
	</td></tr></table></form>-->
<?//}
	if(isset($_POST['export_hotel_room']) && $_POST['export_hotel_room']=="Export"){
		$filename="Hotel_room".date("Ymd");
		header("Content-type: application/vnd.ms-excel"); 
		//header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".$filename.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		}
    	$totalcount=0;
    	$actarray=array();
    	/*
    	print "<div align=center class=ftitle5>Hotel Room Allocations</div>";
    	print "<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
		print "<TBODY>";
		print "<TR><TD class=ftitle1 align=center colspan=6><B>Room Summary ((Today BookIn) + (Current) = (Total for Today)):</B></TD></TR>";
		
		print "<TR>
		<TD class=ftitle1 align=center width=10%>DATE</TD>
		<TD class=ftitle1 align=center >DDN</TD>
		<TD class=ftitle1 align=center >DDS</TD>
		<TD class=ftitle1 align=center >DKN</TD>
		<TD class=ftitle1 align=center >DKS</TD>
		<TD class=ftitle1 align=center >TOTAL</TD>
		</TR>";
		*/
		
		$xlsoutput = "
		<div align=center class=ftitle5>Hotel Room Allocations</div>
    	<table width=100% border=1 cellspacing=0 cellpadding=0>
		<TBODY>
		<TR><TD class=ftitle1 align=center colspan=8><B>Room Summary ((Today BookIn) + (Current) = (Total for Today)):</B></TD></TR>
		<TR>
		<TD class=ftitle1 align=center width=10%>DATE</TD>
		";
    		$roomresult=$User_Info->dosearchSQL("ref_room_tb","room_short_form!='NA'","room_name","inf");
    		while($roomrow=mysql_fetch_array($roomresult)) {
			$xlsoutput .= "<TD class=ftitle1 align=center >".$roomrow["room_short_form"]."</TD>" ;
		}
		$xlsoutput .= "<TD class=ftitle1 align=center >TOTAL</TD></TR>" ;
		
		$arrival_dateresult=$User_Info->dosearchSQL("cust_tb","","hotelcheckindate ASC","inf","hotelcheckindate","hotelcheckindate");
		if($arrivalrow=mysql_fetch_array($arrival_dateresult))
		{
			$firstdate=strtotime($arrivalrow["hotelcheckindate"]);
		}
		$depart_dateresult=$User_Info->dosearchSQL("cust_tb","","hotelcheckoutdate DESC","inf","hotelcheckoutdate","hotelcheckoutdate");
		if($departrow=mysql_fetch_array($depart_dateresult))
		{
			$lastedate=strtotime($departrow["hotelcheckoutdate"]);
		}
		while($firstdate < ($lastedate+86400))
    	{
    		$rangesum_total=0;
    		$numsum_total=0;
    		//print "<TR>";
			//print "<TD class=ftitle1 align=center width=10>";
    		//print strftime("%d-%m-%Y", $firstdate)."<br/></td>";
    		
		$ttlcnt = 0;
		ob_start();
    		print "
    		<TR>
			<TD class=ftitle1 align=center width=10%>
    		".strftime("%d-%m-%Y", $firstdate)."<br/></td>
    		";
    		
    		$roomresult=$User_Info->dosearchSQL("ref_room_tb","room_short_form!='NA'","room_name","inf");
    		$roomstt=0;

    		while($roomrow=mysql_fetch_array($roomresult))
    		{
    			$numsum_result=$User_Info->dosearchSQL("cust_tb",("hotelcheckindate=\"".strftime("%Y-%m-%d", $firstdate)."\" and room_short_form=\"".$roomrow["room_short_form"]."\""),"","inf");
    			$numsum_total=mysql_num_rows($numsum_result);
    			$rangesum_result=$User_Info->dosearchSQL("cust_tb",("(TO_DAYS('".strftime("%Y-%m-%d", $firstdate)."')- TO_DAYS(hotelcheckoutdate)) < 0 and TO_DAYS(hotelcheckindate) - TO_DAYS('".strftime("%Y-%m-%d", $firstdate)."') <= 0 and room_short_form=\"".$roomrow["room_short_form"]."\""),"","inf");
    			$rangesum_total=mysql_num_rows($rangesum_result);
    			
    			$chkvalue = $numsum_total + ($rangesum_total-$numsum_total);
    			if($chkvalue!=0)
    			{
    				print "
    				<TD class=fname1 align=center>
    				". $numsum_total." + ".($rangesum_total-$numsum_total) ." = <b>".$rangesum_total ."</b>
    				</td>
    				";
				$ttlcnt += $rangesum_total;
			}	
			else
			{
				print "<TD class=fname1 align=center>-</td>";
			}
			$roomstt += $rangesum_total;
		}
			
		if($roomstt!=0)
    		{
    			print "<TD class=fname1 align=center><b>".$roomstt ."</b></td>";
    		}	
		else
		{
			print "<TD class=fname1 align=center>-</td>";
		}
					
		$firstdate=$firstdate+86400;
		print "</tr>";
		$_c = ob_get_contents();
		ob_end_clean();
		if ($ttlcnt>0) $xlsoutput .= $_c ;

		}
			//print "</TBODY></TABLE>";
			$xlsoutput .= "</TBODY></TABLE>";

			print $xlsoutput;
			//header("Location: $PHP_SELF?action=download");
			//if ($action=="downloadExcel")
			//{
				/*
				header("Content-type: application/x-msdownload");
				header("Content-Disposition: attachment; filename=extraction.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				print "$xlsoutput";
				*/ 
			//}
			
}
else
{
	$User_Info->go_page("error");
}
?>
