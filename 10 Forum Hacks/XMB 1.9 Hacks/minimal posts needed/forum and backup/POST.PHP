<?php
/* $Id: post.php,v 1.6 2004/04/20 09:26:23 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require 'header.php';
loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','post_notloggedin','post_loggedin','post_preview','post_attachmentbox','post_newthread','post_reply_review_toolong','post_reply_review_post','post_reply','post_edit','functions_smilieinsert','functions_smilieinsert_smilie','functions_bbcodeinsert','forumdisplay_password','header','footer','css','functions_bbcode','post_newpoll');
eval("\$css = \"".template('css')."\";");

if(!$ppp || $ppp == '') {
    if($postperpage){
        $ppp = $postperpage;
    }else{
        $ppp = 30;
    }
}

if($tid) {
    $query = $db->query("SELECT * FROM $table_threads WHERE tid='$tid' LIMIT 1");
    if($db->num_rows($query) == 1){
        $thread = $db->fetch_array($query);
        $threadname = $thread['subject'];
        $threadname = stripslashes($threadname);
        $fid = $thread['fid'];
    }else{
        error($lang['textnothread']);
    }
}


$query = $db->query("SELECT * FROM $table_forums WHERE fid='$fid'");
$forums = $db->fetch_array($query);
$forums['name'] = stripslashes($forums['name']);

if($forums['mpfa'] != "0" && $self['postnum'] < $forums['mpfa'] && $self['status'] != "Super Administrator") {
    $message = str_replace("*posts*", $forums['mpfa'], $lang['mpfae']); 
    error($message, true, '', '', false, true, false, true);
}

if($forums['type'] != "forum" && $forums['type'] != "sub" && $forums['fid'] != $fid) {
    $posterror = $lang['textnoforum'];
}

smcwcache();
$postaction = '';
if($action != "edit" && $tid) {
    $postaction .= $lang['textpostreply'];
}
elseif($action == "edit") {
    $postaction .= $lang['texteditpost'];
}

if($forums['type'] == "forum") {
    $postaction = "<a href=\"forumdisplay.php?fid=$fid\">".stripslashes($forums['name']) ."</a> &raquo; ";
} else {
    $query = $db->query("SELECT name, fid FROM $table_forums WHERE fid='$forums[fup]'");
    $fup = $db->fetch_array($query);
    $postaction = "<a href=\"forumdisplay.php?fid=$fup[fid]\">".stripslashes($fup['name'])."</a> &raquo; <a href=\"forumdisplay.php?fid=$fid\">".stripslashes($forums['name'])."</a> &raquo; ";
}


if($action != "edit" && !$tid) {
    $postaction .= $lang['textpostnew'];
}

if($forums['attachstatus'] != "off") {
    eval("\$attachfile = \"".template("post_attachmentbox")."\";");
}

if(!$xmbuser || !$xmbpw) {
    eval("\$loggedin = \"".template("post_notloggedin")."\";");
} else {
    eval("\$loggedin = \"".template("post_loggedin")."\";");
}
$navigation = "&raquo; $postaction";

if ($self['ban'] == "posts" || $self['ban'] == "both") {
    error($lang['textbanfrompost']);
}

if($self['status'] == "Banned") {
    error($lang['bannedmessage']);
}

$listed_icons = 0;
$icons = '';

if($action != 'edit') {
    if(!X_STAFF){
        $querysmilie = $db->query("SELECT * FROM $table_smilies WHERE type='picon' AND (url NOT LIKE '%rsvd%')");
        while($smilie = $db->fetch_array($querysmilie)) {
            $icons .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" /><img src=\"$smdir/$smilie[url]\" alt=\"$smilie[code]\" />";
            $listed_icons += 1;
            if($listed_icons == 9) {
                $icons .= "<br />";
                $listed_icons = 0;
            }
        }
    } else {
        $querysmilie = $db->query("SELECT * FROM $table_smilies WHERE type='picon'");
        while($smilie = $db->fetch_array($querysmilie)) {
            $icons .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" /><img src=\"$smdir/$smilie[url]\" alt=\"$smilie[code]\" />";
            $listed_icons += 1;
            if($listed_icons == 9) {
                $icons .= "<br />";
                $listed_icons = 0;
            }
        }
    }
}

eval('$bbcodescript = "'.template('functions_bbcode').'";');

// BEGIN TOPIC SUBJECT PREFIXES

 {
	
	$prefix = "<select name=\"subjectprefix\">
    <option value=\"\" selected>&nbsp;&nbsp;</option>
    <option value=\"[UNRESOLVED]\">[UNRESOLVED]&nbsp;&nbsp;</option>
	<option value=\"[RESOLVED]\">[RESOLVED]&nbsp;&nbsp;</option>
	<option value=\"[QUESTION]\">[QUESTION]&nbsp;&nbsp;</option>
	<option value=\"[HELP ME]\">[HELP ME]&nbsp;&nbsp;</option>
	<option value=\"[NEED HELP?\">[NEED HELP?]&nbsp;&nbsp;</option>
	<option value=\"[ANNOUNCEMENT\">!ANNOUNCEMENT!&nbsp;&nbsp;</option>
    </select>\n";
}

// END TOPIC SUBJECT PREFIXES

if($forums['allowimgcode'] == "yes") {
    $allowimgcode = "$lang[texton]";
} else {
    $allowimgcode = "$lang[textoff]";
}

if($forums['allowhtml'] == "yes") {
    $allowhtml = "$lang[texton]";
} else {
    $allowhtml = "$lang[textoff]";
}

if($forums['allowsmilies'] == "yes") {
    $allowsmilies = "$lang[texton]";
} else {
    $allowsmilies = "$lang[textoff]";
}

if($forums['allowbbcode'] == "yes") {
    $allowbbcode = "$lang[texton]";
} else {
    $allowbbcode = "$lang[textoff]";
}
if($action == 'newthread'){
    $pperm['type'] = 'thread';
}else{
    $pperm['type'] = 'reply';
}


if(!postperm($forums, $pperm['type'])){
    error($lang['privforummsg']);
}

if($xmbuser && $xmbuser != '') {
    if($sig != '') {
        $usesigcheck = 'checked';
    }else{
        $usesigcheck = '';
    }
}

if(!$xmbuser && $forums['guestposting'] == 'on') {
    $guestpostingmsg = $lang['guestpostingonmsg'];
}else{
    $guestpostingmsg = '';
}


if($posterror) {
    error($posterror);
}

// Start forum password check
if($forums['password'] != ${'fidpw'.$fid} && $forums['password'] != "" && $self['status'] != 'Super Administrator') {
    eval("\$header = \"".template("header")."\";");
    echo $header;
    eval("\$pwform = \"".template("forumdisplay_password")."\";");
    echo $pwform;

    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    exit();
}
$setquery = $db->query("SELECT * FROM $table_shop_settings");
$ssettings = $db->fetch_array($setquery);

$query = $db->query("SELECT * FROM $table_forums WHERE fid='$fid'");
$forum = $db->fetch_array($query);
$authorization = privfcheck($forum['private'], $forum['userlist']);
if(!$authorization) {
    error($lang['privforummsg']);
}

// Start temp-spellcheck //
if($SETTINGS['spellcheck'] == 'on'){
    $spelling_submit1 = '<input type="hidden" name="subaction" value="spellcheck"><input type="submit" class="submit" name="spellchecksubmit" value="Check Spelling" />';
    $spelling_lang = '<select name="language"><option value="en" selected>English</option></select>';

    if($subaction == 'spellcheck' && (isset($spellchecksubmit) || isset($spellcheckersubmit))){
        if(!$updates_submit){
            $subject = checkInput(stripslashes($subject), '', '', '', true);
            $message = checkInput(stripslashes($message), '', '', '', true);
            require './classes/spelling.php';
            $spelling = new spelling($language);
            $problems = $spelling->check_text($message);
            if(count($problems) > 0){
                foreach($problems as $orig=>$new){
                    $mistake = array();
                    foreach($new as $suggestion){
                        eval('$mistake[] = "'.template('spelling_suggestion_new').'";');
                    }
                    $mistake = implode("\n", $mistake);
                    eval('$suggest[] = "'.template('spelling_suggestion_row').'";');
                }
                $suggestions = implode("\n", $suggest);
                eval('$suggestions = "'.template('spelling_suggestion').'";');
            }else{
                eval('$suggestions = "'.template('spelling_suggestion_no').'";');
            }
        }else{
            foreach($old_words as $word){
                $message = str_replace($word, ${'replace_'.$word}, $message);
            }
        }
    }else{
        $suggestions = '';
    }
}else{
    $spelling_submit1 = '';
    $spelling_lang = '';
    $suggestions = '';
}
// End temp-spellcheck //

$bbcodeinsert = bbcodeinsert();
$smilieinsert = smilieinsert();

if($previewpost) {
    $currtime = time();
    $date = gmdate("n/j/y",$currtime + ($timeoffset * 3600) + ($addtime * 3600));
    $time = gmdate("H:i",$currtime + ($timeoffset * 3600) + ($addtime * 3600));
    $poston = "$lang[textposton] $date $lang[textat] $time";

    if($posticon != '') {
        $thread['icon'] = (file_exists($smdir.'/'.$posticon)) ? "<img src=\"$smdir/$posticon\" />" : '';
    }else{
        $thread['icon'] = '';
    }

    $subject = checkInput(stripslashes($subject), '', '', '', true);
    $message = checkInput(stripslashes($message), '', '', '', true);

    $message1 = postify($message, $smileyoff, $bbcodeoff, $forums['allowsmilies'], $forums['allowhtml'], $forums['allowbbcode'], $forums['allowimgcode']);

    if($smileyoff == "yes") {
        $smileoffcheck = "checked=\"checked\"";
    }

    if($usesig == "yes") {
        $usesigcheck = "checked=\"checked\"";
    }

    if($bbcodeoff == "yes") {
        $codeoffcheck = "checked=\"checked\"";
    }

    if($subject != "") {
        $dissubject = stripslashes($subject);
    } else {
        $dissubject = "";
    }
    eval("\$preview = \"".template("post_preview")."\";");
    $preview = stripslashes($preview);
}

if($action == "newthread") {
    $priv = privfcheck($private, $userlist);
    if($forums['mpnt'] != "0" && $self['postnum'] < $forums['mpnt'] && $self['status'] != "Super Administrator") {
        $message = str_replace("*posts*", $forums['mpnt'], $lang['mpnte']); 
        error($message, true, '', '', false, true, false, true);
    }

    if(!isset($topicsubmit) || !$topicsubmit) {
        eval("\$header = \"".template("header")."\";");
        echo $header;
        $status1 = modcheck($self['status'], $xmbuser, $forums['moderator']);
        if($self['status'] == "Super Moderator") {
            $status1 = "Moderator";
        }

        if(X_STAFF) {
            $topoption = "<br /><input type=\"checkbox\" name=\"toptopic\" value=\"yes\" /> $lang[topmsgques]";
            $closeoption = "<br /><input type=\"checkbox\" name=\"closetopic\" value=\"yes\" /> $lang[closemsgques]<br />";
        }else{
            $topoption = '';
            $closeoption = '';
        }

        if($poll == "yes" && $forums['pollstatus'] != "off") {
            eval("\$postform = \"".template("post_newpoll")."\";");
            echo $postform;
        } else {
            eval("\$postform = \"".template("post_newthread")."\";");
            echo $postform;
        }
    }else{
        if(!empty($username) && !empty($password)) {
            if (empty($xmbuser) && empty($xmbpw)) {
                $password = md5($password);
            }
            $q = $db->query("SELECT * FROM $table_members WHERE username='$username'");
            if ($db->num_rows($q) != 1) {
                error($lang['badname']);
            }
            else {
                $self = $db->fetch_array($q);
                if ($password != $self['password']) {
                    error($lang['textpw1']);
                }
                $username = $self['username'];
            }
            if ($self['status'] == "Banned") {
                error($lang['bannedmessage']);
            }
            $currtime = time() + (86400*30);
            setcookie("xmbuser", $username, $currtime, $cookiepath, $cookiedomain);
            setcookie("xmbpw", $password, $currtime, $cookiepath, $cookiedomain);
            if ($self['ban'] == "posts" || $self['ban'] == "both") {
                error($lang['textbanfrompost']);
            }
        }
        else {
            if(empty($xmbuser) && empty($xmbpw)){
                $username = "Anonymous";
            }else{
                $username = $xmbuser;
            }
        }

        if($forums['guestposting'] != "on" && $username == "Anonymous") {
            error($lang['textnoguestposting']);
        }
        if($subject == "" || ereg("^ *$", $subject)) {
            error($lang['textnosubject']);
        }

        $pperm = explode("|", $forums['postperm']);

        if($pperm[0] == "2" && !X_ADMIN) {
            error($lang['postpermerr']);
        } elseif($pperm[0] == "3" && !X_STAFF) {
            error($lang['postpermerr']);
        } elseif($pperm[0] == "4") {
            error($lang['postpermerr']);
        }

        $query = $db->query("SELECT lastpost, type, fup FROM $table_forums WHERE fid='$fid'");
        $for = $db->fetch_array($query);

        if($for['lastpost'] != "") {
            $lastpost = explode("|", $for['lastpost']);
            $rightnow = time() - $floodctrl;

            if($rightnow <= $lastpost[0] && $username == $lastpost[1]) {
                error($lang['floodprotect'].' '.$floodlink.' '.$lang['tocont']);
            }
        }

        $message = addslashes($message);
        $subject = addslashes($subject);
		$subjectprefix = addslashes($subjectprefix);

        if($usesig != "yes") {
            $usesig = 'no';
        }

        if($forums['pollstatus'] != "off") {
            $pollanswers = checkInput($pollanswers);
            if(strpos($pollanswers, '#|#') !== false || strpos($pollanswers, '||~|~||') !== false){
                $pollanswers = '';
            }else{
                $pollops = explode("\n", $pollanswers);
                $pollanswers = "";
                $pnumnum = count($pollops);
                if($pnumnum < 2 && $pollanswers != ''){
                    error($lang['too_few_pollopts']);
                }
                for($pnum = 0; $pnum < $pnumnum; $pnum++) {
                    if($pollops[$pnum] != "") {
                        $pollanswers .= "$pollops[$pnum]||~|~|| 0#|#";
                    }
                }

                $pollanswers = str_replace("\n", '', $pollanswers);
            }
        }

        if($posticon != "") {
            $query = $db->query("SELECT id FROM $table_smilies WHERE type='picon' AND url='$posticon'");

            if(!$db->result($query, 0)) {
                exit();
            }
        }

        $thatime = time();
        $subject = checkOutput($subject, 'on');
        $subject = checkInput($subject);

        $db->query("INSERT INTO $table_threads VALUES ('', '$fid', '$subjectprefix $subject', '$posticon', '$thatime|$username', '0', '0', '$username', '', '', '$pollanswers')");
        $tid = $db->insert_id();

        $message = checkOutput($message, 'on');
        $message = checkInput($message, '', '', '', true);

		$db->query("INSERT INTO $table_posts VALUES ('$fid', '$tid', '', '$username', '$message', '$subjectprefix $subject', ".$db->time($thatime).", '$posticon', '$usesig', '$onlineip', '$bbcodeoff', '$smileyoff')");
		
        $pid = $db->insert_id();

        // Insert Attachment if there is one
        if (($attachedfile = get_attached_file($_FILES['attach'], $forums['attachstatus'], $max_attach_size)) !== false) {
            $db->query("INSERT INTO $table_attachments VALUES ('', '$tid', '$pid', '$filename', '$filetype', '$filesize', '$attachedfile', '0')");
        }


        // Check if forum is subforum, if so, make lastpost on fup-forum
        if($forum['type'] == 'sub'){
            $db->query("UPDATE $table_forums SET lastpost='$thatime|$username', threads=threads+1, posts=posts+1 WHERE fid='$for[fup]'");
        }

        $db->query("UPDATE $table_forums SET lastpost='$thatime|$username', threads=threads+1, posts=posts+1 WHERE fid='$fid'");

        // Auto subscribe options
        if($emailnotify == "yes") {
            $query = $db->query("SELECT tid FROM $table_favorites WHERE tid='$tid' AND username='$xmbuser' AND type='subscription'");
            $thread = $db->fetch_array($query);
            if(!$thread) {
                $db->query("INSERT INTO $table_favorites VALUES ('$tid', '$username', 'subscription')");
            }
        }


        $db->query("UPDATE $table_members SET postnum=postnum+1, money=money+'$ssettings[mpt]' WHERE username like '$username'");

        if((X_STAFF) && $toptopic == "yes") {
            $db->query("UPDATE $table_threads SET topped='1' WHERE tid='$tid' AND fid='$fid'");
        }
        if((X_STAFF) && $closetopic == "yes") {
            $db->query("UPDATE $table_threads SET closed='yes' WHERE tid='$tid' AND fid='$fid'");
        }
        if(!$xmbuser || !$xmbpw) {
            $currtime = time() + (86400*30);
            setcookie("xmbuser", $username, $currtime, $cookiepath, $cookiedomain);
            setcookie("xmbpw", $password, $currtime, $cookiepath, $cookiedomain);
        }
        eval("\$header = \"".template("header")."\";");
        echo $header;
        echo "<center><span class=\"mediumtxt \">$lang[postmsg]</span></center>";


        $query = $db->query("SELECT count(*) FROM $table_posts WHERE tid='$tid'");
        $posts = $db->result($query, 0);

        if($posts > $ppp) {
            $topicpages = $posts / $ppp;
            $topicpages = ceil($topicpages);
        }else{
            $topicpages=1;
        }
        redirect("viewthread.php?tid=".$tid."&page=".$topicpages."#pid".$pid, 2, X_REDIRECT_JS);
    }
}elseif($action == "reply") {
    $navigation .= "<a href=\"viewthread.php?tid=$tid\">".$threadname."</a> &raquo; ".$lang['textreply'];
    $priv = privfcheck($private, $userlist);
    if($forums['mpnp'] != "0" && $self['postnum'] < $forums['mpnp'] && $self['status'] != "Super Administrator") {
        $message = str_replace("*posts*", $forums['mpnp'], $lang['mpnpe']); 
        error($message, true, '', '', false, true, false, true);
    }

    if(!isset($replysubmit) || !$replysubmit) {
        $posts = '';
        eval("\$header = \"".template("header")."\";");
        echo $header;

        if(X_STAFF) {
            $closeoption = "<br /><input type=\"checkbox\" name=\"closetopic\" value=\"yes\" /> $lang[closemsgques]<br />";
        }else{
            $closeoption = '';
        }

        // Start Reply With Quote
        if($repquote) {
            $query = $db->query("SELECT p.message, p.fid, p.author, f.private AS fprivate, f.userlist AS fuserlist FROM $table_posts p, $table_forums f WHERE pid='$repquote' AND f.fid=p.fid");
            $thaquote = $db->fetch_array($query);
            $quotefid = $thaquote['fid'];
            $message = censor($thaquote['message']);

            $authorization = privfcheck($thaquote['fprivate'], $thaquote['fuserlist']);
            if(!$authorization) {
                error($lang['privforummsg']);
            }

            $message = "[quote][i]$lang[origpostedby] $thaquote[author][/i]\n$message [/quote]";
            //$message = stripslashes($message);
        }
        // Start Topic/Thread Review
        $querytop = $db->query("SELECT COUNT(*) FROM $table_posts WHERE tid='$tid'");
        $replynum = $db->result($querytop, 0);

        if($replynum >= $ppp) {
            $threadlink = "viewthread.php?fid=$fid&tid=$tid";
            eval($lang['evaltrevlt']);
            eval("\$posts .= \"".template("post_reply_review_toolong")."\";");
        }else{
            $thisbg = $altbg1;
            $query = $db->query("SELECT * FROM $table_posts WHERE tid='$tid' ORDER BY dateline DESC");
            while($post = $db->fetch_array($query)) {
                $date = gmdate($dateformat, $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
                $time = gmdate($timecode, $post['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));

                $poston = "$lang[textposton] $date $lang[textat] $time";
                if($post['icon'] != "") {
                    $post['icon'] = "<img src=\"$smdir/$post[icon]\" alt=\"$lang[altpostmood]\" />";
                }

                $post['message'] = stripslashes($post['message']);
                $post['message'] = postify($post['message'], $post['smileyoff'], $post['bbcodeoff'], $forums['allowsmilies'], $forums['allowhtml'], $forums['allowbbcode'], $forums['allowimgcode']);
                eval("\$posts .= \"".template("post_reply_review_post")."\";");
                if($thisbg == $altbg2) {
                    $thisbg = $altbg1;
                } else {
                    $thisbg = $altbg2;
                }
            }
        }
        // Start Displaying the Post form
        if($forums['attachstatus'] != "off") {
            eval("\$attachfile = \"".template("post_attachmentbox")."\";");
        }
        eval("\$postform = \"".template("post_reply")."\";");
        echo stripslashes($postform);
    }else{
        if(!$subject && !$message){
            error($lang['postnothing']);
        }
        if((empty($password) && empty($password)) && (!empty($xmbuser) && !empty($xmbpw))){
            $username = $xmbuser;
            $password = $xmbpw;
        }
        if(!empty($username) && !empty($password)) {
            if (empty($xmbuser) && empty($xmbpw)) {
                $password = md5($password);
            }
            $q = $db->query("SELECT * FROM $table_members WHERE username='$username'");
            if ($db->num_rows($q) != 1) {
                error($lang['badname']);
            }
            else {
                $self = $db->fetch_array($q);
                if ($password != $self['password']) {
                    error($lang['textpw1']);
                }
                $username = $self['username'];
            }
            if ($self['status'] == "Banned") {
                error($lang['bannedmessage']);
            }
            $currtime = time() + (86400*30);
            setcookie("xmbuser", $username, $currtime, $cookiepath, $cookiedomain);
            setcookie("xmbpw", $password, $currtime, $cookiepath, $cookiedomain);
            if ($self['ban'] == "posts" || $self['ban'] == "both") {
                error($lang['textbanfrompost']);
            }
        }
        else {
            if(empty($xmbuser) && empty($xmbpw)){
                $username = "Anonymous";
            }else{
                $username = $xmbuser;
            }
        }

        if($forums['guestposting'] != "on" && $username == "Anonymous") {
            error($lang['textnoguestposting']);
        }

        $pperm = explode("|", $forums['postperm']);

        if($pperm[1] == "2" && !X_ADMIN) {
            error($lang['postpermerr']);
        } elseif($pperm[1] == "3" && !X_STAFF) {
            error($lang['postpermerr']);
        } elseif($pperm[1] == "4") {
            error($lang['postpermerr']);
        }

        if($posticon != "") {
            $query = $db->query("SELECT id FROM $table_smilies WHERE type='picon' AND url='$posticon'");

            if(!$db->result($query, 0)) {
                exit();
            }
        }

        $query = $db->query("SELECT lastpost, type, fup FROM $table_forums WHERE fid='$fid'");
        $for = $db->fetch_array($query);
        $last = $for['lastpost'];

        if($last != "") {
            $lastpost = explode("|", $last);
            $rightnow = time() - $floodctrl;

            if($rightnow <= $lastpost[0] && $username == $lastpost[1]) {
                $floodlink = "<a href=\"viewthread.php?fid=$fid&tid=$tid\">Click here</a>";
                error($lang['floodprotect'].' '.$floodlink.' '.$lang['tocont']);
            }
        }
        $message = addslashes($message);

        if($usesig != "yes") {
            $usesig = "no";
        }

        $subject = addslashes($subject);
		$subjectprefix = addslashes($subjectprefix);

        $query = $db->query("SELECT closed,topped FROM $table_threads WHERE fid=$fid AND tid=$tid");
        $closed1 = $db->fetch_array($query);
        $closed = $closed1['closed'];
        if($closed == "yes" && !X_STAFF) {
            error($lang['closedmsg']);
        } else {
            $thatime = time();
            $subject = checkOutput($subject, 'on');
            $message = checkOutput($message, 'on');
            $subject = checkInput($subject, '', '', '', true);
            $message = checkInput($message, '', '', '', true);

$db->query("INSERT INTO $table_posts VALUES ('$fid', '$tid', '', '$username', '$message', '$subjectprefix $subject', ".$db->time($thatime).", '$posticon', '$usesig', '$onlineip', '$bbcodeoff', '$smileyoff')");
            $pid = $db->insert_id();

            if((X_STAFF) && $closetopic == "yes") {
                $db->query("UPDATE $table_threads SET closed='yes' WHERE tid='$tid' AND fid='$fid'");
            }

            // Insert Attachment if there is on
            if (($attachedfile = get_attached_file($_FILES['attach'], $forums['attachstatus'], $max_attach_size)) !== false) {
                $db->query("INSERT INTO $table_attachments VALUES ('', '$tid', '$pid', '$filename', '$filetype', '$filesize', '$attachedfile', '0')");
            }

            $db->query("UPDATE $table_threads SET lastpost='$thatime|$username', replies=replies+1 WHERE (tid='$tid' AND fid='$fid') OR closed='moved|$tid'");

            if($for['type'] == 'sub'){
                $db->query("UPDATE $table_forums SET lastpost='$thatime|$username', posts=posts+1 WHERE fid='$for[fup]'");
            }
            $db->query("UPDATE $table_forums SET lastpost='$thatime|$username', posts=posts+1 WHERE fid='$fid'");
            $db->query("UPDATE $table_members SET postnum=postnum+1, money=money+'$ssettings[mpp]' WHERE username='$username'");

            // Start Subsciptions

            $query = $db->query("SELECT count(pid) FROM $table_posts WHERE tid='$tid'");
            $count = $db->result($query,0);

            if($ppp < 1){
                $ppp = 20;
            }

            $query =$db->query("SELECT COUNT(*) FROM $table_posts WHERE pid<=$pid AND tid='$tid' AND fid='$fid'");
            $post = $db->result($query,0);
            $postsnum = $post;

            if($postsnum > $ppp) {
                $posts = $postsnum;
                $topicpages = $posts / $ppp;
                $topicpages = ceil($topicpages);
            }

            redirect("viewthread.php?tid=${tid}&page=${topicpages}#pid${pid}", 2, X_REDIRECT_JS);

            // let's get the time for the previous post.
            $date = $db->result($db->query("SELECT dateline FROM $table_posts WHERE tid='$tid' AND pid < '$pid' ORDER BY pid ASC LIMIT 1"), 0);
            $subquery = $db->query("SELECT f.*, m.* FROM $table_favorites f, $table_members m WHERE f.type='subscription' AND f.tid='$tid' AND m.username=f.username AND f.username != '$username'");
            while($subs = $db->fetch_array($subquery)) {
                if($subs['lastvisit'] < $date) { // don't send double mails...!
                    continue;
                }
                if($postsnum > $subs['ppp']) {
                    $topicpages = $postsnum / $subs['ppp'];
                    $topicpages = ceil($topicpages);
                } else {
                    $topicpages = 1;
                }

                $threadurl = $SETTINGS['boardurl'] . 'viewthread.php?tid='.$tid.'&page='.$topicpages.'#pid'.$pid;
                mail($subs['email'], $lang['textsubsubject'].' '.$threadname, $username.' '.$lang['textsubbody']." \n".$threadurl, "From: $bbname <$adminemail>");
            }

            // End Subscriptions
            // Auto subscribe options
            if($emailnotify == "yes") {
                $query = $db->query("SELECT tid FROM $table_favorites WHERE tid='$tid' AND username='$xmbuser' AND type='subscription'");
                if($db->num_rows($query) < 1) {
                    $db->query("INSERT INTO $table_favorites VALUES ('$tid', '$username', 'subscription')");
                }
            }

        }

        if(!$xmbuser || !$xmbpw) {
            $currtime = time() + (86400*30);
            setcookie("xmbuser", $username, $currtime, $cookiepath, $cookiedomain);
            setcookie("xmbpw", $password, $currtime, $cookiepath, $cookiedomain);
        }
        eval("\$header = \"".template("header")."\";");
        echo $header;
        echo "<center><span class=\"mediumtxt \">$lang[replymsg]</span></center>";

        if($count > $ppp) {
            $topicpages = $posts / $ppp;
            $topicpages = ceil($topicpages);
        }else{
            $topicpages=1;
        }
        redirect("viewthread.php?tid=${tid}&page=${topicpages}#pid${pid}", 2, X_REDIRECT_JS);
    }

}elseif($action == "edit") {
    $navigation .= "<a href=\"viewthread.php?tid=$tid\">".$threadname."</a>" . ' &raquo; ' . $lang['texteditpost'];
    if(!isset($editsubmit)) {
        eval("\$header = \"".template("header")."\";");
        echo $header;
        $queryextra = $db->query("SELECT f.* FROM $table_forums f LEFT JOIN $table_posts p ON (f.fid = p.fid) WHERE p.tid='$tid' AND p.pid='$pid'");
        $forum = $db->fetch_array($queryextra);

        $authorization = privfcheck($forum['private'], $forum['userlist']);
        if(!$authorization) {
            $header = '';
            error($lang['privforummsg']);
        }

        if($forum['password'] != ${"fidpw$fid"} && $forum['password'] != "" && $self['status'] != 'Super Administrator') {
            $url = "viewthread.php?tid=$tid&action=pwverify";
            eval("\$pwform = \"".template("forumdisplay_password")."\";");
            echo $pwform;

            end_time();
            eval("\$footer = \"".template("footer")."\";");
            echo $footer;
            exit();
        }

        if($previewpost){
            $postinfo = array("usesig"=>$usesig, "bbcodeoff"=>$bbcodeoff, "smileyoff"=>$smileyoff, "message"=>$message, "subject"=>$subject);
            $query = $db->query("SELECT filename, filesize, downloads FROM $table_attachments WHERE pid='$pid' AND tid='$tid'");
            $postinfo = array_merge($postinfo, $db->fetch_array($query));
        }else{
            $query = $db->query("SELECT a.filename, a.filesize, a.downloads, p.* FROM $table_posts p LEFT JOIN $table_attachments a  ON (a.pid = p.pid) WHERE p.pid='$pid' AND p.tid='$tid' AND p.fid='$forum[fid]'");
            $postinfo = $db->fetch_array($query);
        }

        if($postinfo['usesig'] == "yes") {
            $checked = "checked=\"checked\"";
        }

        $postinfo['message'] = stripslashes($postinfo['message']);

        if($postinfo['bbcodeoff'] == "yes") {
            $offcheck1 = "checked=\"checked\"";
        }

        if($postinfo['smileyoff'] == "yes") {
            $offcheck2 = "checked=\"checked\"";
        }

        if($postinfo['usesig'] == "yes") {
            $offcheck3 = "checked=\"checked\"";
        }

        if(!X_STAFF){
            $querysmilie = $db->query("SELECT * FROM $table_smilies WHERE type='picon' AND (url NOT LIKE '%rsvd%')");
            while($smilie = $db->fetch_array($querysmilie)) {
                if($postinfo['icon'] == $smilie['url']){
                    $icons .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" checked=\"checked\"/><img src=\"$smdir/$smilie[url]\" alt=\"$smilie[code]\" />";
                }else{
                    $icons .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" /><img src=\"$smdir/$smilie[url]\" alt=\"$smilie[code]\" />";
                }
                $listed_icons += 1;
                if($listed_icons == 9) {
                    $icons .= "<br />";
                    $listed_icons = 0;
                }
            }
        } else {
            $querysmilie = $db->query("SELECT * FROM $table_smilies WHERE type='picon'");
            while($smilie = $db->fetch_array($querysmilie)) {
                if($postinfo['icon'] == $smilie['url']){
                    $icons .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" checked=\"checked\"/><img src=\"$smdir/$smilie[url]\" alt=\"$smilie[code]\" />";
                }else{
                    $icons .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" /><img src=\"$smdir/$smilie[url]\" alt=\"$smilie[code]\" />";
                }
                $listed_icons += 1;
                if($listed_icons == 9) {
                    $icons .= "<br />";
                    $listed_icons = 0;
                }
            }
        }

        $postinfo['subject'] = stripslashes($postinfo['subject']);
        $postinfo['subject'] = str_replace('"', "&quot;", $postinfo['subject']);

        if($self['status'] != 'Super Administrator'){
            $postinfo['subject'] = censor($postinfo['subject']);
        }

        $message = $postinfo['message'];
        $subject = $postinfo['subject'];

        if($previewpost) {
            $message = censor($message);
        }
        if($postinfo['filename'] != ''){
            eval('$attachment = "'.template('post_edit_attachment').'";');
        }else{
            $attachment = $attachfile;
        }
        eval("\$edit = \"".template("post_edit")."\";");
        echo $edit;
    }else{
        if (empty($xmbuser) && empty($xmbpw)) {
            $password = md5($password);
        }
        $q = $db->query("SELECT * FROM $table_members WHERE username='$username'");
        if ($db->num_rows($q) != 1) {
            error($lang['badname']);
        }
        else {
            $self = $db->fetch_array($q);
            if ($password != $self['password']) {
                error($lang['textpw1']);
            }
            $username = $self['username'];
        }
        if ($self['status'] == "Banned") {
            error($lang['bannedmessage']);
        }
        $currtime = time() + (86400*30);
        setcookie("xmbuser", $username, $currtime, $cookiepath, $cookiedomain);
        setcookie("xmbpw", $password, $currtime, $cookiepath, $cookiedomain);
        if ($self['ban'] == "posts" || $self['ban'] == "both") {
            error($lang['textbanfrompost']);
        }

        $date = gmdate($dateformat);
        if ($SETTINGS['editedby'] == 'on'){
            $message .= "\n\n[$lang[textediton] $date $lang[textby] $username]";
        }

        $subject = addslashes($subject);
		$subjectprefix = addslashes($subjectprefix);


        $status1 = modcheck($self['status'], $username, $forums['moderator']);
        if($self['status'] == "Super Moderator") {
            $status1 = "Moderator";
        }

        if($posticon != "") {
            $query = $db->query("SELECT id FROM $table_smilies WHERE type='picon' AND url='$posticon'");

            if(!$db->result($query, 0)) {
                exit();
            }
        }

        $query = $db->query("SELECT pid FROM $table_posts WHERE tid='$tid' ORDER BY dateline LIMIT 1");
        $isfirstpost = $db->fetch_array($query);

        if(trim($subject) == '' && $pid == $isfirstpost['pid']){
            error($lang['textnosubject']);
        }

        if($attachment_action == 'edit'){
            $file = get_attached_file($_FILES['attach'], $forums['attachstatus'], $max_attach_size);

            $update_attachment = 'yes';
        }elseif($attachment_action == 'rename' && trim($attach_name) != ''){
            $attach_rename = $attach_name;
        }elseif($attachment_action == 'delete'){
            $delete_attachment = 'yes';
        }



        $subject = checkOutput($subject, 'on');
        $subject = checkInput($subject);

        $message = checkOutput($message, 'on');
        $message = checkInput($message, '', '', '', true);

        $posticon = htmlspecialchars($posticon);

        $query = $db->query("SELECT p.author as author, m.status as status FROM $table_posts p LEFT JOIN $table_members m ON p.author=m.username WHERE pid='$pid' AND tid='$tid' AND fid='$fid'");
        $orig = $db->fetch_array($query);

        $message = addslashes($message);
        if((X_STAFF && $status1 == 'Moderator') || $username == $orig['author']) {
            if($SETTINGS['allowrankedit'] != 'off'){

                switch($orig['status']){
                    case 'Super Administrator':
                        if($self[status] != 'Super Administrator' && $xmbuser != $orig['author']){
                            error($lang['noedit']);
                        }
                        break;

                    case 'Administrator':
                        if(($self['status'] != 'Super Administrator' && $self['status'] != 'Administrator') && $xmbuser != $orig['author']){
                        error($lang['noedit']);
                    }
                    break;

                    case 'Super Moderator':
                        if(($self['status'] != 'Super Administrator' && $self['status'] != 'Administrator' && $self['status'] != 'Super Moderator') && $xmbuser != $orig['author']){
                            error($lang['noedit']);
                        }
                        break;

                    case 'Moderator':
                        if(($self['status'] != 'Super Administrator' && $self['status'] != 'Administrator' && $self['status'] != 'Super Moderator') && $xmbuser != $orig['author']){
                            error($lang['noedit']);
                        }
                        break;
                }
            }

            if($isfirstpost['pid'] == $pid) {
                $db->query("UPDATE $table_threads SET icon='$posticon', subject='$subject' WHERE tid='$tid'");
            }

            if($delete != "yes") {
                $db->query("UPDATE $table_posts SET message='$message', usesig='$usesig', bbcodeoff='$bbcodeoff', smileyoff='$smileyoff', icon='$posticon', subject='$subject' WHERE pid='$pid'");

                if($update_attachment == 'yes'){
                    $db->query("DELETE FROM $table_attachments WHERE pid='$pid'");
                    $db->query("INSERT INTO $table_attachments VALUES ('', '$tid', '$pid', '$filename', '$attach[type]', '$filesize', '$file', '0')");
                }elseif($delete_attachment == 'yes'){
                    $db->query("DELETE FROM $table_attachments WHERE pid='$pid'");
                }elseif(!empty($attach_rename)){
                    $attach_rename = checkInput($attach_rename, '', '', '', true);
                    $db->query("UPDATE $table_attachments SET filename='$attach_rename' WHERE pid='$pid'");
                }elseif (($attachedfile = get_attached_file($_FILES['attach'], $forums['attachstatus'], $max_attach_size)) !== false) {
                    $db->query("INSERT INTO $table_attachments VALUES ('', '$tid', '$pid', '$filename', '$filetype', '$filesize', '$attachedfile', '0')");
                }
            } elseif($delete == "yes" && !($isfirstpost['pid'] == $pid)) {
                $db->query("UPDATE $table_members SET postnum=postnum-1, money=money-'$ssettings[spd]' WHERE username='$orig[author]'");
                $db->query("DELETE FROM $table_attachments WHERE pid='$pid'");
                $db->query("DELETE FROM $table_posts WHERE pid='$pid'");
                updateforumcount($fid);
                updatethreadcount($tid);

            } elseif($delete == "yes" && $isfirstpost['pid'] == $pid) {
$query = $db->query("SELECT pid, author FROM $table_posts WHERE tid='$tid'");
while($result = $db->fetch_array($query)) {
	if($isfirstpost['pid'] == $result['pid']) {
		$db->query("UPDATE $table_members SET postnum=postnum-1, money=money-'$ssettings[std]' WHERE username='$result[author]'");
	} else {
		$db->query("UPDATE $table_members SET postnum=postnum-1, money=money-'$ssettings[spd]' WHERE username='$result[author]'");
	}
}
                $db->query("DELETE FROM $table_threads WHERE tid='$tid'");
                $threaddelete = 'yes';
                    $db->query("DELETE FROM $table_attachments WHERE tid='$tid'");
                    $db->query("DELETE FROM $table_posts WHERE tid='$tid'");
                updateforumcount($fid);
                updatethreadcount($tid);
            }
        } else {
            error($lang['noedit']);
        }
        eval("\$header = \"".template("header")."\";");
        echo $header;
        echo "<center><span class=\"mediumtxt \">$lang[editpostmsg]</span></center>";

        if($threaddelete != 'yes'){
            $query =$db->query("SELECT COUNT(*) FROM $table_posts WHERE pid<=$pid AND tid='$tid' AND fid='$fid'");
            $post = $db->result($query,0);
            $postsnum = $post;

            if($postsnum > $ppp) {
                $posts = $postsnum;
                $topicpages = $posts / $ppp;
                $topicpages = ceil($topicpages);
            }else{
                $topicpages = 1;
            }

            redirect("viewthread.php?tid=${tid}&page=${topicpages}#pid${pid}", 2, X_REDIRECT_JS);
        }else{
            redirect("forumdisplay.php?fid=$fid", 2, X_REDIRECT_JS);
        }
    }
}else{
    error($lang['textnoaction']);
}

end_time();

eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>