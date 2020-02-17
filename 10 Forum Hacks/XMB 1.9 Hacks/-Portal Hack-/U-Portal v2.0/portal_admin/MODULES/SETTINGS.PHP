<?php

/************************************************************************/
/* Xmb Ultimate Portal v2.0.0                                           */
/* ==================================================================== */
/*  Copyright (c) 2003 - 2004 by FREEWILL46 (freewill_46@hotmail.com)   */
/*  http://www.fw46.com/eforum                                          */
/*======================================================================*/
/* BASED ON PHP-NUKE: Advanced Content Management System                */
/* =====================================================================*/
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/************************************************************************/

if (!eregi("portal_admin.php", $_SERVER['PHP_SELF'])) { die ("Access Denied"); }
global $status;
if ($status == "Super Administrator" || $status == "Administrator") {

function Configure() {
    global $prefix, $db, $borderwidth, $tablespace, $bordercolor, $altbg2 ,$altbg1;
    include ("portal_header.php");
    GraphicAdmin();
    $result = $db->query("SELECT anonpost, commentlimit, anonymous, articlecomm, storyhome, oldnum, banners, displaynews, numaccounts, admingraphic, news_fid, news_list_this_many, date_format, msg_length_limit, dgif, CensorMode, CensorReplace, locale, portal_status, portal_invisible, portal_secure, portal_online, show_ticker from ".$prefix."_config");
    list($anonpost, $commentlimit, $anonymous, $articlecomm, $storyhome, $oldnum, $banners, $displaynews, $numaccounts, $admingraphic, $news_fid, $news_list_this_many, $date_format, $msg_length_limit, $dgif, $CensorMode, $CensorReplace, $locale, $portal_status, $portal_invisible, $portal_secure, $portal_online, $show_ticker) = $db->fetch_row($result);
    OpenTable();
    echo "<center><font class='subject'><b>U-Portal Configuration</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='tablerow'><b>General Info</b></font></center>
    <form action='portal_admin.php' method='post'>
	<table border='0' cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" width=\"100%\">";
    if($portal_status == "on") {
        $portal_status_on = "selected=\"selected\"";
    }else{
        $portal_status_off = "selected=\"selected\"";

    }

   echo "<tr><td class=\"tablerow\" bgcolor=\"$altbg1\" width=\"50%\">Display portal on main page?:</td>
         <td class=\"tablerow\" bgcolor=\"$altbg2\"><select size=\"1\" name=\"xportal_status\">
                                                <option value=\"on\"$portal_status_on>Enabled</option>
                                                <option value=\"off\"$portal_status_off>Disabled</option>
        </select></td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Locale Time Format:</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type='text' name='xlocale' value='$locale' size='20' maxlength='40'>
    </td></tr>";
    
   	echo"<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Graphics in Administration Menu?</td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
    if ($admingraphic==1) {
	echo "<input type='radio' name='xadmingraphic' value='1' checked>Yes &nbsp;<input type='radio' name='xadmingraphic' value='0'>No";
    } else {
	echo "<input type='radio' name='xadmingraphic' value='1'>Yes &nbsp;<input type='radio' name='xadmingraphic' value='0' checked>"._NO."";
    }
    echo"</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Activate Banners in your site?</td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
    if ($banners==1) {
	echo "<input type='radio' name='xbanners' value='1' checked>Yes &nbsp;<input type='radio' name='xbanners' value='0'>No";
    } else {
	echo "<input type='radio' name='xbanners' value='1'>Yes &nbsp;<input type='radio' name='xbanners' value='0' checked>No";
    }
    echo"</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Stories Number in Home:</td>
        <td bgcolor=\"$altbg2\" class=\"tablerow\"><select name='xstoryhome'>
        <option name='xstoryhome'>$storyhome</option>
        <option name='xstoryhome'>5</option>
        <option name='xstoryhome'>10</option>
        <option name='xstoryhome'>15</option>
        <option name='xstoryhome'>20</option>
        <option name='xstoryhome'>25</option>
        <option name='xstoryhome'>30</option>
        </select></td></tr>";
        
        echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Stories in Old Articles Box:</td>
        <td bgcolor=\"$altbg2\" class=\"tablerow\"><select name='xoldnum'>
        <option name='xoldnum'>$oldnum</option>
        <option name='xoldnum'>10</option>
        <option name='xoldnum'>20</option>
        <option name='xoldnum'>30</option>
        <option name='xoldnum'>40</option>
        <option name='xoldnum'>50</option>
        </select></td></tr>";

        echo"<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Allow Anonymous to Post?</td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
        if ($anonpost==1) {
	    echo "<input type='radio' name='xanonpost' value='1' checked>Yes &nbsp;<input type='radio' name='xanonpost' value='0'>No";
        } else {
	    echo "<input type='radio' name='xanonpost' value='1'>Yes &nbsp;<input type='radio' name='xanonpost' value='0' checked>No";
        }
       echo "</td></tr>";
       
       echo"<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Comments Limit in Bytes:</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type='text' name='xcommentlimit' value='$commentlimit' size='11' maxlength='10'></td></tr>";
       echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Anonymous Default Name</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type='text' name='xanonymous' value='$anonymous'></td></tr>";

       echo"<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Activate Comments in Articles?</td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
       if ($articlecomm==1) {
       echo "<input type='radio' name='xarticlecomm' value='1' checked>Yes &nbsp;<input type='radio' name='xarticlecomm' value='0'>No";
       } else {
	   echo "<input type='radio' name='xarticlecomm' value='1'>Yes &nbsp;<input type='radio' name='xarticlecomm' value='0' checked>No";
       }
       echo "</td></tr>";

       echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Censor Mode</td><td bgcolor=\"$altbg2\" class=\"tablerow\">";
       if ($CensorMode == 0) {
	   $sel0 = "selected";
	   $sel1 = "";
	   $sel2 = "";
	   $sel3 = "";
       } elseif ($CensorMode == 1) {
	   $sel0 = "";
	   $sel1 = "selected";
	   $sel2 = "";
	   $sel3 = "";
       } elseif ($CensorMode == 2) {
	   $sel0 = "";
	   $sel1 = "";
	   $sel2 = "selected";
	   $sel3 = "";
       } elseif ($CensorMode == 3) {
	   $sel0 = "";
	   $sel1 = "";
	   $sel2 = "";
	   $sel3 = "selected";
       }
       echo "<select name='xCensorMode'>
	   <option name='xCensorMode' value='0' $sel0>No filtering</option>
	   <option name='xCensorMode' value='1' $sel1>Exact match</option>
	   <option name='xCensorMode' value='2' $sel2>Match word at the beginning</option>
       <option name='xCensorMode' value='3' $sel3>Match anywhere in the text</option>
	   </select></td></tr>";
       echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Replace Censored Words with:</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type='text' name='xCensorReplace' value='$CensorReplace' size='10' maxlength='10'></td></tr>";

       echo "</table><br><br>";
       CloseTable();
       echo "<br>";
       
       OpenTable();
       echo "<center><font class='subject'><b>U-Portal Forum News</b></font></center>";
       CloseTable();
       echo "<br>";
       OpenTable();
       echo "<center><font class='tablerow'><b>General Configration</b></font></center><br>
	   <table border='0' cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" width=\"100%\">";
       echo"<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Display The Forum news?:</td><td bgcolor=\"$altbg2\" class=\"tablerow\">";

       if($displaynews == "on") {
        $displaynews_on = "selected=\"selected\"";
        }else{
        $displaynews_off = "selected=\"selected\"";
        }
       echo "<select name='xdisplaynews'>
       <option name='xdisplaynews' value='on' $displaynews_on>On</option>
	   <option name='xdisplaynews' value='off' $displaynews_off>Off</option>
       </select></td></tr>";
       
       echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">The forum id you want to drag news from:</td>
       <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type='text' name='xnews_fid' value='$news_fid' size='5'></td></tr>";

       echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Number of threads to list:</td>
       <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type='text' name='xnews_list_this_many' value='$news_list_this_many' size='5'></td></tr>";

       echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">The format of your date (see php.net):</td>
       <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type='text' name='xdate_format' value='$date_format' size='25'></td></tr>";

       echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">Number of characters to cut messages down to:</td>
       <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type='text' name='xmsg_length_limit' value='$msg_length_limit' size='5'></td></tr>";

       echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\">The default smilie if one isn't present in the post this one will be used:</td>
       <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type='text' name='xdgif' value='$dgif' size='10'></td></tr>";

       echo"</table><br><br>";
       CloseTable();
       echo "<br>";
       OpenTable();
       echo "<center><b>Other Options</b></center>";
       CloseTable();
       echo "<br>";
       if($portal_invisible == "on") {
        $invisible_on = "selected=\"selected\"";
        }else{
        $invisible_off = "selected=\"selected\"";
        }
       OpenTable();
       echo "<table border='0' cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" width=\"100%\">";
       echo"<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\"><b>Do you have the invisible hack?</b><br>Note:Select this only if you have the <br>invisible hack installed.</td>";
       echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><select name='xportal_invisible'>
       <option name='xportal_invisible' value='on' $invisible_on>Yes</option>
	   <option name='xportal_invisible' value='off' $invisible_off>No</option>
       </select><br>It will appear on the login block</td></tr>";
        if($portal_secure == "on") {
        $secure_on = "selected=\"selected\"";
        }else{
        $secure_off = "selected=\"selected\"";
        }
        echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\"><b>Do you have the secure Login Hack?</b><br>Note:Select this only if you have the<br>secure Login Hack installed.</td>";
        echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><select name='xportal_secure'>
        <option name='xportal_secure' value='on' $secure_on>Yes</option>
	    <option name='xportal_secure' value='off' $secure_off>No</option>
        </select><br>It will appear on the login block</td></tr>";
        if($portal_online == "on") {
        $online_on = "selected=\"selected\"";
        }else{
        $online_off = "selected=\"selected\"";
        }
        echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\"><b>Display Who's online Table?</b><br> Select this if you want to display<br>Who's Online table On footer.</td>";
        echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><select name='xportal_online'>
        <option name='xportal_online' value='on' $online_on>Yes</option>
	    <option name='xportal_online' value='off' $online_off>No</option></select></td></tr>";
        if($show_ticker == "on") {
        $ticker_on = "selected=\"selected\"";
        }else{
        $ticker_off = "selected=\"selected\"";
        }
       echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"50%\"><b>Display News Ticker Table?</b><br> Select this if you want to display<br> News Ticker Table on Header.</td>";
       echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><select name='xshow_ticker'>
        <option name='xshow_ticker' value='on' $ticker_on>Yes</option>
	    <option name='xshow_ticker' value='off' $ticker_off>No</option></select></td></tr>";
       echo "</table>";
       CloseTable();
       echo "<br>";
       OpenTable();
       echo "<input type='hidden' name='op' value='ConfigSave'>"
	   ."<center><input type='submit' value='Save Changes'></center>"
	   ."</form>";
       CloseTable();
       include ("portal_footer.php");
}

function ConfigSave ($xanonpost, $xcommentlimit, $xanonymous, $xarticlecomm, $xstoryhome, $xoldnum, $xbanners, $xdisplaynews, $xnumaccounts, $xadmingraphic, $xnews_fid, $xnews_list_this_many, $xdate_format, $xmsg_length_limit, $xdgif, $xCensorMode, $xCensorReplace, $xlocale, $xportal_status, $xportal_invisible, $xportal_secure, $xportal_online, $xshow_ticker) {
    global $prefix, $db;
    $db->query("UPDATE ".$prefix."_config SET
                        anonpost = '$xanonpost',
                        commentlimit = '$xcommentlimit',
                        anonymous = '$xanonymous',
                        articlecomm = '$xarticlecomm',
                        storyhome = '$xstoryhome',
                        oldnum = '$xoldnum',
                        banners = '$xbanners',
                        locale = '$xlocale',
                        displaynews = '$xdisplaynews',
                        numaccounts = '$xnumaccounts',
                        admingraphic = '$xadmingraphic',
                        news_fid = '$xnews_fid',
                        news_list_this_many = '$xnews_list_this_many',
                        date_format = '$xdate_format',
                        msg_length_limit = '$xmsg_length_limit',
                        dgif = '$xdgif',
                        CensorMode = '$xCensorMode',
                        CensorReplace = '$xCensorReplace',
                        portal_status = '$xportal_status',
                        portal_invisible = '$xportal_invisible',
                        portal_secure = '$xportal_secure',
                        portal_online = '$xportal_online',
                        show_ticker = '$xshow_ticker'
                        ");
                        Header("Location: portal_admin.php?op=Configure");
}

switch($op) {

    case "Configure":
    Configure();
    break;

    case "ConfigSave":
    ConfigSave ($xanonpost, $xcommentlimit, $xanonymous, $xarticlecomm, $xstoryhome, $xoldnum, $xbanners, $xdisplaynews, $xnumaccounts, $xadmingraphic, $xnews_fid, $xnews_list_this_many, $xdate_format, $xmsg_length_limit, $xdgif, $xCensorMode, $xCensorReplace, $xlocale, $xportal_status, $xportal_invisible, $xportal_secure, $xportal_online, $xshow_ticker);
    break;

}

} else {
    echo "Access Denied";
}

?>
