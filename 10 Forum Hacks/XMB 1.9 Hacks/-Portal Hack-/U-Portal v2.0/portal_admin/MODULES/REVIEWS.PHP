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

function mod_main($title, $description) {
    global $prefix, $db;
    $title = stripslashes(FixQuotes($title));
    $description = stripslashes(FixQuotes($description));
    $db->query("update ".$prefix."_reviews_main set title='$title', description='$description'");
    Header("Location: portal_admin.php?op=reviews");
}

function reviews() {
    global $prefix, $db, $bordercolor, $tablespace, $borderwidth, $altbg1, $altbg2;
    include ("portal_header.php");
    
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Reviews Administration</b></center>";
    CloseTable();
    echo "<br>";
    $resultrm = $db->query("select title, description from ".$prefix."_reviews_main");
    list($title, $description) = $db->fetch_row($resultrm);
    OpenTable();
    echo "<center><b>Add Reviews Page title & Description</b></center><br>";
    echo "<form action=\"portal_admin.php\" method=\"post\">"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Reviews Page Title</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Reviews Page Description</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea name=\"description\" rows=\"15\" wrap=\"virtual\" cols=\"60\">$description</textarea></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><input type=\"hidden\" name=\"op\" value=\"mod_main\">"
	."<input type=\"submit\" value=\"Save Changes\">"
	."</td></tr></table></form>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>Reviews Waiting for Validation</b></font><br>";
    $result = $db->query("select * from ".$prefix."_reviews_add order by id");
    $numrows = $db->num_rows($result);
    if ($numrows>0) {
	while(list($id, $date, $title, $text, $reviewer, $email, $score, $url, $url_title) = $db->fetch_row($result)) {
	    $title = stripslashes($title);
	    $text = stripslashes($text);
	    echo "<form action=\"portal_admin.php\" method=\"post\">"
		."<hr noshade size=\"1\"><br><table border=\"0\" cellpadding=\"1\" cellspacing=\"2\">"
		."<tr><td><b>Review ID:</td><td><b>$id</b></td></tr>"
		."<input type=\"hidden\" name=\"id\" value=\"$id\">"
		."<tr><td>Date:</td><td><input type=\"text\" name=\"date\" value=\"$date\" size=\"11\" maxlength=\"10\"></td></tr>"
		."<tr><td>Product Title:</td><td><input type=\"text\" name=\"title\" value=\"$title\" size=\"25\" maxlength=\"40\"></td></tr>";
	    echo "<tr><td>Text:</td><td><TEXTAREA name=\"text\" rows=\"6\" wrap=\"virtual\" cols=\"40\">$text</textarea></td></tr>"
		."<tr><td>Reviewer:</td><td><input type=\"text\" name=\"reviewer\" value=\"$reviewer\" size=\"41\" maxlength=\"40\"></td></tr>"
		."<tr><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$email\" size=\"41\" maxlength=\"80\"></td></tr>"
		."<tr><td>Score:</td><td><input type=\"text\" name=\"score\" value=\"$score\" size=\"3\" maxlength=\"2\"></td></tr>";
	    if ($url != "") {
		echo "<tr><td>Related Link:</td><td><input type=\"text\" name=\"url\" value=\"$url\" size=\"25\" maxlength=\"100\"></td></tr>"
		    ."<tr><td>Link Title:</td><td><input type=\"text\" name=\"url_title\" value=\"$url_title\" size=\"25\" maxlength=\"50\"></td></tr>";
	    }
	    echo "<tr><td>Image:</td><td><input type=\"text\" name=\"cover\" size=\"25\" maxlength=\"100\"><br><i>Store your 150*150 image in images/reviews</i></td></tr></table>";
	    echo "<input type=\"hidden\" name=\"op\" value=\"add_review\"><input type=\"submit\" value=\"Add Review\"> - [ <a href=\"portal_admin.php?op=deleteNotice&amp;id=$id&amp;table=".$prefix."_reviews_add&amp;op_back=reviews\">Delete</a> ]</form>";
	}
    } else {
    	echo "<br><br><i>No reviews to add</i><br><br>";
    }
    echo "<a href=\"modules.php?modulename=reviews&rop=write_review\">Click here to write a review</a></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>Delete / Modify a review</b></font><br><br>"
	."You can simply delete/modify reviews by browsing <a href=\"modules.php?modulename=reviews\">Reviews</a> as Admin.</center>";
    CloseTable();
    include ("portal_footer.php");
}

function add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title) {
    global $prefix, $db;
    $title = stripslashes(FixQuotes($title));
    $text = stripslashes(FixQuotes($text));
    $reviewer = stripslashes(FixQuotes($reviewer));
    $email = stripslashes(FixQuotes($email));
    $db->query("insert into ".$prefix."_reviews values (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
    $db->query("delete from ".$prefix."_reviews_add WHERE id = $id");
    Header("Location: portal_admin.php?op=reviews");
}

switch ($op){

    case "reviews":
    reviews();
    break;

    case "add_review":
    add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title);
    break;

    case "mod_main":
    mod_main($title, $description);
    break;

}

} else {
    echo "Access Denied";
}

?>
