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

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
	die ("You can't access this file directly...");
}

require_once("portal_main.php");
$userpage = 1;
$module_name = basename(dirname(__FILE__));
eval("\$header = \"".template("header")."\";");
echo $header;

if(!$xmbuser || !$xmbpw) {

	?>
	<script>
	function redirect(){
		window.location.replace("misc.php?action=login");
	}

	setTimeout("redirect();", 1250);
	</script>

	<?
	exit();
}

	$query = $db->query("SELECT * FROM $table_members WHERE username='$xmbuser'");
	$userinfo = $db->fetch_array($query);
    $num = $db->num_rows($query);
	if($userinfo[avatar] == "") {
		$userinfo[avatar] = "&nbsp;";
	} else {
		$userinfo[avatar] = "<img src=\"$userinfo[avatar]\" border=0\" />";
	}
    if(($num == 1) && ($userinfo[site] || $userinfo[email] || $userinfo[bio] || $userinfo[icq] || $userinfo[aim] || $userinfo[yahoo] || $userinfo[msn] || $userinfo[location] || $userinfo[customstatus] || $userinfo[sig])) {
	if ($userinfo[site] != "http://" AND $userinfo[site] != "") { $usersite = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Home page:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\"><a href=\"$userinfo[site]\" target=\"new\">$userinfo[site]</a></td></tr>"; }
	if ($userinfo[email]) { $useremail = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>E-Mail:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\"><a href=\"mailto:$userinfo[email]\">$userinfo[email]</a></td></tr>"; }
	if ($userinfo[icq]) $usericq = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Icq:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\">$userinfo[icq]</td></tr>";
	if ($userinfo[aim]) $useraim = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Aim:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\">$userinfo[aim]</td></tr>";
	if ($userinfo[yahoo]) $useryahoo = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Yahoo:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\">$userinfo[yahoo]</td></tr>";
	if ($userinfo[msn]) $usermsn = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Msn:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\">$userinfo[msn]</td>";
	if ($userinfo[location]) $userlocation = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Location:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\">$userinfo[location]</td></tr>";
        $personstatus = substr_replace($personstatus, ' ', 20, 0);
	    $personstatus = substr_replace($personstatus, ' ', 41, 0);
	    $personstatus = substr_replace($personstatus, ' ', 62, 0);
	    $personstatus = substr_replace($personstatus, ' ', 83, 0);
	    $personstatus .= "<br />";
	if ($userinfo[customstatus]) $personstatus = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Custom Status:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\">$userinfo[customstatus]</td></tr>";
	    $userinfo[sig] = postify($userinfo[sig], '', $sigbbcode, $forum[allowsmilies], $SETTINGS[sightml], '', $forum[allowimgcode]);
	if ($userinfo[sig]) $usersig = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\" colspan=\"5\"><b>Your Singnature:</b></td></tr><tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\">$userinfo[sig]</td></tr>";
	if ($userinfo[bio]) { $userbio = "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Bio:</b></td><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"5\">$userinfo[bio]</td></tr>"; }
}

	    $query = $db->query("SELECT * FROM $table_u2u WHERE msgto='$xmbuser' AND folder='inbox' ORDER BY dateline DESC LIMIT 0, 15");
	    $u2unum = $db->num_rows($query);
	    while($message = $db->fetch_array($query)) {
		$postdate = gmdate("$dateformat",$message[dateline] + ($timeoffset * 3600) + ($addtime * 3600));
		$posttime = gmdate("$timecode",$message[dateline] + ($timeoffset * 3600) + ($addtime * 3600));
		$senton = "$postdate $lang_textat $posttime";

		if($message[subject] == "") {
			$message[subject] = "&lt;$lang_textnosub&raquo;";
		}

		if($message[readstatus] == "yes"){
			$read = "$lang_textread";
		} else {
			$read = "$lang_textunread";
		}

		$message[subject] = stripslashes($message[subject]);
		$messages .= " <tr><td class=\"tablerow\" bgcolor=\"$altbg2\"><a href=\"#\"onclick=\"Popup('u2u.php?action=view&u2uid=$message[u2uid]', 'Window', 550, 450);\">$message[subject]</a></td>
                       <td class=\"tablerow\" bgcolor=\"$altbg1\">$message[msgfrom]</td>
                       <td class=\"tablerow\" bgcolor=\"$altbg2\">$senton</td>
                       <td class=\"tablerow\" bgcolor=\"$altbg2\">$read</td>
                       </tr>";
	    }

	    if($u2unum == 0) {
           $messages .= "<tr><td class=\"tablerow\" bgcolor=\"$altbg1\" colspan=\"4\">$lang_nou2umsg</td></tr>";
	    }
     
	    $query2 = $db->query("SELECT * FROM $table_favorites f, $table_threads t, $table_posts p WHERE f.tid=t.tid AND p.tid=t.tid AND p.subject=t.subject AND f.username='$xmbuser' AND f.type='favorite' ORDER BY t.lastpost DESC");
	    $favnum = $db->num_rows($query2);
	    while($fav = $db->fetch_array($query2)) {
		$query = $db->query("SELECT name, fup, fid FROM $table_forums WHERE	fid='$fav[fid]'");
		$forum = $db->fetch_array($query);
		$lastpost = explode("|", $fav[lastpost]);
		$dalast = $lastpost[0];
		$lastpost[1] = "<a href=\"member.php?action=viewpro&member=".rawurlencode($lastpost[1])."\">$lastpost[1]</a>";
		$lastreplydate = gmdate($dateformat, $lastpost[0] + ($timeoffset * 3600) + ($addtime * 3600));
		$lastreplytime = gmdate($timecode, $lastpost[0] + ($timeoffset * 3600) + ($addtime * 3600));
		$lastpost = "$lang_lastreply1 $lastreplydate $lang_textat
		$lastreplytime $lang_textby $lastpost[1]";
		$fav[subject] = stripslashes($fav[subject]);

		if($fav[icon] != "") {
			$fav[icon] = "<img src=\"$smdir/$fav[icon]\" />";
		} else {
			$fav[icon] = "&nbsp;";
		}

		$favs .= "<tr><td class=\"tablerow\" bgcolor=\"$altbg2\" width=\"4%\">$fav[icon]</td><td class=\"tablerow\" bgcolor=\"$altbg1\" width=\"43%\"><a href=\"viewthread.php?tid=$fav[tid]\">$fav[subject]</a></td>
                  <td class=\"tablerow\" bgcolor=\"$altbg2\"><a href=\"forumdisplay.php?fid=$forum[fid]\">$forum[name]</a></td>
                  <td class=\"tablerow\" bgcolor=\"$altbg1\" width=\"6%\">$fav[replies]</td>
                  <td class=\"tablerow\" bgcolor=\"$altbg2\" width=\"19%\"><font size=\"1\" face=\"verdana\">$lastpost</font></td></tr>";
	    }
    
	    if($favnum == '0') {
		   $favs .= "<tr><td class=\"tablerow\" bgcolor=\"$altbg1\" colspan=\"6\">$lang_nofavsmsg</td>";
    	}
 
    include("portal_header.php");
    Opentable();
    echo "<center>";
    echo "<b>Brief Account Summary For $xmbuser</b><br>";
   	echo "This is your personal page</center><br><br>";
    echo "<center>$userinfo[avatar]</center>";
    echo "<br>";
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">";
    echo"<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Uid</b></td>
             <td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Username</b></td>
             <td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Posts</b></td>
             <td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Memeber Status</b></td>
             <td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Mood</b></td></tr>";
    echo"<tr><td bgcolor=\"$altbg2\" class=\"tablerow\">$userinfo[uid]</td>
             <td bgcolor=\"$altbg2\" class=\"tablerow\">$userinfo[username]</td>
             <td bgcolor=\"$altbg2\" class=\"tablerow\">$userinfo[postnum]</td>
             <td bgcolor=\"$altbg2\" class=\"tablerow\">$userinfo[status]</td>
             <td bgcolor=\"$altbg2\" class=\"tablerow\">$userinfo[mood]</td>
             </tr>";
    echo "$useremail $usericq $useraim $useryahoo $usermsn $userlocation $usersig $userbio $personstatus";
    echo "</table>";
    echo"<br>";
    Closetable();
    echo "<br>";
    title("Latest Fifteen U2U Messages");
    OpenTable();
    echo"<br>";
    BeginTable();
     echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\"><b>Subject:</b></td>
               <td bgcolor=\"$altbg2\" class=\"tablerow\"><b>From:</b></td>
               <td bgcolor=\"$altbg2\" class=\"tablerow\"><b>Sent:</b></td>
               <td bgcolor=\"$altbg2\" class=\"tablerow\"><b>Read?</b></td></tr>";
    echo "$messages";
    EndTable();
    echo"<br>";
    CloseTable();
    echo "<br>";
    title("Latest Five Favourites With New Replies");
    OpenTable();
    echo"<br>";
    BeginTable();
     echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\"><b>Subject:</b></td>
               <td bgcolor=\"$altbg2\" class=\"tablerow\"><b>Forum:</b></td>
               <td bgcolor=\"$altbg2\" class=\"tablerow\"><b>Replies:</b></td>
               <td bgcolor=\"$altbg2\" class=\"tablerow\"><b>Last Post:</b></td></tr>";
     echo "$favs";
    EndTable();
    echo"<br>";
    CloseTable();
    echo "<br>";
    title("Personal Block");
    OpenTable();
    echo"<br>";
    BeginTable();
    if ($userinfo['ublockon']==1) {
        $sel = "checked";
    }
    echo "<form action=\"modules.php?modulename=$module_name\" method=\"post\">"
         ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Activate Personal Block:</b></td>"
         ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"checkbox\" name=\"ublockon\" $sel><br>(Check this option and the following text will appear in the Main Portal)</td></tr>"
         ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Personal Block Content:</b></td>"
         ."<td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea cols=\"55\" rows=\"5\" name=\"ublock\">$userinfo[ublock]</textarea><br>(You can use HTML code to put links, for example)</td></tr>"
         ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">"
   	     ."<input type=\"hidden\" name=\"username\" value=\"$userinfo[username]\">"
	     ."<input type=\"hidden\" name=\"uid\" value=\"$userinfo[uid]\">"
	     ."<input type=\"hidden\" name=\"op\" value=\"savehome\">"
         ."<input type=\"submit\" value=\"Save Changes\">";

    EndTable();
    echo"</form><br>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<b>Last 10 Comments by $userinfo[username]:</b><br>";
    $sql6 = "SELECT tid, storyid, subject FROM ".$prefix."_comments WHERE name='$username' ORDER BY tid DESC LIMIT 0,10";
    $result6 = $db->query($sql6);
    while($row6 = $db->fetch_array($result6)) {
	$tid = $row6['tid'];
	$storyid = $row6['storyid'];
	$subject = $row6['subject'];
    echo "<li><a href=\"modules.php?modulename=News&modulefile=article&thold=-1&mode=flat&order=0&storyid=$storyid#$tid\">$subject</a><br>";
    }
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<b>Last 10 News Submissions sent by $userinfo[username]:</b><br>";
    $sql7 = "SELECT storyid, title FROM ".$prefix."_stories WHERE informant='$username' ORDER BY storyid DESC LIMIT 0,10";
    $result7 = $db->query($sql7);
    while($row7 = $db->fetch_array($result7)) {
	$storyid = $row7['storyid'];
	$title = $row7['title'];
    echo "<li><a href=\"modules.php?modulename=News&modulefile=article&storyid=$storyid\">$title</a><br>";
    }
    CloseTable();

function savehome($uid, $username, $ublockon, $ublock) {
    global $xmbuser, $userinfo, $table_members, $db, $module_name;
	if(isset($ublockon)) $ublockon=1; else $ublockon=0;
	$ublock = FixQuotes($ublock);
	$db->query("UPDATE $table_members SET ublockon='$ublockon', ublock='$ublock' WHERE uid='$uid'");
    Header("Location: modules.php?modulename=$module_name");
}



include("portal_footer.php");
end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;


switch($op) {

    case "savehome":
    savehome($uid, $username, $ublockon, $ublock);
	break;
}
?>
