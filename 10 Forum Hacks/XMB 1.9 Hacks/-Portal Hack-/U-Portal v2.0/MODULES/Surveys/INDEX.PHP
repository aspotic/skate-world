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

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

require_once("portal_main.php");
$module_name = basename(dirname(__FILE__));
eval("\$header = \"".template("header")."\";");
echo $header;

if(!isset($pollID)) {
    include ('portal_header.php');
    pollList();
    include ('portal_footer.php');
} elseif(isset($forwarder)) {
    pollCollector($pollID, $voteID, $forwarder);
} elseif($op == "results" && $pollID > 0) {
    include ("portal_header.php");
    OpenTable();
    echo "<center><b>Current Poll Results</b></center>";
    CloseTable();
    echo "<br>";
    echo "<table border='0' cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" width='100%'><tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width='70%' valign='top'>";
    OpenTable();
    pollResults($pollID);
    CloseTable();
    echo "</td><td bgcolor=\"$altbg1\" class=\"tablerow\">&nbsp;</td><td bgcolor=\"$altbg1\" class=\"tablerow\" width='30%' valign='top'>";
    OpenTable();
    echo "<b>Last 5 Surveys at $sitename</b><br><br>";
    if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
    if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
    if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
    $resu = $db->query("SELECT pollID, pollTitle, voters FROM ".$prefix."_poll_desc where artid='0' order by timeStamp DESC limit 1,6");
    while (list($plid, $pltitle, $plvoters) = $db->fetch_array($resu)) {
	if ($pollID == $plid) {
	    echo "<img src='$portal_images/arrow.gif' border='0'>&nbsp;$pltitle ($plvoters votes)<br><br>";
	} else {
	    echo "<img src='$portal_images/arrow.gif' border='0'>&nbsp;<a href='modules.php?modulename=$module_name&op=results&pollID=$plid$r_options'>$pltitle</a> ($plvoters votes)<br><br>";
	}
    }
    echo "<a href='modules.php?modulename=$module_name'><b>...more polls</b></a>";
    CloseTable();
    echo "</td></tr></table>";
    include ("portal_footer.php");
    } elseif($voteID > 0) {
    pollCollector($pollID, $voteID);
    } elseif($pollID != pollLatest()) {
    include ('portal_header.php');
    OpenTable();
    echo "<center><b>Survey</b></center>";
    CloseTable();
    echo "<br><br>";
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" align=\"center\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">";
    pollMain($pollID);
    echo "</td></tr></table>";
    include ('portal_footer.php');
} else {
    include ('portal_header.php');
    OpenTable();
    echo "<center><b>Current Survey</b></center>";
    CloseTable();
    echo "<br><br><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" align=\"center\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">";
    pollNewest();
    echo "</td></tr></table>";
    include ('portal_footer.php');
}

function pollMain($pollID) {
    global $boxTitle, $boxContent, $pollcomm, $xmbuser, $cookie, $prefix, $db, $module_name, $borderwidth, $tablespace, $bordercolor, $altbg1;
    if(!isset($pollID))
	$pollID = 1;
    if(!isset($url))
	$url = sprintf("modules.php?modulename=$module_name&amp;op=results&amp;pollID=%d", $pollID);
    $boxContent .= "<form action=\"modules.php?modulename=$module_name\" method=\"post\">";
    $boxContent .= "<input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\">";
    $boxContent .= "<input type=\"hidden\" name=\"forwarder\" value=\"".$url."\">";
    $result = $db->query("SELECT pollTitle, voters FROM ".$prefix."_poll_desc WHERE pollID='$pollID'");
    list($pollTitle, $voters) = $db->fetch_array($result);
    $boxTitle = "Survey";
    $boxContent .= "<b>$pollTitle</b><br><br>\n";
    $boxContent .= "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" width=\"100%\">";
    for($i = 1; $i <= 12; $i++) {
	$result = $db->query("SELECT pollID, optionText, optionCount, voteID FROM ".$prefix."_poll_data WHERE pollID='$pollID' AND voteID='$i'");
	$object = mysql_fetch_object($result);
	if(is_object($object)) {
	    $optionText = $object->optionText;
	    if($optionText != "") {
		$boxContent .= "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" valign=\"top\"><input type=\"radio\" name=\"voteID\" value=\"".$i."\"></td><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"100%\"><font class=\"tablerow\">$optionText</font></td></tr>\n";
	    }
	}
    }
    $boxContent .= "</table><br><center><input type=\"submit\" value=\"Vote\"><br>";
    if ($xmbuser) {
        cookiedecode($xmbuser);
    }
    for($i = 0; $i < 12; $i++) {
	$result = $db->query("SELECT optionCount FROM ".$prefix."_poll_data WHERE pollID='$pollID' AND voteID='$i'");
	$object = mysql_fetch_object($result);
	$optionCount = $object->optionCount;
	$sum = (int)$sum+$optionCount;
    }
    $boxContent .= "<br><a href=\"modules.php?modulename=$module_name&amp;op=results&amp;pollID=$pollID&amp;mode=$cookie[4]&amp;order=$cookie[5]&amp;thold=$cookie[6]\"><b>Results</b></a><br><a href=\"modules.php?modulename=$module_name\"><b>Polls</b></a><br>";
    $boxContent .= "<br>Votes: <b>$sum</b>\n\n";
    $boxContent .= "</font></center></form>\n\n";
    themesidebox($boxTitle, $boxContent);
}

function pollLatest() {
    global $prefix, $db;
    $result = $db->query("SELECT pollID FROM ".$prefix."_poll_desc ORDER BY pollID DESC LIMIT 1");
    $pollID = $db->fetch_array($result);
    return($pollID[0]);
}

function pollNewest() {
    $pollID = pollLatest();
    pollMain($pollID);
}

function pollCollector($pollID, $voteID, $forwarder) {
    global $HTTP_COOKIE_VARS, $prefix, $db;
    if (empty($ip)) {
	$ip = $_SERVER["REMOTE_ADDR"];
    }
    $past = time()-1800;
    $db->query("DELETE FROM ".$prefix."_poll_check WHERE time < $past");
    $result = $db->query("SELECT ip FROM ".$prefix."_poll_check WHERE (ip='$ip') AND (pollID='$pollID')");
    list($ips) = $db->fetch_array($result);
    $ctime = time();
    if ($ip == $ips) {
	$voteValid = 0;
    } else {
	$db->query("INSERT INTO ".$prefix."_poll_check (ip, time, pollID) VALUES ('$ip', '$ctime', '$pollID')");
	$voteValid = "1";
    }
    if($voteValid>0) {
        $db->query("UPDATE ".$prefix."_poll_data SET optionCount=optionCount+1 WHERE pollID='$pollID' AND voteID='$voteID'");
        if ($voteID != "") {
	    $db->query("UPDATE ".$prefix."_poll_desc SET voters=voters+1 WHERE pollID='$pollID'");
        }
	    Header("Location: $forwarder");
        } else {
        Header("Location: $forwarder");
        }
        echo "<html><head></head><body></body></html>";
}

function pollList() {
    global $xmbuser, $cookie, $prefix, $status, $db, $module_name, $borderwidth, $tablespace, $bordercolor, $altbg1;
    if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
    if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
    if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
    $result = $db->query("SELECT pollID, pollTitle, timeStamp, voters FROM ".$prefix."_poll_desc WHERE artid='0' ORDER BY timeStamp DESC");
    $counter = 0;
    OpenTable();
    OpenTable();
    echo "<center><font class=\"subject\"><b>Past Surveys</b></font></center>";
    CloseTable();
    echo "<br><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" width=\"100%\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">";
    while($object = mysql_fetch_object($result)) {
	$resultArray[$counter] = array($object->pollID, $object->pollTitle, $object->timeStamp, $object->voters);
	$counter++;
    }
    for ($count = 0; $count < count($resultArray); $count++) {
	$id = $resultArray[$count][0];
	$pollTitle = $resultArray[$count][1];
	$voters = $resultArray[$count][3];
	for($i = 0; $i < 12; $i++) {
	    $result = $db->query("SELECT optionCount FROM ".$prefix."_poll_data WHERE pollID='$id' AND voteID='$i'");
	    $object = mysql_fetch_object($result);
	    $optionCount = $object->optionCount;
	    $sum = (int)$sum+$optionCount;
	}
	echo "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?modulename=$module_name&amp;pollID=$id\">$pollTitle</a> ";
	if ($status == "Super Administrator" || $status == "Administrator") {
	    $editing = " - <a href=\"portal_admin.php?op=polledit&amp;pollID=$id\">Edit</a>";
	} else {
	    $editing = "";
	}
	echo "(<a href=\"modules.php?modulename=$module_name&amp;op=results&amp;pollID=$id$r_options\">Results</a> - $sum votes $editing)<br>\n";
	$sum = 0;
    }
    echo "</td></tr></table>"
	."<br>";
    OpenTable();
    echo "<center><font class=\"title\"><b>Surveys Attached to Articles</b></font></center>";
    CloseTable();
    echo "<br>";
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" width=\"100%\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\">";
    $querylang = "WHERE artid!='0'";
    $counter = 0;
    $result = $db->query("SELECT pollID, pollTitle, timeStamp, voters FROM ".$prefix."_poll_desc $querylang ORDER BY timeStamp DESC");
    while($object = mysql_fetch_object($result)) {
	$resultArray2[$counter] = array($object->pollID, $object->pollTitle, $object->timeStamp, $object->voters);
	$counter++;
    }
    for ($count = 0; $count < count($resultArray2); $count++) {
	$id = $resultArray2[$count][0];
	$pollTitle = $resultArray2[$count][1];
	$voters = $resultArray2[$count][3];
	for($i = 0; $i < 12; $i++) {
	    $result = $db->query("SELECT optionCount FROM ".$prefix."_poll_data WHERE pollID='$id' AND voteID='$i'");
	    $object = mysql_fetch_object($result);
	    $optionCount = $object->optionCount;
	    $sum = (int)$sum+$optionCount;
	}
	echo "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?modulename=$module_name&amp;pollID=$id\">$pollTitle</a> ";
	if ($status == "Super Administrator" || $status == "Administrator") {
	    $editing = " - <a href=\"portal_admin.php?op=polledit&amp;pollID=$id\">Edit</a>";
	} else {
	    $editing = "";
	}
	$res = $db->query("select storyid, title from ".$prefix."_stories where pollID='$id'");
    $check = $db->num_rows($res);
    list($storyid, $title) = $db->fetch_array($res);
	echo "(<a href=\"modules.php?modulename=$module_name&amp;op=results&amp;pollID=$id$r_options\">Results</a> - $sum votes $editing)<br>\n"
    ."- Attached to article: <a href=\"modules.php?modulename=News&amp;modulefile=article&amp;storyid=$storyid$r_options\">$title</a><br><br>\n";
	$sum = "";
    }
    if($check == 0) {
    echo " <center><font class=\"tablerow\"> There are No Surveys Attached to article</font></center>";
    }
    echo "</td></tr></table>";
    CloseTable();
}

function pollResults($pollID) {
    global $resultTableBgColor, $resultBarFile, $prefix, $db, $status, $module_name, $borderwidth, $tablespace, $portal_images;
    if(!isset($pollID)) $pollID = 1;
    $result = $db->query("SELECT pollID, pollTitle, timeStamp, artid FROM ".$prefix."_poll_desc WHERE pollID='$pollID'");
    $holdtitle = $db->fetch_array($result);
    echo "<b>$holdtitle[1]</b><br><br>";
    for($i = 0; $i < 12; $i++) {
	$result = $db->query("SELECT optionCount FROM ".$prefix."_poll_data WHERE pollID='$pollID' AND voteID='$i'");
	$object = mysql_fetch_object($result);
	$optionCount = $object->optionCount;
	$sum = (int)$sum+$optionCount;
    }
    echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\">";
    /* cycle through all options */
    for($i = 1; $i <= 12; $i++) {
	/* select next vote option */
	$result = $db->query("SELECT pollID, optionText, optionCount, voteID FROM ".$prefix."_poll_data WHERE pollID='$pollID' AND voteID='$i'");
	$object = mysql_fetch_object($result);
	if(is_object($object)) {
	    $optionText = $object->optionText;
	    $optionCount = $object->optionCount;
	    if($optionText != "") {
		echo "<tr><td class=\"tablerow\">";
		echo "$optionText";
		echo "</td>";
		if($sum) {
		    $percent = 100 * $optionCount / $sum;
		} else {
		    $percent = 0;
		}
		echo "<td class=\"tablerow\">";
		$percentInt = (int)$percent * 4 * 1;
		$percent2 = (int)$percent;

		if (file_exists("$portal_images/survey_leftbar.gif") AND file_exists("$portal_images/survey_mainbar.gif") AND file_exists("$portal_images/survey_rightbar.gif")) {
		    $l_size = getimagesize("$portal_images/survey_leftbar.gif");
    		    $m_size = getimagesize("$portal_images/survey_mainbar.gif");
		    $r_size = getimagesize("$portal_images/survey_rightbar.gif");
		    $leftbar = "survey_leftbar.gif";
		    $mainbar = "survey_mainbar.gif";
		    $rightbar = "survey_rightbar.gif";
		} else {
		    $l_size = getimagesize("$portal_images/leftbar.gif");
    		    $m_size = getimagesize("$portal_images/mainbar.gif");
		    $r_size = getimagesize("$portal_images/rightbar.gif");
		    $leftbar = "leftbar.gif";
		    $mainbar = "mainbar.gif";
		    $rightbar = "rightbar.gif";
		}
		if (file_exists("$portal_images/survey_mainbar_d.gif")) {
		    $m1_size = getimagesize("$portal_images/survey_mainbar_d.gif");
		    $mainbar_d = "survey_mainbar_d.gif";
		    if ($percent2 > 0 AND $percent2 <= 23) {
			$salto = "<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"$percentInt\">";
		    } elseif ($percent2 > 24 AND $percent2 < 50) {
			$a = $percentInt - 100;
			$salto = "<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"$portal_images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"$a\">";
		    } elseif ($percent2 > 49 AND $percent2 < 75) {
			$a = $percentInt - 200;
			$salto = "<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"$portal_images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"$portal_images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"$a\">";
		    } elseif ($percent2 > 74 AND $percent2 <= 100) {
			$a = $percentInt - 300;
			$salto = "<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
			        ."<img src=\"$portal_images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"$portal_images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"$portal_images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"$portal_images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"$a\">";
		    }
		}
		if ($percent > 0) {
		    echo "<img src=\"$portal_images/$leftbar\" height=\"$l_size[1]\" width=\"$l_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		    if (file_exists("$portal_images/survey_mainbar_d.gif")) {
			echo "$salto";
		    } else {
			echo "<img src=\"$portal_images/$mainbar\" height=\"$m_size[1]\" width=\"$percentInt\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		    }
		    echo "<img src=\"$portal_images/$rightbar\" height=\"$r_size[1]\" width=\"$r_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		} else {
		    echo "<img src=\"$portal_images/$leftbar\" height=\"$l_size[1]\" width=\"$l_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		    if (!file_exists("$portal_images/survey_mainbar_d.gif")) {
			echo "<img src=\"$portal_images/$mainbar\" height=\"$m_size[1]\" width=\"$m_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		    }
		    echo "<img src=\"$portal_images/$rightbar\" height=\"$r_size[1]\" width=\"$r_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		}
                printf(" %.2f%% (%s)", $percent, $optionCount);
		echo "</td></tr>";
	    }
	}

    }
    echo "</table><br>";
    echo "<center><font class=\"tablerow\">";
    echo "<b>Total Votes: $sum</b><br>";
    echo "<br><br>";
    $booth = $pollID;
    if ($holdtitle[3] > 0) {
	$article = "<br><br>[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]</font></center>";
    } else {
	$article = "</font></center>";
    }
    echo "[ <a href=\"modules.php?modulename=$module_name&amp;pollID=$booth\">Voting Booth</a> | "
	."<a href=\"modules.php?modulename=$module_name\">Other Polls</a> ] $article";
    if ($status == "Super Administrator" || $status == "Administrator") {
	echo "<br><center>[ <a href=\"portal_admin.php?op=create\">Add</a> | <a href=\"portal_admin.php?op=polledit&pollID=$pollID\">Edit</a> ]</center>";
    }
    return(1);
}
end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>
