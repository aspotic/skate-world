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
$pagetitle = "- Submit News";

function defaultDisplay() {
    global $AllowableHTML, $prefix, $xmbuser, $cookie, $anonymous, $module_name, $bordercolor, $borderwidth, $tablespace, $altbg1, $altbg2;
    include ('portal_header.php');
    OpenTable();
    echo "<center><b>Submit News</b><br><br>";
    echo "<font class=\"tablerow\"><i>Please write your article/story filling the following form and double check your submission.<br>You're advised that not all submissions will be posted.<br>Your submission will be checked for proper grammar and maybe edited by our staff.</i></center><br>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Submit News</b><br><br>";
    BeginTable();
    echo "<tr>";
    if ($xmbuser) getusrinfo($xmbuser);
    echo "<p><form action=\"modules.php?modulename=$module_name\" method=\"post\">"
	."<td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Your Name:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
    if ($xmbuser) {
	cookiedecode($xmbuser);
	echo "$xmbuser <font class=\"nav\">[ <a href=\"misc.php?action=logout\">Logout</a> ]</font>";
    } else {
   	echo "$anonymous <font class=\"nav\">[ <a href=\"member.php?action=reg\">New user</a> ]</font>";
    }
    echo "</td></tr>"
        ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title</b><br> "
        ."(Be Descriptive, Clear and Simple)</td>"
        ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"80\"><br>(bad titles='Check This Out!' or 'An Article')</td></tr>"
        ."<tr>"
        ."<td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"> <select name=\"topic\">";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = mysql_query($sql);
    echo "<option value=\"\">Select Topic</option>\n";
    while ($row = mysql_fetch_array($result)) {
	$topicid = $row['topicid'];
	$topics = $row['topictext'];
    if ($topicid == $topic) {
    $sel = "selected ";
	}
	echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>";
	echo "</td></tr>"
    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Story Text:</b><br> (HTML is fine, but double check those URLs and HTML tags!)</td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea cols=\"50\" rows=\"12\" name=\"story\"></textarea></td></tr>"
    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Extended Text:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea cols=\"50\" rows=\"12\" name=\"storyext\"></textarea><br>"
	."</td></tr>"
    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Allowed HTML:</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\">";
    while (list($key,) = each($AllowableHTML)) echo "&lt;".$key."&gt;";
    echo "</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><input type=\"submit\" name=\"op\" value=\"Preview\">&nbsp;&nbsp;"
	."<select name=\"posttype\">\n"
	."<option value=\"exttrans\">Extrans (html tags to text)</option>\n"
	."<option value=\"html\" >HTML Formated</option>\n"
	."<option value=\"plaintext\" selected>Plain Old Text</option>\n"
	."</select>"
	."<br>(You must preview once before you can submit)</td></tr></form>";
    EndTable();
    CloseTable();
    include ('portal_footer.php');
}

function PreviewStory($modulename, $address, $subject, $story, $storyext, $topic, $posttype) {
    global $xmbuser, $cookie, $bordercolor, $borderwidth, $tablespace, $altbg1, $altbg2, $anonymous, $prefix, $AllowableHTML, $db, $module_name, $portal_images;
    include ('portal_header.php');
    $subject = stripslashes($subject);
    $story = stripslashes($story);
    $storyext = stripslashes($storyext);
    if ($posttype=="exttrans") {
        $f_story = nl2br(htmlspecialchars($story));
	$f_storyext = nl2br(htmlspecialchars($storyext));
    } elseif ($posttype=="plaintext") {
    $f_story = nl2br($story);
	$f_storyext = nl2br($storyext);
    } else {
	$f_story = $story;
	$f_storyext = $storyext;
    }
    $story2 = "$f_story<br><br>$f_storyext";
    OpenTable();
    echo "<center><font class=\"subject\"><b>News Submission Preview</b></font>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><i>Your Story will look something like this:</i></center><br><br>";
    echo "<table width=\"95%\" bgcolor=\"$bordercolor\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\"align=\"center\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">"
	."<table width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" border=\"0\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">";
    if ($topic=="") {
        $topicimage="AllTopics.gif";
        $warning = "<center><blink><b>Select Topic</b></blink></center>";
    } else {
        $warning = "";
        $sql = "SELECT topicimage FROM ".$prefix."_topics WHERE topicid='$topic'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$topicimage = $row['topicimage'];
    }
    echo "<img src=\"$portal_images/topics/$topicimage\" border=\"0\" align=\"right\">";
    themepreview($subject, $story2);
    echo "$warning"
	."</td></tr></table></td></tr></table>"
	."<br><br><center>Please check text, links, etc. before send your story!</center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<p><form action=\"modules.php?modulename=$module_name\" method=\"post\">";
    BeginTable();
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Your Name:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
    if ($xmbuser) {
   	cookiedecode($xmbuser);
	echo "$xmbuser <font class=\"nav\">[ <a href=\"misc.php?action=logout\">Logout</a> ]</font>";
    } else {
	echo "$anonymous";
    }
    echo "</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"80\" value=\"$subject\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"topic\">";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = mysql_query($sql);
    echo "<OPTION VALUE=\"\">Select Topic</option>\n";
    while ($row = mysql_fetch_array($result)) {
	$topicid = $row['topicid'];
	$topics = $row['topictext'];
    if ($topicid == $topic) {
    $sel = "selected ";
	}
	echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select></td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Story Text:</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea cols=\"50\" rows=\"12\" name=\"story\">$story</textarea><br> (HTML is fine, but double check those URLs and HTML tags!)</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Extended Text:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea cols=\"50\" rows=\"12\" name=\"storyext\">$storyext</textarea>"
	."</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Allowed HTML:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
    while (list($key,) = each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "</td></tr>"
    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><input type=\"submit\" name=\"op\" value=\"Preview\">&nbsp;&nbsp;"
	."<input type=\"submit\" name=\"op\" value=\"Ok!\">&nbsp;&nbsp;"
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
    echo ">Plain Old Text</option></select>"
	."</td></tr></form>";
    EndTable();
    CloseTable();
    include ('portal_footer.php');
}

function submitStory($modulename, $address, $subject, $story, $storyext, $topic, $posttype) {
    global $xmbuser, $EditedMessage, $cookie, $anonymous, $table_members, $prefix, $db;
    if ($xmbuser) {
   	cookiedecode($xmbuser);
	$postername = $xmbuser;
    } else {
	$postername = "$anonymous";
    }
    $subject = ereg_replace("\"", "''", $subject);
    $subject = FixQuotes(filter_text($subject, "nohtml"));
    if($posttype=="exttrans") {
   	$story = FixQuotes(nl2br(htmlspecialchars(check_words($story))));
	$storyext = FixQuotes(nl2br(htmlspecialchars(check_words($storyext))));
    } elseif($posttype=="plaintext") {
   	$story = FixQuotes(nl2br(filter_text($story)));
	$storyext = FixQuotes(nl2br(filter_text($storyext)));
    } else {
	$story = FixQuotes(filter_text($story));
	$storyext = FixQuotes(filter_text($storyext));
    }
    $query = mysql_query("select * from $table_members where username='$xmbuser'");
    $member = mysql_fetch_array($query);
    if($xmbuser) {
    $uid = $member['uid'];
    }else{
    $uid = 0;
    }
    $sql = "INSERT INTO ".$prefix."_queue VALUES (NULL, '$uid', '$postername', '$subject', '$story', '$storyext', now(), '$topic')";
    $result = mysql_query($sql);
    if(!$result) {
   	echo "Error<br>";
	exit();
    }
    include ('portal_header.php');
    OpenTable();
    $sql = "SELECT * FROM ".$prefix."_queue";
    $result = $db->query($sql);
    $waiting = $db->num_rows($result);
    echo "<center><font class=\"title\">Your Article has been received...</font><br><br>"
	."<font class=\"content\"><b>Thanks for your submission!</b><br><br>"
	."We will check your submission in the next few hours, if it is interesting and relevant we will publish it soon."
	."<br>At this moment we have $waiting submissions waiting to be published.";
    CloseTable();
    include ('portal_footer.php');
}

switch($op) {

    case "Preview":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	PreviewStory($modulename, $address, $subject, $story, $storyext, $topic, $posttype);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    break;

    case "Ok!":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	SubmitStory($modulename, $address, $subject, $story, $storyext, $topic, $posttype);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

    default:
    eval("\$header = \"".template("header")."\";");
    echo $header;
	defaultDisplay();
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

}

?>
