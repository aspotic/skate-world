<?php 

// Find Header 
require "header.php"; 

// Language 
$lang_textlinkus = 'Magazine'; 

// Navigation 
$navigation = "&raquo; $lang_textlinkus"; 
eval("\$forum_dictionary =\"".template("forum_dictionary")."\";"); 

// Load Templates 
loadtemplates('header,forum_dictionary,footer'); 

// View Templates 
eval("\$css = \"".template("css")."\";"); 
echo $css; 
eval("\$header = \"".template("header")."\";"); 
echo $header; 
eval("\$linkus = \"".template("forum_dictionary")."\";"); 
$adds = stripslashes($linkus); 
echo $linkus; 

// View Footer 
end_time(); 
eval("\$footer = \"".template("footer")."\";"); 
echo $footer; 
exit(); 
?>
