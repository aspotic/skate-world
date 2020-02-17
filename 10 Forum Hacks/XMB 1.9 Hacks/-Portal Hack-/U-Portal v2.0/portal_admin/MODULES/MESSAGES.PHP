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

function MsgDeactive($mid) {
    global $prefix, $db;
    $db->query("update ".$prefix."_message set active='0' WHERE mid='$mid'");
    Header("Location: admin.php?op=messages");
}

function messages() {
    global $status, $altbg1, $altbg2, $prefix, $db, $bordercolor, $borderwidth, $tablespace;
    include ("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Messages Administration</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Overview messages</b><br><br><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">"
	."<td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\"><b>ID</b></td>"
	."<td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\"><b>Title</b></td>"
	."<td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\" nowrap>&nbsp;<b>Visible to</b>&nbsp;</td>"
	."<td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\">&nbsp;<b>Active</b>&nbsp;</td>"
	."<td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\">&nbsp;<b>Functions</b>&nbsp;</td></tr>";
    $result = $db->query("select mid, title, content, date, expire, active, view from ".$prefix."_message");
    while(list($mid, $title, $content, $mdate, $expire, $active, $view) = $db->fetch_row($result)) {
    $mcheck = $db->num_rows($result);
    if ($active == 1) {
	$mactive = "Yes";
    } elseif ($active == 0) {
	$mactive = "No";
    }
    if ($view == 1) {
	$mview = "All Visitors";
    } elseif ($view == 2) {
	$mview = "Staff Users Only";
    } elseif ($view == 3) {
	$mview = "Registered Users Only";
    } elseif ($view == 4) {
	$mview = "Administrators Only";
    }
	echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"right\"><b>$mid</b>"
	    ."</td><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"left\" width=\"100%\"><b>$title</b>"
	    ."</td><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" nowrap>$mview"
	    ."</td><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\">$mactive"
	    ."</td><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"right\" nowrap>(<a href=\"portal_admin.php?op=editmsg&mid=$mid\">Edit</a>-<a href=\"portal_admin.php?op=deletemsg&mid=$mid\">Delete</a>)"
	    ."</td></tr>";

    }
    if($mcheck == 0){
       echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" colspan=\"5\">
             There are no Messages Found In databases .. </td></tr>";
    }
    echo "</table></center><br>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Add message</b></center><br>";
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">";
    echo "<form action=\"portal_admin.php\" method=\"post\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Title:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"add_title\" value=\"\" size=\"50\" maxlength=\"100\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Content:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea name=\"add_content\" rows=\"15\" wrap=\"virtual\" cols=\"60\"></textarea></td></tr>";
    $now = time();
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Expiration:</b></td> <td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"add_expire\">"
	."<option value=\"86400\" >1 Day</option>"
	."<option value=\"172800\" >2 Days</option>"
	."<option value=\"432000\" >5 Days</option>"
	."<option value=\"1296000\" >15 Days</option>"
	."<option value=\"2592000\" >30 Days</option>"
	."<option value=\"0\" >Unlimited</option>"
	."</select></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Active?</b></td> <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"add_active\" value=\"1\" checked>Yes "
	."<input type=\"radio\" name=\"add_active\" value=\"0\" >No</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Who can View This?</b> <td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"add_view\">"
	."<option value=\"1\" >All Visitors</option>"
	."<option value=\"2\" >Staff Users Only</option>"
	."<option value=\"3\" >Registered Users Only</option>"
	."<option value=\"4\" >Administrators Only</option>"
	."</select></td></tr><tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\" align=\"center\">"
	."<input type=\"hidden\" name=\"op\" value=\"addmsg\">"
	."<input type=\"hidden\" name=\"add_mdate\" value=\"$now\">"
	."<input type=\"submit\" value=\"Add message\">"
	."</td></tr></table></form>";
    CloseTable();
    include ("portal_footer.php");
}

function editmsg($mid) {
    global $status, $altbg1, $altbg2, $prefix, $db, $bordercolor, $borderwidth, $tablespace;
    include ("portal_header.php");
    
    GraphicAdmin();
    OpenTable();
    echo "<center><b>Messages Administration</b></center>";
    CloseTable();
    echo "<br>";
    $result = $db->query("select title, content, date, expire, active, view from ".$prefix."_message WHERE mid='$mid'");
    list($title, $content, $mdate, $expire, $active, $view) = $db->fetch_row($result);
    OpenTable();
    echo "<center><b>Edit message</b></center>";
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">";
    if ($active == 1) {
	$asel1 = "checked";
	$asel2 = "";
    } elseif ($active == 0) {
	$asel1 = "";
	$asel2 = "checked";
    }
    if ($view == 1) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
    } elseif ($view == 2) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
	$sel4 = "";
    } elseif ($view == 3) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
	$sel4 = "";
    } elseif ($view == 4) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "selected";
    }
    if ($expire == 86400) {
	$esel1 = "selected";
	$esel2 = "";
	$esel3 = "";
	$esel4 = "";
	$esel5 = "";
	$esel6 = "";
    } elseif ($expire == 172800) {
	$esel1 = "";
	$esel2 = "selected";
	$esel3 = "";
	$esel4 = "";
	$esel5 = "";
	$esel6 = "";
    } elseif ($expire == 432000) {
	$esel1 = "";
	$esel2 = "";
	$esel3 = "selected";
	$esel4 = "";
	$esel5 = "";
	$esel6 = "";
    } elseif ($expire == 1296000) {
	$esel1 = "";
	$esel2 = "";
	$esel3 = "";
	$esel4 = "selected";
	$esel5 = "";
	$esel6 = "";
    } elseif ($expire == 2592000) {
	$esel1 = "";
	$esel2 = "";
	$esel3 = "";
	$esel4 = "";
	$esel5 = "selected";
	$esel6 = "";
    } elseif ($expire == 0) {
	$esel1 = "";
	$esel2 = "";
	$esel3 = "";
	$esel4 = "";
	$esel5 = "";
	$esel6 = "selected";
    }
    echo "<form action=\"portal_admin.php\" method=\"post\">"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Title:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\"></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Content:</b></td>"
	."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea name=\"content\" rows=\"15\" wrap=\"virtual\" cols=\"60\">$content</textarea></td></tr>";

    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Expiration:</b></td> <td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"expire\">"
	."<option name=\"expire\" value=\"86400\" $esel1>1 Day</option>"
	."<option name=\"expire\" value=\"172800\" $esel2>2 Days</option>"
	."<option name=\"expire\" value=\"432000\" $esel3>5 Days</option>"
	."<option name=\"expire\" value=\"1296000\" $esel4>15 Days</option>"
	."<option name=\"expire\" value=\"2592000\" $esel5>30 Days</option>"
	."<option name=\"expire\" value=\"0\" $esel6>Unlimited</option>"
	."</select></td></tr>"
	."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Active?</b><td bgcolor=\"$altbg2\" class=\"tablerow\"> <input type=\"radio\" name=\"active\" value=\"1\" $asel1>Yes"
	."<input type=\"radio\" name=\"active\" value=\"0\" $asel2>No</td></tr>";
    if ($active == 1) {
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Change start date to today?</b></td>"
    ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"chng_date\" value=\"1\">Yes"
    ."<input type=\"radio\" name=\"chng_date\" value=\"0\" checked>No";
    } elseif ($active == 0) {
	echo "<br><font class=\"tiny\">(If you Active this Message now, the start date will be today)</font>"
    ."<input type=\"hidden\" name=\"chng_date\" value=\"1\">";
    }
    echo "</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Who can View This?</b><td bgcolor=\"$altbg2\" class=\"tablerow\"> <select name=\"view\">"
	."<option name=\"view\" value=\"1\" $sel1>All Visitors</option>"
	."<option name=\"view\" value=\"2\" $sel2>Staff Users Only</option>"
	."<option name=\"view\" value=\"3\" $sel3>Registered Users Only</option>"
	."<option name=\"view\" value=\"4\" $sel4>Administrators Only</option>"
	."</select></td></tr><tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\" align=\"center\">"
	."<input type=\"hidden\" name=\"mdate\" value=\"$mdate\">"
	."<input type=\"hidden\" name=\"mid\" value=\"$mid\">"
	."<input type=\"hidden\" name=\"op\" value=\"savemsg\">"
	."<input type=\"submit\" value=\"Save Changes\">"
	."</td></tr></table></form>";
    CloseTable();
    include ("portal_footer.php");
}

function savemsg($mid, $title, $content, $mdate, $expire, $active, $view, $chng_date) {
    global $prefix, $db;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    if ($chng_date == 1) {
	$newdate = time();
    } elseif ($chng_date == 0) {
	$newdate = $mdate;
    }
    $result = $db->query("update ".$prefix."_message set title='$title', content='$content', date='$newdate', expire='$expire', active='$active', view='$view' WHERE mid='$mid'");
    Header("Location: portal_admin.php?op=messages");
}

function addmsg($add_title, $add_content, $add_mdate, $add_expire, $add_active, $add_view) {
    global $prefix, $db;
    $title = stripslashes(FixQuotes($add_title));
    $content = stripslashes(FixQuotes($add_content));
    $result = $db->query("insert into ".$prefix."_message values (NULL, '$add_title', '$add_content', '$add_mdate', '$add_expire', '$add_active', '$add_view')");
    if (!$result) {
	exit();
    }
    Header("Location: portal_admin.php?op=messages");
}

function deletemsg($mid, $ok=0) {
    global $prefix, $db;
    if($ok) {
	$result = $db->query("delete from ".$prefix."_message where mid=$mid");
	if (!$result) {
	    return;
	}
	Header("Location: portal_admin.php?op=messages");
    } else {
	include("portal_header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><b>Messages Administration</b></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center>Are you sure you want to remove this message ?";
	echo "<br><br>[ <a href=\"portal_admin.php?op=messages\">No</a> | <a href=\"portal_admin.php?op=deletemsg&amp;mid=$mid&amp;ok=1\">Yes</a> ]</center>";
	CloseTable();
	include("portal_footer.php");
    }
}

switch ($op){

    case "messages":
    messages();
    break;

    case "editmsg":
    editmsg($mid, $title, $content, $mdate, $expire, $active, $view, $chng_date);
    break;

    case "addmsg":
    addmsg($add_title, $add_content, $add_mdate, $add_expire, $add_active, $add_view);
    break;

    case "deletemsg":
    deletemsg($mid, $ok);
    break;

    case "savemsg":
    savemsg($mid, $title, $content, $mdate, $expire, $active, $view, $chng_date);
    break;

}

} else {
    echo "Access Denied";
}

?>
