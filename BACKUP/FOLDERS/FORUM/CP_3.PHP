<?php
/* $Id: cp2.php,v 1.2 2004/04/15 15:16:37 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require "./header.php";
loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','header','footer', 'css');
eval('$css = "'.template('css').'";');

if(!isset($action)){
    $action = '';
}

// Start Download Templates and Theme Code
if($self['status'] == "Administrator" || $self['status']=="Super Administrator") {
    if($action == "templates" && isset($download)) {
        $templates=$db->query("SELECT * FROM $table_templates");
        while ($template = $db->fetch_array($templates)) {
            $template['template'] = trim($template['template']);
            $template['name'] = trim($template['name']);

            if(!($template['name'] == '')){
                $template['template'] = stripslashes($template['template']);

                $code.= $template['name']."|#*XMB TEMPLATE*#|\r\n".$template['template']."\r\n\r\n|#*XMB TEMPLATE FILE*#|";
            }
        }
        header("Content-disposition: filename=templates.xmb");
        header("Content-Length: ".strlen($code));
        header("Content-type: unknown/unknown");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $code;
        exit();
    }
    elseif($action == "themes" && isset($download)) {
        $query = $db->query("SELECT * FROM $table_themes WHERE themeid='$download'");
        $themebits = $db->fetch_array($query);
        while(list($key,$val) = each($themebits)) {
            if(!is_integer($key) && $key != 'themeid') {
                $contents .= "$key=$val\r\n";
            }
        }
        $name = str_replace(' ', '+', $themebits['name']);
        header("Content-Type: application/x-ms-download");
        header("Content-Disposition: filename=${name}-theme.xmb");
        echo $contents;
        exit();
    }

    elseif($action == 'dbdump'){
        $time = time();
        header("Content-disposition: filename=$bbname-$time.sql");
        header("Content-type: text/sql");
        header("Pragma: no-cache");
        header("Expires: 0");

        @ob_implicit_flush(1);

        echo "########################################\n";
        echo "# XMB 1.9 Database Dump function\n";
        echo "# Created by: Tularis\n";
        echo "# Version 1.00\n";
        echo "# Copyright 2001-2003 \n";
        echo "# by The XMB Group &amp; aventure-media \n";
        echo "#\n";
        echo "#\n";
        echo "# Start of Dump\n";
        echo "########################################\n\n";

        foreach($db->fetch_tables($dbname) as $table){
            if(strpos($table, 'attachments') === false){
                echo "\n\n# Dumping: $table\n";
                echo create_table_backup($table);
            }else{
                echo "\n\n#dumping $table\n";
                echo create_table_backup($table, false);
            }
        }

        echo "########################################\n";
        echo "# End of Dump\n";
        echo "########################################\n";
        exit();
    }

    elseif($action == 'dump_attachments'){
        $i = 0;

        if(!is_writable('./attachments')){
            error('attachments directory ("./attachments") should be chmodded to 777 so XMB can write to it.');
        }

        $query = $db->unbuffered_query("SELECT * FROM $table_attachments");
        while($attachment = $db->fetch_array($query)){
            $stream = @fopen('./attachments/'.$attachment['aid'].'.xmb', 'w+');
            fwrite($stream, $attachment['attachment'], strlen($attachment['attachment']));
            fclose($stream);

            unset($attachment['attachment']);
            $info_string = implode('//||//', $attachment)."\n";

            $stream2 = @fopen('./attachments/index.inf', 'a+');
            fwrite($stream2, $info_string, strlen($info_string));
            fclose($stream2);

            $i++;
        }

        echo $i.' attachments stored';
    }
}

// End Download Templates and Theme Code

$navigation = "&raquo; $lang[textcp]";
eval("\$header = \"".template("header")."\";");
echo $header;

if($self['status'] != "Administrator" && $self['status'] !="Super Administrator") {
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
$filehandle = @fopen('./cplogfile.php','a');
if(!$filehandle){
    error('Wrong File permissions set. Please CHMOD the <i>cplogfile.php</i> to <i>777</i>', false, '', '', false, false, false, false);
}
@flock($filehandle, 2);
@fwrite($filehandle, $string);
@fclose($filehandle);
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
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>">RESTRICTED</font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['textmembers']?></font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['look_feel']?></font></b></td>
</tr>

<tr>
<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp_3.php?action=censor"><?=$lang['textcensors']?></a><br />
&raquo; <a href="cp_3.php?action=newsletter"><?=$lang['textnewsletter']?></a><br />
&raquo; <a href="cp3.php?action=search"><?=$lang['cpsearch']?></a><br />
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
RESTRICTED
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp3.php?action=ipban"><?=$lang['textipban']?></a><br />
&raquo; <a href="cp3.php?action=members"><?=$lang['textmembers']?></a><br />
&raquo; <a href="cp_3.php?action=restrictions"><?=$lang['cprestricted']?></a><br />
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp_3.php?action=smilies"><?=$lang['smilies']?></a><br />
</td>
</tr>

<tr>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['logs']?></font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>"><?=$lang['tools']?></font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>">RESTRICTED</font></b></td>
<td class="category" <?=$catbgcode?> valign="top" width="20%" align="center"><b><font color="<?=$cattext?>">RESTRICTED</font></b></td>
</tr>

<tr>
<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
&raquo; <a href="cp_3.php?action=cplog"><?=$lang['cplog']?></a><br />
&raquo; <a href="cp_3.php?action=modlog"><?=$lang['modlogs']?></a><br />
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
RESTRICTED
</td>

<td class="tablerow" align="left" valign="top" width="20%" bgcolor="<?=$altbg2?>">
RESTRICTED
</td>
</tr>
</table></td>
</tr>
</table>
<br />

<?
if($action == "restrictions") {
    if(!$restrictedsubmit) {
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=restrictions">
        <table align="center" border="0" cellspacing="0" cellpadding="0" width="450">
        <tr>
        <td bgcolor="<?=$bordercolor?>">
        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
        <tr class="header">
        <td><span class="smalltxt"><?=$lang['textdeleteques']?></span></td>
        <td><span class="smalltxt"><?=$lang['restrictedname']?></span></td>
        <td><span class="smalltxt">case-sensitive</span></td>
        <td><span class="smalltxt">partial-match</span></td>
        </tr>

        <?
        $query = $db->query("SELECT * FROM $table_restricted ORDER BY id");
        while($restricted = $db->fetch_array($query)) {
            if($restricted['case_sensitivity'] == 1){
                $case_check = 'checked="checked"';
            }else{
                $case_check = '';
            }

            if($restricted['partial'] == 1){
                $partial_check = 'checked="checked"';
            }else{
                $partial_check = '';
            }
            ?>
            <tr class="tablerow">
            <td bgcolor="<?=$altbg1?>"><input type="checkbox" name="delete<?=$restricted[id]?>" value="<?=$restricted[id]?>" /></td>
            <td bgcolor="<?=$altbg1?>"><input type="text" size="30" name="name<?=$restricted[id]?>" value="<?=$restricted[name]?>" /></td>
            <td bgcolor="<?=$altbg1?>"><input type="checkbox" name="case<?=$restricted[id]?>" value="<?=$restricted[id]?>" <?=$case_check?> /></td>
            <td bgcolor="<?=$altbg1?>"><input type="checkbox" name="partial<?=$restricted[id]?>" value="<?=$restricted[id]?>" <?=$partial_check?> /></td>
            </tr>
            <?
        }

        ?>
        <tr>
        <td bgcolor="<?=$altbg2?>" colspan="4"><img src="./images/pixel.gif" alt="" /></td>
        </tr>
        <tr class="tablerow">
        <td bgcolor="<?=$altbg1?>" colspan="4" align="left">
        <table border="0">
        <tr><td colspan="2"><span class="smalltxt"><?=$lang[textnewcode]?></span></td></tr>
        <tr><td colspan="2"><span class="smalltxt"><?=$lang[newrestriction]?></span></td></tr>
        <tr><td colspan="2"><span class="smalltxt"><?=$lang[newrestrictionwhy]?></span></td></tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr><td><span class="smalltxt">name:</span></td><td><input type="text" size="30" name="newname" /></td></tr>
        <tr><td><span class="smalltxt">case-sensitive:</span></td><td><input type="checkbox" name="newcase" value="1" checked="unchecked" /></td></tr>
        <tr><td><span class="smalltxt">partial-match:</span></td><td><input type="checkbox" name="newpartial" value="1" checked="checked" /></td></tr>
        </table>
        </td>
        </tr>
        </table>
        </td>
        </tr>
        </table><br />
        <center><input class="submit" type="submit" name="restrictedsubmit" value="<?=$lang[textsubmitchanges]?>" /></center>
        </form>
        </td>
        </tr>

        <?
    }else{
        $queryrestricted = $db->query("SELECT id FROM $table_restricted");
        while($restricted = $db->fetch_array($queryrestricted)) {
            $name = '';
            $delete = '';
            $case = '';
            $partial = '';

            $name = ${'name'.$restricted[id]};
            $delete = ${'delete'.$restricted[id]};
            $case = ${'case'.$restricted[id]};
            $partial = ${'partial'.$restricted[id]};

            if($partial != ''){
                $partial = 1;
            }else{
                $partial = 0;
            }

            if($case != ''){
                $case = 1;
            }else{
                $case = 0;
            }

            if($delete != "") {
                $db->query("DELETE FROM $table_restricted WHERE id='$delete'");
                continue;
            }
            $db->query("UPDATE `$table_restricted` SET `name`='$name', `case_sensitivity`='$case', `partial`='$partial' WHERE `id`='$restricted[id]'");
        }

        if($newname != "") {
            if(!$newpartial || $newpartial != 1){
                $newpartial = 0;
            }else{
                $newpartial = 1;
            }
            if(!$newcase || $newcase != 1){
                $newcase = 0;
            }else{
                $newcase = 1;
            }
            $db->query("INSERT INTO $table_restricted (`name`, `id`, `case_sensitivity`, `partial`) VALUES ('$newname', '', '$newcase', '$newpartial')");
        }

        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[restrictedupdate]</td></tr>";

        redirect('cp2.php?action=restrictions');
    }
}

elseif($action == "themes") {
    if(!isset($themesubmit) && !isset($single) && !isset($importsubmit)) {
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td>
        <br />
        <form method="POST" action="cp2.php?action=themes" name="theme_main">
        <table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
        <tr>
        <td bgcolor="<?=$bordercolor?>">
        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
        <tr class="header">
        <td align="center"><?=$lang['textdeleteques']?></td>
        <td><?=$lang['textthemename']?></td>
        <td><?=$lang['numberusing']?></td>
        </tr>

        <?
        $query = $db->query("SELECT name, themeid FROM $table_themes ORDER BY name ASC");
        while($themeinfo = $db->fetch_array($query)) {
            $themeid = $themeinfo['themeid'];
            if($themeinfo['themeid'] == $SETTINGS['theme']){
                $themeid2 = "OR theme=''";
            }else{
                $themeid2 = "";
            }

            $memthemequery = $db->query("SELECT COUNT(theme) FROM $table_members WHERE theme='$themeid' $themeid2");
            $members = $db->result($memthemequery, 0);

            if($themeinfo['themeid'] == $theme) {
                $checked = "checked=\"checked\"";
            }else{
                $checked = "checked=\"unchecked\"";
            }

            ?>

            <tr bgcolor="<?=$altbg2?>" class="tablerow">
            <td align="center"><input type="checkbox" name="theme_delete[]" value="<?=$themeinfo['themeid']?>" /></td>
            <td>
            <input type="text" name="theme_name[<?=$themeinfo['themeid']?>]" value="<?=$themeinfo['name']?>" />
            <a href="cp2.php?action=themes&amp;single=<?=$themeinfo['themeid']?>">
            <?=$lang['textdetails']?></a>
            -
            <a href="cp2.php?action=themes&amp;download=<?=$themeinfo['themeid']?>">
            <?=$lang['textdownload']?>
            </a>
            </td>
            <td><?=$members?></td>
            </tr>

            <?
        }
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td colspan="3"><img src="./images/pixel.gif" alt="" /></td>
        </tr>
        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td colspan="3">
            <a href="cp2.php?action=themes&amp;single=anewtheme1">
                <b><?=$lang['textnewtheme']?></b>
            </a>
             -
            <a href="#" onclick="setCheckboxes('theme_main', 'theme_delete[]', true); return false;">
                Check All
            </a>
             -
            <a href="#" onclick="setCheckboxes('theme_main', 'theme_delete[]', false); return false;">
                Uncheck All
            </a>
             -
            <a href="#" onclick="invertSelection('theme_main', 'theme_delete[]'); return false;">
                Invert Selection
            </a>
        </td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        <center><br /><input type="submit" name="themesubmit" value="<?=$lang['textsubmitchanges']?>" class="submit"/></center></form><br />
        <form method="post" action="cp2.php?action=themes" enctype="multipart/form-data">
        <table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
        <tr>
        <td bgcolor="<?=$bordercolor?>">
        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
        <tr class="header">
        <td colspan="2"><?=$lang['textimporttheme']?></td>
        </tr>
        <tr class="tablerow">
        <td bgcolor="<?=$altbg1?>"><?=$lang['textthemefile']?></td>
        <td bgcolor="<?=$altbg2?>"><input name="themefile" type="file" /></td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        <center><br /><input type="submit" class="submit" name="importsubmit" value="<?=$lang['textimportsubmit']?>" /></center></form>
        </td>
        </tr>

        <?

    }elseif($importsubmit && isset($themefile['tmp_name'])) {
        $themebits = readFileAsINI($themefile['tmp_name']);
        $start = "INSERT INTO $table_themes";

        $keysql = array();
        $valsql = array();

        foreach($themebits as $key=>$val){
            if($key == 'themeid'){
                $val = '';
            }elseif($key == 'name'){
                $name = $val;
            }
            $keysql[] = $key;
            $valsql[] = "'$val'";
        }


        $keysql = implode(', ', $keysql);
        $valsql = implode(', ', $valsql);

        $query = $db->query("SELECT count(themeid) FROM $table_themes WHERE name='".addslashes($name)."'");
        if($db->result($query, 0) > 0){
            error($lang['theme_already_exists'], false, '</td></tr></table></td></tr></table>');
        }

        $sql = "INSERT INTO $table_themes ($keysql) VALUES ($valsql);";
        $query = $db->query($sql);

        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">";
        if(!$query) {
            echo $lang['textthemeimportfail'];
        } else {
            echo $lang['textthemeimportsuccess'];
        }
        echo '</td></tr>';

    }elseif($themesubmit) {
        $number_of_themes = $db->result($db->query("SELECT count(themeid) FROM $table_themes"), 0);

        if(count($theme_delete) >= $number_of_themes){
            error($lang['delete_all_themes'], false, '</td></tr></table></td></tr></table>');
        }

        if(isset($theme_delete)){
            foreach($theme_delete as $themeid){
                $otherid = $db->result($db->query("SELECT themeid FROM $table_themes WHERE themeid != '$themeid' ORDER BY rand() LIMIT 1"), 0);
                $db->query("UPDATE $table_members SET theme='$otherid' WHERE theme='$themeid'");
                $db->query("UPDATE $table_forums SET theme='' WHERE theme='$themeid'");

                if($SETTINGS['theme'] == $themeid){
                    $db->query("UPDATE $table_settings SET theme='$otherid'");
                }

                $db->query("DELETE FROM $table_themes WHERE themeid='$themeid'");
            }
        }
        foreach($theme_name as $themeid=>$name){
            $db->query("UPDATE $table_themes SET name='$name' WHERE themeid='$themeid'");
        }

        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[themeupdate]</td></tr>";

    }elseif($single && $single != "submit" && $single != "anewtheme1") {
        $query = $db->query("SELECT * FROM $table_themes WHERE themeid='$single'");
        $themestuff = $db->fetch_array($query);
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td>
        <br /><form method="post" action="cp2.php?action=themes&amp;single=submit">
        <table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
        <tr>
        <td bgcolor="<?=$bordercolor?>">
        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[texthemename]?></td>
        <td colspan="2"><input type="text" name="namenew" value="<?=$themestuff[name]?>" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textbgcolor]?></td>
        <td><input type="text" name="bgcolornew" value="<?=$themestuff[bgcolor]?>" /></td>
        <td bgcolor="<?=$themestuff[bgcolor]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textaltbg1]?></td>
        <td><input type="text" name="altbg1new" value="<?=$themestuff[altbg1]?>" /></td>
        <td bgcolor="<?=$themestuff[altbg1]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textaltbg2]?></td>
        <td><input type="text" name="altbg2new" value="<?=$themestuff[altbg2]?>" /></td>
        <td bgcolor="<?=$themestuff[altbg2]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textlink]?></td>
        <td><input type="text" name="linknew" value="<?=$themestuff[link]?>" /></td>
        <td bgcolor="<?=$themestuff[link]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textborder]?></td>
        <td><input type="text" name="bordercolornew" value="<?=$themestuff[bordercolor]?>" /></td>
        <td bgcolor="<?=$themestuff[bordercolor]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textheader]?></td>
        <td><input type="text" name="headernew" value="<?=$themestuff[header]?>" /></td>
        <td bgcolor="<?=$themestuff[header]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textheadertext]?></td>
        <td><input type="text" name="headertextnew" value="<?=$themestuff[headertext]?>" /></td>
        <td bgcolor="<?=$themestuff[headertext]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[texttop]?></td>
        <td><input type="text" name="topnew" value="<?=$themestuff[top]?>" /></td>
        <td bgcolor="<?=$themestuff[top]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textcatcolor]?></td>
        <td><input type="text" name="catcolornew" value="<?=$themestuff[catcolor]?>" /></td>
        <td bgcolor="<?=$themestuff[catcolor]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textcattextcolor]?></td>
        <td><input type="text" name="cattextnew" value="<?=$themestuff[cattext]?>" /></td>
        <td bgcolor="<?=$themestuff[cattext]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[texttabletext]?></td>
        <td><input type="text" name="tabletextnew" value="<?=$themestuff[tabletext]?>" /></td>
        <td bgcolor="<?=$themestuff[tabletext]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[texttext]?></td>
        <td><input type="text" name="textnew" value="<?=$themestuff[text]?>" /></td>
        <td bgcolor="<?=$themestuff[text]?>">&nbsp;</td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textborderwidth]?></td>
        <td colspan="2"><input type="text" name="borderwidthnew" value="<?=$themestuff[borderwidth]?>" size="2" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textwidth]?></td>
        <td colspan="2"><input type="text" name="tablewidthnew" value="<?=$themestuff[tablewidth]?>" size="3" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textspace]?></td>
        <td colspan="2"><input type="text" name="tablespacenew" value="<?=$themestuff[tablespace]?>" size="2" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textfont]?></td>
        <td colspan="2"><input type="text" name="fnew" value="<?=$themestuff[font]?>" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textbigsize]?></td>
        <td colspan="2"><input type="text" name="fsizenew" value="<?=$themestuff[fontsize]?>" size="4" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textboardlogo]?></td>
        <td colspan="2"><input type="text"  value="<?=$themestuff[boardimg]?>" name="boardlogonew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[imgdir]?></td>
        <td colspan="2"><input type="text"  value="<?=$themestuff[imgdir]?>" name="imgdirnew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[smdir]?></td>
        <td colspan="2"><input type="text"  value="<?=$themestuff[smdir]?>" name="smdirnew" /></td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        <center><br /><input type="submit" class="submit" value="<?=$lang[textsubmitchanges]?>" /></center><input type="hidden" name="orig" value="<?=$single?>" /></form>
        </td>
        </tr>

        <?

    }elseif($single == "anewtheme1") {

        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=themes&amp;single=submit">
        <table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
        <tr>
        <td bgcolor="<?=$bordercolor?>">
        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[texthemename]?></td>
        <td><input type="text" name="namenew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textbgcolor]?></td>
        <td><input type="text" name="bgcolornew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textaltbg1]?></td>
        <td><input type="text" name="altbg1new" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textaltbg2]?></td>
        <td><input type="text" name="altbg2new" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textlink]?></td>
        <td><input type="text" name="linknew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textborder]?></td>
        <td><input type="text" name="bordercolornew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textheader]?></td>
        <td><input type="text" name="headernew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textheadertext]?></td>
        <td><input type="text" name="headertextnew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[texttop]?></td>
        <td><input type="text" name="topnew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textcatcolor]?></td>
        <td><input type="text" name="catcolornew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textcattextcolor]?></td>
        <td><input type="text" name="cattextnew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[texttabletext]?></td>
        <td><input type="text" name="tabletextnew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[texttext]?></td>
        <td><input type="text" name="textnew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textborderwidth]?></td>
        <td><input type="text" name="borderwidthnew" size="2" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textwidth]?></td>
        <td><input type="text" name="tablewidthnew" size="3" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textspace]?></td>
        <td><input type="text" name="tablespacenew" size="2" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textfont]?></td>
        <td><input type="text" name="fnew" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textbigsize]?></td>
        <td><input type="text" name="fsizenew" size="4" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[textboardlogo]?></td>
        <td><input type="text" name="boardlogonew" value="<?=$boardimg?>" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[imgdir]?></td>
        <td><input type="text" name="imgdirnew" value="images" /></td>
        </tr>
        <tr bgcolor="<?=$altbg2?>" class="tablerow">
        <td><?=$lang[smdir]?></td>
        <td><input type="text" name="smdirnew" value="images" /></td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        <center><br /><input class="submit" type="submit" value="<?=$lang[textsubmitchanges]?>" /></center><input type="hidden" name="newtheme" value="<?=$single?>" /></form>
        </td>
        </tr>

        <?

    }elseif($single == "submit" && !$newtheme) {
        $db->query("UPDATE $table_themes SET name='$namenew', bgcolor='$bgcolornew', altbg1='$altbg1new', altbg2='$altbg2new', link='$linknew', bordercolor='$bordercolornew', header='$headernew', headertext='$headertextnew', top='$topnew', catcolor='$catcolornew', tabletext='$tabletextnew', text='$textnew', borderwidth='$borderwidthnew', tablewidth='$tablewidthnew', tablespace='$tablespacenew', fontsize='$fsizenew', font='$fnew', boardimg='$boardlogonew', imgdir='$imgdirnew', smdir='$smdirnew', cattext='$cattextnew' WHERE themeid='$orig'");
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[themeupdate]</td></tr>";

    }elseif($single == "submit" && $newtheme) {
        $db->query("INSERT INTO $table_themes (themeid, name, bgcolor, altbg1, altbg2, link, bordercolor, header, headertext, top, catcolor, tabletext, text, borderwidth, tablewidth, tablespace, font, fontsize, boardimg, imgdir, smdir, cattext) VALUES('', '$namenew', '$bgcolornew', '$altbg1new', '$altbg2new', '$linknew', '$bordercolornew', '$headernew', '$headertextnew', '$topnew', '$catcolornew', '$tabletextnew', '$textnew', '$borderwidthnew', '$tablewidthnew', '$tablespacenew', '$fnew', '$fsizenew', '$boardlogonew', '$imgdirnew', '$smdirnew', '$cattextnew')");
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[themeupdate]</td></tr>";
    }
}



elseif($action == "smilies") {
    if(!$smiliesubmit) {
?>

    <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br /><form method="post" action="cp2.php?action=smilies">
            <table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
                <tr>
                    <td bgcolor="<?=$bordercolor?>">
                        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
                            <tr class="header">
                                <td colspan="4" align="left"><?=$lang[smilies]?></td>
                            </tr>
                            <tr class="header">
                                <td align="center"><?=$lang[textdeleteques]?></td>
                                <td><?=$lang[textsmiliecode]?></td>
                                <td><?=$lang[textsmiliefile]?></td>
                                <td align="center"><?=$lang[smilies]?></td>
                            </tr>

<?
    $query = $db->query("SELECT * FROM $table_smilies WHERE type='smiley' ORDER BY id");
    while($smilie = $db->fetch_array($query)) {
?>

                            <tr>
                                <td bgcolor="<?=$altbg2?>" align="center" class="tablerow"><input type="checkbox" name="delete<?=$smilie[id]?>" value="<?=$smilie[id]?>" /></td>
                                <td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="code<?=$smilie[id]?>" value="<?=$smilie[code]?>" /></td>
                                <td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="url<?=$smilie[id]?>" value="<?=$smilie[url]?>" /></td>
                                <td bgcolor="<?=$altbg2?>" align="center" class="tablerow"><img src="<?=$smdir?>/<?=$smilie[url]?>" alt="<?=$smilie[code]?>" /></td>
                            </tr>

<?
}
?>
                            <tr>
                                <td bgcolor="<?=$altbg2?>" colspan="4"><img src="./images/pixel.gif" alt="" /></td>
                            </tr>
                            <tr bgcolor="<?=$altbg1?>" class="tablerow">
                                <td><?=$lang[textnewsmilie]?></td>
                                <td><input type="text" name="newcode" /></td>
                                <td colspan="2"><input type="text" name="newurl1" /></td>
                            </tr>
                            <tr>
                                <td bgcolor="<?=$altbg2?>" colspan="4" align="left"><img src="./images/pixel.gif" alt="" /></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="header"><?=$lang[picons]?></td>
                            </tr>
                            <tr class="header">
                                <td align="center"><?=$lang[textdeleteques]?></td>
                                <td colspan="2" align="left"><?=$lang[textsmiliefile]?></td>
                                <td align="center"><?=$lang[picons]?></td>
                            </tr>
<?
    $query = $db->query("SELECT * FROM $table_smilies WHERE type='picon' ORDER BY id");
        while($smilie = $db->fetch_array($query)) {
?>

                            <tr>
                                <td bgcolor="<?=$altbg2?>" align="center" class="tablerow"><input type="checkbox" name="delete<?=$smilie[id]?>" value="<?=$smilie[id]?>" /></td>
                                <td colspan="2" align="left" bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="url<?=$smilie[id]?>" value="<?=$smilie[url]?>" /></td>
                                <td bgcolor="<?=$altbg2?>" align="center" class="tablerow"><img src="<?=$smdir?>/<?=$smilie[url]?>" alt="<?=$smilie[url]?>" /></td>
                            </tr>

<?
}
?>

                            <tr>
                                <td bgcolor="<?=$altbg2?>" colspan="4"><img src="./images/pixel.gif" alt="" /></td>
                            </tr>
                            <tr bgcolor="<?=$altbg1?>" class="tablerow">
                                <td colspan="4" align="left"><?=$lang[textnewpicon]?>&nbsp;&nbsp;<input type="text" name="newurl2" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
                <center><br /><input type="submit" class="submit" name="smiliesubmit" value="<?=$lang[textsubmitchanges]?>" /></center></form>
        </td>
    </tr>

<?
}

if($smiliesubmit) {
    $querysmilie = $db->query("SELECT id FROM $table_smilies WHERE type='smiley'");
        while($smilie = $db->fetch_array($querysmilie)) {
            $code = "code$smilie[id]";
            $code = "${$code}";
            $url = "url$smilie[id]";
            $url = "${$url}";
            $delete = "delete$smilie[id]";
            $delete = "${$delete}";
                if($delete != "") {
                    $query = $db->query("DELETE FROM $table_smilies WHERE id='$delete'");
                }
            $query = $db->query("UPDATE $table_smilies SET code='$code', url='$url' WHERE id='$smilie[id]' AND type='smiley'");
        }

    $querysmilie = $db->query("SELECT id FROM $table_smilies WHERE type='picon'");
        while($picon = $db->fetch_array($querysmilie)) {
            $url = "url$picon[id]";
            $url = "${$url}";
            $delete = "delete$picon[id]";
            $delete = "${$delete}";
                if($delete != "") {
                    $query = $db->query("DELETE FROM $table_smilies WHERE id='$delete'");
                }
            $query = $db->query("UPDATE $table_smilies SET url='$url' WHERE id='$picon[id]' AND type='picon'");
        }

    if($newcode != "") {
        $query = $db->query("INSERT INTO $table_smilies VALUES ('smiley', '$newcode', '$newurl1', '')");
    }

    if($newurl2 != "") {
        $query = $db->query("INSERT INTO $table_smilies VALUES ('picon', '', '$newurl2', '')");
    }

    echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[smilieupdate]</td></tr>";
    }
}



elseif($action == "censor") {
if(!$censorsubmit) {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="cp2.php?action=censor">
<table cellspacing="0" cellpadding="0" border="0" width="450"
align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>"
cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td width="4%" align="center"><?=$lang[textdeleteques]?></td>
<td align="left"><?=$lang[textcensorfind]?></td>
<td align="left"><?=$lang[textcensorreplace]?></td>
</tr>

<?
$query = $db->query("SELECT * FROM $table_words ORDER BY id");
while($censor = $db->fetch_array($query)) {

?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td align="center"><input type="checkbox" name="delete<?=$censor[id]?>"
value="<?=$censor[id]?>" /></td>
<td align="left"><input type="text" size="20" name="find<?=$censor[id]?>"
value="<?=$censor[find]?>" /></td>
<td align="left"><input type="text" size="20"
name="replace<?=$censor[id]?>" value="<?=$censor[replace1]?>" /></td>
</tr>

<?
}
?>

<tr bgcolor="<?=$altbg2?>">
<td colspan="3"><img src="./images/pixel.gif" alt="" /></td>
</tr>
<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td align="center"><b><?=$lang[textnewcode]?></b></td>
<td align="left"><input
type="text" size="20" name="newfind" /></td>
<td align="left"><input type="text" size="20" name="newreplace" /></td>
</tr>

</table>
</td></tr></table>
<center><br /><input type="submit" class="submit" name="censorsubmit" value="<?=$lang[textsubmitchanges]?>" /></center>
</form>

</td>
</tr>

<?
}

if($censorsubmit) {
$querycensor = $db->query("SELECT id FROM $table_words");

while($censor = $db->fetch_array($querycensor)) {
$find = "find$censor[id]";
$find = "${$find}";
$replace = "replace$censor[id]";
$replace = "${$replace}";
$delete = "delete$censor[id]";
$delete = "${$delete}";

if($delete != "") {
$db->query("DELETE FROM $table_words WHERE id='$delete'");
}

$db->query("UPDATE $table_words SET find='$find', replace1='$replace' WHERE id='$censor[id]'");
}

if($newfind != "") {
$db->query("INSERT INTO $table_words VALUES ('$newfind', '$newreplace', '')");
}

echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[censorupdate]</td></tr>";
}

} elseif($action == "ranks") {
    if(!$rankssubmit) {
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=ranks">
        <table cellspacing="0" cellpadding="0" border="0" width="650"
        align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>"
        cellpadding="<?=$tablespace?>" width="100%">

        <tr class="header">
        <td align="center"><?=$lang[textdeleteques]?></td>
        <td align="left"><?=$lang[textcusstatus]?></td>
        <td><?=$lang[textposts]?></td>
        <td><?=$lang[textstars]?></td>
        <td><?=$lang[textallowavatars]?></td>
        <td><?=$lang[textavatar]?></td>
        </tr>

        <?
        $query = $db->query("SELECT * FROM $table_ranks ORDER BY id");
        while($rank = $db->fetch_array($query)) {
            if($rank['title'] == 'Super Administrator' || $rank['title'] == 'Administrator' || $rank['title'] == 'Super Moderator' || $rank['title'] == 'Moderator'){
                $staff_disable = 'disabled';
            }else{
                $staff_disable = '';
            }

            if($rank['allowavatars'] == 'yes') {
                $avataryes = "selected=\"selected\"";
            } else {
                $avatarno = "selected=\"selected\"";
            }
            ?>

            <tr bgcolor="<?=$altbg2?>" class="tablerow">
            <td align="center"><input type="checkbox" name="delete[<?=$rank[id]?>]" value="1" <?=$staff_disable?> /></td>
            <td align="left"><input type="text" name="title[<?=$rank[id]?>]" value="<?=$rank[title]?>" <?=$staff_disable?>/></td>
            <td><input type="text" name="posts[<?=$rank[id]?>]" value="<?=$rank[posts]?>" <?=$staff_disable?> size="5" /></td>
            <td><input type="text" name="stars[<?=$rank[id]?>]" value="<?=$rank[stars]?>" size="4" /></td>
            <td><select name="allowavatars[<?=$rank[id]?>]">
            <option value="yes" <?=$avataryes?>><?=$lang[texton]?></option>
            <option value="no" <?=$avatarno?>><?=$lang[textoff]?></option>
            </select><input type="hidden" name="id[<?=$rank['id']?>]" value="<?=$rank['id']?>" /></td>
            <td><input type="text" name="avaurl[<?=$rank[id]?>]" value="<?=$rank['avatarrank']?>" size="20" /></td>
            </tr>

            <?
            $avataryes = "";
            $avatarno = "";
        }
        ?>

        <tr bgcolor="<?=$altbg2?>"><td colspan="6"> </td></tr>
        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td colspan="2"><?=$lang['textnewrank']?>&nbsp;&nbsp;<input type="text"
        name="newtitle" /></td>
        <td><input type="text" name="newposts" size="5" /></td>
        <td><input type="text" name="newstars" size="4" /></td>
        <td><select name="newallowavatars"><option value="yes"><?=$lang[texton]?></option>
        <option value="no"><?=$lang['textoff']?></option></select></td>
        <td><input type="text" name="newavaurl" size="20" /></td>
        </tr>

        </table>
        </td></tr></table>
        <center><br /><input type="submit" name="rankssubmit" class="submit" value="<?=$lang[textsubmitchanges]?>" /></center>
        </form>

        </td>
        </tr>

        <?
    }else{
        $query = $db->query("SELECT * FROM $table_ranks");

        while($ranks = $db->fetch_array($query)) {
            /*
            $title = "title$ranks[id]";
            $title = "${$title}";
            $posts = "posts$ranks[id]";
            $posts = "${$posts}";
            $stars = "stars$ranks[id]";
            $stars = "${$stars}";
            $allowavatars = "allowavatars$ranks[id]";
            $allowavatars = "${$allowavatars}";
            $delete = "delete$ranks[id]";
            $delete = "${$delete}";
            $avaurl = "avaurl$ranks[id]";
            $avaurl = "${$avaurl}";
            */
            if($ranks['title'] == 'Super Administrator' || $ranks['title'] == 'Administrator' || $ranks['title'] == 'Super Moderator' || $ranks['title'] == 'Moderator'){
                $title["$ranks[id]"] = $ranks['title'];
            }
        }
        $i=0;
        foreach($id as $key=>$val){
            if($delete[$key] == 1){
                $db->query("DELETE FROM $table_ranks WHERE id='$key'");
                continue;
            }

            $db->query("UPDATE $table_ranks SET title='$title[$key]', posts='$posts[$key]', stars='$stars[$key]', allowavatars='$allowavatars[$key]', avatarrank='$avaurl[$key]' WHERE id='$key'");
        }

        if($newtitle != "") {
            $db->query("INSERT INTO $table_ranks VALUES ('$newtitle', '$newposts', '', '$newstars', '$newallowavatars', '$newavaurl')");
        }

        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[rankingsupdate]</td></tr>";
    }


}elseif($action == "newsletter") {
    if(!$newslettersubmit) {

        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td>
        <br />
        <form method="post" action="cp2.php?action=newsletter">
        <table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr class="header">
        <td colspan="2"><?=$lang[textnewsletter]?></td>
        </tr>


        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td><?=$lang[textsubject]?></td><td><input type="text" name="newssubject" size="80" /></td>
        </tr>
        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td valign="top"><?=$lang[textmessage]?></td><td><textarea cols="80" rows="10" name="newsmessage"></textarea></td>
        </tr>

        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td valign="top"><?=$lang[textsendvia]?></td><td><input type="radio" value="email" checked="checked" name="sendvia" /> <?=$lang[textemail]?><br /><input type="radio" value="u2u" checked="checked" name="sendvia" /> <?=$lang[textu2u]?></td>
        </tr>

        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td valign="top"><?=$lang[textsendto]?></td>
        <td><input type="radio" value="all" checked="checked" name="to" /> <?=$lang[textsendall]?><br />
        <input  type="radio" value="staff" name="to" /> <?=$lang[textsendstaff]?><br />
        <input type="radio" value="admin" name="to" /> <?=$lang[textsendadmin]?><br />
        <input type="radio" value="supermod" name="to" /> <?=$lang[textsendsupermod]?><br />
        <input type="radio" value="mod" name="to" /> <?=$lang[textsendmod]?></td>
        </tr>

        <tr bgcolor="<?=$altbg1?>" class="tablerow">
        <td valign="top">
        <?=$lang['textfaqextra']?>
        </td>
        <td>
        <input type="checkbox" value="yes" checked="checked" name="newscopy" /> <?=$lang['newsreccopy']?><br />

        <select name="wait">
        <option value="0">0</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="150">150</option>
        <option value="200">200</option>
        <option value="250">250</option>
        <option value="500">500</option>
        <option value="1000">1000</option>
        </select>

        <?=$lang['newswait']?><br />
        </td>
        </tr>

        </table>
        </td></tr></table>
        <center><br /><input type="submit" class="submit" name="newslettersubmit" value="<?=$lang[textsubmitchanges]?>" /></center>
        </form>

        </td>
        </tr>

        <?
    }else{
        @set_time_limit(0);

        $newssubject = addslashes($newssubject);
        $newsmessage = addslashes($newsmessage);

        if($newscopy != 'yes') {
            $tome = 'AND NOT username=\''.$xmbuser.'\'';
        }else{
            $tome = '';
        }

        if($to == "all"){
            $query = $db->query("SELECT username, email FROM $table_members WHERE newsletter='yes' $tome ORDER BY uid");
        }elseif($to == "staff"){
            $query = $db->query("SELECT username, email FROM $table_members WHERE (status='Super Administrator' OR status='Administrator' OR status='Super Moderator' OR status='Moderator') $tome ORDER BY uid");
        }elseif($to == "admin"){
            $query = $db->query("SELECT username, email FROM $table_members WHERE (status='Administrator' OR status = 'Super Administrator') $tome ORDER BY uid");
        }elseif($to == "supermod"){
            $query = $db->query("SELECT username, email FROM $table_members WHERE status='Super moderator' $tome ORDER by uid");
        }elseif($to == "mod"){
            $query = $db->query("SELECT username, email FROM $table_members WHERE status='Moderator' ORDER BY uid");
        }

        $_xmbuser = addslashes($xmbuser);

        if($sendvia == "u2u") {
            while ($memnews = $db->fetch_array($query)) {
                $db->query("INSERT INTO $table_u2u VALUES('', '".addslashes($memnews['username'])."', '".$_xmbuser."', 'incoming', '".addslashes($memnews['username'])."', 'inbox', '$newssubject', '$newsmessage', '" . time() . "', 'no', 'yes')");
            }
        }else{
            $newssubject = stripslashes(stripslashes($newssubject));
            $newsmessage = stripslashes(stripslashes($newsmessage));
            $headers[] = "From: $bbname <$adminemail>";
            $headers[] = "X-Sender: <$adminemail>";
            $headers[] = 'X-Mailer: PHP';
            $headers[] = 'X-AntiAbuse: Board servername - '.$bbname;
            $headers[] = 'X-AntiAbuse: Username - '.$xmbuser;
            //$headers[] = 'X-AntiAbuse: User IP - '.$onlineip;
            $headers[] = 'X-Priority: 2';
            $headers[] = "Return-Path: <$adminemail>";
            $headers[] = 'Content-Type: text/plain; charset=ASCII';
            $headers = implode("\r\n", $headers);

            $i = 0;
            @ignore_user_abort(1);
            @set_time_limit(0);
            @ob_implicit_flush(1);

            while($memnews = $db->fetch_array($query)){
                if($i > 0 && $i == $wait){
                    sleep(3);
                    $i = 0;
                } else {
                    $i++;
                }

                mail($memnews['email'], '['.$bbname.'] '.$newssubject, $newsmessage, $headers);
            }
        }
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[newslettersubmit] </td></tr>";
    }
}



elseif($action == "prune") {
    if(!$_POST['prunesubmit']){
        $forumselect = "<select name=\"forumprune\">\n";
        $querygrp = $db->query("SELECT * FROM $table_forums WHERE type='group' OR (type='forum' AND fup='0') ORDER BY displayorder");
        while($group = $db->fetch_array($querygrp)) {
            $forumselect .= "<option value=\"\">$group[name]</option>";
            $forumselect .= "<option value=\"\">--------------------</option>";

            $queryfor = $db->query("SELECT * FROM $table_forums WHERE fup='$group[fid]' AND (type='forum' OR type='sub') ORDER BY displayorder");
            while($forum = $db->fetch_array($queryfor)) {
                $forumselect .= "<option value=\"$forum[fid]\"> &nbsp; &raquo; $forum[name]</option>";

                $querysub = $db->query("SELECT * FROM $table_forums WHERE fup='$forum[fid]' AND type='sub' ORDER BY displayorder");
                while($sub = $db->fetch_array($querysub)) {
                    $forumselect .= "<option value=\"$sub[fid]\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &raquo; $sub[name]</option>";
                }
            }
            $forumselect .= "<option value=\"\"> </option>";
        }
        $forumselect .= "</select>";

        $prunefid = "<input type=text name=fid size=7 />";
        ?>

        <tr bgcolor="<?=$altbg2?>">
        <td>
        <br />
        <form method="post" action="cp2.php?action=prune">
        <table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header" colspan="2"><?=$lang[textprune]?></td>
        </tr>

        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[prunewhere]?></td>
        <td align="right" bgcolor="<?=$altbg2?>"><input type="text" name="days" size="7" /></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[prunein]?></td>
        <td align="right" bgcolor="<?=$altbg2?>"><?=$forumselect?></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[prune_fid]?></td>
        <td align="right" bgcolor="<?=$altbg2?>"><?=$prunefid?></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang['prune_types']?></td>
        <td align="right" bgcolor="<?=$altbg2?>">
            <select multiple name="prunetype[]">
                <option value="normal" selected><?=$lang['prune_normal']?></option>
                <option value="closed" selected><?=$lang['prune_closed']?></option>
                <option value="topped"><?=$lang['prune_topped']?></option>
            </select>
        </td>
        </tr>

        </table>
        </td></tr></table>
        <center><br /><input class="submit" type="submit" name="prunesubmit" value="<?=$lang['textsubmitchanges']?>" /></center>
        </form>

        </td>
        </tr>

        <?
    }else{
        $prunedate = time() - (86400*$days);

        $normal = in_array('normal', $prunetype);

        if(!in_array('closed', $prunetype)){
            $restrict[] = "closed != 'yes'";
        }elseif(in_array('closed', $prunetype) && !$normal){
            $restrict[] = "closed = 'yes'";
        }
        if(!in_array('topped', $prunetype)){
            $restrict[] = "topped != '1'";
        }elseif(in_array('topped', $prunetype) && !$normal){
            $restrict[] = "topped = '1'";
        }

        $kill = false;
        if(!$normal){
            $restricted = count($restrict);
            if($restricted == 0){
                $kill = true;
            }else{
                $restrict = @implode(' OR ', $restrict);
            }
        }else{
            $restrict = @implode(' OR ', $restrict);
        }

        if($restrict != ''){
            $restrict = 'AND ('.$restrict.')';
        }

        if(!$kill){
            if($fid != 0 && $fid != ''){
                $querythread = $db->query("SELECT * FROM $table_threads WHERE lastpost <= '$prunedate' AND fid='$fid' $restrict");
                $prunedate = '';
                $forumprune = '';
            }else{
                if($forumprune == $lang['textall']) {
                    $querythread = $db->query("SELECT * FROM $table_threads WHERE lastpost <= '$prunedate' $restrict");
                } else {
                    $querythread = $db->query("SELECT * FROM $table_threads WHERE lastpost <= '$prunedate' AND fid='$forumprune' $restrict");
                }
            }


            while($thread = $db->fetch_array($querythread)) {
                $db->query("DELETE FROM $table_threads WHERE tid='$thread[tid]'");
                $db->query("UPDATE $table_forums SET posts=posts-1, threads=threads-1 WHERE fid='$thread[fid]'");
                $db->query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$thread[author]'");

                $querypost = $db->query("SELECT * FROM $table_posts WHERE tid='$thread[tid]'");
                while($post = $db->fetch_array($querypost)) {
                    $db->query("DELETE FROM $table_posts WHERE pid='$post[pid]'");
                    $db->query("UPDATE $table_forums SET posts=posts-1 WHERE fid='$post[fid]'");
                    $db->query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$post[author]'");
                }
            }
        }

        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[forumpruned]</td></tr>";
    }
}


elseif($action == "templates") {
    if(!isset($edit) && !isset($editsubmit) && !isset($delete) && !isset($deletesubmit) && !isset($new) && !isset($restore) && !isset($restoresubmit)) {
        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=templates">
        <table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header"><?=$lang['templates']?></td>
        </tr>
        <tr>
        <td bgcolor="<?=$altbg2?>" class="tablerow" align="left">
        <INPUT TYPE="text" NAME="newtemplatename" size="30" maxlength="50">&nbsp;&nbsp;
        <input type="submit" class="submit" name="new" value="<?=$lang['newtemplate']?>">
        </td></tr>

        <tr>
        <td bgcolor="<?=$altbg1?>" class="tablerow" align="left">

        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td bgcolor="<?=$altbg1?>">

        <?
        $query = $db->query("SELECT * FROM $table_templates ORDER BY name");
        echo "<select name=\"tid\"><option value=\"default\">$lang[selecttemplate]</option>";
        while($template = $db->fetch_array($query)) {
            if(!empty($template['name'])){
                echo "<option value=\"$template[id]\">$template[name]</option>\r\n";
            }
        }
        echo "</select>&nbsp;&nbsp;";
        ?>

        <input type="submit" class="submit"name="edit" value="<?=$lang['textedit']?>">&nbsp;
        <input type="submit" class="submit"name="delete" value="<?=$lang['deletebutton']?>">
        </td>
        <td bgcolor="<?=$altbg1?>" align="right"><input type="submit" class="submit" name="restore" value="<?=$lang['textrestoredeftemps']?>">&nbsp;
        <input type="submit" class="submit" name="download" value="<?=$lang['textdownloadtemps']?>"></td>
        </tr>
        </table>
        </table>
        </td></tr></table>
        </form>
        </td>
        </tr>

        <?
    }

    if(isset($restore)) {
        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=templates">
        <table cellspacing="0" cellpadding="0" border="0" width="300" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header"><?=$lang['templates']?></td>
        </tr>
        <tr>
        <td bgcolor="<?=$altbg1?>" class="tablerow" align="center">
        <?=$lang['templaterestoreconfirm']?><p>
        <center><input type="submit" class="submit" name="restoresubmit" value="<?=$lang['textyes']?>" /></center>
        </td>
        </tr>

        </table>
        </td></tr></table>
        </form>

        </td>
        </tr>
        <?
    }

    if(isset($restoresubmit)) {
        if(!file_exists('./templates.xmb')){
            $header = '';
            $message = $lang['no_templates'];

            end_time();

            eval('$error = "'.template('error').'";');
            eval('$footer = "'.template('footer').'";');

            echo $error;
            echo $footer;

            exit();
        }
        $db->query("TRUNCATE $table_templates");

        $filesize=filesize('templates.xmb');
        $fp=fopen('templates.xmb','r');
        $templatesfile=fread($fp,$filesize);
        fclose($fp);

        $templates = explode("|#*XMB TEMPLATE FILE*#|", $templatesfile);
        while (list($key,$val) = each($templates)) {
            $template = explode("|#*XMB TEMPLATE*#|", $val);
            $template[1] = addslashes($template[1]);
            $db->query("INSERT INTO $table_templates VALUES ('', '".addslashes($template[0])."', '".addslashes($template[1])."')");
        }

        $db->query("DELETE FROM $table_templates WHERE name=''");
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[templatesrestoredone]</td></tr>";
    }

    if(isset($edit) && !isset($editsubmit)) {
        if($tid == "default") {
            echo "<td align=\"center\"><font class=\"subject\">$lang[selecttemplate]</td>";
            redirect('cp2.php?action=templates');
            end_time();
            eval("\$footer = \"".template("footer")."\";");
            echo $footer;
            exit();
        }

        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=templates&amp;tid=<?=$tid?>">
        <table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header"><?=$lang['templates']?></td>
        </tr>

        <?
        $query = $db->query("SELECT * FROM $table_templates WHERE id='$tid' ORDER BY name");
        $template = $db->fetch_array($query);
        $template['template'] = stripslashes($template['template']);
        $template['template'] = htmlspecialchars($template['template']);
        ?>

        <tr>
        <td bgcolor="<?=$altbg1?>" class="ctrtablerow" align="center">
        <b><?=$template['name']?></b><br />
        <textarea cols="70" rows="20" name="templatenew"><?=$template['template']?></textarea>
        <center><input type="submit" name="editsubmit" class="submit" value="<?=$lang['textsubmitchanges']?>" /></center>
        </td>
        </tr>

        </table>
        </td></tr></table>
        </form>

        </td>
        </tr>

        <?
    }

    if(isset($editsubmit)) {
        $templatenew = addslashes($templatenew);
        if($tid == "new") {
            if(empty($namenew)){
                echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">Template Name can not be empty!!</td></tr></table></td></tr></table>";

                end_time();
                eval("\$footer = \"".template("footer")."\";");
                echo $footer;

                exit();
            }else{
                $check = $db->query("SELECT name FROM $table_templates WHERE name = '$namenew'");
                if($check && $db->num_rows($check) != 0){
                    echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">Template Name already exists!!</td></tr></table></td></tr></table>";

                    end_time();
                    eval("\$footer = \"".template("footer")."\";");
                    echo $footer;

                    exit();
                }else{
                    $db->query("INSERT INTO $table_templates VALUES('', '$namenew', '$templatenew')");
                }
            }
        } else {
            $db->query("UPDATE $table_templates SET template='$templatenew' WHERE id='$tid'");
        }
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[templatesupdate]</td></tr>";
    }

    if(isset($delete)) {
        if($tid == "default") {
            echo "<td align=\"center\"><font class=\"subject\">$lang[selecttemplate]</td>";
            redirect('cp2.php?action=templates');
            end_time();
            eval("\$footer = \"".template("footer")."\";");
            echo $footer;
            exit();
        }

        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=templates&amp;tid=<?=$tid?>">
        <table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header"><?=$lang['templates']?></td>
        </tr>
        <tr>
        <td bgcolor="<?=$altbg1?>" class="tablerow" align="center">
        <?=$lang['templatedelconfirm']?>
        </td>
        </tr>

        </table>
        </td></tr></table>
        <center><input type="submit" class="submit" name="deletesubmit" value="<?=$lang['textyes']?>" /></center>
        </form>

        </td>
        </tr>

        <?
    }

    if(isset($deletesubmit)) {
        $db->query("DELETE FROM $table_templates WHERE id='$tid'");
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[templatesdelete]</td></tr>";
    }

    if(isset($new)) {

        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=templates&amp;tid=new">
        <table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header"><?=$lang['templates']?></td>
        </tr>
        <tr>
        <td bgcolor="<?=$altbg1?>" class="tablerow" align="center">
        <?=$lang['templatename']?> <input type="text" name="namenew" size="30" value="<?=$newtemplatename?>" /><br />
        <textarea cols="70" rows="20" name="templatenew"></textarea>
        <center><input type="submit" name="editsubmit" value="<?=$lang['textsubmitchanges']?>" class="submit" /></center>
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

elseif($action == "attachments") {
    if(!$attachsubmit && !$searchsubmit) {
        $forumselect = "<select name=\"forumprune\">\n";
        $forumselect .= "<option value=\"$lang[textall]\">$lang[textall]</option>\n";
        $querycat = $db->query("SELECT * FROM $table_forums WHERE type='forum' OR type='sub' ORDER BY displayorder");
        while($forum = $db->fetch_array($querycat)) {
            $forumselect .= "<option value=\"$forum[fid]\">$forum[name]</option>\n";
        }
        $forumselect .= "</select>";
        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=attachments">
        <table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header" colspan="2"><?=$lang[textsearch]?></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[attachmanwherename]?></td>
        <td bgcolor="<?=$altbg2?>"><input type="text" name="filename" size="30" /></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[attachmanwhereauthor]?></td>
        <td bgcolor="<?=$altbg2?>"><input type="text" name="author" size="40" /></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[attachmanwhereforum]?></td>
        <td bgcolor="<?=$altbg2?>"><?=$forumselect?></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[attachmanwheresizesmaller]?></td>
        <td bgcolor="<?=$altbg2?>"><input type="text" name="sizeless" size="20" /></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[attachmanwheresizegreater]?></td>
        <td bgcolor="<?=$altbg2?>"><input type="text" name="sizemore" size="20" /></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[attachmanwheredlcountsmaller]?></td>
        <td bgcolor="<?=$altbg2?>"><input type="text" name="dlcountless" size="20" /></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[attachmanwheredlcountgreater]?></td>
        <td bgcolor="<?=$altbg2?>"><input type="text" name="dlcountmore" size="20" /></td>
        </tr>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$lang[attachmanwheredaysold]?></td>
        <td bgcolor="<?=$altbg2?>"><input type="text" name="daysold" size="20" /></td>
        </tr>
        </table>
        </td></tr></table><br />
        <center><input type="submit" name="searchsubmit" class="submit" value="<?=$lang[textsubmitchanges]?>" /></center>
        </form>

        </td>
        </tr>
        <?
    }
    if($searchsubmit) {
        ?>
        <tr bgcolor="<?=$altbg2?>">
        <td align="center">
        <br />
        <form method="post" action="cp2.php?action=attachments">
        <table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
        <tr><td bgcolor="<?=$bordercolor?>">

        <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

        <tr>
        <td class="header" colspan="6"><?=$lang[textattachsearchresults]?></td>
        </tr>
        <tr>
        <td class="header" width="4%" align="center">?</td>
        <td class="header" width="25%"><?=$lang[textfilename]?></td>
        <td class="header" width="29%"><?=$lang[textauthor]?></td>
        <td class="header" width="27%"><?=$lang[textinthread]?></td>
        <td class="header" width="10%"><?=$lang[textfilesize]?></td>
        <td class="header" width="5%"><?=$lang[textdownloads]?></td>


        </tr>
        <?
        if($forumprune != "" && $forumprune != "$lang[textall]") {
            $queryforum = "AND p.fid='$forumprune' ";
        }
        if($daysold != "") {
            $datethen = time() - (86400*$daysold);
            $querydate = "AND p.dateline <= '$datethen' ";
        }
        if($author != "") {
            $queryauthor = "AND p.author = '$author' ";
        }
        if($filename != "") {
            $queryname = "AND a.filename LIKE '%$filename%' ";
        }
        if($sizeless != "") {
            $querysizeless = "AND a.filesize < '$sizeless' ";
        }
        if($sizemore != "") {
            $querysizemore = "AND a.filesize > '$sizemore' ";
        }
        if($dlcountless != "") {
            $querydlcountless = "AND a.downloads < '$dlcountless' ";
        }
        if($dlcountmore != "") {
            $querydlcountmore = "AND a.downloads > '$dlcountmore' ";
        }
        $query = $db->query("SELECT a.*, p.*, t.tid, t.subject AS tsubject, f.name AS fname FROM $table_attachments a, $table_posts p, $table_threads t, $table_forums f WHERE a.pid=p.pid AND t.tid=a.tid AND f.fid=p.fid $queryforum $querydate $queryauthor $queryname $querysizeless $querysizemore");
            while($attachment = $db->fetch_array($query)) {
            $attachsize = strlen($attachment[attachment]);
            if($attachsize >= 1073741824) { $attachsize = round($attachsize / 1073741824 * 100) / 100 . "gb"; }
            elseif($attachsize >= 1048576) { $attachsize = round($attachsize / 1048576 * 100) / 100 . "mb"; }
            elseif($attachsize >= 1024) { $attachsize = round($attachsize / 1024 * 100) / 100 . "kb"; }
            else { $attachsize = $attachsize . "b"; }
            $attachment[tsubject] = stripslashes($attachment[tsubject]);
            $attachment[fname] = stripslashes($attachment[fname]);
            $attachment[filename] = stripslashes($attachment[filename]);
            ?>
            <tr>
            <td bgcolor="<?=$altbg1?>" class="tablerow" align="center" valign="middle"><a href="cp2.php?action=delete_attachment&amp;aid=<?=$attachment[aid]?>">Delete</a>
            <td bgcolor="<?=$altbg2?>" class="tablerow" valign="top"><input type="text" name="filename<?=$attachment[aid]?>" value="<?=$attachment[filename]?>"><br /><span class="smalltxt"><a href="viewthread.php?action=attachment&amp;tid=<?=$attachment[tid]?>&amp;pid=<?=$attachment[pid]?>"><?=$lang[textdownload]?></a></td>
            <td bgcolor="<?=$altbg2?>" class="tablerow" valign="top"><?=$attachment[author]?></td>
            <td bgcolor="<?=$altbg2?>" class="tablerow" valign="top"><a href="viewthread.php?tid=<?=$attachment[tid]?>"><?=$attachment[tsubject]?></a><br /><span class="smalltxt"><?=$lang[textinforum]?> <a href="forumdisplay.php?fid=<?=$attachment[fid]?>"><?=$attachment[fname]?></a></span></td>
            <td bgcolor="<?=$altbg2?>" class="tablerow" valign="top" align="center"><?=$attachsize?></td>
            <td bgcolor="<?=$altbg2?>" class="tablerow" valign="top" align="center"><?=$attachment[downloads]?></td>
            </tr>
            <?

        }
        ?>
        </table>
        </td></tr></table><br />
        <center><input class="submit" type="submit" name="deletesubmit" value="<?=$lang[textsubmitchanges]?>" /></center>
        <input type="hidden" name="filename" value="<?=$filename?>">
        <input type="hidden" name="author" value="<?=$author?>">
        <input type="hidden" name="forumprune" value="<?=$forumprune?>">
        <input type="hidden" name="sizeless" value="<?=$sizeless?>">
        <input type="hidden" name="sizemore" value="<?=$sizemore?>">
        <input type="hidden" name="dlcountless" value="<?=$dlcountless?>">
        <input type="hidden" name="dlcountmore" value="<?=$dlcountmore?>">
        <input type="hidden" name="daysold" value="<?=$daysold?>">
        </form>

        </td>
        </tr>
        <?
    }
    if($deletesubmit) {
        if($forumprune != "" && $forumprune != "$lang[textall]") {
            $queryforum = "AND p.fid='$forumprune' ";
        }
        if($daysold != "") {
            $datethen = time() - (86400*$daysold);
            $querydate = "AND p.dateline <= '$datethen' ";
        }
        if($author != "") {
            $queryauthor = "AND p.author = '$author' ";
        }
        if($filename != "") {
            $queryname = "AND a.filename LIKE '%$filename%' ";
        }
        if($sizeless != "") {
            $querysizeless = "AND a.filesize < '$sizeless' ";
        }
        if($sizemore != "") {
            $querysizemore = "AND a.filesize > '$sizemore' ";
        }
        if($dlcountless != "") {
            $querydlcountless = "AND a.downloads < '$dlcountless' ";
        }
        if($dlcountmore != "") {
            $querydlcountmore = "AND a.downloads > '$dlcountmore' ";
        }

        $query = $db->query("SELECT a.*, p.*, t.tid, t.subject AS tsubject, f.name AS fname FROM $table_attachments a, $table_posts p, $table_threads t, $table_forums f WHERE a.pid=p.pid AND t.tid=a.tid AND f.fid=p.fid $queryforum $querydate $queryauthor $queryname $querysizeless $querysizemore");
        while($attachment = $db->fetch_array($query)) {
            $delete = "delete$attachment[aid]";
            $delete = "${$delete}";
            $afilename = "filename$attachment[aid]";
            $afilename = "${$afilename}";
            $self[status] = "status$forum[fid]";
            $self[status] = "${$self[status]}";
            $delete = "delete$forum[fid]";
            $delete = "${$delete}";
            $moveto = "moveto$forum[fid]";
            $moveto = "${$moveto}";
//          if($delete != "") {
//              $db->query("DELETE FROM $table_attachments WHERE aid='$attachment[aid]'");
//          }
            if($attachment[filename] != $afilename) {
                $db->query("UPDATE $table_attachments SET filename='$afilename' WHERE aid='$attachment[aid]'");
            }
        }
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[textattachmentsupdate]</td></tr>";
    }
}

elseif($action == "cplog") {
    ?>
    <tr bgcolor="<?=$altbg2?>">
    <td align="center">
    <br />
    <table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
    <tr>
    <td bgcolor="<?=$bordercolor?>">
    <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
    <tr class="header">
    <td>Username:</td>
    <td>Time:</td>
    <td>URL:</td>
    </tr>

    <?php
    $logcontents = file("./cplogfile.php");
    $total = count($logcontents);

    if(!isset($page) || $page < 1){
        $page = 1;
    }

    $start = (($page-1)*100)+2;// +2 to skip the first 1 lines (<?php and $Id tag)

    $show = ($page*100);
    $showreal = $show
    ;
    if($total < $show){
        $show = ($total-1);
    }


    for($i = $start; $i <= $show; $i++) {
        $recordinfo = explode("|#||#|", $logcontents[$i]);
        $date = gmdate($dateformat, $recordinfo[2]);
        $time = gmdate($timecode, $recordinfo[2]);
        $url = "<a href=\"$recordinfo[3]\" target=\"_blank\">$recordinfo[3]</a>";
        ?>
        <tr>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$recordinfo[0]?><br /><span class="smalltxt">IP: <?=$recordinfo[1]?></span></td>
        <td class="tablerow" bgcolor="<?=$altbg2?>"><?=$date?> at <?=$time?></td>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$url?></td>
        </tr>
        <?
    }

    if($total > $showreal){
        if($page == 1){
            $prevpage = '';
        }else{
            $prevpage = '<a href="./cp2.php?action=cplog&amp;page='.($page-1).'"> &laquo; Previous Page</a>';
        }

        $nextpage = '<a href="./cp2.php?action=cplog&amp;page='.($page+1).'">Next Page &raquo;</a>';

        if($prevpage == '' || $nextpage == ''){
            $random_var = '';
        }else{
            $random_var = '-';
        }


        $last = ceil($total/100);
        if($last > $page) {
            $lastpage = '<a href="./cp2.php?action=cplog&amp;page='.$last.'">&nbsp;&raquo;&raquo;</a>';
        }

        $first = 1;
        if($page > $first) {
            $firstpage = '<a href="./cp2.php?action=cplog&amp;page='.$first.'">&nbsp;&laquo;&laquo;</a>';
        }


        ?>
        <tr class="header">
        <td colspan="4"><?=$firstpage?> <?=$prevpage?> <?=$random_var?> <?=$nextpage?> <?=$lastpage?></td>
        </tr>

        <?php
    }else{
        if($page == 1){
            $prevpage = '';
        }else{
            $prevpage = '<a href="./cp2.php?action=cplog&amp;page='.($page-1).'">&laquo; Previous Page</a>';
        }
        $first = 1;
        if($page > $first) {
            $firstpage = '<a href="./cp2.php?action=cplog&amp;page='.$first.'">&nbsp;&laquo;&laquo;</a>';
        }

        ?>
        <tr class="header">
        <td colspan="4"><?=$firstpage?> <?=$prevpage?> <?=$random_var?> <?=$nextpage?></td>
        </tr>
        <?php
    }

    if($total == 0){
        ?>
        <tr class="header">
        <td colspan="4">No logs present</td>
        </tr>
        <?php
    }

    ?>
    </table>
    </td></tr></table>
    </td>
    </tr>
    <?
}

elseif($action == "modlog") {
    ?>
    <tr bgcolor="<?=$altbg2?>">
    <td align="center">
    <br />
    <table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
    <tr>
    <td bgcolor="<?=$bordercolor?>">
    <table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
    <tr class="header">
    <td>Username:</td>
    <td>Time:</td>
    <td>URL:</td>
    <td>Action:</td>
    </tr>

    <?php
    $count = $db->result($db->query("SELECT count(*) FROM $table_logs"), 0);

    if(!isset($page) || $page < 1){
        $page = 1;
    }

    $old = (($page-1)*100);
    $current = ($page*100);

    $query = $db->query("SELECT l.*, t.subject FROM $table_logs l LEFT JOIN $table_threads t ON l.tid=t.tid ORDER BY date ASC LIMIT $old, 100");

    while($recordinfo = $db->fetch_array($query)){
        $date = gmdate($dateformat, $recordinfo['date']);
        $time = gmdate($timecode, $recordinfo['date']);
        if($recordinfo['tid'] > 0 && $recordinfo['action'] != 'delete' && trim($recordinfo['subject']) != ''){
            $url = "<a href=\"./viewthread.php?tid=$recordinfo[tid]\" target=\"_blank\">$recordinfo[subject]</a>";
        }elseif($recordinfo['action'] == 'delete'){
            $recordinfo['action'] = '<b>'.$recordinfo['action'].'</b>';
            $url = '&nbsp;';
        }else{
            $url = 'tid='.$recordinfo['tid'].' - fid:'.$recordinfo['fid'];
        }
        ?>
        <tr>

        <td class="tablerow" bgcolor="<?=$altbg1?>"<a href="./member.php?action=viewpro&amp;member=<?=$recordinfo['username']?>"><?=$recordinfo['username']?></a></td>
        <td class="tablerow" bgcolor="<?=$altbg2?>"><?=$date?> at <?=$time?></td>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$url?></td>
        <td class="tablerow" bgcolor="<?=$altbg1?>"><?=$recordinfo['action']?></td>
        </tr>
        <?
    }

    if($count > $current){

        $page = $current/100;

        if($page == 1){
            $prevpage = '';
        }else{
            $prevpage = '<a href="./cp2.php?action=modlog&amp;page='.($page-1).'">&laquo; Previous Page</a>';
        }

        $nextpage = '<a href="./cp2.php?action=modlog&amp;page='.($page+1).'">Next Page &raquo;</a>';

        if($prevpage == '' || $nextpage == ''){
            $random_var = '';
        }else{
            $random_var = '-';
        }

        $last = ceil($count/100);
        if($last > $page) {
            $lastpage = '<a href="./cp2.php?action=modlog&amp;page='.$last.'">&nbsp;&raquo;&raquo;</a>';
        }

        $first = 1;
        if($page > $first) {
            $firstpage = '<a href="./cp2.php?action=modlog&amp;page='.$first.'">&nbsp;&laquo;&laquo;</a>';
        }


        ?>
        <tr class="header">
        <td colspan="4"><?=$firstpage?> <?=$prevpage?> <?=$random_var?> <?=$nextpage?> <?=$lastpage?></td>
        </tr>

        <?php
    }else{
        if($page == 1){
            $prevpage = '';
        }else{
            $prevpage = '<a href="./cp2.php?action=modlog&amp;page='.($page-1).'">&laquo; Previous Page</a>';
        }

        $first = 1;
        if($page > $first) {
            $firstpage = '<a href="./cp2.php?action=modlog&amp;page='.$first.'">&nbsp;&laquo;&laquo;</a>';
        }

        ?>
        <tr class="header">
        <td colspan="4"><?=$firstpage?> <?=$prevpage?> <?=$random_var?> <?=$nextpage?></td>
        </tr>
        <?php
    }

    if($count == 0){
        ?>
        <tr class="header">
        <td colspan="4">No logs present</td>
        </tr>
        <?php
    }
    ?>
    </table>
    </td></tr></table>
    </td>
    </tr>
    <?php
}

elseif($action == "delete_attachment") {
    $db->query("DELETE FROM $table_attachments WHERE aid='$aid'");
    echo "<p align=\"center\">Deleted ...</br>";
}

elseif($action == 'restore_attachments') {
    $i = 0;
    if(!is_readable('./attachments')){
        echo '<tr bgcolor="'.$altbg2.'" class="tablerow"><td align="center">attachments directory ("./attachments") should be chmodded to 777 so XMB can write to it.</td></tr>';
    }else{

        $trans = array( 0=> 'aid',
                1=> 'tid',
                2=> 'pid',
                3=> 'filename',
                4=> 'filetype',
                5=> 'filesize',
                6=> 'downloads'
                );

        $mainstream = fopen('./attachments/index.inf', 'r');
        while(($line = fgets($mainstream)) !== false){
            $attachment = array();

            $attachment = array_keys2keys(explode('//||//', $line), $trans);

            $stream = fopen('./attachments/'.$attachment['aid'].'.xmb', 'r');
            $attachment['attachment'] = fread($stream, filesize('./attachments/'.$attachment['aid'].'.xmb'));
            fclose($stream);

            $db->query("INSERT INTO $table_attachments VALUES ('$attachment[aid]', '$attachment[tid]', '$attachment[pid]', '$attachment[filename]', '$attachment[filetype]', '$attachment[filesize]', '$attachment[attachment]', '$attachment[downloads]')");

            $i++;
        }

        fclose($mainstream);

        echo '<tr bgcolor="'.$altbg2.'" class="tablerow"><td align="center">'.$i.' attachments stored</td></tr>';
    }
}

echo "</table></td></tr></table>";

end_time();

eval("\$footer = \"".template("footer")."\";");
echo $footer;

function readFileAsINI($filename) {
// Function taken from a phpBB hack with permission
    $lines = file($filename);
    foreach($lines as $line_num => $line){
        $temp = explode("=",$line);
        if($temp[0] == 'dummy'){
            $key = 'dummy';
            $val = 'NULL';
        }else{
            $key = $temp[0];
            $val = $temp[1];
        }

        $key = trim($key);
        $val = trim($val);

        $thefile[$key] = $val;
    }
    return $thefile;
}
?>