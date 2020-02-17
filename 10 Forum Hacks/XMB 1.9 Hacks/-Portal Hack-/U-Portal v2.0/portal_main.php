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

require "./header.php";

          set_magic_quotes_runtime(0);
         $mtime = microtime();
         $mtime = explode(" ",$mtime);
         $mtime = $mtime[1] + $mtime[0];
         $start_time = $mtime;

         function addslashes_array($array) {
         foreach ($array as $key => $val) {
         $array[$key] = (is_array($val)) ? addslashes_array($val) : addslashes($val);
         }
         return $array;
         }

         if(get_magic_quotes_gpc() == 0) {
          $HTTP_GET_VARS = addslashes_array($HTTP_GET_VARS);
          $HTTP_POST_VARS = addslashes_array($HTTP_POST_VARS);
          $HTTP_COOKIE_VARS = addslashes_array($HTTP_COOKIE_VARS);
         }
         
         if (eregi("portal_main.php",$PHP_SELF)) {
             Header("Location: portal.php");
             die();
         }
         if(isset($aid)){
         $aid = $xmbuser;
         }
         
        $prefix = "".$tablepre."portal";
        $mainfile = 1;
        $sql = ("SELECT * FROM ".$prefix."_config");
        $result = mysql_query($sql);
	    foreach(mysql_fetch_array($result) as $key => $val) {
		$$key = $val;
		$PORTAL_CONFIG[$key] = $val;
        }
        $portal_images = "images/portal_images";
        $sitename = $SETTINGS['sitename'];
        $tipath = "".$portal_images."/topics/";

        $AllowableHTML = array("b"=>1, "i"=>1, "a"=>2, "em"=>1, "br"=>1, "strong"=>1, "blockquote"=>1, "tt"=>1, "li"=>1, "ol"=>1, "ul"=>1);
        $CensorList = array("fuck","cunt","fucker","fucking","pussy","cock","c0ck","cum","twat","clit","bitch","fuk","fuking","motherfucker");

/*=====================================*/
/*  Begin Portal Functions            */
/*====================================*/

function title($text) {
    OpenTable();
    echo "<center><font class=\"subject\"><b>$text</b></font></center>";
    CloseTable();
    echo "<br>";
}

function is_active($module) {
    global $prefix;
    $sql = "SELECT active FROM ".$prefix."_modules WHERE title='$module'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $act = $row[active];
    if (!$result OR $act == 0) {
	return 0;
    } else {
	return 1;
    }
}

function render_blocks($side, $blockfile, $title, $content, $bid, $url) {
   global $bordercolor, $borderwidth, $tablespace, $db, $imgdir, $langfile, $catbgcode, $altbg2, $bbname, $altbg1, $cattext, $bgcode, $full_url;
   require "lang/$langfile.lang.php";
    if ($url == "") {
	if ($blockfile == "") {
	    if ($side == "c") {
		themecenterbox($title, $content);
	    } elseif ($side == "d") {
		themecenterbox($title, $content);
	    } else {
		themesidebox($title, $content);
	    }
	    } else {
	    if ($side == "c") {
		blockfileinc($title, $blockfile, 1);
	    } elseif ($side == "d") {
		blockfileinc($title, $blockfile, 1);
	    } else {
		blockfileinc($title, $blockfile);
	    }
	    }
        } else {
	    if ($side == "c" OR $side == "d") {
	    headlines($bid,1);
	    } else {
        headlines($bid);
	    }

    }
}

function blocks($side) {
    global $storynum, $langfile, $imgdir, $status, $db, $bordercolor, $borderwidth, $tablespace, $imgdir, $langfile, $catbgcode, $altbg2, $bbname, $altbg1, $cattext, $bgcode, $prefix, $full_url, $xmbuser;
    require "lang/$langfile.lang.php";
    if (strtolower($side[0]) == "l") {
	$pos = "l";
    } elseif (strtolower($side[0]) == "r") {
	$pos = "r";
    }  elseif (strtolower($side[0]) == "c") {
	$pos = "c";
    } elseif  (strtolower($side[0]) == "d") {
	$pos = "d";
    }
    
    $side = $pos;
    $sql = "SELECT bid, bkey, title, content, url, blockfile, view FROM ".$prefix."_blocks WHERE bposition='$pos' AND active='1' ORDER BY weight ASC";
    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
	$bid = $row[bid];
	$title = $row[title];
	$content = trim($row[content]);
	$url = $row[url];
	$blockfile = $row[blockfile];
	$view = $row[view];
	if($row[bkey] == admin) {
	    adminblock();
	}elseif($row[bkey] == userbox) {
	    userblock();
	}elseif($row[bkey] == "") {
	    if($view == 0) {
		render_blocks($side, $blockfile, $title, $content, $bid, $url);
	    }elseif($view == 1 AND $status == "Member" || $status == "Moderator" || $status == "Super Moderator" || $status == "Super Administrator" || $status == "Administrator") {
		render_blocks($side, $blockfile, $title, $content, $bid, $url);
	    }elseif($view == 2 AND $status == "Super Administrator" || $status == "Administrator") {
		render_blocks($side, $blockfile, $title, $content, $bid, $url);
	    }elseif($view == 3 AND !$xmbuser) {
		render_blocks($side, $blockfile, $title, $content, $bid, $url);
	    }
 	  }
    }
}

function message_box() {
    global $bordercolor, $tablewidth, $tablespace, $borderwidth, $altbg1, $prefix, $altbg2, $catbgcode, $status, $xmbuser;
    $message_box =0;
    $result = mysql_query("SELECT mid, title, content, date, expire, view FROM ".$prefix."_message WHERE active='1'");
	while ($row = mysql_fetch_array($result)) {
	    $mid = $row[mid];
	    $title = $row[title];
	    $content = $row[content];
	    $mdate = $row[date];
	    $expire = $row[expire];
	    $view = $row[view];

	if ($title != "" && $content != "") {
	    if ($expire == 0) {
		$remain = "Unlimited";
	    } else {
		$etime = (($mdate+$expire)-time())/3600;
		$etime = (int)$etime;
		if ($etime < 1) {
		    $remain = "Expiration: Less than 1 hour";
		} else {
		    $remain = "Expiration in $etime Hours";
		}
	    }

	    if ($view == 4 AND $status == "Super Administrator" || $status == "Administrator") {
           $adminview = "<br><br><center><font class=\"subject\">[ View: Administrators Only - $remain - <a href=\"portal_admin.php?op=editmsg&mid=$mid\">Edit</a> ]</font></center>";

         ?>
          <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="<?=$bordercolor?>"><tr><td>
          <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"><tr>
          <td class="category"><b>» <?=$title?></b></td></tr><tr><td bgcolor="<?=$altbg2?>" class="mediumtxt">
          <font class="mediumtxt"><center><?=$content?><br><?=$adminview?></center></font></td></tr>
          </table></td></tr></table><br>
         <?

	    } elseif ($view == 3 AND $xmbuser) {
		if ($status == "Super Administrator" || $status == "Administrator") {
		    $adminview = "<br><br><center><font class=\"subject\">[ View: Registered Users Only - $remain - <a href=\"portal_admin.php?op=editmsg&mid=$mid\">Edit</a> ]</font></center>";
		}
          ?>
          <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="<?=$bordercolor?>"><tr><td>
          <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"><tr>
          <td class="category" <?=$catbgcode?>><b>» <?=$title?></b></td></tr><tr><td bgcolor="<?=$altbg2?>" class="mediumtxt">
          <font class="mediumtxt"><center><?=$content?><br><?=$adminview?></center></font></td></tr>
          </table></td></tr></table><br>
          <?

	    } elseif ($view == 2 AND $status == "Super Administrator" || $status == "Administrator" || $status == "Moderator" || $status == "Super Moderator") {
		if ($status == "Super Administrator" || $status == "Administrator") {
		   $adminview = "<br><br><center><font class=\"subject\">[ View: Staff Users Only - $remain - <a href=\"portal_admin.php?op=editmsg&mid=$mid\">Edit</a> ]</font></center>";
		}

        ?>
          <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="<?=$bordercolor?>"><tr><td>
          <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"><tr>
          <td class="category" <?=$catbgcode?>><b>» <?=$title?></b></td></tr><tr><td bgcolor="<?=$altbg2?>" class="mediumtxt">
          <font class="mediumtxt"><center><?=$content?><br><?=$adminview?></center></font></td></tr>
          </table></td></tr></table><br>
         <?

        } elseif ($view == 1) {
		if ($status == "Super Administrator" || $status == "Administrator") {
		  $adminview = "<br><br><center><font class=\"subject\">[ View: All Visitors - $remain - <a href=\"portal_admin.php?op=editmsg&mid=$mid\">Edit</a> ]</font></center>";
		}

        ?>
          <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="<?=$bordercolor?>"><tr><td>
          <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"><tr>
          <td class="category" <?=$catbgcode?>><b>» <?=$title?></b></td></tr><tr><td bgcolor="<?=$altbg2?>" class="mediumtxt">
          <font class="mediumtxt"><center><?=$content?><br><?=$adminview?></center></font></td></tr>
          </table></td></tr></table><br>
         <?

	    }
        $message_box++;

	    if ($expire != 0) {
	    	$past = time()-$expire;
		if ($mdate < $past) {
		    mysql_query("UPDATE ".$prefix."_message SET active='0' WHERE mid='$mid'");
		}
     }
   }
  }
}

function blockfileinc($title, $blockfile, $side=0) {
    global $bordercolor, $language, $borderwidth, $tablespace, $imgdir, $db, $langfile, $catbgcode, $altbg2, $bbname, $altbg1, $cattext, $bgcode, $full_url, $xmbuser;
    require "./lang/$langfile.lang.php";
    $blockfiletitle = $title;
    $file = @file("blocks/$blockfile");
    if (!$file) {
	$content = "<center>There is a problem right now with this block.</center>";
    } else {
	include("blocks/$blockfile");
    }
    if ($content == "") {
	$content = "<center>There isn't content right now for this block.</center>";
    }
    if ($side == 1) {
	themecenterbox($blockfiletitle, $content);
    } elseif ($side == 2) {
	themecenterbox($blockfiletitle, $content);
    }elseif ($side == 3) {
	centernewsbox($content);
    } else {
	themesidebox($blockfiletitle, $content);
    }
}

function check_words($Message) {
    global $EditedMessage;
    $EditedMessage = $Message;
    if ($CensorMode != 0) {
	if (is_array($CensorList)) {
	    $Replace = $CensorReplace;
	    if ($CensorMode == 1) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("$CensorList[$i]([^a-zA-Z0-9])","$Replace\\1",$EditedMessage);
		}
	    } elseif ($CensorMode == 2) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("(^|[^[:alnum:]])$CensorList[$i]","\\1$Replace",$EditedMessage);
		}
	    } elseif ($CensorMode == 3) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("$CensorList[$i]","$Replace",$EditedMessage);
		}
	    }
	}
    }
    return ($EditedMessage);
}

function delQuotes($string){
    $tmp="";
    $result="";
    $i=0;
    $attrib=-1;
    $quote=1;
    $len = strlen($string);
    while ($i<$len) {
	switch($string[$i]) {
    case "\"":
	if ($quote==0) {
		 $quote=1;
	} else {
        $quote=0;
   if (($attrib>0) && ($tmp != "")) { $result .= "=\"$tmp\""; }
        $tmp="";
        $attrib=-1;
	}
	break;
    case "=":
	if ($quote==0) {
        $attrib=1;
   if ($tmp!="") $result.=" $tmp";
       $tmp="";
	} else $tmp .= '=';
   break;
   case " ":
  if ($attrib>0) {
      $tmp .= $string[$i];
	}
	break;
    default:
	if ($attrib<0)
		$attrib=0;
		$tmp .= $string[$i];
	break;
	}
	$i++;
    }
    if (($quote!=0) && ($tmp != "")) {
	if ($attrib==1) $result .= "=";
	$result .= "\"$tmp\"";
    }
    return $result;
}

function check_html ($str, $strip="") {
    if ($strip == "nohtml")
   	$AllowableHTML=array('');
	$str = stripslashes($str);
	$str = eregi_replace("<[[:space:]]*([^>]*)[[:space:]]*>",'<\\1>', $str);
	$str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>",'<a href="\\1">', $str);
	$str = eregi_replace("<[[:space:]]* img[[:space:]]*([^>]*)[[:space:]]*>", '', $str);
	$tmp = "";
	while (ereg("<(/?[[:alpha:]]*)[[:space:]]*([^>]*)>",$str,$reg)) {
	$i = strpos($str,$reg[0]);
	$l = strlen($reg[0]);
	if ($reg[1][0] == "/") $tag = strtolower(substr($reg[1],1));
	else $tag = strtolower($reg[1]);
	if ($a = $AllowableHTML[$tag])
	if ($reg[1][0] == "/") $tag = "</$tag>";
	elseif (($a == 1) || ($reg[2] == "")) $tag = "<$tag>";
	else {
    $attrb_list=delQuotes($reg[2]);
    $attrb_list = ereg_replace("&","&amp;",$attrb_list);
    $tag = "<$tag" . $attrb_list . ">";
	}
	else $tag = "";
	$tmp .= substr($str,0,$i) . $tag;
	$str = substr($str,$i+$l);
	}
	$str = $tmp . $str;
	return $str;
	exit;
	$str = ereg_replace("<\?","",$str);
	return $str;
}

function filter_text($Message, $strip="") {
    global $EditedMessage;
    check_words($Message);
    $EditedMessage=check_html($EditedMessage, $strip);
    return ($EditedMessage);
}

function FixQuotes ($what = "") {
    $what = ereg_replace("'","''",$what);
    while (eregi("\\\\'", $what)) {
	$what = ereg_replace("\\\\'","'",$what);
    }
    return $what;
}

function formatTimestamp($time) {
    global $datetime, $locale;
    setlocale (LC_TIME, $locale);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = strftime("%A, %B %d @ %T %Z", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    return($datetime);
}

function formatAidHeader($aid) {
    global $table_members;
    $sql = "SELECT site, email FROM $table_members WHERE username='$aid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $url = $row['site'];
    $email = $row['email'];
    if (isset($url)) {
	$aid = "<a href=\"$url\"><b>$aid</b></a>";
    } elseif (isset($email)) {
	$aid = "<a href=\"mailto:$email\"><b>$aid</b></a>";
    } else {
	$aid = $aid;
    }
    echo "<b>$aid</b>";
}

function get_author($aid) {
    global $table_members;
    $sql = "SELECT site, email FROM $table_members WHERE username='$aid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    if (isset($row['site'])) {
	$aid = "<a href=\"$row[site]\"><b>$aid</b></a>";
    } elseif (isset($row['email'])) {
	$aid = "<a href=\"mailto:$row[email]\"><b>$aid</b></a>";
    } else {
	$aid = $aid;
    }
    return($aid);
}

function themepreview($title, $hometext, $bodytext="", $notes="") {
    echo "<b>$title</b><br><br>$hometext";
    if ($bodytext != "") {
	echo "<br><br>$bodytext";
    }
    if ($notes != "") {
	echo "<br><br><b>Note:</b> <i>$notes</i>";
    }
}

function adminblock() {
    global $status, $prefix, $table_application;
    if ($status == "Super Administrator" || $status == "Administrator") {
	$sql = "SELECT title, content FROM ".$prefix."_blocks WHERE bkey='admin'";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
    $content = "<font class=\"tablerow\">$row[content]</font>";
    themesidebox($row[title], $row[content]);
	}
	$title = "Waiting Content";
	$num = mysql_num_rows(mysql_query("SELECT * FROM ".$prefix."_queue"));
	$content = "<font class=\"content\">";
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"portal_admin.php?op=submissions\">Submissions</a>: $num<br>";
	$num = mysql_num_rows(mysql_query("SELECT * FROM ".$prefix."_reviews_add"));
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"portal_admin.php?op=reviews\">Waiting Reviews</a>: $num<br>";
    if (file_exists("application.php")) {
    $appnewquery = mysql_query("SELECT COUNT(aid) FROM $table_application WHERE status = 'New'");
    $apptotal = mysql_result($appnewquery, 0);
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a target=\"_blank\" href=\"application_admin.php?action=adminview\">Waiting applications</a>: $apptotal<br>";
    }
    themesidebox($title, $content);
    }
}

function loginbox() {
    global $xmbuser, $table_members, $bordercolor, $borderwidth, $tablespace, $imgdir, $langfile, $catbgcode, $altbg2, $bbname, $altbg1, $cattext;
    if (!$xmbuser) {
	$title = "Login";
    $boxstuff = "<tr><td align=\"center\" bgcolor=\"$altbg2\" class=\"mediumtxt\">Welcome, <b>Guest!</b><br>Please Login:";
    $boxstuff .= "<form action=\"misc.php?action=login\" method=\"post\">";
    $boxstuff .= "<input type=\"hidden\" name=\"prevpage\" value=\"portal.php\">";
    $boxstuff .= "<input type=\"text\" name=\"username\" size=\"15\"><br>";
    $boxstuff .= "<input type=\"password\" name=\"password\" size=\"15\"><br>";
    $boxstuff .= "<input type=\"submit\" name=\"loginsubmit\" value=\"Login\"><br>";
    $boxstuff .= "</form><font class=\"smalltxt\">Registration is not required but will enable all the personal features</font><br><br>";
    $boxstuff .= "$reglink</td></tr>";
	themesidebox($title, $boxstuff);
    }elseif($xmbuser AND $xmbuser != ""){
    $title = "Logged in as $xmbuser";
    $query = mysql_query("SELECT * FROM $table_members WHERE username='$xmbuser'");
    $member = mysql_fetch_array($query);
    if ($newu2unum == 1) {
    $new_msg_txt = "new message";
    }else{
    $new_msg_txt = "new messages";
    }
    mysql_query("UPDATE $table_members SET avatar='$self[avatar]' WHERE uid='$self[uid]'");
    if ($member[avatar]) {
    $avatar = "<img src=\"$member[avatar]\" border=\"0\">";
    }else {
    unset($avatar);
    }
    if ($newu2umsg != "") {
    $new_msg_txt = "<br>$newu2umsg";
    }else{
    $new_msg_txt = "";
    }
   $boxstuff = "<tr><td bgcolor=\"$altbg2\" class=\"mediumtxt\">$avatar<br>Welcome <b>$xmbuser!</b>$new_msg_txt<br>";
   $boxstuff .= "<li><a href=\"memcp.php\">Your User Control Panel</A></li><br>";
   $boxstuff .= "<li><a href=\"memcp.php?action=profile\">Edit Your Profile</a></li><br>";
   $boxstuff .= "<li><a href=\"memcp.php?action=options\">Your Forum Options</a></li><br>";
   $boxstuff .= "<li><a href=\"#\" onclick=\"Popup('buddy.php', 'Window', 250, 300);\">Launch Buddy List</a></li><br>";
   $boxstuff .= "<li><a href=\"misc.php?action=logout\">Log out</A></li></td></tr>";
   themesidebox($title, $boxstuff);
   }
}

function userblock() {
    global $status, $xmbuser, $table_members;
	$sql = "SELECT ublockon, ublock FROM $table_members WHERE username='$xmbuser'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
    if($row['ublockon'] !="0" AND $status == "Member" || $status == "Moderator" || $status == "Super Moderator" || $status == "Super Administrator" || $status == "Administrator") {
	$title = "Menu for $xmbuser";
	themesidebox($title, $row['ublock']);
    }
}

function getTopics($s_sid) {
    global $topicname, $topicimage, $topictext, $prefix;
    $storyid = $s_sid;
    $sql = "SELECT topic FROM ".$prefix."_stories WHERE storyid='$storyid'";
    $resquery = mysql_query($sql);
    $row = mysql_fetch_array($resquery);
    $sql = "SELECT topicid, topicname, topicimage, topictext FROM ".$prefix."_topics WHERE topicid='$row[topic]'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $topicid = $row[topicid];
    $topicname = $row[topicname];
    $topicimage = $row[topicimage];
    $topictext = $row[topictext];
}

function getusrinfo($xmbuser) {
    global $userinfo, $table_members;
    $sql = "SELECT * FROM $table_members WHERE username='$xmbuser'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) == 1) {
    	$userinfo = mysql_fetch_array($result);
    }
    return $userinfo;
}

function cookiedecode($xmbuser) {
    global $cookie, $table_members;
    $xmbuser = base64_decode($xmbuser);
    $cookie = explode(":", $xmbuser);
    $sql = "SELECT * FROM $table_members WHERE username='$cookie[1]'";
    $result = mysql_query($sql);
    $row = mysql_num_rows($result);
    $pass = $row['password'];
    if($cookie[2] == $pass && $pass != "") {
	return $cookie;
    }else{
	unset($xmbuser);
	unset($cookie);
    }
}

function automated_news() {
    global $prefix;
    $today = getdate();
    $day = $today[mday];
    if ($day < 10) {
	$day = "0$day";
    }
    $month = $today[mon];
    if ($month < 10) {
	$month = "0$month";
    }
    $year = $today[year];
    $hour = $today[hours];
    $min = $today[minutes];
    $sec = "00";
    $sql = "SELECT anid, time FROM ".$prefix."_autonews";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
	$anid = $row[anid];
	$time = $row[time];
	ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $date);
	if (($date[1] <= $year) AND ($date[2] <= $month) AND ($date[3] <= $day)) {
    if (($date[4] < $hour) AND ($date[5] >= $min) OR ($date[4] <= $hour) AND ($date[5] <= $min)) {
	$sql2 = "SELECT * FROM ".$prefix."_autonews WHERE anid='$anid'";
	$result2 = mysql_query($sql2);
	while ($row2 = mysql_fetch_array($result2)) {
    $title = stripslashes(FixQuotes($row2[title]));
    $hometext = stripslashes(FixQuotes($row2[hometext]));
    $bodytext = stripslashes(FixQuotes($row2[bodytext]));
    $notes = stripslashes(FixQuotes($row2[notes]));
    $sql = "INSERT INTO ".$prefix."_stories VALUES (NULL, '$row2[catid]', '$row2[aid]', '$title', '$row2[time]', '$hometext', '$bodytext', '0', '0', '$row2[topic]', '$row2[informant]', '$notes', '$row2[ihome]', '$row2[acomm]', '0', '0', '0', '0', '')";
    mysql_query($sql);
    $sql = "DELETE FROM ".$prefix."_autonews WHERE anid='$anid'";
    mysql_query($sql);
		   }
	     }
	   }
    }
}

function themecenterbox($title, $content) {
    global $bordercolor, $borderwidth, $tablespace, $imgdir, $langfile, $catbgcode, $altbg2, $bbname, $altbg1, $cattext, $bgcode;
    BeginTable();
    echo "<td class=\"category\" $catbgcode><b>» $title </b></td></tr><tr>\n"
	."<td bgcolor=\"$altbg2\" class=\"mediumtxt\">$content";
    EndTable();
    echo "<br>";
}

function removecrlf($str) {
    return strtr($str, "\015\012", ' ');
}
if(empty($copyright)) {
die("you are not authorized to play with the codes");
}
function newsblock() {
        global $content, $bordercolor, $borderwidth, $tablespace, $imgdir, $langfile, $catbgcode, $altbg2, $bbname, $altbg1, $cattext, $table_forums, $table_posts, $table_whosonline, $table_members, $table_threads, $news_fid, $news_list_this_many, $date_format, $smdir, $msg_length_limit, $dgif, $portal_images, $timeoffset;
        require "./lang/$langfile.lang.php";
        smcwcache();
        $query = mysql_query("SELECT * FROM $table_forums WHERE fid='$news_fid'");
        $forum = mysql_fetch_array($query);
        $query = mysql_query("SELECT p.*, m.*,w.time, t.subject, t.views, t.replies, t.closed, t.pollopts, t.topped FROM $table_posts p LEFT JOIN $table_members m ON m.username=p.author LEFT JOIN $table_whosonline w ON p.author=w.username LEFT JOIN $table_threads t ON p.tid=t.tid WHERE p.fid='$news_fid'  GROUP BY (tid) ORDER BY t.fid, p.dateline DESC LIMIT 0,$news_list_this_many");
        while ($news = mysql_fetch_array($query)) {
	  	$readmore = "";
		$tid = "";
        $author = $news['author'];
        $tid = $news['tid'];
	    $date = date("$date_format");
        $news['subject'] = censor($news['subject']);
        $news['message'] = censor($news['message']);
	  	$news['message'] = postify($news['message'], $news['smileyoff'], $news['bbcodeoff'], $forum['allowsmilies'], $forum['allowhtml'], $forum['allowbbcode'], $forum['allowimgcode']);
        $subject = $news['subject'];
	  	$subject = stripslashes($subject);
        $message = stripslashes($news['message']);
	  	$message_o = $message;
        $message = substr ($message, 0, $msg_length_limit);
        if ($message_o != $message) {
			$message .= "... <br><font class=\"smalltxt\">[ <a href=\"viewthread.php?tid=$tid\"><b>Read more....</b></a> ]</font>";
	  	}

        if($news['username'] != ''){
            $onlinenow = "Member Is <b>Online</b>";
        }else{
            $onlinenow = "Member Is Offline";
        }
       $comments = $news['replies'];
	   if ($comments == "1") {
	   $comment_txt = "Reply";
	   }else{
	   $comment_txt = "Replies";
	   }
	   if ($comments > 0) {
		$comments = "<a href=\"viewthread.php?tid=$tid\">$comments $comment_txt</a>";
	  } else {
		$comments = "No Replies";
	  }
    	if($news['icon'] != "") {
		$icon = "<img src=\"$smdir/$news[icon]\" />";
	    } else {
		$icon = "<img src=\"$smdir/$dgif\">";
	   }

       if($news['closed'] == "yes") {
        $pertxt = "Closed";
		$postcomment = "<b>topic Closed</b>";
	    } else {
		$postcomment = "<a href=\"post.php?action=reply&fid=$news_fid&tid=$tid\">[Reply]</a>";
        $pertxt = '';
	   }
       $views = $news['views'];
       if($news['pollopts'] !='') {
         $pertxt = "$lang_poll ";
       }elseif($news['topped'] == 1) {
		$pertxt = "$lang_toppedprefix ";
       }else{
         $pertxt = '';
       }
        echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"><tr><td width=\"80%\" align=\"left\">\n"
          ."<font class=\"smalltxt\"><img src=\"$portal_images/printable.gif\" border=\"0\" alt=\"Printable\">&nbsp;<a href=\"viewthread.php?action=printable&fid=$news_fid&tid=$tid\">Printable</a> | <img src=\"$portal_images/send_to_friend.gif\" border=\"0\" alt=\"Send to a Friend\">&nbsp;<a href=\"emailfriend.php?tid=$tid\">Send to a Friend </a> | <img src=\"$portal_images/subscribe.gif\" border=\"0\" alt=\"Subscribe\">&nbsp;<a href=\"memcp.php?action=subscriptions&subadd=$tid\">Subscribe</a></font>\n"
          ."</td><td width=\"20%\" align=\"right\"><font class=\"smalltxt\">$postcomment</font></td></tr></table>\n"
          ."<table <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
          ."<tr><td bgcolor=\"$bordercolor\">\n"
          ."<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" height=\"69\">\n"
          ."<tr><td width=100% class=\"header\" height=\"19\"><table width=100% border=0 cellpadding=0 cellspacing=0><tr>\n"
          ."<td class=\"category\" width=\"5%\">$icon</td>\n"
          ."<td class=\"category\" align=\"left\" width=\"95%\"> <font color=\"$cattext\"> <b> » </b></font>  $pertxt<b><a href=\"viewthread.php?tid=$tid\"> <font color=\"$cattext\"> $subject</font></a></b></td></tr>\n"
          ."</table></td></tr><tr><td class=\"tablerow\" width=100% bgcolor=\"$altbg2\" height=\"23\">$message\n"
          ."<tr><td bgcolor=\"$altbg1\"><table width=100% border=0 cellpadding=0 cellspacing=0><tr><td width=\"19%\" height=\"20\" bgcolor=\"$altbg1\">\n"
          ."<p align=\"left\"><font class=\"smalltxt\">$comments | <b>Views:</b> $views</font></td><td width=\"33%\" height=\"20\" bgcolor=\"$altbg1\" align=\"left\">\n"
          ."<p><font class=\"smalltxt\"><b>Posted by:</b> <a href=\"member.php?action=viewpro&member=$author\">($author</a>|$onlinenow)</font>\n"
          ."</td><td width=\"21%\" height=\"20\" bgcolor=\"$altbg1\" align=\"right\"><p><font class=\"smalltxt\"><b>Date:</b> $date</font></td>\n"
          ."</tr></table></td></tr></td></tr></table></td></tr></table><br />\n";
    }
    echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" bgcolor=\"$bordercolor\" align=\"center\">\n"
        ."<tr><td><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\"><tr>\n"
        ."<td width=100% bgcolor=\"$altbg1\" align=\"right\"><a href=\"forumdisplay.php?fid=$news_fid\"><font class=\"mediumtxt\"><b>Archived News »</b></font></a>\n"
        ."</td></tr></table></td></tr></table><br>\n";
}

function online() {
    global $table_members, $table_whosonline, $xmbuser, $langfile, $bordercolor, $borderwidth, $tablespace, $imgdir, $langfile, $catbgcode, $altbg2, $bbname, $altbg1, $cattext, $tablewidth;
    require "./lang/$langfile.lang.php";
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
        echo "<center><br><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"$tablewidth\"><tr>
              <td bgcolor=\"$bordercolor\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
              <tr><td class=\"tablerow\" colspan=\"2\" width=\"100%\">
              <table cellspacing=\"1\" cellpadding=\"$tablespace\" border=\"0\" width=\"100%\" align=\"center\">
              <tr><td class=\"category\" $catbgcode colspan=\"2\"><p align=\"left\">
              <b><a href=\"misc.php?action=online\"><font color=\"$cattext\">$lang_whosonline</font></a><font color=\"$cattext\"> - $memonmsg</font></b></td>
              </tr><tr><td bgcolor=\"$altbg2\" colspan=\"2\" class=\"mediumtxt\">$lang_key<u><b><i>$lang_superadmin</u></i></b> -
              <u><b>$lang_textsendadmin</u></b> -
              <b><i>$lang_textsendsupermod</i></b> -
              <b>$lang_textsendmod</b> - $lang_textsendall</td></tr><tr>
              <td bgcolor=\"$altbg1\" align=\"center\" class=\"tablerow\" rowspan=\"4\" width=\"5%\"><img src=\"$imgdir/online.gif\" /></td>
              <td bgcolor=\"$altbg2\" class=\"mediumtxt\">$memtally</td>
              </tr><tr><td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"left\"><b>[<a href=\"misc.php?action=onlinetoday\">+</a>] $lang_last50today</b></td>
              </tr></td></tr><tr>
              <td bgcolor=\"$altbg2\" class=\"mediumtxt\">$todaymembers </td></tr></table></td></tr></table></table><br></center>";
}

/*=====================================*/
/*  End Portal Functions               */
/*====================================*/
?>
