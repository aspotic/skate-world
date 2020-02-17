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

if (eregi("block-Old_Articles.php", $PHP_SELF)) {
    Header("Location: portal.php");
    die();
}

global $locale, $oldnum, $storynum, $storyhome, $cookie, $categories, $cat, $prefix, $new_topic;

    if ($categories == 1) {
    	$querys = "where catid='$cat'";
    } else {
    	$querys = "";
    }
	if ($new_topic != 0) {
	    $querys .= " AND topic='$new_topic'";
	}
    if ($categories == 1) {
   	$querylang = "where catid='$cat'";
    }

$storynum = $storyhome;
$boxstuff = " <A name= \"scrollingCode\"></A>
<MARQUEE behavior= \"scroll\" align= \"center\" direction= \"up\" height=\"120\" scrollamount= \"2\" scrolldelay= \"80\" onmouseover='this.stop()' onmouseout='this.start()'>
<table border=\"0\" width=\"100%\">";
$boxTitle = "Past Articles";
$result = mysql_query("select storyid, title, time, comments from ".$prefix."_stories $querys order by time desc limit $storynum, $oldnum");
$vari = 0;

$r_options = "";
if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }

while(list($storyid, $title, $time, $comments) = mysql_fetch_row($result)) {
    $see = 1;
    setlocale ("LC_TIME", "$locale");
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime2);
    $datetime2 = strftime("%A, %B %d", mktime($datetime2[4],$datetime2[5],$datetime2[6],$datetime2[2],$datetime2[3],$datetime2[1]));
    $datetime2 = ucfirst($datetime2);
    if($time2==$datetime2) {
    $boxstuff .= "<tr><td valign=\"top\"><strong><big>&middot;</big></strong></td><td> <a href=\"modules.php?modulename=News&modulefile=article&storyid=$storyid$r_options\">$title</a> ($comments)</td></tr>\n";
    }else{
    if($a=="") {
    $boxstuff .= "<tr><td colspan=\"2\"><b>$datetime2</b></td></tr><tr><td valign=\"top\"><strong><big>&middot;</big></strong></td><td> <a href=\"modules.php?modulename=News&modulefile=article&storyid=$storyid$r_options\">$title</a> ($comments)</td></tr>\n";
    $time2 = $datetime2;
    $a = 1;
	}else{
    $boxstuff .= "<tr><td colspan=\"2\"><b>$datetime2</b></td></tr><tr><td valign=\"top\"><strong><big>&middot;</big></strong></td><td><a href=\"modules.php?modulename=News&modulefile=article&storyid=$storyid$r_options\">$title</a> ($comments)</td></tr>\n";
    $time2 = $datetime2;
	}
    }
    $vari++;
    if ($vari==$oldnum) {
	if (isset($cookie[3])) {
    $storynum = $cookie[3];
	} else {
    $storynum = $storyhome;
	}
	$min = $oldnum + $storynum;
	$dummy = 1;
    }
}

if ($dummy == 1) {
    $boxstuff .= "</table>";
}

if ($see == 1) {
    $content = $boxstuff;
}

?>
