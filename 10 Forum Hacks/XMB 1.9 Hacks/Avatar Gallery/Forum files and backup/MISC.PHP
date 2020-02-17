<?php
/* $Id: misc.php,v 1.5 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

// Get global settings
    require "./header.php";

// pre-Load templates (saves queries)
    loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','misc_login','misc_search','misc_search_results_row','misc_search_results_none','misc_search_results','misc_lostpw','misc_online_row_admin','misc_online_row','misc_online_admin','misc_online','misc_mlist_row_email','misc_mlist_row_site','misc_mlist_row','misc_mlist','header','footer','css', 'misc_online_u2ufield', 'misc_mlist_admin','misc_online_today', 'misc_lostpw');
    eval("\$css = \"".template('css')."\";");

// Create navigation
    switch($action){
        case 'login';
            $navigation = '&raquo; '.$lang['textlogin'];
            break;
        case 'logout';
            $navigation = '&raquo; '.$lang['textlogout'];
            break;

        case 'search';
            $navigation = '&raquo; '.$lang['textsearch'];
            break;

        case 'lostpw';
            $navigation = '&raquo; '.$lang['textlostpw'];
            break;

        case 'online';
            $navigation = '&raquo; '.$lang['whosonline'];
            break;

        case 'list';
            $navigation = '&raquo; '.$lang['textmemberlist'];
            break;

        case 'onlinetoday';
            $navigation = '&raquo; '.$lang['whosonlinetoday'];
            break;

        default:
            $navigation = '&raquo; '.$lang['error'];
            break;
    }

// Based on the action, choose what to do
    $misc = '';

    switch($action){
        case 'login':
            if(!$loginsubmit) {
                eval("\$misc = \"".template("misc_login")."\";");
                $misc = stripslashes($misc);
            }else{
                $password = md5($password);
                $query = $db->query("SELECT * FROM $table_members WHERE username='$username' AND password='$password'");
                if($query && $db->num_rows($query) == 1){
                    $member = $db->fetch_array($query);
                    $db->query("DELETE FROM $table_whosonline WHERE ip='$onlineip' && username='xguest123'");
                    $currtime = time() + (86400*30);
                    $username = $member['username'];

                    if(isset($hide)){
                        $db->query("UPDATE $table_members SET invisible='1' WHERE username='$username'");
                    }else{
                        $db->query("UPDATE $table_members SET invisible='0' WHERE username='$username'");
                    }
                    if($server == 'Mic'){
                        $misc = '<script>
                            function setCookie(name, value, expires, path, domain, secure) {
                                var curCookie = name + "=" + escape(value) +
                                ((expires) ? "; expires=" + expires.toGMTString() : "") +
                                ((path) ? "; path=" + path : "") +
                                ((domain) ? "; domain=" + domain : "") +
                                ((secure) ? "; secure" : "");
                                document.cookie = curCookie;
                            }

                            var now = new Date();
                            now.setTime(now.getTime() + 365 * 24 * 60 * 60 * 1000);

                            setCookie("xmbuser", "'.$username.'", now, "'.$cookiepath.'", "'.$cookiedomain.'");
                            setCookie("xmbpw", "'.$password.'", now, "'.$cookiepath.'", "'.$cookiedomain.'");

                            window.location="index.php";
                        </script>';
                    }else{
                        if(isset($secure) && $secure == 'yes'){
                            setcookie("xmbuser", $username);
                            setcookie("xmbpw", $password);
                        }else{
                            setcookie("xmbuser", $username, $currtime, $cookiepath, $cookiedomain);
                            setcookie("xmbpw", $password, $currtime, $cookiepath, $cookiedomain);
                        }
                        header("Location: index.php");
                        $misc = '';
                    }

                }else{
                    end_time();

                    eval("\$header = \"".template("header")."\";");
                    eval("\$incorrectpassword = \"".template("misc_login_incorrectdetails")."\";");
                    eval("\$footer = \"".template("footer")."\";");

                    echo $header;
                    echo $incorrectpassword;
                    echo $footer;

                    exit();
                }
            }
            break;

        case 'logout':
            $currtime = time() - (86400*30);
            $query = $db->query("DELETE FROM $table_whosonline WHERE username='$xmbuser'");

            if($server == 'Mic'){
                $misc = '<script>
                function setCookie(name, value, expires, path, domain, secure) {
                    var curCookie = name + "=" + escape(value) +
                    ((expires) ? "; expires=" + expires.toGMTString() : "") +
                    ((path) ? "; path=" + path : "") +
                    ((domain) ? "; domain=" + domain : "") +
                    ((secure) ? "; secure" : "");
                    document.cookie = curCookie;
                }

                var now = new Date();
                now.setTime(now.getTime() + 365 * 24 * 60 * 60 * 1000);

                setCookie("xmbuser", "'.$username.'", now, "'.$cookiepath.'", "'.$cookiedomain.'");
                setCookie("xmbpw", "'.$password.'", now, "'.$cookiepath.'", "'.$cookiedomain.'");

                window.location="index.php";
                </script>';
            }else{
                setcookie("xmbuser", $username, $currtime, $cookiepath, $cookiedomain);
                setcookie("xmbpw", $password, $currtime, $cookiepath, $cookiedomain);
                setcookie("xmbuser", '');
                setcookie("xmbpw", '');
                header("Location: index.php");
            }
            break;

        case 'search':
            //////////////////////////
            // if(!X_ADMIN){
            //  error('The search feature is currently offline while we make a new one');
            // }
            //
            ///////////////////////////
            if($searchstatus != "on") {
                eval("\$header = \"".template("header")."\";");
                echo $header;

                eval("\$featureoff = \"".template("misc_feature_notavailable")."\";");
                end_time();
                eval("\$footer = \"".template("footer")."\";");
                $featureoff = stripslashes($featureoff);
                echo $featureoff;
                echo $footer;
                exit();
            }

            if($type == 'advanced') {
                error("We're sorry, this feature is not yet available for public use");
            }

            if(!$searchsubmit && !$page){
                // temp
                $restrict = '';
                switch($self['status']){
                    case 'member';
                        $restrict .= " private !='3' AND";

                    case 'Moderator';

                    case 'Super Moderator';
                        $restrict .= " private != '2' AND";

                    case 'Administrator';
                        $restrict .= " userlist = '' AND password = '' AND";

                    case 'Super Administrator';
                        break;

                    default:
                        $restrict .= " private !='3' AND private != '2' AND userlist = '' AND password = '' AND";
                        break;
                }

                $forumselect = "<select name=\"srchfid\">\n";
                $forumselect .= "<option value=\"all\">$lang[textall]</option>\n";

                $queryfor = $db->query("SELECT * FROM $table_forums WHERE $restrict fup='' AND type='forum' ORDER BY displayorder");

                while($forum = $db->fetch_array($queryfor)) {
                    $forumselect .= "<option value=\"$forum[fid]\"> &nbsp; &raquo; $forum[name]</option>";
                    $querysub = $db->query("SELECT * FROM $table_forums WHERE $restrict fup='$forum[fid]' AND type='sub' ORDER BY displayorder");

                    while($sub = $db->fetch_array($querysub)) {
                        $forumselect .= "<option value=\"$sub[fid]\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &raquo; $sub[name]</option>";
                    }

                    $forumselect .= "<option value=\"\"> </option>";
                }

                $querygrp = $db->query("SELECT * FROM $table_forums WHERE $restrict type='group' ORDER BY displayorder");
                while($group = $db->fetch_array($querygrp)) {
                    $forumselect .= "<option value=\"$group[fid]\">$group[name]</option>";

                    $queryfor = $db->query("SELECT * FROM $table_forums WHERE $restrict fup='$group[fid]' AND type='forum' ORDER BY displayorder");
                    while($forum = $db->fetch_array($queryfor)) {
                        $forumselect .= "<option value=\"$forum[fid]\"> &nbsp; &raquo; $forum[name]</option>";

                        $querysub = $db->query("SELECT * FROM $table_forums WHERE $restrict fup='$forum[fid]' AND type='sub' ORDER BY displayorder");
                        while($sub = $db->fetch_array($querysub)) {
                            $forumselect .= "<option value=\"$sub[fid]\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &raquo; $sub[name]</option>";
                        }
                    }

                    $forumselect .= "<option value=\"\"> </option>";
                }

                $forumselect .= "</select>";
                // /temp

                eval("\$search = \"".template("misc_search")."\";");
                $misc = stripslashes($search);
            }else{
                if(strlen($srchtxt) < 3 && strlen($srchuname) < 3){
                    error('nothing to search for...');
                }
                if($searchsubmit || $page) {
                    if (!isset($page)) {
                        $page = 1;
                        $offset = 0;
                        $start = 0;
                        $end = 20;
                    } else {
                        $offset = ($page-1)*20;
                        $start = $offset;
                        $end = $offset+20;
                    }

                    $sql = "SELECT count(*), p.*, t.tid AS ttid, t.subject AS tsubject, f.fid, f.private AS fprivate, f.userlist AS fuserlist, f.password AS password FROM $table_posts p, $table_threads t LEFT JOIN $table_forums f ON  f.fid=t.fid WHERE p.tid=t.tid";

                    if($srchfrom == "0") {
                        $srchfrom = time();
                    }

                    $srchfrom = time() - $srchfrom;
                    if($srchtxt) {
                        $sql .= " AND (p.message LIKE '%$srchtxt%' OR p.subject LIKE '%$srchtxt%' OR t.subject LIKE '%$srchtxt')";
                    }
                    if($srchuname != "") {
                        $sql .= " AND p.author='$srchuname'";
                    }
                    if($srchfid != "all" && $srchfid != "") {
                        $sql .= " AND p.fid='$srchfid'";
                    }
                    if($srchfrom) {
                        $sql .= " AND p.dateline >= '$srchfrom'";
                    }
                    $sql .=" GROUP BY dateline ORDER BY dateline DESC LIMIT $start,20";
                    $pagenum = $page+1;
                    eval("\$nextlink = \"".template("misc_search_nextlink")."\";");
                    $querysrch = $db->query($sql);
                    $postcount = 0;

                    smcwcache();
                    if($srchuname != ''){
                        $srchtxt = '\0';
                    }

                    if($filter_distinct == 'yes'){
                        $temparray = array();
                        $searchresults = '';
                        while($post = $db->fetch_array($querysrch)) {
                            $authorization = privfcheck($post['fprivate'], $post['fuserlist']); // private forum check
                            if((($post['password'] != $_COOKIE["fidpw".$post['fid']]) && $post['password'] != "") && $self['status'] != 'Super Administrator') {
                                continue;
                            }
                            if($authorization) {
                                // Start hack

                                if(!array_key_exists($post['ttid'], $temparray)){
                                    $tid = $post['ttid'];
                                    $temparray[$tid] = true;
                                    $message = $post['message'];

                                    $srchtxt = str_replace(array('_ ', ' _','% ', ' %'), '', $srchtxt);
                                    $position = strpos($message, $srchtxt, 0);
                                    $show_num = 100;
                                    $msg_leng = strlen($message);

                                    if($position <= $show_num){
                                        $min = 0;
                                        $add_pre = '';
                                    }else{
                                        $min = $position - $show_num;
                                        $add_pre = '...';
                                    }

                                    if(($msg_leng - $position) <= $show_num){
                                        $max = $msg_leng;
                                        $add_post = '';
                                    }else{
                                            $max = $position + $show_num;
                                        $add_post = '...';
                                    }

                                    $show = substr($message, $min, $max);
                                    $show = str_replace($srchtxt, '<b><i>'.$srchtxt.'</i></b>', $show);
                                    $show = postify($show, 'no', 'yes', 'yes', 'no', 'no', 'no');
                                    // End hack

                                    $date = gmdate($dateformat, $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
                                    $time = gmdate($timecode, $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
                                    $poston = "$date $lang[textat] $time";
                                    $postby = $post['author'];

                                    $post['tsubject'] = stripslashes(censor($post['tsubject']));
                                    if(trim($post['subject']) == ''){
                                        $post['subject'] = $post['tsubject'];
                                    }

                                    $post['subject'] = censor($post['subject']);
                                    eval("\$searchresults .= \"".template("misc_search_results_row")."\";");
                                }
                                $postcount++;
                            }
                        }
                    }else{

                        while($post = $db->fetch_array($querysrch)) {
                            $authorization = privfcheck($post['fprivate'], $post['fuserlist']);
                            if($authorization) {
                                // Start hack

                                $tid = $post['ttid'];
                                $message = $post['message'];

                                $srchtxt = str_replace(array('_ ', ' _','% ', ' %'), '', $srchtxt);
                                $position = strpos($message, $srchtxt, 0);
                                $show_num = 100;
                                $msg_leng = strlen($message);

                                if($position <= $show_num){
                                $min = 0;
                                    $add_pre = '';
                                }else{
                                    $min = $position - $show_num;
                                    $add_pre = '...';
                                }

                                if(($msg_leng - $position) <= $show_num){
                                    $max = $msg_leng;
                                    $add_post = '';
                                }else{
                                    $max = $position + $show_num;
                                    $add_post = '...';
                                }

                                $show = substr($message, $min, $max);
                                $show = str_replace($srchtxt, '<b><i>'.$srchtxt.'</i></b>', $show);
                                $show = postify($show, 'no', 'yes', 'yes', 'no', 'no', 'no');
                                // End hack

                                $date = gmdate($dateformat, $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
                                $time = gmdate($timecode, $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
                                $poston = "$date $lang[textat] $time";
                                $postby = $post['author'];


                                $post['tsubject'] = stripslashes(censor($post['tsubject']));
                                if(trim($post['subject']) == ''){
                                    $post['subject'] = $post['tsubject'];
                                }else{
                                    $post['tsubject'] = $post['subject'];
                                }

                                $postcount++;
                                eval("\$searchresults .= \"".template("misc_search_results_row")."\";");
                            }
                        }
                    }
                }
                if($postcount == 0 || !$postcount) {
                    eval("\$searchresults = \"".template("misc_search_results_none")."\";");
                }elseif($postcount == 20){
                    eval("\$nextlink = \"".template("misc_search_nextlink")."\";");
                }

                eval("\$search = \"".template("misc_search_results")."\";");
                $misc = stripslashes($search);
            }
            break;

        case 'lostpw':
            if(!$lostpwsubmit) {
                eval("\$misc = \"".template("misc_lostpw")."\";");
                $misc = stripslashes($misc);
            }else{
                $query = $db->query("SELECT username, email, pwdate FROM $table_members WHERE username='$username' AND email='$email'");
                $member = $db->fetch_array($query);

                $time = time()-86400;
                if($member['pwdate'] > $time){
                    error($lang['lostpw_in24hrs']);
                }

                if(!$member['username']) {
                    error($lang['badinfo']);
                }

                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
                $newpass = '';
                mt_srand((double)microtime() * 1000000);
                $max = mt_rand(8, 12);
                for($get=strlen($chars), $i=0; $i < $max; $i++){
                    $newpass .= $chars[mt_rand(0, $get)];
                }
                $newmd5pass = md5($newpass);

                $db->query("UPDATE $table_members SET password='$newmd5pass', pwdate='".time()."' WHERE username='$member[username]' OR email='$member[email]'");

                mail($member['email'], '['.$bbname.'] '.$lang['textyourpw'], $lang['textyourpwis']."\n\n".$member['username']."\n".$newpass, "From: ".$bbname." <".$adminemail.">");

                $misc .= '<span class="mediumtxt"><center>'.$lang['emailpw'].'</span></center><br />';
                $misc .='<script>function redirect(){window.location.replace("index.php");}setTimeout("redirect();", 1250);</script>';
            }
            break;

        case 'online':
            if(X_ADMIN) {
                $query = $db->query("SELECT * FROM $table_whosonline ORDER BY username ASC");
            } else {
                $query = $db->query("SELECT * FROM $table_whosonline WHERE invisible = '0' OR (invisible='1' AND username='$xmbuser') ORDER BY username ASC");
            }
            $onlineusers = '';

            while($online = $db->fetch_array($query)){
                $array = url_to_text($online[location]);
                $online['location'] = "<a href=\"$array[url]\">$array[text]</a>";

                $onlinetime = gmdate($timecode,$online[time] + ($timeoffset * 3600) + ($addtime * 3600));
                $username = str_replace("xguest123", $lang[textguest1], $online[username]);

                $online['location'] = stripslashes($online['location']);

                if($online['invisible'] == 1 && (X_ADMIN || $online['username'] == $xmbuser)){
                    $hidden = " ($lang[hidden])";
                }else{
                    $hidden = '';
                }

                if($online['username'] != "xguest123" && $online[username] != $lang['textguest1'] && $self['status'] == 'Super Administrator') {
                    $online['username'] = "<a href=\"member.php?action=viewpro&amp;member=$online[username]\">$username</a>$hidden";
                    $showu2u = true;
                }else{
                    $online['username'] = $username;
                    $showu2u = '';
                }

                if(X_ADMIN) {
                    if($showu2u){
                        eval("\$u2uthing = \"".template("misc_online_u2ufield")."\";");
                    }else{
                        $u2uthing = '&nbsp;';
                    }
                    eval("\$onlineusers .= \"".template("misc_online_row_admin")."\";");
                }else{
                    eval("\$onlineusers .= \"".template("misc_online_row")."\";");
                }
            }

            if(X_ADMIN) {
                eval("\$misc = \"".template("misc_online_admin")."\";");
            }else{
                eval("\$misc = \"".template("misc_online")."\";");
            }

            $misc = stripslashes($misc);
            break;

        case 'onlinetoday':
            $datecut = time() - (3600 * 24);
            $query = $db->query("SELECT username FROM $table_members WHERE lastvisit>='$datecut' ORDER BY username ASC");

            $todaymembersnum = 0;
            $todaymembers = '';
            $comma = '';

            while ($memberstoday = $db->fetch_array($query)) {
                $todaymembers .= $comma.'<a href="member.php?action=viewpro&amp;member='.rawurlencode($memberstoday['username']).'">'.$memberstoday['username'].'</a>';
                ++$todaymembersnum;
                $comma = ", ";
            }

            if ($todaymembersnum == 1) {
                $memontoday = $todaymembersnum.$lang[textmembertoday];
            }else{
                $memontoday = $todaymembersnum.$lang[textmemberstoday];
            }

            eval("\$misc = \"".template("misc_online_today")."\";");
            $misc = stripslashes($misc);
            break;

        case 'list':
            // Check for status of member-list
            if($memliststatus != "on") {
                eval("\$header = \"".template("header")."\";");
                echo $header;

                eval("\$featureoff = \"".template("misc_feature_notavailable")."\";");
                end_time();
                eval("\$footer = \"".template("footer")."\";");

                $featureoff = stripslashes($featureoff);
                echo $featureoff;
                echo $footer;
                exit();
            }

            if(strtolower($desc) != 'desc'){
                $desc = 'asc';
            }

            if($page) {
                $start_limit = ($page-1) * $memberperpage;
            }else{
                $start_limit = 0;
                $page = 1;
            }

            if($order != "username" && $order != "postnum") {
                $order = "uid";
            }


            if(!X_STAFF){
                $srchip = '';
                $srchemail = '';
                $misc_mlist_template = 'misc_mlist';
                $where = array();
                $ext = '';
            }else{
                $where = array();
                $ext = '';
                $misc_mlist_template = 'misc_mlist_admin';
            }



            if($srchemail != ''){
                if($self[status] != 'Super Administrator'){
                    $where[] = " email LIKE '%".$srchemail."%'";
                    $where[] = " showemail = 'yes'";
                }else{
                    $where[] = " email LIKE '%".$srchemail."%'";
                }
                $ext .= '&srchemail='.$srchemail;
            }

            if($srchip != ''){
                $where[] = " regip LIKE '%".$srchip."%'";
                $ext .= '&srchip='.$srchip;
            }

            if($srchmem != ''){
                $where[] = " username LIKE '%$srchmem%'";
                $ext .= '&srchmem='.$srchmem;
            }


            if($staff == 'view'){
                $querymem = $db->query("SELECT * FROM $table_members WHERE status = 'Administrator' OR status = 'Super Moderator' OR status = 'Moderator' OR status ='Super Administrator' ORDER BY $order $desc LIMIT $start_limit, $memberperpage");
                $num = $db->result($db->query("SELECT count(uid) FROM $table_members WHERE status = 'Administrator' OR status = 'Super Moderator' OR status = 'Moderator' OR status ='Super Administrator'"), 0);
                $ext = '&staff=view';
            }else{
                if($where[0] != ''){
                    $q = implode(' AND', $where);
                    $querymem = $db->query("SELECT * FROM $table_members WHERE $q ORDER BY $order $desc LIMIT $start_limit, $memberperpage");
                    $num = $db->result($db->query("SELECT count(uid) FROM $table_members WHERE $q"), 0);
                }else{
                    $querymem = $db->query("SELECT * FROM $table_members ORDER BY $order $desc LIMIT $start_limit, $memberperpage");
                    $num = $db->result($db->query("SELECT count(uid) FROM $table_members"), 0);
                }
            }

            $replace = array('http://', 'https://', 'ftp://');
            $members = '';

            while ($member = $db->fetch_array($querymem)) {
                $member['regdate'] = gmdate($dateformat, $member['regdate']);

                if($member['email'] != "" && $member['showemail'] == "yes") {
                    eval("\$email = \"".template("misc_mlist_row_email")."\";");
                }else{
                    $email = "&nbsp;";
                }

                $member['site'] = str_replace($replace, '', $member['site']);
                $member['site'] = "http://$member[site]";

                if($member['site'] == "http://") {
                    $site = "&nbsp;";
                }else{
                    eval("\$site = \"".template("misc_mlist_row_site")."\";");
                }

                if($member['location'] == "") {
                    $member['location'] = "&nbsp;";
                }

                $memurl = rawurlencode($member['username']);

				// BEGIN GENDER HACK
                    if($member[gender] == "$lang[genderm]"){ $gender = "<img src=\"images/gender_male.gif\" alt=\"" .$lang['genderm']. "\">"; }
                    elseif($member[gender] == "$lang[genderf]"){ $gender = "<img src=\"images/gender_female.gif\" alt=\"" .$lang['genderf']. "\">"; }
                    else{ $gender = "<img src=\"images/gender_none.gif\">"; }
				
                eval("\$members .= \"".template("misc_mlist_row")."\";");
            }

            if($num > $memberperpage) {
                if(!$memberperpage){
                    $memberperpage=30;
                }

                $pages = $num / $memberperpage;
                $pages = ceil($pages);

                if ($page == $pages) {
                    $to = $pages;
                }elseif($page == $pages-1) {
                    $to = $page+1;
                } elseif ($page == $pages-2) {
                    $to = $page+2;
                } else {
                    $to = $page+3;
                }

                if ($page == 1 || $page == 2 || $page == 3) {
                    $from = 1;
                } else {
                    $from = $page-3;
                }

                $fwd_back = "<a href=\"misc.php?action=list&page=1\"><<</a>";

                for ($i = $from; $i <= $to; $i++) {
                    if ($i == $page) {
                        $fwd_back .= "&nbsp;&nbsp;<u><b>$i</b></u>&nbsp;&nbsp;";
                    } elseif (!$order) {
                        $fwd_back .= "&nbsp;&nbsp;<a href=\"misc.php?action=list&page=$i$ext\">$i</a>&nbsp;&nbsp;";
                    } elseif ($order && !$desc) {
                        $fwd_back .= "&nbsp;&nbsp;<a href=\"misc.php?action=list&order=$order&page=$i$ext\">$i</a>&nbsp;&nbsp;";
                    } elseif ($order && $desc) {
                        $fwd_back .= "&nbsp;&nbsp;<a href=\"misc.php?action=list&order=$order&desc=$desc&page=$i$ext\">$i</a>&nbsp;&nbsp;";
                    }
                }

                $fwd_back .= "<a href=\"misc.php?action=list&page=$pages$ext\">>></a>";
                $multipage = "$backall $backone $fwd_back $forwardone $forwardall";
            }

            eval("\$memlist = \"".template($misc_mlist_template)."\";");
            $misc = stripslashes($memlist);
            break;

        case 'smilies':
            $header = '';
            eval("\$css = \"".template('css')."\";");
            eval("\$header = \"".template("popup_header")."\";");
            eval("\$footer = \"".template("popup_footer")."\";");
            if(!isset($smiliecache)){
                smcwcache();
            }
            $smilies = smilieinsert();
            eval("\$misc = \"".template("misc_smilies")."\";");

            echo $header;
            echo $misc;
            echo $footer;
            exit();

            break;

        default:
            error($lang['textnoaction']);
            break;
    }

// Show the created page
    eval("\$header = \"".template("header")."\";");
    echo $header;
    echo $misc;

// Show footer
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
?>