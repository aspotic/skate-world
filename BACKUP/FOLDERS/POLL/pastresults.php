<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>

<?php

/***************************************************************************
			MP Poll
			Morgan Andersson @ Morgande Produsctions (www.morgande.com)
			Free to use, a link to www.morgande.com must be present.
***************************************************************************/

include "admin/pollconfig.php";
include "admin/polldb.php";

	$questionquery = mysql_query("SELECT * FROM poll WHERE pollid=$pollid");
	$answerquery = mysql_query("SELECT * FROM poll_answers WHERE pollid=$pollid");

	while($questrow = mysql_fetch_array($questionquery))
	{
		$question = $questrow['question'];
	}

echo "<table width='200' border='0' cellspacing='0' cellpadding='0'>";
echo "<tr><td bgcolor='#336699'><div align='center'><strong><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>";
echo "MP Poll v".$version;
echo "</font></strong></div></td></tr>";
echo "<tr><td bgcolor='cfcfcf'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
echo $question."&nbsp<br>";

				$resultquery = mysql_query("SELECT * FROM poll_answers WHERE pollid=$pollid");
				
				while($resultrow = mysql_fetch_array($resultquery))
				{

					echo "</font><table width='' border='0' cellspacing='0' cellpadding='0'>";
					echo "<tr><td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
					echo "<B>".$resultrow['answers']."</B><BR>";
					
            if($resultrow['result']>0)
			{
				echo "<img src='blue.gif' width='".$resultrow['result']."' height='10'>";
            }
			echo $resultrow['result']."% (".$resultrow['votes']." votes)";
            echo "</font></td></tr></table>";
			echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
			}

	echo "</font></td></tr></table>";
?>
	
</body>
</html>
