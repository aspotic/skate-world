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

if (eregi("block-Forum-menu.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}
global $SETTINGS, $lang_textsearch, $lang_textfaq, $lang_textmemberlist, $lang_navtodaysposts, $lang_navstats, $banners, $mainfor;
        $content = "<strong><big>&middot;</big></strong>&nbsp;<a href=\"index.php?action=showindex\">Forums Page</a><br>";
	if($SETTINGS['searchstatus'] == "on") {
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"misc.php?action=search\">$lang_textsearch</a><br>";
	}
	if($SETTINGS['faqstatus'] == "on") {
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"faq.php\">$lang_textfaq</a><br>";
	}
	if($SETTINGS['memliststatus'] == "on") {
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"misc.php?action=list\">$lang_textmemberlist</a><br>";
	}
	if($SETTINGS['todaysposts'] == "on") {
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"today.php\">$lang_navtodaysposts</a><br>";
	}
	if($SETTINGS['stats'] == "on") {
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"stats.php?action=view\">$lang_navstats</a><br>";
	}
    if($banners) {
        $content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"portal_banners.php?op=login\">Banners Login</a><br>";
    }

?>
