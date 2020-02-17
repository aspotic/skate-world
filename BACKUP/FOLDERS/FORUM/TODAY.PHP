<?php
/* $Id: today.php,v 1.3 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require './header.php';
loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','today','today2','today3','header','footer','css','functions_bbcode', 'today_multipage');
$navigation .= '&raquo; Todays Posts';
eval("\$css = \"".template('css')."\";");

eval("\$header = \"".template("header")."\";");
echo $header;

// Check if todays posts is on/off
    if($todaysposts == 'off') {
     error($lang['fnasorry3'], false);
    }

smcwcache();

$srchfrom = time() - 86400;

if(!isset($ppp) || $ppp == 0){
    $ppp = 25;
}

if((int) $page > 0) {
    $start_limit = ((int) $page-1) * $ppp;
} else {
    $start_limit = 0;
    $page = 1;
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

$results = $db->result($db->query("SELECT count(*) FROM $table_threads t, $table_forums f WHERE $restrict t.lastpost >= '$srchfrom' AND t.fid=f.fid ORDER BY t.lastpost DESC"), 0);
$mpurl = "today.php";
$multipage = multi($results, $ppp, $page, $mpurl);
if($ppp >= $results){
    $multipage = '';
}else{
    eval('$multipage = "'.template('today_multipage').'";');
}

if($results == 0){
    error($lang['nopoststoday'], false);
}

$query = $db->query("SELECT count(p.pid) as posts, t.*, f.password, f.private, f.userlist, f.name FROM $table_threads t, $table_forums f, $table_posts p WHERE $restrict t.lastpost >= '$srchfrom' AND t.fid=f.fid AND p.tid=t.tid GROUP BY t.tid ORDER BY t.lastpost DESC LIMIT $start_limit, $ppp");
$today2 = array();
while($thread = $db->fetch_array($query)){
    $date = gmdate($dateformat, $thread['dateline'] + ($addtime * 3600));
    $time = gmdate($timecode, $thread['dateline'] + ($addtime * 3600));
    $poston = "$date $lang[textat] $time";
    $thread['subject'] = stripslashes($thread['subject']);

    $forum['private'] = $thread['private'];
    $forum['userlist'] = $thread['userlist'];
    $forum['name'] = $thread['name'];

    $authorization = privfcheck($forum['private'], $forum['userlist']);
    if($authorization == "true" || $self['status'] == 'Super Administrator') {
        if((($thread['password'] == $_COOKIE["fidpw".$thread['fid']]) || $thread['password'] == "") || $self['status'] == 'Super Administrator') {
            if($thread['author'] == $lang['textguest']) {
                $authorlink = $thread['author'];
            }else {
                $authorlink = "<a href=\"member.php?action=viewpro&amp;member=".rawurlencode($thread['author'])."\">$thread[author]</a>";
            }


            $lastpost = explode("|", $thread['lastpost']);
            $dalast = $lastpost[0];


            if($lastpost[1] == $lang['textguest']) {
                $lastpost[1] = $lastpost[1];
            } else {
                $lastpost[1] = "<a href=\"member.php?action=viewpro&amp;member=".rawurlencode($lastpost[1])."\">$lastpost[1]</a>";
            }

            $lastreplydate = gmdate($dateformat, $lastpost[0] + ($timeoffset * 3600) + ($addtime * 3600));
            $lastreplytime = gmdate($timecode, $lastpost[0] + ($timeoffset * 3600) + ($addtime * 3600));
            $lastpost = "$lang[lastreply1] $lastreplydate $lang[textat] $lastreplytime<br />$lang[textby] $lastpost[1]";


            if($thread['icon'] != "") {
                $thread['icon'] = "<img src=\"$imgdir/$thread[icon]\" />";
            } else {
                $thread['icon'] = "&nbsp;";
            }


            if($thread['replies'] >= $hottopic) {
                $folder = "<img src=\"$imgdir/hot_folder.gif\" alt=\"$lang[althotfolder]\" />";
            } else {
                $folder = "<img src=\"$imgdir/folder.gif\" alt=\"$lang[altfolder]\" />";
            }


            $lastvisit2 -= 540;
            if($thread['replies'] >= $hottopic && $lastvisit2 < $dalast) {
                $folder = "<img src=\"$imgdir/hot_red_folder.gif\" alt=\"$lang[althotredfolder]\" />";
            }elseif($lastvisit2 < $dalast) {
                $folder = "<img src=\"$imgdir/red_folder.gif\" alt=\"$lang[altredfolder]\" />";
            }else {
                $folder = $folder;
            }

            $lastvisit2 += 540;
            if($thread['closed'] == "yes") {
                $folder = "<img src=\"$imgdir/lock_folder.gif\" alt=\"$lang[altclosedtopic]\" />";
            }


            $moved = explode("|", $thread['closed']);
            if($moved[0] == "moved") {
                $prefix = "$lang[moved] ";
                $thread['tid'] = $moved[1];
                $thread['replies'] = "-";
                $thread['views'] = "-";
                $folder = "<img src=\"$imgdir/lock_folder.gif\" alt=\"$lang[altclosedtopic]\" />";
            }


            if(!$ppp || $ppp == '') {
                $ppp = $postperpage;
            }


            //$postsnum = $thread[replies] + 1;
            if($thread['posts']  > $ppp) {
                $posts = $thread['posts'];
                $topicpages = $posts / $ppp;
                $topicpages = ceil($topicpages);
                $pagelinks = '';

                for ($i = 1; $i <= $topicpages; $i++) {
                    $pagelinks .= " <a href=\"viewthread.php?tid=$thread[tid]&page=$i\">$i</a> ";
                    if($i == 3) {
                        $i = $topicpages + 1;
                    }
                }

                if($topicpages > 3) {
                    $pagelinks .= " .. <a href=\"viewthread.php?tid=$thread[tid]&page=$topicpages\">$topicpages</a>";
                }

                $multipage2 = "(<small>Pages: $pagelinks</small>)";
                $pagelinks = '';
            } else {
                $multipage2 = '';

            }

            $thread['subject'] = censor($thread['subject']);
            $thread['subject'] = stripslashes($thread['subject']);


            eval("\$today2[] = \"".template("today2")."\";");
        }
    }
}
$rows = implode("\n", $today2);
eval("\$today = \"".template("today")."\";");
echo stripslashes($today);

end_time();

eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>