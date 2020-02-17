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

if (eregi("block-whos_online.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}
global $table_members, $table_whosonline;

		$time = time();
		$newtime = $time - 600;
		$membercount = 0;
		$guestcount = 0;

		$query = mysql_query("SELECT m.status, m.username, w.* FROM $table_whosonline w LEFT JOIN $table_members m ON m.username=w.username ORDER BY w.username");
		while($online = mysql_fetch_array($query)) {
			switch($online['username']) {
				case 'xguest123':
					$guestcount++;
					break;

				default:
					$member[$membercount] = $online;
					$membercount++;
					break;
			}
		}

		$onlinenum = $guestcount + $membercount;

		if($membercount == 0){
			$membern = "no Members";
		}elseif($membercount == 1){
			$membern = "1 Member";
		}else{
			$membern = "$membercount Members";
		}

		if($guestcount==0){
			$guestn = "No Guests";
		}elseif($guestcount==1){
			$guestn = "1 Guest";
		}else{
			$guestn = "$guestcount Guests";
		}

		eval($lang_whosoneval);
		$memonmsg = "<span class=\"smalltxt\">$lang_whosonmsg</span>";

		$memtally = "";
		$num = 1;
		$comma = "";
		for($mnum=0; $mnum<$membercount; $mnum++) {
			$online = $member[$mnum];
			if($online[status] == "Administrator") {
			$pre = "<b><u>";
			$suf = "</b></u>";
			}
			elseif($online[status] == "Super Administrator") {
			$pre = "<i><b><u>";
			$suf = "</i></b></u>";
			}
			elseif($online[status] == "Super Moderator") {
			$pre = "<i><b>";
			$suf = "</i></b>";
			}
			elseif($online[status] == "Moderator") {
			$pre = "<b>";
			$suf = "</b>";
			}
			else {
				$pre = "";
				$suf = "";
			}
			$memtally .= "$comma <a href=\"member.php?action=viewpro&member=".rawurlencode($online[username])."\">$pre$online[username]$suf</a>";
			$comma = ", ";
			$num++;
		}

		if($memtally == "") {
			$memtally = "&nbsp;";
		}

		$datecut = time() - (3600 * 24);
		$query = mysql_query("SELECT username FROM $table_members WHERE lastvisit>='$datecut' ORDER BY username DESC LIMIT 0, 50");

		$todaymembersnum = 0;
		$todaymembers = '';
		$comma = '';

		while ($memberstoday = mysql_fetch_array($query)) {
    			$todaymembers .= "$comma <a href=\"member.php?action=viewpro&member=".rawurlencode($memberstoday['username'])."\">".$memberstoday['username']."</a>";
    			++$todaymembersnum;
    			$comma = ", ";
		}

		if ($todaymembersnum == 1) {
			$memontoday = $todaymembersnum . $lang_textmembertoday;
		} else {
			$memontoday = $todaymembersnum . $lang_textmemberstoday;
		}
  //END WHOS ONLINE TODAY HAC

// TEMPLATE IS BELOW, EDIT AWAY!
$blockfiletitle = "<a href=\"misc.php?action=online\">Who's Online</a>";
$content = "<tr><td bgcolor=\"$altbg2\" class=\"mediumtxt\"><font size=1>Guests: $guestn<br>
Members: $membern</font><br>
$memtally
<hr>
<a href=\"misc.php?action=onlinetoday\"><b>$lang_whosonlinetoday</b></a><br>
<font size=1>$memontoday</font><br>
<hr>
<font size=1>$todaymembers</font>
</tr> ";
?>
