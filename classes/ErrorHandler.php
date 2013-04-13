<?
class ErrorHandler extends DB
{
	var $_error_printer;
	function set_error_printer($set_it)
	{
		$this->_error_printer = $set_it;
	}
	
	function print_error($errorcode)
	{
		if($this->_error_printer==true)
		{	
			if(gettype($errorcode)=="array")
			{
				print "<br/>";
				print_r($errorcode);
				print "<br/>";
			}
			else
				print "<br/>".$errorcode."<br/>";
		}
	}
}

?>