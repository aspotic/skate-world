<?php
/* $Id: buddy.php,v 1.4 2004/04/19 19:50:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2003 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

// get Global stuff
require 'header.php';

// Pre-load templates
loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','buddylist_edit_buddy','buddylist_edit','buddylist_buddy_online','buddylist_buddy_offline','buddylist','buddylist_message', 'header','footer','css','functions_bbcode');
eval('$css = "'.template('css').'";');

if(!isset($self['username']) || strlen($self['username']) < 1) {
    error($lang['u2unotloggedin']);
}

// Define necessary functions
function blistmsg($message, $redirect='', $exit=false) {
    global $bordercolor, $tablewidth, $borderwidth, $tablespace, $altbg1, $css, $bbname, $lang;
    if($redirect != '') {
        redirect($redirect);
    }
    eval("echo stripslashes(\"".template("buddylist_message")."\");");
    if($exit) {
        exit();
    }
}

switch($action) {
    case 'add':
        if(!is_array($buddys)) {
            $buddys = array($buddys);
        } elseif(count($buddys) > 10) {
            $buddys = array_slice($buddys, 0, 10);
        }

        foreach($buddys as $key=>$buddy) {
            if(empty($buddy) || strlen(trim($buddy)) == 0) {
                blistmsg($lang['nobuddyselected'], '', true);
            } else {
                $buddy = addslashes(checkInput($buddy));
                $q = $db->query("SELECT count(*) FROM $table_buddys WHERE username='$xmbuser' AND buddyname='$buddy'");
                if($db->result($q, 0) > 0) {
                    blistmsg($buddy.$lang['buddyalreadyonlist']);
                } else {
                    // not yet on the list.
                    $q2 = $db->query("SELECT count(*) FROM $table_members WHERE username='$buddy'");
                    if($db->result($q2, 0) < 1) {
                        blistmsg($lang['nomember']);
                    } else {
                        $db->query("INSERT INTO $table_buddys (buddyname, username) VALUES ('$buddy', '$xmbuser')");
                        blistmsg($buddy.' '.$lang['buddyaddedmsg'], 'buddy.php');
                    }
                }
            }
        }
        break;

    case 'edit':
        $q = $db->query("SELECT buddyname FROM $table_buddys WHERE username='$xmbuser'") or die($db->error());
        $buddys = array();
        while($buddy = $db->fetch_array($q)) {
            eval('$buddys[] = "'.template('buddylist_edit_buddy').'";');
        }
        if(count($buddys) > 0) {
            $buddys = implode("\n", $buddys);
        } else {
            $buddys = "";
        }
        eval('echo stripslashes("'.template('buddylist_edit').'");');
        break;

    case 'delete':
        if(isset($delete) && is_array($delete) && count($delete) > 0) {
            // we can delete :D
            foreach($delete as $key=>$buddy) {
                $buddy = addslashes(checkInput($buddy));
                $db->query("DELETE FROM $table_buddys WHERE buddyname='$buddy' AND username='$xmbuser'");
            }
        }
        blistmsg($lang['buddylistupdated'], 'buddy.php');
        break;

    case 'add2u2u':
        $users = array();
        $buddys = array();
        $q = $db->query("SELECT b.buddyname, w.invisible, w.username FROM $table_buddys b LEFT JOIN $table_whosonline w ON (b.buddyname=w.username) WHERE b.username='$xmbuser'");
        if(X_ADMIN) {
            while($buddy = $db->fetch_array($q)) {
                if($buddy['invisible'] == 1) {
                    eval('$buddys[online] .= "'.template("buddy_u2u_inv").'";');
                } elseif($buddy['username'] != '') {
                    eval('$buddys[online] .= "'.template("buddy_u2u_on").'";');
                } else {
                    eval('$buddys[offline] .= "'.template("buddy_u2u_off").'";');
                }
            }
        } else {
            while($buddy = $db->fetch_array($q)) {
                if($buddy['invisible'] == 1) {
                    eval('$buddys[offline] .= "'.template("buddy_u2u_off").'";');
                } elseif($buddy['username'] != '') {
                    eval('$buddys[online] .= "'.template("buddy_u2u_on").'";');
                } else {
                    eval('$buddys[offline] .= "'.template("buddy_u2u_off").'";');
                }
            }
        }
        if(count($buddys) == 0) {
            blistmsg($lang['no_buddies']);
        } else {
            eval('echo stripslashes("'.template('buddy_u2u').'");');
        }
        break;

    default:
        $q = $db->query("SELECT b.buddyname, w.invisible, w.username FROM $table_buddys b LEFT JOIN $table_whosonline w ON (b.buddyname=w.username) WHERE b.username='$xmbuser'");
        $buddys = array();

        if(X_ADMIN) {
            while($buddy = $db->fetch_array($q)) {
                if(strlen($buddy['username']) > 0) {
                    if($buddy['invisible'] == 1){
                       $buddystatus = $lang['hidden'];
                    }else{
                        $buddystatus = $lang['textonline'];
                    }
                    eval("\$buddys[online] .= \"".template("buddylist_buddy_online")."\";");
                } else {
                    eval("\$buddys[offline] .= \"".template("buddylist_buddy_offline")."\";");
                }
            }
        } else {
            while($buddy = $db->fetch_array($q)) {
                if(strlen($buddy['username']) > 0) {
                    if($buddy['invisible'] == 1){
                       eval("\$buddys[offline] .= \"".template("buddylist_buddy_offline")."\";");
                       continue;
                    }else{
                        $buddystatus = $lang['textonline'];
                    }
                    eval("\$buddys[online] .= \"".template("buddylist_buddy_online")."\";");
                } else {
                    eval("\$buddys[offline] .= \"".template("buddylist_buddy_offline")."\";");
                }
            }
         }
        eval('echo stripslashes("'.template('buddylist').'");');
        break;
}
?>