<?php 
$db = mysql_connect("localhost","skate-wo_content","content"); 
mysql_select_db ("skate-wo_content"); 
$query = "INSERT INTO pictures(title, url) 
VALUES('".$_POST['title']."','".$_POST['url']."')"; 
$result = mysql_query($query); 
echo 'Link entered.'; 
?> 
