<?php 
$db = mysql_connect("localhost","switchsk_content","content"); 
mysql_select_db ("switchsk_content"); 
$query = "INSERT INTO news(title, url) 
VALUES('".$_POST['title']."','".$_POST['url']."')"; 
$result = mysql_query($query); 
echo 'News entered.'; 
?> 
