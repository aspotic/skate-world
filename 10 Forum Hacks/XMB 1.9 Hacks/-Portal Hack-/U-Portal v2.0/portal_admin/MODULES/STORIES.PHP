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

function deleteStory($qid) {
    global $prefix, $db;
    $result = $db->query("delete from ".$prefix."_queue where qid=$qid");
    if (!$result) {
	return;
    }
    Header("Location: portal_admin.php?op=submissions");
}

function SelectCategory($cat) {
    global $prefix, $db, $altbg1, $altbg2;
    $selcat = $db->query("select catid, title from ".$prefix."_stories_cat order by title");
    $a = 1;
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Category</b></td>";
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

function putpoll($pollTitle, $optionText) {
    global $borderwidth, $tablespace, $bordercolor, $altbg1, $altbg2;
    OpenTable();
    echo "<center><b>Attach a Poll to this article</b><br>"
	."(Leave blank to post the article without any attached Poll)<br>(NOTE: Automated/Programmed news can't have attached Polls)<br><br>";
    BeginTable();
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Poll Title:</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"pollTitle\" size=\"50\" maxlength=\"100\" value=\"$pollTitle\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\" align=\"center\">Please enter each available option into a single field"
	."</td></tr>";
    for($i = 1; $i <= 12; $i++)	{
	echo "<tr><td bgcolor=\"".$altbg1."\" class=\"tablerow\"><b>Option $i:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"optionText[$i]\" size=\"50\" maxlength=\"50\" value=\"$optionText[$i]\"></td></tr>";
    }
    EndTable();
    CloseTable();
}

function AddCategory() {
    include ("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Categories Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Add a New Category</b><br><br><br>"
	."<form action=\"portal_admin.php\" method=\"post\">"
	."<b>Category Name:</b> "
	."<input type=\"text\" name=\"xtitle\" size=\"22\" maxlength=\"20\"> "
	."<input type=\"hidden\" name=\"op\" value=\"SaveCategory\">"
	."<input type=\"submit\" value=\"Save\">"
	."</form></center>";
    CloseTable();
    include("portal_footer.php");
}

function EditCategory($catid) {
    global $prefix, $db;
    $result = $db->query("select title from ".$prefix."_stories_cat where catid='$catid'");
    list($title) = $db->fetch_row($result);
    include ("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Categories Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Edit Category</b><br>";
    if (!$catid) {
	$selcat = $db->query("select catid, title from ".$prefix."_stories_cat");
	echo "<form action=\"portal_admin.php\" method=\"post\">";
	echo "<b>Select Category</b>";
	echo "<select name=\"catid\">";
	echo "<option name=\"catid\" value=\"0\" $sel>Articles</option>";
	while(list($catid, $title) = $db->fetch_row($selcat)) {
    echo "<option name=\"catid\" value=\"$catid\" $sel>$title</option>";
	}
	echo "</select>";
	echo "<input type=\"hidden\" name=\"op\" value=\"EditCategory\">";
	echo "<input type=\"submit\" value=\"Edit\"><br><br>";
	echo "You can't edit <i>Articles</i> Category";
    } else {
	echo "<form action=\"portal_admin.php\" method=\"post\">";
	echo "<b>Category Name:</b> ";
	echo "<input type=\"text\" name=\"xtitle\" size=\"22\" maxlength=\"20\" value=\"$title\"> ";
	echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
	echo "<input type=\"hidden\" name=\"op\" value=\"SaveEditCategory\">";
	echo "<input type=\"submit\" value=\"Save Changes\"><br><br>";
	echo "You can't edit <i>Articles</i> Category";
	echo "</form>";
    }
    echo "</center>";
    CloseTable();
    include("portal_footer.php");
}

function DelCategory($cat) {
    global $prefix, $db;
    $result = $db->query("select title from ".$prefix."_stories_cat where catid='$cat'");
    list($title) = $db->fetch_row($result, $db);
    include ("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Categories Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Delete Category</b><br>";
    if (!$cat) {
	$selcat = $db->query("select catid, title from ".$prefix."_stories_cat");
	echo "<form action=\"portal_admin.php\" method=\"post\">"
	    ."<b>Select a Category to Delete: </b>"
	    ."<select name=\"cat\">";
	while(list($catid, $title) = $db->fetch_row($selcat)) {
	    echo "<option name=\"cat\" value=\"$catid\">$title</option>";
	}
	echo "</select>"
	    ."<input type=\"hidden\" name=\"op\" value=\"DelCategory\">"
	    ."<input type=\"submit\" value=\"Delete\">"
	    ."</form>";
    } else {
	$result2 = $db->query("select * from ".$prefix."_stories where catid='$cat'");
	$numrows = $db->num_rows($result2);
	if ($numrows == 0) {
	    $db->query("delete from ".$prefix."_stories_cat where catid='$cat'");
	    echo "<br><br>Category Deleted!<br><br>Go to Admin Section";
	} else {
	    echo "<br><br><b>Warning:</b> The Category <b>$title</b> has <b>$numrows</b> stories inside<br>"
		."You can Delete this Category and ALL its stories and comments<br>"
		."or you can Move ALL the stories to a New Category.<br><br>"
		."What do you want to do?<br><br>"
		."<b>[ <a href=\"portal_admin.php?op=YesDelCategory&amp;catid=$cat\">Yes! Delete ALL!</a> | "
		."<a href=\"portal_admin.php?op=NoMoveCategory&amp;catid=$cat\">No! Move my Stories</a> ]</b>";
	}
    }
    echo "</center>";
    CloseTable();
    include("portal_footer.php");
}

function YesDelCategory($catid) {
    global $prefix, $db;
    $db->query("delete from ".$prefix."_stories_cat where catid='$catid'");
    $result = $db->query("select sid from ".$prefix."_stories where catid='$catid'");
    while(list($storyid) = $db->fetch_row($result)) {
	$db->query("delete from ".$prefix."_stories where catid='$catid'");
	$db->query("delete from ".$prefix."_comments where storyid='$storyid'");
    }
    Header("Location: portal_admin.php");
}

function NoMoveCategory($catid, $newcat) {
    global $prefix, $db;
    $result = $db->query("select title from ".$prefix."_stories_cat where catid='$catid'");
    list($title) = $db->fetch_row($result);
    include ("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Categories Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Move Stories to a New Category</b><br><br>";
    if (!$newcat) {
	echo "ALL stories under <b>$title</b> will be moved.<br><br>";
	$selcat = $db->query("select catid, title from ".$prefix."_stories_cat");
	echo "<form action=\"portal_admin.php\" method=\"post\">";
	echo "<b>Please Select the New Category:</b> ";
	echo "<select name=\"newcat\">";
    echo "<option name=\"newcat\" value=\"0\">Articles</option>";
	while(list($newcat, $title) = $db->fetch_row($selcat)) {
    echo "<option name=\"newcat\" value=\"$newcat\">$title</option>";
	}
	echo "</select>";
	echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
	echo "<input type=\"hidden\" name=\"op\" value=\"NoMoveCategory\">";
	echo "<input type=\"submit\" value=\"Ok!\">";
	echo "</form>";
    } else {
	$resultm = $db->query("select storyid from ".$prefix."_stories where catid='$catid'");
	while(list($sid) = $db->fetch_row($resultm)) {
    $db->query("update ".$prefix."_stories set catid='$newcat' where storyid='$storyid'");
	}
	$db->query("delete from ".$prefix."_stories_cat where catid='$catid'");
	echo "Congratulations! The move has been completed!";
    }
    CloseTable();
    include("portal_footer.php");
}

function SaveEditCategory($catid, $xtitle) {
    global $prefix, $db;
    $xtitle = ereg_replace("\"","",$xtitle);
    $xcheck = $db->query("select title from ".$prefix."_stories_cat where title='$xtitle'");
    $same = mysql_fetch_array($xcheck);
    if($same['title'] === $xtitle) {
	$what1 = "This Category already exists!";
	$what2 = "[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
    } else {
	$what1 = "Category Saved!";
	$what2 = "[ <a href=\"portal_admin.php\">Go to Admin Section</a> ]";
	$result = $db->query("update ".$prefix."_stories_cat set title='$xtitle' where catid='$catid'");
	if (!$result) {
	    return;
	}
    }
    include ("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Categories Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>$what1</b><br><br>";
    echo "$what2</center>";
    CloseTable();
    include("portal_footer.php");
}

function SaveCategory($xtitle) {
    global $prefix;
    $xtitle = ereg_replace("\"","",$xtitle);
    $xcheck = mysql_query("select title from ".$prefix."_stories_cat where title='$xtitle'");
    $same = mysql_fetch_array($xcheck);
    if($same['title'] === $xtitle) {
	$what1 = "This Category already exists!";
	$what2 = "[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
    }else{
	$what1 = "New Category Added!";
	$what2 = "Go to Admin Section";
	$result = mysql_query("insert into ".$prefix."_stories_cat values (NULL, '$xtitle', '0')");
	if (!$result) {
    return;
	}
    }
    include ("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Categories Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>$what1</b><br><br>";
    echo "$what2</center>";
    CloseTable();
    include("portal_footer.php");
}

function autodelete($anid) {
    global $prefix, $db;
    $db->query("delete from ".$prefix."_autonews where anid='$anid'");
    Header("Location: portal_admin.php?op=adminMain");
}

function autoEdit($anid) {
    global $xuser, $prefix, $db, $portal_images, $bordercolor, $tablespace, $borderwidth, $altbg1, $altbg2, $status;
    $result2 = $db->query("select aid from ".$prefix."_stories where storyid='$storyid'");
    list($aaid) = $db->fetch_row($result2);
    if (($aaid == $xuser) OR ($status == "Super Administrator" || $status == "Administrator")) {
    include ("portal_header.php");
    $result = $db->query("select catid, aid, title, time, hometext, bodytext, topic, informant, notes, ihome, acomm from ".$prefix."_autonews where anid=$anid");
    list($catid, $xuser, $title, $time, $hometext, $bodytext, $topic, $informant, $notes, $ihome, $acomm) = $db->fetch_row($result);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Articles/Stories Administration</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    echo "<center><b>Edit Automated Story</b></center><br><br>"
	."<form action=\"portal_admin.php\" method=\"post\">";
    $title = stripslashes($title);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $notes = stripslashes($notes);
    $result = $db->query("select topicimage from ".$prefix."_topics where topicid='$topic'");
    list($topicimage) = $db->fetch_row($result);
    echo "<table border=\"0\" width=\"75%\" cellpadding=\"0\" cellspacing=\"1\" align=\"center\"><tr><td class=\"tablerow\">"
	."<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\"><tr><td class=\"tablerow\">"
	."<img src=\"$portal_images/topics/$topicimage\" border=\"0\" align=\"right\">";
    themepreview($title, $hometext, $bodytext);
    echo "</td></tr></table></td></tr></table>"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"title\" size=\"50\" value=\"$title\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topics</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"topic\">";
    $toplist = $db->query("select topicid, topictext from ".$prefix."_topics order by topictext");
    echo "<option value=\"\">All Topics</option>\n";
    while(list($topicid, $topics) = $db->fetch_row($toplist)) {
    if ($topicid==$topic) { $sel = "selected "; }
    echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select></td></tr>";
    $cat = $catid;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome, $acomm);

    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Story Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"hometext\">$hometext</textarea></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Extended Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"bodytext\">$bodytext</textarea>"
	."</td></tr>";
    if ($xuser != $informant OR $status == "Super Administrator" || $status == "Administrator") {
   	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Notes</b></td>
	<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\">$notes</textarea></td></tr>";
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Select new date for this Story:</b><br><br>"
	."<b>Now is:</b> $date</td>";
    $xday = 1;
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\">";
    echo "<b>Day:</b> <select name=\"day\">";
    while ($xday <= 31) {
	if ($xday == $datetime[3]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$xday</option>";
	$xday++;
    }
    echo "</select>";
    $xmonth = 1;
    echo "<b>Month:</b> <select name=\"month\">";
    while ($xmonth <= 12) {
	if ($xmonth == $datetime[2]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$xmonth</option>";
	$xmonth++;
    }
    echo "</select>";
    echo "<b>Year:</b> <input type=\"text\" name=\"year\" value=\"$datetime[1]\" size=\"5\" maxlength=\"4\">";
    echo "<br><b>Hour:</b> <select name=\"hour\">";
    $xhour = 0;
    $cero = "0";
    while ($xhour <= 23) {
	$dummy = $xhour;
	if ($xhour < 10) {
	    $xhour = "$cero$xhour";
	}
	if ($xhour == $datetime[4]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"hour\" $sel>$xhour</option>";
	$xhour = $dummy;
	$xhour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $xmin = 0;
    while ($xmin <= 59) {
	if (($xmin == 0) OR ($xmin == 5)) {
	    $xmin = "0$xmin";
	}
	if ($xmin == $datetime[5]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"min\" $sel>$xmin</option>";
	$xmin = $xmin + 5;
    }
    echo "</select>";
    echo ": <b>00</b></td></tr>";
    echo"<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">
    <input type=\"hidden\" name=\"anid\" value=\"$anid\">
    <input type=\"hidden\" name=\"op\" value=\"autoSaveEdit\">
    <input type=\"submit\" value=\"Save Changes\">
    </td></tr></table></form>";
    CloseTable();
    include ('portal_footer.php');
    } else {
	include ('portal_header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"subject\"><b>Articles/Stories Administration</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>You aren't authorized to touch this Article!</b><br><br>"
    ."You can't edit and/or delete articles that you don't published<br><br>"
    ."[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
	CloseTable();
	include("portal_footer.php");
    }
}

function autoSaveEdit($anid, $year, $day, $month, $hour, $min, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $acomm) {
    global $xuser, $prefix, $db, $status;
    $result2 = $db->query("select aid from ".$prefix."_stories where storyid='$storyid'");
    list($aaid) = $db->fetch_row($result2);
    if (($aaid == $xuser) OR ($status == "Super Administrator" || $status == "Administrator")) {
    if ($day < 10) {
	$day = "0$day";
    }
    if($month < 10) {
	$month = "0$month";
    }
    $sec = "00";
    $date = "$year-$month-$day $hour:$min:$sec";
    $title = stripslashes(FixQuotes($title));
    $hometext = stripslashes(FixQuotes($hometext));
    $bodytext = stripslashes(FixQuotes($bodytext));
    $notes = stripslashes(FixQuotes($notes));
    $result = $db->query("update ".$prefix."_autonews set catid='$catid', title='$title', time='$date', hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome', acomm='$acomm' where anid=$anid");
    if (!$result) {
	exit();
    }
    Header("Location: portal_admin.php?op=adminMain");
    } else {
	include ('portal_header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><b>Articles/Stories Administration</b></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>You aren't authorized to touch this Article!</b><br><br>"
    ."You can't edit and/or delete articles that you don't published<br><br>"
    ."[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
	CloseTable();
	include("portal_footer.php");
    }
}

function displayStory($qid) {
    global $xmbuser, $subject, $story, $anonymous, $prefix, $db, $table_members, $portal_images, $altbg2, $altbg1, $borderwidth, $tablespace, $bordercolor;
    include ('portal_header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Stories Submissions Administration</b></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $ttmon = $today[mon];
    if ($ttmon < 10){
	$ttmon = "0$ttmon";
    }
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    $result = $db->query("SELECT qid, uid, uname, subject, story, storyext, topic FROM ".$prefix."_queue where qid=$qid");
    list($qid, $uid, $uname, $subject, $story, $storyext, $topic) = $db->fetch_row($result);
    $subject = stripslashes($subject);
    $story = stripslashes($story);
    $storyext = stripslashes($storyext);

    OpenTable();
    echo ""
	."<form action=\"portal_admin.php\" method=\"post\">"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Name</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" NAME=\"author\" size=\"25\" value=\"$uname\"><br>";
    if ($uname != $anonymous) {
	$res = $db->query("select email from $table_members where username='$uname'");
	list($email) = $db->fetch_row($res);
	echo "[ <a href=\"mailto:$email?Subject=Re: $subject\">Email User</a> | <a href='member.php?action=viewpro&member=$uname'>User Profile</a> | <a href=\"u2u.php?action=send&username=$uname\">Send Private Message</a> ]";
    }
    echo "</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"></td></tr>";
    if($topic=="") {
        $topic = 1;
    }
    $result = $db->query("select topicimage from ".$prefix."_topics where topicid=$topic");
    list($topicimage) = $db->fetch_row($result);
    echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\">"
	."<img src=\"$portal_images/topics/$topicimage\" border=\"0\" align=\"right\" alt=\"\">";
    $storypre = "$story<br><br>$storyext";
    themepreview($subject, $storypre);
    echo "</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"topic\">";
    $toplist = $db->query("select topicid, topictext from ".$prefix."_topics order by topictext");
    echo "<option value=\"\">Select Topic</option>\n";
    while(list($topicid, $topics) = $db->fetch_row($toplist)) {
    if ($topicid==$topic) {
    $sel = "selected ";
	}
    echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>";
    echo "</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Associated Topics</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
	if ($a == 3) {
	    echo "<br>";
	    $a = 0;
	}
	echo "<input type='checkbox' name='assotop[]' value='$row[topicid]'>$row[topictext]";
	$a++;
    }
    echo "</td></tr>";
    SelectCategory($cat);
    puthome($ihome, $acomm);
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Story Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$story</textarea></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Extended Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"8\" name=\"bodytext\">$storyext</textarea>"
	."</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Notes</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\"></textarea>"
	."<input type=\"hidden\" NAME=\"qid\" size=\"50\" value=\"$qid\">"
	."<input type=\"hidden\" NAME=\"uid\" size=\"50\" value=\"$uid\">"
    ."</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Do you want to program this story?</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"automated\" value=\"1\">Yes &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"0\" checked>No</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Now is:</b> $date</td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\">";
    $day = 1;
    echo "<b>Day:</b> <select name=\"day\">";
    while ($day <= 31) {
	if ($tday==$day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$day</option>";
	$day++;
    }
    echo "</select>";
    $month = 1;
    echo "<b>Month:</b> <select name=\"month\">";
    while ($month <= 12) {
	if ($ttmon==$month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$month</option>";
	$month++;
    }
    echo "</select>";
    $date = getdate();
    $year = $date[year];
    echo "<b>Year:</b> <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">";
    echo "<br><b>Hour:</b> <select name=\"hour\">";
    $hour = 0;
    $cero = "0";
    while ($hour <= 23) {
	$dummy = $hour;
	if ($hour < 10) {
	    $hour = "$cero$hour";
	}
	echo "<option name=\"hour\">$hour</option>";
	$hour = $dummy;
	$hour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $min = 0;
    while ($min <= 59) {
	if (($min == 0) OR ($min == 5)) {
	    $min = "0$min";
	}
	echo "<option name=\"min\">$min</option>";
	$min = $min + 5;
    }
    echo "</select>";
    echo ": <b>00</b></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><select name=\"op\">"
	."<option value=\"DeleteStory\">Delete Story</option>"
	."<option value=\"PreviewAgain\" selected>Preview Story</option>"
	."<option value=\"PostStory\">Post Story</option>"
	."</select>"
	."<input type=\"submit\" value=\"Ok!\"> &nbsp;&nbsp [ <a href=\"portal_admin.php?op=DeleteStory&qid=$qid\">Delete</a> ]</td></tr></table>";
    CloseTable();
    echo "<br>";
    putpoll($pollTitle, $optionText);
    echo "</table></form>";
    include ('portal_footer.php');
}

function previewStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $acomm, $pollTitle, $optionText, $assotop) {
    global $xmbuser, $boxstuff, $anonymous, $prefix, $db, $table_members, $portal_images, $borderwidth, $tablespace, $bordercolor, $altbg1, $altbg2;
    include ('portal_header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Articles/Stories Administration</b></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    $subject = stripslashes($subject);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $notes = stripslashes($notes);
    OpenTable();
    echo "<form action=\"portal_admin.php\" method=\"post\">"
	."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Name</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"author\" size=\"25\" value=\"$author\">";
    if ($author != $anonymous) {
	$res = $db->query("select uid, email from $table_members where username='$author'");
	list($pm_userid, $email) = $db->fetch_row($res);
	echo "<br>[ <a href=\"mailto:$email?Subject=Re: $subject\">Email User</a> | <a href='member.php?action=viewpro&member=$author'>User Profile</a> | <a href=\"u2u.php?action=send&username=$author\">Send Private Message</a> ]";
    }
    echo "</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"></td></tr>";
    $result = $db->query("select topicimage from ".$prefix."_topics where topicid=$topic");
    list($topicimage) = $db->fetch_row($result);
	echo"<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">"
	."<img src=\"$portal_images/topics/$topicimage\" border=\"0\" align=\"right\">";
    themepreview($subject, $hometext, $bodytext, $notes);
    echo "</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"> <select name=\"topic\">";
    $toplist = $db->query("select topicid, topictext from ".$prefix."_topics order by topictext");
    echo "<option value=\"\">All Topics</option>\n";
    while(list($topicid, $topics) = $db->fetch_row($toplist)) {
    if ($topicid==$topic) {
    $sel = "selected ";
	}
    echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>";
    echo "</td></tr>";
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    $asso_t = explode("-", $associated);
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Associated Topics</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->query($sql);
    while ($row = $db->fetch_row($result)) {
    if ($a == 3) {
    echo "<br>";
    $a = 0;
	}
	for ($i=0; $i<sizeof($asso_t); $i++) {
	    if ($asso_t[$i] == $row[topicid]) {
	        $checked = "CHECKED";
	        break;
	    }
	}
	echo "<input type='checkbox' name='assotop[]' value='$row[topicid]' $checked>$row[topictext]";
	$checked = "";
	$a++;
    }
    echo "</td></tr>";
    $cat = $catid;
    SelectCategory($cat);
    puthome($ihome, $acomm);
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Story Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$hometext</textarea></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Extended Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"bodytext\">$bodytext</textarea>"
	."</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Notes</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\">$notes</textarea><br><br>"
	."<input type=\"hidden\" NAME=\"qid\" size=\"50\" value=\"$qid\">"
	."<input type=\"hidden\" NAME=\"uid\" size=\"50\" value=\"$uid\"></td></tr>";
    if ($automated == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } else {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Do you want to program this story?</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"automated\" value=\"1\" $sel1>Yes &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"0\" $sel2>No</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Now is:</b> $date</td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\">";
    $xday = 1;
    echo "<b>day:</b> <select name=\"day\">";
    while ($xday <= 31) {
	if ($xday == $day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$xday</option>";
	$xday++;
    }
    echo "</select>";
    $xmonth = 1;
    echo "<b>Month:</b> <select name=\"month\">";
    while ($xmonth <= 12) {
	if ($xmonth == $month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$xmonth</option>";
	$xmonth++;
    }
    echo "</select>";
    echo "<b>Year:</b> <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">";
    echo "<br><b>Hour:</b> <select name=\"hour\">";
    $xhour = 0;
    $cero = "0";
    while ($xhour <= 23) {
	$dummy = $xhour;
	if ($xhour < 10) {
	    $xhour = "$cero$xhour";
	}
	if ($xhour == $hour) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"hour\" $sel>$xhour</option>";
	$xhour = $dummy;
	$xhour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $xmin = 0;
    while ($xmin <= 59) {
	if (($xmin == 0) OR ($xmin == 5)) {
	    $xmin = "0$xmin";
	}
	if ($xmin == $min) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"min\" $sel>$xmin</option>";
	$xmin = $xmin + 5;
    }
    echo "</select>";
    echo ": <b>00</b></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><select name=\"op\">"
	."<option value=\"DeleteStory\">Delete Story</option>"
	."<option value=\"PreviewAgain\" selected>Preview Story</option>"
	."<option value=\"PostStory\">Post Story</option>"
	."</select>"
	."<input type=\"submit\" value=\"Ok!\"></td></tr></table>";
    CloseTable();
    echo "<br>";
    putpoll($pollTitle, $optionText);
    echo "</form>";
    include ('portal_footer.php');
}

function postStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $acomm, $pollTitle, $optionText, $assotop) {
    global $aid, $prefix, $db, $table_members;
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    if ($automated == 1) {
        if ($day < 10) {
	    $day = "0$day";
	}
	if ($month < 10) {
	    $month = "0$month";
	}

	$sec = "00";
	$date = "$year-$month-$day $hour:$min:$sec";
    $query = mysql_query("SELECT * FROM $table_members WHERE status='Super Administrator' ORDER BY username");
    $quser = mysql_fetch_array($query);
    $aid = $quser['username'];
	if ($uid == 0) $author = "";
	if ($hometext == $bodytext) $bodytext = "";
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$notes = stripslashes(FixQuotes($notes));
	$result = $db->query("insert into ".$prefix."_autonews values (NULL, '$catid', '$aid', '$subject', '$date', '$hometext', '$bodytext', '$topic', '$author', '$notes', '$ihome', '$acomm', '$associated')");
	if (!$result) {
	    return;
	}
	if ($uid == 0) {
	} else {
    $db->query("update $table_members set counter=counter+1 where uid='$uid'");
	}
	$db->query("delete from ".$prefix."_queue where qid=$qid");
	Header("Location: portal_admin.php?op=submissions");
    } else {
	if ($uid == 0) $author = "";
	if ($hometext == $bodytext) $bodytext = "";
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$notes = stripslashes(FixQuotes($notes));
	if (($pollTitle != "") AND ($optionText[1] != "") AND ($optionText[2] != "")) {
	    $haspoll = 1;
	    $timeStamp = time();
	    $pollTitle = FixQuotes($pollTitle);
	    if(!$db->query("INSERT INTO ".$prefix."_poll_desc VALUES (NULL, '$pollTitle', '$timeStamp', 0, '0')")) {
		return;
	    }
	    $object = msql_fetch_object($db->query("SELECT pollID FROM ".$prefix."_poll_desc WHERE pollTitle='$pollTitle'"));
	    $id = $object->pollID;
	    for($i = 1; $i <= sizeof($optionText); $i++) {
		if($optionText[$i] != "") {
		    $optionText[$i] = FixQuotes($optionText[$i]);
		}
		if(!$db->query("INSERT INTO ".$prefix."_poll_data (pollID, optionText, optionCount, voteID) VALUES ($id, '$optionText[$i]', 0, $i)")) {
		    return;
		}
	    }
	} else {
	    $haspoll = 0;
	    $id = 0;
	}
    
	$result = $db->query("insert into ".$prefix."_stories values (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topic', '$author', '$notes', '$ihome', '$acomm', '$haspoll', '$id', '0', '0', '$associated')");
	$result = $db->query("select storyid from ".$prefix."_stories WHERE title='$subject' order by time DESC limit 0,1");
	list($artid) = $db->fetch_row($result);
	$db->query("UPDATE ".$prefix."_poll_desc SET artid='$artid' WHERE pollID='$id'");
	if (!$result) {
	    return;
	}
	if ($uid == 0) {
	} else {
     $db->query("update $table_members set counter=counter+1 where uid='$uid'");
	}
	deleteStory($qid);
    }
}

function editStory($storyid) {
    global $aid, $prefix, $db, $status, $borderwidth, $altbg1, $tablespace, $bordercolor, $portal_images, $borderwidth, $tablespace, $bordercolor, $altbg2;
    $result2 = $db->query("select aid from ".$prefix."_stories where storyid='$storyid'");
    list($aaid) = $db->fetch_row($result2);
    if (($status == "Super Administrator" || $status == "Administrator") OR ($aaid == $aid)) {
	include ('portal_header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><b>Articles/Stories Administration</b></center>";
	CloseTable();
	echo "<br>";
	$result = $db->query("SELECT catid, title, hometext, bodytext, topic, notes, ihome, acomm FROM ".$prefix."_stories where storyid=$storyid");
    list($catid, $subject, $hometext, $bodytext, $topic, $notes, $ihome, $acomm) = $db->fetch_row($result);
	$subject = stripslashes($subject);
        $hometext = stripslashes($hometext);
        $bodytext = stripslashes($bodytext);
        $notes = stripslashes($notes);
        $result2 = $db->query("select topicimage from ".$prefix."_topics where topicid=$topic");
        list($topicimage) = $db->fetch_row($result2);
        OpenTable();
        echo "<center><b>Edit Article</b></center><br>"
	    ."<table width=\"100%\" border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\">"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">"
	    ."<img src=\"$portal_images/topics/$topicimage\" border=\"0\" align=\"right\">";
	    themepreview($subject, $hometext, $bodytext, $notes);
	    echo "</td></tr>"
	    ."<form action=\"portal_admin.php\" method=\"post\">"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title</b></td>"
	    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"></td></tr>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"topic\">";
	$toplist = $db->query("select topicid, topictext from ".$prefix."_topics order by topictext");
	echo "<option value=\"\">All Topics</option>\n";
	while(list($topicid, $topics) = $db->fetch_row($toplist)) {
    	    if ($topicid==$topic) { $sel = "selected "; }
        	echo "<option $sel value=\"$topicid\">$topics</option>\n";
		$sel = "";
	}
	echo "</select>";
	echo "</td></tr>";
	$asql = "SELECT associated FROM ".$prefix."_stories WHERE storyid='$storyid'";
	$aresult = $db->query($asql);
	$arow = $db->fetch_array($aresult);
	$asso_t = explode("-", $arow[associated]);
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Associated Topics</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\">";
	$sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->query($sql);
	while ($row = $db->fetch_array($result)) {
	    if ($a == 3) {
		echo "<br>";
		$a = 0;
	    }
	    for ($i=0; $i<sizeof($asso_t); $i++) {
		if ($asso_t[$i] == $row[topicid]) {
		    $checked = "CHECKED";
		    break;
		}
	    }
	    echo "<input type='checkbox' name='assotop[]' value='$row[topicid]' $checked>$row[topictext]";
	    $checked = "";
	    $a++;
	}
	echo "</td></tr>";
	$cat = $catid;
	SelectCategory($cat);
	puthome($ihome, $acomm);
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Story Text</b></td>"
	    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$hometext</textarea></td></tr>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Extended Text</b></td>"
	    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"bodytext\">$bodytext</textarea><br>"
	    ."</td></tr>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Notes</b></td>"
	    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\">$notes</textarea></td></tr>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><input type=\"hidden\" NAME=\"storyid\" size=\"50\" value=\"$storyid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"ChangeStory\">"
	    ."<input type=\"submit\" value=\"Save Changes\">"
	    ."</td></tr></table></form>";
	CloseTable();
	include ('portal_footer.php');
    } else {
	include ('portal_header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><b>Articles/Stories Administration</b></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>You aren't authorized to touch this Article!</b><br><br>"
    ."You can't edit and/or delete articles that you don't published<br><br>"
    ."[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
	CloseTable();
	include("portal_footer.php");
    }
}

function removeStory($storyid, $ok=0) {
    global $aid, $prefix, $db, $table_members, $status;
    $result2 = $db->query("select aid from ".$prefix."_stories where storyid='$storyid'");
    list($aaid) = $db->fetch_row($result2);
    if (($aaid == $aid) OR ($status == "Super Administrator" || $status == "Administrator")) {
	if($ok) {
	    $counter--;
	    $db->query("DELETE FROM ".$prefix."_stories where storyid=$storyid");
	    $db->query("DELETE FROM ".$prefix."_comments where storyid=$storyid");
	    $db->query("update ".$prefix."_poll_desc set artid='0' where artid='$storyid'");
	    $result = $db->query("update $table_members set counter='$counter' where username='$aid'");
	    Header("Location: portal_admin.php");
	    } else {
	    include("portal_header.php");
	    GraphicAdmin();
	    OpenTable();
	    echo "<center><b>Articles/Stories Administration</b></center>";
	    CloseTable();
	    echo "<br>";
	    OpenTable();
	    echo "<center>Are you sure you want to remove Story ID # $storyid and all it's comments?";
	    echo "<br><br>[ <a href=\"portal_admin.php\">No</a> | <a href=\"portal_admin.php?op=RemoveStory&amp;storyid=$storyid&amp;ok=1\">Yes</a> ]</center>";
	    CloseTable();
	    include("portal_footer.php");
	}
    } else {
	include ('portal_header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><b>Articles/Stories Administration</b></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>You aren't authorized to touch this Article!</b><br><br>"
    ."You can't edit and/or delete articles that you don't published<br><br>"
    ."[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
	CloseTable();
	include("portal_footer.php");
    }
}


function changeStory($storyid, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $acomm, $assotop) {
    global $aid, $prefix, $db, $status;
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    $result2 = $db->query("select aid from ".$prefix."_stories where storyid='$storyid'");
    list($aaid) = $db->fetch_row($result2);
    if (($aaid == $aid) OR ($status == "Super Administrator" || $status == "Administrator")) {
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$notes = stripslashes(FixQuotes($notes));
	$db->query("update ".$prefix."_stories set catid='$catid', title='$subject', hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome', acomm='$acomm', associated='$associated' where storyid=$storyid");
	Header("Location: portal_admin.php?op=adminMain");
    }
}

function adminStory() {
    global $prefix, $db, $borderwidth, $altbg1, $altbg2, $tablespace, $bordercolor;
    include ('portal_header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Articles/Stories Administration</b></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $ttmon = $today[mon];
    if ($ttmon < 10){
	$ttmon = "0$ttmon";
    }
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    OpenTable();
    echo "<center><b>Add New Article</b></center><br><br>"
   	."<form action=\"portal_admin.php\" method=\"post\">"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"subject\" size=\"50\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic</b></td>";
    $toplist = $db->query("select topicid, topictext from ".$prefix."_topics order by topictext");
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"topic\">";
    echo "<option value=\"\">Select Topic</option>\n";
    while(list($topicid, $topics) = $db->fetch_row($toplist)) {
    if ($topicid == $topic) {
	    $sel = "selected ";
	}
	echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select></td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Associated Topics</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\">";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
	if ($a == 3) {
	    echo "<br>";
	    $a = 0;
	}
	echo "<input type='checkbox' name='assotop[]' value='$row[topicid]'>$row[topictext]";
	$a++;
    }
    echo "</td></tr>";
    $cat = 0;
    SelectCategory($cat);
    puthome($ihome, $acomm);
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Story Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"hometext\"></textarea></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Extended Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"bodytext\"></textarea>"
	."</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Do you want to program this story?</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=radio name=automated value=1>Yes &nbsp;&nbsp;"
	."<input type=radio name=automated value=0 checked>No</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Now is:</b> $date</td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\">";
    $day = 1;
    echo "<b>Day:</b> <select name=\"day\">";
    while ($day <= 31) {
	if ($tday==$day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$day</option>";
	$day++;
    }
    echo "</select>";
    $month = 1;
    echo "<b>Month:</b> <select name=\"month\">";
    while ($month <= 12) {
	if ($ttmon==$month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$month</option>";
	$month++;
    }
    echo "</select>";
    $date = getdate();
    $year = $date[year];
    echo "<b>Year:</b> <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">"
	."<br><b>Hour:</b> <select name=\"hour\">";
    $hour = 0;
    $cero = "0";
    while ($hour <= 23) {
	$dummy = $hour;
	if ($hour < 10) {
	    $hour = "$cero$hour";
	}
	echo "<option name=\"hour\">$hour</option>";
	$hour = $dummy;
	$hour++;
    }
    echo "</select>"
	.": <select name=\"min\">";
    $min = 0;
    while ($min <= 59) {
	if (($min == 0) OR ($min == 5)) {
	    $min = "0$min";
	}
	echo "<option name=\"min\">$min</option>";
	$min = $min + 5;
    }
    echo "</select>";
    echo ": <b>00</b></td></tr>"
	."<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><select name=\"op\">"
	."<option value=\"PreviewAdminStory\" selected>Preview Story</option>"
	."<option value=\"PostAdminStory\">Post Story</option>"
	."</select>"
	."<input type=\"submit\" value=\"Ok!\"></td></tr></table>";
    CloseTable();
    echo "<br>";
    putpoll($pollTitle, $optionText);
    echo "</form>";
    include ('portal_footer.php');
}

function previewAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome, $acomm, $pollTitle, $optionText, $assotop) {
    global $xmbuser, $prefix, $db, $portal_images, $tablespace, $borderwidth, $bordercolor, $altbg1, $altbg2;
    include ('portal_header.php');
    if ($topic<1) {
        $topic = 1;
    }
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Articles/Stories Administration</b></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    OpenTable();
    echo "<center><b>Preview Story</b></center><br><br>"
	."<form action=\"portal_admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
    $subject = stripslashes($subject);
    $subject = ereg_replace("\"", "''", $subject);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $result = $db->query("select topicimage from ".$prefix."_topics where topicid=$topic");
    list($topicimage) = $db->fetch_row($result);
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">"
	."<img src=\"$portal_images/topics/$topicimage\" border=\"0\" align=\"right\" alt=\"\">";
    themepreview($subject, $hometext, $bodytext);
    echo "</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"topic\">";
    $toplist = $db->query("select topicid, topictext from ".$prefix."_topics order by topictext");
    echo "<option value=\"\">All Topics</option>\n";
    while(list($topicid, $topics) = $db->fetch_row($toplist)) {
	if ($topicid==$topic) {
    $sel = "selected ";
	}
    echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select></td></tr>";
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    $asso_t = explode("-", $associated);
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Associated Topics</b></td>"
        ."<td bgcolor=\"$altbg2\" class=\"tablerow\">";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->query($sql);
    while ($row = $db->fetch_array($result)) {
        if ($a == 3) {
    	    echo "<br>";
	    $a = 0;
	}
	for ($i=0; $i<sizeof($asso_t); $i++) {
	    if ($asso_t[$i] == $row[topicid]) {
	        $checked = "CHECKED";
	        break;
	    }
	}
	echo "<input type='checkbox' name='assotop[]' value='$row[topicid]' $checked>$row[topictext]";
	$checked = "";
	$a++;
    }
    echo "</td></tr>";
    $cat = $catid;
    SelectCategory($cat);
    puthome($ihome, $acomm);
    
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Story Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"hometext\">$hometext</textarea></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Extended Text</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"bodytext\">$bodytext</textarea></td></tr>";
    if ($automated == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } else {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Do you want to program this story?</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"automated\" value=\"1\" $sel1>Yes &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"0\" $sel2>No</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Now is:</b> $date</td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\">";
    $xday = 1;
    echo "<b>Day:</b> <select name=\"day\">";
    while ($xday <= 31) {
	if ($xday == $day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$xday</option>";
	$xday++;
    }
    echo "</select>";
    $xmonth = 1;
    echo "<b>Month:</b> <select name=\"month\">";
    while ($xmonth <= 12) {
	if ($xmonth == $month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$xmonth</option>";
	$xmonth++;
    }
    echo "</select>";
    echo "<b>Year:</b> <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">";
    echo "<br><b>Hour:</b> <select name=\"hour\">";
    $xhour = 0;
    $cero = "0";
    while ($xhour <= 23) {
	$dummy = $xhour;
	if ($xhour < 10) {
	    $xhour = "$cero$xhour";
	}
	if ($xhour == $hour) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"hour\" $sel>$xhour</option>";
	$xhour = $dummy;
	$xhour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $xmin = 0;
    while ($xmin <= 59) {
	if (($xmin == 0) OR ($xmin == 5)) {
	    $xmin = "0$xmin";
	}
	if ($xmin == $min) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"min\" $sel>$xmin</option>";
	$xmin = $xmin + 5;
    }
    echo "</select>";
    echo ": <b>00</b></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><select name=\"op\">"
	."<option value=\"PreviewAdminStory\" selected>Preview Story</option>"
	."<option value=\"PostAdminStory\">Post Story</option>"
	."</select>"
	."<input type=\"submit\" value=\"Ok!\"></td></tr></table>";
    CloseTable();
    echo "<br>";
    putpoll($pollTitle, $optionText);
    echo "</form>";
    include ('portal_footer.php');
}

function postAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome, $acomm, $pollTitle, $optionText, $assotop) {
    global $xmbuser, $prefix, $db, $table_members;
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    if ($automated == 1) {
	if ($day < 10) {
	    $day = "0$day";
	}
	if ($month < 10) {
	    $month = "0$month";
	}
	$sec = "00";
	$date = "$year-$month-$day $hour:$min:$sec";
	$notes = "";
	$author = "$xmbuser";
	$subject = stripslashes(FixQuotes($subject));
	$subject = ereg_replace("\"", "''", $subject);
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$result = $db->query("insert into ".$prefix."_autonews values (NULL, '$catid', '$xmbuser', '$subject', '$date', '$hometext', '$bodytext', '$topic', '$author', '$notes', '$ihome', '$acomm', '$associated')");
	if (!$result) {
	    exit();
	}
	$result = $db->query("update $table_members set counter=counter+1 where username='$xmbuser'");
	Header("Location: portal_admin.php?op=adminMain");
    } else {
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	if (($pollTitle != "") AND ($optionText[1] != "") AND ($optionText[2] != "")) {
	    $haspoll = 1;
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
		if(!$db->query("INSERT INTO ".$prefix."_poll_data (pollID, optionText, optionCount, voteID) VALUES ($id, '$optionText[$i]', 0, $i)")) {
		    return;
		}
	    }
	} else {
	    $haspoll = 0;
	    $id = 0;
	}
	$result = $db->query("insert into ".$prefix."_stories values (NULL, '$catid', '$xmbuser', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topic', '$xmbuser', '$notes', '$ihome', '$acomm', '$haspoll', '$id', '0', '0', '$associated')");
	$result = $db->query("select storyid from ".$prefix."_stories WHERE title='$subject' order by time DESC limit 0,1");
	list($artid) = $db->fetch_row($result);
	$db->query("UPDATE ".$prefix."_poll_desc SET artid='$artid' WHERE pollID='$id'");
	if (!$result) {
	    exit();
	}
	$result = $db->query("update $table_members set counter=counter+1 where username='$aid'");
	Header("Location: portal_admin.php?op=adminMain");
    }
}

function submissions() {
    global $status, $prefix, $db, $bordercolor, $altbg2, $altbg1, $borderwidth, $tablespace;
    $dummy = 0;
    include ("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Stories Submissions Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
	$result = $db->query("SELECT qid, subject, timestamp FROM ".$prefix."_queue order by timestamp DESC");
	if($db->num_rows($result) == 0) {
	    echo "<table width=\"100%\"><tr><td align=\"center\" class=\"tablerow\"><b>No New Submissions</b></td></tr></table>\n";
	} else {
	    echo "<center><b>New Stories Submissions</b><form action=\"portal_admin.php\" method=\"post\"><table width=\"100%\" border=\"0\" bgcolor=\"$bordercolor\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\">";
        echo "<tr>";
        echo "<td class=\"header\">Option</td>";
        echo "<td class=\"header\">Topics</td>";
        echo "<td class=\"header\">Date</td></tr>";
	    while (list($qid, $subject, $timestamp) = $db->fetch_row($result)) {
		$hour = "AM";
		ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $timestamp, $datetime);
		if ($datetime[4] > 12) { $datetime[4] = $datetime[4]-12; $hour = "PM"; }
		$datetime = date("%A, %B %d @ %T %Z", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		echo "<tr>\n"
        ."<td align=\"center\" bgcolor=\"$altbg1\" class=\"tablerow\">(<a href=\"portal_admin.php?op=DeleteStory&amp;qid=$qid\">Delete</a>)&nbsp;</td>\n"
   	    ."<td bgcolor=\"$altbg1\" width=\"50%\" class=\"tablerow\"><font class=\"tablerow\">\n";
		if ($subject == "") {
		    echo "&nbsp;<a href=\"portal_admin.php?op=DisplayStory&amp;qid=$qid\">No Subject</a></font>\n";
		} else {
		    echo "&nbsp;<a href=\"portal_admin.php?op=DisplayStory&amp;qid=$qid\">$subject</a></font>\n";
		}

		echo "</td>\n";
		$timestamp = ereg_replace(" ", "@", $timestamp);
		echo "<td align=\"center\" bgcolor=\"$altbg1\" class=\"tablerow\"><font class=\"tablerow\">&nbsp;$timestamp&nbsp;</font></td></tr>\n";
		$dummy++;
	    }
	    if ($dummy < 1) {
		echo "<tr><td align=\"center\" class=\"tablerow\"><b>No New Submissions</b></form></td></tr></table>\n";
	    } else {
		echo "</table></form>\n";
	    }
	}
    if ($status == "Super Administrator" || $status == "Administrator") {
	echo "<br><center>"
    ."[ <a href=\"portal_admin.php?op=subdelete\">Delete</a> ]"
    ."</center><br>";
    }
    CloseTable();
    include ("portal_footer.php");
}

function subdelete() {
    global $prefix, $db;
    $db->query("delete from ".$prefix."_queue");
    Header("Location: portal_admin.php?op=adminMain");
}

switch($op) {

    case "EditCategory":
    EditCategory($catid);
    break;

    case "subdelete":
    subdelete();
    break;

    case "DelCategory":
    DelCategory($cat);
    break;

    case "YesDelCategory":
    YesDelCategory($catid);
    break;

    case "NoMoveCategory":
    NoMoveCategory($catid, $newcat);
    break;

    case "SaveEditCategory":
    SaveEditCategory($catid, $xtitle);
    break;

    case "SelectCategory":
    SelectCategory($cat);
    break;

    case "AddCategory":
    AddCategory();
    break;

    case "SaveCategory":
    SaveCategory($xtitle);
    break;

    case "DisplayStory":
    displayStory($qid);
    break;

    case "PreviewAgain":
    previewStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $acomm, $pollTitle, $optionText, $assotop);
    break;

    case "PostStory":
    postStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $acomm, $pollTitle, $optionText, $assotop);
    break;

    case "EditStory":
    editStory($storyid);
    break;

    case "RemoveStory":
    removeStory($storyid, $ok);
    break;

    case "ChangeStory":
    changeStory($storyid, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $acomm, $assotop);
    break;

    case "DeleteStory":
    deleteStory($qid);
    break;

    case "adminStory":
    adminStory($storyid);
    break;

    case "PreviewAdminStory":
    previewAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome, $acomm, $pollTitle, $optionText, $assotop);
    break;

    case "PostAdminStory":
    postAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome, $acomm, $pollTitle, $optionText, $assotop);
    break;

    case "autoDelete":
    autodelete($anid);
    break;

    case "autoEdit":
    autoEdit($anid);
    break;

    case "autoSaveEdit":
    autoSaveEdit($anid, $year, $day, $month, $hour, $min, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $acomm);
    break;

    case "submissions":
    submissions();
    break;

}

} else {
    echo "Access Denied";
}

?>
