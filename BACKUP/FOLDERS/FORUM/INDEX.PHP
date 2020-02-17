<?php
/* $Id: index.php,v 1.3 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require "./header.php";

if($PORTAL['orderview'] == "on" && !$gid) {
Header("Location: ".$full_url."portal.php");
}elseif($PORTAL['orderview'] == "off" || $gid) {

loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime', 'index_whosonline', 'index_category', 'index_forum', 'index', 'index_welcome_member', 'index_welcome_guest', 'index_forum_lastpost', 'index_ticker', 'header', 'footer', 'css', 'functions_bbcode', 'index_category_spacer', 'index_forum_nolastpost');

eval("\$css = \"".template('css')."\";");

if($tickerstatus == "on"){
    $tickercontents = str_replace("\r\n", "\n", $tickercontents);   // make windows compatible
    $tickercontents = str_replace("\r", "\n", $tickercontents);     // make mac compatible
    $news       = explode("\n", $tickercontents);                   // use UNIX-style
    $contents   = '';
    for($i=0;$i<count($news);$i++){
        if(strlen(trim($news[$i])) == 0) {
            continue;
        }
        $news[$i]  = postify($news[$i], 'no', 'no', 'yes', 'no', 'yes', 'yes', false, 'yes', 'no');
        $news[$i]  = str_replace('\"', '"', addslashes($news[$i]));
        $contents .= "\tcontents[$i]='$news[$i]';\n";
    }
    eval("\$ticker  = \"".template("index_ticker")."\";");
}


if(isset($gid)) {
    $whosonlinestatus = 'off';
    $query = $db->query("SELECT name FROM $table_forums WHERE fid='$gid' AND type='group'");
    $cat = $db->fetch_array($query);
    $navigation = "&raquo; $cat[name]";
    $lang['stats4'] = "";
}

eval("\$header = \"".template("header")."\";");
echo $header;

if(!isset($gid) || !$gid){
    if($xmbuser) {
        eval("\$welcome = \"".template("index_welcome_member")."\";");
    } else {
        eval("\$welcome = \"".template("index_welcome_guest")."\";");
    }

    // Start Whos Online and Stats
    $query = $db->query("SELECT username FROM $table_members ORDER BY regdate DESC LIMIT 1");
    $lastmem = $db->fetch_array($query);
    $lastmember = $lastmem['username'];

    $query = $db->query("SELECT count(uid) FROM $table_members");
    $members = $db->result($query, 0);
    $db->free_result($query);

    $query = $db->query("SELECT COUNT(tid) FROM $table_threads");
    $threads = $db->result($query, 0);
    $db->free_result($query);

    $query = $db->query("SELECT COUNT(pid) FROM $table_posts");
    $posts = $db->result($query, 0);
    $db->free_result($query);

    $memhtml = "<a href=\"member.php?action=viewpro&amp;member=".rawurlencode($lastmember)."\"><b>$lastmember</b></a>.";
    eval($lang['evalindexstats']);

    if($whosonlinestatus == "on") {
        $guestcount     = 0;
        $membercount    = 0;
        $hiddencount    = 0;

        $query  = $db->query("SELECT m.status, m.username, m.invisible, w.* FROM $table_whosonline w LEFT JOIN $table_members m ON m.username=w.username ORDER BY w.username");
        while($online = $db->fetch_array($query)) {
            switch($online['username']) {
                case 'xguest123':
                    $guestcount++;
                    break;
                default:
                    if ($online['invisible'] != 0 && X_ADMIN) {
                        $member[] = $online;
                        $hiddencount++;
                    } elseif($online['invisible'] != 0){
                        $hiddencount++;
                    } else {
                        $member[] = $online;
                        $membercount++;
                    }
                break;
            }
        }

        $db->free_result($query);

        $onlinetotal = $guestcount + $membercount;

        if ($membercount != 1) {
            $membern = "<b>$membercount</b> $lang[textmembers]";
        } else {
            $membern = "<b>1</b> $lang[textmem]";
        }

        if ($guestcount != 1) {
            $guestn = "<b>$guestcount</b> $lang[textguests]";
        } else {
            $guestn = "<b>1</b> $lang[textguest1]";
        }

        if ($hiddencount != 1) {
            $hiddenn = "<b>$hiddencount</b> $lang[texthmems]";
        } else {
            $hiddenn = "<b>1</b> $lang[texthmem]";
        }

        eval($lang['whosoneval']);
        $memonmsg = "<span class=\"smalltxt\">$lang[whosonmsg]</span>";

        $memtally = array();
        $num = 1;
        $comma = "";
        $show_total = (X_ADMIN) ? ($membercount+$hiddencount) : ($membercount);

        $show_inv_key = false;

        for($mnum=0; $mnum<$show_total; $mnum++) {
            $pre = '';
            $suf = '';

            $online = $member[$mnum];
            switch($online['status']) {
                case 'Super Administrator':
                    $pre = "<b><u><i><img src=\"images/usersonline/online_supadmin.gif\">";
                    $suff = "</i></u></b>";
                    break;
                case 'Administrator':
                    $pre = "<b><u><img src=\"images/usersonline/online_admin.gif\">";
                    $suff = "</u></b>";
                    break;
                case 'Super Moderator':
                    $pre = "<i><b><img src=\"images/usersonline/online_supmod.gif\">";
                    $suff = "</b></i>";
                    break;
                case 'Moderator':
                    $pre = "<b><img src=\"images/usersonline/online_mod.gif\">";
                    $suff = "</b>";
                    break;
                default:
                    $pre = "<img src=\"images/usersonline/online_mem.gif\">";
                    $suff = "";
                    break;
            }

            if ($online['invisible'] != 0) {
                $pre .= "<strike>";
                $suff .= "</strike>";
                if(!X_ADMIN && $online['username'] != $xmbuser){
                    $num++;
                    continue;
                }
            }
            if($online['username'] == $xmbuser && $online['invisible'] != 0){
                $show_inv_key = true;
            }

            $memtally[] = "<a href=\"member.php?action=viewpro&amp;member=".rawurlencode($online['username'])."\">$pre$online[username]$suff</a>";

            $num++;
        }
        if(X_ADMIN || $show_inv_key === true){
            $hidden = " - <strike>$lang[texthmem]</strike>";
        }else{
            $hidden = '';
        }
        $memtally = implode(', ', $memtally);


        if($memtally == "") {
            $memtally = "&nbsp;";
        }
		
		if ($guestcount>0) {
	for ($mani=0; $mani<$guestcount; $mani++) {
		$memtally .= "$comma <img src=images/usersonline/online_guest.gif border=0>Guest";
		$comma = ", ";
		$num++;
	}
	
	if ($memtally == "") {
		$memtally = "&nbsp;";
	}
}


        $datecut = time() - (3600 * 24);
        if(X_ADMIN){
            $query = $db->query("SELECT username FROM $table_members WHERE lastvisit >= '$datecut' ORDER BY lastvisit DESC LIMIT 0, 50");
        }else{
            $query = $db->query("SELECT username FROM $table_members WHERE lastvisit >= '$datecut' AND invisible != '1' ORDER BY lastvisit DESC LIMIT 0, 50");
        }

        $todaymembersnum = 0;
        $todaymembers = '';
        $comma = '';

        while ($memberstoday = $db->fetch_array($query)) {
                $todaymembers .= "$comma <a href=\"member.php?action=viewpro&amp;member=".rawurlencode($memberstoday['username'])."\">".$memberstoday['username']."</a>";
                ++$todaymembersnum;
                $comma = ", ";
        }

        if ($todaymembersnum == 1) {
            $memontoday = $todaymembersnum . $lang['textmembertoday'];
        } else {
            $memontoday = $todaymembersnum . $lang['textmemberstoday'];
        }

        eval("\$whosonline = \"".template("index_whosonline")."\";");
    }

    // End Whosonline and Stats

    // Start Getting Forums and Groups
    if($SETTINGS['catsonly'] == 'on'){
        $fquery = $db->query("SELECT name as cat_name, fid as cat_fid FROM $table_forums WHERE status='on' and type='group' ORDER BY displayorder ASC");
    }else{
        $fquery = $db->query("SELECT f.*, c.name as cat_name, c.fid as cat_fid FROM $table_forums f LEFT JOIN $table_forums c ON (f.fup = c.fid) WHERE (c.type='group' AND f.type='forum' AND c.status='on' AND f.status='on') OR (f.type='forum' AND f.fup='' AND f.status='on') ORDER BY c.displayorder ASC, f.displayorder ASC");
    }
}else {
    $fquery = $db->query("SELECT f.*, c.name as cat_name, c.fid as cat_fid FROM $table_forums f LEFT JOIN $table_forums c ON (f.fup = c.fid) WHERE (c.type='group' AND f.type='forum' AND c.status='on' AND f.status='on' AND f.fup='$gid') ORDER BY c.displayorder ASC, f.displayorder ASC");
}


$lastcat = 0;
$forumlist = '';
$spacer = '';
if($SETTINGS['space_cats'] == 'on'){
    eval("\$spacer = \"".template("index_category_spacer")."\";");
}

while($thing = $db->fetch_array($fquery)){
    $cforum = '';
    if($catsonly != 'on'){
        $cforum = forum($thing, "index_forum");
    }

    if($lastcat != $thing['cat_fid'] && !empty($cforum)){
        $lastcat = $thing['cat_fid'];
        eval("\$forumlist .= \"".template("index_category")."\";");
    }

    $forumlist .= $cforum;
}
eval("\$index = \"".template("index")."\";");
$index = stripslashes($index);
echo $index;

end_time();

eval("\$footer = \"".template("footer")."\";");
echo $footer;

}

?>

<center>
<A HREF="http://undeadgear.mytopsitelist.com/jump.php?cmd=in&list_id=skateboardsfromhell&site_id=skateworld" target="_blank"><IMG src="http://www.skate-world.net/voteus.gif" border="0"  width="81" height="30"></A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<A href="http://www.skateboard-city.com/topsites/index.php?vote=156" target="_blank"><IMG border="0" src="http://www.skateboard-city.com/topsites/top50button.gif" alt="top 50" width="88" height="31"></A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="http://skatetop.com/index.php?do=v&i=7" target="_blank"><img src="http://skatetop.com/index.php?do=in&id=7" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<A HREF="http://to0zworld.com/topsite/topsites.cgi?drumsticks" TARGET="_BLANK"> <IMG SRC="http://to0zworld.com/topsite/topb1.gif" BORDER=0></A> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;