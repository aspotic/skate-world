<?php
/* $Id: tools.php,v 1.2 2004/04/15 15:16:37 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require "./header.php";
loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','header','footer','css');
eval("\$css = \"".template('css')."\";");

$navigation .= "&raquo; <a href=\"cp.php\">Administration Panel</a>";
eval("\$header = \"".template("header")."\";");
echo $header;

if(!$xmbuser || !$xmbpw) {
    $xmbuser = "";
    $xmbpw = "";
    $self[status] = "";
}

if($self[status] != "Administrator" && $self[status] !="Super Administrator") {
    eval("\$notadmin = \"".template("error_nologinsession")."\";");
    echo $notadmin;
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    exit();
}

$cploc = $HTTP_SERVER_VARS["REQUEST_URI"];

if(getenv(HTTP_CLIENT_IP)) {
    $ip = getenv(HTTP_CLIENT_IP);
} elseif(getenv(HTTP_X_FORWARDED_FOR)) {
    $ip = getenv(HTTP_X_FORWARDED_FOR);
} else {
    $ip = getenv(REMOTE_ADDR);
}

$time = time();
$string = "$xmbuser|#||#|$ip|#||#|$time|#||#|$_SERVER[REQUEST_URI]\n";

@chmod('./cplogfile.php', 0777);
$filehandle = @fopen('./cplogfile.php','a');
if(!$filehandle){
    echo('<center><b>Wrong File permissions set. Please CHMOD the <i>cplogfile.php</i> to <i>777</i></b></center>');
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
&raquo; <a href="portal_admin.php">Portal Cpanel</a><br />
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
if(!$action) {
}

if($action == "fixftotals") {
    $fquery = $db->query("SELECT fid FROM $table_forums WHERE type='forum'");
    while($forum = $db->fetch_array($fquery)) {
        $threadnum    = 0;
        $postnum    = 0;
        $sub_threadnum    = 0;
        $sub_postnum    = 0;
        $squery        = '';
        $stquery    = '';
        $spquery    = '';
        $ftquery    = '';
        $fpquery    = '';

        // Get all posts and threads from the subforums
        $squery = $db->query("SELECT fid FROM $table_forums WHERE fup='$forum[fid]' AND type='sub'");

        while($sub = $db->fetch_array($squery)){
            $stquery = $db->query("SELECT COUNT(*) FROM $table_threads WHERE fid='$sub[fid]'");
            $sub_threadnum = $db->result($stquery, 0);

            $spquery = $db->query("SELECT COUNT(*) FROM $table_posts WHERE fid='$sub[fid]'");
            $sub_postnum = $db->result($spquery, 0);

            $db->query("UPDATE $table_forums SET threads='$sub_threadnum', posts='$sub_postnum' WHERE fid='$sub[fid]'");
            $threadnum += $sub_threadnum;
            $postnum += $sub_postnum;
        }

        // Get all threads and posts for the forum itself
        $ftquery = $db->query("SELECT COUNT(*) FROM $table_threads WHERE fid='$forum[fid]'");
        $threadnum += $db->result($ftquery, 0);

        $fpquery = $db->query("SELECT COUNT(*) FROM $table_posts WHERE fid='$forum[fid]'");
        $postnum += $db->result($fpquery, 0);

        // Update it all
        $db->query("UPDATE $table_forums SET threads='$threadnum', posts='$postnum' WHERE fid='$forum[fid]'");
    }

    $navigation .= " &raquo; ".$lang['tools'];
    echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[tool_completed] Fixed Forum Totals</td></tr></table></table>";
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    exit();
}


if($action == "fixttotals") {
    $queryt = $db->query("SELECT * FROM $table_threads");
    while($threads = $db->fetch_array($queryt)) {
        $query = $db->query("SELECT COUNT(*) FROM $table_posts WHERE tid='$threads[tid]'");
        $replynum = $db->result($query, 0) -1;
        $db->query("UPDATE $table_threads SET replies='$replynum' WHERE tid='$threads[tid]'");
    }

    $navigation .= " &raquo; ".$lang['tools'];
    echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[tool_completed] Fixed Thread Totals</td></tr></table></table>";
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    exit();
}


if($action == "fixmposts") {
    $queryt = $db->query("SELECT username FROM $table_members");
    while($mem = $db->fetch_array($queryt)) {
        $mem[username] = stripslashes($mem[username]);
        $mem[username] = addslashes($mem[username]);

        $query = $db->query("SELECT COUNT(pid) FROM $table_posts WHERE author='$mem[username]'");
        $postsnum = $db->result($query, 0);
        $db->query("UPDATE $table_members SET postnum='$postsnum' WHERE username='$mem[username]'");
    }

    $navigation .= " &raquo; ".$lang['tools'];
    echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[tool_completed] - Fixed Member Post-numbers</td></tr></table></table>";
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    exit();
}

if($action == "fixlastposts"){
    $fquery = $db->query("SELECT fid FROM $table_forums WHERE fup = '' OR fup = '0'") or die($db->error());
    while($top = $db->fetch_array($fquery)){
        $dateline = 0;
        $author = '';

        $q = $db->query("SELECT fid FROM $table_forums WHERE fup = '$top[fid]'");
        while($sub = $db->fetch_array($q)){
            $qt = $db->query("SELECT author, dateline FROM $table_posts WHERE fid = '$sub[fid]' ORDER BY pid DESC") or die($db->error());
            if($db->num_rows($qt) > 0){
                $stuff = $db->fetch_array($qt);
                $db->query("UPDATE $table_forums SET lastpost='".$stuff[dateline].'|'.$stuff[author]."' WHERE fid = '$sub[fid]'") or die($db->error());
                if($dateline < $stuff['dateline']){
                    $dateline = $stuff['dateline'];
                    $author = $stuff['author'];
                }

                $stuff = array();
            }
            $tlq = $db->query("SELECT tid FROM $table_threads WHERE fid = '$sub[fid]'") or die($db->error());
            while($thread = $db->fetch_array($tlq)){
                $qt2 = $db->query("SELECT author, dateline FROM $table_posts WHERE tid = '$thread[tid]' ORDER BY pid DESC") or die($db->error());
                $stuff = $db->fetch_array($qt2);
                $db->query("UPDATE $table_threads SET lastpost='".$stuff[dateline].'|'.$stuff[author]."' WHERE tid = '$thread[tid]'") or die($db->error());

                $qt2 = 0;
                $stuff = array();
            }

        }
        $qt4 = $db->query("SELECT author, dateline FROM $table_posts WHERE fid = '$top[fid]' ORDER BY pid DESC") or die($db->error());
        if($db->num_rows($qt4) > 0){
            $stuff = $db->fetch_array($qt4);
            if($dateline < $stuff['dateline']){
                $dateline = $stuff['dateline'];
                $author = $stuff['author'];
            }

            $stuff = array();
        }

        $tlq2 = $db->query("SELECT tid FROM $table_threads WHERE fid = '$top[fid]'") or die($db->error());
        while($thread = $db->fetch_array($tlq2)){
            $qt3 = $db->query("SELECT author, dateline FROM $table_posts WHERE tid = '$thread[tid]' ORDER BY pid DESC") or die($db->error());
            $stuff = $db->fetch_array($qt3);
            $db->query("UPDATE $table_threads SET lastpost='".$stuff['dateline'].'|'.$stuff['author']."' WHERE tid = '$thread[tid]'") or die($db->error());

            $qt3 = 0;
            $stuff = array();
        }

        if($dateline == 0){
            $lastpost = '';
        }else{
            $lastpost = $dateline.'|'.$author;
        }
        $db->query("UPDATE $table_forums SET lastpost='$lastpost' WHERE fid = '$top[fid]'") or die($db->error());

        $lastpost = '';
    }

    $navigation .= " &raquo; ".$lang['tools'];
    echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[tool_completed] - Fixed Last Posts</td></tr></table></table>";
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    exit();
}


if($action == "updatemoods") {
    $db->query("UPDATE $table_members SET mood='No Mood'");
    $navigation .= " &raquo; ".$lang['tools'];
    echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[tool_completed] Moods Updated</td></tr></table></table>";
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    exit();
}


if($action == "u2udump") {
    if(!isset($yessubmit)) {
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">".$lang['u2udump_confirm']."<br /><form action=\"tools.php?action=u2udump\" method=\"post\"><input type=\"submit\" name=\"yessubmit\" value=\"".$lang['textyes']."\" /> - <input type=\"submit\" name=\"yessubmit\" value=\"".$lang['textno']."\" /></form></td></tr></table></table>";
    } elseif($lang['textyes'] == $yessubmit) {
        $db->query("DELETE FROM $table_u2u");
        $navigation .= " &raquo; ".$lang['tools'];
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[tool_completed] U2Us Cleared</td></tr></table></table>";
        end_time();
        eval("\$footer = \"".template("footer")."\";");
        echo $footer;
        exit();
    } else {
        redirect('./cp.php');
    }
}

if($action == "whosonlinedump") {
    if(!isset($yessubmit)) {
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">".$lang['whoodump_confirm']."<br /><form action=\"tools.php?action=whosonlinedump\" method=\"post\"><input type=\"submit\" name=\"yessubmit\" value=\"".$lang['textyes']."\" /> - <input type=\"submit\" name=\"yessubmit\" value=\"".$lang['textno']."\" /></form></td></tr></table></table>";
    } elseif($lang['textyes'] == $yessubmit) {
        $db->query("DELETE FROM $table_whosonline");
        $navigation .= " &raquo; ".$lang['tools'];
        echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[tool_completed] Whos Online Cleared</td></tr></table></table>";
        end_time();
        eval("\$footer = \"".template("footer")."\";");
        echo $footer;
        exit();
    } else {
        redirect('./cp.php');
    }
}

if($action == 'fixorphanedthreads'){
    if(!isset($orphsubmit)) {
        echo '<tr bgcolor="'.$altbg2.'" class="tablerow"><td>';
        echo '<form action="tools.php?action=fixorphanedthreads" method="post">';
        echo '<input type="text" name="export_fid" size="4"/> '.$lang['export_fid_expl'];
        echo '<br /><input type="submit" name="orphsubmit" />';
        echo '</form>';
        echo '</td></tr></table></table>';

    } else {
        if(!isset($export_fid)){
            error($lang['export_fid_not_there'], false, '</table></table><br />');
        }

        $q = $db->query("SELECT fid FROM $table_forums WHERE type = 'forum' OR type='sub'");
        while($f = $db->fetch_array($q)){
            $fids[] = $f['fid'];
        }
        $fq = "fid != '";
        $fids = implode("' AND fid != '", $fids);
        $fq .= $fids;
        $fq .= "'";

        $q = $db->query("SELECT tid FROM $table_threads WHERE $fq");
        $i = 0;
        while($t = $db->fetch_array($q)){
            $db->query("UPDATE $table_threads SET fid='$export_fid' WHERE tid='$t[tid]'");
            $db->query("UPDATE $table_posts SET fid='$export_fid' WHERE tid='$t[tid]'");
            $i++;
        }
        echo '<tr bgcolor="'.$altbg2.'" class="ctrtablerow"><td>';
        echo $i.$lang['o_threads_found'];
        echo '</td></tr></table></table>';
    }
}

if($action == 'repairtables'){
    $start = true;

    @set_time_limit(180);
    $tables = $db->fetch_tables($dbname);
    $q = array();
    foreach($tables as $key=>$val){
        if($start){
            dump_query($db->query('REPAIR TABLE `'.$val.'`'));
            $start = false;
        }else{
            dump_query($db->query('REPAIR TABLE `'.$val.'`'), false);
        }
    }
}

if($action == 'optimizetables'){
    $start = true;

    @set_time_limit(180);
    $tables = $db->fetch_tables($dbname);
    $q = array();
    foreach($tables as $key=>$val){
        if($start){
            dump_query($db->query('OPTIMIZE TABLE `'.$val.'`'));
            $start = false;
        }else{
            dump_query($db->query('OPTIMIZE TABLE `'.$val.'`'), false);
        }
    }
}

if($action == 'analyzetables'){
    $start = true;

    @set_time_limit(180);
    $tables = $db->fetch_tables($dbname);
    $q = array();
    foreach($tables as $key=>$val){
        if($start){
            dump_query($db->query('ANALYZE TABLE `'.$val.'`'));
        }else{
            dump_query($db->query('ANALYZE TABLE `'.$val.'`'), false);
        }
    }
}

echo "</td></tr></table></table>";
end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>