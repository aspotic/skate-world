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

if (eregi("block-Latest_members.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

global $table_members;

$portal_newmembers = 6;

$num = 0;
$query = mysql_query("SELECT username FROM $table_members ORDER BY regdate DESC LIMIT 0, $portal_newmembers");
while($lastmem = mysql_fetch_array($query)) {
$lastmember = rawurlencode($lastmem['username']);
if ($num == 0){
$portal_newest_member = "<a href=\"member.php?action=viewpro&member=$lastmember\"><b>$lastmem[username]</b></a>";
}
$new_members_list .= "<li><a href=\"member.php?action=viewpro&member=$lastmember\">$lastmem[username]</a></li><br>\n";
$num++;
}

$blockfiletitle = "$portal_newmembers Newest Members";
$content = "<tr>
<td bgcolor=\"$altbg2\" class=\"mediumtxt\">
$new_members_list
</font>
<br>
<center>
<b><font class=\"smalltxt\">< <a href=\"misc.php?action=list\">View all Members</a> ></font></b>
</td>
</tr>";

?>
