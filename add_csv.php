<?php 

include_once("AustGolf_Addon.php");
$AustGolf_Addon=new AustGolf_Addon();
//set up of the tmp structures
	$tmptb=((isset($_GET["tbn"]))?$_GET["tbn"]:"");
	$tmpcolnum=((isset($_GET["tcol"]))?$_GET["tcol"]:"");
	$tmpcolname=((isset($_GET["id"]))?$_GET["id"]:"");
//print $tmpcolnum." ".$tmpcolname;
$tblist=$User_Info->getFieldName("$tmptb");
//print $_GET["tbn"]."b<br/>";
//do the access checks here
if($Site_Info->check_TBAction($_SESSION["sCONTROLLVL"],$_GET["tbn"],$_GET["as"])){
	
	if(isset($_POST["Add"])){
		$site_name = $_SERVER['HTTP_HOST'];
		//$url_dir = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		//$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

		//$upload_dir = "archive/files/";
		//$upload_url = $url_dir."archive/files/";
		$message ="";
	
		//print $upload_url;
		/*
		if (!is_dir($upload_url)) {
			die (" upload_files directory doesn't exist!");
		}
		*/
		if ($_FILES['userfile']) {
			$temp_name = $_FILES['userfile']['tmp_name'];
			$file_name = $_FILES['userfile']['name']; 
			$file_type = $_FILES['userfile']['type']; 
			$file_size = $_FILES['userfile']['size']; 
			$result    = $_FILES['userfile']['error'];
			
			//File Name Check
    		if ( $file_name =="") { 
    			$message = "Invalid File Name Specified";
    		}
    	//File Size Check
    	else if ( $file_size > 5000000) {
        	$message = "The file size is over 5000K.";
    	}
		 //File Type Check
    	else if ( $file_type != "application/octet-stream" || !preg_match("/.csv$|.txt$/i", $file_name)){
        	$message = "Sorry, You can only upload CSV files" ;
        }
		else
		{
			
			$csvsql = "insert into csv_file values (NULL,'$file_name',$file_size,'$file_type','".$_SESSION["sNick"]."',NULL,NULL);";
			//print $csvsql;
			$updarow=($User_Info->doUpdateSQL($csvsql));
			
			print $updarow." records have been added.";
    		$tempinsertid=mysql_insert_id();
    		$file_path = $_G["UPLOAD_DIR"].$tempinsertid;
    		$file_url  = $_G["UPLOAD_URL"].$tempinsertid;
    		$result  =  move_uploaded_file($temp_name, $file_path);
    		//print $result;
    		$message = ($result)?"File url <a href='$file_url'>$file_url</a> $file_type ":"Somthing is wrong with uploading a file.";
    		
    		$row=1;
    		$handle = fopen($file_url, "r");
    		
    		?>
    		<form name=formupdate action="index.php?s=sync_csv&tbn=cust_tb&as=adddata" method=post>
    		<table cellSpacing=0 cellPadding=2 width="100%" border=1>
    		<?
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
   				$num = count($data);
   				//echo "<p> $num fields in line $row: <br />\n";
   				$row++;
   				if($row>=9&& $row<=20){
   					$actionmsg="&nbsp;";
   					$actionact=0;
   					$actioncolor="black";
   				//need to do another loop to skip if sal,first,family name not insert	
   				//$data[1] $data[2]
   				if($data[1]=="" || $data[2]=="")
   				{
   					
   				}
   				else
   				{
   					if($row!=9 &&$row!=10){
   						//print $row;
   						$chksql="select cust_id,salutation, family_name, first_name , player_type , country_name , arrival_date , arrival_time , arrival_flight , depart_date , depart_time , depart_flight ,transport , room_short_form , comment  from cust_tb where family_name=\"".$data[1]."\" and  first_name=\"".$data[2]."\"";
   						$qrow=0;
   						//print $chksql;
   						$resultx=$User_Info->doSQL($chksql);
						if($qrow=mysql_fetch_array($resultx)){
							$actionmsg="Duplicate";
							$actionact=1;
						}
						else
						{
							$actionmsg="New";
							$actioncolor="green";
							//$qrow=0;
						}
					}
				echo "<tr>";
   				for ($c=0; $c < $num; $c++) {
   					/*
   					if(isset($qrow))
   					{
   					if(is_array($qrow)){
       				print $qrow[$c+1]." ~ ".$data[$c];
       				}
       				}
       			*/
       			
					//Data verification module, cut and paste
					if(isset($qrow))
					{
						//print $c;
						if(is_array($qrow)){
						if($c==5){
							//strftime("%H:%M", strtotime($qrow["depart_time"]))
							$data[$c]=strftime("%d-%m-%Y", strtotime($data[$c]));
							$qrow[$c+1]=strftime("%d-%m-%Y", strtotime(($qrow[$c+1])));
							
						}
						else if($c==6){
							//strftime("%H:%M", strtotime($qrow["depart_time"]))
							$data[$c]=strftime("%H:%M", strtotime($data[$c]));
							$qrow[$c+1]=strftime("%H:%M", strtotime(($qrow[$c+1])));
						}
						else if($c==8){
							//strftime("%H:%M", strtotime($qrow["depart_time"]))
							$data[$c]=strftime("%d-%m-%Y", strtotime($data[$c]));
							$qrow[$c+1]=strftime("%d-%m-%Y", strtotime(($qrow[$c+1])));
						}
						else if($c==9){
							//strftime("%H:%M", strtotime($qrow["depart_time"]))
							$data[$c]=strftime("%H:%M", strtotime($data[$c]));
							$qrow[$c+1]=strftime("%H:%M", strtotime(($qrow[$c+1])));
						}
						
						}
						else{
						
							if($c==5){
							//strftime("%H:%M", strtotime($qrow["depart_time"]))
							$data[$c]=strftime("%d-%m-%Y", strtotime($data[$c]));
						}
						else if($c==6){
							//strftime("%H:%M", strtotime($qrow["depart_time"]))
							$data[$c]=strftime("%H:%M", strtotime($data[$c]));
						}
						else if($c==8){
							//strftime("%H:%M", strtotime($qrow["depart_time"]))
							$data[$c]=strftime("%d-%m-%Y", strtotime($data[$c]));
						}
						else if($c==9){
							//strftime("%H:%M", strtotime($qrow["depart_time"]))
							$data[$c]=strftime("%H:%M", strtotime($data[$c]));
						}
						
						}
						
					}
					
					
       				if($actionact==1&&$qrow[$c+1]!=$data[$c]){
       					echo "<td>".($qrow[$c+1]!=""?$qrow[$c+1]:"&nbsp;")." <font color='FFFFFF'>".($actionact==1&&$qrow[$c+1]!=$data[$c]?$data[$c]:"") ."</font></td>\n";
       					$actionmsg="Changed";
       					$actioncolor="gray";
       					//print "Error - " . $data[$c]. " -- " . $qrow[$c+1];
       				}
       				else
       					echo "<td>".($data[$c]!=""?$data[$c]:"&nbsp;")." <font color='FFFFFF'>".($actionact==1&&$qrow[$c+1]!=$data[$c]?$qrow[$c+1]:"") ."</font></td>\n";
       				//<input type="checkbox" name="$row" value="checkbox" />
   				}
   				if($row==9)
   					echo "<td>sync?</td><td>Action</td></tr>";
   				else if($row==10)
   					echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
   				else{
   					echo "<td><input type='checkbox' name='$row' value='1'".($actionmsg=="Changed"||$actionmsg=="New"?"checked":"") ." /></td>\n<td><span style=color:$actioncolor;>$actionmsg</span></td></tr>\n";
   				}
   				
   				}//else
   			
				}//if 9 to 20 loop
			}
			//$tempinsertid
			print "<input type=hidden name=fileid value='$tempinsertid'>";
			?>
			</table>
			<DIV align=center><INPUT class=ButtonStyle type=submit value="Sync Now" name=Sync>&nbsp;&nbsp; 
			<INPUT class=ButtonStyle onclick="location.href='index.php?s=admin';" type=button value=Cancel>&nbsp;&nbsp; 
            </DIV>
			</form>
			<?			
			fclose($handle);
		}
	}
	else{
		$message = "Invalid File Specified.";
	}
	//print $message;

	}
	else{
?>

Add CSV Page
<br />
<?php 
	require_once("classes/Addon.php");
	$DateFormat= new Addon;
	$rmtotal=0;
	//print $tmptb;
	//$tblist=$User_Info->getFieldName("$tmptb");
	//print_r($tblist);
	//print $tmpcol."test";
	//print $atb[$tmpcol]."test";
	//print $tmpcol;
	//$resultx= $User_Info->dosearchSQL("$tmptb","$tmpcol=$tmpcolname","","");
	print "<br />";
	/*
	print "<form name='formupdate' method='post' action='".$_SERVER["REQUEST_URI"]."'>\n";
	print "<table cellSpacing=0 cellPadding=2 width='100%' border=1>\n";
	//print "<input type='hidden' name='$tmpcolnum' value='".$tmpcolname."' size='20'>";
	
	while (list($key, $val) = each($tblist)) {
			
   			if($key==0)
   			{
   				print "<input type='hidden' name='$val' value='' >";
   			}
   			else{
   			
   				if($val==$tmpcolnum){
   					//print  "<tr>\n<td valign=top> " . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n<td valign=top>";
   					print "<input type='hidden' name='$val' value='".$tmpcolname."' >";}
   				else{
   					print  "<tr>\n<td valign=top class=ftitle1 align=right width='20%'> " . (!empty($htmldisrep["$val"])?$htmldisrep["$val"]:$val). "</td>\n<td valign=top class=fname1 align=left>";
   					//print "<input type='text' name='$val' value='".$htmltxt."' size='20'>";}
   					print "<input type='text' name='$val' size='100'>";
   				}
   			
   				//print "<textarea name='$val' cols='50' rows='5'>".$htmltxt."</textarea>";
   				print "</td>\n<td valign=top class=ftitle1 align=right width='10%'>&nbsp;</td></tr>\n";
   			}
   	}
   	
	print "</table>";
	print "<input type='submit' name='Add' value='Submit'>";
    print "<input type='reset' name='Resetxxx' value='Reset'>";
	print "</form>\n";
	*/
	?>
	<FORM name=formupdate action=<?=$_SERVER["REQUEST_URI"]; ?> method=post encType=multipart/form-data>
	<TABLE cellSpacing=0 cellPadding=3 width="100%" align=center border=1>
		<TR>
			<TD class=fname vAlign=top align=right width="20%">CSV File Upload</TD>
                <TD class=fvalue><SPAN class=fcomments>You must use the MS Excel template file provided to you. Make sure it is saved as 
                  a .cvs file before uploading: In MS Excel, select "File" from 
                  the menu bar.<BR>Select "Save As...".<BR>Select "CSV (comma 
                  delimited)" under "Save as type"<BR>Make sure the file has an 
                  extension of ".csv". </SPAN>
                <INPUT class=TextBoxStyle style="WIDTH: 100%" type=file name=userfile> 
                </TD>
			<TD class=fname vAlign=top align=right width="10%">
                <IMG alt="This is a required field." src="images/tick.gif"  border=0> &nbsp;
			</TD>
		</TR>
                  
              <!--
              <TR>
                <TD class=fname vAlign=top align=right width="20%">Recreate 
                  Group?</TD>
                <TD class=fvalue><SPAN class=fcomments></SPAN><INPUT 
                  type=radio value=1 name=RecreateAction> Yes&nbsp;&nbsp; <INPUT 
                  type=radio CHECKED value=0 name=RecreateAction> No&nbsp;&nbsp; 
                  <SPAN class=fcomments><BR>Yes: Remove all guest information in 
                  the uploaded group (detected from the uploaded file) from the 
                  current database.<BR>No: Update guest information in the 
                  current database with the data in the uploaded file (you may 
                  preview and select which guest's data to update).</SPAN> </TD>
                <TD class=fname vAlign=top align=right width="10%">&nbsp;</TD></TR>
                -->
	</TABLE><BR><BR>
<DIV align=center><INPUT class=ButtonStyle onclick="if (file_CsvFile.value=='0') {alert('<CSV File Upload> is a mandatory field !');file_CsvFile.focus(); return false;} ;" type=submit value=Add name=Add>&nbsp;&nbsp; 
<INPUT class=ButtonStyle onclick="location.href='index.php?s=admin';" type=button value=Cancel>&nbsp;&nbsp; 
            </DIV></FORM>
	<?
	
	
	}
?><br /><br />


<?php
}
else{
	//$User_Info->go_page("error");
}

?>