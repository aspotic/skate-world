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
$module_name = basename(dirname(__FILE__));
$pagetitle = "- Recommend this Site to a Friend";
eval("\$header = \"".template("header")."\";");
echo $header;

function FriendSend($storyid) {
    global $xmbuser, $colspan, $altbg1, $db, $module_name, $prefix, $table_members;
    if(!isset($storyid)) { exit(); }
    include ("portal_header.php");
    $sql = "SELECT title FROM ".$prefix."_stories WHERE storyid='$storyid'";
    $query = mysql_query($sql);
    $row = mysql_fetch_array($query);
    $title = $row[title];
    title("Send to a Friend");
    OpenTable();
    echo "<tr><td $colspan bgcolor=\"$altbg2\" class=\"mediumtxt\" align=center><br>"
	."You will send the story <b>$title</b> to a specified friend:<br></td></tr>"
	."<form action=\"modules.php?modulename=$module_name&amp;modulefile=friend\" method=\"post\">"
	."<input type=\"hidden\" name=\"storyid\" value=\"$storyid\">";
    if($xmbuser) {
	$sql = "SELECT username, email FROM $table_members WHERE username='$xmbuser'";
	$rquery = mysql_query($sql);
	$row = mysql_fetch_array($rquery);
	$yn = $row[username];
	$ye = $row[email];
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"mediumtxt\"><b>Your Name:</b></td>
    <td bgcolor=\"$altbg1\" class=\"mediumtxt\"><input type=\"text\" name=\"yname\" value=\"$yn\"></td></tr>
    <tr><td bgcolor=\"$altbg1\" class=\"mediumtxt\"><b>Your E-mail: </b></td>
    <td bgcolor=\"$altbg1\" class=\"mediumtxt\"><input type=\"text\" name=\"ymail\" value=\"$ye\"></td></tr>
	<tr><td bgcolor=\"$altbg1\" class=\"mediumtxt\"><b>Your Friend's Name: </b></td>
    <td bgcolor=\"$altbg1\" class=\"mediumtxt\"><input type=\"text\" name=\"fname\"></td></tr>
	<tr><td bgcolor=\"$altbg1\" class=\"mediumtxt\"><b>Your Friend's E-mail: </b></td>
    <td bgcolor=\"$altbg1\" class=\"mediumtxt\"><input type=\"text\" name=\"fmail\"></td></tr>
    <tr><td $colspan bgcolor=\"$altbg1\" class=\"mediumtxt\">
	<input type=\"hidden\" name=\"op\" value=\"SendStory\">
	<input type=\"submit\" value=\"Send\">
	</td></tr></form>";
    CloseTable();
    include ('portal_footer.php');
}

function SendStory($storyid, $yname, $ymail, $fname, $fmail) {
    global $sitename, $full_url, $prefix, $db, $module_name;

    $fname = removecrlf($fname);
    $fmail = removecrlf($fmail);
    $yname = removecrlf($yname);
    $ymail = removecrlf($ymail);    

    $sql = "SELECT title, time, topic FROM ".$prefix."_stories WHERE storyid='$storyid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $title = $row[title];
    $time = $row[time];
    $topic = $row[topic];

    $sql = "SELECT topictext FROM ".$prefix."_topics WHERE topicid='$topic'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $topictext = $row[topictext];

    $subject = "Interesting Article at $sitename";
    $message = "Hello $fname:\n\nYour Friend $yname considered the following article interesting and wanted to send it to you.\n\n\n$title\n(Date: $time)\nTopic: $topictext\n\nURL: ".$full_url."modules.php?modulename=$module_name&modulefile=article&storyid=$storyid\n\nYou can read interesting articles at $sitename\n".$full_url."portal.php";
    mail($fmail, $subject, $message, "From: \"$yname\" <$ymail>\nX-Mailer: PHP/" . phpversion());
    $title = urlencode($title);
    $fname = urlencode($fname);
    Header("Location: modules.php?modulename=$module_name&modulefile=friend&op=StorySent&title=$title&fname=$fname");
}

function StorySent($title, $fname) {
    include ("portal_header.php");
    $title = urldecode($title);
    $fname = urldecode($fname);
    OpenTable();
    echo "<center><font class=\"subject\">Story <b>$title</b> Has been sent to $fname... Thanks!</font></center>";
    CloseTable();
    include ("portal_footer.php");
}

switch($op) {

    case "SendStory":
    SendStory($storyid, $yname, $ymail, $fname, $fmail);
    break;
	
    case "StorySent":
    StorySent($title, $fname);
    break;

    case "FriendSend":
    FriendSend($storyid);
    break;

}
end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>
