<?PHP

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

function removeSubComments($tid) {
    global $prefix;
    $result = mysql_query("select tid from ".$prefix."_comments where pid='$tid'");
    $numrows = mysql_num_rows($result);
    if($numrows>0) {
	while(list($stid) = mysql_fetch_row($result)) {
    removeSubComments($stid);
    mysql_query("delete from ".$prefix."_comments where tid='$stid'");
        }
    }
    mysql_query("delete from ".$prefix."_comments where tid='$tid'");
}

function removeComment ($tid, $storyid, $ok=0) {
    global $prefix;
    if($ok) {
	$result = mysql_query("select date from ".$prefix."_comments where pid='$tid'");
	$numresults = mysql_num_rows($result);
    mysql_query("update ".$prefix."_stories set comments=comments-1-'$numresults' where storyid='$storyid'");
    removeSubComments($tid);
    Header("Location: modules.php?modulename=News&modulefile=article&storyid=$storyid");
    }
	include("portal_header.php");
    GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"subject\"><b>Delete Comments</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
    echo "<center>Are you sure you want to delete selected Comment and all its replies?";
    echo "<br><br>[ <a href=\"javascript:history.go(-1)\">No</a> | <a href=\"portal_admin.php?op=RemoveComment&tid=$tid&storyid=$storyid&ok=1\">Yes</a> ]</center>";
	CloseTable();
    include("portal_footer.php");

}
switch ($op) {

    case "RemoveComment":
    removeComment ($tid, $storyid, $ok);
    break;

    case "removeSubComments":
    removeSubComments($tid);
    break;

}

} else {
    echo "Access Denied";
}
?>
