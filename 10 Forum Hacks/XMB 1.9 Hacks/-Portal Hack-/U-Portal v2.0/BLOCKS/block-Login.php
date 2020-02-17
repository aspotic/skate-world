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

if (eregi("block-Login.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

global $xmbuser, $table_members, $portal_invisible, $portal_secure, $table_themes, $table_u2u;
    if (!$xmbuser) {
               $content = "<tr><td align=\"center\" bgcolor=\"$altbg2\" colspan=\"4\" class=\"mediumtxt\">Welcome, <b>Guest!</b><br>Please Login:</td></tr>";
               $content .= "<form action=\"misc.php?action=login\" method=\"post\">";
               $content .= "<tr><td bgcolor=\"$altbg1\"><input name=\"username\" size=\"10\" style=\"float: left\"></td>";
               $content .= "<td class=\"tablerow\" bgcolor=\"$altbg1\"><a href=\"member.php?action=reg\">$lang_regques</a></td></tr>";
               $content .= "<tr><td bgcolor=\"$altbg1\"><input type=\"password\" name=\"password\" size=\"10\" style=\"float: left\"></td>";
               $content .= "<td class=\"tablerow\" bgcolor=\"$altbg1\"><a href=\"misc.php?action=lostpw\">$lang_forgotpw</a></td></tr>";
    if($portal_invisible == "on") {
               $content .= "<tr class=\"tablerow\"><td bgcolor=\"$altbg1\">Browse invisible</td>";
               $content .= "<td bgcolor=\"$altbg1\"><input type=\"checkbox\" name=\"hide\" value=\"1\"></td></tr>";
    }
    if($portal_secure == "on"){
               $content .= "<tr class=\"tablerow\"><td bgcolor=\"$altbg1\">Secure Login</td>";
               $content .= "<td bgcolor=\"$altbg1\"><input type=\"checkbox\" name=\"secure\" value=\"yes\"></td></tr>";
    }
    
               $content .= "<tr><td bgcolor=\"$altbg2\" colspan=\"4\" align=\"center\"><input type=\"submit\" name=\"loginsubmit\" value=\"Login\"><br>";
               $content .= "</td></tr></form>";
}elseif($xmbuser AND $xmbuser != ""){

        $blockfiletitle = "Logged in as $xmbuser";
        $query = mysql_query("SELECT * FROM $table_members WHERE username='$xmbuser'");
        $member = mysql_fetch_array($query);
               
		$query = mysql_query("SELECT * FROM $table_u2u WHERE msgto = '$xmbuser' AND folder = 'inbox' AND new = 'yes'");
		$newu2unum = mysql_num_rows($query);
		if($newu2unum > 0) {
			$newu2umsg = "<a href=\"#\" onclick=\"Popup('u2u.php', 'Window', 550, 450);\">$lang_newu2u1 $newu2unum $lang_newu2u2</a></br>";
		}else{
			$newu2umsg = '';
		}
  
        $themelist = "<select name=\"thememem\">\n<option value=\"\">$lang_textusedefault</option>";
        $query2 = mysql_query("SELECT name FROM $table_themes");
        while($theme = mysql_fetch_array($query2)) {
        if($theme[name] == $member[theme]) {
        $themelist .= "<option value=\"$theme[name]\" selected=\"selected\">$theme[name]</option>\n";
        }else{
        $themelist .= "<option value=\"$theme[name]\">$theme[name]</option>\n";
        }
		}

		$themelist  .= "</select>";
  
		$langfileselect = "<select name=\"langfilenew\">\n";
		$dir = opendir("lang");
		while ($thafile = readdir($dir)) {
		if(is_file("lang/$thafile") && strstr($thafile, '.lang.php')) {
		$thafile = str_replace(".lang.php", "", $thafile);
		if($thafile == "$member[langfile]") {
		$langfileselect .= "<option value=\"$thafile\" selected=\"selected\">$thafile</option>\n";
		}else{
		$langfileselect .= "<option value=\"$thafile\">$thafile</option>\n";
		}
		}
		}
		$langfileselect .= "</select>";
  
              $content = "<tr><td bgcolor=\"$altbg2\" class=\"mediumtxt\" colspan=\"2\">$avatar$newu2umsg";
              $content .= "<li><a href=\"memcp.php\">Your User Control Panel</A></li><br>";
              $content .= "<li><a href=\"memcp.php?action=profile\">Edit Your Profile</a></li><br>";
              $content .= "<li><a href=\"memcp.php?action=options\">Your Forum Options</a></li><br>";
              $content .= "<li><a href=\"#\" onclick=\"Popup('buddy.php', 'Window', 250, 300);\">Launch Buddy List</a></li><br>";
              $content .= "<li><a href=\"misc.php?action=logout\">Log out</A></li></td></tr>";
              
              $content .= "<form method=\"post\" action=\"memcp.php?action=profile\" name=\"reg\">";
              $content .= "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><b>$lang_texttheme</b></td></tr>";
              $content .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\">$themelist </td></tr>";
              $content .= "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><b>$lang_textlanguage</b></td></tr>";
              $content .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\">$langfileselect </td></tr>";
              $content .= "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><b>$lang_memcpmood</b></td></tr>";
              $content .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><input type=\"text\" name=\"newmood\" size=\"20\" value=\"$member[mood]\"></textarea></td></tr>";
              $content .= "<tr><td bgcolor=\"$altbg2\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"editsubmit\" value=\"$lang_texteditpro\"/></td></tr></form>";
              
}

?>
