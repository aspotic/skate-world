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

if (eregi("block-staff.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

global $table_members, $portal_images;

$query = mysql_query("SELECT DISTINCT * FROM $table_members WHERE status='Super Administrator' OR status='Administrator' OR status='Super Moderator' OR status='Moderator'");
while ($member = mysql_fetch_array($query)) {

	$x_list = "";
	$aim = "";
	$icq = "";
	$msn = "";
	$yahoo = "";
	$bio_text = "";
    if(!empty($member[icon2])) {
     $md[icon] = "<img src=\"$smdir/$member[icon2]\" />";
    }
	if ($member[aim]) $aim = " <a href=\"aim:goim?screenname=$member[aim]&message=Hi+I+got+your+name+from+a+message+board...\"><img src=\"$imgdir/aim.gif\" border=\"0\" title=\"Chat with $member[aim] via AIM\"></a>";
	if ($member[icq]) $icq = " <a href=\"http://wwp.icq.com/scripts/search.dll?to=$member[icq]\"><img src=\"http://web.icq.com/whitepages/online?icq=$member[icq]&img=5\" border=\"0\" title=\"Chat with $member[icq] via ICQ\"></a>";
	if ($member[yahoo])
	{
		$yahoo = "
		<a href=\"http://edit.yahoo.com/config/send_webmesg?.target=$member[yahoo]&.src=pg\">
		<img border=0 src=\"http://opi.yahoo.com/online?u=$member[yahoo]&m=g&t=3\" title=\"Chat with $member[yahoo] via Yahoo Messenger\"></a>
		";
	}
	if ($member[msn])
	{
		$msn = "<a href=\"member.php?action=viewpro&member=$encodename\"><img src=\"$imgdir/msn.gif\" border=\"0\" alt=\"This User Has MSN Messenger\"></a>";
	}
	if (!empty($member[mood])) {
      $mood = "<b>Mood:</b>$md[icon] $member[mood]<br>";
       }elseif(!empty($member[icon2]))  {
      $mood = "<b>Mood:</b> $md[icon] $member[mood]<br>";
       }else{
      unset($mood);
        }

        $staffstat = "";

        if($member[staff] != '') {

        $staffstat = "<br><img src =\"$portal_images/cup.gif\" border=\"0\"><font class=\"smalltxt\"><b>Position:</b>&nbsp;$member[staff]</font>";
         }else{

         $staffstat = "";

      }

	if ($member[status] == "Super Administrator") $x_list = "superadmin_list";
	elseif ($member[status] == "Administrator") $x_list = "admin_list";
	elseif ($member[status] == "Super Moderator") $x_list = "supermod_list";
	elseif ($member[status] == "Moderator") $x_list = "mod_list";
	$$x_list .= "

	<a href=\"member.php?action=viewpro&member=$member[username]\"><b>$member[username]</b></a>
	<small>(<a href=\"#\" onclick=\"Popup('u2u.php?action=send&username=$member[username]', 'Window', 550, 450);\">Send U2U</a>)
	(<a href=\"javascript:Popup('buddy.php?action=add&buddy=$member[username]', 'Window', 250, 300);\">Add to Buddies</a>)</small>

	<br>
	<b>Rank:</b> $member[status]<br>
	$mood
    $staffstat<br>
	$aim $msn $icq $yahoo<br>

	<br>
	";

}
// END STAFF
$content = "<tr><td bgcolor=\"$altbg2\" class=\"mediumtxt\"><font class=\"mediumtxt\"><br><b>Super Administrators:</b>
<br><br>
$superadmin_list
<hr color=\"$bordercolor\">
<br>
<b>Administrators:</b>
<br><br>
$admin_list
<hr color=\"$bordercolor\">
<br>
<b>Super Moderators:</b>
<br><br>
$supermod_list
<hr color=\"$bordercolor\">
<br>
<b>Moderators:</b>
<br><br>
$mod_list
<br>
</font>
</td>
</tr>";
?>
