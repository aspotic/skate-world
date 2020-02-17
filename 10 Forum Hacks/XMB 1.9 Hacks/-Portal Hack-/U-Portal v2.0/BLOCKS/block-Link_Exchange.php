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

if (eregi("block-Link_Exchange.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}
global $portal_images;

$usemarquee = 1;
$scrolldirection = "Up";

// Link Exchange
$content .= "<center><br><img src=$portal_images/exchange.gif><br><img src=$portal_images/space.gif></center>";
$content .= "<Marquee Behavior=\"Scroll\" Direction=\"$scrolldirection\" Height=\"80\" ScrollAmount=\"2\" ScrollDelay=\"130\" onMouseOver=\"this.stop()\" onMouseOut=\"this.start()\"><br>";
$content .= "<center><a href=\"$full_url\" target=\"_blank\" ><img src=$portal_images/Web_dev.jpg border=0></a>";
$content .="</center></Marquee><br><br>";
// Link to Us
$content .= "<center><b>.:Link to Us:.</b><br><textarea rows=9 name=x cols=20 ><a href=\"$full_url\" target=\"_blank\"><img border=0 src=\"$full_url$portal_images/Web_dev.jpg\" alt=\"$bbname\" width=88 height=31></a></textarea></center><br>";

// Your Banner
$content .= "<center><a href=\"$full_url\"><img src=\"$portal_images/Web_dev.jpg\" border=0 alt=\"$bbname\"></a><br>";

// Set to your default home page!
$content .= "<a style='cursor:hand' HREF onClick='this.style.behavior=\"url(#default#homepage)\";this.setHomePage(\"$full_url\");'><img src=\"$imgdir/site.gif\" border=0 alt=\"Set to your default home page!\"></a><br>";

// Email to webmaster
$content .= "<a href=\"contact.php\" target=\"_blank\"><img src=\"$imgdir/email.gif\" border=0></a></center><br>";

// END LINK EXCHANGE

?>
