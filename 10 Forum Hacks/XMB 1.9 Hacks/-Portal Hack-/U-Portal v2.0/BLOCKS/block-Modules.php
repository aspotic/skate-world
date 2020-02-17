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

if (eregi("block-Modules.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

global $prefix, $status;

    $sql = "SELECT main_module FROM ".$prefix."_main";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $main_module = $row[main_module];

    $sql = "SELECT title FROM ".$prefix."_modules";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
	$title = $row[title];
	$a = 0;
	$handle=opendir('modules');
	while ($file = readdir($handle)) {
   if ($file == $title) {
	$a = 1;
	    }
	}
	closedir($handle);
	if ($a == 0) {
	    mysql_query("DELETE FROM ".$prefix."_modules WHERE title='$title'");
	}
    }

    $content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"portal.php\">Home</a><br>\n";
    $sql = "SELECT title, custom_title, view FROM ".$prefix."_modules WHERE active='1' AND title!='$def_module' AND inmenu='1' ORDER BY custom_title ASC";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
	$m_title = $row[title];
	$custom_title = $row[custom_title];
	$view = $row[view];
	$m_title2 = ereg_replace("_", " ", $m_title);
	if ($custom_title != "") {
	    $m_title2 = $custom_title;
	}
	if ($m_title != $main_module) {
	    if ($status == "Super Administrator" || $status == "Administrator" AND $view == 2 OR $view != 2) {
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?modulename=$m_title\">$m_title2</a><br>\n";
	    }
	}
    }
    
    if ($status == "Super Administrator" || $status == "Administrator") {
	$handle=opendir('modules');
	while ($modulefile = readdir($handle)) {
	    if ( (!ereg("[.]",$modulefile)) ) {
		$modlist .= "$modulefile ";
	    }
	}
	closedir($handle);
	$modlist = explode(" ", $modlist);
	sort($modlist);
	for ($i=0; $i < sizeof($modlist); $i++) {
	    if($modlist[$i] != "") {
		$sql = "SELECT mid FROM ".$prefix."_modules WHERE title='$modlist[$i]'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$mid = $row[mid];
		$mod_uname = ereg_replace("_", " ", $modlist[$i]);
		if ($mid == "") {
		    mysql_query("INSERT INTO ".$prefix."_modules VALUES (NULL, '$modlist[$i]', '$mod_uname', '0', '0', '1')");
		}
	    }
	}
	$content .= "<br><center><b>Invisible Modules</b><br>";
	$content .= "<font class=\"smalltxt\">(Active but invisible link)</font></center><br>";
	$sql = "SELECT title, custom_title FROM ".$prefix."_modules WHERE active='1' AND inmenu='0' ORDER BY title ASC";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
	    $mn_title = $row[title];
	    $custom_title = $row[custom_title];
	    $mn_title2 = ereg_replace("_", " ", $mn_title);
	    if ($custom_title != "") {
		$mn_title2 = $custom_title;
	    }
	    if ($mn_title2 != "") {
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?modulename=$mn_title\">$mn_title2</a><br>\n";
		$dummy = 1;
	    } else {
		$a = 1;
	    }
	}
	if ($a == 1 AND $dummy != 1) {
    	    $content .= "<strong><big>&middot;</big></strong>&nbsp;<i>None</i><br>\n";
	}
	$content .= "<br><center><b>Inactive Modules</b><br>";
	$content .= "<font class=\"smalltxt\">(for Admin tests)</font></center><br>";
	$sql = "SELECT title, custom_title FROM ".$prefix."_modules WHERE active='0' ORDER BY title ASC";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
	    $mn_title = $row[title];
	    $custom_title = $row[custom_title];
	    $mn_title2 = ereg_replace("_", " ", $mn_title);
	    if ($custom_title != "") {
		$mn_title2 = $custom_title;
	    }
	    if ($mn_title2 != "") {
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?modulename=$mn_title\">$mn_title2</a><br>\n";
		$dummy = 1;
	    } else {
		$a = 1;
	    }
	}
	if ($a == 1 AND $dummy != 1) {
    	    $content .= "<strong><big>&middot;</big></strong>&nbsp;<i>None</i><br>\n";
	}
    }

?>
