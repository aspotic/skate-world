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

if (eregi("block-Sections_Lastpost.php",$PHP_SELF)) {
	    Header("Location: portal.php");
    	die();
	}

  
global $prefix;

$content = ""; 
$content .= "<A name= \"scrollingCode\"></A><MARQUEE behavior= \"scroll\" align= \"center\" direction= \"up\" height=\"120\" scrollamount= \"2\" scrolldelay= \"90\" onmouseover='this.stop()' onmouseout='this.start()'>
<table cellpadding=\"0\" cellspacing=\"0\">"; 
$content .= "<tr valign=\"top\"><td>";
$result = mysql_query("SELECT artid, title FROM ".$prefix."_seccont ORDER BY artid desc LIMIT 30");

while($mypost = mysql_fetch_array($result)) {
$lastpost = "$mypost[title]";
$content .= "<font size=\"2\"><strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=Sections&sop=viewarticle&amp;artid=$mypost[artid]\">";
$content .= "$lastpost</a>";
$content .= "</font><br>";
 }
$content .= "</td></tr>";
$content .= "</table>";

?>
