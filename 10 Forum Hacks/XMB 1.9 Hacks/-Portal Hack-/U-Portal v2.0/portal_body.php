<?php

/************************************************************************/
/* Xmb Ultimate Portal v2.0.0                                           */
/* ==================================================================== */
/*  Copyright (c) 2003 - 2004 by FREEWILL46 (freewill_46@hotmail.com)   */
/*  http://www.fw46.com/eforum                                          */
/*======================================================================*/
/* BASED ON PHP-NUKE: Advanced Content Management System                */
/* =====================================================================*/
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/************************************************************************/

function BeginTable() {
    global $bordercolor, $borderwidth, $tablespace;
          echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" bgcolor=\"$bordercolor\"><tr><td>\n";
          echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\">\n";
}

function EndTable() {
    echo "</td></tr></table></table>\n";
}

function OpenTable() {
    global $altbg2, $bordercolor;
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$bordercolor\"><tr><td class=\"tablerow\">\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"8\" bgcolor=\"$altbg2\"><tr><td class=\"tablerow\">\n";
}

function CloseTable() {
    echo "</td></tr></table></td></tr></table>\n";
}

function FormatStory($thetext, $notes, $aid, $informant) {
    global $anonymous, $catetxt;
    if ($notes != "") {
	$notes = "<br><b>Note:</b> <i>$notes</i>\n";
    } else {
	$notes = "";
    }
    if ("$aid" == "$informant") {
	echo "<font class=\"tablerow\">$thetext<br>$notes</font>\n";
    } else {
	if($informant != "") {
	    $boxstuff = "<a href=\"member.php?action=viewpro&member=$informant\"><font color=\"#FF8080\"><b>$informant</b></font></a> ";
	} else {
	    $boxstuff = "<font color=\"#FF8080\"><b>$anonymous</b></font>";
	}
	$boxstuff .= "   writes \"$thetext\" $notes\n";
	echo "<font class=\"tablerow\">$boxstuff</font>\n";
    }
}

function themeheader() {
    global $xmbuser, $bbname, $tablewidth, $banners, $portal_images, $copyright;
    if($banners) {
	include("portal_banners.php");
    }
    if(!empty($copyright)){
    echo "<br>";
    echo "<table width=\"$tablewidth\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\"><tr valign=\"top\">\n"
	."<td><img src=\"$portal_images/pixel.gif\" border=\"0\" alt=\"\"></td></tr></table>\n"
	."<table width=\"$tablewidth\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\"><tr valign=\"top\">\n"
	."<td><img src=\"$portal_images/pixel.gif\" border=\"0\" alt=\"\"></td>\n"
	."<td valign=\"top\">\n";
    blocks(left);
    echo "</td><td><img src=\"$portal_images/pixel.gif\" width=\"13\" height=\"1\" border=\"0\" alt=\"\"></td><td width=\"100%\">\n";
    }else{
    die("you are not authorized to play with the codes");
   }
}

function themefooter() {
    global $index, $tablewidth, $bordercolor, $borderwidth, $altbg1, $tablespace, $portal_images, $copyright;
    if ($index == 1) {
    if(empty($copyright)) {
       die("you are not authorized to play with the codes");
    }
	echo "</td><td><img src=\"$portal_images/pixel.gif\" width=\"13\" height=\"1\" border=\"0\" alt=\"\"></td><td valign=\"top\" width=\"155\">\n";
    blocks(right);
 	echo "<td><img src=\"$portal_images/pixel.gif\" border=\"0\" alt=\"\">";
    } else {
	echo "</td><td colspan=\"2\"><img src=\"$portal_images/pixel.gif\" border=\"0\" alt=\"\">";
    }
    echo "<br><br></td></tr></table>\n";

 
}


function themeindex ($aid, $informant, $time, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext) {
    global $anonymous, $tipath, $bordercolor, $borderwidth, $tablespace, $catbgcode, $altbg2, $altbg1;
    echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td bgcolor=\"$bordercolor\">\n"
	."<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\"><tr><td bgcolor=\"$altbg2\" class=\"tablerow\">\n"
	."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">\n"
	."</td><td bgcolor=\"$altbg1\" height=20><font class=\"subject\"><b>&nbsp;$title</b></font></td></tr>\n"
	."<tr><td colspan=\"2\" bgcolor=\"$altbg2\" class=\"tablerow\"><br>\n"
	."<table border=\"0\" cellpadding=\"$tablespace\" cellspacing=\"0\" width=\"98%\" align=\"center\"><tr><td bgcolor=\"$altbg2\" class=\"tablerow\">\n"
	."<a href=\"modules.php?modulename=News&new_topic=$topic\"><img src=\"$tipath$topicimage\" alt=\"$topictext\" border=\"0\" align=\"right\"></a>";
    FormatStory($thetext, $notes, $aid, $informant);
    echo "</td></tr></table>\n"
	."</td></tr></table><br>\n"
	."</td></tr><tr><td bgcolor=\"$altbg1\" align=\"center\" class=\"tablerow\">Posted by\n";
    formatAidHeader($aid);
    echo " on $time $timezone ($counter reads)<br><br>\n"
	."<font class=\"content\">$morelink</font></center><br>\n"
	."</td></tr></table>\n"
	."</td></tr></table><br>\n";
}

function themearticle ($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext) {
    global $admin, $storyid, $tipath, $bordercolor, $altbg1, $altbg2;
    echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td bgcolor=\"$bordercolor\">\n"
	."<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\"><tr><td bgcolor=\"$altbg2\" class=\"tablerow\">\n"
	."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">\n"
	."</td><td bgcolor=\"$altbg1\" height=20><font class=\"subject\"><b>&nbsp;$title</b></font></td></tr>\n"
	."<tr><td colspan=\"2\" bgcolor=\"$altbg2\" class=\"tablerow\" height=20><br>\n"
	."<table border=\"0\" width=\"98%\" align=\"center\"><tr><td bgcolor=\"$altbg2\" class=\"tablerow\" height=20>\n"
	."<a href=\"modules.php?modulename=News&new_topic=$topic\"><img src=\"$tipath$topicimage\" alt=\"$topictext\" border=\"0\" align=\"right\"></a>";
    FormatStory($thetext, $notes="", $aid, $informant);
    echo "</td></tr></table>\n"
	."</td></tr></table><br>\n"
	."</td></tr></table>\n"
	."</td></tr></table><br><br>\n";
}

function themesidebox($title, $content) {
    global $bordercolor, $borderwidth, $tablespace, $catbgcode, $altbg2, $colspan;
    if(!isset($colspan)){
    $colspan = "colspan=\"0\"";
    }else{
    $colspan = "colspan=\"2\"";
    }
    echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"190\" bgcolor=\"$bordercolor\"><tr><td class=\"tablerow\"><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\"><tr>\n"
       ."<td $colspan class=\"header\" $catbgcode><b>» $title </b></td></tr><tr><td $colspan bgcolor=\"$altbg2\" class=\"tablerow\">\n"
       ."$content</td></tr></table></td></tr></table>\n"
	   ."<br>\n\n\n";
}
?>
