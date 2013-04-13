<?
/*
 * com@psychosexy.psycho   --- yes indeed it is backwards... just avoiding spam
 * 
 * Notes: 
 * 
 * The dynamic function $entries($month, $date, $year) is called for each day on the
 * calendar and the return value will be inserted into the box.
 * Set it to what you like in your parent script prior to calling the include file.
 * For example:
 * 
 * calendar.html:
 *
 * 
 * $entries = "get_bd";
 * function get_bd($month, $day, $year)
 * {
 *         global $conn;
 *         $query = sprintf("select name from contacts where birthday = '%s/%s/%s'", $month, $day, $year);
 *         $result = pg_query($conn, $query);
 *         while($data = pg_fetch_object($result))
 *                 $ret .= sprintf("%s%s<br>\n", substr($data->name,0,12), (strlen($data->name)>12)?'...':'');
 *         return $ret;
 * }
 * include 'calendar.inc';
 */

function request($item)
{
	if(isset($_GET[$item])) return $_GET[$item];
	if(isset($_POST[$item])) return $_POST[$item];
	return FALSE;
}


// Border style
$caltable = "background-color: #336699";
$calborder = "background-color: #FFFFFF; padding: 2px; height: 90px; width: 90px; font-family: Verdana, Helvetica, sans-serif; font-size: 6pt; text-align: left; vertical-align: top;";
// Header style
$calheader = "background-color: #FFFFFF; font-family: Garamond,serif; font-size: 14pt; font-weight: bold; text-align: center; vertical-align: middle;";
$dayheader = "background-color: #FFFFFF; font-family: Verdana, Helvetica, sans-serif; font-size: 8pt; text-align: center; vertical-align: center;";
$pdateheader = "padding-left: 4px; background-color: #FFFFFF; font-family: Verdana, Helvetica, sans-serif; font-size: 10pt; text-align: left; vertical-align: middle;";
$ndateheader = "padding-right: 4px; background-color: #FFFFFF; font-family: Verdana, Helvetica, sans-serif; font-size: 10pt; text-align: right; vertical-align: middle;";
// Entry style
$calsmall = "font-family: Verdana, Helvetica, sans-erif; font-size: 7pt; text-align: left;";
// Link style
$link = "color: #00008A;";

define("FDAY", 0);
define("LDAY", 1);
define("START", 2);
define("MONTH", 3);
define("YEAR", 4);

$caldata[MONTH] = request("month");
        if(!preg_match("/^[0-9]*$/", $caldata[MONTH])) unset($caldata[MONTH]);
$caldata[YEAR] = request("year");
        if(!preg_match("/^[0-9]*$/", $caldata[YEAR])) unset($caldata[YEAR]);

if(!$caldata[MONTH]) $caldata[MONTH] = date("n", time());
if(!$caldata[YEAR]) $caldata[YEAR] = date("Y", time());

$tcaldata = explode(" ", date("j t w", strtotime(sprintf("%d/1/%d", $caldata[MONTH], $caldata[YEAR]))));

$caldata = array_merge($tcaldata, $caldata);

print "<table style=\"$caltable\" cellpadding=\"0\" cellspacing=\"1\"><tr><td>\n";
print "<table cellpadding=\"0\" cellspacing=\"1\">\n";


$pm = ($caldata[MONTH]==1)?12:$caldata[MONTH]-1;
$py = ($caldata[MONTH]==1)?$caldata[YEAR]-1:$caldata[YEAR];
$nm = ($caldata[MONTH]==12)?1:$caldata[MONTH]+1;
$ny = ($caldata[MONTH]==12)?$caldata[YEAR]+1:$caldata[YEAR];

printf("  <tr>\n    <td colspan=\"7\">\n");
printf("      <table width=\"100%%\" cellpadding=\"0\" cellspacing=\"0\">\n");
printf("        <tr><td colspan=\"3\" bgcolor=\"#FFFFFF\"> </td></tr>\n");
printf("        <tr>\n");
printf("          <td align=\"left\" style=\"%s\"><a style=\"%s\" href=\"%s?month=%s&year=%s\"><< %s</a></td>\n",
	$pdateheader, $link, $_SERVER['PHP_SELF'], $pm, $py,
	date("M y", strtotime(sprintf("%d/1/%d", $pm, $py))));
printf("          <td style=\"%s\">%s</td>\n \n", $calheader,
	date("F Y", strtotime(sprintf("%d/%d/%d", $caldata[MONTH], $caldata[FDAY], $caldata[YEAR]))));
printf("          <td align=\"right\" style=\"%s\"><a style=\"%s\" href=\"%s?month=%s&year=%s\">%s >></a></td>\n",
	$ndateheader, $link, $_SERVER['PHP_SELF'], $nm, $ny,
	date("M y", strtotime(sprintf("%d/1/%d", $nm, $ny))));
printf("        </tr>\n");
printf("        <tr><td colspan=\"3\" bgcolor=\"#FFFFFF\"> </td></tr>\n");
printf("      </table>\n");
printf("    </td>\n  </tr>\n"); 

print "  <tr>\n";
print "    <td style=\"$dayheader\">Sunday</td>\n";
print "    <td style=\"$dayheader\">Monday</td>\n";
print "    <td style=\"$dayheader\">Tuesday</td>\n";
print "    <td style=\"$dayheader\">Wednesday</td>\n";
print "    <td style=\"$dayheader\">Thursday</td>\n";
print "    <td style=\"$dayheader\">Friday</td>\n";
print "    <td style=\"$dayheader\">Saturday</td>\n";
print "  </tr>\n";

$cur = 1;
$first = 1;

while($cur <= $caldata[LDAY]) {
	print "  <tr>\n";
	for($i = 0; $i < 7; $i++) {
		if($i < $caldata[START] && $first) {
			printf("    <td style=\"%s\"> </td>\n", $calborder);
		} else {
			$first = 0;
			printf("    <td style=\"%s\">%s<span style=\"%s\"><br>%s</span></td>\n", $calborder,
				($cur > $caldata[LDAY])?' ':$cur, $calsmall,
				($cur > $caldata[LDAY]||!$entries)?'':$entries($caldata[MONTH], $cur, $caldata[YEAR]));
			$cur++;
		}
	}
	
	print "  </tr>\n";
}

print "</table>\n";
print "</td></tr></table>\n";
?>