<style type="text/css" >
body {background-color:032F41;}
a {color:ffffff;}
</style>

<FONT FACE ="Arial Narrow" COLOR="#FFFFFF">
<?php 

//Connect to DB 

$db = mysql_connect("localhost","skate-wo_content","content"); 
mysql_select_db("skate-wo_content"); 

if($_POST['delete']) { 

// If $_POST['delete'] ($_POST because we passed it to this script using a form with action set to 'post' is present show this : 
// This query deletes from our table (links) where the title is $delete, which is the title you entered in the form. 

    $result = mysql_query("DELETE FROM html WHERE title = '".$_POST['delete']."'"); 
    echo "You deleted the following html tutorial : ".$_POST['delete']; 

} else {// Otherwise, show this : 
    echo 'You didnt enter any data'; 
} 
?>