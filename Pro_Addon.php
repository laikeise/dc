<?
class Pro_Addon extends Session {
	/**
		* Verify site access
		* 
		* This will verify the site access 
		* 
		* @access public
		* @param strint $site_name - Site name
    * @return int - reture sid, if null then site dun exists
		*
	*/
		
   function get_Ren_Name($ren_id) {
    	$q="select pro_ren_name from pro_ren_sites where pro_ren_id=$ren_id;";
    	//print $q."<br/>";
    	$result = $this->doSQL($q);
    	if($qrow=mysql_fetch_array($result))
    	{
    		$fgen_sid=$qrow[0];
    		return $fgen_sid;
    	}
    	else
    		return FALSE;
    }
    
     function get_Rom_Name($rom_id) {
    	$q="select pro_rom_name from pro_room_type where pro_rom_id=$rom_id;";
    	//print $q."<br/>";
    	$result = $this->doSQL($q);
    	if($qrow=mysql_fetch_array($result))
    	{
    		$fgen_sid=$qrow[0];
    		return $fgen_sid;
    	}
    	else
    		return FALSE;
    }
    
     function generate_site_list() {
		print "<select name='html_ren_type' onchange='chstate();'> ";
    	print "<option value=''>Select Site</option>";
    	$ren_q="select pro_ren_id,pro_ren_name from pro_ren_sites group by pro_ren_name";
    	//print $ren_q;
				
				$ren_result = $this->doSQL($ren_q);
				while($ren_row=mysql_fetch_array($ren_result))
				{
					$db_ren_name=$ren_row["pro_ren_name"];
					$db_ren_id=$ren_row["pro_ren_id"];
					print "<option value='" .$db_ren_id. "' " . ($db_ren_id==$_POST[html_ren_type]||$db_ren_id==$_GET[html_ren_type]?"selected":" ") ." >".$db_ren_name."</option>";
		   		}
    	print "</select>";
     }
     
     //get from site
     function generate_room_list($input_ren) {
		print "<select name='html_room_type' >";
    	print "<option value=''>Select Site</option>";
    	$rm_q="select pro_rom_id,pro_rom_name from pro_room_type where pro_ren_id=$input_ren";
    	print $rm_q;
		$rm_result = $this->doSQL($rm_q);
		while($rm_row=mysql_fetch_array($rm_result))
		{
			$db_rm_name=$rm_row["pro_rom_name"];
			$db_rm_id=$rm_row["pro_rom_id"];
			print "<option value='" .$db_rm_id. "' " . ($db_rm_id==$_POST[html_room_type]?"selected":" ") ." >".$db_rm_name."</option>";
		}
		print "</select>";
     }
     
     function get_room_type($input_room) {
		$rm_q="select pro_def_rom_id from pro_room_type where pro_ren_id=$input_room";
    	print $rm_q;
		$rm_result = $this->doSQL($rm_q);
		if($rm_row=mysql_fetch_array($rm_result))
		{
			$db_def_rom_id=$rm_row["pro_def_rom_id"];
			$rmd_q="select pro_def_rom_name from pro_def_rome where pro_def_rom_id=$db_def_rom_id";
    		//print $rmd_q;
    		$rmd_result = $this->doSQL($rmd_q);
			if($rmd_row=mysql_fetch_array($rmd_result))
			{
				$db_rm_name=$rmd_row["pro_def_rom_name"];
			}
		}
		return $db_rm_name;
     }
     
}
?>