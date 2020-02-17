<?php
/* $Id: u2uadmin.php,v 1.3 2004/04/17 18:46:44 Tularis Exp $ */
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
loadtemplates('css','u2u_header_admin','u2u_footer_admin','error_nologinsession','u2u','u2u_folderlink','u2u_inbox','u2u_outbox','u2u_drafts','u2u_row_admin','u2u_row_none','u2u_view_admin','u2u_main_admin');

// Eval necessary templates
eval("\$css = \"".template('css')."\";");
eval("\$u2uheader = \"".template('u2u_header_admin')."\";");
eval("\$u2ufooter = \"".template('u2u_footer')."\";");
eval("\$notadmin = \"".template("error_nologinsession")."\";");

// Check to see whether the user trying to access the page has admin status.
// If there is no administration status the user will be displayed with a login box.
if($self['status'] != "Super Administrator") {
    echo $u2uheader.$notadmin.$u2ufooter;
    exit;
}

// Check to see if member exists
$userid = $db->fetch_array($db->query("SELECT uid FROM $table_members WHERE username='$uid'"));
if(empty($userid['uid'])){
    error($lang['nomember'], false, $u2uheader);
}

$thewidth = $tablewidth;

// Start actions
switch($action){
    case "view":
        smcwcache();
        $query = $db->query("SELECT * FROM $table_u2u WHERE u2uid='$u2uid' AND owner='$uid'");
        if($u2u = $db->fetch_array($query)){
            $u2udate = gmdate("$dateformat",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
            $u2utime = gmdate("$timecode",$u2u['dateline'] + ($timeoffset * 3600) + ($addtime * 3600));
            $u2udateline = "$u2udate $lang[textat] $u2utime";
            $u2usubject = checkOutput($u2u['subject']);
            $u2umessage = checkOutput($u2u['message']);
            $u2umessage = postify($u2u['message'], "no", "", "yes", "no");
            $u2ufolder = $u2u['folder'];
            $u2ufrom = "<a href=\"member.php?action=viewpro&amp;member=".$u2u['msgfrom']."\" target=\"mainwindow\">".urlencode($u2u['msgfrom'])."</a>";
            $u2uto = ($u2u['type'] == 'draft') ? $lang['textu2unotsent'] : "<a href=\"member.php?action=viewpro&amp;member=".urlencode($u2u['msgto'])."\" target=\"mainwindow\">".$u2u['msgto']."</a>";
        }else{
            error($lang['u2uadmin_noperm'], false, $u2uheader);
        }
        $db->free_result($query);
        eval("\$leftpane = \"".template("u2u_view_admin")."\";");
        break;
    case "delete":
        if(isset($u2uid)){
            $db->query("DELETE FROM $table_u2u WHERE u2uid=$u2uid AND owner='$uid'");
            u2u_msg($lang['imdeletedmsg'], "u2uadmin.php?uid=$uid&folder=$folder");
            break;
        }elseif(isset($u2u_select)){
            unset($in);
            foreach($u2u_select as $value){
                $in .= (empty($in)) ? "$value" : ",$value";
            }
            $db->query("DELETE FROM $table_u2u WHERE u2uid IN($in) AND owner='$uid'");
            u2u_msg($lang['imdeletedmsg'], "u2uadmin.php?folder=$folder&uid=$uid");
        }else{
            error($lang['textnonechosen'], false, $u2uheader, "u2uadmin.php?folder=$folder&uid=$uid");
        }
        break;
    default:
        unset($u2usin, $u2usout, $u2usdraft);
        if(empty($folder)){
            $folder = "Inbox";
        }
        if($folder == "Inbox"){
            $search = 'incoming';
        }elseif($folder == "Outbox"){
            $search = 'outgoing';
        }elseif($folder == "Drafts"){
            $search = 'draft';
        }
        $query = $db->query("SELECT * FROM $table_u2u WHERE type='$search' AND owner='$uid' ORDER BY dateline DESC");
        while($u2u = $db->fetch_array($query)){
            if($u2u['readstatus'] == 'yes'){
                $u2ureadstatus = $lang['textread'];
            }else{
                $u2ureadstatus = "<b>".$lang['textunread']."</b>";
            }
            if(empty($u2u['subject'])) {
                $u2u['subject'] = "&laquo;".$lang['textnosub']."&raquo;";
            }
            $u2usubject = checkOutput($u2u['subject']);
            if($u2u['type'] == 'incoming'){
                $u2usent = "<a href=\"member.php?action=viewpro&amp;member=".urlencode($u2u['msgfrom'])."\" target=\"mainwindow\">".$u2u['msgfrom']."</a>";
            }elseif($u2u['type'] == 'outgoing'){
                $u2usent = "<a href=\"member.php?action=viewpro&amp;member=".urlencode($u2u['msgto'])."\" target=\"mainwindow\">".$u2u['msgto']."</a>";
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
            eval("\$$u2us .= \"".template('u2u_row_admin')."\";");
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
        }
        eval("\$leftpane = \"".template('u2u_main_admin')."\";");
        break;
}

// Display page
eval("\$u2upage = \"".template('u2u_admin')."\";");
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