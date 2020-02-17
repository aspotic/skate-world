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

function BlocksAdmin() {
	global $prefix, $db, $borderwidth, $tablespace, $bordercolor, $altbg1, $altbg2, $portal_images;
	include("portal_header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"subject\"><b>Blocks Administration</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><table border=\"0\" width=\"100%\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\"><tr>"
	    ."<td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>Title</b></td>"
	    ."<td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\" width=\"14%\"><b>Position</b></td>"
	    ."<td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><b>Weight</b></td>"
	    ."<td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>Type</b></td>"
	    ."<td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>Status</b></td>"
	    ."<td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>Visible to</b></td>";
	echo "<td align=\"center\" bgcolor=\"$altbg2\" class=\"tablerow\" width=\"22%\"><b>Functions</b></tr>";
        $result = $db->query("select bid, bkey, title, url, bposition, weight, active, blockfile, view from ".$prefix."_blocks order by bposition, weight");
	    while(list($bid, $bkey, $title, $url, $bposition, $weight, $active, $blockfile, $view) = $db->fetch_row($result)) {
        $cutweight = $weight-1;
	    $addweight = $weight+1;
	    $res = $db->query("select bid from ".$prefix."_blocks where weight='$cutweight' AND bposition='$bposition'");
	    list ($bid1) = $db->fetch_row($res);
	    $UP = "$bid1";
	    $query = $db->query("select bid from ".$prefix."_blocks where weight='$addweight' AND bposition='$bposition'");
	    list ($bid2) = $db->fetch_row($query);
	    $DOWN = "$bid2";
	    echo "<tr>"
		."<td align=\"center\" bgcolor=\"$altbg1\" class=\"subject\">$title</td>";
	    if ($bposition == "l") {
		$bposition = "<img src=\"$portal_images/center_r.gif\" border=\"0\" alt=\"Left Block\" title=\"Left Block\" hspace=\"5\"> Left";
	    } elseif ($bposition == "r") {
		$bposition = "Right <img src=\"$portal_images/center_l.gif\" border=\"0\" alt=\"Right Block\" title=\"Right Block\" hspace=\"5\">";
	    } elseif ($bposition == "c") {
		$bposition = "<img src=\"$portal_images/center_l.gif\" border=\"0\" alt=\"Center Block\" title=\"Center Block\">&nbsp;Center Up&nbsp;<img src=\"$portal_images/center_r.gif\" border=\"0\" alt=\"Center Block\" title=\"Center Block\">";
	    } elseif ($bposition == "d") {
		$bposition = "<img src=\"$portal_images/center_l.gif\" border=\"0\" alt=\"Center Block\" title=\"Center Block\">&nbsp;Center Down&nbsp;<img src=\"$portal_images/center_r.gif\" border=\"0\" alt=\"Center Block\" title=\"Center Block\">";
	    }
	    echo "<td align=\"center\" bgcolor=\"$altbg1\" class=\"tablerow\">$bposition</td>"
		."<td align=\"center\" bgcolor=\"$altbg1\" class=\"tablerow\">"
		."&nbsp;$weight&nbsp;</td><td align=\"center\" bgcolor=\"$altbg1\" class=\"tablerow\">";
	    if ($UP) {
		echo"<a href=\"portal_admin.php?op=BlockOrder&amp;weight=$weight&amp;bidori=$bid&amp;weightrep=$cutweight&amp;bidrep=$UP\"><img src=\"$portal_images/up.gif\" alt=\"Block UP\" title=\"Block UP\" border=\"0\" hspace=\"3\"></a>";
	    }
	    if ($DOWN) {
		echo "<a href=\"portal_admin.php?op=BlockOrder&amp;weight=$weight&amp;bidori=$bid&amp;weightrep=$addweight&amp;bidrep=$DOWN\"><img src=\"$portal_images/down.gif\" alt=\"Block DOWN\" title=\"Block DOWN\" border=\"0\" hspace=\"3\"></a>";
	    }
	    echo"</td>";
	    if ($bkey == "") {
		if ($url == "") {
		    $type = "<font color=blue>HTML</font>";
		}
		if ($blockfile != "") {
		    $type = "<font color=green>FILE</font>";
		}
	    } elseif ($bkey != "") {
		$type = "<font color=red>SYSTEM</font>";
	    }
	    echo "<td align=\"center\" bgcolor=\"$altbg1\" class=\"tablerow\">$type</td>";
	    $block_act = $active;
	    if ($active == 1) {
		$active = "Active";
	    } elseif ($active == 0) {
		$active = "<i>Inactive</i>";
	    }
	    echo "<td align=\"center\" bgcolor=\"$altbg1\" class=\"tablerow\">$active</td>";
	    if ($view == 0) {
		$who_view = "All Visitors";
	    } elseif ($view == 1) {
		$who_view = "Registered Users Only";
	    } elseif ($view == 2) {
		$who_view = "Administrators Only";
	    } elseif ($view == 3) {
		$who_view = "Anonymous Users Only";
	    }
	    echo "<td align=\"center\" bgcolor=\"$altbg1\" class=\"tablerow\">$who_view</td>";
	    echo "<td align=\"center\" bgcolor=\"$altbg1\" class=\"tablerow\">[ <a href=\"portal_admin.php?op=BlocksEdit&bid=$bid\">Edit</a> |";
        
        if ($bkey == "") {
		echo "<a href=\"portal_admin.php?op=BlocksDelete&bid=$bid\">Delete</a> | ";
	    } elseif ($bkey != "") {
		echo "Delete | ";
	    }
	    if ($block_act == 0) {
		echo "<a href=\"portal_admin.php?op=block_show&bid=$bid\">Show</a> ]</font></td></tr>";
	    } else {
		echo "Show ]</font></td></tr>";
	    }
	}
	echo "</table>"
	    ."<br><br>"
	    ."<center>[ <a href=\"portal_admin.php?op=fixweight\"><u>Fix Block's Weight Conflicts</u></a> ]</center><br>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><font class=\"option\"><b>Add a New Block</b></font></center><br><br>"
	    ."<form action=\"portal_admin.php\" method=\"post\">"
	    ."<table border=\"0\" width=\"100%\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\">"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Title:</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\"></td></tr>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Filename:</td><td bgcolor=\"$altbg2\" class=\"tablerow\">"
	    ."<select name=\"blockfile\">"
	    ."<option name=\"blockfile\" value=\"\" selected>None</option>";
	$blocksdir = dir("blocks");
	while($func=$blocksdir->read()) {
	    if(substr($func, 0, 6) == "block-") {
    		$blockslist .= "$func ";
	    }
	}
	closedir($blocksdir->handle);
	$blockslist = explode(" ", $blockslist);
	sort($blockslist);
	for ($i=0; $i < sizeof($blockslist); $i++) {
	    if($blockslist[$i]!="") {
		$bl = ereg_replace("block-","",$blockslist[$i]);
		$bl = ereg_replace(".php","",$bl);
		$bl = ereg_replace("_"," ",$bl);
		$result = $db->query("select * from ".$prefix."_blocks where blockfile='$blockslist[$i]'");
		if ($db->num_rows($result) == 0) {
		    echo "<option value=\"$blockslist[$i]\">$bl</option>\n";
		}
	    }
	}
	echo "</select>&nbsp;&nbsp;<font class=\"tablerow\">(Select a custom Block to be included. All other fields will be ignored)</font></td></tr>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Content:</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea name=\"content\" cols=\"50\" rows=\"10\"></textarea><br><font class=\"tiny\">If you fill the URL the content you write will not be displayed!</font></td></tr>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Position:</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"bposition\"><option name=\"bposition\" value=\"l\">Left</option>"
	    ."<option name=\"bposition\" value=\"c\">Center Up</option>"
	    ."<option name=\"bposition\" value=\"d\">Center Down</option>"
	    ."<option name=\"bposition\" value=\"r\">Right</option></select></td></tr>";
	    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Activate?</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"active\" value=\"1\" checked>Yes &nbsp;&nbsp;"
	    ."<input type=\"radio\" name=\"active\" value=\"0\">No</td></tr>"
	    ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Who can View This?</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"view\">"
	    ."<option value=\"0\" >All Visitors</option>"
	    ."<option value=\"1\" >Registered Users Only</option>"
	    ."<option value=\"2\" >Administrators Only</option>"
	    ."<option value=\"3\" >Anonymous Users Only</option>"
	    ."</select>"
	    ."</td></tr></table><br><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"BlocksAdd\">"
	    ."<input type=\"submit\" value=\"Create Block\"></form>";
	    CloseTable();
	    include("portal_footer.php");
}

function block_show($bid) {
    global $prefix, $db;
    include("portal_header.php");
    GraphicAdmin();
    title("Blocks Administration");
    OpenTable();
    $result = $db->query("select bid, bkey, title, content, url, bposition, blockfile from ".$prefix."_blocks where bid='$bid'");
    list($bid, $bkey, $title, $content, $url, $bposition, $blockfile) = $db->fetch_row($result);
    if ($bkey == main) {
        mainblock();
    } elseif ($bkey == admin) {
        adminblock();
    } elseif ($bkey == modules) {
        modules_block();
    } elseif ($bkey == category) {
        category();
    } elseif ($bkey == userbox) {
        userblock();
    } elseif ($bkey == "") {
        if ($url == "") {
   	    if ($blockfile == "") {
		if ($bposition == "c") {
        themecenterbox($title, $content);
		} else {
        themesidebox($title, $content);
		}
	    } else {
		if ($bposition == "c") {
        blockfileinc($title, $blockfile, 1);
		} else {
        blockfileinc($title, $blockfile);
		}
	    }
    	}
    }
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>Blocks Administration: Functions</b></font><br><br>"
	."[ <a href=\"portal_admin.php?op=BlocksEdit&bid=$bid\">Edit</a> | ";
    if ($bkey == "") {
	echo "<a href=\"portal_admin.php?op=BlocksDelete&bid=$bid\">Delete</a> | ";
    } else {
	echo "Delete | ";
    }
    echo "<a href=\"portal_admin.php?op=BlocksAdmin\">Blocks Administration</a> ]</center>";
    CloseTable();
    include("portal_footer.php");
}

function fixweight() {
    global $prefix, $db;
    $leftpos = "l";
    $rightpos = "r";
    $centerpos = "c";
    $result = $db->query("select bid from ".$prefix."_blocks where bposition='$leftpos' order by weight ASC");
    $weight = 0;
    while(list($bid) = $db->fetch_row($result)) {
	$weight++;
	$db->query("update ".$prefix."_blocks set weight='$weight' where bid='$bid'");
    }
    $result = $db->query("select bid from ".$prefix."_blocks where bposition='$rightpos' order by weight ASC");
    $weight = 0;
    while(list($bid) = $db->fetch_row($result)) {
	$weight++;
	$db->query("update ".$prefix."_blocks set weight='$weight' where bid='$bid'");
    }
    $result = $db->query("select bid from ".$prefix."_blocks where bposition='$centerpos' order by weight ASC");
    $weight = 0;
    while(list($bid) = $db->fetch_row($result)) {
	$weight++;
	$db->query("update ".$prefix."_blocks set weight='$weight' where bid='$bid'");
    }
    Header("Location: portal_admin.php?op=BlocksAdmin");
}

function BlockOrder ($weightrep,$sameweight,$bidrep,$bidori) {
    global $prefix, $db;
    $result = $db->query("update ".$prefix."_blocks set weight='$sameweight' where bid='$bidrep'");
    $result2 = $db->query("update ".$prefix."_blocks set weight='$weightrep' where bid='$bidori'");
    Header("Location: portal_admin.php?op=BlocksAdmin");
}

function BlocksAdd($title, $content, $bposition, $active, $blockfile, $view) {
    global $prefix, $db;
    $result = $db->query("SELECT weight FROM ".$prefix."_blocks WHERE bposition='$bposition' ORDER BY weight DESC");
    list ($weight) = $db->fetch_row($result);
    $weight++;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    $bkey = "";
    $btime = "";
    if ($blockfile != "") {
	$url = "";
	if ($title == "") {
	    $title = ereg_replace("block-","",$blockfile);
	    $title = ereg_replace(".php","",$title);
	    $title = ereg_replace("_"," ",$title);
	}
    }
    $content = FixQuotes($content);
	$db->query("insert into ".$prefix."_blocks values (NULL, '$bkey', '$title', '$content', '$url', '$bposition', '$weight', '$active', '$blockfile', '$view')");
	Header("Location: portal_admin.php?op=BlocksAdmin");
}

function BlocksEdit($bid) {
    global $altbg2, $altbg1, $prefix, $db, $borderwidth, $tablespace, $bordercolor;
    include("portal_header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"subject\"><b>Edit Block</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = $db->query("select bkey, title, content, url, bposition, weight, active, blockfile, view from ".$prefix."_blocks where bid='$bid'");
    list($bkey, $title, $content, $url, $bposition, $weight, $active, $blockfile, $view) = $db->fetch_row($result);
    if ($blockfile != "") {
	$type = "(Block File)";
    }
    OpenTable();
    echo "<center><font class=\"subject\"><b>Block: $title $type</b></font></center><br><br>"
        ."<form action=\"portal_admin.php\" method=\"post\">"
        ."<table border=\"0\" width=\"100%\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\">"
        ."<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Title:</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\" value=\"$title\"></td></tr>";
    if ($blockfile != "") {
	echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Filename:</td><td bgcolor=\"$altbg2\" class=\"tablerow\">"
	    ."<select name=\"blockfile\">";
	$blocksdir = dir("blocks");
	while($func=$blocksdir->read()) {
	    if(substr($func, 0, 6) == "block-") {
    		$blockslist .= "$func ";
	    }
	}
	closedir($blocksdir->handle);
	$blockslist = explode(" ", $blockslist);
	sort($blockslist);
	for ($i=0; $i < sizeof($blockslist); $i++) {
	    if($blockslist[$i]!="") {
		$bl = ereg_replace("block-","",$blockslist[$i]);
		$bl = ereg_replace(".php","",$bl);
		$bl = ereg_replace("_"," ",$bl);
		echo "<option value=\"$blockslist[$i]\" ";
		if ($blockfile == $blockslist[$i]) { echo "selected"; }
		echo ">$bl</option>\n";
	    }
	}
	echo "</select>&nbsp;&nbsp;<br><font class=\"tiny\">(Select a custom Block to be included. All other fields will be ignored)</font></td></tr>";
    }else {
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Content:</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea name=\"content\" cols=\"50\" rows=\"10\">$content</textarea></td></tr>";
    }
    $oldposition = $bposition;
    echo "<input type=\"hidden\" name=\"oldposition\" value=\"$oldposition\">";
    if ($bposition == "l") {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
    } elseif ($bposition == "c") {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
	$sel4 = "";
    } elseif ($bposition == "r") {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
	$sel4 = "";
    } elseif ($bposition == "d") {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "selected";
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Position:</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"bposition\">"
	."<option name=\"bposition\" value=\"l\" $sel1>Left</option>"
	."<option name=\"bposition\" value=\"c\" $sel2>Center Up</option>"
	."<option name=\"bposition\" value=\"d\" $sel4>Center Down</option>"
	."<option name=\"bposition\" value=\"r\" $sel3>Right</option></select></td></tr>";
    if ($active == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } elseif ($active == 0) {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Activate?</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"radio\" name=\"active\" value=\"1\" $sel1>Yes &nbsp;&nbsp;"
    ."<input type=\"radio\" name=\"active\" value=\"0\" $sel2>No</td></tr>";
    if ($view == 0) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
    } elseif ($view == 1) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
	$sel4 = "";
    } elseif ($view == 2) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
	$sel4 = "";    
    } elseif ($view == 3) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "selected";    
    }
    echo "</td></tr><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">Who can View This?</td><td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"view\">"
        ."<option value=\"0\" $sel1>All Visitors</option>"
        ."<option value=\"1\" $sel2>Registered Users Only</option>"
        ."<option value=\"2\" $sel3>Administrators Only</option>"
        ."<option value=\"3\" $sel4>Anonymous Users Only</option>"
        ."</select>"
	."</td></tr></table><br><br>"
	."<input type=\"hidden\" name=\"bid\" value=\"$bid\">"
	."<input type=\"hidden\" name=\"bkey\" value=\"$bkey\">"
	."<input type=\"hidden\" name=\"weight\" value=\"$weight\">"
        ."<input type=\"hidden\" name=\"op\" value=\"BlocksEditSave\">"
        ."<input type=\"submit\" value=\"Save Block\"></form>";
    CloseTable();
    include("portal_footer.php");
}

function SortWeight($bposition) {
    global $prefix, $db;
    $numbers = 1;
    $number_two = 1;
    $result = $db->query("SELECT bid,weight FROM ".$prefix."_blocks WHERE bposition='$bposition' ORDER BY weight");
    while (list ($bid,$weight) = $db->fetch_row($result)) {
	$result2 = $db->query("update ".$prefix."_blocks set weight='$numbers' where bid='$bid'");
	$numbers++;
    }
    if ($bposition == l) {
	$position_two = "r";
    } else {
	$position_two = "l";
    }
    $result_two = $db->query("SELECT bid,weight FROM ".$prefix."_blocks WHERE bposition='$position_two' ORDER BY weight");
    while (list ($bid2,$weight) = $db->fetch_row($result_two)) {
	$result_two2 = $db->query("update ".$prefix."_blocks set weight='$number_two' where bid='$bid2'");
	$number_two++;
    }
    return $numbers;
}

function BlocksEditSave($bid, $bkey, $title, $content, $oldposition, $bposition, $active, $weight, $blockfile, $view) {
    global $prefix, $db;
        if ($url != "") {
	$bkey = "";
	$btime = time();
	if (!ereg("http://",$url)) {
	    $url = "http://$url";
	}
	$rdf = parse_url($url);
	$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	if (!$fp) {
    	    rssfail();
    	    exit;
	}
	if ($fp) {
    	    fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTML/1.0\r\n");
    	    fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
    	    $string	= "";
    	    while(!feof($fp)) {
    		$pagetext = fgets($fp,300);
		$string .= chop($pagetext);
	    }
	    fputs($fp,"Connection: close\r\n\r\n");
	    fclose($fp);
	    $items = explode("</item>",$string);
	    $content = "<font class=\"content\">";
	    for ($i=0;$i<10;$i++) {
		$link = ereg_replace(".*<link>","",$items[$i]);
		$link = ereg_replace("</link>.*","",$link);
		$title2 = ereg_replace(".*<title>","",$items[$i]);
		$title2 = ereg_replace("</title>.*","",$title2);
		if ($items[$i] == "" AND $cont != 1) {
		    $content = "";
		} else {
		    if (strcmp($link,$title2) AND $items[$i] != "") {
			$cont = 1;
			$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
		    }
		}
	    }
	}
	if ($oldposition != $bposition) {
	    $result = $db->query("select bid from ".$prefix."_blocks where weight>='$weight' AND bposition='$bposition'");
	    $fweight = $weight;
	    $oweight = $weight;
	    while (list($nbid) = $db->fetch_row($result)) {
		$weight++;
		$db->query("update ".$prefix."_blocks set weight='$weight' where bid='$nbid'");
	    }
	    $result = $db->query("select bid from ".$prefix."_blocks where weight>'$oweight' AND bposition='$oldposition'");
	    while (list($obid) = $db->fetch_row($result)) {
		$db->query("update ".$prefix."_blocks set weight='$oweight' where bid='$obid'");
		$oweight++;
	    }
	    $result = $db->query("select weight from ".$prefix."_blocks where bposition='$bposition' order by weight DESC limit 0,1");
	    list($lastw) = $db->fetch_row($result);
	    if ($lastw <= $fweight) {
		$lastw++;
		$db->query("update ".$prefix."_blocks set title='$title', content='$content', bposition='$bposition', weight='$lastw', active='$active', view='$view' where bid='$bid'");
	    } else {
		$db->query("update ".$prefix."_blocks set title='$title', content='$content', bposition='$bposition', weight='$fweight', active='$active', view='$view' where bid='$bid'");
     }
      } else {
	    $result = $db->query("update ".$prefix."_blocks set bkey='$bkey', title='$title', content='$content', url='$url', bposition='$bposition', weight='$weight', active='$active', view='$view' where bid='$bid'");
    }
      Header("Location: portal_admin.php?op=BlocksAdmin");
      }else  {

	$title = stripslashes(FixQuotes($title));
	$content = stripslashes(FixQuotes($content));

	if ($oldposition != $bposition) {
	    $result = $db->query("select bid from ".$prefix."_blocks where weight>='$weight' AND bposition='$bposition'");
	    $fweight = $weight;
	    $oweight = $weight;
	    while (list($nbid) = $db->fetch_row($result)) {
		$weight++;
		$db->query("update ".$prefix."_blocks set weight='$weight' where bid='$nbid'");
	    }
	    $result = $db->query("select bid from ".$prefix."_blocks where weight>'$oweight' AND bposition='$oldposition'");
	    while (list($obid) = $db->fetch_row($result)) {
		$db->query("update ".$prefix."_blocks set weight='$oweight' where bid='$obid'");
		$oweight++;
	    }
	    $result = $db->query("select weight from ".$prefix."_blocks where bposition='$bposition' order by weight DESC limit 0,1");
	    list($lastw) = $db->fetch_row($result);
	    if ($lastw <= $fweight) {
		$lastw++;
		$db->query("update ".$prefix."_blocks set title='$title', content='$content', bposition='$bposition', weight='$lastw', active='$active', view='$view' where bid='$bid'");
	    } else {
		$db->query("update ".$prefix."_blocks set title='$title', content='$content', bposition='$bposition', weight='$fweight', active='$active', view='$view' where bid='$bid'");
	    }
	    } else {
	    $result = $db->query("update ".$prefix."_blocks set bkey='$bkey', title='$title', content='$content', url='$url', bposition='$bposition', weight='$weight', active='$active', view='$view' where bid='$bid'");
	}
	Header("Location: portal_admin.php?op=BlocksAdmin");
    }
}

function BlocksDelete($bid, $ok=0) {
    global $prefix, $db;
    if ($ok) {
	$result = $db->query("select bposition, weight from ".$prefix."_blocks where bid='$bid'");
	list($bposition, $weight) = $db->fetch_row($result);
	$result = $db->query("select bid from ".$prefix."_blocks where weight>'$weight' AND bposition='$bposition'");
	while (list($nbid) = $db->fetch_row($result)) {
	    $db->query("update ".$prefix."_blocks set weight='$weight' where bid='$nbid'");
	    $weight++;
	}
	$db->query("delete from ".$prefix."_blocks where bid='$bid'");
	Header("Location: portal_admin.php?op=BlocksAdmin");
    } else {
    $result = $db->query("select title from ".$prefix."_blocks where bid='$bid'");
	list($title) = $db->fetch_row($result);
	include("portal_header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"tablerow\"><b>Blocks Administration</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center>Are you sure you want to remove Block <i>$title</i>?";
	echo "<br><br>[ <a href=\"portal_admin.php?op=BlocksAdmin\">No</a> | <a href=\"portal_admin.php?op=BlocksDelete&amp;bid=$bid&amp;ok=1\">Yes</a> ]</center>";
	CloseTable();
	include("portal_footer.php");
    }
}

switch($op) {

    case "BlocksAdmin":
    BlocksAdmin();
    break;

    case "BlocksAdd":
    BlocksAdd($title, $content, $bposition, $active, $blockfile, $view);
    break;

    case "BlocksEdit":
    BlocksEdit($bid);
    break;

    case "BlocksEditSave":
    BlocksEditSave($bid, $bkey, $title, $content, $oldposition, $bposition, $active, $weight, $blockfile, $view);
    break;

    case "blockstatus":
    blockstatus($bid);
    break;

    case "BlocksDelete":
    BlocksDelete($bid, $ok);
    break;

    case "BlockOrder":
    BlockOrder ($weightrep,$weight,$bidrep,$bidori);
    break;

    case "fixweight":
    fixweight();
    break;

    case "block_show":
    block_show($bid);
    break;

}

} else {
    echo "Access Denied";
}

?>
