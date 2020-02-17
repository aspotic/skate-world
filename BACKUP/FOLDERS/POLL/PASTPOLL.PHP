<?
/***************************************************************************
			MP Poll
			Morgan Andersson @ Morgande Produsctions (www.morgande.com)
			Free to use, a link to www.morgande.com must be present.
***************************************************************************/
include "admin/pollconfig.php";
include "admin/polldb.php";
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?

echo "<table width='200' border='0' cellspacing='0' cellpadding='0'>";
echo "<tr><td bgcolor='#336699'><div align='center'>";
echo "<strong><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>";
echo "MP Poll v".$version;
echo "</font></strong></div></td></tr>";

echo "<tr><td bgcolor='cfcfcf'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";

	$pollquery = mysql_query("SELECT * FROM poll");
	
	while($pollrow = mysql_fetch_array($pollquery))
	{
    	echo $pollrow['question']." <a href=pastresults.php?pollid=".$pollrow['pollid'].">View Results</a><br>";
    }
	
echo "</font></tr></td></table>";
?>
</body>
</html>
