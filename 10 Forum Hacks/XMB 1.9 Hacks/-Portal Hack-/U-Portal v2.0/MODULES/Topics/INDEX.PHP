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

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

require_once("portal_main.php");
eval("\$header = \"".template("header")."\";");
echo $header;
$module_name = basename(dirname(__FILE__));

include("portal_header.php");
 global $portal_images;
$sql = "SELECT topicid, topicname, topicimage, topictext FROM ".$prefix."_topics ORDER BY topictext";
$result = mysql_query($sql);
if (mysql_num_rows($result) == 0) {
    include("portal_header.php");
    include("portal_footer.php");
}
if (mysql_num_rows($result) > 0) {
    $r_options = "";
    if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
    if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
    if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
    OpenTable();
    echo "<center><font class=\"subject\"><b>Current Active Topics</b></font><br>\n"
	."<font class=\"tablerow\">Current Active Topics</font><br><br>\n"
	."</form></center><br><br>";
    while ($row = mysql_fetch_array($result)) {
	$topicid = $row['topicid'];
	$topicname = $row['topicname'];
	$topicimage = $row['topicimage'];
	$topictext = $row['topictext'];
    $t_image = "$tipath$topicimage";
	$sql = "SELECT counter FROM ".$prefix."_stories WHERE topic='$topicid'";
	$res = mysql_query($sql);
	$numrows = mysql_num_rows($res);
	$reads = 0;
	while ($counting = mysql_fetch_array($res)) {
	    $reads = $reads+$counting[counter];
	}
	title($topictext);
	echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" align=\"center\" bgcolor=\"$bordercolor\">\n"
	    ."<tr><td bgcolor=\"$altbg2\" valign=\"top\" width='25%' class=\"tablerow\">\n"
	    ."<a href=\"modules.php?modulename=News&amp;new_topic=$topicid\"><img src=\"$t_image\" border=\"0\" alt=\"$topictext\" title=\"$topictext\" hspace='5' vspace='5'></a><br><br>\n"
	    ."<font class=\"content\">\n"
	    ."<big><strong>&middot</strong></big>&nbsp;<b>Topic:</b> $topictext<br>\n"
	    ."<big><strong>&middot</strong></big>&nbsp;<b>Total News:</b> $numrows<br>\n"
	    ."<big><strong>&middot</strong></big>&nbsp;<b>Total Reads:</b> $reads</font>\n"
	    ."</td>\n"
	    ."<td bgcolor=\"$altbg2\" valign='top' class=\"tablerow\">";
	$sql = "SELECT storyid, catid, title FROM ".$prefix."_stories WHERE topic='$topicid' ORDER BY storyid DESC LIMIT 0,10";
	$result2 = $db->query($sql);
	$num = $db->num_rows($result2);
	if ($num != 0) {
	    while ($row2 = $db->fetch_array($result2)) {
		$sql = "SELECT title FROM ".$prefix."_stories_cat WHERE catid='$row2[catid]'";
		$result3 = $db->query($sql);
		$row3 = $db->fetch_array($result3);
		if ($row2[catid] == 0) {
		    $cat_link = "";
		} else {
		    $cat_link = "<a href='modules.php?modulename=News&file=categories&op=newindex&catid=$row2[catid]'><b>$row3[title]</b></a>: ";
		}
		echo "<img src='$portal_images/arrow.gif' border='0' alt='' title=''>&nbsp;&nbsp;$cat_link<a href='modules.php?modulename=News&modulefile=article&storyid=$row2[storyid]$r_options'>$row2[title]</a><br>";
	    }
	    if ($num == 10) {
		echo "<div align='right'><big><strong>&middot</strong></big>&nbsp;<a href='modules.php?modulename=News&new_topic=$topicid'><b>More --></b></a>&nbsp;&nbsp;</div>";
	    }
	} else {
	    echo "<i>...There aren't any news yet for this topic...</i>";
	}
	echo "</td></tr></table><br><br>";
    }
} 
CloseTable();
include("portal_footer.php");
end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>
