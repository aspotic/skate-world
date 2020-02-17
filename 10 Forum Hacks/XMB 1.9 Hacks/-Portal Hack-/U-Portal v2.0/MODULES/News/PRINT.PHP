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

if(!isset($storyid)) {
    exit();
}

function PrintPage($storyid) {
    global $logo, $full_url, $sitename, $datetime, $prefix, $module_name;

    $sql = "SELECT title, time, hometext, bodytext, topic, notes FROM ".$prefix."_stories WHERE storyid='$storyid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $title = $row[title];
    $time = $row[time];
    $hometext = $row[hometext];
    $bodytext = $row[bodytext];
    $topic = $row[topic];
    $notes = $row[notes];

    $sql = "SELECT topictext FROM ".$prefix."_topics WHERE topicid='$topic'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $topictext = $row[topictext];

    formatTimestamp($time);
    echo "
    <html>
    <head><title>$sitename - $title</title>
    <style type=\"text/css\">
    a {
	color: Black;
	text-decoration: none;
    }
	a:hover {
    color: Red;
	text-decoration: underline;
    }
    .subject {
	font-size: 10px;
	font-family: Verdana;
	font-weight: bold;
     }
    .tablerow {
	font-family: Verdana;
	color: #000000;
	font-size: 10px;
    }
    </style>
    </head>
    <body bgcolor=\"#ffffff\" text=\"#000000\">
    <table border=\"0\" align=\"center\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"#ffffff\"><tr><td class=\"tablerow\">
    <center>
    $logo<br><br>
    <font class=\"subject\">
    <b>$title</b></font><br>
    <font class=subject><b>Date:</b> $datetime<br><b>Topic:</b> $topictext</font><br><br>
    </center>
    <font class=\"tablerow\">
    $hometext<br><br>
    $bodytext<br><br>
    $notes<br><br>
    </font>
    </td></tr></table></td></tr></table>
    <br><br><center>
    <font class=\"nav\">
    This article comes from $sitename<br>
    <a href=\"".$full_url."portal.php\">".$full_url."portal.php</a><br><br>
    The URL for this story is:<br>
    <a href=\"".$full_url."modules.php?modulename=$module_name&modulefile=article&storyid=$storyid\">".$full_url."modules.php?modulename=$module_name&modulefile=article&storyid=$storyid</a>
    </font>
    </td></tr></table>
    </body>
    </html>
    ";
}
PrintPage($storyid);
?>
