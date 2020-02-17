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
$index = 1;

function theindex($new_topic=0) {
    global $storyhome, $topicname, $topicimage, $topictext, $datetime, $xmbuser, $cookie, $prefix, $articlecomm, $bbname, $user_news, $portal_images, $db;
    
    include("portal_header.php");
    automated_news();
    if (isset($cookie[3]) AND $user_news == 1) {
	$storynum = $cookie[3];
    } else {
	$storynum = $storyhome;
    }
    if ($new_topic == 0) {
	$qdb = "WHERE (ihome='0' OR catid='0')";
	$home_msg = "";
    } else {
	$qdb = "WHERE topic='$new_topic'";
	$sql_a = "SELECT topictext FROM ".$prefix."_topics WHERE topicid='$new_topic'";
	$result_a = mysql_query($sql_a);
	$row_a = mysql_fetch_array($result_a);
	$numrows_a = mysql_num_rows($result_a);
	$topic_title = $row_a[topictext];
	OpenTable();
	if ($numrows_a == 0) {
	    echo "<center>$bbname<br><br>Search on This Topic<br><br>[ <a href=\"modules.php?modulename=News\">Go to News Index</a> | <a href=\"modules.php?modulename=Topics\">Select a New Topic</a> ]</center>";
	} else {
	    echo "<center>$bbname: $topic_title<br><br>"
		."[ <a href=\"portal.php\">Go to Home</a> | <a href=\"modules.php?modulename=Topics\">Select a New Topic</a> ]</center>";
	}
	CloseTable();
	echo "<br>";
    }
    $sql = "SELECT storyid, catid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes, acomm, score, ratings FROM ".$prefix."_stories $qdb ORDER BY storyid DESC limit $storynum";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
	$s_sid = $row['storyid'];
	$catid = $row['catid'];
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
	if ($catid > 0) {
	    $sql2 = "SELECT title FROM ".$prefix."_stories_cat WHERE catid='$catid'";
	    $result2 = mysql_query($sql2);
	    $row2 = mysql_fetch_array($result2);
	    $cattitle = $row2['title'];
	}
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
	    $the_icons = " | <a href=\"modules.php?modulename=News&amp;modulefile=print&amp;storyid=$s_sid\"><img src=\"$portal_images/printable.gif\" border=\"0\" alt=\"Printer Friendly Page\" title=\"Printer Friendly Page\" width=\"16\" height=\"11\"></a>&nbsp;&nbsp;<a href=\"modules.php?modulename=News&amp;modulefile=friend&amp;op=FriendSend&amp;storyid=$s_sid\"><img src=\"$portal_images/subscribe.gif\" border=\"0\" alt=\"Send to a Friend\" title=\"Send to a Friend\" width=\"16\" height=\"11\"></a>";
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
	    if ($c_count == 0) { $morelink .= "$story_link comments?</a>"; } elseif ($c_count == 1) { $morelink .= "$story_link$c_count comments</a>"; } elseif ($c_count > 1) { $morelink .= "$story_link$c_count comments</a>"; }
	}
	$morelink .= "$the_icons";
	$storyid = $s_sid;
	if ($catid != 0) {
	    $sql3 = "SELECT title FROM ".$prefix."_stories_cat WHERE catid='$catid'";
	    $result3 = mysql_query($sql3);
	    $row3 = mysql_fetch_array($result3);
	    $title1 = $row3[title];
	    $title = "<a href=\"modules.php?modulename=News&amp;modulefile=categories&amp;op=newindex&amp;catid=$catid\"><font class=\"storycat\">$title1</font></a>: $title";
	    $morelink .= " | <a href=\"modules.php?modulename=News&amp;modulefile=categories&amp;op=newindex&amp;catid=$catid\">$title1</a>";
	}
	if ($score != 0) {
	    $rated = substr($score / $ratings, 0, 4);
	} else {
	    $rated = 0;
	}
	$morelink .= " | Score: $rated";
	$morelink .= ")";
	$morelink = str_replace(" |  | ", " | ", $morelink);
	themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext);
    }
    include("portal_footer.php");
}

function rate_article($storyid, $score) {
    global $prefix, $ratecookie, $sitename, $r_options;
    $score = intval($score);
    if ($score) {
	if ($score > 5) { $score = 5; }
	if ($score < 1) { $score = 1; }
	if ($score != 1 AND $score != 2 AND $score != 3 AND $score != 4 AND $score != 5) {
	    Header("Location: index.php");
	    die();
	}
	if (isset($ratecookie)) {
	    $rcookie = base64_decode($ratecookie);
	    $r_cookie = explode(":", $rcookie);
	}
	for ($i=0; $i < sizeof($r_cookie); $i++) {
	    if ($r_cookie[$i] == $storyid) {
		$a = 1;
	    }
	}
	if ($a == 1) {
	    Header("Location: modules.php?modulename=News&op=rate_complete&storyid=$storyid&rated=1");
	} else {
	    $result = mysql_query("update ".$prefix."_stories set score=score+$score, ratings=ratings+1 where storyid='$storyid'");
	    $info = base64_encode("$rcookie$storyid:");
	    setcookie("ratecookie","$info",time()+3600);
	    Header("Location: modules.php?modulename=News&op=rate_complete&storyid=$storyid$r_options");
	}
    } else {
	include("portal_header.php");
    title("$sitename: Article Rating");
    OpenTable();
	echo "<center>You didn't selected any score for the article!<br><br>[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]</center>";
    CloseTable();
	include("portal_footer.php");
    }
}

function rate_complete($storyid, $rated=0) {
    global $sitename, $xmbuser, $cookie;
    $r_options = "";
    if ($xmbuser) {
	if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
	if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
        if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
    }
    include("portal_header.php");
    title("$sitename: Article Rating");
    OpenTable();
    if ($rated == 0) {
	echo "<center>Thanks for voting for this article!<br><br>"
	    ."[ <a href=\"modules.php?modulename=News&amp;modulefile=article&amp;storyid=$storyid$r_options\">Back to Article's Page</a> ]</center>";
    } elseif ($rated == 1) {
	echo "<center>Sorry, you already voted for this article recently!<br><br>"
	    ."[ <a href=\"modules.php?modulename=News&amp;modulefile=article&amp;storyid=$storyid$r_options\">Back to Article's Page</a> ]</center>";
    }
    CloseTable();
    include("portal_footer.php");
}

switch ($op) {

    default:
    theindex($new_topic);
    break;

    case "rate_article":
    rate_article($storyid, $score);
    break;

    case "rate_complete":
    rate_complete($storyid, $rated);
    break;

}
end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>
