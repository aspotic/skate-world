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

if (eregi("block-Top_members.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

global $table_members;

$portal_topmembers = 6;
$show_avatar = "on";

$memcount = "1";
$query = mysql_query("SELECT * FROM $table_members WHERE status!='Super Administrator' AND status!='Administrator' AND status!='Super Moderator' AND status!='Moderator' ORDER BY postnum DESC LIMIT 0, $portal_topmembers");
while($top_member = mysql_fetch_array($query)) {
$membercheck = mysql_num_rows($query);
if ($top_members_list == "") {
if($top_member[avatar]!= "" AND $show_avatar == "on") {
$avatar = "<img src=\"$top_member[avatar]\" border=\"0\"></a></center><br>";
}
$top_members_list = "<center><a href=\"member.php?action=viewpro&member=$top_member[username]\">$avatar
<b><a href=\"member.php?action=viewpro&member=$top_member[username]\">$top_member[username]</a></b><br>
<b>Rank</b> : $top_member[status] <br>
<b>Posts</b>: $top_member[postnum]<br>
<hr color=\"$bordercolor\">";
$memcount++;
}else{
$top_members_list .= "<b>$memcount. <a href=\"member.php?action=viewpro&member=$top_member[username]\" title=\"Posts $top_member[postnum]\">$top_member[username]</a></b><br><b>Rank</b>: $top_member[status] <br>
<b>Posts</b>: $top_member[postnum] <br><br>";
$memcount++;
   }
}
$blockfiletitle = "$portal_topmembers Top members";
if($membercheck == 0) {
$content = "<br>There are No Top members right now.<br>";
}else{
$content = "<tr><td bgcolor=\"$altbg2\" class=\"mediumtxt\"><font class=\"mediumtxt\"><center>$top_members_list</center></font></td></tr> ";
}
?>
