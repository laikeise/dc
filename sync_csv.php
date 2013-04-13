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
	if(isset($_POST["Sync"])){
		/*
		print_r($HTTP_POST_VARS);
		for($i=11;$i<=20;$i++){
			print "$i ->".(isset($HTTP_POST_VARS[$i])?$HTTP_POST_VARS[$i]:"")."<br/>";
		}
		*/
			$actionact=0;
			$tempsql="";
			$updarow=0;
			$file_path = $_G["UPLOAD_DIR"].$HTTP_POST_VARS["fileid"];
    		$file_url  = $_G["UPLOAD_URL"].$HTTP_POST_VARS["fileid"];
    		//$result  =  move_uploaded_file($temp_name, $file_path);
    		//print $result;
    		//$message = ($result)?"File url <a href='$file_url'>$file_url</a> $file_type ":"Somthing is wrong with uploading a file.";
    		
    		$row=1;
    		$handle = fopen($file_url, "r");
    		
    		?>
<!--    		<form name=formupdate action="index.php?s=sync_csv&tbn=cust_tb&as=adddata" method=post>-->
    		<table cellSpacing=0 cellPadding=2 width="100%" border=1>
    		<?
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
   				$num = count($data);
   				$tempsql="";
   				$tempid="";
   				//echo "<p> $num fields in line $row: <br />\n";
   				$row++;
   				if($row>=9&& $row<=20){
   					$actionmsg="&nbsp;";
   					$actionact=0;
   				//need to do another loop to skip if sal,first,family name not insert	
   				//$data[1] $data[2]
   				if($data[1]=="" || $data[2]=="")
   				{
   					
   				}
   				else
   				{
   					if($row!=9 &&$row!=10){
   						//print $row;
   						$chksql="select cust_id,salutation, family_name, first_name , player_type , country_name , arrival_date , arrival_time , arrival_flight , depart_date , depart_time , depart_flight ,transport , room_short_form , comment   from cust_tb where family_name=\"".$data[1]."\" and  first_name=\"".$data[2]."\"";
   						//print $chksql;
   						$resultx=$User_Info->doSQL($chksql);
						if($qrow=mysql_fetch_array($resultx)){
							$actionmsg="Duplicate";
							$actionact=1;
							$tempid=$qrow[0];
						}
						else
							$actionmsg="New";
					}
				echo "<tr>";
   				for ($c=0; $c < $num; $c++) {
					//Data verification module, cut and paste
					if(isset($qrow))
					{
						//print $c;
						if(is_array($qrow)){
							
						if($c==5||$c==8){
							$data[$c]=strftime("%Y-%m-%d", strtotime($data[$c]));
							$qrow[$c+1]=strftime("%Y-%m-%d", strtotime(($qrow[$c+1])));
						}
						else if($c==6||$c==9){
							$data[$c]=strftime("%H:%M", strtotime($data[$c]));
							$qrow[$c+1]=strftime("%H:%M", strtotime(($qrow[$c+1])));
						}
						else if($c==7||$c==10||$c==3){
							$data[$c]= strtoupper($data[$c]);
						}
						
						
						}
						else{
						
						if($c==5||$c==8){
							
							$data[$c]=strftime("%Y-%m-%d", strtotime($data[$c]));
						}
						else if($c==6||$c==9){
							
							$data[$c]=strftime("%H:%M", strtotime($data[$c]));
						}
						else if($c==7||$c==10||$c==3){
							
							$data[$c]= strtoupper($data[$c]);
						}
						
						}
						
					}
       				//echo $c." ".$data[$c] . " -- ";
       				//echo "<td>".($data[$c]!=""?$data[$c]:"&nbsp;")." <font color='FFFFFF'>".($actionact==1&&$qrow[$c+1]!=$data[$c]?$qrow[$c+1]:"") ."</font></td>\n";
       				if($actionact==1&&$qrow[$c+1]!=$data[$c]){
       					echo "<td>".($qrow[$c+1]!=""?$qrow[$c+1]:"&nbsp;")." <font color='FFFFFF'>".($actionact==1&&$qrow[$c+1]!=$data[$c]?$data[$c]:"") ."</font></td>\n";
       					$actionmsg="Changed";
       					print "Error - " . $data[$c]. " -- " . $qrow[$c+1];
       					$tempsql=$tempsql.$tblist[$c+1]."='".$data[$c]."', ";
       				}
       				else
       					echo "<td>".($data[$c]!=""?$data[$c]:"&nbsp;")." <font color='FFFFFF'>".($actionact==1&&$qrow[$c+1]!=$data[$c]?$qrow[$c+1]:"") ."</font></td>\n";
       				//<input type="checkbox" name="$row" value="checkbox" />
       				
   				}
   				if($row==9)
   					echo "<td>Action</td></tr>";
   				else if($row==10)
   					echo "<td>&nbsp;</td></tr>";
   				else{
   					echo "<td>&nbsp;";
   					
   		
   					if(isset($HTTP_POST_VARS[$row]))
   					{
   						
   					if($actionmsg=="Changed"){
   						$tempsql="Update cust_tb set ".$tempsql."cust_id=".$tempid ." where cust_id=".$tempid.";";
   					}
   					else if($actionmsg=="New"){
   						$day_stay=((strtotime($data[8]))-(strtotime($data[5])))/86400;
						$tempsql="insert into cust_tb values (NULL,\"".$data[0]."\",\"".$data[1]."\",\"".$data[2]."\",\"".$data[3]."\" , \"".$data[4]."\"  ,\"".$day_stay."\" ,\"".$data[5]."\",\"".$data[6]."\",\"".$data[7]."\",\"".$data[8]."\",\"".$data[9]."\",\"".$data[10]."\",\"".$data[11]."\",\"".$data[12]."\",\"".$data[13]."\" );";
   					}
	   					print $tempsql;
	   					if($User_Info->doUpdateSQL($tempsql)){
   							$updarow++;
   							print "Added";
   						}
   						else
   							print "No ActionX";

   					}
   					else
   						print "No Action";
   					echo "</td></tr>\n";
   				}
   				
   				}//if of data check.
   				//print "<br/>";
   				}//9 to 20 loop
			}
			//$tempinsertid
			//print "<input type=hidden name=fileid value='$tempinsertid'>";
			?>
			</table>
			

<!--			<DIV align=center><INPUT class=ButtonStyle type=submit value="Sync Now" name=Sync>&nbsp;&nbsp; 
			<INPUT class=ButtonStyle onclick="location.href='index.php?s=admin';" type=button value=Cancel>&nbsp;&nbsp; 
            </DIV>
-->
			</form>
			<?			
			fclose($handle);
			print $updarow ." records have been added!";
		
	}
}
?>
<DIV align=center>
			<INPUT class=ButtonStyle onclick="location.href='index.php?s=admin';" type=button value=Ok>&nbsp;&nbsp; 
            </DIV>