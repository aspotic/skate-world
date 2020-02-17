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

function modules() {
    global $prefix, $db, $multilingual, $altbg2, $altbg1, $borderwidth, $tablespace, $bordercolor;
    include ("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"subject\"><b>Modules Administration</b></font></center>";
    CloseTable();
    $handle=opendir('modules');
    while ($file = readdir($handle)) {
	if ( (!ereg("[.]",$file)) ) {
		$modlist .= "$file ";
	}
    }
    closedir($handle);
    $modlist = explode(" ", $modlist);
    sort($modlist);
    for ($i=0; $i < sizeof($modlist); $i++) {
	if($modlist[$i] != "") {
	    $result = $db->query("select mid from ".$prefix."_modules where title='$modlist[$i]'");
	    list ($mid) = $db->fetch_row($result);
	    if ($mid == "") {
		$db->query("insert into ".$prefix."_modules values (NULL, '$modlist[$i]', '$modlist[$i]', '0', '0', '1')");
	    }
	}
    }
    $result = $db->query("select title from ".$prefix."_modules");
    while (list($title) = $db->fetch_row($result)) {
	$a = 0;
	$handle=opendir('modules');
	while ($file = readdir($handle)) {
	    if ($file == $title) {
		$a = 1;
	    }
	}
	closedir($handle);
	if ($a == 0) {
	    $db->query("delete from ".$prefix."_modules where title='$title'");
	}
    }
    echo "<br>";
    OpenTable();
    echo "<br><center><font class=\"category\">Modules and Addons</font><br><br>"
	."<font class=\"tablerow\">See your Modules/Addons current status and change it by Activating or Deactivating them.<br>New Modules copied on the <i>/modules/</i> directory will be automaticaly added with <i>Inactive</i> status to the database when you reload this page.<br>If you want to remove a module just delete it from the <i>/modules/</i> directory, the system will automaticaly update your database to show the changes.</font><br><br>"
	."[ <big><strong>&middot;</strong></big> ] means a module which name and link will not be visible in Modules Block<br><br>"
	."<form action=\"portal_admin.php?op=modules\" method=\"post\">"
    ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" align=\"center\" width=\"99%\"><tr><td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\">"
	."<b>Title</b></td><td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>Custom Title</b></td><td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>Status</b></td><td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>Visible to</b></td><td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>Functions</b></td></tr>";
    $main_m = $db->query("select main_module from ".$prefix."_main");
    list($main_module) = $db->fetch_row($main_m);
    $result = $db->query("select mid, title, custom_title, active, view, inmenu from ".$prefix."_modules order by title ASC");
    while(list($mid, $title, $custom_title, $active, $view, $inmenu) = $db->fetch_row($result)) {
	if ($custom_title == "") {
	    $custom_title = ereg_replace("_"," ",$title);
	    $db->query("update ".$prefix."_modules set custom_title='$custom_title' where mid='$mid'");
	}
	if ($active == 1) {
	    $active = "Active";
	    $change = "Deactivate";
	    $act = 0;
	} else {
	    $active = "<i>Inactive</i>";
	    $change = "Activate";
	    $act = 1;
	}
	if ($custom_title == "") {
	    $custom_title = ereg_replace("_", " ", $title);
	}
	if ($view == 0) {
	    $who_view = "All Visitors";
	} elseif ($view == 1) {
	    $who_view = "Registered Users Only";
	} elseif ($view == 2) {
	    $who_view = "Administrators Only";
	}
	if ($title != $main_module AND $inmenu == 0) {
	    $title = "[ <big><strong>&middot;</strong></big> ] $title";
	}
	if ($title == $main_module) {
	    $title = "<b>$title</b>";
	    $custom_title = "<b>$custom_title</b>";
	    $active = "<b>$active (In Home)</b>";
	    $who_view = "<b>$who_view</b>";
	    $puthome = "<i>Put in Home</i>";
	    $background = "bgcolor=\"$altbg2\"";
	} else {
	    $puthome = "<a href=\"portal_admin.php?op=home_module&mid=$mid\">Put in Home</a>";
	    $background = "bgcolor=\"$altbg1\"";
	}
	echo "<tr><td $background class=\"tablerow\">&nbsp;$title</td><td class=\"tablerow\" align=\"center\" $background>$custom_title</td><td align=\"center\" $background class=\"tablerow\">$active</td><td align=\"center\" $background class=\"tablerow\">$who_view</td><td align=\"center\" $background class=\"tablerow\">[ <a href=\"portal_admin.php?op=module_edit&mid=$mid\">Edit</a> | $puthome ]</td></tr>";
    }
    echo "</table></form><br>";
    CloseTable();
    include ("portal_footer.php");
}

function home_module($mid, $ok=0) {
    global $prefix, $db;
    if ($ok == 0) {
	include ("portal_header.php");
	GraphicAdmin();
	title("Home Configuration");
	OpenTable();
	$result = $db->query("select title from ".$prefix."_modules where mid='$mid'");
	list($new_m) = $db->fetch_row($result);
	$result = $db->query("select main_module from ".$prefix."_main");
	list($old_m) = $db->fetch_row($result);
	echo "<center><b>Default Homepage Module</b><br><br>"
 	   ."Are you sure you want to change your Homepage from <b>$old_m</b> To <b>$new_m</b>?<br><br>"
    	."[ <a href=\"portal_admin.php?op=modules\">No</a> | <a href=\"portal_admin.php?op=home_module&mid=$mid&ok=1\">Yes</a> ]</center>";
	CloseTable();
	include("portal_footer.php");
    } else {
	$result = $db->query("select title from ".$prefix."_modules where mid='$mid'");
	list($title) = $db->fetch_row($result);
	$active = 1;
	$view = 0;
	$result = $db->query("update ".$prefix."_main set main_module='$title'");
	$result = $db->query("update ".$prefix."_modules set active='$active', view='$view' where mid='$mid'");
    Header("Location: portal_admin.php?op=modules");
    }
}

function module_edit($mid) {
    global $prefix, $db, $borderwidth, $tablespace, $bordercolor, $altbg1, $altbg2;
    $main_m = $db->query("select main_module from ".$prefix."_main");
    list($main_module) = $db->fetch_row($main_m);
    $result = $db->query("select title, custom_title, active, view, inmenu from ".$prefix."_modules where mid='$mid'");
    list($title, $custom_title, $newactive, $view, $inmenu) = $db->fetch_row($result);
    include ("portal_header.php");
    GraphicAdmin();
    title("Modules Edit");
    OpenTable();
    if ($view == 0) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
    } elseif ($view == 1) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
    } elseif ($view == 2) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";    
    }
    if ($title == $main_module) {
	$a = " - In Home";
    } else {
	$a = "";
    }
    if ($inmenu == 1) {
	$insel1 = "checked";
	$insel2 = "";
    } elseif ($inmenu == 0) {
	$insel1 = "";
	$insel2 = "checked";
    }
    if ($newactive == 1) {
	$newsel1 = "checked";
	$newsel2 = "";
    } elseif ($newactive == 0) {
	$newsel1 = "";
	$newsel2 = "checked";
    }
    
    echo "<center><b>Change Module Name</b><br>($title$a)</center><br><br>"
	."<form action=\"portal_admin.php\" method=\"post\">"
	."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">"
	."Custom Module Name:</td><td bgcolor=\"$altbg2\" class=\"tablerow\">"
	."<input type=\"text\" name=\"custom_title\" value=\"$custom_title\" size=\"50\"></td></tr>";
    if ($title == $main_module) {
	echo "<input type=\"hidden\" name=\"view\" value=\"0\">"
	    ."<input type=\"hidden\" name=\"inmenu\" value=\"$inmenu\">"
	    ."</table><br><br>";
    } else {
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Who can View This?</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"view\">"
	    ."<option value=\"0\" $sel1>All Visitors</option>"
	    ."<option value=\"1\" $sel2>Registered Users Only</option>"
	    ."<option value=\"2\" $sel3>Administrators Only</option>"
	    ."</select></tr></td>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Visible in Modules Block?</td><td bgcolor=\"$altbg2\" class=\"tablerow\">"
	    ."<input type=\"radio\" name=\"inmenu\" value=\"1\" $insel1> Yes &nbsp;&nbsp; <input type=\"radio\" name=\"inmenu\" value=\"0\" $insel2> No"
     
        ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Activate This Modules?</td><td bgcolor=\"$altbg2\" class=\"tablerow\">"
	    ."<input type=\"radio\" name=\"newactive\" value=\"1\" $newsel1> Yes &nbsp;&nbsp; <input type=\"radio\" name=\"newactive\" value=\"0\" $newsel2> No"

        ."</td></tr></table><br><br>";
    }
    if ($title != $main_module) {
	
    }
    echo "<input type=\"hidden\" name=\"mid\" value=\"$mid\">"
	."<input type=\"hidden\" name=\"op\" value=\"module_edit_save\">"
	."<input type=\"submit\" value=\"Save Changes\">"
	."</form>"
	."<br><br><center>[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]</center>";
    CloseTable();
    include("portal_footer.php");
}

function module_edit_save($mid, $custom_title, $view, $inmenu, $newactive) {
    global $prefix, $db;
    $result = $db->query("update ".$prefix."_modules set custom_title='$custom_title', active= '$newactive', view='$view', inmenu='$inmenu' where mid='$mid'");
    Header("Location: portal_admin.php?op=modules");
}

switch ($op){

    case "modules":
    modules();
    break;

    case "module_edit":
    module_edit($mid);
    break;
    
    case "module_edit_save":
    module_edit_save($mid, $custom_title, $view, $inmenu, $newactive);
    break;

    case "home_module":
    home_module($mid, $ok);
    break;

}

} else {
    echo "Access Denied";
}

?>
