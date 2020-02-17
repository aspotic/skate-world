<?php
/* $Id: functions.php,v 1.5 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

function template($name){
    global $tempcache, $table_templates, $db, $comment_output;

    if (isset($tempcache[$name])) {
        $template=$tempcache[$name];
    } else {
        $query= $db->query("SELECT * FROM $table_templates WHERE name='$name'");
        $gettemplate=$db->fetch_array($query);
        $template= $gettemplate['template'];
        $tempcache[$name]= $template;
    }
    $template = str_replace("\\'","'",$template);
    if($name != 'phpinclude' && $comment_output === true) {
        return "<!--Begin Template: $name -->\n$template\n<!-- End Template: $name -->";
    } else {
        return $template;
    }
}

function loadtemplates() {
    global $db,$tempcache,$table_templates;

    $num = func_num_args();
    if($num < 1){
        echo 'Not enough arguments given to loadtemplates() on line: '.__LINE__;
        return false;
    }else{
        $namesarray = func_get_args();

        $sql = '';
        while (list($key,$title) = each($namesarray)) {
            if ($sql != '') {
                $sql .= ',';
            }
            $sql .= "'$title'";
        }
        $query = $db->query("SELECT * FROM $table_templates WHERE name IN ($sql) LIMIT $num");
        while($template = $db->fetch_array($query)) {
            $name           = $template['name'];
            $tempcache[$name]   = $template['template'];
        }
    }
}

function censor($txt, $ignorespaces=false){
    global $censorcache;

    //$num = func_num_args();
    //check_args(1, 1,$num);

    if(is_array($censorcache)){
        if(count($censorcache) > 0){
            reset($censorcache);
            while(list($find, $replace) = each($censorcache)) {
                if($ignorespaces === true){
                    $txt = str_replace($find, $replace, $txt);
                }else{
                    $txt = preg_replace("#\b($find)\b#si", ' '.$replace.' ', $txt);
                }
            }
        }
    }

    return $txt;
}

function smile($txt) {
    global $smiliesnum, $smiliecache, $smdir;

    if($smiliesnum > 0) {
        reset($smiliecache);
        foreach($smiliecache as $code=>$url){
            $txt = str_replace($code, '<img src="./'.$smdir.'/'.$url.'" style="border:none" alt="'.$code.'" />', $txt);
        }
    }
    return $txt;
}

function postify($message, $smileyoff='no', $bbcodeoff='no', $allowsmilies='yes', $allowhtml='yes', $allowbbcode='yes', $allowimgcode='yes', $ignorespaces=false, $ismood="no", $wrap="yes") {
    global $imgdir, $bordercolor, $table_words, $table_forums, $table_smilies, $db, $smdir, $smiliecache, $censorcache, $smiliesnum, $wordsnum;

    //$num = func_num_args();
    //check_args(1, 7, $num);

    $message = checkOutput($message, $allowhtml);

    $message = censor($message, $ignorespaces);

    $bballow = ($allowbbcode == 'yes' || $allowbbcode == 'on') ? (($bbcodeoff != 'off' && $bbcodeoff != 'yes') ? true : false) : false;
    $smiliesallow = ($allowsmilies == 'yes' || $allowsmilies == 'on') ? (($smileyoff != 'off' && $smileyoff != 'yes') ? true : false) : false;

    if($bballow) {
        $message = stripslashes($message);

        if($ismood == "yes"){
            $message = str_replace(array('[poem]', '[/poem]', '[quote]', '[/quote]', '[code]', '[/code]', '[list]', '[/list]', '[list=1]', '[list=a]', '[list=A]', '[/list=1]', '[/list=a]', '[/list=A]'), '', $message);
        }

        $begin = array(
                0    => '[b]',
                1    => '[i]',
                2    => '[u]',
                3    => '[poem]',
                4    => '[marquee]',
                5    => '[blink]',
                6    => '[strike]',
                7    => '[quote]',
                8    => '[code]',
                9    => '[list]',
                10    => '[list=1]',
                11    => '[list=a]',
                12    => '[list=A]',
                );

        $end = array(
                0    => '[/b]',
                1    => '[/i]',
                2    => '[/u]',
                3    => '[/poem]',
                4    => '[/marquee]',
                5    => '[/blink]',
                6    => '[/strike]',
                7    => '[/quote]',
                8    => '[/code]',
                9    => '[/list]',
                10    => '[/list=1]',
                11    => '[/list=a]',
                12    => '[/list=A]',
                );

        foreach($begin as $key=>$value){
            $check = substr_count($message, $value) - substr_count($message, $end[$key]);
            if($check > 0){
                $message = $message.str_repeat($end[$key], $check);
            }elseif($check < 0){
                $message = str_repeat($value, abs($check)).$message;
            }
        }

        $find = array(
                0   => '[b]',
                1   => '[/b]',
                2   => '[i]',
                3   => '[/i]',
                4   => '[poem]',
                5   => '[/poem]',
                6   => '[u]',
                7   => '[/u]',
                8   => '[marquee]',
                9   => '[/marquee]',
                10  => '[blink]',
                11  => '[/blink]',
                12  => '[strike]',
                13  => '[/strike]',
                14  => '[vinfo]',
                15  => '[quote]',
                16  => '[/quote]',
                17  => '[code]',
                18  => '[/code]',
                19  => '[list]',
                20  => '[/list]',
                21  => '[list=1]',
                22  => '[list=a]',
                23  => '[list=A]',
                24  => '[/list=1]',
                25  => '[/list=a]',
                26  => '[/list=A]',
                27  => '[credits]',
                28  => '[*]',
                29  => '[buildedition]',
                30  => '<br />'
                );

        $replace = array(
                0   => '<b>',
                1   => '</b>',
                2   => '<i>',
                3   => '</i>',
                4   => '<center><i>',
                5   => '</center></i>',
                6   => '<u>',
                7   => '</u>',
                8   => '<marquee>',
                9   => '</marquee>',
                10  => '<blink>',
                11  => '</blink>',
                12  => '<strike>',
                13  => '</strike>',
                14  => '<b>'.strrev('suxeN - 9.1 BMX').'</b>',
                15  => "<table align=\"center\" class=\"quote\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"quote\">Quote:</td></tr><tr><td class=\"quotemessage\">",
                16  => "</td></tr></table>",
                17  => "<table align=\"center\" class=\"code\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"code\">Code:</td></tr><tr><td class=\"codemessage\">",
                18  => "</td></tr></table>",
                19  => '<ul type=square>',
                20  => '</ul>',
                21  => '<ol type=1>',
                22  => '<ol type=A>',
                23  => '<ol type=A>',
                24  => '</ol>',
                25  => '</ol>',
                26  => '</ol>',
                27  => 'XMB 1.9 Main Developers - Tularis, Richard, RevMac, Daf, Ixan, ~PuNkEr~. For More Information On Other Staff - Visit xmbforum.com',
                28  => '<li>',
                29  => '<b>Build ID: '.$versionbuild.'</b>',
                30  => ' <br/>'
                );

        $message = str_replace($find, $replace, $message);

        if($smiliesallow) {
            $message = smile($message);
        }

        $patterns = array();
        $replacements = array();

        //$message = preg_replace('#[\s]+((f|ht)tp(s?)){1}://([\S]+)
        $message = eregi_replace("(^|[>[:space:]\n])([[:alnum:]]+)://([^[:space:]<]*)([[:alnum:]#?/&=])([<[:space:]\n]|$)","\\1<a href=\"\\2://\\3\\4\" target=\"_blank\">\\2://\\3\\4</a>\\5", $message);

        $patterns[] = "#\[color=([^\"'<>]*?)\](.*?)\[/color\]#si";
        $replacements[] = '<font color="\1">\2</font>';

        $patterns[] = "#\[size=([^\"'<>]*?)\](.*?)\[/size\]#si";
        $replacements[] = '<font size="\1">\2</font>';

        $patterns[] = "#\[font=([a-z\r\n\t 0-9]+)\](.*?)\[/font\]#si";
        $replacements[] = '<font face="\1">\2</font>';

        $patterns[] = "#\[align=([a-z]+)\](.*?)\[/align\]#si";
        $replacements[] = '<p align="\1">\2</p>';

        if(($allowimgcode != 'no' && $allowimgcode != 'off')) {

        if(!stristr($message, 'javascript:') && (stristr($message, 'jpg[/img]') || stristr($message, 'jpeg[/img]') || stristr($message, 'gif[/img]') || stristr($message, 'png[/img]') || stristr($message, 'bmp[/img]') || stristr($message, 'php[/img]'))) {

            $patterns[] = "#\[img\]([^\[\]][^\"]*?)\[/img\]#mi";
            $replacements[] = '<img src="\1" border="0" alt="\1"/>';

            $patterns[] = "#\[img=([0-9].*?){1}x([0-9]*?)\]([^\"]*?)\[/img\]#mi";
            $replacements[] = '<img width="\1" height="\2" src="\3" alt="\3" border="0" />';
        }

            $patterns[] = "#\[flash=([0-9].*?){1}x([0-9]*?)\](.*?)\[/flash\]#si";
            $replacements[] = '<OBJECT classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 codebase=http://active.macromedia.com/flash2/cabs/swflash.cab#version=6,0,0,0 ID=main WIDTH=\1 HEIGHT=\2><PARAM NAME=movie VALUE=\3><PARAM NAME=loop VALUE=false><PARAM NAME=menu VALUE=false><PARAM NAME=quality VALUE=best><EMBED src=\3 loop=false menu=false quality=best WIDTH=\1 HEIGHT=\2 TYPE=application/x-shockwave-flash PLUGINSPAGE=http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash></EMBED></OBJECT>';
        }

        $patterns[] = "#\[url\]([a-z]+?://){1}([^\"]*?)\[/url\]#mi";
        $replacements[] = '<a href="\1\2" target="_blank">\1\2</a>';

        $patterns[] = "#\[url\]([^\"]*?)\[/url\]#mi";
        $replacements[] = '<a href="http://\1" target="_blank">\1</a>';

        $patterns[] = "#\[url=([a-z]+?://){1}([^\"]*?)\](.*?)\[/url\]#mi";
        $replacements[] = '<a href="\1\2" target="_blank">\3</a>';

        $patterns[] = "#\[url=([^\"]*?)\](.*?)\[/url\]#mi";
        $replacements[] = '<a href="http://\1" target="_blank">\2</a>';

        $patterns[] = "#\[email\]([^\"]*?)\[/email\]#mi";
        $replacements[] = '<a href="mailto:\1">\1</a>';

        $patterns[] = "#\[email=(.*?){1}([^\"]*?)\](.*?)\[/email\]#mi";
        $replacements[] = '<a href="mailto:\1\2">\3</a>';

        $message = preg_replace($patterns, $replacements, $message);

        $message = addslashes($message);
    }else{
        if($smiliesallow) {
            $message = smile($message);
        }
    }

    $message = nl2br($message);
    if($wrap == "yes") {
        $message = wordwrap($message, 150, "\n", 1);
        $message = preg_replace('#(\[/?.*)\n(.*\])#mi', '\\1\\2', $message);
    }

    return $message;
}

function modcheck($status, $username, $mods) {
    global $db, $table_forums;

    //$num = func_num_args();
    //check_args(3, 3, $num);

    if($status == 'Moderator') {
        $mods = explode(',', $mods);
        foreach($mods as $key=>$moderator) {
            if(strtoupper(trim($moderator)) == strtoupper($username)){
                return 'Moderator';
                break;
            }
        }
    }elseif($status == 'Super Administrator'){
        return 'Moderator';
    }elseif($status == 'Administrator'){
        return 'Moderator';
    }else{
        return '';
    }
}


function privfcheck($private, $userlist) {
global $self, $xmbuser, $hideprivate, $db, $mg_status_on, $mg_status_symbol, $table_membergroups;

if($self['status'] == 'Super Administrator'){
return true;
}
if($private == 2 && X_ADMIN) {
return true;
} elseif($private == 3 && X_STAFF) {
return true;
} elseif($private == 1 && $userlist == '') {
return true;
} elseif($userlist != '') {
$user = explode(',', $userlist);
$mg_xmbuser=strtolower($xmbuser);
$mg_status=strtolower($self['status']);
// cycle thru users in forum's userlist
for($i=0;$i<count($user);$i++){
$user[$i]=strtolower(trim($user[$i]));
if($user[$i] != ''){ // only continue checking user if it isn't blank...
// Is the user directly listed ?
if($mg_xmbuser == $user[$i]){
return true;
} elseif(($mg_status_on == true) && ($mg_status_symbol.$mg_status==$user[$i]) && ($mg_status_symbol != '')){ // Is statuscheck enabled ? is it a status ? (Starts with > normally)
return true;
} else { // is it a membergroup ?
// Pull all entries from membergroups table that match the entry in the list. ($user[x]) ...
$query = $db->query("SELECT `groupname`,`userlist` FROM $table_membergroups WHERE `groupname` = '$user[$i]'");
// ...cycle them thru $mgtmparray
while($mgtmparray = $db->fetch_array($query)) {
// trim them to remove ",", " " etc and convert them to lower case.
$mgtmparray[groupname]=strtolower(trim($mgtmparray[groupname]));
// check if the groupname matches the entry in the userlist
if($user[$i]==$mgtmparray[groupname]){
// If so, put the group userlist in $groupmember
$groupmember = explode(",", $mgtmparray[userlist]);
// Cycle thru it...
for($j=0;$j<count($groupmember);$j++){
// trim each entry
$groupmember[$j]=strtolower(trim($groupmember[$j]));
// Is statuscheck enabled ? is it a status ? Is user that status ? (Starts with > normally)
if(($mg_status_on == true) && ($mg_status_symbol.$mg_status==$groupmember[$j]) && ($mg_status_symbol != '') &&($groupmember[$j]!='')){
return true;
} else {
if(($mg_xmbuser==$groupmember[$j]) && ($groupmember[$j]!='')){ // does it match the user's username ?
return true;
}
}
}
}
}
}
}
}
}else{
return false;
}
}



function forum($forum, $template) {
    global $timecode, $dateformat, $lang, $xmbuser, $self, $lastvisit2, $timeoffset, $hideprivate, $addtime;

    //$num = func_num_args();
    //check_args(2, 2,$num);

    $altbg1 = $GLOBALS['altbg1'];
    $altbg2 = $GLOBALS['altbg2'];
    $imgdir = $GLOBALS['imgdir'];

    if($forum['lastpost'] != '') {
        $lastpost = explode('|', $forum['lastpost']);
        $dalast = $lastpost[0];
        if($lastpost[1] != 'Anonymous' && $lastpost[1] != '') {
            $lastpost[1] = '<a href="member.php?action=viewpro&amp;member='.rawurlencode($lastpost[1]).'">'.$lastpost[1].'</a>';
        } else {
            $lastpost[1] = $lang['textanonymous'];
        }

        $lastpostdate = gmdate($dateformat, $lastpost[0] + ($timeoffset * 3600) + ($addtime * 3600));
        $lastposttime = gmdate($timecode, $lastpost[0] + ($timeoffset * 3600) + ($addtime * 3600));
        $lastpost = $lastpostdate.' '.$lang['textat'].' '.$lastposttime.'<br />'.$lang['textby'].' '.$lastpost[1];
        eval("\$lastpostrow = \"".template("".$template."_lastpost")."\";");
    } else {
        $dalast     = 0;
        $lastpost   = $lang['textnever'];
        eval("\$lastpostrow = \"".template($template.'_nolastpost')."\";");
    }

    $lastvisit2 -= 540;
    if($lastvisit2 < $dalast) {
        $folder = "<img src=\"$imgdir/red_folder.gif\" alt=\"$lang[altredfolder]\" />";
    } else {
        $folder = "<img src=\"$imgdir/folder.gif\" alt=\"$lang[altfolder]\" />";
    }

    if($dalast == "") {
        $folder = "<img src=\"$imgdir/folder.gif\" alt=\"$lang[altfolder]\" />";
    }

    $lastvisit2 += 540;
    $authorization = privfcheck($forum['private'], $forum['userlist']);
    $comma = '';
    if($authorization || $hideprivate == 'off' || $self['status'] == 'Super Administrator') {
        if($forum['moderator'] != '') {
            $moderators = explode(', ', $forum['moderator']);
            $forum['moderator'] = '';
            for($num = 0; $num < count($moderators); $num++) {
                $forum['moderator'] .= $comma.'<a href="member.php?action=viewpro&amp;member='.$moderators[$num].'">'.$moderators[$num].'</a>';
                $comma = ', ';
            }
            $forum['moderator'] = '('.$lang['textmodby'].' '.$forum['moderator'].')';
        } else {
            $forum['moderator'] = '';
        }
        eval("\$foruminfo = \"".template("$template")."\";");
    }

    $foruminfo = stripslashes($foruminfo);
    $dalast = '';
    $fmods = '';
    $authorization = '';

    return $foruminfo;
}


function multi($num, $perpage, $page, $mpurl) {

    //$num = func_num_args();
    //check_args(4, 4,$num);

    if(strpos($mpurl, '?') !== false){
        $string = '&';
    }else{
        $string = '?';
    }

    if(($num > $perpage) && $num != 0) {
        $pages = $num / $perpage;
        $pages = ceil($pages);

        if($page == $pages) {
            $to = $pages;
        } elseif($page == $pages-1) {
            $to = $page+1;
        } elseif($page == $pages-2) {
            $to = $page+2;
        } else {
            $to = $page+3;
        }

        if($page == 1 || $page == 2 || $page == 3) {
            $from = 1;
        } else {
            $from = $page-3;
        }
        $fwd_back .= '<a href="'.$mpurl.$string.'page=1">&lt;&lt;&lt;</a>';

        for($i = $from; $i <= $to; $i++) {
            if($i != $page) {
                $fwd_back .= '&nbsp;&nbsp;<a href="'.$mpurl.$string.'page='.$i.'">'.$i.'</a>&nbsp;&nbsp;';
            } else {
                $fwd_back .= '&nbsp;&nbsp;<u><b>'.$i.'</b></u>&nbsp;&nbsp;';
            }
        }

        $fwd_back .= '<a href="'.$mpurl.$string.'page='.$pages.'">&gt;&gt;</a>';
        $multipage = $fwd_back;
    }
    return $multipage;
}

function bbcodeinsert() {
    global $imgdir, $bbinsert, $altbg1, $altbg2, $lang, $SETTINGS, $spelling_lang;

    //$num = func_num_args();
    //check_args(0, 0,$num);

    if($bbinsert == 'on') {
        eval("\$bbcode = \"".template("functions_bbcodeinsert")."\";");
    }
    return $bbcode;
}
function smilieinsert() {
    global $imgdir, $smdir, $table_smilies, $db, $smileyinsert, $smcols, $smtotal;

    //$num = func_num_args();
    //check_args(0, 0,$num);

    if($smileyinsert == 'on' && $smtotal != '' && $smcols != '') {
    $col_smilies = 0;
    $smilies .= '<tr>';
    $querysmilie = $db->query("SELECT * FROM $table_smilies WHERE type='smiley' LIMIT 0, $smtotal") or die($db->error());
        while($smilie = $db->fetch_array($querysmilie)) {
            eval("\$smilies .= \"".template("functions_smilieinsert_smilie")."\";");
            $col_smilies += 1;
            if($col_smilies == $smcols) {
                $smilies .= '</tr><tr>';
                $col_smilies = 0;
            }
        }
        $smilies .= '</tr>';
        eval("\$smilieinsert .= \"".template("functions_smilieinsert")."\";");
    }
    return $smilieinsert;
}

function printsetting1($setname, $varname, $check1, $check2) {
    global $lang, $altbg1, $altbg2;

    //$num = func_num_args();
    //check_args(4, 4,$num);

    ?>
    <tr><td class="tablerow" bgcolor="<?=$altbg1?>"><?=$setname?></td>
    <td class="tablerow" bgcolor="<?=$altbg2?>"><select name="<?=$varname?>">
    <option value="on" <?=$check1?>><?=$lang[texton]?></option><option value="off" <?=$check2?>><?=$lang[textoff]?></option>
    </select></td></tr>
    <?php
}

function printsetting2($setname, $varname, $value, $size) {
    global $altbg1, $altbg2;

    //$num = func_num_args();
    //check_args(4, 4,$num);

    ?>
    <tr>
    <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$setname?></td>
    <td class="tablerow" bgcolor="<?=$altbg2?>"><input type="text"  size="<?=$size?>" value="<?=$value?>" name="<?=$varname?>" /></td>
    </tr>
    <?php
}

function printsetting3($setname, $boxname, $varnames, $values, $checked, $multi=true) {
    global $altbg1, $altbg2;

    foreach($varnames as $key=>$val){
        if($checked[$key] !== true){
            $optionlist[] = '<option value="'.$values[$key].'">'.$varnames[$key].'</option>';
        }else{
            $optionlist[] = '<option value="'.$values[$key].'" selected="selected">'.$varnames[$key].'</option>';
        }
    }

    $optionlist = implode("\n", $optionlist);
    ?>
    <tr>
    <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$setname?></td>
    <td class="tablerow" bgcolor="<?=$altbg2?>"><select <?=($multi ? 'multiple="multiple"' : '')?> name="<?=$boxname?><?=($multi ? '[]' : '')?>"><?=$optionlist?></select></td>
    </tr>
    <?php
}

function printsetting4($title, $name, $value, $rows=10, $cols=50){
    global $altbg1, $altbg2;


    ?>
    <tr>
    <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$title?></td>
    <td class="tablerow" bgcolor="<?=$altbg2?>"><textarea rows="<?=$rows?>" cols="<?=$cols?>"><?=$value?></textarea></td>
    </tr>
    <?php
}

function noaccess($message) {

    //$num = func_num_args();
    //check_args(1, 1,$num);

    extract($GLOBALS);

    loadtemplates("css");
    eval("\$css = \"".template("css")."\";");

    eval("\$header = \"".template("header")."\";");
    echo $header;
    ?>

    <table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
    <tr><td class="mediumtxt"><center><?=$message?></center></td></tr></table>

    <?php
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
}


function updateforumcount($fid) {
    global $db, $table_posts, $table_forums, $table_threads;

    //$num = func_num_args();
    //check_args(1, 1,$num);

    $postcount = 0;
    $threadcount = 0;

    $pquery = $db->query("SELECT count(pid) FROM $table_posts WHERE fid='$fid'");
    $postcount = $db->result($pquery, 0);

    $tquery = $db->query("SELECT count(tid) FROM $table_threads WHERE (fid='$fid' AND closed != 'moved')");
    $threadcount = $db->result($tquery, 0);

    // Count posts in subforums.
    $queryc = $db->query("SELECT fid FROM $table_forums WHERE fup='$fid'");
    while($children = $db->fetch_array($queryc)) {
        $chquery1 = '';
        $chquery2 = '';
        $chquery1 = $db->query("SELECT count(pid) FROM $table_posts WHERE fid='$children[fid]'");
        $postcount += $db->result($chquery1, 0);

        $chquery2 = $db->query("SELECT count(tid) FROM $table_threads WHERE fid='$children[fid]' AND closed != 'moved'");
        $threadcount += $db->result($chquery2, 0);
    }

    $query = $db->query("SELECT t.lastpost FROM $table_threads t, $table_forums f WHERE (t.fid=f.fid AND f.fid='$fid') OR (t.fid=f.fid AND f.fup='$fid') ORDER BY t.lastpost DESC LIMIT 0,1");
    $lp = $db->fetch_array($query);
    $db->query("UPDATE $table_forums SET posts='$postcount', threads='$threadcount', lastpost='$lp[lastpost]' WHERE fid='$fid'");
}

function updatethreadcount($tid) {
    global $db, $table_threads, $table_posts;

    //$num = func_num_args();
    //check_args(1, 1,$num);

    $query1 = $db->query("SELECT * FROM $table_posts WHERE tid='$tid'");
    $replycount = $db->num_rows($query1);
    $replycount--;
    $query2 = $db->query("SELECT dateline, author FROM $table_posts WHERE tid='$tid' ORDER BY dateline DESC LIMIT 1");
    $lp = $db->fetch_array($query2);
    $lastpost = "$lp[dateline]|$lp[author]";
    $db->query("UPDATE $table_threads SET replies='$replycount', lastpost='$lastpost' WHERE tid='$tid'");
}

function smcwcache() {
    global $db, $table_smilies, $table_words, $smiliecache, $censorcache, $smiliesnum, $wordsnum;

    //$num = func_num_args();
    //check_args(0, 0,$num);

    $smquery = $db->query("SELECT * FROM $table_smilies WHERE type='smiley'");
    $smiliesnum = $db->num_rows($smquery);
    $wquery = $db->query("SELECT * FROM $table_words");
    $wordsnum = $db->num_rows($wquery);

    if($smiliesnum > 0) {
        while($smilie = $db->fetch_array($smquery)) {
            $code = $smilie['code'];
            $smiliecache[$code] = $smilie['url'];
        }
    }
    if($wordsnum > 0) {
        while($word = $db->fetch_array($wquery)) {
            $find = $word['find'];
            $censorcache[$find] = $word['replace1'];
        }
    }
}

function checkInput($input, $striptags='no', $allowhtml='no', $word='', $no_quotes=true){
    // Function generously donated by FiXato

    //$num = func_num_args();
    //check_args(1, 4,$num);

    $input = trim($input);
    if($striptags == 'yes'){
        $input = strip_tags($input);
    }

    if($allowhtml != 'yes' && $allowhtml != 'on'){
        if($no_quotes){
            $input = htmlspecialchars($input, ENT_NOQUOTES);
        }else{
            $input = htmlspecialchars($input, ENT_QUOTES);
        }
    }
    if($word != '') {
        $input = str_replace($word, "_".$word, $input);
    }

    return $input;
}

function checkOutput($output, $allowhtml='no', $word=''){

    //$num = func_num_args();
    //check_args(1, 3,$num);

    $output = trim($output);
    if($allowhtml == 'yes' || $allowhtml == 'on'){
        $output = htmlspecialchars_decode($output);
    }
    if($word != '') {
        $output = str_replace($word, "_".$word, $output);
    }

    return $output;
}

function htmlspecialchars_decode($string, $type=ENT_QUOTES){

    //$num = func_num_args();
    //check_args(1, 1,$num);

    $array = array_flip(get_html_translation_table(HTML_SPECIALCHARS, $type));
    return strtr($string, $array);
}

function htmlentities_decode($string, $type=ENT_QUOTES){
    //$num = func_num_args();
    //check_args(1, 1,$num);

    $array = array_flip(get_html_translation_table(HTML_ENTITIES, $type));
    return strtr($string, $array);
}

function FunForum_su($here, $gimme) {
    $go = $gimme - 1;
    $bsu = explode("|", $here);
    return $bsu[$go];
}

function end_time() {
    global $footerstuff;

    extract($GLOBALS);

    $mtime2 = explode(" ", microtime());
    $endtime = $mtime2[1] + $mtime2[0];
    $totaltime = ($endtime - $starttime);

    $footer_options = explode('-', $SETTINGS['footer_options']);

    if(in_array('serverload', $footer_options) && X_ADMIN){
        $load = ServerLoad();
        eval("\$footerstuff[load] = \"".template('footer_load')."\";");
    }else{
        $footerstuff['load'] = '';
    }
    if(in_array('queries', $footer_options)){
        $querynum = $db->querynum;
        eval("\$footerstuff['querynum'] = \"".template('footer_querynum')."\";");
    }else{
        $footerstuff['querynum'] = '';
    }
    if(in_array('phpsql', $footer_options)){
        $db_duration = number_format(($db->duration/$totaltime)*100, 1);
        $php_duration = number_format((1-($db->duration/$totaltime))*100, 1);
        eval("\$footerstuff['phpsql'] = \"".template('footer_phpsql')."\";");
    }else{
        $footerstuff['phpsql'] = '';
    }

    if(in_array('loadtimes', $footer_options) && X_ADMIN){
        $totaltime = number_format($totaltime, 7);
        eval("\$footerstuff['totaltime'] = \"".template('footer_totaltime')."\";");
    }else{
        $footerstuff['totaltime'] = '';
    }

    if($self['status'] == 'Super Administrator' && $debug === 1){
        $stuff = array();

        $stuff[] = '<table cols="2"><tr><td width="5%">#</td><td>Query:</td></tr>';
        foreach($db->querylist as $key=>$val){
            $val = mysql_syn_highlight($val);
            $stuff[] = '<tr><td><b>'.++$key.'.</b></td><td>'.$val.'</td></tr>';
        }
        $stuff[] = '</table>';
        $footerstuff['querydump'] = implode("\n", $stuff);
    }else{
        $footerstuff['querydump'] = '';
    }

    return $footerstuff;
}

function pwverify($pass='', $url){
    global $self, $navigation;

    extract($GLOBALS);

    if(trim($pass) != '' && $_COOKIE['fidpw'.$fid] != $pass && $self[status] != 'Super Administrator'){
        if($_POST['pw'] != $pass){
            $navigation .= '&raquo; '.$lang[error];
            end_time();

            $message = $lang['invalidforumpw'];

            eval("\$header = \"".template("header")."\";");
            eval("\$error = \"".template("error")."\";");
            eval("\$pwform = \"".template("forumdisplay_password")."\";");
            eval("\$footer = \"".template("footer")."\";");

            echo stripslashes($error);
            echo stripslashes($pwform);
            echo $footer;

        }else{
            setcookie("fidpw$fid", $pw, (time() + (86400*30)), $cookiepath, $cookiedomain);
            header("Location: $url");
        }
        exit();
        return NULL;
    }
}

function redirect($path, $timeout=2, $type=X_REDIRECT_HEADER){
    if($type == X_REDIRECT_JS) {
        ?>
        <script>
        function redirect(){
            window.location.replace("<?=$path?>");
        }

        setTimeout("redirect();", <?=($timeout*100)?>);
        </script>

        <?
    }else{
        if($timeout == 0) {
            header("Location: $path");
        } else {
            header("Refresh: $timeout; URL=./$path");
        }
    }
    return true;
}

function url_to_text($url){
    global $db, $table_forums, $table_threads, $lang, $self;

    //$num = func_num_args();
    //check_args(1, 1,$num);

    switch($self['status']){
        case 'member';
            $restrict .= " f.private !='3' AND";

        case 'Moderator';

        case 'Super Moderator';
            $restrict .= " f.private != '2' AND";

        case 'Administrator';
            $restrict .= " f.userlist = '' AND";
            $restrict .= " f.password = '' AND";

        case 'Super Administrator';
            break;

        default:
            $restrict = " f.private !='3' AND";
            $restrict .= " f.private != '2' AND";
            $restrict .= " f.userlist = '' AND";
            $restrict .= " f.password = '' AND";
            break;
    }


    if(false !== strpos($url, 'tid') && false === strpos($url, "/post.php")){
        $temp = explode('?', $url);
        $urls = explode('&', $temp[1]);
        foreach($urls as $key=>$val){
            if(strpos($val, 'tid') !== false){
                $tid = substr($val, 4);
            }
        }
        $query = $db->query("SELECT f.theme, t.fid, t.subject FROM $table_forums f, $table_threads t WHERE $restrict f.fid=t.fid AND t.tid='$tid'");
        while($locate = $db->fetch_array($query)){
            $location = "$lang[onlineviewthread] $locate[subject]";
        }

    }elseif(false !== strpos($url, 'fid')  && false === strpos($url, "/post.php")){
        $temp = explode('?', $url);
        $urls = explode('&', $temp[1]);
        foreach($urls as $key=>$val){
            if(strpos($val, 'fid') !== false){
                $fid = substr($val, 4);
            }
        }
        $query = $db->query("SELECT name FROM $table_forums f WHERE $restrict f.fid='$fid'");
        while($locate = $db->fetch_array($query)){
            $location = "$lang[onlineforumdisplay] $locate[name]";
        }

    }elseif(false !== strpos($url, "/memcp.php")){
        $location = $lang['onlinememcp'];
    }elseif(false !== strpos($url, "/cp.php") || false !== strpos($url, "/cp2.php")){
        $location = $lang['onlinecp'];
    }elseif(false !== strpos($url, "/editprofile.php")){
        $location = $lang['onlineeditprofile'];
    }elseif(false !== strpos($url, "/emailfriend.php")){
        $location = $lang['onlineemailfriend'];
    }elseif(false !== strpos($url, "/faq.php")){
        $location = $lang['onlinefaq'];
    }elseif(false !== strpos($url, "/index.php")){
        $location = $lang['onlineindex'];
    }elseif(false !== strpos($url, "/member.php")){
        if(false !== strpos($url, 'action=reg')){
            $location = $lang['onlinereg'];
        }elseif(false !== strpos($url, 'action=viewpro')){
            $temp = explode('?', $url);
            $urls = explode('&', $temp[1]);
            foreach($urls as $argument){
                if(strpos($argument, 'member') !== false){
                    $member = str_replace('member=', '', $argument);
                }
            }
            eval("\$location = \"$lang[onlineviewpro]\";");
        }elseif(false !== strpos($url, 'action=coppa')){
            $location = $lang['onlinecoppa'];
        }
    }elseif(false !== strpos($url, "misc.php")){
        if(false !== strpos($url, 'login')){
            $location = $lang['onlinelogin'];
        }elseif(false !== strpos($url, 'logout')){
            $location = $lang['onlinelogout'];
        }elseif(false !== strpos($url, 'search')){
            $location = $lang['onlinesearch'];
        }elseif(false !== strpos($url, 'lostpw')){
            $location = $lang['onlinelostpw'];
        }elseif(false !== strpos($url, 'online')){
            $location = $lang['onlinewhosonline'];
        }elseif(false !== strpos($url, 'onlinetoday')){
            $location = $lang['onlineonlinetoday'];
        }elseif(false !== strpos($url, 'list')){
            $location = $lang['onlinememlist'];
        }

    }elseif(false !== strpos($url, "/post.php")){
        if(false !== strpos($url, 'action=edit')){
            $location = $lang['onlinepostedit'];
        }elseif(false !== strpos($url, 'action=newthread')){
            $location = $lang['onlinepostnewthread'];
        }elseif(false !== strpos($url, 'action=reply')){
            $location = $lang['onlinepostreply'];
        }
    }elseif(false !== strpos($url, "/stats.php")){
        $location = $lang['onlinestats'];
    }elseif(false !== strpos($url, "/today.php")){
        $location = $lang['onlinetodaysposts'];
    }elseif(false !== strpos($url, "/tools.php")){
        $location = $lang['onlinetools'];
    }elseif(false !== strpos($url, "/topicadmin.php")){
        $location = $lang['onlinetopicadmin'];
    }elseif(false !== strpos($url, "/u2u.php")){
        if(false !== strpos($url, 'action=send')){
            $location = $lang['onlineu2usend'];
        }elseif(false !== strpos($url, 'action=delete')){
            $location = $lang['onlineu2udelete'];
        }elseif(false !== strpos($url, 'action=ignore') || false !== strpos($url, 'action=ignoresubmit')){
            $location = $lang['onlineu2uignore'];
        }elseif(false !== strpos($url, 'action=view')){
            $location = $lang['onlineu2uview'];
        }
    }else{
        $location = $url;
    }

    if(trim($location) == ''){
        $location = $lang['secret'];
    }else{
        $location = str_replace('%20', '&nbsp;', $location);
    }

    $url = addslashes($url);
    $return = array();
    $return['url'] = checkInput($url, 'yes');
    $return['text'] = $location;

    return $return;
}

function check_args($min, $max, $num){
    if($num > $min || $num < $maxx){
        extract($GLOBALS);

        $navigation .= '&raquo; '.$lang[error];
        $message = $lang['wrong_arg_num'];
        end_time();

        eval("\$header = \"".template('header')."\";");
        eval("\$error = \"".template('error')."\";");
        eval("\$footer = \"".template('footer')."\";");

        echo $error;
        echo $footer;

        exit();
        return false;
    }else{
        return true;
    }
}

function postperm($forums, $type){
    global $lang, $self, $whopost1, $whopost2, $whopost3;

    //$num = func_num_args();
    //check_args(2, 2,$num);

    $pperm = explode('|', $forums['postperm']);

    if($pperm[0] == 1) {
        $whopost1 = $lang['whocanpost11'];
    } elseif($pperm[0] == 2) {
        $whopost1 = $lang['whocanpost12'];
    } elseif($pperm[0] == 3) {
        $whopost1 = $lang['whocanpost13'];
    } elseif($pperm[0] == 4) {
        $whopost1 = $lang['whocanpost14'];
    }

    if($pperm[1] == 1) {
        $whopost2 = $lang['whocanpost21'];
    } elseif($pperm[1] == 2) {
        $whopost2 = $lang['whocanpost22'];
    } elseif($pperm[1] == 3) {
        $whopost2 = $lang['whocanpost23'];
    } elseif($pperm[1] == 4) {
        $whopost2 = $lang['whocanpost24'];
    }

    if($pperm[0] == 4 && $pperm[1] == 4) {
        $whopost3 = $lang['whocanpost32'];
    }

    if(($forums['private'] === 2 || ($type == 'thread' && $pperm[0] == 2) || ($type == 'reply' && $pperm[1] == 2)) && $self['status'] != "Administrator" && $self['status'] != "Super Administrator") {
        return false;
    } elseif(($forums['private'] === 3 || ($type == 'thread' && $pperm[0] == 3) || ($type == 'reply' && $pperm[1] == 3)) && $self['status'] != "Administrator" && $self['status'] != "Super Administrator" && $self['status'] != "Moderator" && $self['status'] != "Super Moderator") {
        return false;
    } elseif(($forums['private'] === 4 || ($type == 'thread' && $pperm[0] == 4) || ($type == 'reply' && $pperm[1] == 4)) && (!privfcheck($forums['private'], $forums['userlist']))){
        return false;
    } else {
        return true;
    }
}

function get_extension($filename){
    $a = explode('.', $filename);
    $count = count($a);
    if($count == 1){
        return '';
    }else{
        return $a[$count-1];
    }
}

function create_table_backup($table, $full=true){
    global $db;

    $query = $db->query("SHOW CREATE TABLE $table");
    $create = $db->fetch_array($query, SQL_NUM);
    $create = $create[1];

    $create = "DROP TABLE IF EXISTS $table;\n$create;";
    if(!$full){
        return $create;
    }

    $query = $db->query("SELECT * FROM $table");

    $count = $db->num_fields($query);
    for($i=0;$i<$count;$i++){
        $fields[] = $db->field_name($query, $i);
    }
    $inserts = array();

    while($member = $db->fetch_array($query)){
        reset($fields);
        $insert = array();

        foreach($fields as $field){
            $insert[] = "'".addslashes(stripslashes($member[$field]))."'";
        }
        $insert = implode(', ', $insert);

        $inserts[] = $insert;
    }
    if(count($inserts) > 0){
        $inserts = implode('), (', $inserts);

        reset($fields);
        $cols = implode(', ', $fields);

        return "$create; \n\nINSERT INTO $table ($cols) VALUES ($inserts);";
    }else{
        return $create;
    }
}

function get_attached_file($file, $attachstatus, $max_size=1000000) {
    global $lang, $filename, $filetype, $filesize;

    $filename = '';
    $filetype = '';
    $filesize = 0;

    if($file['name'] != 'none' && !empty($file['name']) && $attachstatus != 'off' && is_uploaded_file($file['tmp_name'])) {
        $attachment = addslashes(fread( fopen($file['tmp_name'], 'rb'), filesize($file['tmp_name'])));

        if($file['size'] > $max_size) {
            extract($GLOBALS);

            $message = $lang['attachtoobig'];
            end_time();

            eval("\$header = \"".template("header")."\";");
            eval("\$error = \"".template("error")."\";");
            eval("\$footer = \"".template("footer")."\";");

            echo $error;
            echo $footer;
            exit();
        }else{
            $filename = $file['name'];
            $filetype = $file['type'];
            $filesize = $file['size'];

            return $attachment;
        }
    }else{
        return false;
    }
}

function ServerLoad() {
    if($stats = @exec('uptime')) {
        $parts = explode(',', $stats);
        $count = count($parts);

        $first = explode(' ', $parts[$count-3]);
        $c = count($first);
        $first = $first[$c-1];

        return array($first, $parts[$count-2], $parts[$count-1]);
    }else{
        return array();
    }
}

function error($msg, $showheader=true, $prepend='', $append='', $redirect=false, $die=true, $return_as_string=false, $showfooter=true) {
    extract($GLOBALS);
    $args = func_get_args();

    $message    = (isset($args[0]) ? $args[0] : '');
    $showheader = (isset($args[1]) ? $args[1] : true);
    $prepend    = (isset($args[2]) ? $args[2] : '');
    $append     = (isset($args[3]) ? $args[3] : '');
    $redirect   = (isset($args[4]) ? $args[4] : false);
    $die        = (isset($args[5]) ? $args[5] : true);
    $return_str = (isset($args[6]) ? $args[6] : false);
    $showfooter = (isset($args[7]) ? $args[7] : true);

    $navigation = '&raquo; '.$lang['error'];

    end_time();

    if($redirect !== false){
        redirect($redirect);
    }

    if($showheader === false){
        $header = '';
    }else{
        eval("\$header = \"".template("header")."\";");
    }

    eval("\$error = \"".template('error')."\";");
    if($showfooter === true) {
        eval("\$footer = \"".template("footer")."\";");
    }else{
        $footer = '';
    }

    if($return_str !== false){
        $return = $prepend . $error . $footer . $append;
    }else{
        echo $prepend . $error . $footer . $append;
        $return = '';
    }

    if($die) {
        exit();
    }
    return $return;
}

function array_keys2keys($array, $translator){
    $new_array = array();

    foreach($array as $key=>$val){
        if(isset($translator[$key])){
            $new_key = $translator[$key];
        }else{
            $new_key = $key;
        }
        $new_array[$new_key] = $val;
    }

    return $new_array;
}

function mysql_syn_highlight($query){
    global $tables, $tablepre;

    $find       = array();
    $replace    = array();

    foreach($tables as $name) {
        $find[] = $tablepre.$name;
    }

    $find[] = 'SELECT';
    $find[] = 'UPDATE';
    $find[] = 'DELETE';
    $find[] = 'INSERT INTO';

    $find[] = 'WHERE';
    $find[] = 'ON';
    $find[] = 'FROM';

    $find[] = 'GROUP BY';
    $find[] = 'ORDER BY';
    $find[] = 'LEFT JOIN';

    $find[] = 'IN';
    $find[] = 'SET';
    $find[] = 'AS';

    $find[] = '(';
    $find[] = ')';

    $find[] = 'ASC';
    $find[] = 'DESC';

    $find[] = 'AND';
    $find[] = 'OR';
    $find[] = 'NOT';

    // temporary

    foreach($find as $key=>$val){
        $replace[$key] = '</i><b>'.$val.'</b><i>';
    }

    //$query = preg_replace('#([A-Za-Z0-9]?)[\d]?=\'(.*?)\'#i', '<b>\1</b> = </i>\2<i>', $query);
    $return = '<i>'.str_replace($find, $replace, $query).'</i>';
    return str_replace('</i><b></i><b>IN</b><i>SERT </i><b>IN</b><i>TO</b><i>', '</i><b>INSERT INTO</b><i>', $return);
}

function dump_query($resource, $header=true){
    global $altbg2, $altbg1, $db;
    if(!$db->error()){
        $count = $db->num_fields($resource);
        if($header){
            ?><tr bgcolor="<?=$altbg2?>" align="center" class="header"><?php
            for($i=0;$i<$count;$i++){
                echo '<td align="left">';
                echo '<u><b>'.$db->field_name($resource, $i).'</b></u>';
                echo '</td>';
            }
            echo '</tr>';
        }

        while($a = $db->fetch_array($resource, SQL_NUM)){
            ?><tr bgcolor="<?=$altbg1?>" class="ctrtablerow"><?php
            for($i=0;$i<$count;$i++){
                echo '<td align="left">';

                if(trim($a[$i]) == ''){
                    echo '&nbsp;';
                }else{
                    echo $a[$i];
                }
                echo '</td>';
            }
            echo '</tr>';
        }
    }else{
        error($db->error());
    }
}
function loadAwards()
{
global $db, $tablepre, $awardcache, $awardnum;
$query = $db->query("SELECT * FROM ".$tablepre."awards");
$awardnum = $db->num_rows($query, 0);
while($award = $db->fetch_array($query)) {
$awardcache[$award['awid']] = '<img src="images/awards/'.$award['awimgurl'].'" border="0"alt="'.$award['awname'].': '.$award['awdesc'].'">';
}
}

function makeAwards($awardinfo)
{
global $awardcache, $awardnum;
$showawards = '';
if ($awardnum > 0 && $awardinfo != '') {
foreach ($awardcache as $key => $value) {
$awardinfo = str_replace('['.$key.']', $value, $awardinfo);
}
}
return $awardinfo;
}
?>