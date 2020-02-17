<?php 

// Find Header 
require "header.php"; 

// Language 
$lang_textlinkus = 'Tagger'; 

// Navigation 
$navigation = "&raquo; $lang_textlinkus"; 
eval("\$forum_tagger =\"".template("forum_tagger")."\";"); 

// Load Templates 
loadtemplates('header,forum_tagger,footer'); 

// View Templates 
eval("\$css = \"".template("css")."\";"); 
echo $css; 
eval("\$header = \"".template("header")."\";"); 
echo $header; 
eval("\$linkus = \"".template("forum_tagger")."\";"); 
$adds = stripslashes($linkus); 
echo $linkus; 

// View Footer 
end_time(); 
eval("\$footer = \"".template("footer")."\";"); 
echo $footer; 
exit(); 
?>
