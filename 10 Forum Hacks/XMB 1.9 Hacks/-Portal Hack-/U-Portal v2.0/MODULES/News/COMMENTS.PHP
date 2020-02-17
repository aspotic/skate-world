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

if (!eregi("modules.php", $_SERVER["PHP_SELF"])) {
    die("You can't access this file directly...");
}

require_once("portal_main.php");
$module_name = basename(dirname(__FILE__));

function format_url($comment) {
    global $full_url;
    unset($location);
    $comment = $comment;
    $links = array();
    $hrefs = array();
    $pos = 0;
    while (!(($pos = strpos($comment,"<",$pos)) === false)) {
	$pos++;
	$endpos = strpos($comment,">",$pos);
	$tag = substr($comment,$pos,$endpos-$pos);
	$tag = trim($tag);
	if (isset($location)) {
    	    if (!strcasecmp(strtok($tag," "),"/A")) {
        	$link = substr($comment,$linkpos,$pos-1-$linkpos);
        	$links[] = $link;
        	$hrefs[] = $location;
        	unset($location);
    	    }
	    $pos = $endpos+1;
	} else {
	    if (!strcasecmp(strtok($tag," "),"A")) {
		if (eregi("HREF[ \t\n\r\v]*=[ \t\n\r\v]*\"([^\"]*)\"",$tag,$regs));
		else if (eregi("HREF[ \t\n\r\v]*=[ \t\n\r\v]*([^ \t\n\r\v]*)",$tag,$regs));
		else $regs[1] = "";
		if ($regs[1]) {
	    	    $location = $regs[1];
		}
		$pos = $endpos+1;
		$linkpos = $pos;
	    } else {
		$pos = $endpos+1;
	    }
	}
    }
    for ($i=0; $i<sizeof($links); $i++) {
	if (!eregi("http://", $hrefs[$i])) {
	    $hrefs[$i] = $full_url;
	} elseif (!eregi("mailto://", $hrefs[$i])) {
	    $href = explode("/",$hrefs[$i]);
	    $href = " [$href[2]]";
	    $comment = ereg_replace(">$links[$i]</a>", "title='$hrefs[$i]'> $links[$i]</a>$href", $comment);
	}
    }
    return($comment);
}


function nocomm() {
    OpenTable();
    echo "<center>Sorry, Comments are not available for this article.</center>";
    CloseTable();
}

function navbar($storyid, $title, $thold, $mode, $order) {
    global $xmbuser, $altbg2, $altbg1, $bordercolor, $anonpost, $status, $module_name, $prefix;
    $sql = "SELECT * FROM ".$prefix."_comments WHERE storyid='$storyid'";
    $query = mysql_query($sql);
    if(!$query) {
	$count = 0;
    } else {
	$count = mysql_num_rows($query);
    }
    if(!isset($thold)) {
	$thold=0;
    }
    echo "\n\n<!-- COMMENTS NAVIGATION BAR START -->\n\n";
    OpenTable();
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    if($title) {
	echo "<tr><td bgcolor=\"$bgcolor2\" align=\"center\" class=\"tablerow\">";
	if($xmbuser) {
	    echo "Logged in as <b>$xmbuser</b>";
	} else {

	    echo "<a href=\"misc.php?action=login\"><font class=\"nav\">Login</font></a> Or ";
        echo "<a href=\"member.php?action=reg\"><font class=\"nav\">Create an Account</font></a>";
	}
    echo "| There are ";
	if(($count==1)) {
	    echo " <B>$count</B> comment";
	} else {
	    echo " <B>$count</B> comments";
	}
	    echo "</td></tr>\n";
    }
    echo "<tr><td bgcolor=\"$altbg2\" align=\"center\" width=\"100%\" class=\"tablerow\">\n"
	."<table border=\"0\"><tr><td class=\"tablerow\">\n"
	."<form method=\"post\" action=\"modules.php?modulename=$module_name&modulefile=article\">\n"
	."Threshold <select name=\"thold\">\n"
	."<option value=\"-1\"";
    if ($thold == -1) {
	echo " selected";
    }
    echo ">-1</option>\n"
         ."<option value=\"0\"";
    if ($thold == 0) {
	echo " selected";
    }
    echo ">0</option>\n"
	 ."<option value=\"1\"";
    if ($thold == 1) {
	echo " selected";
    }
    echo ">1</option>\n"
	 ."<option value=\"2\"";
    if ($thold == 2) {
	echo " selected";
    }
    echo ">2</option>\n"
	 ."<option value=\"3\"";
    if ($thold == 3) {
	echo " selected";
    }
    echo ">3</option>\n"
	 ."<option value=\"4\"";
    if ($thold == 4) {
	echo " selected";
    }
    echo ">4</option>\n"
	 ."<option value=\"5\"";
    if ($thold == 5) {
	echo " selected";
    }
    echo ">5</option>\n"
	 ."</select> <select name=mode>"
	 ."<option value=\"nocomments\"";
    if ($mode == 'nocomments') {
	echo " selected";
    }
    echo ">No Comments</option>\n"
	 ."<option value=\"nested\"";
    if ($mode == 'nested') {
	echo " selected";
    }
    echo ">Nested</option>\n"
	 ."<option value=\"flat\"";
    if ($mode == 'flat') {
	echo " selected";
    }
    echo ">Flat</option>\n"
	 ."<option value=\"thread\"";
    if (!isset($mode) || $mode=='thread' || $mode=="") {
	echo " selected";
    }
    echo ">Thread</option>\n"
	 ."</select> <select name=\"order\">"
	 ."<option value=\"0\"";
    if (!$order) {
	echo " selected";
    }
    echo ">Oldest First</option>\n"
	 ."<option value=\"1\"";
    if ($order==1) {
	echo " selected";
    }
    echo ">Newest First</option>\n"
    	 ."<option value=\"2\"";
    if ($order==2) {
	echo " selected";
    }
    echo ">Highest Scores First</option>\n"
	 ."</select>\n"
	 ."<input type=\"hidden\" name=\"storyid\" value=\"$storyid\">\n"
	 ."<input type=\"submit\" value=\"Refresh\"></form>\n";
    if ($anonpost==1 OR $status == "Super Administrator" || $status == "Administrator" OR $xmbuser) {
	echo "</font></td><td bgcolor=\"$bgcolor1\" valign=\"top\" class=\"tablerow\"><form action=\"modules.php?modulename=$module_name&amp;modulefile=comments\" method=\"post\">"
	    ."<input type=\"hidden\" name=\"pid\" value=\"$pid\">"
	    ."<input type=\"hidden\" name=\"storyid\" value=\"$storyid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"Reply\">"
	    ."&nbsp;&nbsp;<input type=\"submit\" value=\"Post Comment\">";
    }
    echo "</font></form></td></tr></table>"
	."</td></tr><tr><td bgcolor=\"$bgcolor2\" align=\"center\" class=\"tablerow\">The comments are owned by the poster. We aren't responsible for their content.</td></tr></table>"
	."\n\n<!-- COMMENTS NAVIGATION BAR END -->\n\n";
    CloseTable();
    if ($anonpost == 0 AND !$xmbuser) {
    echo "<br>";
	OpenTable();
	echo "<center>No Comments Allowed for Anonymous, please <a href=\"member.php?action=reg\">register</a></center>";
	CloseTable();
    }
}

function DisplayKids ($tid, $mode, $order=0, $thold=0, $level=0, $dummy=0, $tblwidth=99) {
    global $datetime, $status, $altbg1, $xmbuser, $cookie, $reasons, $anonymous, $anonpost, $commentlimit, $prefix, $db, $module_name, $table_members;
    $comments = 0;
    cookiedecode($xmbuser);
    $sql = "SELECT tid, pid, storyid, date, name, email, host_name, subject, comment, score, reason FROM ".$prefix."_comments WHERE pid='$tid' ORDER BY date, tid";
    $result = mysql_query($sql);
    if ($mode == 'nested') {
	while ($row = mysql_fetch_array($result)) {
	    $r_tid = $row['tid'];
	    $r_pid = $row['pid'];
	    $r_sid = $row['storyid'];
	    $r_date = $row['date'];
	    $r_name = $row['name'];
	    $r_email = $row['email'];
	    $r_host_name = $row['host_name'];
	    $r_subject = $row['subject'];
	    $r_comment = $row['comment'];
	    $r_score = $row['score'];
	    $r_reason = $row['reason'];
	    if($r_score >= $thold) {
		if (!isset($level)) {
		} else {
		    if (!$comments) {
			echo "<ul>";
			$tblwidth -= 5;
		    }
		}
		$comments++;
		if (!eregi("[a-z0-9]",$r_name)) $r_name = $anonymous;
		if (!eregi("[a-z0-9]",$r_subject)) $r_subject = "[No Subject]";
		$r_bgcolor = ($dummy%2)?"":"#E6E6D2";
		echo "<a name=\"$r_tid\">";
		echo "<table border=\"0\"><tr bgcolor=\"$altgb1\" class=\"tablerow\"><td>";
		formatTimestamp($r_date);
		if ($r_email) {
		    echo "<b>$r_subject</b>";
		    if(!$cookie[7]) {
			echo "(Score: $r_score";
			if($r_reason>0) echo ", $reasons[$r_reason]";
			echo ")";
		    }
		    echo "<br>by <a href=\"mailto:$r_email\">$r_name</a> on $datetime";
		} else {
		    echo "<b>$r_subject</b>";
		    if(!$cookie[7]) {
			echo "(Score: $r_score";
			if($r_reason>0) echo ", $reasons[$r_reason]";
			echo ")";
		    }
		    echo "<br>by $r_name on $datetime";
		}			
		if ($r_name != $anonymous) { 
		    $sql2 = "SELECT * FROM $table_members WHERE username='$r_name'";
		    $result2 = mysql_query($sql2);
		    $row2 = mysql_fetch_array($result2);
		    echo "<br>(<a href=\"member.php?action=viewpro&member=$row2[username]\">User Info</a> | <a href=\"u2u.php?action=send&username=freewill46$row2[username]\">Send a Message</a>) ";
		}
		$sql_url = "SELECT site FROM $table_members WHERE username='$r_name'";
		$result_url = mysql_query($sql_url);
		$row_url = mysql_fetch_array($result_url);
		$url = $row_url['site'];
		if ($url != "http://" AND $url != "" AND eregi("http://", $url)) { echo "<a href=\"$url\" target=\"new\">$url</a> "; }
		echo "</font></td></tr><tr><td>";
		if(($cookie[10]) && (strlen($r_comment) > $cookie[10])) echo substr("$r_comment", 0, $cookie[10])."<br><br><b><a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;storyid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">Read the rest of this comment...</a></b>";
		elseif(strlen($r_comment) > $commentlimit) echo substr("$r_comment", 0, $commentlimit)."<br><br><b><a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;storyid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">Read the rest of this comment...</a></b>";
		else echo $r_comment;
		echo "</td></tr></table><br><br>";
		if ($anonpost==1 OR $status == "Super Administrator" || $status == "Administrator" OR $xmbuser) {
		    echo "<font class=\"tablerow\"> [ <a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;op=Reply&amp;pid=$r_tid&amp;storyid=$r_sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">Reply to This</a>";
		}
		echo " ]</font><br><br>";
		DisplayKids($r_tid, $mode, $order, $thold, $level+1, $dummy+1, $tblwidth);
	    }
	}
	} elseif ($mode == 'flat') {
	    while ($row = mysql_fetch_array($result)) {
		$r_tid = $row['tid'];
		$r_pid = $row['pid'];
		$r_sid = $row['storyid'];
		$r_date = $row['date'];
		$r_name = $row['name'];
		$r_email = $row['email'];
		$r_host_name = $row['host_name'];
		$r_subject = $row['subject'];
		$r_comment = $row['comment'];
		$r_score = $row['score'];
		$r_reason = $row['reason'];
		if($r_score >= $thold) {
		    if (!eregi("[a-z0-9]",$r_name)) $r_name = $anonymous;
		    if (!eregi("[a-z0-9]",$r_subject)) $r_subject = "[No Subject]";
		    echo "<a name=\"$r_tid\">";
		    echo "<hr><table width=\"99%\" border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\"><tr class=\"tablerow\"><td bgcolor=\"$altbg1\">";
		    formatTimestamp($r_date);
		    if ($r_email) {
			echo "<b>$r_subject</b>";
			if(!$cookie[7]) {
			    echo "(Score: $r_score";
			    if($r_reason>0) echo ", $reasons[$r_reason]";
			    echo ")";
			}
			echo "<br>by <a href=\"mailto:$r_email\">$r_name</a><b>($r_email)</b> on $datetime";
 		    } else {
			echo "<b>$r_subject</b> <font class=\"tablerow\">";
			if(!$cookie[7]) {
			    echo "(Score: $r_score";
			    if($r_reason>0) echo ", $reasons[$r_reason]";
			    echo ")";
			}
			echo "<br>by $r_name on $datetime";
		    }			
		    if ($r_name != $anonymous) {
			$sql2 = "SELECT username FROM $table_members WHERE username='$r_name'";
			$result2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($result2);
			echo "<br>(<a href=\"member.php?action=viewpro&member=$row2[username]\">User Info</a> | <a href=\"u2u.php?action=send&username=$row2[username]\">Send a Message</a>) ";
		    }
		    $sql_url = "SELECT site FROM $table_members WHERE username='$r_name'";
		    $result_url = mysql_query($sql_url);
		    $row_url = mysql_fetch_array($result_url);
		    $url = $row_url[site];
		    if ($url != "http://" AND $url != "" AND eregi("http://", $url)) { echo "<a href=\"$url\" target=\"new\">$url</a> "; }
		    echo "</font></td></tr><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">";
		    if(($cookie[10]) && (strlen($r_comment) > $cookie[10])) echo substr("$r_comment", 0, $cookie[10])."<br><br><b><a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;storyid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">Read the rest of this comment...</a></b>";
		    elseif(strlen($r_comment) > $commentlimit) echo substr("$r_comment", 0, $commentlimit)."<br><br><b><a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;storyid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">Read the rest of this comment...</a></b>";
		    else echo $r_comment;
		    echo "</td></tr></table><br><br>";
		    if ($anonpost==1 OR $status == "Super Administrator" || $status == "Administrator" OR $xmbuser) {
			echo "<font class=\"tablerow\"> [ <a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;op=Reply&amp;pid=$r_tid&amp;storyid=$r_sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">Reply to This</a>";
		    }
		    echo " ]</font><br><br>";
		    DisplayKids($r_tid, $mode, $order, $thold);
		}
	    }
	    } else {
        while ($row = mysql_fetch_array($result)) {
		$r_tid = $row['tid'];
		$r_pid = $row['pid'];
		$r_sid = $row['storyid'];
		$r_date = $row['date'];
		$r_name = $row['name'];
		$r_email = $row['email'];
		$r_host_name = $row['host_name'];
		$r_subject = $row['subject'];
		$r_comment = $row['comment'];
		$r_score = $row['score'];
		$r_reason = $row['reason'];
		if($r_score >= $thold) {
		    if (!isset($level)) {
		    } else {
			if (!$comments) {
			    echo "<ul>";
			}
		    }
		    $comments++;
		    if (!eregi("[a-z0-9]",$r_name)) $r_name = $anonymous;
		    if (!eregi("[a-z0-9]",$r_subject)) $r_subject = "[No Subject]";
		    formatTimestamp($r_date);
		    echo "<li><font class=\"tablerow\"><a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;op=showreply&amp;tid=$r_tid&amp;storyid=$r_sid&amp;pid=$r_pid&amp;mode=$mode&amp;order=$order&amp;thold=$thold#$r_tid\">$r_subject</a> by $r_name on $datetime</font><br>";
		    DisplayKids($r_tid, $mode, $order, $thold, $level+1, $dummy+1);
		}
	    }
	}
    if ($level && $comments) {
        echo "</ul>";
    }
}

function DisplayBabies ($tid, $level=0, $dummy=0) {
    global $datetime, $anonymous, $prefix, $db, $module_name;
    $comments = 0;
    $sql = "SELECT tid, pid, storyid, date, name, email, host_name, subject, comment, score, reason FROM ".$prefix."_comments WHERE pid='$tid' ORDER BY date, tid";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $r_tid = $row['tid'];
        $r_pid = $row['pid'];
        $r_sid = $row['storyid'];
        $r_date = $row['date'];
        $r_name = $row['name'];
        $r_email = $row['email'];
        $r_host_name = $row['host_name'];
        $r_subject = $row['subject'];
        $r_comment = $row['comment'];
        $r_score = $row['score'];
        $r_reason = $row['reason'];
	if (!isset($level)) {
	} else {
	    if (!$comments) {
		echo "<ul>";
	    }
	}
	$comments++;
	if (!eregi("[a-z0-9]",$r_name)) { $r_name = $anonymous; }
	if (!eregi("[a-z0-9]",$r_subject)) { $r_subject = "[No Subject]"; }
	formatTimestamp($r_date);
	echo "<a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;op=showreply&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">$r_subject</a></font><font class=\"jubject\"> by $r_name on $datetime<br>";
	DisplayBabies($r_tid, $level+1, $dummy+1);
    } 
    if ($level && $comments) {
    	echo "</ul>";
    }
}

function DisplayTopic ($storyid, $pid=0, $tid=0, $mode="thread", $order=0, $thold=0, $level=0, $nokids=0) {
    global $hr, $xmbuser, $datetime, $cookie, $mainfile, $status, $commentlimit, $anonymous, $reasons, $anonpost, $foot1, $foot2, $foot3, $foot4, $prefix, $acomm, $articlecomm, $db, $module_name, $full_url, $table_members;
    if($mainfile) {
	global $title, $altbg1, $altbg2, $tablespace, $borderwidth, $bordercolor;
    } else {
	global $title, $altbg1, $altbg2, $tablespace, $borderwidth, $bordercolor;
	include("portal_main.php");
	include("portal_header.php");
    }
    if ($pid!=0) {
	include("portal_header.php");
    }
    $count_times = 0;
    cookiedecode($xmbuser);
    $q = "SELECT tid, pid, storyid, date, name, email, host_name, subject, comment, score, reason FROM ".$prefix."_comments WHERE storyid='$storyid' and pid='$pid'";
    if($thold != "") {
	$q .= " AND score>='$thold'";
    } else {
	$q .= " AND score>='0'";
    }
    if ($order==1) $q .= " ORDER BY date DESC";
    if ($order==2) $q .= " ORDER BY score DESC";
    $something = mysql_query($q);
    $num_tid = mysql_num_rows($something);
    if ($acomm == 1) {
	nocomm();
    }
    if (($acomm == 0) AND ($articlecomm == 1)) {
	navbar($storyid, $title, $thold, $mode, $order);
    }
    while ($count_times < $num_tid) {
	echo "<br>";
	OpenTable();
	$row = mysql_fetch_array($something);
	$tid = $row['tid'];
	$pid = $row['pid'];
	$sid = $row['storyid'];
	$date = $row['date'];
	$c_name = $row['name'];
	$email = $row['email'];
	$host_name = $row['host_name'];
	$subject = $row['subject'];
	$comment = $row['comment'];
	$score = $row['score'];
	$reason = $row['reason'];
	if ($c_name == "") { $c_name = $anonymous; }
	if ($subject == "") { $subject = "[No Subject]"; }
	echo "<a name=\"$tid\"></a>";
	echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\"><tr><td bgcolor=\"$altbg1\" width=\"100%\" class=\"tablerow\">";
	formatTimestamp($date);
	if ($email) {
	    echo "<b>$subject</b>";
	    if(!$cookie[7]) {
		echo "(Score: $score";
		if($reason>0) echo ", $reasons[$reason]";
		echo ")";
	    }
	    echo "<br>by <a href=\"mailto:$email\"><b>$c_name</b></a> on $datetime";
	   } else {
	    echo "<b>$subject</b>";
	    if(!$cookie[7]) {
		echo "(Score: $score";
		if($reason>0) echo ", $reasons[$reason]";
		echo ")";
	    }
	    echo "<br>by $c_name on $datetime";
	    }
		
	if ($c_name != $anonymous) {
	    $sql2 = "SELECT username FROM $table_members WHERE username='$c_name'";
	    $result2 = mysql_query($sql2);
	    $row2 = mysql_fetch_array($result2);
	    echo "<br>(<a href=\"member.php?action=viewpro&member=$row2[username]\">User Info</a> | <a href=\"u2u.php?action=send&username=$row2[username]\">Send a Message</a>) ";
	}
	$sql_url = "SELECT site FROM $table_members WHERE username='$c_name'";
	$result_url = mysql_query($sql_url);
	$row_url = mysql_fetch_array($result_url);
	$url = $row_url[site];
	if ($url != "http://" AND $url != "" AND eregi("http://", $url)) { echo "<a href=\"$url\" target=\"new\">Home Page</a> "; }

	if($status == "Super Administrator" || $status == "Administrator") {
	    $sql = "SELECT host_name FROM ".$prefix."_comments WHERE tid='$tid'";
	    $result = mysql_query($sql);
	    $row = mysql_fetch_array($result);
	    $host_name = $row['host_name'];
	    echo "<br><b>(IP: $host_name)</b>";
	}
	echo "</font></td></tr><tr><td bgcolor=\"$altbg1\" width=\"100%\" class=\"tablerow\">";
	if(($cookie[10]) && (strlen($comment) > $cookie[10])) echo substr("$comment", 0, $cookie[10])."<br><br><b><a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;storyid=$storyid&tid=$tid&mode=$mode&order=$order&thold=$thold\">Read the rest of this comment...</a></b>";
	elseif(strlen($comment) > $commentlimit) echo substr("$comment", 0, $commentlimit)."<br><br><b><a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;storyid=$storyid&tid=$tid&mode=$mode&order=$order&thold=$thold\">Read the rest of this comment...</a></b>";
	else echo $comment;
	echo "</td></tr><tr><td bgcolor=\"$altbg1\" width=\"100%\" class=\"tablerow\">";
	if ($anonpost==1 OR $status == "Super Administrator" || $status == "Administrator" OR $xmbuser) {
	    echo "<font class=\"nav\"> [ <a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;op=Reply&amp;pid=$tid&amp;storyid=$storyid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">Reply to This</a>";
	}
	if ($pid != 0) {
	    $sql = "SELECT pid FROM ".$prefix."_comments WHERE tid='$pid'";
	    $result = mysql_query($sql);
	    $row = mysql_fetch_array($result);
	    $erin = $row[pid];
	    echo " | <a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;storyid=$storyid&amp;pid=$erin&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">Parent</a>";
	}
	if($status == "Super Administrator" || $status == "Administrator") {
	    echo " |<font class=\"nav\"> <a href=\"admin.php?op=RemoveComment&amp;tid=$tid&amp;storyid=$storyid\">Delete</a> ]</font><br><br>";
	} elseif ($anonpost != 0 OR $status == "Super Administrator" || $status == "Administrator" OR $xmbuser) {
	    echo " ]</font><br><br>";
	}
		
	DisplayKids($tid, $mode, $order, $thold, $level);
	echo "</ul>";
	if($hr) echo "<hr noshade size=\"1\">";
	$count_times += 1;
    echo "</td></tr></table>";
	CloseTable();
    }
    if ($pid==0) {
	return array($storyid, $pid, $subject);

    } else {
	include("portal_footer.php");
    }
}

function singlecomment($tid, $storyid, $mode, $order, $thold) {
    global $module_name, $xmmbuser, $cookie, $datetime, $altbg1, $altbg2, $status, $anonpost, $prefix, $db;
    include("portal_header.php");
    $sql = "SELECT date, name, email, subject, comment, score, reason FROM ".$prefix."_comments WHERE tid='$tid' AND storyid='$storyid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_row($result);
    $date = $row['date'];
    $name = $row['name'];
    $email = $row['email'];
    $subject = $row['subject'];
    $comment = $row['comment'];
    $score = $row['score'];
    $reason = $row['reason'];
    $titlebar = "<b>$subject</b>";
    if($name == "") $name = $anonymous;
    if($subject == "") $subject = "[No Subject]";
    OpenTable();
    echo "<table width=\"100%\" border=\"0\"><tr class=\"tablerow\"><td width=\"500\">";
    formatTimestamp($date);
    if($email) echo "<b>$subject</b>(Score: $score)<br>by <a href=\"mailto:$email\">$name</a> <b>($email)</b> on $datetime";
    else echo "<b>$subject</b> (Score: $score)<br>by $name on $datetime";
    echo "</td></tr><tr><td>$comment</td></tr></table><br><br>";
    if ($anonpost==1 OR $status == "Super Administrator" || $status == "Administrator" OR $xmbuser) {
	echo "<font class=content> [ <a href=\"modules.php?modulename=$module_name&amp;modulefile=comments&amp;op=Reply&amp;pid=$tid&amp;storyid=$storyid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">Reply to This</a> | <a href=\"modules.php?modulename=$module_name&amp;modulefile=article&amp;storyid=$storyid&mode=$mode&order=$order&thold=$thold\">Root</a>";
    }
    echo " ]";
    CloseTable();
    include("portal_footer.php");
}

function reply($pid, $storyid, $mode, $order, $thold) {
    include("portal_header.php");
    global $module_name, $xmbuser, $cookie, $datetime, $altbg1, $altbg2, $prefix, $db, $anonpost, $AllowableHTML, $borderwidth, $tablespace, $bordercolor;
    if ($anonpost == 0 AND !$xmbuser) {
	OpenTable();
	echo "<center><b>Comment Post</b>t</center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center>No Comments Allowed for Anonymous, please <a href=\"member.php?action=reg\">register</a><br><br>[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]</center>";
	CloseTable();
    } else {
	if ($pid != 0) {
	    $sql = "SELECT date, name, email, subject, comment, score FROM ".$prefix."_comments WHERE tid='$pid'";
	    $result = mysql_query($sql);
	    $row = mysql_fetch_array($result);
	    $date = $row['date'];
	    $name = $row['name'];
	    $email = $row['email'];
	    $subject = $row['subject'];
	    $comment = $row['comment'];
	    $score = $row['score'];
	} else {
	    $sql = "SELECT time, title, hometext, bodytext, informant, notes FROM ".$prefix."_stories WHERE storyid='$storyid'";
	    $result = mysql_query($sql);
	    $row = mysql_fetch_array($result);
        $date = $row['time'];
	    $subject = $row['title'];
	    $temp_comment = $row['hometext'];
	    $comment2 = $row['bodytext'];
	    $name = $row['informant'];
	    $notes = $row['notes'];
	}
	if($comment == "") {
	    $comment = "$temp_comment<br><br>$comment2";
	}
	OpenTable();
	echo "<center><b>Comment Post</b></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	if ($name == "") $name = $anonymous;
	if ($subject == "") $subject = "[No Subject]";
	formatTimestamp($date);
	echo "<b>$subject</b>";
	if (!$temp_comment) echo"(Score: $score)";
	if ($email) {
	    echo "<br>by <a href=\"mailto:$email\">$name</a> <b>($email)</b> on $datetime";
	} else {
	    echo "<br>by $name on $datetime";
	}
	echo "<br><br>$comment<br><br>";
	if ($pid == 0) {
	    if ($notes != "") {
		echo "<b>Note:</b> <i>$notes</i><br><br>";
	    } else {
		echo "";
	    }
	}
	if (!isset($pid) || !isset($storyid)) { echo "Something is not right. This message is just to keep things from messing up down the road"; exit(); }
	if ($pid == 0) {
	    $sql = "SELECT title FROM ".$prefix."_stories WHERE storyid='$storyid'";
	    $result = mysql_query($sql);
	    $row = mysql_fetch_array($result);
	    $subject = $row['title'];
	} else {
	    $sql = "SELECT subject FROM ".$prefix."_comments WHERE tid='$pid'";
	    $result = mysql_query($sql);
	    $row = mysql_fetch_array($result);
	    $subject = $row['subject'];
	}
	CloseTable();
	echo "<br>";
	OpenTable();
    echo "<br>";
	echo "<form action=\"modules.php?modulename=$module_name&amp;modulefile=comments\" method=\"post\">";
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">\n";
	echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><b>Your Name:</b>";
	if ($xmbuser) {
	    cookiedecode($xmbuser);
	    echo " $xmbuser [ <a href=\"misc.php?action=logout\">Logout</a> ]<br>";
	} else {
        echo "$anonymous";
	    echo " [ <a href=\"member.php?action=reg\">New User</a> ]<br>";
	}
    echo "</td></tr>";
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Subject:</b></td>";
	if (!eregi("Re:",$subject)) $subject = "Re: ".substr($subject,0,81)."";
	echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"85\" value=\"$subject\"></td></tr>";
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Comment:</b></td>"
	    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"comment\"></textarea></td></tr>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Allowed HTML:</b></td>"
        ."<td bgcolor=\"$altbg2\" class=\"tablerow\">";
	while (list($key)= each($AllowableHTML)) echo " &lt;".$key."&gt;";
	echo "</td></tr>";
	if ($xmbuser AND ($anonpost == 1)) {
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Post Anonymously</b></td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"checkbox\" name=\"xanonpost\"></td></tr>";
    }
    echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\">";
	echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\">\n"
        ."<input type=\"hidden\" name=\"storyid\" value=\"$storyid\">\n"
	    ."<input type=\"hidden\" name=\"mode\" value=\"$mode\">\n"
	    ."<input type=\"hidden\" name=\"order\" value=\"$order\">\n"
	    ."<input type=\"hidden\" name=\"thold\" value=\"$thold\">\n"
	    ."<input type=\"submit\" name=\"op\" value=\"Preview\">\n"
	    ."<input type=\"submit\" name=\"op\" value=\"Ok!\">\n"
	    ."<select name=\"posttype\">\n"
	    ."<option value=\"exttrans\">Extrans (html tags to text)</option>\n"
	    ."<option value=\"html\" >HTML Formated</option>\n"
	    ."<option value=\"plaintext\" selected>Plain Old Text</option>\n"
	    ."</select></td></tr></table></form>\n";
	CloseTable();
    }
    include("portal_footer.php");
}

function replyPreview ($pid, $storyid, $subject, $comment, $xanonpost, $mode, $order, $thold, $posttype) {
    include("portal_header.php");
    global $module_name, $xmbuser, $cookie, $AllowableHTML, $anonymous, $anonpost, $borderwidth, $tablespace, $bordercolor, $altbg2, $altbg1;
    OpenTable();
    echo "<center><b>Comment Post Preview</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    cookiedecode($xmbuser);
    $subject = stripslashes($subject);
    $comment = stripslashes($comment);
    if (!isset($pid) || !isset($storyid)) {
        echo "Something is not right with passing a variable to this function. This message is just to keep things from messing up down the road";
        exit();
    }
    echo "<b>$subject</b>";
    echo "<br>by ";
    if(($xmbuser) AND empty($xanonpost)) {
	echo "<b>$xmbuser</b>";
    }else{
    echo "<b>$anonymous</b>";
    }
    echo " on......<br><br>";
    if ($posttype=="exttrans") {
        echo nl2br(htmlspecialchars($comment));
    } elseif ($posttype=="plaintext") {
        echo nl2br($comment);
    } else {
        echo $comment;
    }
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<br>";
    echo "<form action=\"modules.php?modulename=$module_name&amp;modulefile=comments\" method=\"post\">";
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">\n";
	echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><b>Your Name:</b>";
    if($xmbuser) {
    echo " $xmbuser <font class=\"nav\">[ <a href=\"misc.php?action=logout\">Logout</a> ]</font>";
    }else{
    echo "$anonymous";
    }
    echo "</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Subject:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"85\" value=\"$subject\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Comment:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"comment\">$comment</textarea></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Allowed HTML:</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\">";
    while (list($key,) = each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "</td></tr>";
    if(($xanonpost) AND ($anonpost == 1)){
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Post Anonymously</td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"checkbox\" name=\"xanonpost\" checked></td></tr>";
    }elseif(($xmbuser) AND ($anonpost == 1)) {
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Post Anonymously</td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"checkbox\" name=\"xanonpost\"></td></tr>";
    }
    echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\">";
    echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\">"
    ."<input type=\"hidden\" name=\"storyid\" value=\"$storyid\">"
	."<input type=\"hidden\" name=\"mode\" value=\"$mode\">"
    ."<input type=\"hidden\" name=\"order\" value=\"$order\">"
	."<input type=\"hidden\" name=\"thold\" value=\"$thold\">"
    ."<input type=submit name=op value=\"Preview\">"
    ."<input type=submit name=op value=\"Ok!\">\n"
	."<select name=\"posttype\"><option value=\"exttrans\"";
    if ($posttype=="exttrans") {
    echo " selected";
    }
    echo ">Extrans (html tags to text)</option>\n"
	."<OPTION value=\"html\"";;
    if ($posttype=="html") {
    echo " selected";
    }
    echo ">HTML Formated</option>\n"
	."<OPTION value=\"plaintext\"";
    if (($posttype!="exttrans") && ($posttype!="html")) {
        echo " selected";
    }
    echo ">Plain Old Text</option></select></td></tr></table></form>";
    CloseTable();
    include("portal_footer.php");
}

function CreateTopic ($xanonpost, $subject, $comment, $pid, $storyid, $host_name, $mode, $order, $thold, $posttype) {
    global $module_name, $xmbuser, $userinfo, $EditedMessage, $cookie, $AllowableHTML, $prefix, $anonpost, $articlecomm, $db;
    cookiedecode($xmbuser);
    $author = FixQuotes($author);
    $subject = FixQuotes(filter_text($subject, "nohtml"));
    $comment = format_url($comment);
    if($posttype=="exttrans") {
    	$comment = FixQuotes(nl2br(htmlspecialchars(check_words($comment))));
    } elseif($posttype=="plaintext") {
    	$comment = FixQuotes(nl2br(filter_text($comment)));
    } else {
	$comment = FixQuotes(filter_text($comment));
    }
    if($xmbuser) {
    getusrinfo($xmbuser);
    }
    if (($xmbuser) && (!$xanonpost)) {
	getusrinfo($xmbser);
	$name = $userinfo['username'];
	$email = $userinfo['email'];
	$url = $userinfo['site'];
	$score = 1;
    } else {
	$name = ""; $email = ""; $url = "";
	$score = 0;
    }
    $ip = $_SERVER["REMOTE_HOST"];
    if (empty($ip)) {
    $ip = $_SERVER["REMOTE_ADDR"];
    }
    $sql = "SELECT * FROM ".$prefix."_stories WHERE storyid='$storyid'";
    $result = mysql_query($sql);
    $fake = mysql_num_rows($result);
    if (($fake == 1) AND ($articlecomm == 1)) {
	if ((($anonpost == 0) AND ($xmbuser)) OR ($anonpost == 1)) {
	    $sql = "INSERT INTO ".$prefix."_comments VALUES (NULL, '$pid', '$storyid', now(), '$name', '$email', '$url', '$ip', '$subject', '$comment', '$score', '0')";
	    mysql_query($sql);
	    $sql = "UPDATE ".$prefix."_stories SET comments=comments+1 WHERE storyid='$storyid'";
	    mysql_query($sql);
        } else {
	    echo "Nice try...";
	    die();
	}
    } else {
	include("header.php");
	echo "According to my records, the topic you are trying "
	    ."to reply to does not exist. If you're just trying to be "
	    ."annoying, well then too bad.";
	include("footer.php");
	die();
    }
    if (isset($cookie[4])) { $options .= "&mode=$cookie[4]"; } else { $options .= "&mode=thread"; }
    if (isset($cookie[5])) { $options .= "&order=$cookie[5]"; } else { $options .= "&order=0"; }
    if (isset($cookie[6])) { $options .= "&thold=$cookie[6]"; } else { $options .= "&thold=0"; }
    Header("Location: modules.php?modulename=$module_name&modulefile=article&storyid=$storyid$options");
}

switch($op) {

    case "Reply":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reply($pid, $storyid, $mode, $order, $thold);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

    case "Preview":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	replyPreview ($pid, $storyid, $subject, $comment, $xanonpost, $mode, $order, $thold, $posttype);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    break;

    case "Ok!":
	CreateTopic($xanonpost, $subject, $comment, $pid, $storyid, $host_name, $mode, $order, $thold, $posttype);
	break;

    case "showreply":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	DisplayTopic($storyid, $pid, $tid, $mode, $order, $thold);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

    default:
	if ((isset($tid)) && (!isset($pid))) {
	    singlecomment($tid, $storyid, $mode, $order, $thold);
	} elseif (($mainfile) xor (($pid==0) AND (!isset($pid)))) {
	    Header("Location: modules.php?modulename=$module_name&modulefile=article&storyid=$storyid&mode=$mode&order=$order&thold=$thold");
	} else {
	    if(!isset($pid)) $pid=0;
	    DisplayTopic($storyid, $pid, $tid, $mode, $order, $thold);
	}
	break;

}

?>
