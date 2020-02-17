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

$index = 1;
$categories = 1;
$cat = $catid;
automated_news();

function theindex($catid) {
    global $storyhome, $topicname, $topicimage, $topictext, $datetime, $xmbuser, $cookie, $prefix, $articlecomm, $module_name, $portal_images;

    include("portal_header.php");
    if (isset($cookie[3])) {
	$storynum = $cookie[3];
    } else {
	$storynum = $storyhome;
    }
    mysql_query("update ".$prefix."_stories_cat set counter=counter+1 where catid='$catid'");
    $sql = "SELECT storyid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes, acomm, score, ratings FROM ".$prefix."_stories where catid='$catid' ORDER BY storyid DESC limit $storynum";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)){
	$s_sid = $row['storyid'];
	$aid = $row['aid'];
	$title = $row['title'];
	$time = $row['time'];
	$hometext = $row['hometext'];
	$bodytext = $row['bodytext'];
	$comments = $row['comments'];
	$counter = $row['counter'];
	$topic = $row['topic'];
	$informant = $row['informant'];
	$notes = $row['notes'];
	$acomm = $row['acomm'];
	$score = $row['score'];
	$ratings = $row['ratings'];
	getTopics($s_sid);
	formatTimestamp($time);
	$subject = stripslashes($subject);
	$hometext = stripslashes($hometext);
	$notes = stripslashes($notes);
	$introcount = strlen($hometext);
	$fullcount = strlen($bodytext);
	$totalcount = $introcount + $fullcount;
	$c_count = $comments;
	$r_options = "";
        if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
        if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
        if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
	if ($xmbuser) {
	    $the_icons = " | <a href=\"modules.php?modulename=News&amp;modulefile=print&amp;storyid=$s_sid\"><img src=\"$portal_images/printable.gif\" border=\"0\" alt=\"Printer Friendly Page\" title=\"Printer Friendly Page\" width=\"16\" height=\"11\"></a>&nbsp;&nbsp;<a href=\"modules.php?modulename=News&amp;modulefile=friend&amp;op=FriendSend&amp;sid=$s_sid\"><img src=\"$portal_images/subscribe.gif\" border=\"0\" alt=\"Send to a Friend\" title=\"Send to a Friend\" width=\"16\" height=\"11\"></a>";
	} else {
	    $the_icons = "";
	}
	$story_link = "<a href=\"modules.php?modulename=News&amp;modulefile=article&amp;storyid=$s_sid$r_options\">";
	$morelink = "(";
	if ($fullcount > 0 OR $c_count > 0 OR $articlecomm == 0 OR $acomm == 1) {
	    $morelink .= "$story_link<b>Read More...</b></a> | ";
	} else {
	    $morelink .= "";
	}
	if ($fullcount > 0) { $morelink .= "$totalcount bytes more | "; }
	if ($articlecomm == 1 AND $acomm == 0) {
	    if ($c_count == 0) { $morelink .= "$story_link comments?</a>"; } elseif ($c_count == 1) { $morelink .= "$story_link$c_count comment</a>"; } elseif ($c_count > 1) { $morelink .= "$story_link$c_count comments</a>"; }
	}
	$morelink .= "$the_icons";
	if ($score != 0) {
	    $rated = substr($score / $ratings, 0, 4);
	} else {
	    $rated = 0;
	}
	$morelink .= " | Score: $rated";
	$morelink .= ")";
	$morelink = str_replace(" |  | ", " | ", $morelink);
	$storyid = $s_sid;
	$sql2 = "select title from ".$prefix."_stories_cat where catid='$catid'";
	$result2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($result2);
	$title1 = $row2[title];
	
	$title = "$title1: $title";
	themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext);
    }
    include("portal_footer.php");
}

switch ($op) {

    case "newindex":
    loadtemplates('header,footer');
    eval("\$header = \"".template("header")."\";");
    echo $header;
	if ($catid == 0 OR $catid == "") {
	    Header("Location: modules.php?modulename=$module_name");
	}
	theindex($catid);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    break;

    default:
    Header("Location: modules.php?modulename=$module_name");

}

?>
