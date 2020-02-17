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

if (eregi("block-forum_stats.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}
global $table_threads, $table_posts, $table_forums, $table_members, $status;


$restrict = 'WHERE';

 switch($status){
	case 'member';
		$restrict .= " f.private !='3' AND";

	case 'Moderator';

	case 'Super Moderator';
		$restrict .= " f.private != '2' AND";

	case 'Administrator';
		$restrict .= " f.userlist = '' AND";

	case 'Super Administrator';
		break;
}
	$query 		= mysql_query("SELECT COUNT(*) FROM $table_threads");
	$threads 	= mysql_result($query, 0);
	$query 		= mysql_query("SELECT COUNT(*) FROM $table_posts");
	$posts 		= mysql_result($query, 0);
	$query 		= mysql_query("SELECT COUNT(*) FROM $table_forums WHERE type='forum'");
	$forums 	= mysql_result($query, 0);
	$query 		= mysql_query("SELECT COUNT(*) FROM $table_forums WHERE type='forum' AND status='on'");
	$forumsa 	= mysql_result($query, 0);
	$query 		= mysql_query("SELECT COUNT(*) FROM $table_members");
	$members 	= mysql_result($query, 0);
	$query 		= mysql_query("SELECT COUNT(*) FROM $table_members WHERE postnum!='0'");
	$membersact 	= mysql_result($query, 0);
	$mapercent  	= number_format(($membersact*100/$members), 2).'%';
	$query 		= mysql_query("SELECT t.views, t.tid, t.subject FROM $table_threads t, $table_forums f $addon $restrict f.fid = t.fid ORDER BY views DESC LIMIT 0,5");
	while($views = mysql_fetch_array($query)) {
		$views_subject 	 = stripslashes($views[subject]);
		$viewmost 	.= "<li><a href=\"viewthread.php?tid=$views[tid]\">$views_subject</a> ($views[views]$lang[viewsl])</li><br />";
	}

	$query = mysql_query("SELECT t.replies, t.tid, t.subject FROM $table_threads t, $table_forums f $addon $restrict f.fid = t.fid ORDER BY replies DESC LIMIT 0,5");
	while($reply = mysql_fetch_array($query)) {
		$reply_subject 	 = stripslashes($reply[subject]);
		$replymost 	.= "<li><a href=\"viewthread.php?tid=$reply[tid]\">$reply_subject</a> ($reply[replies]$lang[repliesl])</li><br />";
	}

	$query = mysql_query("SELECT t.lastpost, t.tid, t.subject FROM $table_threads t, $table_forums f $addon $restrict f.fid = t.fid ORDER BY lastpost DESC LIMIT 0,5");
	while($last = mysql_fetch_array($query)) {
		$lpdate 	 = gmdate("$dateformat", $last[lastpost] + ($timeoffset * 3600) + ($addtime * 3600));
		$lptime 	 = gmdate("$timecode", $last[lastpost] + ($timeoffset * 3600) + ($addtime * 3600));
		$thislast 	 = "$lang[lpoststats]$lang[lastreply1]$lpdate$lang[textat] $lptime";
		$last_subject 	 = stripslashes($last[subject]);
		$latest 	.= "<a href=\"viewthread.php?tid=$last[tid]\">$last_subject</a> ($thislast)<br/>";
	}

	$query 		= mysql_query("SELECT f.posts, f.threads, f.fid, f.name FROM $table_forums f $restrict f.fid = f.fid ORDER BY posts DESC LIMIT 0, 1");
	$pop 		= mysql_fetch_array($query);
	$popforum 	= "<a href=\"forumdisplay.php?fid=$pop[fid]\"><b>$pop[name]</b></a>";

	$mempost 	= 0;
	$query 		= mysql_query("SELECT SUM(postnum) FROM $table_members");
	$mempost 	= number_format((mysql_result($query, 0) / $members), 2);

	$forumpost 	= 0;
	$query 		= mysql_query("SELECT SUM(posts) FROM $table_forums");
	$forumpost 	= number_format((mysql_result($query, 0) / $forums), 2);

	$threadreply 	= 0;
	$query 		= mysql_query("SELECT SUM(replies) FROM $table_threads");
	$threadreply 	= number_format((mysql_result($query, 0) / $threads), 2);

	$query 		= mysql_query("SELECT lastpost FROM $table_threads ORDER BY lastpost LIMIT 0, 1");
	$postsday 	= number_format(($posts / ((time() - mysql_result($query, 0)) / 86400)), 2);

	$query 		= mysql_query("SELECT regdate FROM $table_members ORDER BY regdate LIMIT 0, 1");
	$memberdays 	=
	$membersday 	=
	$membersday 	= number_format(($members / ((time() - mysql_result($query, 0)) / 86400)), 2);

	$timesearch = time() - 86400;
	$eval = $lang_evalnobestmember;

	$query = mysql_query("SELECT author, Count(*) AS Total FROM $table_posts WHERE dateline >= '$timesearch' GROUP BY author ORDER BY Total DESC");
	$info = mysql_fetch_array($query);

	$bestmember = $info['author'];
	if($bestmember == '') {
		$bestmember = 'Nobody';
		$bestmemberpost = 'No';
	}else{
		if($info['Total'] != 0){
			$membesthtml = "<a href=\"member.php?action=viewpro&member=".rawurlencode($bestmember)."\"><b>$bestmember</b></a>";
			$bestmemberpost = $info['Total'];
		}
	}

$content .= "<marquee direction=\"up\" scrolldelay=\"80\" scrollamount=\"2\" onMouseOver=\"this.stop();\" onMouseOut=\"this.start();\">
<b>On the $bbname, there are</b>:
<br />
$posts posts
<br />
$threads topics
<br />
$forums forums ($forumsa active)
<br />
$members members
<hr>
<b>Top 5 most viewed topics:</b>
<br />
$viewmost
<hr>
<b>Top 5 most replied to topics:</b>
<br />
$replymost
<hr>
<b>The most popular forum is</b>
<br />
[$popforum]
<br />
with $pop[posts] posts and $pop[threads] topics
<br />
</marquee>";
 ?>
