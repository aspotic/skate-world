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

if (eregi("block-latest_replies.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

global $table_posts, $timeoffset, $dateformat, $timecode;

$posts_list_this_many = 10;			// How many posts you would like to list
$smdir = "images/smilies/";			// Path to your smilies directory
$dsm = "info.gif";				// Path to the default smilie used if there isn't one present
$use_icons = "yes";			// Do you want to use topic icons?
$dont_fid_a = 24;					// Don't pull news from this fid
$dont_fid_b = 22;					// Don't pull news from this fid
$dont_fid_c = 61;					// Don't pull news from this fid
$dont_fid_d = 62;					// Don't pull news from this fid
$dont_fid_e = 63;					// Don't pull news from this fid

$query = mysql_query("SELECT * FROM $table_posts WHERE fid!='$dont_fid_a' && fid!='$dont_fid_b' && fid!='$dont_fid_c' && fid !='$dont_fid_d' && fid !='$dont_fid_e' && fid!='$dont_fid_f' ORDER BY dateline DESC LIMIT 0,$posts_list_this_many");
while ($posts = mysql_fetch_array($query)) {
    $tid = $posts['tid'];
	$query2 = mysql_query("SELECT * FROM $table_posts WHERE tid = $tid ORDER BY dateline ASC LIMIT 0,$posts_list_this_many");
	$thread = mysql_fetch_array($query2);
    $thread['subject'] = censor($thread['subject']);
	$subject = $thread['subject'];
    $author = $thread['author'];
    $athour = stripslashes($athour);
    $replies = stripslashes($replies);
	$subject = stripslashes($subject);
    $date = date("$dateformat", $thread['dateline'] + ($timeoffset * 3600));
    $time = date("$timecode", $thread['dateline'] + ($timeoffset * 3600));
    $Postdate = "<b>On</b> $date <b>At</b> $time";
	$icon = $posts[icon];
	if ($icon AND $use_icons == "yes") $icon = "<img src=\"$smdir/$icon\" border=\"0\">";
	else $icon = "<img src=\"$smdir/$dsm\" border=\"0\">";

 $posts_listed .= "$icon <font class=\"mediumtxt\"><a href=\"viewthread.php?tid=$tid\"><b>$subject</b></a></font><br> <b>By</b> <a href=\"member.php?action=viewpro&member=$author\"><u>$author</u></a><br>$Postdate<br>";

}
$blockfiletitle = "$posts_list_this_many Newest Posts";
$content = "<marquee direction=\"up\" scrolldelay=\"80\" scrollamount=\"2\" onMouseOver=\"this.stop();\" onMouseOut=\"this.start();\">$posts_listed</marquee>";

?>
