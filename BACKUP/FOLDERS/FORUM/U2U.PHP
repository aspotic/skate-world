<?php
/* $Id: u2u.php,v 1.2 2004/04/15 15:16:37 Tularis Exp $ */

/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

// Get required files
require './header.php';

// Load templates
loadtemplates('css','u2u_header','u2u_footer','u2u_msg','u2u','u2u_folderlink','u2u_inbox','u2u_outbox','u2u_drafts','u2u_row','u2u_row_none','u2u_view','u2u_ignore','u2u_send','u2u_send_preview','u2u_folders','u2u_main','u2u_quotabar','u2u_old','u2u_printable');

smcwcache();

if(isset($action) && $action == 'send') {
    $sendmode = 'true';
} else {
    $sendmode = 'false';
}

// Eval necessary templates
eval("\$css = \"".template('css')."\";");
eval("\$u2uheader = \"".template('u2u_header')."\";");
eval("\$u2ufooter = \"".template('u2u_footer')."\";");

// Check if logged in
if(empty($xmbuser) || empty($xmbpw)){
    error($lang['u2unotloggedin'], false, $u2uheader, $u2ufooter, false, true, false, false);
    exit;
}

// Get folderlist
if(empty($action) && empty($folder)){
    $folder = 'Inbox';
} else {
    $folder = checkInput($folder, yes);
}
$u2ucount = 0;
unset($flist, $folderlist);
$folders = (empty($self['u2ufolders'])) ? array() : explode(",", $self['u2ufolders']);
foreach ($folders as $key => $value) {
    $folders[$key] = trim($value);
}
sort($folders);
$folders = array_merge(array('Inbox'=>$lang['textu2uinbox'], 'Outbox'=>$lang['textu2uoutbox']), $folders, array('Drafts'=>$lang['textu2udrafts'], 'Trash'=>$lang['textu2utrash']));
$query = $db->query("SELECT folder, count(u2uid) as count FROM $table_u2u WHERE owner='$self[username]' GROUP BY folder ORDER BY folder ASC");
while($flist = $db->fetch_array($query)){
    $farray[$flist['folder']] = $flist['count'];
    $u2ucount += $flist['count'];
}
$db->free_result($query);
foreach($folders as $link=>$value){
    if(is_numeric($link)){
        $link = $value;
    }
    if($link == $folder){
        $value = '<b>'.$value.'</b>';
    }
    $count = (empty($farray[$link])) ? 0 : $farray[$link];
    if($link == 'Trash'){
        $emptytrash = " (<a href=\"u2u.php?action=emptytrash\">".$lang['textemptytrash']."</a>)";
    }
    eval("\$folderlist .= \"".template('u2u_folderlink')."\";");
}
unset($flist);

// Make a graphical u2ucount
$barwidth = number_format(($u2ucount / $u2uquota) * 100, 0);
eval($lang['evaluqinfo']);
eval("\$u2uquotabar = \"".template('u2u_quotabar')."\";");

// Check for old u2u interface
$thewidth = ($self['useoldu2u'] == 'yes') ? $tablewidth : '100%';
$tu2u = ($self['useoldu2u'] == 'yes') ? 'u2u_old' : 'u2u';

// Start actions
switch($action){
    case "send":
        if($self['ban'] == 'u2u' || $self['ban'] == 'both'){
            error($lang['textbanfromu2u'], false, $u2uheader, $u2ufooter, false, true, false, false);
        }
        if($u2ucount >= $u2uquota){
            error($lang['u2ureachedquota'], false, $u2uheader, $u2ufooter, false, true, false, false);
        }
        if(isset($savesubmit)){
            if(empty($subject) && empty($message)) {
                 error($lang['u2uempty'], false, $u2uheader, $u2ufooter, false, true, false, false);
            }
            $subject = checkInput(censor(addslashes($subject)));
            $message = checkInput(addslashes($message));
            $db->query("INSERT INTO $table_u2u VALUES ('', '', '', 'draft', '".$self['username']."', 'Drafts', '$subject', '$message', $db->time($time), 'yes', 'no')");
            u2u_msg($lang['imsavedmsg'], "u2u.php?folder=Drafts");
        }elseif(isset($sendsubmit)){
            unset($errors);
            if(empty($subject) && empty($message)) {
                 error($lang['u2uempty'], false, $u2uheader, $u2ufooter, false, true, false, false);
            }
            $subject = checkInput(censor(addslashes($subject)));
            $message = checkInput(addslashes($message));
            if(strstr($msgto, ",")){
                $recipients = explode(",", $msgto);
                foreach($recipients as $value){
                    $query = $db->query("SELECT username, email, lastvisit, ignoreu2u, emailonu2u FROM $table_members WHERE username='".trim($value)."'");
                    if($rcpt = $db->fetch_array($query)){
                        $ilist = explode(",", $rcpt['ignoreu2u']);
                        foreach($ilist as $key=>$value){
                            $ilist[$key] = trim($value);
                        }
                        if(!in_array($self['username'], $ilist)){
                            $username = $rcpt['username'];
                            $db->query("INSERT INTO $table_u2u VALUES ('', '$username', '".$self['username']."', 'incoming', '$username', 'Inbox', '$subject', '$message', $db->time($time), 'no', 'yes')");
                            if($self['saveogu2u'] == 'yes'){
                                $db->query("INSERT INTO $table_u2u VALUES ('', '$username', '".$self['username']."', 'outgoing', '".$self['username']."', 'Outbox', '$subject', '$message', $db->time($time), 'no', 'yes')");
                            }
                            if($rcpt['emailonu2u'] == 'yes'){
                                $lastvisitcheck = time() - 600;
                                if($lastvisitcheck > $rcpt['lastvisit']){
                                    $u2uurl = $SETTINGS['boardurl'].'u2u.php';
                                    mail($rcpt['email'], "$lang[textnewu2uemail]", "$self[username] $lang[textnewu2ubody] \n$u2uurl", "From: $bbname <$adminemail>");
                                }
                            }
                        }else{
                            $errors .= "<br />".$value." - ".$lang['u2ublocked'];
                        }
                    }else{
                        $errors .= "<br />".$value." - ".$lang['badrcpt'];
                    }
                }
            }else{
                $query = $db->query("SELECT username, email, lastvisit, ignoreu2u, emailonu2u FROM $table_members WHERE username='".trim($msgto)."'");
                if($rcpt = $db->fetch_array($query)){
                    $ilist = explode(",", $rcpt['ignoreu2u']);
                    foreach($ilist as $key=>$value){
                        $ilist[$key] = trim($value);
                    }
                    if(!in_array($self['username'], $ilist)){
                        $username = $rcpt['username'];
                        $db->query("INSERT INTO $table_u2u VALUES ('', '$username', '".$self['username']."', 'incoming', '$username', 'Inbox', '$subject', '$message', $db->time($time), 'no', 'yes')");
                        if($self['saveogu2u'] == 'yes'){
                            $db->query("INSERT INTO $table_u2u VALUES ('', '$username', '".$self['username']."', 'outgoing', '".$self['username']."', 'Outbox', '$subject', '$message', $db->time($time), 'no', 'yes')");
                        }
                        if($rcpt['emailonu2u'] == 'yes'){
                            $lastvisitcheck = time() - 600;
                            if($lastvisitcheck > $rcpt['lastvisit']){
                                $u2uurl = $SETTINGS['boardurl'].'u2u.php';
                                mail($rcpt['email'], "$lang[textnewu2uemail]", "$self[username] $lang[textnewu2ubody] \n$u2uurl", "From: $bbname <$adminemail>");
                            }
                        }
                    }else{
                        error($lang['u2ublocked'], false, $u2uheader, $u2ufooter, false, true, false, false);
                    }
                }else{
                    error($lang['badrcpt'], false, $u2uheader, $u2ufooter, false, true, false, false);
                }
            }
            $db->free_result($query);
            if(empty($errors)){
                u2u_msg($lang['imsentmsg'], "u2u.php");
            }else{
                u2u_msg($lang['imsentmsg'].$errors);
            }
        }else{
            if(isset($u2uid)){
                $query = $db->query("SELECT * FROM $table_u2u WHERE u2uid=$u2uid AND owner='$self[username]'");
                if($quote = $db->fetch_array($query)){
                    $prefixes = array($lang['textre'], $lang['textfwd']);
                    $subject = trim(stripslashes(str_replace($prefixes, '', $quote['subject'])));
                    $message = trim(stripslashes($quote['message']));
                    if($forward == 'yes'){
                        $subject = $lang['textfwd'].' '.$subject;
                        $message = '[quote][i]'.$lang['origpostedby'].' '.$quote['msgfrom']."[/i]\n".$message.'[/quote]';
                    }elseif ($reply == 'yes') {
                        $subject = $lang['textre'].' '.$subject;
                        $message = '[quote]'.$message.'[/quote]';
                        $username = $quote['msgfrom'];
                    }
                }
                $db->free_result($query);
            }elseif(isset($previewsubmit)){
                $u2usubject = checkOutput(censor(checkInput(stripslashes($subject))));
                $u2umessage = checkOutput(checkInput(stripslashes($message)));
                $u2umessage = postify($u2umessage, "no", "", "yes", "no");
                $username = $msgto;
                eval("\$u2upreview = \"".template("u2u_send_preview")."\";");
            }
        }
        eval("\$leftpane = \"".template("u2u_send")."\";");
        break;
    case "view":
        $query = $db->query("SELECT * FROM $table_u2u WHERE u2uid='$u2uid' AND owner='$self[username]'");
        if($u2u = $db->fetch_array($query)){
            if($u2u['type'] == 'incoming'){
                $db->query("UPDATE $table_u2u SET readstatus='yes' WHERE u2uid=$u2u[u2uid] OR (u2uid=$u2u[u2uid]+1 AND type='outgoing' AND msgto='$self[username]')");
            }elseif($u2u['type'] == 'draft'){
                $db->query("UPDATE $table_u2u SET readstatus='yes' WHERE u2uid=$u2u[u2uid]");
            }
            $u2udate = gmdate("$dateformat",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
            $u2utime = gmdate("$timecode",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
            $u2udateline = "$u2udate $lang[textat] $u2utime";
            $u2usubject = checkOutput(censor($u2u['subject']));
            $u2umessage = checkOutput($u2u['message']);
            $u2umessage = postify($u2u['message'], "no", "", "yes", "no");
            $u2ufolder = $u2u['folder'];
            $u2ufrom = "<a href=\"member.php?action=viewpro&amp;member=".urlencode($u2u['msgfrom'])."\" target=\"mainwindow\">".$u2u['msgfrom']."</a>";
            $u2uto = ($u2u['type'] == 'draft') ? $lang['textu2unotsent'] : "<a href=\"member.php?action=viewpro&amp;member=".urlencode($u2u['msgto'])."\" target=\"mainwindow\">".$u2u['msgto']."</a>";
            if ($u2u['type'] == 'draft') {
                $sendoptions = "<input type=\"radio\" name=\"mod\" value=\"send\" /> $lang[textu2u]<br />";
                $delchecked = " checked=\"checked\"";
            }elseif ($u2u['msgfrom'] != $self['username']) {
                $sendoptions = "<input type=\"radio\" name=\"mod\" value=\"reply\" checked=\"checked\" /> $lang[textreply]<br /><input type=\"radio\" name=\"mod\" value=\"forward\" /> $lang[textforward]<br />";
            }else{
                $delchecked = " checked=\"checked\"";
            }
            $mtofolder = "<select name=\"tofolder\"><option value=\"\">$lang[textpickfolder]</option>";
            foreach ($folders as $key => $value) {
                if (is_numeric($key)) {
                    $key = $value;
                }
                $mtofolder .= "<option value=\"$key\">$value</option>";
            }
            $mtofolder .= "</select>";
        }else{
            error($lang['u2uadmin_noperm'], false, $u2uheader, $u2ufooter, false, true, false, false);
        }
        $db->free_result($query);
        eval("\$leftpane = \"".template("u2u_view")."\";");
        break;
    case "printable":
        $query = $db->query("SELECT * FROM $table_u2u WHERE u2uid='$u2uid' AND owner='$self[username]'");
        if($u2u = $db->fetch_array($query)){
            $u2udate = gmdate("$dateformat",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
            $u2utime = gmdate("$timecode",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
            $u2udateline = "$u2udate $lang[textat] $u2utime";
            $u2usubject = stripslashes(checkOutput(censor($u2u['subject'])));
            $u2umessage = checkOutput($u2u['message']);
            $u2umessage = stripslashes(postify($u2u['message'], "no", "", "yes", "no"));
            $u2ufolder = $u2u['folder'];
            $u2ufrom = $u2u['msgfrom'];
            $u2uto = ($u2u['type'] == 'draft') ? $lang['textu2unotsent'] : $u2u['msgto'];
            $db->free_result($query);
            eval("echo \"".template("u2u_printable")."\";");
            exit;
        }else{
            error($lang['u2uadmin_noperm'], false, $u2uheader, $u2ufooter, false, true, false, false);
        }
        break;
    case "modif":
        switch($mod){
            case "send":
                header("Location: u2u.php?action=send&u2uid=$u2uid");
                break;
            case "reply":
                header("Location: u2u.php?action=send&u2uid=$u2uid&reply=yes");
                break;
            case "forward":
                header("Location: u2u.php?action=send&u2uid=$u2uid&forward=yes");
                break;
            case "sendtoemail":
                $query = $db->query("SELECT * FROM $table_u2u WHERE u2uid='$u2uid' AND owner='$self[username]'");
                if($u2u = $db->fetch_array($query)){
                    $u2udate = gmdate("$dateformat",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
                    $u2utime = gmdate("$timecode",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
                    $u2udateline = "$u2udate $lang[textat] $u2utime";
                    $u2usubject = stripslashes(checkOutput(censor($u2u['subject'])));
                    $u2umessage = stripslashes(checkOutput($u2u['message']));
                    $u2ufolder = $u2u['folder'];
                    $u2ufrom = $u2u['msgfrom'];
                    $u2uto = ($u2u['type'] == 'draft') ? $lang['textu2unotsent'] : $u2u['msgto'];
                    $email = $lang[textsubject]." ".$u2usubject."\n".$lang['textfrom']." ".$u2ufrom."\n".$lang['textto']." ".$u2uto."\n".$lang['textu2ufolder']." ".$u2ufolder."\n".$lang['textsent']." ".$u2udateline."\n\n".$u2umessage;
                    mail($self['email'], $lang['textu2utoemail']." ".$u2usubject, $email, "From: $bbname <".$self['email'].">");
                    $db->free_result($query);
                    u2u_msg($lang['textu2utoemailsent'], "u2u.php?action=view&u2uid=$u2uid");
                }else{
                    error($lang['u2uadmin_noperm'], false, $u2uheader, $u2ufooter, false, true, false, false);
                }
                break;
            case "delete":
                if($folder == "Trash"){
                    $db->query("DELETE FROM $table_u2u WHERE u2uid=$u2uid AND owner='$self[username]'");
                }else{
                    $db->query("UPDATE $table_u2u SET folder='Trash' WHERE u2uid=$u2uid AND owner='$self[username]'");
                }
                u2u_msg($lang['imdeletedmsg'], "u2u.php?folder=$folder");
                break;
            case "move":
                if(empty($tofolder)){
                    error($lang['textnofolder'], false, $u2uheader, $u2ufooter, "u2u.php?action=view&amp;u2uid=$u2uid", true, false, false);
                }else{
                    if(!(in_array($tofolder, $folders) || $tofolder == 'Inbox' || $tofolder == 'Outbox' || $tofolder == 'Drafts') || ($tofolder == 'Inbox' && ($type == 'draft' || $type == 'outgoing')) || ($tofolder == 'Outbox' && ($type == 'incoming' || $type == 'draft')) || ($tofolder == 'Drafts' && ($type == 'incoming' || $type == 'outgoing'))){
                        error($lang['textcantmove'], false, $u2uheader, $u2ufooter, "u2u.php?action=view&amp;u2uid=$u2uid", true, false, false);
                    }
                    $db->query("UPDATE $table_u2u SET folder='$tofolder' WHERE u2uid=$u2uid AND owner='$self[username]'");
                    u2u_msg($lang['textmovesucc'], "u2u.php?folder=$folder");
                }
                break;
            case "markunread":
                if($type == 'outgoing'){
                    error($lang['textnomur'], false, $u2uheader, $u2ufooter, "u2u.php?folder=$folder", true, false, false);
                }
                $db->query("UPDATE $table_u2u SET readstatus='no' WHERE u2uid=$u2uid AND owner='$self[username]'");
                u2u_msg($lang['textmarkedunread'], "u2u.php?folder=$folder");
                break;

        }
        break;
    case "mod":
        switch($modaction){
            case "delete":
                if(empty($u2u_select)){
                    error($lang['textnonechosen'], false, $u2uheader, $u2ufooter, "u2u.php?folder=$folder", true, false, false);
                }
                unset($in);
                foreach($u2u_select as $value){
                    $in .= (empty($in)) ? "$value" : ",$value";
                }
                if($folder == "Trash"){
                    $db->query("DELETE FROM $table_u2u WHERE u2uid IN($in) AND owner='$self[username]'");
                }else{
                    $db->query("UPDATE $table_u2u SET folder='Trash' WHERE u2uid IN($in) AND owner='$self[username]'");
                }
                u2u_msg($lang['imdeletedmsg'], "u2u.php?folder=$folder");
                break;
            case "move":
                if(empty($tofolder)){
                    error($lang['textnofolder'], false, $u2uheader, $u2ufooter, "u2u.php?action=view&amp;u2uid=$u2uid", true, false, false);
                }else{
                    if (empty($u2u_select)) {
                        error($lang['textnonechosen'], false, $u2uheader, $u2ufooter, "u2u.php?folder=$folder", true, false, false);
                    }
                    unset($in);
                    foreach($u2u_select as $value){
                        $type = ${'type'.$value};
                        if((in_array($tofolder, $folders) || $tofolder == 'Inbox' || $tofolder == 'Outbox' || $tofolder == 'Drafts') && !($tofolder == 'Inbox' && ($type == 'draft' || $type == 'outgoing')) && !($tofolder == 'Outbox' && ($type == 'incoming' || $type == 'draft')) && !($tofolder == 'Drafts' && ($type == 'incoming' || $type == 'outgoing'))){
                            $in .= (empty($in)) ? "$value" : ",$value";
                        }
                    }
                    if(empty($in)){
                        error($lang['textcantmove'], false, $u2uheader, $u2ufooter, "u2u.php?folder=$folder", true, false, false);
                    }
                    $db->query("UPDATE $table_u2u SET folder='$tofolder' WHERE u2uid IN($in) AND owner='$self[username]'");
                    u2u_msg($lang['textmovesucc'], "u2u.php?folder=$folder");
                }
                break;
            case "markunread":
                if(empty($u2u_select)){
                    error($lang['textnonechosen'], false, $u2uheader, $u2ufooter, "u2u.php?folder=$folder", true, false, false);
                }
                unset($in);
                foreach($u2u_select as $value){
                    if(${'type'.$value} != 'outgoing'){
                        $in .= (empty($in)) ? "$value" : ",$value";
                    }
                }
                if(empty($in)){
                    error($lang['textnonechosen'], false, $u2uheader, $u2ufooter, "u2u.php?folder=$folder", true, false, false);
                }
                $db->query("UPDATE $table_u2u SET readstatus='no' WHERE u2uid IN($in) AND owner='$self[username]'");
                u2u_msg($lang['textmarkedunread'], "u2u.php?folder=$folder");
                break;
            default:
                error($lang['testnothingchos'], false, $u2uheader, $u2ufooter, "u2u.php?folder=$folder", true, false, false);
                break;
        }
        break;
    case "folders":
        if(isset($folderssubmit)){
            $newfolders = explode(",", $u2ufolders);
            foreach($newfolders as $key=>$value){
                $newfolders[$key] = trim($value);
                if(empty($newfolders[$key])){
                    unset($newfolders[$key]);
                }
            }
            foreach($folders as $value){
                if($farray[$value] != 0 && !in_array($value, $newfolders) && !in_array($value, array('Inbox', 'Outbox', 'Drafts', 'Trash'))){
                    $newfolders[] = $value;
                }
            }
            $u2ufolders = checkInput(implode(", ", $newfolders));
            $db->query("UPDATE $table_members SET u2ufolders='$u2ufolders' WHERE username='$self[username]'");
            u2u_msg($lang['foldersupdate'], "u2u.php?action=folders");
        }else{
            $self['u2ufolders'] = checkOutput($self['u2ufolders']);
            eval("\$leftpane = \"".template("u2u_folders")."\";");
        }
        break;
    case "ignore":
        if(isset($ignoresubmit)){
            $self['ignoreu2u'] = checkInput($self['ignoreu2u']);
            $db->query("UPDATE $table_members SET ignoreu2u='$ignorelist' WHERE username='$self[username]'");
            u2u_msg($lang['ignoreupdate'], "u2u.php?action=ignore");
        }else{
            $self['ignoreu2u'] = checkOutput($self['ignoreu2u']);
            eval("\$leftpane = \"".template("u2u_ignore")."\";");
        }
        break;
    case "emptytrash":
        $db->query("DELETE FROM $table_u2u WHERE folder='Trash' AND owner='$self[username]'");
        u2u_msg($lang['texttrashemptied'], "u2u.php");
        break;
    default:
        unset($u2usin, $u2usout, $u2usdraft);
        if(empty($folder)){
            $folder = "Inbox";
        }
        $query = $db->query("SELECT u.*, w.username, w.invisible FROM $table_u2u u LEFT JOIN $table_whosonline w ON (u.msgto=w.username OR u.msgfrom=w.username) AND w.username!='$self[username]' WHERE u.folder='$folder' AND u.owner='$self[username]' ORDER BY dateline DESC");
        while($u2u = $db->fetch_array($query)){
            if($u2u['readstatus'] == 'yes'){
                $u2ureadstatus = $lang['textread'];
            }else{
                $u2ureadstatus = "<b>".$lang['textunread']."</b>";
            }
            if(empty($u2u['subject'])) {
                $u2u['subject'] = "&laquo;".$lang['textnosub']."&raquo;";
            }
            $u2usubject = checkOutput(censor($u2u['subject']));
            if($u2u['type'] == 'incoming'){
                if($u2u['msgfrom'] == $u2u['username'] || $u2u['msgfrom'] == $self['username']){
                    if($u2u['invisible'] == 1){
                        if(X_ADMIN){
                            $online = $lang['hidden'];
                        }else{
                            $online = $lang['textoffline'];
                        }
                    }else{
                            $online = $lang['textonline'];
                    }
                }else{
                    $online = $lang['textoffline'];
                }
                $u2usent = "<a href=\"member.php?action=viewpro&amp;member=".urlencode($u2u['msgfrom'])."\" target=\"_blank\">".$u2u['msgfrom']."</a> ($online)";
            }elseif($u2u['type'] == 'outgoing'){
                if($u2u['msgto'] == $u2u['username'] || $u2u['msgto'] == $self['username']){
                    if($u2u['invisible'] == 1){
                        if(X_ADMIN){
                            $online = $lang['hidden'];
                        }else{
                            $online = $lang['textoffline'];
                        }
                    }else{
                            $online = $lang['textonline'];
                    }
                }else{
                    $online = $lang['textoffline'];
                }
                $u2usent = "<a href=\"member.php?action=viewpro&amp;member=".urlencode($u2u['msgto'])."\" target=\"_blank\">".$u2u['msgto']."</a> ($online)";
            }elseif($u2u['type'] == 'draft'){
                $u2usent = $lang['textu2unotsent'];
            }
            $u2udate = gmdate("$dateformat",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
            $u2utime = gmdate("$timecode",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
            $u2udateline = "$u2udate $lang[textat] $u2utime";
            if($u2u['type'] == 'incoming'){
                $u2us = 'u2usin';
            }elseif($u2u['type'] == 'outgoing'){
                $u2us = 'u2usout';
            }elseif($u2u['type'] == 'draft'){
                $u2us = 'u2usdraft';
            }
            eval("\$$u2us .= \"".template('u2u_row')."\";");
        }
        $db->free_result($query);
        if(empty($u2usin)){
            eval("\$u2usin = \"".template('u2u_row_none')."\";");
        }
        if(empty($u2usout)){
            eval("\$u2usout = \"".template('u2u_row_none')."\";");
        }
        if(empty($u2usdraft)){
            eval("\$u2usdraft = \"".template('u2u_row_none')."\";");
        }
        if($folder == 'Inbox'){
            eval("\$u2ulist = \"".template('u2u_inbox')."\";");
        }elseif($folder == 'Outbox'){
            eval("\$u2ulist = \"".template('u2u_outbox')."\";");
        }elseif($folder == 'Drafts'){
            eval("\$u2ulist = \"".template('u2u_drafts')."\";");
        }else{
            eval("\$u2ulist = \"".template('u2u_inbox')."<br />".template('u2u_outbox')."<br />".template('u2u_drafts')."\";");
        }
        $mtofolder = "<select name=\"tofolder\"><option value=\"\">$lang[textpickfolder]</option>";
        foreach($folders as $key => $value){
            if(is_numeric($key)){
                $key = $value;
            }
            $mtofolder .= "<option value=\"$key\">$value</option>";
        }
        $mtofolder .= "</select>";
        eval("\$leftpane = \"".template('u2u_main')."\";");
        break;
}

// Display page
eval("\$u2upage = \"".template($tu2u)."\";");
echo stripslashes($u2upage);

// Function for informative messages
function u2u_msg($msg, $redirect) {
    global $u2uheader, $u2ufooter, $tablewidth, $bordercolor, $tablespace, $borderwidth, $altbg1;
    if(!empty($redirect)){
        redirect($redirect);
    }
    eval("echo stripslashes(\"".template('u2u_msg')."\");");
    exit;
}

?>