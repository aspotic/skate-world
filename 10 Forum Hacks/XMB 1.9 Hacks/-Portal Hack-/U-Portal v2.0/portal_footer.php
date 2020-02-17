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

if (eregi("footer.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}
$footer = 1;
function footmsg() {
    global $copyright;
    echo "<font class=\"smalltxt\">\n";
    echo "$copyright\n</font>\n";
}

function foot() {
    global $prefix, $db, $index, $xmbuser, $storynum, $status, $home, $module, $modulename, $portal_online;
    if ($home == 1) {
	blocks(Down);
    }
    themefooter();
    if($portal_online == "on"){
    online();
    }
   	echo "<br><center>";
    footmsg();
    echo "</center>";
    echo "</body>\n"
	."</html>\n";
}
foot();
?>
