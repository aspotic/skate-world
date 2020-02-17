<?php
/* $Id: viewthread.php,v 1.3 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require "./header.php";

if(!$ppp || $ppp == '') {
    $ppp = $postperpage;
}


if($goto == "lastpost") {
    if(isset($tid) && $tid > 0) {
        $query = $db->query("SELECT count(*) FROM $table_posts WHERE tid='$tid'");
        $posts = $db->result($query, 0);

        $query2 = $db->query("SELECT pid FROM $table_posts WHERE tid='$tid' ORDER BY pid DESC LIMIT 0,1");
        $pid = $db->result($query2, 0);


    }elseif(isset($fid) && $fid > 0) {
        $query2 = $db->query("SELECT pid, tid FROM $table_posts WHERE fid='$fid' ORDER BY pid DESC LIMIT 0,1");
        $stuff = $db->fetch_array($query2);
        $pid = $stuff['pid'];
        $tid = $stuff['tid'];

        $query3 = $db->query("SELECT fid FROM $table_forums WHERE fup='$fid'");
        while($sub = $db->fetch_array($query3)){
            $query4 = $db->query("SELECT pid, tid FROM $table_posts WHERE fid='$sub[fid]' ORDER BY pid DESC LIMIT 0,1");
            $stuff = $db->fetch_array($query4);

            if($stuff['pid'] > $pid){
                $pid = $stuff['pid'];
                $tid = $stuff['tid'];
            }
        }

        $query5 = $db->query("SELECT count(*) FROM $table_posts WHERE tid='$tid'");
        $posts = $db->result($query5, 0);
    }


    if($posts > $ppp) {
        $topicpages = $posts / $ppp;
        $topicpages = ceil($topicpages);
    }else{
        $topicpages=1;
    }

    header("Location: viewthread.php?tid=$tid&page=$topicpages#pid$pid");
    exit();
}





loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','viewthread','viewthread_newtopic','viewthread_newpoll','viewthread_reply','forumdisplay_password','viewthread_poll_options_view','viewthread_poll_options','viewthread_poll_submitbutton','viewthread_poll','viewthread_post_email','viewthread_post_site','viewthread_post_repquote','viewthread_post_edit','viewthread_post_search','viewthread_post_profile','viewthread_post_u2u','viewthread_post_ip','viewthread_post_report','viewthread_post_attachment','viewthread_post','viewthread_invalid','viewthread_modoptions','viewthread_printable','viewthread_printable_row','viewthread_post_yahoo', 'viewthread_post_icq', 'viewthread_post_aim', 'viewthread_post_msn', 'viewthread_post_sig','viewthread_quickreply', 'header','footer','css', 'functions_bbcode', 'functions_smilieinsert_smilie');
eval("\$css = \"".template('css')."\";");

if(!isset($_COOKIE['oldtopics'])) {
    $oldtopics = '';
}
if(false === strpos($oldtopics, "|$tid|")) {
    $oldtopics .= "|$tid| ";
    $expire = time() + 600;
    setcookie("oldtopics", $oldtopics, $expire, $cookiepath, $cookiedomain);
}

// Cache smilies & words
smcwcache();
loadAwards();
$notexist = false;
$notexist_txt = '';
$posts = '';

$query = $db->query("SELECT * FROM $table_threads WHERE tid='$tid'");
$thread = $db->fetch_array($query);

// if subject has MAX $table_thread[subject] size (128 chars) assume it is actually longer
if(strlen($thread['subject']) == 128) {
    $thread['subject'] .= '...';
}

$thread['subject'] = censor($thread['subject']);
$fid = $thread['fid'];

if($thread['tid'] != $tid || !$tid) {
    $notexist_txt = $lang['textnothread'];
    $notexist = true;
}

$query = $db->query("SELECT * FROM $table_forums WHERE fid='$fid'");
$forum = $db->fetch_array($query);

if($forum['type'] != "forum" && $forum['type'] != "sub" && $forum['fid'] != $fid) {
    $notexist_txt = $lang['textnoforum'];
    $notexist = true;
}

if($notexist || trim($notexist_txt) != ''){
    error($notexist_txt);
}

$authorization = true;
if($forum['type'] == 'sub'){
    $query = $db->query("SELECT name, fid, private, userlist FROM $table_forums WHERE fid='$forum[fup]'");
    $fup = $db->fetch_array($query);
    $authorization = privfcheck($fup['private'], $fup['userlist']);
}

if(!$authorization || !privfcheck($forum['private'], $forum['userlist'])) {
    error($lang['privforummsg']);
}

pwverify($forum['password'], 'viewthread.php?tid='.$tid);

if($forum['type'] == "forum") {
    $navigation .= "&raquo; <a href=\"forumdisplay.php?fid=$fid\"> ".stripslashes($forum['name'])."</a> &raquo; ".stripslashes(censor($thread['subject']));
} else {
    $navigation .= "&raquo; <a href=\"forumdisplay.php?fid=$fup[fid]\">".stripslashes($fup['name'])."</a> &raquo; <a href=\"forumdisplay.php?fid=$fid\">".stripslashes($forum['name'])."</a> &raquo; ".stripslashes(censor($thread['subject']));
}

if($forum['allowimgcode'] == "yes") {
    $allowimgcode = $lang['texton'];
} else {
    $allowimgcode = $lang['textoff'];
}

if($forum['allowhtml'] == "yes") {
    $allowhtml = $lang['texton'];
} else {
    $allowhtml = $lang['textoff'];
}

if($forum['allowsmilies'] == "yes") {
    $allowsmilies = $lang['texton'];
} else {
    $allowsmilies = $lang['textoff'];
}

if($forum['allowbbcode'] == "yes") {
    $allowbbcode = $lang['texton'];
} else {
    $allowbbcode = $lang['textoff'];
}


eval('$bbcodescript = "'.template('functions_bbcode').'";');
if($smileyinsert == 'on' && $smiliesnum > 0) {
    $max = ($smiliesnum > 16) ? 16 : $smiliesnum;

    $keys = array_rand($smiliecache, $max);

    $smilies = array();
    $smilies[] = '<table border="0"><tr>';
    $i = 0;
    $total = 0;
    $pre = 'opener.';
    foreach($keys as $key){
        if($total == 16){
            break;
        }
        $smilie['code'] = $key;
        $smilie['url'] = $smiliecache[$key];

        if($i >= 4){
            $smilies[] = '</tr><tr>';
            $i = 0;
        }
        eval("\$smilies[] = \"".template("functions_smilieinsert_smilie")."\";");
        $i++;
        $total++;
    }
    $smilies[] = '</tr></table>';
    $smilies = implode("\n", $smilies);
}

if(!$action) {

    if($xmbuser && $xmbuser != '') {
        if($sig != '') {
            $usesigcheck = 'checked';
        }else{
            $usesigcheck = '';
        }
    }

    eval("\$header = \"".template("header")."\";");
    echo $header;
    $ppthread = postperm($forum, 'thread');
    $ppreply = postperm($forum, 'reply');

    if($thread['closed'] == "yes" && $self['status'] != 'Super Administrator') {
        $replylink = "";
        $closeopen = $lang['textopenthread'];
    } else {
        $closeopen = $lang['textclosethread'];
        eval("\$replylink = \"".template("viewthread_reply")."\";");
        eval("\$quickreply = \"".template("viewthread_quickreply")."\";");
    }

    if(!$ppthread){
        $newtopiclink = '';
        $newpolllink = '';

        if(!$ppreply || ($self['status'] == '' && $forum['guestposting'] != 'on')){
            $replylink = '';
            $quickreply = '';
        }
    }else{
        eval("\$newtopiclink = \"".template("viewthread_newtopic")."\";");
        if($forum['pollstatus'] != "off") {
            eval("\$newpolllink = \"".template("viewthread_newpoll")."\";");
        }else{
            $newpolllink = '';
        }

        if(!$ppreply || ($self['status'] == '' && $forum['guestposting'] != 'on')){
            $replylink = '';
            $quickreply = '';
        }
    }

    if($thread['topped'] == 1) {
        $topuntop = $lang['textuntopthread'];
    } else {
        $topuntop = $lang['texttopthread'];
    }

    if($page) {
        $start_limit = ($page-1) * $ppp;
    } else {
        $start_limit = 0;
        $page = 1;
    }

    //Query for user ranks. We do this only once now.  -Aharon

    $queryranks = $db->query("SELECT id,title,posts,stars,allowavatars,avatarrank FROM $table_ranks");
    while($query = $db->fetch_row($queryranks)) {
        $title = $query[1];
        $rposts= $query[2];

        if($title == 'Super Administrator' || $title == 'Administrator' || $title == 'Super Moderator' || $title == 'Moderator'){
            $specialrank[$title]    = "$query[0],$query[1],$query[2],$query[3],$query[4],$query[5]";
        }else{
            $rankposts[$rposts] = "$query[0],$query[1],$query[2],$query[3],$query[4],$query[5]";
        }
    }

    //End user rank query.

    $db->query("UPDATE $table_threads SET views=views+1 WHERE tid='$tid'");
    $query = $db->query("SELECT count(pid) FROM $table_posts WHERE fid='$fid' AND tid='$tid'");
    $num = $db->result($query, 0);

    $mpurl = "viewthread.php?tid=$tid";
    $multipage = multi($num, $ppp, $page, $mpurl);

// Start polls
if($thread['pollopts'] != '' && $forum['pollstatus'] != 'off' && $thread['closed'] != 'yes'){
    $pollbar = '';
    $num = array();
    $pollhtml = '';

    $options = explode("#|#", $thread['pollopts']);
    $num_options = count($options);

    if(false !== strpos(' '.$options[$num_options-1].' ', ' '.$xmbuser.' ') || $viewresults == 'yes'){
        //show the 'voted' look
        if($viewresults == 'yes') {
            $results = "[<a href=\"./viewthread.php?tid=$tid\">$lang[backtovote]</a>]";
        }else{
            $results = '';
        }


        for($i=0;$i<($num_options-1);$i++){
            $that = array();
            $that = explode('||~|~||', $options[$i]);

            $num_votes += $that[1];
            $poll[$i]['name'] = postify($that[0], 'no', 'no', 'yes', 'no', 'yes', 'yes');
            $poll[$i]['votes'] = $that[1];
        }

        foreach($poll as $num=>$array){
            $pollimgnum = 0;
            $pollbar = '';

            if($array['votes'] > 0){
                $orig = round($array['votes']/$num_votes*100, 2);
                $percentage = round($orig, 2);
                $poll_length = round($orig/3, 2);
                for($num = 0; $num < $poll_length; $num++) {
                    $pollbar .= '<img src="'.$imgdir.'/pollbar.gif" alt="'.$lang['altpollpercentage'].'" />';
                }
                $percentage .= '%';
            }else{
                $percentage = '0%';
            }
            eval("\$pollhtml .= \"".template("viewthread_poll_options_view")."\";");

        }

        $buttoncode = '';
    }else{
        $results = '[<a href="./viewthread.php?tid='.$tid.'&viewresults=yes">'.$lang['viewresults'].'</a>]';
        for($i=0;$i<($num_options-1);$i++){
            $that = array();
            $that = explode('||~|~||', $options[$i]);
            $poll['name'] = postify($that[0], 'no', 'no', 'yes', 'no', 'yes', 'yes');
            eval("\$pollhtml .= \"".template("viewthread_poll_options")."\";");
        }
        eval("\$buttoncode = \"".template("viewthread_poll_submitbutton")."\";");
    }
    eval("\$poll = \"".template("viewthread_poll")."\";");

}elseif($thread['closed'] == 'yes' && $thread['pollopts'] != ''){
    $pollbar = '';
    $pollhtml = '';
    $num = array();


    $options = explode("#|#", $thread['pollopts']);
    $num_options = count($options);

    for($i=0;$i<($num_options-1);$i++){
        $that = '';
        $that = explode('||~|~||', $options[$i]);
        $num_votes += $that[1];
        $poll[$i]['name'] = postify($that[0], 'no', 'no', 'yes', 'no', 'yes', 'yes');
        $poll[$i]['votes'] = $that[1];
    }

    foreach($poll as $num=>$array){
        $pollimgnum = 0;
        $pollbar = '';

        if($array['votes'] > 0){
            $percentage = round(round(($array['votes'])/$num_votes*100,2)/3, 2);
            for($num = 0; $num < $percentage; $num++) {
                $pollbar .= '<img src="'.$imgdir.'/pollbar.gif" alt="'.$lang['altpollpercentage'].'" />';
            }
            $percentage .= '%';
        }else{
            $percentage = '0%';
        }
        eval("\$pollhtml .= \"".template("viewthread_poll_options_view")."\";");
    }

    $buttoncode = '';
    eval("\$poll = \"".template("viewthread_poll")."\";");
}

// End Polls

    $thisbg = $altbg2;
    $querypost = $db->query("SELECT a.*, p.*, m.*,w.time FROM $table_posts p LEFT JOIN $table_members m ON m.username=p.author LEFT JOIN $table_attachments a ON a.pid=p.pid LEFT JOIN $table_whosonline w ON p.author=w.username WHERE p.fid='$fid' AND p.tid='$tid' ORDER BY p.pid LIMIT $start_limit, $ppp");

    while($post = $db->fetch_array($querypost)) {
    $post['avatar'] = str_replace("javascript:", "java script:", $post['avatar']);

        if($post['time'] != "" && $post['author'] != "xguest123"){
            if($post['invisible'] == 1){
                if(X_ADMIN){
                    $onlinenow = $lang['memberison'] . ' (' . $lang['hidden'] . ')';
                }else{
                    $onlinenow = $lang['memberisoff'];
                }
            }else{
                $onlinenow = $lang['memberison'];
            }
        }else{
            $onlinenow = $lang['memberisoff'];
        }
        $date = gmdate("$dateformat", $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
        $time = gmdate("$timecode", $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));

        $poston = "$lang[textposton] $date $lang[textat] $time";

        if($post['icon'] != "") {
            $post['icon'] = "<img src=\"$smdir/$post[icon]\" alt=\"$post[icon]\" />";
        }

        if($post['author'] != "Anonymous") {
		$items = "";

$itemsquery = $db->query("SELECT * FROM $table_member_items WHERE uid='$post[uid]'");

while($memberitem = $db->fetch_array($itemsquery)) {
	$itemquery = $db->query("SELECT * FROM $table_shop_items WHERE id='$memberitem[iid]'");
	$item = $db->fetch_array($itemquery);

	if($item[imageurl] == "") {
		$items .= "<img src=\"$imgdir/folder.gif\" border=\"0\" alt=\"$item[itemname]\"> "; // You may wish to change this
	} else {
		$items .= "<img src=\"$item[imageurl]\" border=\"0\" alt=\"$item[itemname]\"> ";
	}
}

if($items != "") {
	$items = "<br><br>$lang_shop_textitems: $items";
}
          // BEGIN SHOP HACK

if($post['hexcolor'] == "" || $post['hexcolorstatus'] == "off") {
	$hexcolor1 = "";
	$hexcolor2 = "";
} else {
	$hexcolor1 = "#$post[hexcolor]";
	$hexcolor2 = "color:#$post[hexcolor]";
}

if($post['glowcolor'] == "" || $post['glowcolorstatus'] == "off") {
	$span1 = "<font color=$hexcolor1>";
	$span2 = "</font>";
} else {
	$glowcolor = "#$post[glowcolor]";
	$span1 = "<font	style=\"width:100%; $hexcolor2; filter:glow(color=$glowcolor, strength=2)\">";
	$span2 = "</font>";
}

$username = "$span1$post[username]$span2";

$money = "<br>$lang_shop_currency: $post[money]<br>";

// END SHOP HACK
            if($post['showemail'] == "yes") {
                eval("\$email = \"".template("viewthread_post_email")."\";");
            } else {
                $email = "";
            }
            if($post['personstatus'] != "" && $personstaton == "on") {
                $personstatus = substr_replace($personstatus, ' ', 20, 0);
                $personstatus = substr_replace($personstatus, ' ', 41, 0);
                $personstatus = substr_replace($personstatus, ' ', 62, 0);
                $personstatus = substr_replace($personstatus, ' ', 83, 0);
                $personstatus .= "<br />";
            } else {
                $personstatus = "";
            }
            if($post['site'] == "") {
                $site = "";
            } else {
                $post['site'] = str_replace("http://", "", $post['site']);
                $post['site'] = "http://$post[site]";
                eval("\$site = \"".template("viewthread_post_site")."\";");
            }


            $encodename = urlencode($post['author']);
            if($post['icq'] == "") {
                $icq = "";
            } else {
                eval("\$icq = \"".template("viewthread_post_icq")."\";");
            }

            if($post['aim'] == "") {
                $aim = "";
            } else {
                eval("\$aim = \"".template("viewthread_post_aim")."\";");
            }

            if($post['msn'] == "") {
                $msn = "";
            } else {
                eval("\$msn = \"".template("viewthread_post_msn")."\";");
            }

            if($post['yahoo'] == "") {
                $yahoo = "";
            }else{
                eval("\$yahoo = \"".template("viewthread_post_yahoo")."\";");
            }

            eval("\$search = \"".template("viewthread_post_search")."\";");
            eval("\$profile = \"".template("viewthread_post_profile")."\";");
            eval("\$u2u = \"".template("viewthread_post_u2u")."\";");
            $showtitle = $post['status'];

            $rank = array();

            if($post['status'] == 'Administrator' || $post['status'] == 'Super Administrator' || $post['status'] == 'Super Moderator' || $post['status'] == 'Moderator') {
                $rankinfo = explode(",", $specialrank["$post[status]"]);
                $rank['allowavatars']   = $rankinfo[4];
                $rank['title']      = $rankinfo[1];
                $rank['stars']      = $rankinfo[3];
                $rank['avatarrank'] = $rankinfo[5];

            }elseif($post['status'] == 'Banned'){
                $rank['allowavatars']   = 'no';
                $rank['title']      = $lang['textbanned'];
                $rank['stars']      = 0;
                $rank['avatarrank'] = '';

            }else {
                $last_max = -1;
                foreach($rankposts as $key => $rankstuff) {
                    if($post['postnum'] >= $key && $key > $last_max) {
                        $last_max = $key;
                        $rankinfo = explode(",", $rankstuff);
                        $rank['allowavatars'] = $rankinfo[4];
                        $rank['title'] = $rankinfo[1];
                        $rank['stars'] = $rankinfo[3];
                        $rank['avatarrank'] = $rankinfo[5];
                    }
                }
            }

            $allowavatars   = $rank['allowavatars'];
            $showtitle  = $rank['title'];
            $stars      = '';

            for($i = 0; $i < $rank['stars']; $i++) {
                $stars .= "<img src=\"$imgdir/star.gif\" alt=\"*\" />";
            }

            if($allowavatars == 'no') {
                $post['avatar'] = '';
            }


            if($post['customstatus'] != "") {
                $showtitle = $post['customstatus'];
                $showtitle .= "<br />";
            } else {
                $showtitle .= "<br />";
                $custitle = "";
            }

            if($rank['avatarrank'] != ''){
                $rank['avatar'] = '<img src="'.$rank['avatarrank'].'" class="ctrtablerow" alt="'.$lang['altavatar'].'"/><br />';
            }

            $tharegdate = gmdate($dateformat, $post['regdate'] + ($timeoffset * 3600) + ($addtime * 3600));
            $stars .= '<br />';

            if($SETTINGS['avastatus'] == 'on' || $SETTINGS['avastatus'] == 'list') {
                if($post['avatar'] != "" && $allowavatars != "no") {
                    if(false !== strpos($post['avatar'], ",")) {
                        $flashavatar = explode(",",$post['avatar']);
                        $avatar = "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0\" ID=main WIDTH=$flashavatar[1] HEIGHT=$flashavatar[2]>
                            <PARAM NAME=movie VALUE=\"$flashavatar[0]\">
                            <PARAM NAME=loop VALUE=false>
                            <PARAM NAME=menu VALUE=false>
                            <PARAM NAME=quality VALUE=best>
                            <EMBED src=\"$flashavatar[0]\" loop=false menu=false quality=best WIDTH=$flashavatar[1] HEIGHT=$flashavatar[2] TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\">
                            </EMBED>
                            </OBJECT>";
                    }else{
                        $avatar = '<img src="'.$post['avatar'].'" alt="'.$lang['altavatar'].'"/>';
                    }
                }else{
                    $avatar = "";
                }
            }else{
                $avatar = '';
            }

            if($post['mood'] != ''){
                $mood = '<b>'.$lang['mood'].'</b> '.postify($post['mood'], 'no', 'no', 'yes', 'no', 'yes', 'no', true, 'yes');
            }else{
                $mood = '';
            }
			
			            // BEGIN GENDER HACK
                if($post[gender] == $lang[genderm]){ $gender = "<img src=\"images/gender_male.gif\" alt=\"" .$lang['genderm']. "\">"; }
                elseif($post[gender] == $lang[genderf]){ $gender = "<img src=\"images/gender_female.gif\" alt=\"" .$lang['genderf']. "\">"; }
                else{ $gender = "<img src=\"images/gender_none.gif\">"; }

            if($post['location'] != "") {
                $location = '<br />'.$lang['textlocation'].' '.$post['location'];
            } else {
                $location = '';
            }
			if ($post['awards'] != "") {
$showawards = makeAwards($post['awards']);
$showawards .= "<a href=\"javascript:void('')\" onclick=\"Popup(\'awedit.php?action=viewaw\', \'Window\', 350, 300);\">[?]</a>";
} else {
$showawards = "";
}
if($self[status] != "Administrator" && $self[status] != "Super Administrator") {
$showawards .= "<br><a href=\"javascript:void('')\" onclick=\"Popup('awedit.php?action=edit&username=$encodename', 'Window', 350, 400);\">edit awards</a> ";
}
        } else {
            $post['author'] = $lang['textanonymous'];
            $showtitle = $lang['textunregistered'].'<br />';
            $stars = '';
            $avatar = '';
            $post['postnum'] = 'N/A';
            $tharegdate = 'N/A';
            $email = '';
            $site = '';
            $icq = '';
            $msn='';
            $aim = '';
            $yahoo = "";
            $profile = '';
            $search = '';
            $u2u = '';
            $location = '';
            $mood = '';
        }

        if(!X_STAFF) {
            $ip = "";
        } else {
            eval("\$ip = \"".template("viewthread_post_ip")."\";");
        }

        if($thread['closed'] == "yes") {
            $repquote = "";
        } else {
            eval("\$repquote = \"".template("viewthread_post_repquote")."\";");
        }

        if($xmbuser != "" && $reportpost != "off") {
            eval("\$reportlink = \"".template("viewthread_post_report")."\";");
        } else {
            $reportlink = "";
        }
        if($post['subject'] != ""){
            $post['subject'] = censor($post['subject']).'<br /><br />';
            $post['subject'] = str_replace('&amp;', '&', $post['subject']);
        }

        eval("\$edit = \"".template("viewthread_post_edit")."\";");
        $bbcodeoff = $post['bbcodeoff'];
        $smileyoff = $post['smileyoff'];
        $post['message'] = postify($post['message'], $smileyoff, $bbcodeoff, $forum['allowsmilies'], $forum['allowhtml'], $forum['allowbbcode'], $forum['allowimgcode']);

        // Deal with the attachment if there is one
        if($post['filename'] != "" && $forum['attachstatus'] != "off") {
            $extention = strtolower(substr(strrchr($post['filename'],"."),1));
            if($attachimgpost == 'on' && ($extention == 'jpg' || $extention == 'jpeg' || $extention == 'jpe' || $extention == 'gif' || $extention == 'png' || $extention == 'bmp')) {
                eval("\$post[message] .= \"".template("viewthread_post_attachmentimage")."\";");
            } else {
                $attachsize = $post['filesize'];
                if($attachsize >= 1073741824){
                    $attachsize = round($attachsize / 1073741824 * 100) / 100 . "gb";
                }elseif($attachsize >= 1048576){
                    $attachsize = round($attachsize / 1048576 * 100) / 100 . "mb";
                    }elseif($attachsize >= 1024){
                    $attachsize = round($attachsize / 1024 * 100) / 100 . "kb";
                }else{
                    $attachsize = $attachsize . "b";
                }

                $downloadcount = $post['downloads'];
                if($downloadcount == "") {
                    $downloadcount = 0;
                }
                eval("\$post[message] .= \"".template("viewthread_post_attachment")."\";");
            }
        }

        if($post['usesig'] == "yes") {
            $post['sig'] = postify($post['sig'], 'no', 'no', $forum['allowsmilies'], $SETTINGS['sightml'], $SETTINGS['sigbbcode'], $forum['allowimgcode'], false);
            eval("\$post[message] .= \"".template("viewthread_post_sig")."\";");
        }


        if(!$notexist) {
            eval("\$posts .= \"".template("viewthread_post")."\";");
        } else {
            eval("\$posts = \"".template("viewthread_invalid")."\";");
        }

        if($thisbg == $altbg2) {
            $thisbg = $altbg1;
        } else {
            $thisbg = $altbg2;
        }
    }

    $status1 = modcheck($self['status'], $xmbuser, $forum['moderator']);

    if(X_ADMIN || $self['status'] == 'Super Moderator' || $status1 == 'Moderator') {
        eval("\$modoptions = \"".template("viewthread_modoptions")."\";");
    } else {
        $modoptions = "";
    }
	
	    if($forum['mpfa'] != "0" && $self['postnum'] < $forum['mpfa'] && $self['status'] != "Super Administrator") {
        $message = str_replace("*posts*", $forum['mpfa'], $lang['mpfae']); 
        error($message, false, '', '', false, true, false, true);
    }

    if($forum['mpnp'] != "0" || $forum['mpnt'] != "0" || $forum['mpfa'] != "0"){ $Ffie = ''; $showfi = '';
        if($forum['mpfa'] != "0"){ $lang['mpfai'] = str_replace("*posts*", $forum['mpfa'], $lang['mpfai']); $showfi .= $lang['mpfai']; $Ffie = '<br />'; }
        if($forum['mpnp'] != "0"){ $lang['mpnpi'] = str_replace("*posts*", $forum['mpnp'], $lang['mpnpi']); $showfi .= "$Ffie"; $showfi .= $lang['mpnpi']; $Ffie = '<br />'; if($self['postnum'] < $forum['mpnp'] && $self['status'] != "Super Administrator"){ $replylink = ''; $quickreply = ''; } }
        if($forum['mpnt'] != "0"){ $lang['mpnti'] = str_replace("*posts*", $forum['mpnt'], $lang['mpnti']); $showfi .= "$Ffie"; $showfi .= $lang['mpnti']; if($self['postnum'] < $forum['mpnt'] && $self['status'] != "Super Administrator"){ $newpolllink = ''; $newtopiclink = ''; } }
        eval("\$minimal_posts_needed = \"".template("forumdisplay_mpn_info")."\";");
    }else{ $minimal_posts_needed = ''; }

    eval("\$viewthread = \"".template("viewthread")."\";");
    echo stripslashes($viewthread);

    end_time();

    eval("\$footer = \"".template("footer")."\";");
    echo stripslashes($footer);
    $db->free_result($querypost);
    exit();

}elseif($action == "attachment" && $forum['attachstatus'] != "off") {
    // select attachment
    $query = $db->query("SELECT * FROM $table_attachments WHERE pid='$pid' and tid='$tid'");
    $file = $db->fetch_array($query);
    $db->query("UPDATE $table_attachments SET downloads=downloads+1 WHERE pid='$pid'");

    // Check if file is corrupt
    if($file['filesize'] != strlen($file['attachment'])){
        error('The file you are trying to download appears corrupt.<br /><br />&raquo; File download aborted');
    }

    // Generate $type, $name and $size vars
    $type = $file['filetype'];
    $name = $file['filename'];
    $size = $file['filesize'];

    // Make sure text/html types can't be run...
    $type = ($type == 'text/html') ? 'text/plain' : $type;

    // Put out headers for mime-type, filesize, forced-download, description and no-cache.
    header("Content-type: $type");
    header("Content-length: $size");
    header("Content-Disposition: inline; filename=$name");
    header("Content-Description: XMB Attachment");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Start file download
    echo $file['attachment'];

    // End download
    exit();

}elseif($action == "printable") {

    $querypost = $db->query("SELECT * FROM $table_posts WHERE fid='$fid' AND tid='$tid' ORDER BY pid");
    $posts = '';
    while($post = $db->fetch_array($querypost)) {
        $date = gmdate($dateformat, $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
        $time = gmdate($timecode, $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
        $poston = "$date $lang[textat] $time";
        $post['message'] = stripslashes($post['message']);

        $bbcodeoff = $post['bbcodeoff'];
        $smileyoff = $post['smileyoff'];
        $post['message'] = postify($post['message'], $smileyoff, $bbcodeoff, $forum['allowsmilies'], $forum['allowhtml'], $forum['allowbbcode'], $forum['allowimgcode']);

        eval("\$posts .= \"".template("viewthread_printable_row")."\";");
    }
    eval("\$printable = \"".template("viewthread_printable")."\";");
    $printable = stripslashes($printable);
    echo $printable;
}
?>