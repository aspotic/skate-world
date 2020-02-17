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

if (eregi("block-Random_member_moods.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

$portal_rmods = 6;

global $table_members, $smdir;

$query = mysql_query("SELECT * FROM $table_members WHERE mood!='' AND mood!='Not Defined' ORDER BY RAND() LIMIT 0,$portal_rmods");
while ($moodmems = mysql_fetch_array($query)) {
$randomcheck = mysql_num_rows($query);
$moodname = rawurlencode($moodmems['username']);
if(!empty($moodmems[icon2])) {
$md[icon] = "<img src=\"$smdir/$moodmems[icon2]\" />";
}
$content .= "<tr><td class=\"mediumtxt\" bgcolor=\"$altbg2\"><a href=\"member.php?action=viewpro&member=$moodname\">$moodmems[username]</a></td><td class=\"mediumtxt\" bgcolor=\"$altbg1\">$md[icon] $moodmems[mood]</td></tr>\n";
}
$blockfiletitle = "$portal_rmods Member Moods";
if($randomcheck == 0){
$content = "<br> There are no Moods Found in database";
}


?>
