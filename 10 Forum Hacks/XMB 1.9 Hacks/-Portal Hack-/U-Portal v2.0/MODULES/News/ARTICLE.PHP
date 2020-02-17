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

eval("\$header = \"".template("header")."\";");
echo $header;

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}
require_once("portal_main.php");
$module_name = basename(dirname(__FILE__));

if (stristr($REQUEST_URI,"mainfile")) {
    Header("Location: modules.php?modulename=$module_name&modulefile=article&storyid=$storyid");
} elseif (!isset($storyid) && !isset($tid)) {
    Header("Location: index.php");
}

if ($op == "Reply") {
    Header("Location: modules.php?modulename=$module_name&modulefile=comments&op=Reply&pid=0&storyid=$storyid&mode=$mode&order=$order&thold=$thold");
}

$sql = "select catid, aid, time, title, hometext, bodytext, topic, informant, notes, acomm, haspoll, pollID, score, ratings FROM ".$prefix."_stories where storyid='$storyid'";
$result = mysql_query($sql);
if ($numrows = mysql_num_rows($result) != 1) {
    Header("Location: index.php");
    die();
}
$row = mysql_fetch_array($result);
$catid = $row['catid'];
$aid = $row['aid'];
$time = $row['time'];
$title = $row['title'];
$hometext = $row['hometext'];
$bodytext = $row['bodytext'];
$topic = $row['topic'];
$informant = $row['informant'];
$notes = $row['notes'];
$acomm = $row['acomm'];
$haspoll = $row['haspoll'];
$pollID = $row['pollID'];
$score = $row['score'];
$ratings = $row['ratings'];

if ($aid == "") {
    Header("Location: modules.php?modulename=$module_name");
}

mysql_query("UPDATE ".$prefix."_stories SET counter=counter+1 where storyid=$storyid");

$artpage = 1;
require("portal_header.php");
$artpage = 0;

formatTimestamp($time);
$title = stripslashes($title);
$hometext = stripslashes($hometext);
$bodytext = stripslashes($bodytext);
$notes = stripslashes($notes);

if ($notes != "") {
    $notes = "<br><br><b>Note:</b> <i>$notes</i>";
} else {
    $notes = "";
}

if($bodytext == "") {
    $bodytext = "$hometext$notes";
} else {
    $bodytext = "$hometext<br><br>$bodytext$notes";
}

if($informant == "") {
    $informant = $anonymous;
}

getTopics($storyid);

if ($catid != 0) {
    $sql = "select title from ".$prefix."_stories_cat where catid='$catid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $title1 = $row[title];
    $title = "<a href=\"modules.php?modulename=$module_name&amp;modulefile=categories&amp;op=newindex&amp;catid=$catid\"><font class=\"subject\">$title1</font></a>: $title";
}

echo "<table width=\"100%\" border=\"0\"><tr><td valign=\"top\" width=\"100%\">\n";
themearticle($aid, $informant, $datetime, $title, $bodytext, $topic, $topicname, $topicimage, $topictext);
echo "</td><td>&nbsp;</td><td valign=\"top\">\n";


if ($haspoll == 1) {
    $url = sprintf("modules.php?modulename=Surveys&amp;op=results&amp;pollID=%d", $pollID);
    $boxContent = "<form action=\"modules.php?modulename=Surveys\" method=\"post\">";
    $boxContent .= "<input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\">";
    $boxContent .= "<input type=\"hidden\" name=\"forwarder\" value=\"".$url."\">";
    $sql = "SELECT pollTitle, voters FROM ".$prefix."_poll_desc WHERE pollID='$pollID'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $pollTitle = $row['pollTitle'];
    $voters = $row['voters'];
    $boxTitle = "Article's Poll";
    $boxContent .= "<font class=\"subject\"><b>$pollTitle</b></font><br><br>\n";
    $boxContent .= "<table border=\"0\" width=\"100%\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\">";
    for($i = 1; $i <= 12; $i++) {
	$sql = "SELECT pollID, optionText, optionCount, voteID FROM ".$prefix."_poll_data WHERE (pollID='$pollID') AND (voteID='$i')";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$numrows = mysql_num_rows($result);
	if($numrows != 0) {
	    $optionText = $row['optionText'];
	    if($optionText != "") {
		$boxContent .= "<tr><td valign=\"top\" bgcolor=\"$altbg1\" class=\"tablerow\"><input type=\"radio\" name=\"voteID\" value=\"".$i."\"></td><td width=\"100%\" bgcolor=\"$altbg1\" class=\"tablerow\">$optionText</td></tr>\n";
	    }
	}
    }
    $boxContent .= "</table><br><center><font class=\"tablerow\"><input type=\"submit\" value=\"Vote\"></font><br><br>";
    if ($xmbuser) {
    cookiedecode($xmbuser);
    }
    for($i = 0; $i < 12; $i++) {
	$sql = "SELECT optionCount FROM ".$prefix."_poll_data WHERE (pollID='$pollID') AND (voteID='$i')";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$optionCount = $row['optionCount'];
	$sum = (int)$sum+$optionCount;
    }
    $boxContent .= "[ <a href=\"modules.php?modulename=Surveys&amp;op=results&amp;pollID=$pollID&amp;mode=$cookie[4]&amp;order=$cookie[5]&amp;thold=$cookie[6]\"><b>Results</b></a> | <a href=\"modules.php?modulename=Surveys\"><b>Polls</b></a> ]<br>";

    if ($pollcomm) {
	$sql = "select * from ".$prefix."_pollcomments where pollID='$pollID'";
	$result = mysql_query($sql);
	$numcom = mysql_num_rows($result);
	$boxContent .= "<br>Votes: <b>$sum</b><br>Comments: <b>$numcom</b>\n\n";
    } else {
    $boxContent .= "<br>Votes <b>$sum</b>\n\n";
    }
    $boxContent .= "</font></center></form>\n\n";
    themesidebox($boxTitle, $boxContent);
}

$sql = "select title, content, active, bposition from ".$prefix."_blocks where blockfile='block-Login.php'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$title = $row['title'];
$content = $row['content'];
$active = $row['active'];
$position = $row['position'];
if (($active == 1) AND ($position == "r") AND (!$xmbuser)) {
    loginbox();
}

$boxtitle = "Related Links";
$boxstuff = "<font class=\"tablerow\">";
$sql = "select name, url from ".$prefix."_related where tid=$topic";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
    $name = $row['name'];
    $url = $row['url'];
    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$url\" target=\"new\">$name</a><br>\n";
}

$boxstuff .= "<center><font class=\"tablerow\"><b>Most read story about $topictext:</b><br>\n";

global $prefix;

$sql = "select storyid, title from ".$prefix."_stories where topic=$topic order by counter desc limit 0,1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$topstory = $row['storyid'];
$ttitle = $row['title'];

$boxstuff .= "<a href=\"modules.php?modulename=$module_name&modulefile=article&storyid=$topstory\">$ttitle</a></font></center><br>\n";
themesidebox($boxtitle, $boxstuff);

if ($ratings != 0) {
    $rate = substr($score / $ratings, 0, 4);
    $r_image = round($rate);
    $the_image = "<br><br><img src=\"$portal_images/articles/stars-$r_image.gif\" border=\"1\"></center><br>";
} else {
    $rate = 0;
    $the_image = "</center><br>";
}
$ratetitle = "Article Rating";
$ratecontent = "<center>Average Score: <b>$rate</b><br>Votes: <b>$ratings</b>$the_image";
$ratecontent .= "<form action=\"modules.php?modulename=$module_name\" method=\"post\"><center>Please take a second and vote for this article:</center><br>";
$ratecontent .= "<input type=\"hidden\" name=\"storyid\" value=\"$storyid\">";
$ratecontent .= "<input type=\"hidden\" name=\"op\" value=\"rate_article\">";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"5\"> <img src=\"$portal_images/articles/stars-5.gif\" border=\"0\" alt=\"Excellent\" title=\"Excellent\"><br>";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"4\"> <img src=\"$portal_images/articles/stars-4.gif\" border=\"0\" alt=\"Very Good\" title=\"Very Good\"><br>";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"3\"> <img src=\"$portal_images/articles/stars-3.gif\" border=\"0\" alt=\"Good\" title=\"Good\"><br>";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"2\"> <img src=\"$portal_images/articles/stars-2.gif\" border=\"0\" alt=\"Regular\" title=\"Regular\"><br>";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"1\"> <img src=\"$portal_images/articles/stars-1.gif\" border=\"0\" alt=\"Bad\" title=\"Bad\"><br><br>";
$ratecontent .= "<center><input type=\"submit\" value=\"Cast my Vote!\"></center></form><br>";
themesidebox($ratetitle, $ratecontent);

$optiontitle = "Options";
$optionbox .= "<br>&nbsp;<img src=\"$portal_images/printable.gif\" border=\"0\" alt=\"Printer Friendly Page\" title=\"Printer Friendly Page\" width=\"16\" height=\"11\">&nbsp;&nbsp;<a href=\"modules.php?modulename=$module_name&amp;modulefile=print&amp;storyid=$storyid\">Printer Friendly Page</a><br><br>";
$optionbox .= "&nbsp;<img src=\"$portal_images/subscribe.gif\" border=\"0\" alt=\"Send to a Friend\" title=\"Send to a Friend\" width=\"16\" height=\"11\">&nbsp;&nbsp;<a href=\"modules.php?modulename=$module_name&amp;modulefile=friend&amp;op=FriendSend&amp;storyid=$storyid\">Send to a Friend</a><br><br>\n";
if ($status == "Super Administrator" || $status == "Administrator") {
    $optionbox .= "<center><b>Admin:</b><br>[ <a href=\"portal_admin.php?op=adminStory\">Add</a> | <a href=\"portal_admin.php?op=EditStory&storyid=$storyid\">Edit</a> | <a href=\"portal_admin.php?op=RemoveStory&storyid=$storyid\">Delete</a> ]</center>";
}
themesidebox($optiontitle, $optionbox);

echo "</td></tr></table>\n";
cookiedecode($xmbuser);

include("modules/$module_name/associates.php");

if ((($mode != "nocomments") OR ($acomm == 0)) OR ($articlecomm == 1)) {
    include("modules/News/comments.php");
}
include ("portal_footer.php");
end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>
