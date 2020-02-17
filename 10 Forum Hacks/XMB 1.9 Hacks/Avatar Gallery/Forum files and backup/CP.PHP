<?php
/* $Id: cp.php,v 1.2 2004/04/15 15:16:37 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require "./header.php";

loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','header','footer', 'css');

$navigation = "&raquo; $lang[textcp]";

eval('$css = "'.template('css').'";');
eval("\$header = \"".template("header")."\";");
echo $header;

if($self['status'] != "Super Administrator") {
    eval("\$notadmin = \"".template("error_nologinsession")."\";");
    echo $notadmin;
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    exit();
}

$ip = $onlineip;

$time = time();
$string = "$xmbuser|#||#|$ip|#||#|$time|#||#|$_SERVER[REQUEST_URI]\n";
@chmod('./cplogfile.php', 0777);
$stream = @fopen('./cplogfile.php','a');
if(!$stream){
    error('Wrong File permissions set. Please CHMOD the <i>cplogfile.php</i> to <i>777</i>', false, '', '', false, false, false, false);
}
@flock($stream, 2);
@fwrite($stream, $string);
@fclose($stream);
@chmod('./cplogfile.php', 0766);
?>

<!-- Admin Panel design kindly donated by John Briggs -->
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr>
<td bgcolor="<?=$bordercolor?>"><table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="category">
<td colspan="30" align="center" <?=$catbgcode?>><b><font color="<?=$cattext?>"><?=$lang['textcp']?></font></b></td>
</tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td colspan="30" align="center"><br /><table cellspacing="0" cellpadding="0" border="0" width="98%" align="center">
<tr>
<td bgcolor="<?=$bordercolor?>">
<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['general']?></font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['textforums']?></font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['textmembers']?></font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['look_feel']?></font></b></td>
</tr>

<tr>
<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp2.php?action=attachments"><?=$lang['textattachman']?></a><br />
&raquo; <a href="cp2.php?action=censor"><?=$lang['textcensors']?></a><br />
&raquo; <a href="cp2.php?action=newsletter"><?=$lang['textnewsletter']?></a><br />
&raquo; <a href="cp.php?action=search"><?=$lang['cpsearch']?></a><br />
&raquo; <a href="cp.php?action=settings"><?=$lang['textsettings']?></a><br />
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp.php?action=forum"><?=$lang['textforums']?></a><br />
&raquo; <a href="cp.php?action=mods"><?=$lang['textmods']?></a><br />
&raquo; <a href="cp2.php?action=prune"><?=$lang['textprune']?></a><br />
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp.php?action=ipban"><?=$lang['textipban']?></a><br />
&raquo; <a href="cp.php?action=members"><?=$lang['textmembers']?></a><br />
&raquo; <a href="cp2.php?action=ranks"><?=$lang['textuserranks']?></a><br />
&raquo; <a href="cp2.php?action=restrictions"><?=$lang['cprestricted']?></a><br />
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp2.php?action=smilies"><?=$lang['smilies']?></a><br />
&raquo; <a href="cp2.php?action=templates"><?=$lang['templates']?></a><br />
&raquo; <a href="cp2.php?action=themes"><?=$lang['themes']?></a><br />
</td>
</tr>

<tr>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['logs']?></font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['tools']?></font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['mysql_tools']?></font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['backup_tools']?></font></b></td>
</tr>

<tr>
<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp2.php?action=cplog"><?=$lang['cplog']?></a><br />
&raquo; <a href="cp2.php?action=modlog"><?=$lang['modlogs']?></a><br />
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="tools.php?action=fixftotals"><?=$lang['textfixposts']?></a><br />
&raquo; <a href="tools.php?action=fixlastposts"><?=$lang['textfixlastposts']?></a><br />
&raquo; <a href="tools.php?action=fixmposts"><?=$lang['textfixmemposts']?></a><br />
&raquo; <a href="tools.php?action=fixttotals"><?=$lang['textfixthread']?></a><br />
&raquo; <a href="tools.php?action=updatemoods"><?=$lang['textfixmoods']?></a><br />
&raquo; <a href="tools.php?action=fixorphanedthreads"><?=$lang['textfixothreads']?></a><br />
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="tools.php?action=analyzetables"><?=$lang['analyze']?></a><br />
&raquo; <a href="tools.php?action=whosonlinedump"><?=$lang['cpwodump']?></a><br />
&raquo; <a href="cp.php?action=upgrade"><?=$lang['raw_mysql']?></a><br />
&raquo; <a href="tools.php?action=optimizetables"><?=$lang['optimize']?></a><br />
&raquo; <a href="tools.php?action=repairtables"><?=$lang['repair']?></a><br />
&raquo; <a href="tools.php?action=u2udump"><?=$lang['u2udump']?></a><br />
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp2.php?action=dbdump"><?=$lang['db_backup']?></a><br />
&raquo; <a href="dump_attachments.php?action=dump_attachments"><?=$lang['dump_attachments']?></a><br />
&raquo; <a href="dump_attachments.php?action=restore_attachments"><?=$lang['restore_attachments']?></a><br />
</td>
</tr>
</table></td>
</tr>
</table>
<br />

<?
if(!isset($action)){
    $action = '';
}

if($action == "settings") {
    if(!$settingsubmit) {

        $langfileselect = "<select name=\"langfilenew\">\n";

        $dir = opendir("lang");
        while ($thafile = readdir($dir)) {
            if (is_file("lang/$thafile")) {
                $thafile = str_replace('.lang.php', '', $thafile);
                if ($thafile == $SETTINGS['langfile']) {
                    $langfileselect .= "<option value=\"$thafile\" selected=\"selected\">$thafile</option>\n";
                } else {
                    $langfileselect .= "<option value=\"$thafile\">$thafile</option>\n";
                }
            }
        }

        $langfileselect .= "</select>";

        $themelist = "<select name=\"themenew\">\n";
        $query = $db->query("SELECT themeid, name FROM $table_themes");
        while($themeinfo = $db->fetch_array($query)) {
            if($themeinfo['themeid'] == $SETTINGS['theme']) {
                $themelist .= "<option value=\"$themeinfo[themeid]\" selected=\"selected\">$themeinfo[name]</option>\n";
            } else {
                $themelist .= "<option value=\"$themeinfo[themeid]\">$themeinfo[name]</option>\n";
            }
        }
        $themelist  .= "</select>";


        if($SETTINGS['bbstatus'] == "on") {
            $onselect = 'selected="selected"';
        } else {
            $offselect = 'selected="selected"';
        }

        if($SETTINGS['whosonlinestatus'] == "on") {
            $whosonlineon = 'selected="selected"';
        } else {
            $whosonlineoff = 'selected="selected"';
        }

        if($SETTINGS['regstatus'] == "on") {
            $regon = 'selected="selected"';
        } else {
            $regoff = 'selected="selected"';
        }

        if($SETTINGS['regviewonly'] == "on") {
            $regonlyon = 'selected="selected"';
        } else {
            $regonlyoff = 'selected="selected"';
        }

        if($SETTINGS['catsonly'] == "on") {
            $catsonlyon = 'selected="selected"';
        } else {
            $catsonlyoff = 'selected="selected"';
        }

        if($SETTINGS['hideprivate'] == "on") {
            $hideon = 'selected="selected"';
        } else {
            $hideoff = 'selected="selected"';
        }

        if($SETTINGS['emailcheck'] == "on") {
            $echeckon = 'selected="selected"';
        } else {
            $echeckoff = 'selected="selected"';
        }

        if($SETTINGS['bbrules'] == "on") {
            $ruleson = 'selected="selected"';
        } else {
            $rulesoff = 'selected="selected"';
        }

        if($SETTINGS['searchstatus'] == "on") {
            $searchon = 'selected="selected"';
        } else {
            $searchoff = 'selected="selected"';
        }

        if($SETTINGS['faqstatus'] == "on") {
            $faqon = 'selected="selected"';
        } else {
            $faqoff = 'selected="selected"';
        }

        if($SETTINGS['memliststatus'] == "on") {
            $memliston = 'selected="selected"';
        } else {
            $memlistoff = 'selected="selected"';
        }

        if($SETTINGS['todaysposts'] == "on") {
            $todayon = 'selected="selected"';
        } else {
            $todayoff = 'selected="selected"';
        }

        if($SETTINGS['stats'] == "on") {
            $statson = 'selected="selected"';
        } else {
            $statsoff = 'selected="selected"';
        }


        if($SETTINGS['avastatus'] == "on") {
            $avataron = 'selected="selected"';
        } elseif($avastatus == "list") {
            $avatarlist = 'selected="selected"';
        } else {
            $avataroff = 'selected="selected"';
        }

        if($SETTINGS['gzipcompress'] == "on") {
            $gzipcompresson = 'selected="selected"';
        } else {
            $gzipcompressoff = 'selected="selected"';
        }

        if($SETTINGS['coppa'] == "on") {
            $coppaon = 'selected="selected"';
        } else {
            $coppaoff = 'selected="selected"';
        }

        if($SETTINGS['timeformat'] == "24") {
            $check24 = "checked=\"checked\"";
        } else {
            $check12 = "checked=\"checked\"";
        }

        if($SETTINGS['sigbbcode'] == "on") {
            $sigbbcodeon = 'selected="selected"';
        } else {
            $sigbbcodeoff = 'selected="selected"';
        }

        if($SETTINGS['sightml'] == "on") {
            $sightmlon = 'selected="selected"';
        } else {
            $sightmloff = 'selected="selected"';
        }

        if($SETTINGS['reportpost'] == "on") {
            $reportposton = 'selected="selected"';
        } else {
            $reportpostoff = 'selected="selected"';
        }

        if($SETTINGS['bbinsert'] != "on") {
            $bbinsertoff = 'selected="selected"';
        } else {
            $bbinserton = 'selected="selected"';
        }

        if($SETTINGS['smileyinsert'] != "on") {
            $smileyinsertoff = 'selected="selected"';
        } else {
            $smileyinserton = 'selected="selected"';
        }

        if($SETTINGS['doublee'] == "on") {
            $doubleeon = 'selected="selected"';
        } else {
            $doubleeoff = 'selected="selected"';
        }

        if($SETTINGS['editedby'] == "on") {
            $editedbyon = 'selected="selected"';
        } else {
            $editedbyoff = 'selected="selected"';
        }

        if($SETTINGS['dotfolders'] == "on") {
            $dotfolderson = 'selected="selected"';
        } else {
            $dotfoldersoff = 'selected="selected"';
        }

        if($SETTINGS['attachimgpost'] == "on") {
            $attachimgposton = 'selected="selected"';
        } else {
            $attachimgpostoff = 'selected="selected"';
        }

        if($SETTINGS['tickerstatus'] == "on") {
            $tickerstatusonon = 'selected="selected"';
        } else {
            $tickerstatusoff = 'selected="selected"';
        }

        if($SETTINGS['space_cats'] == "on") {
            $spacecatson = 'selected="selected"';
        } else {
            $spacecatsoff = 'selected="selected"';
        }

        if($SETTINGS['allowrankedit'] == "on") {
            $allowrankediton = 'selected="selected"';
        } else {
            $allowrankeditoff = 'selected="selected"';
        }

        if(!defined('PSPELL_FAST')){
            $spell_off_reason = $lang['pspell_needed'];
            $SETTINGS['spellcheck'] = 'off';
        }

        if($SETTINGS['spellcheck'] == "on") {
            $spellcheckson = 'selected="selected"';
        } else {
            $spellcheckoff = 'selected="selected"';
        }

        $footer_options = explode('-', $SETTINGS['footer_options']);
        if(in_array('serverload', $footer_options)){
            $sel_serverload = true;
        } else {
            $sel_serverload = false;
        }
        if(in_array('queries', $footer_options)){
            $sel_queries = true;
        } else {
            $sel_queries = false;
        }
        if(in_array('phpsql', $footer_options)){
            $sel_phpsql = true;
        } else {
            $sel_phpsql = false;
        }
        if(in_array('loadtimes', $footer_options)){
            $sel_loadtimes = true;
        } else {
            $sel_loadtimes = false;
        }

        if($avatarlist){
            $avchecked['list']  = true;
        }elseif($avataroff){
            $avchecked['off']   = true;
        }else{
            $avchecked['on']    = true;
        }

		$avatars_on = $avatars_off = '';
		if ($SETTINGS['avatars'] == "on") {
			$avatars_on = " selected";
		} else {
			$avatars_off = " selected";
		}

        $values = array('serverload', 'queries', 'phpsql', 'loadtimes');
        $names = array('Enable Server Load', 'Enable Queries', 'Enable PHP/SQL Calculation', 'Enable Page-loadtimes');
        $checked = array($sel_serverload, $sel_queries, $sel_phpsql, $sel_loadtimes);

        $SETTINGS['bboffreason'] = stripslashes($SETTINGS['bboffreason']);
        $SETTINGS['bbrulestxt'] = stripslashes($SETTINGS['bbrulestxt']);
        $SETTINGS['tickercontents'] = stripslashes($SETTINGS['tickercontents']);
		$SETTINGS['subjectprefixes'] = stripslashes($SETTINGS['subjectprefixes']);
        $max_avatar_sizes = explode('x', $SETTINGS['max_avatar_size']);
        $lang['spell_checker'] .= $spell_off_reason;

        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp.php?action=settings">
        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
        <tr>
        <td bgcolor="<?=$bordercolor?>"><table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
        <tr class="category">
        <td colspan="2"><b><font color="<?=$cattext?>">&raquo; <?=$lang['admin_main_settings1']?></font></b></td>
        </tr>

        <?
        printsetting2($lang['textsitename'], "sitenamenew", $SETTINGS['sitename'], "50");
        printsetting2($lang['bbname'], "bbnamenew", $SETTINGS['bbname'], "50");
        printsetting2($lang['textsiteurl'], "siteurlnew", $SETTINGS['siteurl'], "50");
        printsetting2($lang['textboardurl'], "boardurlnew", $SETTINGS['boardurl'], "50");
        printsetting2($lang['adminemail'], "adminemailnew", $SETTINGS['adminemail'], "50");
        printsetting1($lang['textbbrules'], 'bbrulesnew',   $ruleson,   $rulesoff,  $langfile);
        ?>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang['textbbrulestxt']?></td>
        <td class="tablerow" bgcolor="<?=$altbg2?>"><textarea rows="5" name="bbrulestxtnew" cols="50"><?=$SETTINGS['bbrulestxt']?></textarea></td>
        </tr>
        <?php
        printsetting1($lang['textbstatus'], "bbstatusnew", $onselect, $offselect, $langfile);
        ?>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang['textbboffreason']?></td>
        <td class="tablerow" bgcolor="<?=$altbg2?>"><textarea rows="5" name="bboffreasonnew" cols="50"><?=$SETTINGS['bboffreason']?></textarea></td>
        </tr>
        <?php
        printsetting1($lang['gzipcompression'],     'gzipcompressnew',  $gzipcompresson,$gzipcompressoff,   $langfile);
        ?>

        <tr>
        <td class="tablerow" bgcolor="<?=$altbg2?>" colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td bgcolor="<?=$altbg1?>" colspan="2" class="category"><b><font color="<?=$cattext?>">&raquo; <?=$lang['admin_main_settings2']?></font></b></td>
        </tr>

        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang['textlanguage']?></td>
        <td class="tablerow" bgcolor="<?=$altbg2?>"><?=$langfileselect?></td>
        </tr>

        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang['texttheme']?></td>
        <td class="tablerow" bgcolor="<?=$altbg2?>"><?=$themelist?></td>
        </tr>

        <?php
        printsetting2($lang['textppp'], "postperpagenew", $SETTINGS['postperpage'], 3);
        printsetting2($lang['texttpp'], "topicperpagenew", $SETTINGS['topicperpage'], 3);
		printsetting2($lang['topicsubjectprefixes'], "subjectprefixesnew", $SETTINGS['subjectprefixes'], "50");
        printsetting2($lang['textmpp'], "memberperpagenew", $SETTINGS['memberperpage'], 3);
        ?>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang['texttimeformat']?></td>
        <td class="tablerow" bgcolor="<?=$altbg2?>"><input type="radio" value="24" name="timeformatnew" <?=$check24?>><?=$lang['text24hour']?> <input type="radio" value="12" name="timeformatnew" <?=$check12?>><?=$lang['text12hour']?></td>
        </tr>
        <?php
        printsetting2($lang['dateformat'], "dateformatnew", $SETTINGS['dateformat'], "20");
        ?>

        <tr>
        <td class="tablerow" bgcolor="<?=$altbg2?>" colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td bgcolor="<?=$altbg1?>" colspan="2" class="category"><b><font color="<?=$cattext?>">&raquo; <?=$lang['admin_main_settings3']?></font></b></td>
        </tr>

        <?php
        printsetting1($lang['textsearchstatus'],    'searchstatusnew',  $searchon,  $searchoff,     $langfile);
        printsetting1($lang['textfaqstatus'],       'faqstatusnew',     $faqon,     $faqoff,        $langfile);
        printsetting1($lang['texttodaystatus'],     'todaystatusnew',   $todayon,   $todayoff,      $langfile);
        printsetting1($lang['textstatsstatus'],     'statsstatusnew',   $statson,   $statsoff,      $langfile);
        printsetting1($lang['textmemliststatus'],   'memliststatusnew', $memliston, $memlistoff,        $langfile);
        printsetting1($lang['spell_checker'],       'spellchecknew',    $spellcheckon,  $spellcheckoff,     $langfile);
        printsetting1($lang['coppastatus'],         'coppanew',     $coppaon,   $coppaoff,      $langfile);
        printsetting1($lang['reportpoststatus'],    'reportpostnew',    $reportposton,  $reportpostoff,     $langfile);
        ?>

        <tr>
        <td class="tablerow" bgcolor="<?=$altbg2?>" colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td bgcolor="<?=$altbg1?>" colspan="2" class="category"><b><font color="<?=$cattext?>">&raquo; <?=$lang['admin_main_settings4']?></font></b></td>
        </tr>

        <?php
        printsetting1($lang['space_cats'],  'space_catsnew',$spacecatson,   $spacecatsoff,  $langfile);
        printsetting1($lang['allowrankedit'],  'allowrankeditnew',   $allowrankediton,   $allowrankeditoff,  $langfile);
        printsetting1($lang['textcatsonly'],    'catsonlynew',  $catsonlyon,    $catsonlyoff,   $langfile);
        printsetting1($lang['whosonline_on'],   'whos_on',  $whosonlineon,  $whosonlineoff, $langfile);
        printsetting2($lang['smtotal'], "smtotalnew", $SETTINGS['smtotal'], 5);
        printsetting2($lang['smcols'], "smcolsnew", $SETTINGS['smcols'], 5);
        printsetting1($lang['dotfolders'], "dotfoldersnew", $dotfolderson, $dotfoldersoff, $langfile);
        printsetting1($lang['editedby'], "editedbynew", $editedbyon, $editedbyoff, $langfile);
        printsetting1($lang['attachimginpost'], "attachimgpostnew", $attachimgposton, $attachimgpostoff, $langfile);
        ?>

        <tr>
        <td class="tablerow" bgcolor="<?=$altbg2?>" colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td bgcolor="<?=$altbg1?>" colspan="2" class="category"><b><font color="<?=$cattext?>">&raquo; <?=$lang['admin_main_settings5']?></font></b></td>
        </tr>

        <?php
        printsetting1($lang['reg_on'],      'reg_on',   $regon,     $regoff,    $langfile);
        printsetting1($lang['textreggedonly'],  'regviewnew',   $regonlyon, $regonlyoff,    $langfile);
        printsetting1($lang['texthidepriv'],    'hidepriv', $hideon,    $hideoff,   $langfile);
        printsetting1($lang['emailverify'], 'emailchecknew',$echeckon,  $echeckoff, $langfile);
        printsetting2($lang['textflood'], "floodctrlnew", $SETTINGS['floodctrl'], 3);
        printsetting2($lang['u2uquota'], "u2uquotanew", $SETTINGS['u2uquota'], 3);
        printsetting3($lang['textavastatus'], 'avastatusnew', array($lang['texton'], $lang['textlist'], $lang['textoff']), array('on', 'list', 'off'), $avchecked, false);
	  printsetting1($lang['avatargallerystatus'], "avatarsnew", $avatars_on, $avatars_off, $langfile);
	  printsetting2($lang['avatarsperpage'], "avatarsperpagenew", $SETTINGS['avatars_perpage'], "2");
	  printsetting2($lang['avatarsperrow'], "avatarsperrownew", $SETTINGS['avatars_perrow'], "2");
	  printsetting1($lang['doublee'],         'doubleenew',       $doubleeon, $doubleeoff,        $langfile);
        ?>

        <tr>
        <td class="tablerow" bgcolor="<?=$altbg2?>" colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td bgcolor="<?=$altbg1?>" colspan="2" class="category"><b><font color="<?=$cattext?>">&raquo; <?=$lang['admin_main_settings6']?></font></b></td>
        </tr>

        <?
        printsetting2($lang['texthottopic'], "hottopicnew", $SETTINGS['hottopic'], 3);
        printsetting1($lang['bbinsert'],        'bbinsertnew',      $bbinserton,    $bbinsertoff,       $langfile);
        printsetting1($lang['smileyinsert'],        'smileyinsertnew',  $smileyinserton,$smileyinsertoff,   $langfile);
        printsetting3($lang['footer_options'],      'new_footer_options',   $names,     $values,        $checked );
        printsetting2($lang['addtime'], "addtimenew", $SETTINGS['addtime'], 3);
        printsetting1($lang['sigbbcode'],       'sigbbcodenew',     $sigbbcodeon,   $sigbbcodeoff,      $langfile);
        printsetting1($lang['sightml'],         'sightmlnew',       $sightmlon, $sightmloff,        $langfile);
        printsetting2($lang['max_avatar_size_w'], "max_avatar_size_w_new", $max_avatar_sizes[0], 4);
        printsetting2($lang['max_avatar_size_h'], "max_avatar_size_h_new", $max_avatar_sizes[1], 4);
        printsetting1($lang['what_tickerstatus'], "tickerstatusnew", $tickerstatuson, $tickerstatusoff, $langfile);
        printsetting2($lang['what_tickerdelay'], "tickerdelaynew", $SETTINGS['tickerdelay'], "5");
        ?>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang['tickercontents']?></td>
        <td class="tablerow" bgcolor="<?=$altbg2?>"><textarea rows="5" name="tickercontentsnew" cols="50"><?=$SETTINGS['tickercontents']?></textarea></td>
        </tr>

        </table>
        </td></tr></table><br />
        <center><input class="submit" type="submit" name="settingsubmit" value="<?=$lang['textsubmitchanges']?>" /></center>
        </form></td>
        </tr>
        </td>
        </tr>
        </table></td>
        </tr>

        <?
    }else{
        $bbrulestxtnew = addslashes($bbrulestxtnew);
        $bboffreasonnew = addslashes($bboffreasonnew);
        $tickercontentsnew = addslashes($tickercontentsnew);
		$subjectprefixesnew = addslashes($subjectprefixesnew);

        $max_avatar_size_w_new = (int) $max_avatar_size_w_new;
        $max_avatar_size_h_new = (int) $max_avatar_size_h_new;

        if(!empty($new_footer_options)){
                $footer_options = implode('-', $new_footer_options);
        }else{
                $footer_options = '';
        }

        $space_catsnew = ($space_catsnew == 'on') ? 'on' : 'off';
        $allowrankeditnew = ($allowrankeditnew == 'on') ? 'on' : 'off';
        $spellchecknew = ($spellchecknew == 'on' && defined('PSPELL_FAST')) ? 'on' : 'off';

        $db->query("UPDATE $table_settings SET subjectprefixes = '$subjectprefixesnew', langfile='$langfilenew', bbname='$bbnamenew', postperpage='$postperpagenew', topicperpage='$topicperpagenew', hottopic='$hottopicnew', theme='$themenew', bbstatus='$bbstatusnew', whosonlinestatus='$whos_on', regstatus='$reg_on', bboffreason='$bboffreasonnew', regviewonly='$regviewnew', floodctrl='$floodctrlnew', memberperpage='$memberperpagenew', catsonly='$catsonlynew', hideprivate='$hidepriv', emailcheck='$emailchecknew', bbrules='$bbrulesnew', bbrulestxt='$bbrulestxtnew', searchstatus='$searchstatusnew', faqstatus='$faqstatusnew', memliststatus='$memliststatusnew', sitename='$sitenamenew', siteurl='$siteurlnew', avastatus='$avastatusnew', u2uquota='$u2uquotanew', gzipcompress='$gzipcompressnew', boardurl='$boardurlnew', coppa='$coppanew', timeformat='$timeformatnew', adminemail='$adminemailnew', dateformat='$dateformatnew', sigbbcode='$sigbbcodenew', sightml='$sightmlnew', reportpost='$reportpostnew', bbinsert='$bbinsertnew', smileyinsert='$smileyinsertnew', doublee='$doubleenew', smtotal='$smtotalnew', smcols='$smcolsnew', editedby='$editedbynew', dotfolders='$dotfoldersnew', attachimgpost='$attachimgpostnew', tickerstatus='$tickerstatusnew', tickercontents='$tickercontentsnew', tickerdelay='$tickerdelaynew', addtime='$addtimenew', todaysposts='$todaystatusnew', stats='$statsstatusnew', max_avatar_size='${max_avatar_size_w_new}x${max_avatar_size_h_new}', footer_options='$footer_options', space_cats='$space_catsnew', spellcheck='$spellchecknew', allowrankedit='$allowrankeditnew', avatars='$avatarsnew', avatars_perpage='$avatarsperpagenew', avatars_perrow='$avatarsperrownew'");

        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[textsettingsupdate]</td></tr>";
    }
}



if($action == "forum") {
    if(!$forumsubmit && !$fdetails) {
        $groups = array();
        $forums = array();
        $forums[0] = array();
        $forumlist = array();
        $subs = array();
        $i = 0;
        $query = $db->query("SELECT * FROM $table_forums ORDER BY displayorder ASC");
        while($array = $db->fetch_array($query)){
            if($array['type'] == 'group'){
                $groups[$i]['fid'] = $array['fid'];
                $groups[$i]['name'] = $array['name'];
                $groups[$i]['displayorder'] = $array['displayorder'];
                $groups[$i]['status'] = $array['status'];
                $groups[$i]['fup'] = $array['fup'];

            }elseif($array['type'] == 'forum'){
                $id = (empty($array['fup'])) ? 0 : $array['fup'];
                $forums[$id][$i]['fid'] = $array['fid'];
                $forums[$id][$i]['name'] = $array['name'];
                $forums[$id][$i]['displayorder'] = $array['displayorder'];
                $forums[$id][$i]['status'] = $array['status'];
                $forums[$id][$i]['fup'] = $array['fup'];
                $forumlist[$i]['fid'] = $array['fid'];
                $forumlist[$i]['name'] = $array['name'];

            }elseif($array['type'] == 'sub'){
                $subs["$array[fup]"][$i]['fid'] = $array['fid'];
                $subs["$array[fup]"][$i]['name'] = $array['name'];
                $subs["$array[fup]"][$i]['displayorder'] = $array['displayorder'];
                $subs["$array[fup]"][$i]['status'] = $array['status'];
                $subs["$array[fup]"][$i]['fup'] = $array['fup'];
            }
            $i++;
        }
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td>
        <br />
        <form method="post" action="cp.php?action=forum">
        <table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header"><?=$lang['textforumopts']?></td>
        </tr>

        <?

        foreach($forums['0'] as $forum){

            if($forum['status'] == "on") {
                $on = "selected=\"selected\"";
            } else {
                $off = "selected=\"selected\"";
            }

            ?>

            <tr bgcolor="<?=$altbg2?>" class="tablerow">
            <td class="smalltxt"><input type="checkbox" name="delete<?=$forum['fid']?>" value="<?=$forum['fid']?>" />
            &nbsp;<input type="text" name="name<?=$forum['fid']?>" value="<?=stripslashes($forum['name'])?>" />
            &nbsp; <?=$lang['textorder']?> <input type="text" name="displayorder<?=$forum['fid']?>" size="2" value="<?=$forum['displayorder']?>" />
            &nbsp; <select name="status<?=$forum['fid']?>">
            <option value="on" <?=$on?>><?=$lang['texton']?></option><option value="off" <?=$off?>><?=$lang['textoff']?></option></select>
            &nbsp; <select name="moveto<?=$forum['fid']?>"><option value="" selected="selected">-<?=$lang['textnone']?>-</option>

            <?
            foreach($groups as $moveforum) {
                echo "<option value=\"$moveforum[fid]\">".stripslashes($moveforum[name])."</option>";
            }
            ?>
            </select>
            <a href="cp.php?action=forum&amp;fdetails=<?=$forum['fid']?>"><?=$lang[textmoreopts]?></a></td>
            </tr>

            <?
if (array_key_exists("$forum[fid]", $subs)) {
            foreach($subs["$forum[fid]"] as $subforum) {
                if($subforum['status'] == "on") {
                    $on = "selected=\"selected\"";
                } else {
                    $off = "selected=\"selected\"";
                }
                ?>

                <tr bgcolor="<?=$altbg2?>" class="tablerow">
                <td class="smalltxt"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="delete<?=$subforum[fid]?>" value="<?=$subforum[fid]?>" />
                &nbsp;<input type="text" name="name<?=$subforum[fid]?>" value="<?=stripslashes($subforum[name])?>" />
                &nbsp; <?=$lang[textorder]?> <input type="text" name="displayorder<?=$subforum[fid]?>" size="2" value="<?=$subforum[displayorder]?>" />
                &nbsp; <select name="status<?=$subforum[fid]?>">
                <option value="on" <?=$on?>><?=$lang[texton]?></option><option value="off" <?=$off?>><?=$lang[textoff]?></option></select>
                &nbsp; <select name="moveto<?=$subforum[fid]?>">

                <?
                foreach($forumlist as $moveforum) {
                    if($subforum['fup'] == $moveforum['fid']) {
                        echo '<option value="'.$moveforum['fid'].'" selected="selected">'.stripslashes($moveforum['name']).'</option>';
                    } else {
                        echo '<option value="'.$moveforum['fid'].'">'.stripslashes($moveforum['name']).'</option>';
                    }
                }

                ?>
                </select>
                <a href="cp.php?action=forum&amp;fdetails=<?=$subforum['fid']?>"><?=$lang['textmoreopts']?></a></td>
                </tr>

                <?
                $on = "";
                $off = "";
            }
}
            $on = "";
            $off = "";
        }


        foreach($groups as $group) {
            if($group['status'] == "on") {
                $on = "selected=\"selected\"";
            } else {
                $off = "selected=\"selected\"";
            }

            ?>

            <tr bgcolor="<?=$altbg2?>" class="tablerow">
                <td>&nbsp;</td>
            </tr>
            <tr bgcolor="<?=$altbg1?>" class="tablerow">
            <td class="smalltxt"><input type="checkbox" name="delete<?=$group['fid']?>" value="<?=$group['fid']?>" />
             <input type="text" name="name<?=$group['fid']?>" value="<?=stripslashes($group['name'])?>" />
            &nbsp; <?=$lang['textorder']?> <input type="text" name="displayorder<?=$group['fid']?>" size="2" value="<?=$group['displayorder']?>" />
            &nbsp; <select name="status<?=$group['fid']?>">
            <option value="on" <?=$on?>><?=$lang['texton']?></option><option value="off" <?=$off?>><?=$lang['textoff']?></option></select>
            </td>
            </tr>

            <?
if (array_key_exists("$group[fid]", $forums)) {
            foreach($forums["$group[fid]"] as $forum) {

                if($forum['status'] == "on") {
                    $on = "selected=\"selected\"";
                } else {
                    $off = "selected=\"selected\"";
                }

                ?>

                <tr bgcolor="<?=$altbg2?>" class="tablerow">
                <td class="smalltxt"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="delete<?=$forum['fid']?>" value="<?=$forum['fid']?>" />
                &nbsp;<input type="text" name="name<?=$forum['fid']?>" value="<?=stripslashes($forum['name'])?>" />
                &nbsp; <?=$lang['textorder']?> <input type="text" name="displayorder<?=$forum['fid']?>" size="2" value="<?=$forum['displayorder']?>" />
                &nbsp; <select name="status<?=$forum['fid']?>">
                <option value="on" <?=$on?>><?=$lang['texton']?></option><option value="off" <?=$off?>><?=$lang['textoff']?></option></select>
                &nbsp; <select name="moveto<?=$forum['fid']?>"><option value="">-<?=$lang['textnone']?>-</option>

                <?
                foreach($groups as $moveforum) {
                    if($moveforum['fid'] == $forum['fup']) {
                        $curgroup = "selected=\"selected\"";
                    } else {
                        $curgroup = "";
                    }
                echo "<option value=\"$moveforum[fid]\" $curgroup>".stripslashes($moveforum['name'])."</option>";
                }
                ?>
                </select>
                <a href="cp.php?action=forum&amp;fdetails=<?=$forum['fid']?>"><?=$lang['textmoreopts']?></a></td>
                </tr>

                <?
if (array_key_exists($forum['fid'], $subs)) {
                foreach($subs["$forum[fid]"] as $forum) {
                    if($forum['status'] == "on") {
                        $on = "selected=\"selected\"";
                    } else {
                        $off = "selected=\"selected\"";
                    }
                    ?>

                    <tr bgcolor="<?=$altbg2?>" class="tablerow">
                    <td class="smalltxt"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input type="checkbox" name="delete<?=$forum['fid']?>" value="<?=$forum['fid']?>" />
                    &nbsp;<input type="text" name="name<?=$forum['fid']?>" value="<?=stripslashes($forum['name'])?>" />
                    &nbsp; <?=$lang['textorder']?> <input type="text" name="displayorder<?=$forum['fid']?>" size="2" value="<?=$forum['displayorder']?>" />
                    &nbsp; <select name="status<?=$forum['fid']?>">
                    <option value="on" <?=$on?>><?=$lang['texton']?></option><option value="off" <?=$off?>><?=$lang['textoff']?></option></select>
                    &nbsp; <select name="moveto<?=$forum['fid']?>">

                        <?
                    foreach($forumlist as $moveforum) {
                        if($moveforum['fid'] == $forum['fup']) {
                            echo "<option value=\"$moveforum[fid]\" selected=\"selected\">".stripslashes($moveforum[name])."</option>";
                        } else {
                            echo "<option value=\"$moveforum[fid]\">".stripslashes($moveforum[name])."</option>";
                        }
                    }

                    ?>
                    </select>
                    <a href="cp.php?action=forum&amp;fdetails=<?=$forum[fid]?>"><?=$lang[textmoreopts]?></a></td>
                    </tr>

                    <?
                    $on = "";
                    $off = "";
                }
    }
                $on = "";
                $off = "";
            }
    }
            $on = "";
            $off = "";
        }
        ?>

        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td>&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td class="smalltxt"><input type="text" name="newgname" value="<?=$lang['textnewgroup']?>" />
        &nbsp; <?=$lang['textorder']?> <input type="text" name="newgorder" size="2" />
        &nbsp; <select name="newgstatus">
        <option value="on"><?=$lang['texton']?></option><option value="off"><?=$lang['textoff']?></option></select></td>
        </tr>

        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td class="smalltxt"><input type="text" name="newfname" value="<?=$lang['textnewforum']?>" />
        &nbsp; <?=$lang['textorder']?> <input type="text" name="newforder" size="2" />
        &nbsp; <select name="newfstatus">
        <option value="on"><?=$lang['texton']?></option><option value="off"><?=$lang['textoff']?></option></select>
        &nbsp; <select name="newffup"><option value="" selected="selected">-<?=$lang['textnone']?>-</option>

        <?
        foreach($groups as $group) {
            echo "<option value=\"$group[fid]\">".stripslashes($group[name])."</option>";
        }
        ?>

        </select>
        </td></tr>

        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td class="smalltxt"><input type="text" name="newsubname" value="<?=$lang['textnewsubf']?>" />
        &nbsp; <?=$lang['textorder']?> <input type="text" name="newsuborder" size="2" />
        &nbsp; <select name="newsubstatus"><option value="on"><?=$lang['texton']?></option><option value="off"><?=$lang['textoff']?></option></select>
        &nbsp; <select name="newsubfup">

        <?
        foreach($forumlist as $group) {
            echo "<option value=\"$group[fid]\">".stripslashes($group[name])."</option>";
        }
        ?>

        </select>
        </td></tr>

        </table>
        </td></tr></table><br />
        <center><input type="submit" name="forumsubmit" value="<?=$lang[textsubmitchanges]?>" class="submit"/></center>
        </form>

        </td>
        </tr>

        <?
    }elseif($fdetails && !$forumsubmit) {

        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp.php?action=forum&amp;fdetails=<?=$fdetails?>">
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header" colspan="2"><?=$lang[textforumopts]?></td>
        </tr>

        <?
        $queryg = $db->query("SELECT * FROM $table_forums WHERE fid='$fdetails'");
        $forum = $db->fetch_array($queryg);

        $themelist = "<select name=\"themeforumnew\"><option value=\"\">$lang[textusedefault]</option>\n";
        $querytheme = $db->query("SELECT themeid, name FROM $table_themes");
        while($theme = $db->fetch_array($querytheme)) {
            if($theme['themeid'] == $forum['theme']) {
                $themelist .= "<option value=\"$theme[themeid]\" selected>$theme[name]\n";
            } else {
                $themelist .= "<option value=\"$theme[themeid]\">$theme[name]\n";
            }
        }
        $themelist  .= "</select>";


        if($forum['private'] == "staff") {
            $checked1 = "checked";
        } else {
            $checked1 = "";
        }

        if($forum['allowhtml'] == "yes" || $forum['allowhtml'] == 'on') {
            $checked2 = "checked";
        } else {
            $checked2 = "";
        }

        if($forum['allowsmilies'] == "yes" || $forum['allowsmilies'] == "on") {
            $checked3 = "checked";
        } else {
            $checked3 = "";
        }

        if($forum['allowbbcode'] == "yes" || $forum['allowbbcode'] == "on") {
            $checked4 = "checked";
        } else {
            $checked4 = "";
        }

        if($forum['allowimgcode'] == "yes" || $forum['allowimgcode'] == "on") {
            $checked5 = "checked";
        } else {
            $checked5 = "";
        }

        if($forum['attachstatus'] == "on" || $forum['attachstatus'] == "yes") {
            $checked6 = "checked";
        } else {
            $checked6 = "";
        }

        if($forum['pollstatus'] == "on" || $forum['pollstatus'] == "yes") {
            $checked7 = "checked";
        } else {
            $checked7 = "";
        }
        if($forum['guestposting'] == "on" || $forum['guestposting'] == "yes") {
            $checked8 = "checked";
        } else {
            $checked8 = "";
        }

        $pperm = explode("|", $forum[postperm]);

        if($pperm[0] == "2") {
            $type12 = "selected";
        } elseif($pperm[0] == "3") {
            $type13 = "selected";
        } elseif($pperm[0] == "4") {
            $type14 = "selected";
        } elseif($pperm[0] == "1") {
            $type11 = "selected";
        }

        if($pperm[1] == "2") {
            $type22 = "selected";
        } elseif($pperm[1] == "3") {
            $type23 = "selected";
        } elseif($pperm[1] == "4") {
            $type24 = "selected";
        } elseif($pperm[1] == "1") {
            $type21 = "selected";
        }

        if($forum['private'] == "2") {
            $type32 = "selected";
        } elseif($forum['private'] == "3") {
            $type33 = "selected";
        } elseif($forum['private'] == "4") {
            $type34 = "selected";
        } elseif($forum['private'] == "1") {
        $type31 = "selected";
        }


        $forum['name'] = stripslashes($forum['name']);
        $forum['description'] = stripslashes($forum['description']);
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow"><?=$lang['textforumname']?></td>
        <td><input type="text" name="namenew" value="<?=$forum['name']?>" /></td>
        </tr>

        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow"><?=$lang['textdesc']?></td>
        <td><textarea rows="4" cols="30" name="descnew"><?=$forum['description']?></textarea></td>
        </tr>

        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow" valign="top"><?=$lang['textallow']?></td>
        <td class="smalltxt"><input type="checkbox" name="allowhtmlnew" value="yes" <?=$checked2?> /><?=$lang['texthtml']?><br />
        <input type="checkbox" name="allowsmiliesnew" value="yes" <?=$checked3?> /><?=$lang['textsmilies']?><br />
        <input type="checkbox" name="allowbbcodenew" value="yes" <?=$checked4?> /><?=$lang['textbbcode']?><br />
        <input type="checkbox" name="allowimgcodenew" value="yes" <?=$checked5?> /><?=$lang['textimgcode']?><br />
        <input type="checkbox" name="attachstatusnew" value="on" <?=$checked6?> /><?=$lang['attachments']?><br />
        <input type="checkbox" name="pollstatusnew" value="on" <?=$checked7?> /><?=$lang['polls']?><br />
        <input type="checkbox" name="guestpostingnew" value="on" <?=$checked8?> /><?=$lang['textanonymousposting']?><br />
        </td>
        </tr>

		        <tr bgcolor="<?=$altbg2?>"><td class="tablerow"><?=$lang['mpfa']?></td><td><input type="text" name="mpfanew" value="<?=$forum['mpfa']?>" /></td></tr>
        <tr bgcolor="<?=$altbg2?>"><td class="tablerow"><?=$lang['mpnp']?></td><td><input type="text" name="mpnpnew" value="<?=$forum['mpnp']?>" /></td></tr>
        <tr bgcolor="<?=$altbg2?>"><td class="tablerow"><?=$lang['mpnt']?></td><td><input type="text" name="mpntnew" value="<?=$forum['mpnt']?>" /></td></tr>
		
        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow"><?=$lang['texttheme']?></td>
        <td><?=$themelist?></td>
        </tr>

        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow"><?=$lang['whopostop1']?></td>
        <td class="tablerow"><select name="postperm1">
        <option value="1" <?=$type11?>><?=$lang['textpermission1']?>
        <option value="2" <?=$type12?>><?=$lang['textpermission2']?>
        <option value="3" <?=$type13?>><?=$lang['textpermission3']?>
        <option value="4" <?=$type14?>><?=$lang['textpermission41']?>
        </select>
        </td>
        </tr>

        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow"><?=$lang['whopostop2']?></td>
        <td class="tablerow"><select name="postperm2">
        <option value="1" <?=$type21?>><?=$lang['textpermission1']?>
        <option value="2" <?=$type22?>><?=$lang['textpermission2']?>
        <option value="3" <?=$type23?>><?=$lang['textpermission3']?>
        <option value="4" <?=$type24?>><?=$lang['textpermission41']?>
        </select>
        </td>
        </tr>

        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow"><?=$lang['whoview']?></td>
        <td class="tablerow"><select name="privatenew">
        <option value="1" <?=$type31?>><?=$lang['textpermission1']?>
        <option value="2" <?=$type32?>><?=$lang['textpermission2']?>
        <option value="3" <?=$type33?>><?=$lang['textpermission3']?>
        <option value="4" <?=$type34?>><?=$lang['textpermission42']?>
        </select>
        </td>
        </tr>

        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow"><?=$lang['textuserlist']?></td>
        <td class="tablerow"><textarea rows="4" cols="30" name="userlistnew"><?=$forum['userlist']?></textarea></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow"><?=$lang['forumpw']?></td>
        <td><input type="text" name="passwordnew" value="<?=$forum['password']?>"></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>">
        <td class="tablerow"><?=$lang['textdeleteques']?></td>
        <td><input type="checkbox" name="delete" value="<?=$forum['fid']?>" /></td>
        </tr>

        </table>
        </td></tr></table><br />
        <center><input type="submit" name="forumsubmit" value="<?=$lang['textsubmitchanges']?>" class="submit"/></center>
        </form>

        </td>
        </tr>
        <?
    }elseif($forumsubmit) {
        if(!$fdetails) {
            $queryforum = $db->query("SELECT fid, type FROM $table_forums WHERE type='forum' OR type='sub'");
            $db->query("DELETE FROM $table_forums WHERE name=''");
            while($forum = $db->fetch_array($queryforum)) {
                $displayorder = "displayorder$forum[fid]";
                $displayorder = "${$displayorder}";
                $name = "name$forum[fid]";
                $name = "${$name}";
                $self[status] = "status$forum[fid]";
                $self[status] = "${$self[status]}";
                $delete = "delete$forum[fid]";
                $delete = "${$delete}";
                $moveto = "moveto$forum[fid]";
                $moveto = "${$moveto}";

                if($delete != "") {
                    $db->query("DELETE FROM $table_forums WHERE (type='forum' OR type='sub') AND fid='$delete'");

                    $querythread = $db->query("SELECT * FROM $table_threads WHERE fid='$delete'");
                    while($thread = $db->fetch_array($querythread)) {
                        $db->query("DELETE FROM $table_threads WHERE tid='$thread[tid]'");
                        $db->query("DELETE FROM $table_favorites WHERE tid='$thread[tid]'");
                        $db->query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$thread[author]'");

                        $querypost = $db->query("SELECT * FROM $table_posts WHERE tid='$thread[tid]'");
                        while($post = $db->fetch_array($querypost)) {
                            $db->query("DELETE FROM $table_posts WHERE pid='$post[pid]'");
                            $db->query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$post[author]'");
                        }
                    }
                }
                $name = addslashes($name);
                $db->query("UPDATE $table_forums SET name='$name', displayorder='$displayorder', status='$self[status]', fup='$moveto' WHERE fid='$forum[fid]'");
            }


            $querygroup = $db->query("SELECT fid FROM $table_forums WHERE type='group'");
            while($group = $db->fetch_array($querygroup)) {
                $name = "name$group[fid]";
                $name = "${$name}";
                $displayorder = "displayorder$group[fid]";
                $displayorder = "${$displayorder}";
                $self[status] = "status$group[fid]";
                $self[status] = "${$self[status]}";
                $delete = "delete$group[fid]";
                $delete = "${$delete}";

                if($delete != "") {
                    $query = $db->query("SELECT fid FROM $table_forums WHERE type='forum' AND fup='$delete'");
                    while($forum = $db->fetch_array($query)) {
                        $db->query("UPDATE $table_forums SET fup='' WHERE type='forum' AND fup='$delete'");
                    }

                    $db->query("DELETE FROM $table_forums WHERE type='group' AND fid='$delete'");
                }
                $name = addslashes($name);
                $db->query("UPDATE $table_forums SET name='$name', displayorder='$displayorder', status='$self[status]' WHERE fid='$group[fid]'");
            }

            if($newfname != $lang[textnewforum]) {
                $newfname = addslashes($newfname);
                $db->query("INSERT INTO $table_forums VALUES ('forum', '', '$newfname', '$newfstatus', '', '', '$newforder', '1', '', 'no', 'yes', 'yes', '', '', '0', '0', '$newffup', '1|1', 'yes', 'on', 'on', '', 'off', '0', '0', '0')");
            }

            if($newgname != $lang[textnewgroup]) {
                $newgname = addslashes($newgname);
                $db->query("INSERT INTO $table_forums VALUES ('group', '', '$newgname', '$newgstatus', '', '', '$newgorder', '', '', '', '', '', '', '', '0', '0', '', '', '', '', '', '', 'off', '0', '0', '0')");
            }

            if($newsubname != $lang[textnewsubf]) {
                $newsubname = addslashes($newsubname);
                $db->query("INSERT INTO $table_forums VALUES ('sub', '', '$newsubname', '$newsubstatus', '', '', '$newsuborder', '1', '', 'no', 'yes', 'yes', '', '', '0', '0', '$newsubfup', '1|1', 'yes', 'on', 'on', '', 'off', '0', '0', '0')");
            }

            echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[textforumupdate]</td></tr>";
        }else {
            $check_vars = array('allowhtmlnew', 'allowsmiliesnew', 'allowbbcodenew', 'allowimgcodenew', 'attachstatusnew', 'pollstatusnew', 'guestpostingnew');
            foreach($check_vars as $key){
                if($$key != 'on' && $$key != 'yes'){
                    $$key = 'off';
                }
            }

            $namenew = addslashes($namenew);
            $descnew = addslashes($descnew);

            $db->query("UPDATE $table_forums SET name='$namenew', description='$descnew', allowhtml='$allowhtmlnew', allowsmilies='$allowsmiliesnew', allowbbcode='$allowbbcodenew', theme='$themeforumnew', userlist='$userlistnew', private='$privatenew', postperm='$postperm1|$postperm2', allowimgcode='$allowimgcodenew', attachstatus='$attachstatusnew', pollstatus='$pollstatusnew', password='$passwordnew', guestposting='$guestpostingnew' , mpnp='$mpnpnew', mpnt='$mpntnew', mpfa='$mpfanew' WHERE fid='$fdetails'");
            if($delete != "") {
                $db->query("DELETE FROM $table_forums WHERE fid='$delete'");
            }

            echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[textforumupdate]</td></tr>";
        }
    }
}



if($action == "mods") {
    if(!$modsubmit) {
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td>
        <br />
        <form method="post" action="cp.php?action=mods">
        <table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr class="header">
        <td><?=$lang['textforum']?></td>
        <td><?=$lang['textmoderator']?></td>
        </tr>

        <?
        $oldfid = 0;
        $query = $db->query("SELECT f.moderator, f.name, f.fid, c.name as cat_name, c.fid as cat_fid FROM $table_forums f LEFT JOIN $table_forums c ON (f.fup = c.fid) WHERE (c.type='group' AND f.type='forum') OR (f.type='forum' AND f.fup='') ORDER BY c.displayorder, f.displayorder");
        while($forum = $db->fetch_array($query)) {
            if($oldfid != $forum['cat_fid']){
                $oldfid = $forum['cat_fid']
                ?>
                <tr bgcolor="<?=$altbg2?>" class="tablerow">
                <td colspan="2"><b><?=$forum['cat_name']?></b></td>
                </tr>
                <?php
            }
            ?>

            <tr bgcolor="<?=$altbg1?>" class="tablerow">
            <td><?=$forum['name']?></td>
            <td><input type="text" name="mod[<?=$forum['fid']?>]"" value="<?=$forum['moderator']?>" /></td>
            </tr>

            <?php
            $querys = $db->query("SELECT name, fid, moderator FROM $table_forums WHERE fup='$forum[fid]' AND type='sub'");
            while($sub = $db->fetch_array($querys)){
                ?>
                <tr bgcolor="<?=$altbg1?>" class="tablerow">
                <td><?=$lang['4spaces']?><?=$lang['4spaces']?><i><?=$sub['name']?></i></td>
                <td><input type="text" name="mod[<?=$sub['fid']?>]"" value="<?=$sub['moderator']?>" /></td>
                </tr>
                <?php
            }
        }
        ?>

        </table>
        </td></tr></table><br />
        <span class="smalltxt"><?=$lang['multmodnote']?></span><br /><br />
        <center><input type="submit" class="submit" name="modsubmit" value="<?=$lang['textsubmitchanges']?>" /></center>
        </form>

        </td>
        </tr>

        <?php
    }else{
        if(is_array($mod)){
            foreach($mod as $fid=>$mods){
                $db->query("UPDATE $table_forums SET moderator='$mods' WHERE fid='$fid'");
            }
        }

        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[textmodupdate]</td></tr>";
    }
}



if($action == "members") {
    if(!$membersubmit) {
        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />

        <?
        if(!$members) {
            ?>

            <form method="post" action="cp.php?action=members&amp;members=search">
            <span class="mediumtxt"><?=$lang[textsrchusr]?></span> <input type="text" name="srchmem">&nbsp;&nbsp;<span class="mediumtxt"><?=$lang[textwithstatus]?></span>

            <select name="srchstatus">
            <option value="0"><?=$lang[anystatus]?></option>
            <option value="Administrator"><?=$lang[textadmin]?></option>
            <option value="Super Administrator"><?=$lang[superadmin]?></option>
            <option value="Super Moderator"><?=$lang[textsupermod]?></option>
            <option value="Moderator"><?=$lang[textmod]?></option>
            <option value="Member"><?=$lang[textmem]?></option>
            <option value="Banned"><?=$lang[textbanned]?></option>
            </select><br /><br />
            <input type="submit" class="submit" value="<?=$lang[textgo]?>" />
            </form>
            </td></tr>

            <?
        }elseif($members == "search") {
            ?>
            <form method="post" action="cp.php?action=members">
            <table cellspacing="0" cellpadding="0" border="0" width="91%" align="center">
            <tr><td bgcolor="<?=$bordercolor?>">

            <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">


            <tr class="header">
            <td align="center" width="3%"><?=$lang[textdeleteques]?></td>
            <td><?=$lang[textusername]?></td>
            <td><?=$lang[textnewpassword]?></td>
            <td><?=$lang[textposts]?></td>
            <td><?=$lang_shop_textmoney?></td>
            <td><?=$lang[textstatus]?></td>
            <td><?=$lang[textcusstatus]?></td>
            <td><?=$lang[textbanfrom]?></td>
            </tr>

            <?
            if($srchstatus == "0") {
                $query = $db->query("SELECT * FROM $table_members WHERE username LIKE '%$srchmem%' ORDER BY username");
            } else {
                $query = $db->query("SELECT * FROM $table_members WHERE username LIKE '%$srchmem%' AND status='$srchstatus' ORDER BY username");
            }

            while($member = $db->fetch_array($query)) {
                switch($member['status']) {
                    case 'Super Administrator';
                        $sadminselect = 'selected="selected"';
                        break;

                    case 'Administrator':
                        $adminselect = 'selected="selected"';
                        break;

                    case 'Super Moderator':
                        $smodselect = 'selected="selected"';
                        break;

                    case 'Moderator':
                        $modselect = 'selected="selected"';
                        break;

                    case 'Member':
                        $memselect = 'selected="selected"';
                        break;

                    case 'Banned':
                        $banselect = 'selected="selected"';
                        break;

                    default:
                        $memselect = 'selected="selected"';
                        break;
                }

                switch($member['ban']){
                    case 'u2u':
                        $u2uban = 'selected="selected"';
                        break;

                    case 'posts':
                        $postban = 'selected="selected"';
                        break;

                    case 'both':
                        $bothban = 'selected="selected"';
                        break;

                    default:
                        $noban = 'selected="selected"';
                        break;
                }
                ?>

                <tr bgcolor="<?=$altbg2?>" class="tablerow">
                <td align="center"><input type="checkbox" name="delete<?=$member[uid]?>" value="<?=$member[uid]?>" /></td>
                <td><a href="member.php?action=viewpro&amp;member=<?=$member[username]?>"><?=$member[username]?>
				<br />- <a href="javascript:void('')" onclick="Popup('awedit.php?action=edit&username=<?=$member[username]?>', 'Window', 350, 400);"><b>Edit awards</b></a>
                <br /><a href="cp.php?action=deleteposts&amp;member=<?=$member[username]?>"><b><?=$lang[cp_deleteposts]?></b></a>
                <br /><a href="u2uadmin.php?uid=<?=$member[username]?>"><?=$lang[cp_viewinbox]?></a></td>
                <td><input type="text" size="12" name="pw<?=$member[uid]?>"></td>
                <td><input type="text" size="3" name="postnum<?=$member[uid]?>" value="<?=$member[postnum]?>"></td>
                <td><input type="text" size="3" name="money<?=$member[uid]?>" value="<?=$member[money]?>"></td>
                <td><select name="status<?=$member[uid]?>">
                <option value="Super Administrator" <?=$sadminselect?>><?=$lang[superadmin]?></option>
                <option value="Administrator" <?=$adminselect?>><?=$lang[textadmin]?></option>
                <option value="Super Moderator" <?=$smodselect?>><?=$lang[textsupermod]?></option>
                <option value="Moderator" <?=$modselect?>><?=$lang[textmod]?></option>
                <option value="Member" <?=$memselect?>><?=$lang[textmem]?></option>
                <option value="Banned" <?=$banselect?>><?=$lang[textbanned]?></option>
                </select></td>
                <td><input type="text" size="16" name="cusstatus<?=$member[uid]?>" value="<?=stripslashes($member[customstatus])?>" /></td>
                <td><select name="banstatus<?=$member[uid]?>">
                <option value="" <?=$noban?>><?=$lang[noban]?></option>
                <option value="u2u" <?=$u2uban?>><?=$lang[banu2u]?></option>
                <option value="posts" <?=$postban?>><?=$lang[banpost]?></option>
                <option value="both" <?=$bothban?>><?=$lang[banboth]?></option>
                </select></td>
                </tr>

                <?
                $adminselect = "";
                $sadminselect = "";
                $smodselect = "";
                $modselect = "";
                $memselect = "";
                $banselect = "";
                $noban = "";
                $u2uban = "";
                $postban = "";
                $bothban = "";
            }
            ?>

            </table>
            </td></tr></table><br />
            <center><input type="submit" class="submit" name="membersubmit" value="<?=$lang[textsubmitchanges]?>" /></center>
            <input type="hidden" name="srchmem" value="<?=$srchmem?>">
            <input type="hidden" name="srchstatus" value="<?=$srchstatus?>">
            </form>

            </td>
            </tr>
            </td></tr>

            <?
        }
    }elseif($membersubmit) {
        if($srchstatus == "0") {
            $query = $db->query("SELECT uid, username, password, status FROM $table_members WHERE username LIKE '%$srchmem%'");
        } else {
            $query = $db->query("SELECT uid, username, password, status FROM $table_members WHERE username LIKE '%$srchmem%' AND status='$srchstatus'");
        }

        while($mem = $db->fetch_array($query)) {
            $to[status] = "status$mem[uid]";
            $to[status] = "${$to[status]}";

            $origstatus = '';
            $origstatus = $mem['status'];

            $banstatus = "banstatus$mem[uid]";
            $banstatus = "${$banstatus}";

            $cusstatus = "cusstatus$mem[uid]";
            $cusstatus = "${$cusstatus}";

            $pw = "pw$mem[uid]";
            $pw = "${$pw}";

            $postnum = "postnum$mem[uid]";
            $postnum = "${$postnum}";

            $money = "money$mem[uid]";
            $money = "${$money}";

            $delete = "delete$mem[uid]";
            $delete = "${$delete}";

            if($pw != "") {
                $newpw = md5($pw);
                $queryadd = " , password='$newpw'";
            } else {
                $newpw = $mem[password];
                $queryadd = " , password='$newpw'";
            }

            if($self['status'] != "Super Administrator" && ($origstatus == "Super Administrator" || $to['status'] == "Super Administrator")){
                continue;
            }

            if($delete != "") {
                $db->query("DELETE FROM $table_members WHERE uid='$delete'");
            }else {
                if(strpos($pw, '"') !== false || strpos($pw, "'") !== false) {
                    $lang[textmembersupdate] = "$mem[username]: $lang[textpwincorrect]";
                } else {
                    $newcustom = addslashes($cusstatus);
                    $db->query("UPDATE $table_members SET ban='$banstatus', status='$to[status]', postnum='$postnum', money='$money', customstatus='$newcustom'$queryadd WHERE uid='$mem[uid]'");
                    $newpw="";
                }
            }
        }


        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[textmembersupdate]</td></tr>";
    }
}

if($action == "ipban") {
    if(!$ipbansubmit) {
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp.php?action=ipban">
        <table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
        <tr>
        <td class="header"><?=$lang[textdeleteques]?></td>
        <td class="header"><?=$lang[textip]?>:</td>
        <td class="header"><?=$lang[textipresolve]?>:</td>
        <td class="header"><?=$lang[textadded]?></td>
        </tr>

        <?
        $query = $db->query("SELECT * FROM $table_banned ORDER BY dateline");
        while($ipaddress = $db->fetch_array($query)) {

            for($i=1; $i<=4; ++$i) {
                $j = "ip" . $i;
                if($ipaddress[$j] == -1){
                    $ipaddress[$j] = "*";
                }
            }

            $ipdate = gmdate("n/j/y", $ipaddress[dateline] + ($timeoffset * 3600) + ($addtime * 3600)) . " $lang[textat] " . gmdate("$timecode", $ipaddress[dateline] + ($timeoffset * 3600) + ($addtime * 3600));
            $theip = "$ipaddress[ip1].$ipaddress[ip2].$ipaddress[ip3].$ipaddress[ip4]";
            ?>

            <tr bgcolor="<?=$altbg2?>">
            <td class="tablerow"><input type="checkbox" name="delete<?=$ipaddress[id]?>" value="<?=$ipaddress[id]?>" /></td>
            <td class="tablerow"><?=$theip?></td>
            <td class="tablerow"><?=@gethostbyaddr($theip)?></td>
            <td class="tablerow"><?=$ipdate?></td>
            </tr>

            <?
        }

        $query = $db->query("SELECT id FROM $table_banned WHERE (ip1='$ips[0]' OR ip1='-1') AND (ip2='$ips[1]' OR ip2='-1') AND (ip3='$ips[2]' OR ip3='-1') AND (ip4='$ips[3]' OR ip4='-1')");
        $result = $db->fetch_array($query);
        if($result){
            $warning = $lang[ipwarning];
        }

        ?>
        <tr bgcolor="<?=$altbg2?>"><td colspan="4"> </td></tr>
        <tr bgcolor="<?=$altbg1?>">
        <td colspan="4" class="tablerow"><?=$lang[textnewip]?>
        <input type="text" name="newip1" size="3" maxlength="3" />.<input type="text" name="newip2" size="3" maxlength="3" />.<input type="text" name="newip3" size="3" maxlength="3" />.<input type="text" name="newip4" size="3" maxlength="3" /></td>
        </tr>

        </table>
        </td></tr></table><br />
        <span class="smalltxt"><?=$lang[currentip]?> <b><?=$onlineip?></b><?=$warning?><br /><?=$lang[multipnote]?></span><br />
        <br /><center><input type="submit" class="submit" name="ipbansubmit" value="<?=$lang[textsubmitchanges]?>" /></center>
        </form>

        </td>
        </tr>

        <?
    }else{
        $queryip = $db->query("SELECT id FROM $table_banned");
        $newid = 1;
        while($ip = $db->fetch_array($queryip)) {
            $delete = "delete$ip[id]";
            $delete = "${$delete}";

            if($delete != "") {
                $query = $db->query("DELETE FROM $table_banned WHERE id='$delete'");
            }elseif($ip[id] > $newid) {
                $query = $db->query("UPDATE $table_banned SET id='$newid' WHERE id='$ip[id]'");
            }
            ++$newid;
        }

        $self[status] = $lang[textipupdate];

        if($newip1 != "" || $newip2 != "" || $newip3 != "" || $newip4 != "") {
            $invalid = 0;

            for($i=1;$i<=4 && !$invalid;++$i) {
                $newip = "newip$i";
                $newip = "${$newip}";
                $newip = trim($newip);

                if($newip == "*"){
                    $ip[$i] = -1;
                }elseif(ereg("^[0-9]+$", $newip)){
                    $ip[$i] = $newip;
                }else{
                    $invalid = 1;
                }
            }

            if($invalid){
                $self[status] = $lang[invalidip];
            }else{
                if($ip[1] == '-1' && $ip[2] == '-1' && $ip[3] == '-1' && $ip[4] == '-1'){
                    $self[status] = $lang[impossiblebanall];
                }else{
                    $query = $db->query("SELECT id FROM $table_banned WHERE (ip1='$ip[1]' OR ip1='-1') AND (ip2='$ip[2]' OR ip2='-1') AND (ip3='$ip[3]' OR ip3='-1') AND (ip4='$ip[4]' OR ip4='-1')");
                    $result = $db->fetch_array($query);
                    if ($result){
                        $self[status] = $lang[existingip];
                    }else{
                        $query = $db->query("INSERT INTO $table_banned VALUES ('$ip[1]', '$ip[2]', '$ip[3]', '$ip[4]', '$onlinetime', '$newid')");
                    }
                }
            }
        }

        echo "<tr bgcolor=\"$altbg2\"><td align=\"center\" class=\"tablerow\">$self[status]</td></tr>";
    }
}

if($action == "deleteposts"){
$queryd = $db->query("DELETE FROM $table_posts WHERE author='$member'");
$queryt = $db->query("SELECT * FROM $table_threads");
while($threads = $db->fetch_array($queryt)) {

$query = $db->query("SELECT COUNT(*) FROM $table_posts WHERE tid='$threads[tid]'");
$replynum = $db->result($query, 0);

$replynum--;
$db->query("UPDATE $table_threads SET replies=replies-1 WHERE tid='$threads[tid]'");
$db->query("DELETE FROM $table_threads WHERE author='$member'");
}
}

if($action == "upgrade") {
    if($self['status'] != "Super Administrator"){
        $navigation .= '&raquo; '.$lang['error'];
        $message = $lang['superadminonly'];
        end_time();

        echo "</table></td></tr></table>";
        $header = '';
        eval("\$error = \"".template('error')."\";");
        eval("\$footer = \"".template("footer")."\";");

        echo $error;
        echo $footer;

        exit();
    }
    if($upgradesubmit) {
        if(isset($_FILES['sql_file'])) {
            $add = get_attached_file($_FILES['sql_file'], 'on');
            if($add !== false) {
                $upgrade .= $add;
            }
        }
        $upgrade = str_replace('$table_', $tablepre, $upgrade);

        $explode = explode(";", $upgrade);
        $count = count($explode);
        if(strlen(trim($explode[$count-1])) == 0) {
            unset($explode[$count-1]);
            $count--;
        }
        
        echo "</table></td></tr></table>";
        echo '<br />';
        
        for($num=0;$num<$count;$num++) {
            $explode[$num] = stripslashes($explode[$num]);
            
            if($allow_spec_q !== true) {
                if(strtoupper(substr(trim($explode[$num]), 0, 3)) == 'USE' || strtoupper(substr(trim($explode[$num]), 0, 14)) == 'SHOW DATABASES'){
                    error($lang['textillegalquery']);
                }
            }
            if($explode[$num] != "") {
                $query = $db->query($explode[$num]);
            }
            echo '<br />';
            ?>
            <table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center"><tr><td bgcolor="<?=$bordercolor?>">
            <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
                <tr bgcolor="<?=$altbg2?>" class="tablerow">
                    <td colspan="<?=$db->num_fields($query)?>">
                        <b>
                            <?=$explode[$num]?>
                        </b>
                    </td>
                </tr>

                <tr bgcolor="<?=$altbg1?>" class="tablerow">
            <?php
            $xn = strtoupper($explode[$num]);
            if(strpos($xn, 'SELECT') !== false || strpos($xn, 'SHOW') !== false || strpos($xn, 'EXPLAIN') !== false || strpos($xn, 'DESCRIBE') !== false){
                dump_query($query, true);
            }else{
                $selq=false;
            }
            ?>
            </tr></table></td></tr></table>
            <?php

        }
        ?>
            <br />
            <table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center"><tr><td bgcolor="<?=$bordercolor?>"><table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
                <tr bgcolor="<?=$altbg2?>" class="ctrtablerow">
                    <td>
                        <?=$lang['upgradesuccess']?>
                    </td>
                </tr>
             </table></td></tr></table>
        <?php
        end_time();

        eval("echo \"".template("footer")."\";");
        exit();
        
    } else {

        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp.php?action=upgrade" enctype="multipart/form-data">
        <table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr class="header">
        <td colspan=2><?=$lang['textupgrade']?></td>
        </tr>

        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td valign="top"><?=$lang['upgrade']?><br /><textarea cols="85" rows="10" name="upgrade"></textarea><br />
        <input type="file" name="sql_file" /><br />
        <?=$lang['upgradenote']?><br />
        <center><input type="submit" class="submit" name="upgradesubmit" value="<?=$lang['textsubmitchanges']?>" /></center>
        </td>
        </tr>
        </table>
        </td></tr></table>
        </form>

        </td>
        </tr>

        <?
    }
}

if($action == "search"){
    if($searchsubmit){
        $found = 0;
        $list = array();
        if($userip && !empty($userip)){
            $query = $db->query("SELECT * FROM $table_members WHERE regip = '$userip'");
            while($users = $db->fetch_array($query)){
                $link = "./member.php?action=viewpro&amp;member=$users[username]";
                $list[] = "<a href = \"$link\">$users[username]<br />";
                $found++;
            }
        }

        if($postip && !empty($postip)){
            $query = $db->query("SELECT * FROM $table_posts WHERE useip = '$postip'");
            while($users = $db->fetch_array($query)){
                $link = "./viewthread.php?tid=$users[tid]#pid$users[pid]";
                if(!empty($users[subject])){
                    $list[] = "<a href = \"$link\">$users[subject]<br />";
                }else{
                    $list[] = "<a href = \"$link\">- - No subject - -<br />";
                }
                $found++;
            }
        }

        if($profileword && !empty($profileword)){
            $query = $db->query("SELECT * FROM $table_members WHERE bio = '%$profileword%'");
            while($users = $db->fetch_array($query)){
                $link = "./member.php?action=viewpro&amp;member=$users[username]";
                $list[] = "<a href = \"$link\">$users[username]<br />";
                $found++;
            }
        }

        if($postword && !empty($postword)){
            $query = $db->query("SELECT * FROM $table_posts WHERE subject LIKE '%".$postword."%' OR message LIKE '%".$postword."%'");
            while($users = $db->fetch_array($query)){
                $link = "./viewthread.php?tid=$users[tid]#pid$users[pid]";
                if(!empty($users[subject])){
                    $list[] = "<a href = \"$link\">$users[subject]<br />";
                }else{
                    $list[] = "<a href = \"$link\">- - No subject - -<br />";
                }
                $found++;
            }
        }

    ?>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
            <td align="left" colspan="2">
                <b><?=$found?></b> <?=$lang['beenfound']?>
                <br />
            </td>
        </tr>
        <?php
        foreach($list as $num=>$val){
            ?>
            <tr class="tablerow" width="5%">
                <td align="left" bgcolor="<?=$altbg2?>">
                    <b><?=($num+1)?>.</b>
                </td>
                <td align="left" width="95%" bgcolor="<?=$altbg1?>">
                    <?=$val?>
                </td>
            </tr>
            <?php
         }
    }else{
        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp.php?action=search">
        <table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr class="header">
        <td colspan=2><?=$lang[insertdata]?>:</td>
        </tr>

        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td valign="top"><center><br />
        <?=$lang['userip']?><br /><input type="text" name="userip"></input><br /><br />
        <?=$lang['postip']?><br /><input type="text" name="postip"></input><br /><br />
              <?=$lang['profileword']?><br /><input type="text" name="profileword"></input><br /><br />
        <?=$lang['postword']?><br />
        <?php
        $query = $db->query("SELECT find FROM $table_words");
        $select = "<select name=\"postword\"><option value=\"\"></option>";
        while($temp = $db->fetch_array($query)){
            $select .= "<option value=\"$temp[find]\">$temp[find]</option>";
        }
        $select .= "</select>";
        echo $select;
        ?>

        <br /><br />
        <center><br /><input type="submit" class="submit" name="searchsubmit" value="Search now" /><br /><br /></center>
        </td>
        </tr>
        </table>
        </td></tr></table>
        </form>

        </td>
        </tr>
    <?php
    }
}

echo "</table></td></tr></table>";

end_time();

eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>