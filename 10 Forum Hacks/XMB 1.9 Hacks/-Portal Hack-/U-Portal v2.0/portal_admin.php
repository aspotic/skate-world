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

require_once("portal_main.php");
if(!isset($op)) { $op = "adminMain"; }
eval ("\$header = \"".template("header")."\";");
echo $header;

if (!$xmbuser || !$xmbpw) {
	unset($xmbuser, $xmbpw, $status);
}

if ($status != "Super Administrator" && $status != "Administrator") {
	eval("\$notadmin = \"".template("error_nologinsession")."\";");
	echo $notadmin;
	end_time();
	eval("\$footer = \"".template("footer")."\";");
	echo $footer;
	exit;
}

// BEGIN LOG

if (getenv(HTTP_CLIENT_IP)) {
	$ip = getenv(HTTP_CLIENT_IP);
} elseif (getenv(HTTP_X_FORWARDED_FOR)) {
	$ip = getenv(HTTP_X_FORWARDED_FOR);
} else {
	$ip = getenv(REMOTE_ADDR);
}

$time = time();

$string = "$xmbuser|#||#|$ip|#||#|$time|#||#|$_SERVER[REQUEST_URI]\n";

@chmod('./cplogfile.php', 0777);

$filehandle = @fopen('./cplogfile.php','a');
@flock($filehandle, 2);
@fwrite($filehandle, $string);
@fclose($filehandle);
@chmod('./cplogfile.php', 0766);

// END LOG

function deleteNotice($id, $table, $op_back) {

    mysql_query("DELETE FROM $table WHERE id = '$id'");
    Header("Location: portal_admin.php?op=$op_back");
}

function adminmenu($url, $title, $image) {
    global $counter, $admingraphic, $altbg2;
	$image = "./portal_admin/images/$image";
    if ($admingraphic == 1) {
	$img = "<img src=\"$image\" border=\"0\" alt=\"$title\" title=\"$title\"></a><br>";
	$close = "";
    } else {
	$image = "";
	$close = "</a>";
    }
    echo "<td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\"><font class=\"nav\"><a href=\"$url\">$img<b>$title</b>$close<br><br></font></td>";
    if ($counter == 5) {
	echo "</tr><tr>";
	$counter = 0;
    } else {
	$counter++;
    }
}

function GraphicAdmin() {
    global $admingraphic, $prefix, $tablespace, $borderwidth;
    $newsubs = mysql_num_rows(mysql_query("SELECT qid FROM ".$prefix."_queue"));
    OpenTable();
    echo "<center><a href=\"portal_admin.php\"><font class='subject'>Administration Menu</font></a>";
    echo "<br><br>";
    echo"<table border=\"0\" width=\"100%\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\"><tr>";
    adminmenu("portal_admin.php?op=adminStory", "Add Story", "stories.gif");
    adminmenu("portal_admin.php?op=BlocksAdmin", "Blocks", "blocks.gif");
    adminmenu("portal_admin.php?op=messages", "Messages", "messages.gif");
    adminmenu("portal_admin.php?op=modules", "Modules", "modules.gif");
    adminmenu("portal_admin.php?op=reviews", "Reviews", "reviews.gif");
    adminmenu("portal_admin.php?op=sections", "Sections", "sections.gif");
    adminmenu("portal_admin.php?op=Configure", "Preferences", "preferences.gif");
    adminmenu("portal_admin.php?op=submissions", "Submissions", "submissions.gif");
    adminmenu("portal_admin.php?op=create", "Surveys/Polls", "surveys.gif");
    adminmenu("portal_admin.php?op=topicsmanager", "Topics", "topics.gif");
    adminmenu("portal_admin.php?op=BannersAdmin", "Banners", "banners.gif");
    adminmenu("misc.php?action=logout", "Logout / Exit", "logout.gif");
    echo"</tr></table></center>";
    CloseTable();
    echo "<br>";
}

function adminMain() {
    global $status, $aid, $prefix, $modulefile, $bbname, $borderwidth, $altbg1, $altbg2, $tablespace, $bordercolor;
    include ("portal_header.php");
    $dummy = 0;
    $Today = getdate();
    $month = $Today['month'];
    $mday = $Today['mday'];
    $year = $Today['year'];
    $pmonth = $Today['month'];
    $pmday = $Today['mday'];
    $pmday = $mday-1;
    $pyear = $Today['year'];
    if ($pmonth=="January") { $pmonth=1; } else
    if ($pmonth=="February") { $pmonth=2; } else
    if ($pmonth=="March") { $pmonth=3; } else
    if ($pmonth=="April") { $pmonth=4; } else
    if ($pmonth=="May") { $pmonth=5; } else
    if ($pmonth=="June") { $pmonth=6; } else
    if ($pmonth=="July") { $pmonth=7; } else
    if ($pmonth=="August") { $pmonth=8; } else
    if ($pmonth=="September") { $pmonth=9; } else
    if ($pmonth=="October") { $pmonth=10; } else
    if ($pmonth=="November") { $pmonth=11; } else
    if ($pmonth=="December") { $pmonth=12; };
    $test = mktime (0,0,0,$pmonth,$pmday,$pyear,1);
    $curDate2 = "%".$month[0].$month[1].$month[2]."%".$mday."%".$year."%";
    $preday = strftime ("%d",$test);
    $premonth = strftime ("%B",$test);
    $preyear = strftime ("%Y",$test);
    $curDateP = "%".$premonth[0].$premonth[1].$premonth[2]."%".$preday."%".$preyear."%";
    GraphicAdmin();
    $aid = $xmbuser;
    $sql = "SELECT main_module from ".$prefix."_main";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $main_module = $row['main_module'];
    OpenTable();
    echo "<center><b>$bbname: Default Homepage Module</b><br><br>"
	."Module Loaded in the Homepage: <b>$main_module</b><br>[ <a href=\"portal_admin.php?op=modules\">Change</a> ]</center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Programmed Articles</b></center><br>";
    $count = 0;
    $sql = "SELECT anid, aid, title, time FROM ".$prefix."_autonews ORDER BY time ASC";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
	$anid = $row['anid'];
	$said = $row['aid'];
	$title = $row['title'];
	$time = $row['time'];
	if ($anid != "") {
	    if ($count == 0) {
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\">";
        echo "<tr><td nowrap bgcolor=\"$altbg2\" class=\"tablerow\"><b>Options</b></td>";
        echo "<td nowrap bgcolor=\"$altbg2\" class=\"tablerow\"><b>Title</b></td>";
        echo "<td nowrap bgcolor=\"$altbg2\" class=\"tablerow\"><b>Display Date</b></td></tr>";
        $count = 1;
	    }
	    $time = ereg_replace(" ", "@", $time);
	    if ($status == "Super Administrator" || $status == "Administrator") {
		if (($aid == $said) OR ($status == "Super Administrator" || $status == "Administrator")) {
         echo "<tr><td nowrap bgcolor=\"$altbg1\" class=\"tablerow\">&nbsp;(<a href=\"portal_admin.php?op=autoEdit&amp;anid=$anid\">Edit</a>-<a href=\"portal_admin.php?op=autoDelete&amp;anid=$anid\">Delete</a>)&nbsp;</td><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"100%\">&nbsp;$title&nbsp;</td><td nowrap bgcolor=\"$altbg1\" class=\"tablerow\">&nbsp;$time&nbsp;</td></tr>";
		} else {
        echo "<tr><td nowrap bgcolor=\"$altbg1\" class=\"tablerow\">&nbsp;(---------)&nbsp;</td><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"100%\">&nbsp;$title&nbsp;</td><td align=\"center\">&nbsp;</td><td bgcolor=\"$altbg1\" class=\"tablerow\">&nbsp;$time&nbsp;</td></tr>";
		}
	    } else {
		echo "<tr><td nowrap bgcolor=\"$altbg1\" class=\"tablerow\" width=\"100%\">&nbsp;$title&nbsp;</td><td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\">&nbsp;</td><td bgcolor=\"$altbg1\" class=\"tablerow\">&nbsp;$time&nbsp;</td></tr>";
	    }
	}
    }
    if (($anid == "") AND ($count == 0)) {
	echo "<center><i>There are no programmed articles</i></center>";
    }
    if ($count == 1) {
        echo "</table>";
    }
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>Last 20 Articles</b></center><br>";
    $sql = "SELECT storyid, aid, title, time, topic, informant FROM ".$prefix."_stories ORDER BY time DESC LIMIT 0,20";
    $result = mysql_query($sql);
    echo "<center><table border=\"0\" width=\"100%\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\">";
    echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"left\" width=\"5%\"><b>Id</b></td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"left\" width=\"55%\"><b>Title</b></td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" width=\"15%\"><b>Category</b></td>";
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" width=\"15%\"><b>Options</b></td></tr>";
    while ($row = mysql_fetch_array($result)) {
	$storyid = $row['storyid'];
	$said = $row['aid'];
	$title = $row['title'];
	$time = $row['time'];
	$topic = $row['topic'];
	$informant = $row['informant'];
	$sql = "SELECT topicname FROM ".$prefix."_topics WHERE topicid='$topic'";
	$ta = mysql_query($sql);
	$row = mysql_fetch_array($ta);
	$topicname = $row['topicname'];

	formatTimestamp($time);
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"left\"><b>$storyid</b>"
	    ."</td><td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"left\"><a href=\"modules.php?modulename=News&file=article&storyid=$storyid\">$title</a>"
	    ."</td>"
	    ."</td><td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\">$topicname";
	if ($status == "Super Administrator" || $status == "Administrator") {
	    if (($aid == $said) OR ($status == "Super Administrator" || $status == "Administrator")) {
		echo "</td><td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\" nowrap>(<a href=\"portal_admin.php?op=EditStory&storyid=$storyid\">Edit</a>-<a href=\"portal_admin.php?op=RemoveStory&storyid=$storyid\">Delete</a>)"
		    ."</td></tr>";
	    } else {
		echo "</td><td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\" nowrap><font class=\"nav\"><i>(---------)</i></font>"
		    ."</td></tr>";
	    }
	} else {
	    echo "</td></tr>";
	}
    }
    echo "</table>";
    if ($status == "Super Administrator" || $status == "Administrator") {
	echo "<center>"
	    ."<form action=\"portal_admin.php\" method=\"post\">"
	    ."Story ID: <input type=\"text\" NAME=\"storyid\" SIZE=\"10\">"
	    ."<select name=\"op\">"
	    ."<option value=\"EditStory\" SELECTED>Edit</option>"
	    ."<option value=\"RemoveStory\">Delete</option>"
	    ."</select>"
	    ."<input type=\"submit\" value=\"Go!\">"
	    ."</form></center>";
    }
    CloseTable();
    $sql = "SELECT pollID, pollTitle FROM ".$prefix."_poll_desc WHERE artid='0' ORDER BY pollID DESC LIMIT 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $pollID = $row['pollID'];
    $pollTitle = $row['pollTitle'];
    echo "<br>";
    OpenTable();
    echo "<center><b>Current Poll:</b> $pollTitle [ <a href=\"portal_admin.php?op=polledit&pollID=$pollID\">Edit</a> | <a href=\"portal_admin.php?op=create\">Add</a> ]</center>";
    CloseTable();
    include ("portal_footer.php");
}

switch($op) {
    case "adminStory":
    case "submissions":
    case "YesDelCategory":
    case "subdelete":
    case "DelCategory":
    case "NoMoveCategory":
    case "EditCategory":
    case "SaveEditCategory":
    case "AddCategory":
    case "SaveCategory":
    case "DisplayStory":
    case "PreviewAgain":
    case "PostStory":
    case "EditStory":
    case "RemoveStory":
    case "ChangeStory":
    case "DeleteStory":
    case "PreviewAdminStory":
    case "PostAdminStory":
    case "autoDelete":
    case "autoEdit":
    case "autoSaveEdit":
    include("portal_admin/modules/stories.php");
    break;
    
    case "BlocksAdmin":
    case "BlocksAdd":
    case "BlocksEdit":
    case "BlocksEditSave":
    case "BlocksDelete":
    case "BlockOrder":
    case "fixweight":
    case "block_show":
    include("portal_admin/modules/blocks.php");
    break;
    
     case "messages":
     case "addmsg":
     case "editmsg":
     case "deletemsg":
     case "savemsg":
     include("portal_admin/modules/messages.php");
     break;
     
    case "modules":
    case "module_status":
    case "module_edit":
    case "module_edit_save":
    case "home_module":
     include("portal_admin/modules/modules.php");
     break;
     
     case "reviews":
     case "mod_main":
     case "add_review":
     include("portal_admin/modules/reviews.php");
     break;
     
     case "sections":
     case "sectionedit":
     case "sectionmake":
     case "sectiondelete":
     case "sectionchange":
     case "secarticleadd":
     case "secartedit":
     case "secartchange":
     case "secartdelete":
     include("portal_admin/modules/sections.php");
     break;
     
     case "Configure":
     case "ConfigSave":
     include("portal_admin/modules/settings.php");
     break;

     case "create":
     case "createPosted":
     case "ChangePoll":
     case "remove":
     case "removePosted":
     case "polledit":
     case "savepoll":
     case "polledit_select":
     include("portal_admin/modules/polls.php");
     break;
     
     case "topicsmanager":
     case "relatedsave":
     case "relatededit":
     case "relateddelete":
     case "topicedit":
     case "topicmake":
     case "topicdelete":
     case "topicchange":
     include("portal_admin/modules/topics.php");
     break;
     
     case "BannersAdmin":
     case "BannersAdd":
     case "BannerAddClient":
     case "BannerDelete":
     case "BannerEdit":
     case "BannerChange":
     case "BannerClientDelete":
     case "BannerClientEdit":
     case "BannerClientChange":
     case "BannerStatus":
     include("portal_admin/modules/banners.php");
     break;
      
	case "deleteNotice":
	deleteNotice($id, $table, $op_back);
	break;
	case "GraphicAdmin":
    GraphicAdmin();
    break;
	case "adminMain":
	adminMain();
	break;

}

end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>
