	<title>Links</title>
	<style type="text/css" >
	body {background-color:032F41;}
	a {color:ffffff;}
	</style>
<base target="_blank">

<FONT FACE ="Arial Narrow" COLOR="#FFFFFF">
<?php 
// Connect to the database 
$db = mysql_connect("localhost","switchsk_content","content"); 
mysql_select_db ("switchsk_content"); 
// Ask the database for the information from the links table 
$result = mysql_query("SELECT * FROM clinks"); 
// Now we print out the results, starting by making a table 
echo "<center> <h3> Links </h3> </center> <br>";
echo "<table border = '0' valign='top' align='center'>"; 
while ($rows = mysql_fetch_row($result)) 
// Here we make the script keep making new rows until all the links in our database are shown, this is called a loop 
{ 
echo "<tr align='center'><td align='center'><a href='$rows[2]'>$rows[1]</a><br></td></tr>"; 
} 
// Finally we close off the table 
echo "</table>"; 
?> 
