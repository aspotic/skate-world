<?php
/* $Id: memcp.php,v 1.3 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require "header.php";

loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','memcp_profile_avatarurl','memcp_profile_avatarlist','memcp_profile','memcp_favs_row','memcp_favs_none','memcp_favs','memcp_subscriptions_row','memcp_subscriptions_none','memcp_subscriptions','buddylist_buddy_online','buddylist_buddy_offline','memcp_home_u2u_row','memcp_home_u2u_none','memcp_home','memcp_home_favs_none','header','footer','css');
eval("\$css = \"".template('css')."\";");

smcwcache();

if (empty($action)) {
    $action = NULL;
} else {
    $action = $action;
}

$favs = NULL;
$buddys = NULL;

// Determine the navigation
if($action == "profile"){
    $memberaction = "<a href=\"memcp.php\">" .$lang['textusercp']. "</a> &raquo; " .$lang['texteditpro']. "";
}elseif($action == "subscriptions"){
    $memberaction = "<a href=\"memcp.php\">" .$lang['textusercp']. "</a> &raquo; " .$lang['textsubscriptions']. "";
}elseif($action == "favorites"){
    $memberaction = "<a href=\"memcp.php\">" .$lang['textusercp']. "</a> &raquo; " .$lang['textfavorites']. "";
}else{
    $memberaction = $lang['textusercp'];
}

$navigation = " &raquo; " .$memberaction. "";


// Make the user CP Nav Bar
function makenav($current) {
    global $bordercolor, $tablewidth, $borderwidth, $tablespacing, $altbg1, $altbg2, $lang;

    ?>
    <table cellpadding="0" cellspacing="0" border="0" bgcolor="<?=$bordercolor?>" width="<?=$tablewidth?>" align="center"><tr><td>
    <table cellpadding="4" cellspacing="1" border="0" width="100%">
    <tr align="center" class="tablerow">

    <?php
    if($current == "") {
        echo "<td bgcolor=\"$altbg1\" width=\"15%\" class=\"ctrtablerow\">" .$lang['textmyhome']. "</td>";
    } else {
        echo "<td bgcolor=\"$altbg2\" width=\"15%\" class=\"ctrtablerow\"><a href=\"memcp.php\">" .$lang['textmyhome']. "</a></td>";
    }

    if($current == "profile") {
        echo "<td bgcolor=\"$altbg1\" width=\"15%\" class=\"ctrtablerow\">" .$lang['texteditpro']. "</td>";
    } else {
        echo "<td bgcolor=\"$altbg2\" width=\"15%\" class=\"ctrtablerow\"><a href=\"memcp.php?action=profile\">" .$lang['texteditpro']. "</a></td>";
    }

    if($current == "subscriptions") {
        echo "<td bgcolor=\"$altbg1\" width=\"15%\" class=\"ctrtablerow\">" .$lang['textsubscriptions']. "</td>";
    } else {
        echo "<td bgcolor=\"$altbg2\" width=\"15%\" class=\"ctrtablerow\"><a href=\"memcp.php?action=subscriptions\">" .$lang['textsubscriptions']. "</a></td>";
    }

    if($current == "favorites") {
        echo "<td bgcolor=\"$altbg1\" width=\"15%\" class=\"ctrtablerow\">" .$lang['textfavorites']. "</td>";
    } else {
        echo "<td bgcolor=\"$altbg2\" width=\"15%\" class=\"ctrtablerow\"><a href=\"memcp.php?action=favorites\">" .$lang['textfavorites']. "</a></td>";
    }

    echo "<td bgcolor=\"$altbg2\" width=\"20%\" class=\"ctrtablerow\"><a href=\"#\" onclick=\"Popup('u2u.php', 'Window', 700, 450);\">" .$lang['textu2umessenger']. "</a></td>";
    echo "<td bgcolor=\"$altbg2\" width=\"15%\" class=\"ctrtablerow\"><a href=\"#\" onclick=\"Popup('buddy.php?', 'Window', 450, 400);\">" .$lang['textbuddylist']. "</a></td>";
    echo "<td bgcolor=\"$altbg2\" width=\"10%\" class=\"ctrtablerow\"><a href=\"faq.php\">" .$lang['helpbar']. "</a></td>";

    ?>
    </tr>
    </table>
    </td></tr></table><br />

    <?php
}


// Determine if user is logged in, if not send to login page

if(!$xmbuser || !$xmbpw) {
    redirect('misc.php?action=login');
    exit();
}

// Start Profile Editor Code
if($action == "profile") {
    eval("\$header = \"".template("header")."\";");
    echo $header;
    makenav($action);

    if(!$editsubmit) {
        $member = $self;

        // BEGIN SHOP HACK

if($member['glowcolorstatus'] == "on") {
	$glowcoloron = "selected";
} else {
	$glowcoloroff = "selected";
}

if($member['hexcolorstatus'] == "on") {
	$hexcoloron = "selected";
} else {
	$hexcoloroff = "selected";
}

if($member['pphotostatus'] == "on") {
	$pphotoon = "selected";
} else {
	$pphotooff = "selected";
}

// END SHOP HACK

        if($member['showemail'] == "yes") {
            $checked = "checked=\"checked\"";
        }

        if($member['newsletter'] == "yes") {
            $newschecked = "checked=\"checked\"";
        }

        if($member['useoldu2u'] == "yes") {
            $uou2uchecked = "checked=\"checked\"";
        }

        if($member['saveogu2u'] == "yes") {
            $ogu2uchecked = "checked=\"checked\"";
        }

        if($member['emailonu2u'] == "yes") {
            $eouchecked = "checked=\"checked\"";
        }

        if($member['invisible'] == 1) {
            $invchecked = "checked=\"checked\"";
        }

        $currdate = gmdate("$timecode", time()+ ($addtime * 3600));
        eval($lang['evaloffset']);

        if($member['timeoffset'] == "-12") { $timezone1 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-11") { $timezone2 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-10") { $timezone3 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-9") { $timezone4 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-8") { $timezone5 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-7") { $timezone6 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-6") { $timezone7 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-5") { $timezone8 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-4") { $timezone9 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-3.5") { $timezone10 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-3") { $timezone11 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-2") { $timezone12 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "-1") { $timezone13 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "0") { $timezone14 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "1") { $timezone15 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "2") { $timezone16 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "3") { $timezone17 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "3.5") { $timezone18 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "4") { $timezone19 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "4.5") { $timezone20 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "5") { $timezone21 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "5.5") { $timezone22 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "5.75") { $timezone23 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "6") { $timezone24 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "6.5") { $timezone25 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "7") { $timezone26 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "8") { $timezone27 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "9") { $timezone28 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "9.5") { $timezone29 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "10") { $timezone30 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "11") { $timezone31 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "12") { $timezone32 = "selected=\"selected\""; }
        elseif($member['timeoffset'] == "13") { $timezone33 = "selected=\"selected\""; }

        $themelist = "<select name=\"thememem\">\n<option value=\"0\">" .$lang['textusedefault']. "</option>";
        $query = $db->query("SELECT themeid, name FROM $table_themes");
        while($theme = $db->fetch_array($query)) {
            if($theme['themeid'] == $member['theme']) {
                $themelist .= "<option value=\"" .$theme['themeid']. "\" selected=\"selected\">" .$theme['name']. "</option>\n";
            }else{
                $themelist .= "<option value=\"" .$theme['themeid']. "\">" .$theme['name']. "</option>\n";
            }
        }

        $themelist  .= "</select>";


        $langfileselect = "<select name=\"langfilenew\">\n";
        $dir = opendir("lang");
        while ($thafile = readdir($dir)) {
            if(is_file("lang/$thafile") && false !== strpos($thafile, '.lang.php')) {
                $thafile = str_replace(".lang.php", "", $thafile);
                if($thafile == $member['langfile']) {
                    $langfileselect .= "<option value=\"" .$thafile. "\" selected=\"selected\">" .$thafile. "</option>\n";
                }else{
                    $langfileselect .= "<option value=\"" .$thafile. "\">" .$thafile. "</option>\n";
                }
            }
        }
        $langfileselect .= "</select>";

        $member['bday'] = str_replace(",", "", $member['bday']);
        $bday = explode(" ", $member['bday']);

        if($bday['0'] == "") {
            $sel0 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textjan']) {
            $sel1 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textfeb']) {
            $sel2 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textmar']) {
            $sel3 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textapr']) {
            $sel4 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textmay']) {
            $sel5 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textjun']) {
            $sel6 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textjul']) {
            $sel7 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textaug']) {
            $sel8 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textsep']) {
            $sel9 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textoct']) {
            $sel10 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textnov']) {
            $sel11 = "selected=\"selected\"";
        } elseif($bday['0'] == $lang['textdec']) {
            $sel12 = "selected=\"selected\"";
        }

        $dayselect = "<select name=\"day\">\n";
        $dayselect .= "<option value=\"\">&nbsp;</option>\n";
        for($num = 1; $num <= 31; $num++) {
            if($bday[1] == $num) {
                $dayselect .= "<option value=\"" .$num. "\" selected=\"selected\">" .$num. "</option>\n";
            }else{
                $dayselect .= "<option value=\"" .$num. "\">" .$num. "</option>\n";
            }
        }
        $dayselect .= "</select>";

        if($member['timeformat'] == "24") {
            $check24 = "checked=\"checked\"";
        } else {
            $check12 = "checked=\"checked\"";
        }

        if($sigbbcode == "on") {
            $bbcodeis = $lang['texton'];
        } else {
            $bbcodeis = $lang['textoff'];
        }

        if($sightml == "on") {
            $sigallowhtml = "yes";
            $htmlis = $lang['texton'];
        } else {
            $sigallowhtml = "no";
            $htmlis = $lang['textoff'];
        }

        if($avastatus == "on") {
            eval("\$avatar = \"".template("memcp_profile_avatarurl")."\";");
        }

		        // Gender
            if($member['gender'] == $lang[genderm]){ $gender_m = "selected=\"selected\""; }
            elseif($member['gender'] == $lang[genderf]){ $gender_f = "selected=\"selected\""; }
            else{ $gender_n = "selected=\"selected\""; }
		

        if($avastatus == "list") {
            $avatars = " <option value=\"\" />" .$lang['textnone']. "</option>  ";
            $dir1 = opendir("images/avatars");
            while ($avatar1 = readdir($dir1)) {
                if (is_file("images/avatars/" .$avatar1. "")) {
                    $avatars .= " <option value=\"images/avatars/" .$avatar1. "\" />" .$avatar1. "</option>  ";
                }
            }

            $avatars = str_replace("value=\"" .$member['avatar']. "\"", "value=\"" .$member['avatar']. "\" SELECTED", $avatars);
            $avatarbox = "<select name=\"newavatar\" onchange=\"document.images.avatarpic.src =this[this.selectedIndex].value;\">" .$avatars. "</select>";
            eval("\$avatar = \"".template("memcp_profile_avatarlist")."\";");
            closedir($dir1);
        }

        eval("\$profile = \"".template("memcp_profile")."\";");
        $profile = stripslashes($profile);
        echo $profile;
    }

    if($editsubmit) {
        //$query = $db->query("SELECT * FROM $table_members WHERE username='$xmbuser'");
        //$member = $db->fetch_array($query);
        reset($self);
        $member = $self;

        if(!$member['username']) {
            error($lang['badname'], false);
        }

        if($xmbpw != $member['password']) {
            error($lang['textpwincorrect'], false);
        }

        if($showemail != "yes") {
            $showemail = "no";
        }

        if($newsletter != "yes") {
            $newsletter = "no";
        }

        if($saveogu2u != "yes") {
            $saveogu2u = "no";
        }

        if($emailonu2u != "yes") {
            $emailonu2u = "no";
        }

        if($useoldu2u != "yes") {
            $useoldu2u = "no";
        }

        $bday = "$month $day, $year";

        if($month == "" || $day == "" || $year == "") {
            $bday = "";
        }

    $newavatar = ereg_replace(' ', '%20', $newavatar); // Problem with spaces in avatar url

        $avatar         = checkInput($newavatar, '', '', 'javascript', false);
        $memlocation        = checkInput($newmemlocation, '', '', 'javascript', false);
        $icq            = checkInput($newicq, '', '', 'javascript', false);
        $yahoo          = checkInput($newyahoo, '', '', 'javascript', false);
        $aim            = checkInput($newaim, '', '', 'javascript', false);
        $msn            = checkInput($newmsn, '', '', 'javascript', false);
        $email          = checkInput($newemail, '', '', 'javascript', false);
        $site           = checkInput($newsite, '', '', 'javascript', false);
        $webcam         = checkInput($newwebcam, '', '', 'javascript', false);
        $bio            = checkInput($newbio, '', '', 'javascript', false);
        $bday           = checkInput($bday, '', '', 'javascript', false);
        $mood           = checkInput($newmood, '', '', 'javascript', false);
        $pstatus        = checkInput($newpstatus, '', '', 'javascript', false);
        $sig            = checkInput($newsig, '', $SETTINGS['sightml'], '', false);

        $sig            = addslashes($sig);
        $bio            = addslashes($bio);
        $memlocation        = addslashes($memlocation);

        $invisible      = ($newinv == 1) ? 1 : 0;
        $showemail      = ($newshowemail == 'yes') ? 'yes' : 'no';
        $newsletter         = ($newnewsletter == 'yes') ? 'yes' : 'no';

        $size = @getimagesize($avatar);
        $max_size = explode('x', $SETTINGS['max_avatar_size']);
        if($size === false){
            $avatar = '';
        }elseif($size['0'] > $max_size['0'] || $size['1'] > $max_size['1']) {
            error($lang['avatar_too_big'] . $SETTINGS['max_avatar_size'] . 'px', false);
        }

        if(trim($newpassword) != "") {
            if(ereg('"', $newpassword) || ereg("'", $newpassword)) {
                error($lang['textpwincorrect'], false);
            }

            $newpassword = md5($newpassword);

            $pwtxt = "password='$newpassword',";

            $currtime = time() - (86400*30);
            setcookie("xmbuser", $username, $currtime, $cookiepath, $cookiedomain);
            setcookie("xmbpw", $password, $currtime, $cookiepath, $cookiedomain);
        }else{
            $pwtxt = '';
        }

        $db->query("UPDATE $table_members SET $pwtxt email='$email', site='$site', aim='$aim', location='$memlocation', bio='$bio', sig='$sig', showemail='$showemail', timeoffset='$timeoffset1', icq='$icq', avatar='$avatar', yahoo='$yahoo', theme='$thememem', bday='$bday', langfile='$langfilenew', tpp='$tppnew', ppp='$pppnew', newsletter='$newsletter', timeformat='$timeformatnew', msn='$msn', dateformat='$dateformatnew', mood='$mood', invisible='$invisible', saveogu2u='$saveogu2u', emailonu2u='$emailonu2u', useoldu2u='$useoldu2u', webcam='$webcam' , glowcolorstatus='$glowcolorstatus', hexcolorstatus='$hexcolorstatus', pphotostatus='$pphotostatus', gender='$gender' WHERE username='$xmbuser'");
        

        echo "<center><span class=\"mediumtxt \">" .$lang['usercpeditpromsg']. "</span></center>";
        redirect('memcp.php', 12.5, X_REDIRECT_JS);
    }
}

// Start Favorites
elseif($action == "favorites") {
    eval("\$header = \"".template("header")."\";");
    echo $header;

    makenav($action);

    if($favadd && !$favsubmit) {
        $query = $db->query("SELECT tid FROM $table_favorites WHERE tid='$favadd' AND username='$xmbuser' AND type='favorite'");
        $favthread = $db->fetch_array($query);

        if($favthread) {
            error($lang['favonlistmsg'], false);
        }

        $db->query("INSERT INTO $table_favorites VALUES ('$favadd', '$xmbuser', 'favorite')");
        echo "<center><span class=\"mediumtxt \">" .$lang['favaddedmsg']. "</span></center>";
        redirect('memcp.php?action=favorites');
    }

    if(!$favadd && !$favsubmit) {
        $query = $db->query("SELECT * FROM $table_favorites f, $table_threads t, $table_posts p WHERE f.tid=t.tid AND p.tid=t.tid AND p.subject=t.subject AND f.username='$xmbuser' AND f.type='favorite' ORDER BY t.lastpost DESC");
        $favnum = 0;
        $favs = '';
        while($fav = $db->fetch_array($query)) {
            $query2 = $db->query("SELECT name, fup, fid FROM $table_forums WHERE fid='$fav[fid]'");
            $forum = $db->fetch_array($query2);
            $lastpost = explode("|", $fav['lastpost']);
            $dalast = $lastpost['0'];


            $lastpost['1'] = "<a href=\"member.php?action=viewpro&amp;member=".rawurlencode($lastpost['1'])."\">$lastpost[1]</a>";

            $lastreplydate = gmdate($dateformat, $lastpost['0'] + ($timeoffset * 3600) + ($addtime * 3600));
            $lastreplytime = gmdate($timecode, $lastpost['0'] + ($timeoffset * 3600) + ($addtime * 3600));
            $lastpost = "" .$lang['lastreply1']. " " .$lastreplydate. " " .$lang['textat']. " " .$lastreplytime. " " .$lang['textby']. " " .$lastpost['1']. "";
            $fav['subject'] = stripslashes($fav['subject']);

            if($fav['icon'] != "") {
                $fav['icon'] = "<img src=\"" .$smdir. "/" .$fav['icon']. "\" />";
            } else {
                $fav['icon'] = "&nbsp;";
            }

            $fav['subject'] = censor($fav['subject']); 
            $favnum++;
            eval("\$favs .= \"".template("memcp_favs_row")."\";");
        }

        if($favnum == 0) {
            eval("\$favs = \"".template("memcp_favs_none")."\";");
        }

        eval("\$favorites = \"".template("memcp_favs")."\";");
        $favorites = stripslashes($favorites);
        echo $favorites;
    }

    if(!$favadd && $favsubmit) {
        $query = $db->query("SELECT tid FROM $table_favorites WHERE username='$xmbuser' AND type='favorite'");
        while($fav = $db->fetch_array($query)) {
            $delete = "delete" .$fav['tid']. "";
            $delete = "${$delete}";
            $db->query("DELETE FROM $table_favorites WHERE username='$xmbuser' AND tid='$delete' AND type='favorite'");
        }

        echo "<center><span class=\"mediumtxt \">" .$lang['favsdeletedmsg']. "</span></center>";
        redirect('memcp.php?action=favorites');
    }
}

// Start Subscriptions
elseif($action == "subscriptions") {
    eval("\$header = \"".template("header")."\";");
    echo $header;

    makenav($action);

    if(!$subadd && !$subsubmit) {
        $query = $db->query("SELECT * FROM $table_favorites f, $table_threads t, $table_posts p WHERE f.tid=t.tid AND p.tid=t.tid AND p.subject=t.subject AND f.username='$xmbuser' AND f.type='subscription' ORDER BY t.lastpost DESC");
        $subnum = 0;
        $subscriptions = '';
        while($fav = $db->fetch_array($query)) {
            $query2 = $db->query("SELECT name, fup, fid FROM $table_forums WHERE fid='$fav[fid]'");
            $forum = $db->fetch_array($query2);
            $lastpost = explode("|", $fav['lastpost']);
            $dalast = $lastpost['0'];


            $lastpost['1'] = "<a href=\"member.php?action=viewpro&amp;member=".rawurlencode($lastpost['1'])."\">$lastpost[1]</a>";

            $lastreplydate = gmdate($dateformat, $lastpost['0'] + ($timeoffset * 3600) + ($addtime * 3600));
            $lastreplytime = gmdate($timecode, $lastpost['0'] + ($timeoffset * 3600) + ($addtime * 3600));
            $lastpost = "" .$lang['lastreply1']. " " .$lastreplydate. " " .$lang['textat']. " " .$lastreplytime. " " .$lang['textby']. " " .$lastpost['1']. "";
            $fav['subject'] = stripslashes($fav['subject']);

            if($fav['icon'] != "") {
                $fav['icon'] = "<img src=\"" .$smdir. "/" .$fav['icon']. "\" />";
            } else {
                $fav['icon'] = "&nbsp;";
            }
            $fav['subject'] = censor($fav['subject']); 
            $subnum++;
            eval("\$subscriptions .= \"".template("memcp_subscriptions_row")."\";");
        }

        if($subnum == 0) {
            eval("\$subscriptions = \"".template("memcp_subscriptions_none")."\";");
        }

        eval("\$page = \"".template("memcp_subscriptions")."\";");
        $page = stripslashes($page);
        echo $page;

    }elseif($subadd && !$subsubmit) {
        $query = $db->query("SELECT count(tid) FROM $table_favorites WHERE tid='$subadd' AND username='$xmbuser' AND type='subscription'");
        if($db->result($query,0) == 1){
            error($lang['subonlistmsg'], false);
        }else{
            $db->query("INSERT INTO $table_favorites VALUES ('$subadd', '$xmbuser', 'subscription')");
            echo "<center><span class=\"mediumtxt \">$lang[subaddedmsg]</span></center>";
            redirect('memcp.php?action=subscriptions');
        }
    }elseif(!$subadd && $subsubmit) {
        $query = $db->query("SELECT tid FROM $table_favorites WHERE username='$xmbuser' AND type='subscription'");
        while($sub = $db->fetch_array($query)) {
            $delete = "delete" .$sub['tid']. "";
            $delete = "${$delete}";
            $db->query("DELETE FROM $table_favorites WHERE username='$xmbuser' AND tid='$delete' AND type='subscription'");
        }

        echo "<center><span class=\"mediumtxt \">" .$lang['subsdeletedmsg']. "</span></center>";
        redirect('memcp.php?action=subscriptions');
    }

}
// Load the Default Page
else {
    eval("\$header = \"".template("header")."\";");
    echo $header;
    eval($lang['evalusercpwelcome']);

    makenav($action);

    // Load Buddy List
    $q = $db->query("SELECT b.buddyname, w.invisible, w.username FROM $table_buddys b LEFT JOIN $table_whosonline w ON (b.buddyname=w.username) WHERE b.username='$xmbuser'");
    $buddys = array();
    if(X_ADMIN) {
        while($buddy = $db->fetch_array($q)) {
            if(strlen($buddy['username']) > 0) {
                if($buddy['invisible'] == 1){
                   $buddystatus = $lang['hidden'];
                }else{
                    $buddystatus = $lang['textonline'];
                }
                eval("\$buddys[online] .= \"".template("buddylist_buddy_online")."\";");
            } else {
                eval("\$buddys[offline] .= \"".template("buddylist_buddy_offline")."\";");
            }
        }
    } else {
        while($buddy = $db->fetch_array($q)) {
            if(strlen($buddy['username']) > 0) {
                if($buddy['invisible'] == 1){
                   eval("\$buddys[offline] .= \"".template("buddylist_buddy_offline")."\";");
                   continue;
                }else{
                    $buddystatus = $lang['textonline'];
                }
                eval("\$buddys[online] .= \"".template("buddylist_buddy_online")."\";");
            } else {
                eval("\$buddys[offline] .= \"".template("buddylist_buddy_offline")."\";");
            }
        }
    }

    $query = $db->query("SELECT * FROM $table_members WHERE username='$xmbuser'");
    $member = $db->fetch_array($query);
    if($member['avatar'] == "") {
        $member['avatar'] = "&nbsp;";
    } else {
        $member['avatar'] = "<img src=\"" .$member['avatar']. "\" border=\"0\" alt=\"$lang[altavatar]\" />";
    }

    if($member['mood'] != ''){
        smcwcache();
        $member['mood'] = postify($member['mood'], 'no', 'no', 'yes', 'no', 'yes', 'no', true, 'yes');
    }else{
        $member['mood'] = '&nbsp;';
    }


// Make the Page
    $query = $db->query("SELECT * FROM $table_u2u WHERE owner='$xmbuser' AND type='incoming' ORDER BY dateline DESC LIMIT 0, 15");
    $u2unum = $db->num_rows($query);
    $messages = '';
    while($message = $db->fetch_array($query)) {
        $postdate = gmdate("$dateformat",$message['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
        $posttime = gmdate("$timecode",$message['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
        $senton = "" .$postdate. " " .$lang['textat']. " " .$posttime. "";

        if($message['subject'] == '') {
            $message['subject'] = "&laquo;" .$lang['textnosub']. "&raquo;";
        }

        if($message['readstatus'] == "yes"){
            $read = $lang['textread'];
        } else {
            $read = $lang['textunread'];
        }

        $message['subject'] = stripslashes($message['subject']);
        eval("\$messages .= \"".template("memcp_home_u2u_row")."\";");
    }

    if($u2unum == 0) {
        eval("\$messages = \"".template("memcp_home_u2u_none")."\";");
    }
    $query2 = $db->query("SELECT * FROM $table_favorites f, $table_threads t, $table_posts p WHERE f.tid=t.tid AND p.tid=t.tid AND p.subject=t.subject AND f.username='$xmbuser' AND f.type='favorite' ORDER BY t.lastpost DESC");
    $favnum = $db->num_rows($query2);
    $favs = '';
    while($fav = $db->fetch_array($query2)) {
        $query = $db->query("SELECT name, fup, fid FROM $table_forums WHERE fid='$fav[fid]'");
        $forum = $db->fetch_array($query);
        $lastpost = explode("|", $fav['lastpost']);
        $dalast = $lastpost['0'];
        $lastpost['1'] = "<a href=\"member.php?action=viewpro&amp;member=".rawurlencode($lastpost[1])."\">$lastpost[1]</a>";
        $lastreplydate = gmdate($dateformat, $lastpost['0'] + ($timeoffset * 3600) + ($addtime * 3600));
        $lastreplytime = gmdate($timecode, $lastpost['0'] + ($timeoffset * 3600) + ($addtime * 3600));
        $lastpost = "" .$lang['lastreply1']. " " .$lastreplydate. " " .$lang['textat']. " " .$lastreplytime. " " .$lang['textby']. " " .$lastpost['1']. "";
        $fav['subject'] = stripslashes($fav['subject']);

        if($fav['icon'] != "") {
            $fav['icon'] = "<img src=\"" .$smdir. "/" .$fav['icon']. "\" />";
        } else {
            $fav['icon'] = "&nbsp;";
        }
            $fav['subject'] = censor($fav['subject']); 
        eval("\$favs .= \"".template("memcp_home_favs_row")."\";");
    }

    if($favnum == '0') {
        eval("\$favs = \"".template("memcp_home_favs_none")."\";");
    }

    eval("\$home = \"".template("memcp_home")."\";");
    $home = stripslashes($home);
    echo $home;
}

end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>