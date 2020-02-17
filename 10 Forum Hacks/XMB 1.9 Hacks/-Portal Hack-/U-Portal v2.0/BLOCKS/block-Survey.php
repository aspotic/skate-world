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

if (eregi("block-Survey.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

global $boxTitle, $content, $pollcomm, $xmbuser, $prefix, $altbg1, $borderwidth, $tablespace, $bordercolor;

$sql = "SELECT pollID FROM ".$prefix."_poll_desc WHERE artid='0' ORDER BY pollID DESC LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$pollID = $row[pollID];
if ($pollID == 0 || $pollID == "") {
    $content = "";
} else {
    if(!isset($url))
	$url = sprintf("modules.php?modulename=Surveys&amp;op=results&amp;pollID=%d", $pollID);
    $content .= "<form action=\"modules.php?modulename=Surveys\" method=\"post\">";
    $content .= "<input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\">";
    $content .= "<input type=\"hidden\" name=\"forwarder\" value=\"".$url."\">";
    $sql2 = "SELECT pollTitle, voters FROM ".$prefix."_poll_desc WHERE pollID=$pollID";
    $result2 = mysql_query($sql2);
    $row2 = mysql_fetch_array($result2);
    $pollTitle = $row2[pollTitle];
    $voters = $row2[voters];
    $boxTitle = "Survey";
    $content .= "<font class=\"content\"><b>$pollTitle</b></font><br><br>\n";
    $content .= "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" width=\"100%\">";
    for($i = 1; $i <= 12; $i++) {
	$sql3 = "SELECT pollID, optionText, optionCount, voteID FROM ".$prefix."_poll_data WHERE (pollID=$pollID) AND (voteID=$i)";
	$result3 = mysql_query($sql3);
	$row3 = mysql_fetch_array($result3);
	if(isset($row3)) {
	    $optionText = $row3[optionText];
	    if ($optionText != "") {
		$content .= "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" valign=\"top\"><input type=\"radio\" name=\"voteID\" value=\"".$i."\"></td><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"100%\"><font class=\"tablerow\">$optionText</font></td></tr>\n";
	    }
	}
    }
    $content .= "</table><br><center><font class=\"mediumtxt\"><input type=\"submit\" value=\"Vote\"></font><br>";
    if ($xmbuser) {
	cookiedecode($xmbuser);
    }
    for($i = 0; $i < 12; $i++) {
	$sql4 = "SELECT optionCount FROM ".$prefix."_poll_data WHERE (pollID=$pollID) AND (voteID=$i)";
	$result4 = mysql_query($sql4);
	$row4 = mysql_fetch_array($result4);
	$optionCount = $row4[optionCount];
	$sum = (int)$sum+$optionCount;
    }
    $content .= "<br><font class=\"mediumtxt\"><a href=\"modules.php?modulename=Surveys&amp;op=results&amp;pollID=$pollID&amp;mode=$cookie[4]&amp;order=$cookie[5]&amp;thold=$cookie[6]\"><b>Results</b></a><br><a href=\"modules.php?modulename=Surveys\"><b>Polls</b></a><br>";
	$content .= "<br>Votes <b>$sum</b>\n\n";
    $content .= "</font></center></form>\n\n";
}

?>
