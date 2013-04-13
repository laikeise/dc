<?
/*
 * Generate random passwds with md5 -- marc 20070502 
 */
function randomkeys($length)
{
	$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
	$key  = $pattern{rand(0,35)};

	for($i=1;$i<$length;$i++) {
		$key .= $pattern{rand(0,35)};
	}

	return $key;
}

$i = 0;
while($i < 17) {
	$passwd[] = randomkeys(6);
	echo $passwd[$i] . " " . md5($passwd[$i]) . "\n";
	$i++;
}


?>
