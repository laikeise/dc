 <?
 //Preparing the data
$data = array( 
array("firstname" => "Mary", "lastname" => "Johnson", "age" => 25), 
array("firstname" => "Amanda", "lastname" => "Miller", "age" => 18), 
array("firstname" => "James", "lastname" => "Brown", "age" => 31), 
array("firstname" => "Patricia", "lastname" => "Williams", "age" => 7), array("firstname" => "Michael", "lastname" => "Davis", "age" => 43), 
array("firstname" => "Sarah", "lastname" => "Miller", "age" => 24), 
array("firstname" => "Patrick", "lastname" => "Miller", "age" => 27) 
);

//echo"<pre>"; var_dump($data); echo "</pre>";

 function cleanData(&$str) { 
	$str = preg_replace("/\t/", "\\t", $str); 
	$str = preg_replace("/\r?\n/", "\\n", $str);
	}  

header("Content-Type: text/plain"); 
 # filename for download 
/*$filename = "website_data_" . date('Ymd') . ".xls"; 
header("Content-Disposition: attachment; filename=\"$filename\""); 
header("Content-Type: application/vnd.ms-excel"); */

 $flag = false; 
 foreach($data as $row) { 
	if(!$flag) { 
		# display field/column names as first row 
		echo implode("\t", array_keys($row)) . "\n"; 
		$flag = true; 
	}  
 
 array_walk($row, 'cleanData');  
 echo implode("\t", array_values($row)) . "\n"; }
?>