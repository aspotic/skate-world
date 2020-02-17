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

$_SERVER['PHP_SELF'] = "modules.php";
require_once("./portal_main.php");
$sql = "SELECT main_module from ".$prefix."_main";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$modulename = $row['main_module'];
$home = 1;
if(empty($copyright)) {
die("you are not authorized to play with the codes");
}
if (!isset($mop)) { $mop="modload"; }
if (!isset($mod_file)) { $mod_file="index"; }
if (ereg("\.\.",$modulename) || ereg("\.\.",$modulefile) || ereg("\.\.",$mod_file) || ereg("\.\.",$mop)) {
}
    $modpath = "modules/$modulename/$mod_file.php";
    if (file_exists($modpath)) {
	include($modpath);
    } else {
	$index = 1;
    include("portal_header.php");
	OpenTable();
	if ($status == "Administrator" || $status == "Super Administrator") {
    echo "<center><font class=\"\"><b>There is a big problem here: we do not have a Homepage!!!</b></font><br><br>[ <a href=\"portal_admin.php?op=modules\">Add a Module in your Home</a> ]</center>";
	} else {
    echo "<center>There is a problem right now on the Homepage. Please check back later.</center>";
	}
	CloseTable();
	include("portal_footer.php");
 }
?>
