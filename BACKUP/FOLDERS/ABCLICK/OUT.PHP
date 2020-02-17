<?

require("shared.inc.php");

$db=mysql_connect("$dbhost","$dbuser","$dbpasswd");
mysql_select_db("$dbname",$db) or die ("Unable to select database");

function error ($error_message) {
	echo $error_message."<BR>";
	exit;
}

$day = date("Y-m-d"); // format the date string
$url = urldecode($QUERY_STRING);

        if ((! $url) || (! preg_match("/http:/", $url))) error ("Error: Invalid syntax, below is an example of the correct link syntax to use:<br> <A HREF=\"$track_url/out.php?http://www.abweb.co.uk/\">$track_url/out.php?http://www.abweb.co.uk/</A>");


if ($QUERY_STRING) {
	$result = mysql_query("SELECT * FROM clicks WHERE url='$url' AND day='$day'",$db);
	if(mysql_num_rows($result) > 0) {
		mysql_free_result($result);
		$result = mysql_query("SELECT UNIX_TIMESTAMP(time) FROM ip WHERE (url='$url' AND ipnum='$REMOTE_ADDR' AND day='$day')",$db);
		if($row = mysql_fetch_row($result)) {
				if(($row[0]+3600) < time()) {
					mysql_query("UPDATE clicks SET raw=raw+1,uni=uni+1 WHERE url='$url' AND day='$day'",$db);
					mysql_query("UPDATE ip SET time=NOW() WHERE url='$url' AND ipnum='$REMOTE_ADDR' AND day='$day'",$db);
				} else { // if the click is raw
					mysql_query("UPDATE clicks SET raw=raw+1 WHERE url='$url' AND day='$day'",$db);
				}
		mysql_free_result($result);
		} else { // new ip address
			mysql_query("UPDATE clicks SET raw=raw+1,uni=uni+1 WHERE url='$url' AND day='$day'",$db);
			mysql_query("INSERT INTO ip (url,ipnum,day) VALUES ('$url','$REMOTE_ADDR','$day')",$db);
		}
	} else { // if its a new day or a new url
		mysql_query("INSERT INTO ip (url,ipnum,day) VALUES ('$url','$REMOTE_ADDR','$day')",$db);
		mysql_query("INSERT INTO clicks (url,day,raw,uni) VALUES ('$url','$day',1,1)",$db);
	}


	$url="Location: ".$url;
	header($url);
	exit;
}
?>
