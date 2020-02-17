<?php
/* $Id: forumdisplay.php,v 1.3 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require "./header.php";
loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','forumdisplay_newtopic','forumdisplay_newpoll','forumdisplay_password','forumdisplay_thread','forumdisplay_invalidforum','forumdisplay_nothreads','forumdisplay','forumdisplay_subforum_lastpost','forumdisplay_thread_lastpost','forumdisplay_admin','forumdisplay_thread_admin','viewthread_newpoll','viewthread_newtopic','header','footer','css','functions_bbcode','forumdisplay_subforum','forumdisplay_subforums');
eval("\$css = \"".template('css')."\";");
$fid = (int) $fid;
$tid = (int) $tid;
$notexist = false;

$query = $db->query("SELECT * FROM $table_forums WHERE fid='$fid'");
$forum = $db->fetch_array($query);

if($forum['type'] != "forum" && $forum['type'] != "sub" || !$fid) {
    $notexist = $lang['textnoforum'];
}

if($forum[type] == 'sub'){
    $query = $db->query("SELECT name, fid FROM $table_forums WHERE fid='$forum[fup]'");
    $fup = $db->fetch_array($query);
}elseif($forum[type] != 'forum'){
    error($notexist);
}

$authorization = privfcheck($forum['private'], $forum['userlist']);
if(!$authorization) {
    error($lang['privforummsg']);
}

pwverify($forum['password'], 'forumdisplay.php?fid='.$fid);

if($forum['type'] == "forum") {
    $navigation .= "&raquo; ".stripslashes($forum['name']);
} elseif($forum['type'] == 'sub'){
    $navigation .= "&raquo; <a href=\"forumdisplay.php?fid=$fup[fid]\">".stripslashes($fup[name])."</a> &raquo; ".stripslashes($forum[name]);
}

smcwcache();

eval("\$header = \"".template("header")."\";");
echo $header;

// Start subforums
$query = $db->query("SELECT * FROM $table_forums WHERE type='sub' AND fup='$fid' AND status='on' ORDER BY displayorder");

if($db->num_rows($query) != 0) {
    $forumlist = '';
    $fulist = $forum['userlist'];
    while($sub = $db->fetch_array($query)) {
        $forumlist .= forum($sub, "forumdisplay_subforum");
    }
    $forum['userlist'] = $fulist;
    eval("\$subforums = \"".template("forumdisplay_subforums")."\";");
}
// End subforums

if(!$notexist){
    if(!postperm($forum, 'thread')){
        $newtopiclink = '';
        $newpolllink = '';
    }else{
        eval("\$newtopiclink = \"".template("viewthread_newtopic")."\";");
        if($forum['pollstatus'] != "off") {
            eval("\$newpolllink = \"".template("viewthread_newpoll")."\";");
        }
    }
}

// Start Topped Image Processing
$t_extension = strtolower(substr(strrchr($lang['toppedprefix'], '.'),1));
if($t_extension == 'gif' || $t_extension == 'jpg' || $t_extension == 'jpeg' || $t_extension == 'png'){
    $lang['toppedprefix'] = "<img src=\"$imgdir/$lang[toppedprefix]\" />";
}

// Start Poll Image Processing
$p_extension = strtolower(substr(strrchr($lang['pollprefix'], '.'),1));
if($p_extension == 'gif' || $p_extension == 'jpg' || $p_extension == 'jpeg' || $p_extension == 'png'){
    $lang['pollprefix'] = "<img src=\"$imgdir/$lang[pollprefix]\" />";
}

if(!$tpp || $tpp == '') {
    $tpp = (int) $topicperpage;
} else {
    $tpp = (int) $tpp;
}

if($page) {
    $start_limit = ($page-1) *$tpp;
} else {
    $start_limit = 0;
    $page = 1;
}
if($cusdate != 0) {
    $cusdate = time() - $cusdate;
    $cusdate = "AND (substring_index(lastpost, '|',1)+1) >= '$cusdate'";
}
elseif($cusdate == 0) {
    $cusdate = "";
}

if(strtolower($ascdesc) != 'asc') {
    $ascdesc = 'desc';
}


if($dotfolders == "on" && $xmbuser != "") {
    $dotadd1 = "DISTINCT p.author AS dotauthor, ";
    $dotadd2 = "LEFT JOIN $table_posts p ON (t.tid = p.tid AND p.author = '$xmbuser')";
}else{
    $dotadd1 = '';
    $dotadd2 = '';
}
$querytop = $db->query("SELECT $dotadd1 t.* FROM $table_threads t $dotadd2 WHERE t.fid='$fid' $cusdate ORDER BY topped $ascdesc,lastpost $ascdesc LIMIT $start_limit, $tpp");

// Start Displaying the threads
$topicsnum = 0;

$status1 = modcheck($self[status], $xmbuser, $forum[moderator]);
if($self[status] == "Super Moderator" || $self[status] == "Super Administrator") {
    $status1 = "Moderator";
}

if($self[status] == "Administrator" || $status1 == "Moderator"){
    $forumdisplay_thread = "forumdisplay_thread_admin";
}else{
    $forumdisplay_thread = "forumdisplay_thread";
}

$threadlist = '';
while($thread = $db->fetch_array($querytop)) {
    $lastpost = explode("|", $thread['lastpost']);
    $dalast = trim($lastpost[0]);

    if($lastpost[1] != "Anonymous") {
        $lastpost[1] = "<a href=\"member.php?action=viewpro&amp;member=".rawurlencode(trim($lastpost[1]))."\">".trim($lastpost[1])."</a>";
    } else {
        $lastpost[1] = "$lang[textanonymous]";
    }

    $lastreplydate = gmdate($dateformat, $lastpost[0] + ($timeoffset * 3600) + ($addtime * 3600));
    $lastreplytime = gmdate($timecode, $lastpost[0] + ($timeoffset * 3600) + ($addtime * 3600));

    $lastpost = "$lastreplydate $lang[textat] $lastreplytime<br />$lang[textby] $lastpost[1]";
    eval("\$lastpostrow = \"".template("forumdisplay_thread_lastpost")."\";");
    if($thread['icon'] != "") {
        $thread['icon'] = "<img src=\"$smdir/$thread[icon]\" alt=\"$thread[icon]\" />";
    } else {
        $thread['icon'] = " ";
    }

    if($thread['replies'] >= $hottopic) {
        $folder = "hot_folder.gif";
    } else {
        $folder = "folder.gif";
    }

    if($thread['topped'] == 1) {
        $topimage = "<img src=\"images/admin/untop.gif\" alt=\"$lang[textuntopthread]\" border=\"0\" />";
         } else {
        $topimage = "<img src=\"images/admin/top.gif\" alt=\"$lang[alttopthread]\" border=\"0\" />";
    }

    //$lastvisit2 -= 540;
    if($thread['replies'] >= $hottopic && $lastvisit2 < $dalast && false === strpos($oldtopics, "|$thread[tid]|")) {
        $folder = "hot_red_folder.gif";
    }elseif($lastvisit2 < $dalast && false === strpos($oldtopics, "|$thread[tid]|")) {
        $folder = "red_folder.gif";
    }else {
        $folder = $folder;
    }

    $lastvisit2 += 540;
    if($dotfolders == "on" && $thread['dotauthor'] == $xmbuser && $xmbuser != "") {
        $folder = "dot_".$folder;
    }
    $folder = "<img src=\"$imgdir/$folder\" alt=\"$lang[altfolder]\" />";

    if($thread['closed'] == "yes") {
        $folder = "<img src=\"$imgdir/lock_folder.gif\" alt=\"$lang[altclosedtopic]\" />";
    }
    $thread['subject'] = stripslashes($thread['subject']);

    $authorlink = "<a href=\"member.php?action=viewpro&amp;member=".rawurlencode($thread['author'])."\">$thread[author]</a>";

    if(!$ppp || $ppp == '') {
        $ppp = $postperpage;
    }

    $postsnum = $thread[replies] + 1;
    if($postsnum  > $ppp) {
        $pagelinks = '';
        $posts = $postsnum;
        $topicpages = $posts / $ppp;
        $topicpages = ceil($topicpages);
        for ($i = 1; $i <= $topicpages; $i++) {
            $pagelinks .= " <a href=\"viewthread.php?tid=$thread[tid]&amp;page=$i\">$i</a> ";
            if($i == 3) {
                $i = $topicpages + 1;
            }
        }
        if($topicpages > 3) {
            $pagelinks .= " .. <a href=\"viewthread.php?tid=$thread[tid]&amp;page=$topicpages\">$topicpages </a>";
        }
        $multipage2 = "(<small>Pages: $pagelinks</small>)";
        $pagelinks = "";
    } else {
        $multipage2 = "";
    }

    $moved = explode("|", $thread[closed]);
    if($moved[0] == "moved") {
        $prefix = "$lang[moved] ";
        $thread['realtid'] = $thread['tid'];
        $thread['tid'] = $moved[1];
        $thread['replies'] = "-";
        $thread['views'] = "-";
        $folder = "<img src=\"$imgdir/lock_folder.gif\" alt=\"$lang[altclosedtopic]\" />";
    }else{
        $thread['realtid'] = $thread['tid'];
    }
    if($thread['pollopts'] != "") {
        $prefix = "$lang[poll] ";
    }
    if($thread['topped'] == 1) {
        $prefix = "$lang[toppedprefix] ";
    }


    $thread['subject'] = censor($thread['subject']);

    eval("\$threadlist .= \"".template($forumdisplay_thread)."\";");

    $prefix = "";
    $topicsnum++;
}
if($notexist) {
    eval("\$threadlist = \"".template("forumdisplay_invalidforum")."\";");
}

if($topicsnum == 0 && !$notexist) {
    eval("\$threadlist = \"".template("forumdisplay_nothreads")."\";");
}

switch($cusdate){
    case 86400:
        $check1 = "selected=\"selected\"";
        break;
    case 432000:
        $check5 = "selected=\"selected\"";
        break;
    case 1296000:
        $check15 = "selected=\"selected\"";
        break;
    case 2592000:
        $check30 = "selected=\"selected\"";
        break;
    case 5184000:
        $check60 = "selected=\"selected\"";
        break;
    case 8640000:
        $check100 = "selected=\"selected\"";
        break;
    case 31536000:
        $checkyear = "selected=\"selected\"";
        break;
    default:
        $checkall = "selected=\"selected\"";
        break;
}

// Do Multipaging
if(!$tpp || $tpp == '') {
    $tpp = $topicperpage;
}

if($page) {
    $start_limit = ($page-1) *$tpp;
} else {
    $start_limit = 0;
    $page = 1;
}

if($cusdate != 0) {
    $cusdate = time() - $cusdate;
} elseif($cusdate == 0) {
    $cusdate = "";
}

if(!$ascdesc) {
    $ascdesc = "DESC";
}

$query = $db->query("SELECT count(tid) FROM $table_threads WHERE fid='$fid'");
$topicsnum = $db->result($query, 0);
$mpurl = "forumdisplay.php?fid=$fid";
$multipage = multi($topicsnum, $tpp, $page, $mpurl);

if($self['status'] == "Administrator" || $status1 == "Moderator"){
    eval("\$forumdisplay = \"".template("forumdisplay_admin")."\";");
} else {
    eval("\$forumdisplay = \"".template("forumdisplay")."\";");
}

echo stripslashes($forumdisplay);

end_time();

eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>