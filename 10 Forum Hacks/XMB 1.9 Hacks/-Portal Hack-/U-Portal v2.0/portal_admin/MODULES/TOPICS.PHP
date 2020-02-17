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

function topicsmanager() {
    global $prefix, $portal_images, $bordercolor, $bordercolor, $tablespace, $borderwidth, $altbg1, $altbg2;
    include("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Topics Manager</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Current Active Topics</b><br>Click on Topic to Edit</font></center><br>"
	."<table border=\"0\" width=\"100%\" align=\"center\" cellpadding=\"2\">";
    $count = 0;
    $result = mysql_query("select topicid, topicname, topicimage, topictext from ".$prefix."_topics order by topicname");
    while(list($topicid, $topicname, $topicimage, $topictext) = mysql_fetch_row($result)) {
	echo "<td align=\"center\" class=\"tablerow\">"
    ."<a href=\"portal_admin.php?op=topicedit&amp;topicid=$topicid\"><img src=\"$portal_images/topics/$topicimage\" border=\"0\" alt=\"\"></a><br>"
    ."<b>$topictext</td>";
	$count++;
	if ($count == 5) {
	    echo "</tr><tr>";
	    $count = 0;
	}
    }
    echo "</table>";
    CloseTable();
    echo "<br><a name=\"Add\">";
    OpenTable();
    echo "<center><b>Add a New Topic</b></center><br>"
   	."<form action=\"portal_admin.php\" method=\"post\">"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic Name:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"topicname\" size=\"20\" maxlength=\"20\" value=\"$topicname\"><br>(just a name without spaces - max: 20 characters)<br>(for example: gamesandhobbies)<br></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic Text:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"topictext\" size=\"40\" maxlength=\"40\" value=\"$topictext\"><br>(the full topic text or description - max: 40 characters)<br>(for example: Games and Hobbies)</font><br></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic Image:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"topicimage\">";
    $path = "$portal_images/topics";
    $handle=opendir($path);
    while ($file = readdir($handle)) {
	if ( (ereg("^([_0-9a-zA-Z]+)([.]{1})([_0-9a-zA-Z]{3})$",$file)) AND $file != "AllTopics.gif") {
	    $tlist .= "$file ";
	}
    }
    closedir($handle);
    $tlist = explode(" ", $tlist);
    sort($tlist);
    for ($i=0; $i < sizeof($tlist); $i++) {
	if($tlist[$i]!="") {
	    echo "<option name=\"topicimage\" value=\"$tlist[$i]\">$tlist[$i]\n";
	}
    }
    echo "</select></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><input type=\"hidden\" name=\"op\" value=\"topicmake\">"
	."<input type=\"submit\" value=\"Add Topic\">"
	."</td></tr></table></form>";
    CloseTable();
    include("portal_footer.php");
}

function topicedit($topicid) {
    global $prefix, $portal_images, $bordercolor, $borderwidth, $tablespace, $altbg1, $altbg2;
    include("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Topics Manager</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $result = mysql_query("select topicid, topicname, topicimage, topictext from ".$prefix."_topics where topicid=$topicid");
    list($topicid, $topicname, $topicimage, $topictext) = mysql_fetch_row($result);
    BeginTable();
    echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"3\"><img src=\"$portal_images/topics/$topicimage\" border=\"0\" align=\"right\" alt=\"$topictext\">"
	."<b>Edit Topic: $topictext</b>"
	."</td></tr>"
	."<form action=\"portal_admin.php\" method=\"post\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic Name:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><input type=\"text\" name=\"topicname\" size=\"20\" maxlength=\"20\" value=\"$topicname\"><br>(just a name without spaces - max: 20 characters)<br>(for example: gamesandhobbies)</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic Text:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><input type=\"text\" name=\"topictext\" size=\"40\" maxlength=\"40\" value=\"$topictext\"><br>(the full topic text or description - max: 40 characters)<br>(for example: Games and Hobbies)</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Topic Image:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><select name=\"topicimage\">";

    $path = "$portal_images/topics";
    $handle=opendir($path);
    while ($file = readdir($handle)) {
	if ( (ereg("^([_0-9a-zA-Z]+)([.]{1})([_0-9a-zA-Z]{3})$",$file)) AND $file != "AllTopics.gif") {
	    $tlist .= "$file ";
	}
    }
    closedir($handle);
    $tlist = explode(" ", $tlist);
    sort($tlist);
    for ($i=0; $i < sizeof($tlist); $i++) {
	if($tlist[$i]!="") {
	    if ($topicimage == $tlist[$i]) {
		$sel = "selected";
	    } else {
		$sel = "";
	    }
	    echo "<option name=\"topicimage\" value=\"$tlist[$i]\" $sel>$tlist[$i]\n";
	}
    }
    echo "</select></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"3\" align=\"center\"><b>Add Related Links:</b></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Site Name:</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><input type=\"text\" name=\"relatedname\" size=\"30\" maxlength=\"30\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>URL:</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><input type=\"text\" name=\"url\" value=\"http://\" size=\"50\" maxlength=\"200\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"3\" align=\"center\"><b>Active Related Links:</b>"
	."</td></tr>";
    $res=mysql_query("select rid, name, url from ".$prefix."_related where tid=$topicid");
    $num = mysql_num_rows($res);
    if ($num == 0) {
	echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"3\"><font class=\"tablerow\">There are no related links for this topic</font></td></tr>";
    }
    while(list($rid, $relatedname, $url) = mysql_fetch_row($res)) {
    echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"left\"><strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"$url\">$relatedname</a></td>"
    ."<td bgcolor=\"$altbg1\" class=\"tablerow\"align=\"center\"><a href=\"$url\">$url</a></td><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"right\"><font class=\"subject\">[ <a href=\"portal_admin.php?op=relatededit&amp;tid=$topicid&amp;rid=$rid\">Edit</a> | <a href=\"portal_admin.php?op=relateddelete&amp;tid=$topicid&amp;rid=$rid\">Delete</a> ]</font></td></tr>";
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"3\">"
	."<input type=\"hidden\" name=\"topicid\" value=\"$topicid\">"
	."<input type=\"hidden\" name=\"op\" value=\"topicchange\">"
    ."<INPUT type=\"submit\" value=\"Save Changes\">    [ <a href=\"portal_admin.php?op=topicdelete&amp;topicid=$topicid\">Delete</a> ]</font>"
	."</td></tr>";
    EndTable();
    echo"</form>";
    CloseTable();
    include("portal_footer.php");
}

function relatededit($tid, $rid) {
    global $prefix, $db, $portal_images, $bordercolor, $borderwidth, $tablespace, $altbg1, $altbg2;
    include("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Topics Manager</b></center>";
    CloseTable();
    echo "<br>";
    $result=mysql_query("select name, url from ".$prefix."_related where rid=$rid");
    list($relatedname, $url) = mysql_fetch_row($result);
    $result2=mysql_query("select topictext, topicimage from ".$prefix."_topics where topicid=$tid");
    list($topictext, $topicimage) = mysql_fetch_row($result2);
    OpenTable();
    BeginTable();
    echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\">"
	."<img src=\"$portal_images/topics/$topicimage\" border=\"0\" alt=\"$topictext\" align=\"right\">"
	."<b>Edit Related Link</b></font><br>"
	."<b>Topic:</b> $topictext</td></tr>"
	."<form action=\"portal_admin.php\" method=\"post\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Site Name:</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"relatedname\" value=\"$relatedname\" size=\"30\" maxlength=\"30\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>URL:</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"url\" value=\"$url\" size=\"60\" maxlength=\"200\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><input type=\"hidden\" name=\"op\" value=\"relatedsave\">"
	."<input type=\"hidden\" name=\"tid\" value=\"$tid\">"
    ."<input type=\"hidden\" name=\"rid\" value=\"$rid\">"
	."<input type=\"submit\" value=\"Save Changes\">   [ <a href=\"javascript:history.go(-1)\">Go Back</a> ]"
	."</td></tr>";
    EndTable();
    echo "</form>";
    CloseTable();
    include("portal_footer.php");
}

function relatedsave($tid, $rid, $relatedname, $url) {
    global $prefix, $db;
    $db->query("update ".$prefix."_related set name='$relatedname', url='$url' where rid=$rid");
    Header("Location: portal_admin.php?op=topicedit&topicid=$tid");
}

function relateddelete($tid, $rid) {
    global $prefix, $db;
    $db->query("delete from ".$prefix."_related where rid='$rid'");
    Header("Location: portal_admin.php?op=topicedit&topicid=$tid");
}

function topicmake($topicname, $topicimage, $topictext) {
    global $prefix, $db;
    $topicname = stripslashes(FixQuotes($topicname));
    $topicimage = stripslashes(FixQuotes($topicimage));
    $topictext = stripslashes(FixQuotes($topictext));
    $db->query("INSERT INTO ".$prefix."_topics VALUES (NULL,'$topicname','$topicimage','$topictext','0')");
    Header("Location: portal_admin.php?op=topicsmanager#Add");
}

function topicchange($topicid, $topicname, $topicimage, $topictext, $relatedname, $url) {
    global $prefix, $db;
    $topicname = stripslashes(FixQuotes($topicname));
    $topicimage = stripslashes(FixQuotes($topicimage));
    $topictext = stripslashes(FixQuotes($topictext));
    $relatedname = stripslashes(FixQuotes($relatedname));
    $url = stripslashes(FixQuotes($url));
    $db->query("update ".$prefix."_topics set topicname='$topicname', topicimage='$topicimage', topictext='$topictext' where topicid=$topicid");
    if (!$relatedname) {
    } else {
    $db->query("insert into ".$prefix."_related VALUES (NULL, '$topicid','$relatedname','$url')");
    }
    Header("Location: portal_admin.php?op=topicedit&topicid=$topicid");
}

function topicdelete($topicid, $ok=0) {
    global $prefix, $db, $portal_images;
    if ($ok==1) {
	$result=$db->query("select storyid from ".$prefix."_stories where topic='$topicid'");
	list($storyid) = $db->fetch_row($result);
	$db->query("delete from ".$prefix."_stories where topic='$topicid'");
	$db->query("delete from ".$prefix."_topics where topicid='$topicid'");
	$db->query("delete from ".$prefix."_related where tid='$topicid'");
	$result = $db->query("select storyid from ".$prefix."_comments where storyid='$storyid'");
	list($storyid) = $db->fetch_row($result);
	$db->query("delete from ".$prefix."_comments where storyid='$storyid'");
	Header("Location: portal_admin.php?op=topicsmanager");
    } else {
	global $topicimage;
	include("portal_header.php");
	GraphicAdmin();
    OpenTable();
	echo "<center><font class=\"subject\"><b>Topics Manager</b></font></center>";
	CloseTable();
	echo "<br>";
	$result2=$db->query("select topicimage, topictext from ".$prefix."_topics where topicid='$topicid'");
	list($topicimage, $topictext) = $db->fetch_row($result2);
	OpenTable();
	echo "<center><img src=\"$portal_images/topics/$topicimage\" border=\"0\" alt=\"$topictext\"><br><br>"
    ."<b>Delete Topic $topictext</b><br><br>"
    ."Are you sure you want to delete Topic <i>$topictext</i>?<br>"
    ."This will delete ALL it's stories and it's comments!<br><br>"
    ."[ <a href=\"portal_admin.php?op=topicsmanager\">No</a> | <a href=\"portal_admin.php?op=topicdelete&amp;topicid=$topicid&amp;ok=1\">Yes</a> ]</center><br><br>";
	CloseTable();
	include("portal_footer.php");
    }
}

switch ($op) {

    case "topicsmanager":
    topicsmanager();
    break;

    case "topicedit":
    topicedit($topicid);
    break;

    case "topicmake":
    topicmake($topicname, $topicimage, $topictext);
    break;

    case "topicdelete":
    topicdelete($topicid, $ok);
    break;

    case "topicchange":
    topicchange($topicid, $topicname, $topicimage, $topictext, $relatedname, $url);
    break;

    case "relatedsave":
    relatedsave($tid, $rid, $relatedname, $url);
    break;
	
    case "relatededit":
    relatededit($tid, $rid);
    break;
			
    case "relateddelete":
    relateddelete($tid, $rid);
    break;

}

} else {
    echo "Access Denied";
}

?>
