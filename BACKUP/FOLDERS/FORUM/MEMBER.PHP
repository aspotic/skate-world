<?php
/* $Id: member.php,v 1.5 2004/04/22 02:35:28 jleq Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require "./header.php";

loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','member_coppa','member_reg_rules','member_reg_password','member_reg_avatarurl','member_reg_avatarlist','member_reg','member_profile_email','member_profile','header','footer','css');
eval("\$css = \"".template('css')."\";");

smcwcache();

$memberaction = (($action=='reg') ? ($lang['textregister']) : (($action == 'viewpro') ? ($lang['textviewpro']) : (($action == 'coppa') ? ($lang['textcoppa']) : ($lang['error']))));

$navigation = "&raquo; $memberaction";
if($action == "coppa") {
    if($self['status'] == "Member" || X_STAFF) {
        eval("\$featurelin = \"".template("misc_feature_not_while_loggedin")."\";");
        eval("\$header = \"".template("header")."\";");
        eval("\$footer = \"".template("footer")."\";");
        echo $header;
        echo $featurelin;
        echo $footer;
        exit();
    }

    if($SETTINGS['coppa'] != 'on') {
        redirect("./member.php?action=reg", 0);
    }
    if($coppasubmit) {
        header("Location: member.php?action=reg");
    }else{
        eval("\$header = \"".template("header")."\";");
        echo $header;
        eval("\$page = \"".template("member_coppa")."\";");
        $page = stripslashes($page);
        echo $page;
    }


}elseif($action == "reg") {
    $time = time()-86400; // take the date and distract 24 hours from it
    $max = 999; // Max. amount of users allowed to register per day
    $query = $db->query("SELECT count(uid) FROM $table_members WHERE regdate > '$time'");
    // Select amount of registrations since $time, which is since 24 hours.
     if($db->result($query, 0) > $max) {
             error($lang['max_regs']);
     }

    if($regstatus != "on") {
        eval("\$featureoff = \"".template("misc_feature_notavailable")."\";");
        eval("\$header = \"".template("header")."\";");
        eval("\$footer = \"".template("footer")."\";");
        echo $header;
        echo $featureoff;
        echo $footer;
        exit();
    }

    if($self['status'] == "Member" || X_STAFF) {
        eval("\$featurelin = \"".template("misc_feature_not_while_loggedin")."\";");
        eval("\$header = \"".template("header")."\";");
        eval("\$footer = \"".template("footer")."\";");
        echo $header;
        echo $featurelin;
        echo $footer;
        exit();
    }

    if(!$regsubmit) {
        eval("\$header = \"".template("header")."\";");
        echo $header;
        if($bbrules == "on" && !$rulesubmit) {
            $bbrulestxt = stripslashes(stripslashes($bbrulestxt));
            $bbrulestxt = nl2br($bbrulestxt);
            eval("\$page = \"".template("member_reg_rules")."\";");
            $page = stripslashes($page);
            echo $page;
        }else{

            $newschecked = 'CHECKED';

            $currdate = gmdate($timecode, time()+ ($addtime * 3600));
            eval($lang['evaloffset']);

            $themelist = '<select name="thememem">'."\n".'<option value="">'.$lang['textusedefault'].'</option>';
            $query = $db->query("SELECT themeid, name FROM $table_themes");
            while($themeinfo = $db->fetch_array($query)) {
                $themelist .= "<option value=\"$themeinfo[themeid]\">$themeinfo[name]</option>\n";
            }
            $themelist  .= "</select>";


            $langfileselect = "<select name=\"newlangfile\">\n";
            $dir = opendir("lang");
            while ($thafile = readdir($dir)) {
                if (is_file("lang/$thafile") && false !== strpos($thafile, 'lang.php')) {
                    $thafile = str_replace(".lang.php", "", $thafile);
                    if ($thafile == "$bblang") {
                        $langfileselect .= "<option value=\"$thafile\" selected=\"selected\">$thafile</option>\n";
                    }
                    else{
                        $langfileselect .= "<option value=\"$thafile\">$thafile</option>\n";
                    }
                }
            }
            $langfileselect .= "</select>";


            $dayselect = "<select name=\"day\">\n";
            $dayselect .= "<option value=\"\">&nbsp;</option>\n";
            for($num = 1; $num <= 31; $num++) {
                $dayselect .= "<option value=\"$num\">$num</option>\n";
            }
            $dayselect .= "</select>";

            if($sigbbcode == "on") {
                $bbcodeis = $lang['texton'];
            }else{
                $bbcodeis = $lang['textoff'];
            }

            if($sightml == "on") {
                $htmlis = $lang['texton'];
            }else{
                $htmlis = $lang['textoff'];
            }

            if($emailcheck != "on") {
                eval("\$pwtd = \"".template("member_reg_password")."\";");
            }

            if($avastatus == "on") {
                eval("\$avatd = \"".template("member_reg_avatarurl")."\";");
            }elseif($avastatus == "list") {
                $avatars = '<option value=""/>'.$lang['textnone'].'</option>';
                $dir1 = opendir("images/avatars");
                while($avatar1 = readdir($dir1)) {
                    if(is_file("images/avatars/$avatar1")) {
                        $avatars .= " <option value=\"images/avatars/$avatar1\" />$avatar1</option>  ";
                    }
                }
                closedir($dir1);
                $avatars = str_replace("value=\"$member[avatar]\"", "value=\"$member[avatar]\" selected=\"selected\"", $avatars);

                eval("\$avatd = \"".template("member_reg_avatarlist")."\";");
            }
            eval("\$page = \"".template("member_reg")."\";");
            $page = stripslashes($page);
            echo $page;
        }

    } else {
        $find = array('<', '>', '|', '"', '[', ']', '\\', ',');
        foreach($find as $needle) {
            if(false !== strpos($username, $needle)) {
                error("Invalid Characters in Username ( $needle )");
            }
        }

        if($ipreg != 'off') {
            $time = time()-86400;
            $query = $db->query("SELECT uid FROM $table_members WHERE regip = '$onlineip' AND regdate >= '$time'");
            if($db->num_rows($query) >= 1) {
                error($lang['reg_today']);
            }
        }

        $email = addslashes(trim($email));

        if($doublee == "off" && false !== strpos($email, "@")) {
            $email1 = ", email";
            $email2 = "OR email='$email'";
        }else{
            $email1 = '';
            $email2 = '';
        }

        $username = trim($username);
        $query = $db->query("SELECT username$email1 FROM $table_members WHERE username='$username' $email2");

        if($member = $db->fetch_array($query)) {
            error($lang['alreadyreg']);
        }

        if($emailcheck == "on") {
            $password = '';
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
            mt_srand((double)microtime() * 1000000);
            for($get = strlen($chars); $i < 8; $i++) {
                $password .= $chars[mt_rand(0, $get)];
            }
            $password2 = $password;
        }

        if($password != $password2) {
            error($lang['pwnomatch']);
        }

        $fail = false;
        $efail = false;
        $query = $db->query("SELECT * FROM $table_restricted");
        while($restriction = $db->fetch_array($query)){
            if($restriction['case_sensitivity'] == 1){
                if($restriction['partial'] == 1){
                    if(strpos($username, $restriction['name']) !== false){
                        $fail = true;
                    }
                    if(strpos($email, $restriction['name']) !== false){
                        $efail = true;
                    }
                }else{
                    if($username == $restriction['name']){
                        $fail = true;
                    }
                    if($email == $restriction['name']){
                        $efail = true;
                    }
                }
            }else{
                $t_username = strtolower($username);
                $t_email = strtolower($email);
                $restriction['name'] = strtolower($restriction['name']);

                if($restriction['partial'] == 1){
                    if(strpos($t_username, $restriction['name']) !== false){
                        $fail = true;
                    }
                    if(strpos($t_email, $restriction['name']) !== false){
                        $efail = true;
                    }
                }else{
                    if($t_username == $restriction['name']){
                        $fail = true;
                    }
                    if($t_email == $restriction['name']){
                        $efail = true;
                    }
                }
            }
        }

        if($fail){
            error($lang['restricted']);
        }

        if($efail){
            error($lang['emailrestricted']);
        }

        if(false === strpos($email, "@")) {
            error($lang['bademail']);
        }

        if($password == "" || strpos($password, '"') != false || strpos($password, "'") != false) {
            error($lang['textpw1']);
        }

        if(trim($username) == ''){
            error($lang['textnousername']);
        }

        $query = $db->query("SELECT COUNT(uid) FROM $table_members");
        $count1 = $db->result($query,0);

        if($count1 != "0") {
            $self['status'] = "Member";
        }else{
            $self['status'] = "Super Administrator";
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

        $avatar     = checkInput($avatar, '', '', "javascript", false);
        $dateformatnew    = checkInput($dateformatnew, '', '', "javascript", false);
        $locationnew    = checkInput($locationnew, '', '', "javascript", false);
        $icq        = checkInput($icq, '', '', "javascript", false);
        $yahoo        = checkInput($yahoo, '', '', "javascript", false);
        $aim        = checkInput($aim, '', '', "javascript", false);
        $msn        = checkInput($msn, '', '', "javascript", false);
        $email        = checkInput($email, '', '', "javascript", false);
        $site        = checkInput($site, '', '', "javascript", false);
        $webcam        = checkInput($webcam, '', '', "javascript", false);
        $bio        = checkInput($bio, '', '', "javascript", false);
        $bday        = checkInput($bday, '', '', "javascript", false);
        $mood        = checkInput($newmood, '', '', "javascript", false);
        $sig         = checkInput($_POST['sig']);

        $sig        = addslashes($sig);
        $bio        = addslashes($bio);
        $locationnew    = addslashes($locationnew);

        $password    = md5(trim($password));
// BEGIN REFERRAL HACK HERE
if($refer != "") {
	$checkquery = $db->query("SELECT username FROM $table_members WHERE username='$refer'");
	$memexcheck = $db->fetch_array($checkquery);
	if($memexcheck[username] == "") {
			echo "<center>Member $refer does not exist in the database.</center>";
			exit;
		} else {
			$querycheck = $db->query("SELECT referred FROM $table_members WHERE username='$refer'");
			$refering = $db->fetch_array($querycheck);
			$referlist = $refering[referred];
			if($referlist == "") {
			$referlist .= $username;
			} else {
			$referlist .= ", " . $username;
			}
			$setquery = $db->query("SELECT mpr FROM $table_shop_settings");
$mpr = $db->result($setquery, 0);

$db->query("UPDATE $table_members SET referrals=referrals+1, referred='$referlist', money=money+'$mpr' WHERE username='$refer'");
		}
	}
// END REFERALL HACK HERE

        $size = @getimagesize($avatar);
        $max_size = explode('x', $SETTINGS['max_avatar_size']);
        if($size === false){
            $avatar = '';
        }elseif(($size[0] > $max_size[0] || $size[1] > $max_size[1]) && $self['status'] != 'Super Administrator') {
            error($lang['avatar_too_big'] . $SETTINGS['max_avatar_size'] . 'px');
        }
        $db->query("INSERT INTO $table_members VALUES ('', '$username', '$password', ".$db->time(time()).", '0', '$email', '$site', '$aim', '$self[status]',  '$locationnew', '$bio', '$sig', '$showemail', '$timeoffset1', '$icq', '$avatar', '$yahoo', '', '$thememem', '$bday', '$newlangfile', '$tpp', '$ppp',  '$newsletter', '$onlineip', '$timeformatnew', '$msn', '', '$dateformatnew', '', '', '$newmood', '', '0', '', '$saveogu2u', '$emailonu2u', '$useoldu2u', '$webcam', 0, '', '', '', 'on', 'on', 'on', '0', '$refer', '', '' , '', 0, '0', '$gender', '', '')");


        if($notify == "on") {
            $mailquery = $db->query("SELECT * FROM $table_members WHERE status = 'Administrator'");
            while($notify = $db->fetch_array($mailquery)) {
                mail($notify['email'], $lang['textnewmember'], $lang['textnewmember2'], "From: $bbname <$adminemail>");
            }
        }

        if($emailcheck == "on") {
            mail($email, $lang['textyourpw'], $lang['textyourpwis']." \n\n$username\n$password2", "From: $bbname <$adminemail>");
        }else{
            $currtime = time() + (86400*30);
            setcookie("xmbuser", $username, $currtime, $cookiepath, $cookiedomain);
            setcookie("xmbpw", $password, $currtime, $cookiepath, $cookiedomain);
        }
        eval("\$header = \"".template("header")."\";");
        $header = stripslashes($header);
        echo $header;
        echo ($emailcheck == "on") ? "<center><span class=\"mediumtxt \">$lang[emailpw]</span></center>" : "<center><span class=\"mediumtxt \">$lang[regged]</span></center>";

        redirect('index.php', 2, X_REDIRECT_JS);
    }


}elseif($action == "viewpro") {
    if(!$member) {
        error($lang['nomember']);
    }else{
        if($member == "$lang[bank_name]"){ header("Location: bank.php"); exit(); } 
        $memberinfo = $db->fetch_array($db->query("SELECT * FROM $table_members WHERE username='$member'"));
        if($memberinfo['status'] == 'Administrator' || $memberinfo['status'] == 'Super Administrator' || $memberinfo['status'] == 'Super Moderator' || $memberinfo['status'] == 'Moderator') {
            $limit = "title = '$memberinfo[status]'";
        }else{
            $limit = "posts <= '$memberinfo[postnum]' AND title != 'Super Administrator' AND title != 'Administrator' AND title != 'Super Moderator' AND title != 'Super Moderator' AND title != 'Moderator'";
        }
        $rank = $db->fetch_array($db->query("SELECT * FROM $table_ranks WHERE $limit ORDER BY posts DESC LIMIT 1"));

        if($memberinfo['uid'] == ''){
            error($lang['nomember']);
            end_time();
        }else{
            eval("\$header = \"".template("header")."\";");
            echo $header;

            $daysreg = (time() - $memberinfo['regdate']) / (24*3600);
            $ppd = $memberinfo['postnum'] / $daysreg;
            $ppd = round($ppd, 2);

            $memberinfo['regdate'] = gmdate("n/j/y",$memberinfo['regdate'] + ($addtime * 3600) + ($timeoffset * 3600));

            if(strpos($memberinfo['site'], 'http') === false){
                $memberinfo['site'] = "http://$memberinfo[site]";
            }

            if($memberinfo['site'] != 'http://') {
                $site = $memberinfo['site'];
            }else{
                $site = '';
            }

            // BEGIN SHOP HACK

if($memberinfo['hexcolor'] == "" || $memberinfo['hexcolorstatus'] == "off") {
	$hexcolor1 = "";
	$hexcolor2 = "";
} else {
	$hexcolor1 = "#$memberinfo[hexcolor]";
	$hexcolor2 = "color:#$memberinfo[hexcolor]";
}

if($memberinfo['glowcolor'] == "" || $memberinfo['glowcolorstatus'] == "off") {
	$span1 = "<font color=$hexcolor1>";
	$span2 = "</font>";
} else {
	$glowcolor = "#$memberinfo[glowcolor]";
	$span1 = "<font style=\"width:100%; $hexcolor2; filter:glow(color=$glowcolor, strength=2)\">";
	$span2 = "</font>";
}

$username = "$span1$memberinfo[username]$span2";

if($memberinfo['pphoto'] == "" || $memberinfo['pphotostatus'] == "off") {
	$pphoto = "";
} else {
	eval("\$pphoto = \"".template("member_profile_pphoto")."\";");
}

// END SHOP HACK

            if(strpos($memberinfo['webcam'], 'http') === false){
                $memberinfo['webcam'] = "http://$memberinfo[webcam]";
            }

            if($memberinfo['webcam'] != 'http://') {
                $webcam = $memberinfo['webcam'];
            }else{
                $webcam = '';
            }

			// Gender
                if($memberinfo['gender'] == $lang[genderm]){ $gender = "<img src=\"images/gender_male.gif\" alt=\"" .$lang[genderm]. "\">"; }
                elseif($memberinfo['gender'] == $lang[genderf]){ $gender = "<img src=\"images/gender_female.gif\" alt=\"" .$lang[genderf]. "\">"; }
                else{ $gender = "<img src=\"images/gender_none.gif\">"; $memberinfo['gender'] = $lang['gendern']; }

            if($memberinfo['email'] != "" && $memberinfo['showemail'] == "yes") {
                $email = $memberinfo['email'];
            }else{
                $email = '';
            }

            if($rank['avatarrank'] != "") {
                $avatar = $rank['avatarrank'];
            }else{
                $avatar = $memberinfo['avatar'];
            }

            if($avatar != '') {
                if(isset($site)) {
                    $avatar = "<a href=\"$site\"><img src=\"$memberinfo[avatar]\" border=\"0\" alt=\"$lang[altavatar]\" /></a>";
                }elseif(!isset($site)) {
                    $avatar = "<img src=\"$memberinfo[avatar]\" border=\"0\" alt=\"$lang[altavatar]\" />";
                }
            }

            $showtitle = $rank['title'];
            $stars = '';
            for($i = 0; $i < $rank['stars']; $i++) {
                $stars .= "<img src=\"$imgdir/star.gif\" alt=\"*\" />";
            }

            if($memberinfo['customstatus'] != '') {
                $showtitle = $rank['title'];
                $customstatus = '<br />'.$memberinfo['customstatus'];
            } else {
                $showtitle = $rank['title'];
                $customstatus = '';
            }

            if(!($memberinfo['lastvisit'] > 0)) {
                $lastmembervisittext = $lang['textpendinglogin'];
            }else{
                $lastvisitdate = gmdate("$dateformat",$memberinfo['lastvisit'] + ($timeoffset * 3600) + ($addtime * 3600));
                $lastvisittime = gmdate("$timecode",$memberinfo['lastvisit'] + ($timeoffset * 3600) + ($addtime * 3600));
                $lastmembervisittext = "$lastvisitdate $lang[textat] $lastvisittime";
            }

            $query = $db->query("SELECT COUNT(pid) FROM $table_posts");
            $posts = $db->result($query, 0);

            $posttot = $posts;
            if($posttot == 0) {
                $percent = "0";
            }else{
                $percent = $memberinfo['postnum']*100/$posttot;
                $percent = round($percent, 2);
            }

            $memberinfo['bio'] = stripslashes($memberinfo['bio']);
            $memberinfo['bio'] = nl2br($memberinfo['bio']);
            $encodeuser = rawurlencode($memberinfo['username']);

            if($memberinfo['showemail'] == "yes") {
                eval("\$emailblock = \"".template("member_profile_email")."\";");
            }

			if ($status == "Super Administrator") {
$editawards = " - <a href=\"javascript:void('')\" onclick=\"Popup('awedit.php?action=edit&username=$encodeuser', 'Window', 350, 400);\">Edit Awards</a> ";
}
elseif ($status == "Administrator") {
$editawards = " - <a href=\"javascript:void('')\" onclick=\"Popup('awedit.php?action=edit&username=$encodeuser', 'Window', 350, 400);\">Edit Awards</a> ";
}

            if($self['status'] == "Super Administrator") {
                $admin_edit = "<br />$lang[adminoption] <a href=\"./editprofile.php?user=$encodeuser\">$lang[admin_edituseraccount]</a>";
            }else{
                $admin_edit = NULL;
            }

            if($memberinfo['mood'] != ''){
                smcwcache();
                $memberinfo['mood'] = postify($memberinfo['mood'], 'no', 'no', 'yes', 'no', 'yes', 'no', true, 'yes');
            }else{
                $memberinfo['mood'] = '&nbsp;';
            }

            $restrict = '';
            switch($self['status']){
                case 'member';
                    $restrict .= " f.private !='3' AND";

                case 'Moderator';

                case 'Super Moderator';
                    $restrict .= " f.private != '2' AND";

                case 'Administrator';
                    $restrict .= " f.userlist = '' AND f.password = '' AND";

                case 'Super Administrator';
                    break;

                default:
                    $restrict .= " f.private !='3' AND f.private != '2' AND f.userlist = '' AND f.password = '' AND";
                    break;
            }



            // Forum most active in
            $query = $db->query("SELECT f.name, p.fid, COUNT(DISTINCT p.pid) as posts FROM $table_posts p LEFT JOIN $table_forums f ON p.fid=f.fid WHERE $restrict p.author='$member' GROUP BY p.fid ORDER BY posts DESC LIMIT 1");
            $forum = $db->fetch_array($query);

            if(!($forum['posts'] > 0)){
                $topforum = $lang['textnopostsyet'];
            }else{
                $topforum = "<a href=\"./forumdisplay.php?fid=$forum[fid]\">$forum[name]</a> ($forum[posts] $lang[textdeleteposts]) [".round(($forum['posts']/$memberinfo['postnum'])*100, 1)."% of total posts]";
            }


            // Last post
            $query = $db->query("SELECT t.tid, t.subject, p.dateline FROM $table_posts p, $table_threads t LEFT JOIN $table_forums f ON p.fid=f.fid WHERE $restrict p.author='$memberinfo[username]' AND p.tid=t.tid ORDER BY p.dateline DESC LIMIT 1");
            if($post = $db->fetch_array($query)){
                $lastpostdate = gmdate("$dateformat", $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
                $lastposttime = gmdate("$timecode", $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
                $lastposttext = "$lastpostdate $lang[textat] $lastposttime";
                $post['subject'] = censor($post['subject']);
                $lastpost = "<a href=\"./viewthread.php?tid=$post[tid]&amp;goto=lastpost\">$post[subject]</a> ($lastposttext)";
            }else{
                $lastpost = $lang['textnopostsyet'];
            }


            $lang['searchusermsg'] = str_replace('*USER*', $memberinfo['username'], $lang['searchusermsg']);

            eval("\$page = \"".template("member_profile")."\";");
            $page = stripslashes($page);
            echo $page;
        }

    }
}else{
    eval("\$header = \"".template('header')."\";");
    $message = $lang['textnoaction'];
    eval("\$error = \"".template('error')."\";");
    echo stripslashes($error);
}

end_time();

eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>

