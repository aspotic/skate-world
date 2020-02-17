<?php
/* $Id: faq.php,v 1.4 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require './header.php';

if ($faqstatus != "on" && $page != 'forumrules') {
    loadtemplates('css', 'header', 'misc_feature_notavailable', 'footer', 'footer_querynum', 'footer_phpsql', 'footer_totaltime', 'footer_load');
    eval("\$css = \"".template('css')."\";");
    $navigation = "&raquo; <a href=\"faq.php\">" .$lang['textfaq']. "</a>";
    eval("\$header = \"".template("header")."\";");
    eval("\$featureoff = \"".template("misc_feature_notavailable")."\";");
    eval("\$footer = \"".template("footer")."\";");
    end_time();
    echo $header;
    echo $featureoff;
    echo $footer;
    exit();
}

$page = (isset($page)) ? $page : null;

switch($page){
    case 'usermaint';
        loadtemplates('css', 'faq_usermaint', 'header', 'footer_querynum', 'footer_phpsql', 'footer_totaltime', 'footer_load');
        $navigation = "&raquo; <a href=\"faq.php\">" .$lang['textfaq']. "</a> &raquo; " .$lang['textuserman']. "";
        eval("\$faq = \"".template("faq_usermaint")."\";");
        break;
        
    case 'using';
        loadtemplates('css', 'faq_using', 'header', 'footer_querynum', 'footer_phpsql', 'footer_totaltime', 'footer_load');
        $navigation = "&raquo; <a href=\"faq.php\">" .$lang['textfaq']. "</a> &raquo; " .$lang['textuseboa']. "";
        eval("\$faq = \"".template("faq_using")."\";");
        break;
            
    case 'messages';
        loadtemplates('css', 'faq_messages_smilierow', 'faq_messages', 'header', 'footer_querynum', 'footer_phpsql', 'footer_totaltime', 'footer_load');
        $smilierows = NULL;
        $navigation = "&raquo; <a href=\"faq.php\">" .$lang['textfaq']. "</a> &raquo; " .$lang['textpostread']. "";
        $querysmilie = $db->query("SELECT * FROM " .$table_smilies. " WHERE type = 'smiley'") or die($db->error());
        while($smilie = $db->fetch_array($querysmilie)) {
            eval("\$smilierows .= \"".template("faq_messages_smilierow")."\";");
        }
        eval("\$faq = \"".template("faq_messages")."\";");
        break;
            
    case 'misc';
        loadtemplates('css', 'faq_misc_rankrow', 'faq_misc' ,'header', 'footer_querynum', 'footer_phpsql', 'footer_totaltime', 'footer_load');
        $stars        = '';
        $rankrows    = '';
        $navigation = '&raquo; <a href="faq.php">' .$lang['textfaq']. '</a> &raquo; '.$lang['textmiscfaq'];
        $query = $db->query("SELECT * FROM $table_ranks WHERE title!='Moderator' AND title!='Super Moderator' AND title!='Super Administrator' AND title!='Administrator' ORDER BY posts ASC");
        while($ranks = $db->fetch_array($query)) {
            $stars = str_repeat("<img src=\"" .$imgdir. "/star.gif\" alt=\"*\" />", $ranks['stars']);
            eval("\$rankrows .= \"".template("faq_misc_rankrow")."\";");
            $stars = '';
        }
        eval("\$faq = \"".template("faq_misc")."\";");
        break;

    case 'forumrules':
        loadtemplates('css', 'faq_forumrules' ,'header', 'footer_querynum', 'footer_phpsql', 'footer_totaltime', 'footer_load');
        $navigation = "&raquo; <a href=\"faq.php\">" .$lang['textfaq']. "</a> &raquo; " .$lang['textbbrules']. "";
        if (empty($SETTINGS['bbrulestxt'])) {
            $SETTINGS['bbrulestxt'] =    $lang['textnone'];
        } else {
            $SETTINGS['bbrulestxt'] = nl2br(stripslashes($SETTINGS['bbrulestxt']));
        }
        eval("\$faq = \"".template("faq_forumrules")."\";");
        break;

    default:
        loadtemplates('css', 'faq', 'header', 'footer_querynum', 'footer_phpsql', 'footer_totaltime', 'footer_load');
        $navigation = "&raquo; " .$lang['textfaq']. "";
        eval("\$faq = \"".template("faq")."\";");
        break;
}

end_time();
eval("\$css = \"".template('css')."\";");
eval('echo "'.template('header').'";');
echo $faq;
eval('echo "'.template('footer').'";');
exit();
?>