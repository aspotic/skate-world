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

if (eregi("portal_header.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

require_once("portal_body.php");
require_once("portal_main.php");

$header = 1;
function head() {
    global $sitename, $banners, $full_url, $artpage, $show_ticker, $topic, $xmbuser, $langfile, $imgdir, $prefix, $bordercolor, $borderwidth, $tablespace, $catbgcode, $altbg2, $altbg1, $cattext, $bgcode, $pagetitle, $portal_images;
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
    echo "<html>\n";
    echo "<head>\n";
    echo "<title>$pagetitle</title>\n";
    if(file_exists("".$full_url."".$portal_images."/favicon.ico")) {
	echo "<link REL=\"shortcut icon\" HREF=\"".$full_url."".$portal_images."/favicon.ico\" TYPE=\"image/x-icon\">\n";
    }
    include("includes/my_header.php");
    if($show_ticker == "on") {
    include_once("includes/ticker.php");
    }
    echo "\n\n\n</head>\n\n";
    themeheader();
}

head();
global $home, $displaynews;
if ($home == 1) {
    blocks(Center);
    message_box();
    if($displaynews == "on") {
    newsblock();
    }
}
?>
