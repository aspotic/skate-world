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

if (!eregi("portal_admin.php", $_SERVER['PHP_SELF'])) { die ("Access Denied"); }
global $status;
if ($status == "Super Administrator" || $status == "Administrator") {

function puthome($ihome, $acomm) {
    global $altbg1, $altbg2;
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Publish in Home?</b></td>";
    if (($ihome == 0) OR ($ihome == "")) {
	$sel1 = "checked";
	$sel2 = "";
    }
    if ($ihome == 1) {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"ihome\" value=\"0\" $sel1>Yes&nbsp;"
	."<input type=\"radio\" name=\"ihome\" value=\"1\" $sel2>No"
	."&nbsp;&nbsp;[ Only works if <i>Articles</i> category isn't selected ]</td></tr>";
	
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Activate Comments for this Story?</b></td>";
    if (($acomm == 0) OR ($acomm == "")) {
	$sel1 = "checked";
	$sel2 = "";
    }
    if ($acomm == 1) {
	$sel1 = "";
	$sel2 = "checked";    
    }
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"acomm\" value=\"0\" $sel1>Yes&nbsp;"
	."<input type=\"radio\" name=\"acomm\" value=\"1\" $sel2>No</td></tr>";
}

function SelectCategory($cat) {
    global $prefix, $db, $altbg1, $altbg2;
    $selcat = $db->query("select catid, title from ".$prefix."_stories_cat order by title");
    $a = 1;
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Category:</b></td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"catid\">";
    if ($cat == 0) {
	$sel = "selected";
    } else {
	$sel = "";
    }
    echo "<option name=\"catid\" value=\"0\" $sel>Articles</option>";
    while(list($catid, $title) = $db->fetch_row($selcat)) {
	if ($catid == $cat) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"catid\" value=\"$catid\" $sel>$title</option>";
	$a++;
    }
    echo "</select> [ <a href=\"portal_admin.php?op=AddCategory\">Add</a> | <a href=\"portal_admin.php?op=EditCategory\">Edit</a> | <a href=\"portal_admin.php?op=DelCategory\">Delete</a> ]</td></tr>";
}

function poll_createPoll() {
    global $status, $prefix, $db, $bordercolor, $tablespace, $borderwidth, $altbg2, $altbg1;
    include ('portal_header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Polls/Surveys Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Create a New Poll</b><br><br>"
	."[ <a href=\"portal_admin.php?op=remove\">Delete Polls</a> | <a href=\"portal_admin.php?op=polledit_select\">Edit Polls</a> ]</center><br><br>"
	."<form action=\"portal_admin.php\" method=\"post\">"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Poll Title:</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"pollTitle\" size=\"50\" maxlength=\"100\"></td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><b>Please enter each available option into a single field</b>"
	."</td></tr>";
    for($i = 1; $i <= 12; $i++)	{
	echo "<tr>"
	    ."<td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Option $i:</b></td>"
         ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"optionText[$i]\" size=\"50\" maxlength=\"50\"></td>"
	    ."</tr>";
    }
    echo "</table>"
	."<br><br><center><hr size=\"1\" noshade><b>Announce this new Survey in your site</b><br>"
	."(Leave blank to create a new survey without announce it)</center><br><br>"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"title\" size=\"40\"></td></tr>";
    $cat = 0;
    $ihome = 0;
    $acomm = 0;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome, $acomm);
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic</b></td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"topic\">";
    $toplist = $db->query("select topicid, topictext from ".$prefix."_topics order by topictext");
    echo "<option value=\"\">Select Topic</option>\n";
    while(list($topicid, $topics) = $db->fetch_row($toplist)) {
    echo "<option value=\"$topicid\">$topics</option>\n";
    }
    echo "</td></tr></select>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Story Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$story</textarea></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Extended Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"8\" name=\"bodytext\"></textarea></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">"
	."<input type=\"hidden\" name=\"op\" value=\"createPosted\">"
	."<input type=\"submit\" value=\"Create Poll\">"
	."</td></tr></table></form>";
    CloseTable();
    include ('portal_footer.php');
}

function old_poll_createPosted() {
    global $pollTitle, $optionText, $prefix, $db;
    $timeStamp = time();
    $result = $db->query("INSERT INTO ".$prefix."_poll_desc VALUES (NULL, '$pollTitle', '$timeStamp', '', '0')");
    if (!$result)	{
	return;
    }
    for($i = 1; $i <= 12; $i++) {
	if($optionText[$i] != "") {
	    $result = $db->query("INSERT INTO ".$prefix."_poll_data VALUES ($id, '$optionText[$i]', 0, $i)");
	}
	if (!result) {
	    return;
	}
    }
    Header("Location: portal_admin.php?op=adminMain");
}

function poll_createPosted($pollTitle, $optionText, $title, $hometext, $topic, $bodytext, $catid, $ihome, $acomm) {
    global $prefix, $db, $aid, $xmbuser;
    $aid = $xmbuser;
    $timeStamp = time();
    $pollTitle = FixQuotes($pollTitle);
    if(!$db->query("INSERT INTO ".$prefix."_poll_desc VALUES (NULL, '$pollTitle', '$timeStamp', 0, '0')")) {
	return;
    }
    $object = mysql_fetch_object($db->query("SELECT pollID FROM ".$prefix."_poll_desc WHERE pollTitle='$pollTitle'"));
    $id = $object->pollID;
    for($i = 1; $i <= sizeof($optionText); $i++) {
	if($optionText[$i] != "") {
	    $optionText[$i] = FixQuotes($optionText[$i]);
	}
	if(!$db->query("INSERT INTO ".$prefix."_poll_data (pollID, optionText, optionCount, voteID) VALUES ($id, '$optionText[$i]', 0, '$i')")) {
	    return;
	}
    }

    if (($title != "") AND ($hometext != "")) {
	$title = stripslashes(FixQuotes($title));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$result = $db->query("insert into ".$prefix."_stories values (NULL, '$catid', '$aid', '$title', now(), '$hometext', '$bodytext', '0', '0', '$topic', '$aid', '', '$ihome', '', '$acomm', '0', '0', '0', '')");
    }
    Header("Location: portal_admin.php?op=adminMain");
}

function poll_removePoll() {
    global $prefix, $db, $bordercolor, $tablespace, $borderwidth, $altbg1, $altbg2;
    include ('portal_header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Polls/Surveys Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Remove an existing Poll</b><br><br>"
	."WARNING: The chosen poll will be removed IMMEDIATELY from the database!</center><br><br>"
	."Please choose a poll from the list below:<br>"
	."<form action=\"portal_admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"op\" value=\"removePosted\">"
	."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">";
    $result = $db->query("SELECT pollID, pollTitle, timeStamp FROM ".$prefix."_poll_desc ORDER BY timeStamp");
    if(!$result) {
	return;
    }
    while($object = mysql_fetch_object($result)) {
	$pollID = $object->pollID;
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Polls:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"id\" value=\"".$object->pollID."\">".$object->pollTitle."</td></tr>";
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">";
    echo "<input type=\"submit\" value=\"Delete\">";
    echo "</td></tr></table></form>";
    CloseTable();
    include ('portal_footer.php');
}

function poll_removePosted() {
    global $id, $prefix, $db;
    $db->query("DELETE FROM ".$prefix."_poll_desc WHERE pollID=$id");
    $db->query("DELETE FROM ".$prefix."_poll_data WHERE pollID=$id");
    Header("Location: portal_admin.php?op=adminMain");
}

function polledit_select() {
    global $prefix, $db, $bordercolor, $tablespace, $borderwidth, $altbg1, $altbg2;
    
    include ('portal_header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Polls/Surveys Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Edit Polls</b></center><br><br>"
	."Choose the Poll you want to edit:<br>"
	."<form action=\"portal_admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"op\" value=\"polledit\">"
	."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">";
    $result = $db->query("SELECT pollID, pollTitle, timeStamp FROM ".$prefix."_poll_desc ORDER BY timeStamp");
    if(!$result) {
	return;
    }
    while($object = mysql_fetch_object($result)) {
	$pollID = $object->pollID;
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b> Polls:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"pollID\" value=\"".$object->pollID."\">".$object->pollTitle."</td></tr>";
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">";
    echo "<input type=\"submit\" value=\"Edit\">";
    echo "</td></tr></table></form>";
    CloseTable();
    include ('portal_footer.php');
}

function polledit($pollID) {
    global $prefix, $db, $bordercolor, $tablespace, $borderwidth, $altbg1, $altbg2;
    include ('portal_header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Polls/Surveys Administration</b></center>";
    $result = $db->query("select pollTitle from ".$prefix."_poll_desc where pollID=$pollID");
    list($pollTitle) = $db->fetch_row($result);
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Edit Poll: $pollTitle</b></center><br><br>";
    echo "<form action=\"portal_admin.php\" method=\"post\">";
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title:</b></td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><input type=\"text\" name=\"pollTitle\" value=\"$pollTitle\" size=\"40\" maxlength=\"100\"></td></tr>";
    $result = $db->query("select optionText, optionCount, voteID from ".$prefix."_poll_data where pollID=$pollID order by voteID");
    while(list($optionText, $optionCount, $voteID) = $db->fetch_row($result)) {
	echo "<tr><td bgcolor=\"$altbg1\" align=\"center\" class=\"tablerow\"><b>$voteID</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"optiontext$voteID\" value=\"$optionText\" size=\"40\" maxlength=\"50\"></td><td bgcolor=\"$altbg1\"align=\"right\" class=\"tablerow\">$optionCount Votes</td></tr>";
    }
    echo "</table><input type=\"hidden\" name=\"pollID\" value=\"$pollID\"><input type=\"hidden\" name=\"op\" value=\"savepoll\">"
	."<br><br><center><input type=\"submit\" value=\"Save Changes\"><br><br>[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]</center><br><br></form>";
    CloseTable();
    include("portal_footer.php");
}

function savepoll($pollID, $pollTitle, $optiontext1, $optiontext2, $optiontext3, $optiontext4, $optiontext5, $optiontext6, $optiontext7, $optiontext8, $optiontext9, $optiontext10, $optiontext11, $optiontext12) {
    global $prefix, $db;
    $result = $db->query("update ".$prefix."_poll_desc set pollTitle='$pollTitle' where pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext1' where voteID=1 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext2' where voteID=2 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext3' where voteID=3 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext4' where voteID=4 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext5' where voteID=5 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext6' where voteID=6 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext7' where voteID=7 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext8' where voteID=8 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext9' where voteID=9 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext10' where voteID=10 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext11' where voteID=11 AND pollID='$pollID'");
    $result = $db->query("update ".$prefix."_poll_data set optionText='$optiontext12' where voteID=12 AND pollID='$pollID'");
    Header("Location: portal_admin.php");
}

switch($op) {

    case "create":
    poll_createPoll();
    break;

    case "createPosted":
    poll_createPosted($pollTitle, $optionText, $title, $hometext, $topic, $bodytext, $catid, $ihome, $acomm);
    break;

    case "ChangePoll":
    ChangePoll($pollID, $pollTitle, $optionText, $voteID);
    break;

    case "remove":
    poll_removePoll();
    break;

    case "removePosted":
    poll_removePosted();
    break;

    case "polledit":
    polledit($pollID);
    break;

    case "savepoll":
    savepoll($pollID, $pollTitle, $optiontext1, $optiontext2, $optiontext3, $optiontext4, $optiontext5, $optiontext6, $optiontext7, $optiontext8, $optiontext9, $optiontext10, $optiontext11, $optiontext12);
    break;

    case "polledit_select":
    polledit_select();
    break;

}

} else {
    echo "Access Denied";
}

?>
