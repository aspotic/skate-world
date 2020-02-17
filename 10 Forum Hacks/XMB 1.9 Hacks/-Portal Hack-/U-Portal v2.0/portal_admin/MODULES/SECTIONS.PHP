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

function sections() {
    global $prefix, $db, $portal_images, $borderwidth, $tablespace, $bordercolor, $altbg1, $altbg2;
    include("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"subject\"><b>Sections Administration</b></font></center>";
    CloseTable();
    $result = $db->query("select secid, secname from ".$prefix."_sections order by secid");
    if ($db->num_rows($result) > 0) {
	echo "<br>";
	OpenTable();
	echo "<center><b>Current Active Sections</b></center><br>"
   ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
   ."<tr><td bgcolor=\"$altbg2\" align=center class=\"tablerow\"><b>Sections Name</b></td>"
   ."<td bgcolor=\"$altbg2\" align=center class=\"tablerow\"><b>Options</b></td></tr>";
	while(list($secid, $secname) = $db->fetch_row($result)) {
    echo "<tr>"
      ."<td bgcolor=\"$altbg1\" align=center class=\"tablerow\">$secname </td>"
      ."<td bgcolor=\"$altbg1\" align=center class=\"tablerow\"><a href=\"portal_admin.php?op=sectionedit&amp;secid=$secid\">Edit This Section</a></td></tr>";
	}
	echo "</table>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><font class=\"tablerow\"><b>Add a New Article in Sections</b></font></center><br>"
    ."<form action=\"portal_admin.php\" method=\"post\">"
    ."<b>Title</b><br>"
    ."<input type=\"text\" name=\"title\" size=\"60\"><br><br>"
    ."<b>Select Section:</b><br>";
	$result = $db->query("select secid, secname from ".$prefix."_sections order by secid");
	while(list($secid, $secname) = $db->fetch_row($result)) {
    echo "<input type=\"radio\" name=\"secid\" value=\"$secid\"> $secname<br>";
	}
	echo "<font class=\"smalltxt\">Note: Don't select any section to store the text and publish it later.</font><br>";
	echo "<br><br><b>Content</b><br>"
	    ."<textarea name=\"smalltxt\" cols=\"60\" rows=\"10\"></textarea><br>"
	    ."<font class=\"content\">If you want multiple pages you can write <b>&lt;!--pagebreak--&gt;</b> where you want to cut.</font><br><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"secarticleadd\">"
	    ."<input type=\"submit\" value=\"Add New Article\">"
	    ."</form>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>Last 20 Articles</b></center><br>"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
    ."<tr>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><b>Articles</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\"><b>Section</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\"><b>Options</b></td></tr>";
	 $result = $db->query("select artid, secid, title, content from ".$prefix."_seccont order by artid desc limit 0,20");
        $artcheck = $db->num_rows($result);
        while(list($artid, $secid, $title, $content) = $db->fetch_row($result)) {
	    $result2 = $db->query("select secid, secname from ".$prefix."_sections where secid='$secid'");
	    list($secid, $secname) = $db->fetch_row($result2);
        echo "<tr>"
            ."<td bgcolor=\"$altbg1\" class=\"tablerow\"><li>$title</li></td>"
            ."<td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\">($secname)</td>"
            ."<td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\"><a href=\"portal_admin.php?op=secartedit&amp;artid=$artid\">Edit</a> | <a href=\"portal_admin.php?op=secartdelete&amp;artid=$artid&amp;ok=0\">Delete</a></td></tr>";
	}
    if($artcheck == 0) {
        echo "<tr>"
             ."<td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"3\" align=\"center\">There are no Articles Added in database..</td></tr>";
        }
	echo "</table>"
	    ."<form action=\"portal_admin.php\" method=\"post\">"
	    ."Edit Article ID: <input type=\"text\" name=\"artid\" size=\"10\">&nbsp;&nbsp;"
	    ."<input type=\"hidden\" name=\"op\" value=\"secartedit\">"
	    ."<input type=\"submit\" value=\"Ok!\">"
	    ."</form>";
	CloseTable();
    }
    echo "<br>";
    OpenTable();
    echo "<center><b>Add a New Section</b></center><br>"
	."<form action=\"portal_admin.php\" method=\"post\"><br>"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Section Name:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"secname\" size=\"40\" maxlength=\"40\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Section Image</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"image\" size=\"40\" maxlength=\"50\"><br><font class=\"tablerow\">(Will be under /$portal_images/sections/ directory. Example: opinion.gif)</font></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><input type=\"hidden\" name=\"op\" value=\"sectionmake\">"
	."<INPUT type=\"submit\" value=\"Add Section\">"
	."</td></tr></table></form>";
    CloseTable();
    include("portal_footer.php");
}

function secarticleadd($secid, $title, $content) {
    global $prefix, $db;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    $db->query("INSERT INTO ".$prefix."_seccont VALUES (NULL,'$secid','$title','$content','0')");
    Header("Location: portal_admin.php?op=sections");
}

function secartedit($artid) {
    global $prefix, $db;
    include("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"subject\"><b>Sections Administration</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = $db->query("select artid, secid, title, content from ".$prefix."_seccont where artid='$artid'");
    list($artid, $secid, $title, $content) = $db->fetch_row($result);
    OpenTable();
    echo "<center><font class=\"tablerow\"><b>Edit Article</b></font></center><br>"
	."<form action=\"portal_admin.php\" method=\"post\">"
	."<b>Title</b><br>"
	."<input type=\"text\" name=\"title\" size=\"60\" value=\"$title\"><br><br>"
	."<b>Select Section:</b><br>";
    $result2 = $db->query("select secid, secname from ".$prefix."_sections order by secname");
    while(list($secid2, $secname) = $db->fetch_row($result2)) {
	    if ($secid2==$secid) {
		$che = "checked";
	    }
	    echo "<input type=\"radio\" name=\"secid\" value=\"$secid2\" $che>$secname<br>";
	    $che = "";
    }
    echo "<br><b>Content</b><br>"
	."<textarea name=\"content\" cols=\"60\" rows=\"10\">$content</textarea><br><br>"
	."<input type=\"hidden\" name=\"artid\" value=\"$artid\">"
	."<input type=\"hidden\" name=\"op\" value=\"secartchange\">"
	."<input type=\"submit\" value=\"Save Changes\">  <b> Or </b>[ <a href=\"portal_admin.php?op=secartdelete&amp;artid=$artid&amp;ok=0\">Delete</a> ]"
	."</form>";
    CloseTable();
    include("portal_footer.php");
}

function sectionmake($secname, $image) {
    global $prefix, $db;
    $secname = stripslashes(FixQuotes($secname));
    $image = stripslashes(FixQuotes($image));
    $db->query("INSERT INTO ".$prefix."_sections VALUES (NULL,'$secname', '$image')");
    Header("Location: portal_admin.php?op=sections");
}

function sectionedit($secid) {
    global $prefix, $db, $portal_images, $bordercolor, $tablespace, $borderwidth, $altbg2, $altbg1;
    include("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"subject\"><b>Sections Administration</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = $db->query("select secid, secname, image from ".$prefix."_sections where secid=$secid");
    list($secid, $secname, $image) = $db->fetch_row($result);
    $result2 = $db->query("select artid from ".$prefix."_seccont where secid=$secid");
    $number = $db->num_rows($result2);
    OpenTable();
    echo "<img src=\"$portal_images/sections/$image\" border=\"0\" alt=\"\"><br><br>"
	."<font class=\"tablerow\"><b>Edit Section: $secname</b></font>"
	."<br>(This Section has $number Articles attached)"
	."<br><br>"
	."<form action=\"portal_admin.php\" method=\"post\">"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Section Name</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"secname\" size=\"40\" maxlength=\"40\" value=\"$secname\"><br>(40 characters max.)</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Section Image</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"image\" size=\"40\" maxlength=\"50\" value=\"$image\"><br>(Will be under /$portal_images/sections/ directory. Example: opinion.gif)</td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><input type=\"hidden\" name=\"secid\" value=\"$secid\">"
	."<input type=\"hidden\" name=\"op\" value=\"sectionchange\">"
	."<input type=\"submit\" value=\"Save Changes\"> <b>Or </b>[ <a href=\"portal_admin.php?op=sectiondelete&amp;secid=$secid&amp;ok=0\">Delete</a> ]"
	."</td></tr></table></form>";
    CloseTable();
    include("portal_footer.php");
}

function sectionchange($secid, $secname, $image) {
    global $prefix, $db;
    $secname = stripslashes(FixQuotes($secname));
    $image = stripslashes(FixQuotes($image));
    $db->query("update ".$prefix."_sections set secname='$secname', image='$image' where secid=$secid");
    Header("Location: portal_admin.php?op=sections");
}

function secartchange($artid, $secid, $title, $content) {
    global $prefix, $db;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    $db->query("update ".$prefix."_seccont set secid='$secid', title='$title', content='$content' where artid=$artid");
    Header("Location: portal_admin.php?op=sections");
}

function sectiondelete($secid, $ok=0) {
    global $prefix, $db;
    if ($ok==1) {
    $db->query("delete from ".$prefix."_seccont where secid='$secid'");
    $db->query("delete from ".$prefix."_sections where secid='$secid'");
    Header("Location: portal_admin.php?op=sections");
    } else {
    include("portal_header.php");
    GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"subject\"><b>Sections Administration</b></font></center>";
	CloseTable();
	echo "<br>";
	$result=$db->query("select secname from ".$prefix."_sections where secid=$secid");
	list($secname) = $db->fetch_row($result);
	OpenTable();
	echo "<center><b>Delete Section: $secname</b><br><br>"
    ."Are you sure you want to delete section $secname?<br>"
    ."This will delete ALL its articles!<br><br>"
    ."[ <a href=\"portal_admin.php?op=sections\">No</a> | <a href=\"portal_admin.php?op=sectiondelete&amp;secid=$secid&amp;ok=1\">Yes</a> ]</center>";
	CloseTable();
    include("portal_footer.php");
    }
}

function secartdelete($artid, $ok=0) {
    global $prefix, $db;
    if ($ok==1) {
    $db->query("delete from ".$prefix."_seccont where artid='$artid'");
    Header("Location: portal_admin.php?op=sections");
    } else {
    include("portal_header.php");
    GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"subject\"><b>Sections Administration</b></font></center>";
	CloseTable();
	echo "<br>";
	$result = $db->query("select title from ".$prefix."_seccont where artid=$artid");
	list($title) = $db->fetch_row($result);
	OpenTable();
	echo "<center><b>Delete Article: $title</b><br><br>"
    ."Are you sure you want to delete this article?<br><br>"
    ."[ <a href=\"portal_admin.php?op=sections\">No</a> | <a href=\"portal_admin.php?op=secartdelete&amp;artid=$artid&amp;ok=1\">Yes</a> ]</center>";
	CloseTable();
    include("portal_footer.php");
    }
}

switch ($op) {

    case "sections":
    sections();
    break;

    case "sectionedit":
    sectionedit($secid);
    break;

    case "sectionmake":
    sectionmake($secname, $image);
    break;

    case "sectiondelete":
    sectiondelete($secid, $ok);
    break;

    case "sectionchange":
    sectionchange($secid, $secname, $image);
    break;

    case "secarticleadd":
    secarticleadd($secid, $title, $content);
    break;

    case "secartedit":
    secartedit($artid);
    break;

    case "secartchange":
    secartchange($artid, $secid, $title, $content);
    break;

    case "secartdelete":
    secartdelete($artid, $ok);
    break;

}

} else {
    echo "Access Denied";
}

?>
