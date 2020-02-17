	<title>Links</title>
	<style type="text/css" >
	body {background-color:032F41;}
	a {color:ffffff;}
	</style>
<base target="_blank">

<FONT FACE ="Arial Narrow" COLOR="#FFFFFF">
<?php 
// Connect to the database 
$db = mysql_connect("localhost","skate_content","content"); 
mysql_select_db ("skate_content"); 
// Ask the database for the information from the links table 
$result = mysql_query("SELECT * FROM articles"); 
// Now we print out the results, starting by making a table 
echo "<center> <h3> Articles </h3> </center> <br>";
echo "<table border='0' valign='top' align='center' width='500'>"; 
while ($rows = mysql_fetch_row($result)) 
// Here we make the script keep making new rows until all the links in our database are shown, this is called a loop 
{ 
echo "<tr bgcolor='07658d'><td align='center'><FONT FACE ='Arial Narrow' COLOR='#FFFFFF'><h4>$rows[1]</h4></td></tr><tr bgcolor='0a5c57'><td align='center'><FONT FACE ='Arial Narrow' COLOR='#FFFFFF'>$rows[2]</td></tr><tr><td align='center'><br><br></td></tr>"; 
} 
// Finally we close off the table 
echo "</table>"; 
?> 
