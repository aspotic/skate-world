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

if (eregi("block-download_center_stats.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}
if (file_exists("files.php")) {

global $table_files, $table_files_categories;

$query = mysql_query("SELECT COUNT(*) FROM $table_files_categories WHERE status = 'on'");
$totalcategories = mysql_result($query, 0);

$query = mysql_query("SELECT COUNT(*) FROM $table_files WHERE status = 'approved'");
$totalfiles = mysql_result($query, 0);

$query = mysql_query("SELECT * FROM $table_files WHERE status = 'approved' ORDER BY time DESC");
$newestfile = mysql_fetch_array($query);
$newestfile[name] = stripslashes($newestfile[name]);

$query = mysql_query("SELECT * FROM $table_files WHERE status = 'approved' ORDER BY time ASC");
$oldestfile = mysql_fetch_array($query);
$oldestfile[name] = stripslashes($oldestfile[name]);

$query = mysql_query("SELECT * FROM $table_files WHERE status = 'approved' ORDER BY views DESC");
$mostview = mysql_fetch_array($query);
$mostview[name] = stripslashes($mostview[name]);

$query = mysql_query("SELECT * FROM $table_files WHERE status = 'approved' ORDER BY views ASC");
$leastview = mysql_fetch_array($query);
$leastview[name] = stripslashes($leastview[name]);

$query = mysql_query("SELECT * FROM $table_files WHERE status = 'approved' AND votes > 0 ORDER BY (rating/votes) DESC");
$mostpopular = mysql_fetch_array($query);
$mostpopular[name] = stripslashes($mostpopular[name]);

$query = mysql_query("SELECT * FROM $table_files WHERE status = 'approved' ORDER BY (rating/votes) ASC");
$leastpopular = mysql_fetch_array($query);
$leastpopular[name] = stripslashes($leastpopular[name]);

$query = mysql_query("SELECT * FROM $table_files WHERE status = 'approved' ORDER BY dls DESC");
$mostdownload = mysql_fetch_array($query);
$mostdownload[name] = stripslashes($mostdownload[name]);

$query = mysql_query("SELECT * FROM $table_files WHERE status = 'approved' ORDER BY dls ASC");
$leastdownload = mysql_fetch_array($query);
$leastdownload[name] = stripslashes($leastdownload[name]);

$query = mysql_query("SELECT SUM(dls) FROM $table_files WHERE status = 'approved'");
$totaldownloads = mysql_result($query, 0);

$query = mysql_query("SELECT SUM(rating) FROM $table_files WHERE status = 'approved'");
$avg1 = mysql_result($query, 0);

$query = mysql_query("SELECT SUM(votes) FROM $table_files WHERE status = 'approved'");
$avg2 = mysql_result($query, 0);

$average = @round($avg1/$avg2);

for ($i = 0; $i < $average; $i++) {
	$stars_average .= "<img src=\"$imgdir/star.gif\" border=\"0\" alt=\"$average/10\" align=\"absmiddle\">";
}

$averagedownloads = @round($totaldownloads/$totalfiles);

$least = @round($leastpopular[rating]/$leastpopular[votes]);

for ($i = 0; $i < $least; $i++) {
	$stars_least .= "<img src=\"$imgdir/star.gif\" border=\"0\" alt=\"$least/10\" align=\"absmiddle\">";
}

$most = @round($mostpopular[rating]/$mostpopular[votes]);

for ($i = 0; $i < $most; $i++) {
	$stars_most .= "<img src=\"$imgdir/star.gif\" border=\"0\" alt=\"$most/10\" align=\"absmiddle\">";
}

eval($lang_downloadstats1);

$content .= "
<marquee direction=\"up\" scrolldelay=\"80\" scrollamount=\"2\" onMouseOver=\"this.stop();\" onMouseOut=\"this.start();\">
$lang_downloadstats1
<br>
The oldest file is
<br>
[<a href=\"files.php?action=file&id=$oldestfile[id]\"><b>$oldestfile[name]</b></a>]
<hr>
The newest file is
<br>
[<a href=\"files.php?action=file&id=$newestfile[id]\"><b>$newestfile[name]</b></a>]
<hr>
The least popular file based on views is
<br>
[<a href=\"files.php?action=file&id=$leastview[id]\"><b>$leastview[name]</b></a>]
<br>
[with <b>$leastview[views]</b> views]
<hr>
The most popular file based on views is
<br>
[<a href=\"files.php?action=file&id=$mostview[id]\"><b>$mostview[name]</b></a>]
<br>
[with <b>$mostview[views]</b> views]
<hr>
The least popular file based on ratings is
<br>
[<a href=\"files.php?action=file&id=$leastpopular[id]\"><b>$leastpopular[name]</b></a>]
<br>
[with a rating of <b>$least/10</b>. $stars_least]
<hr>
The most popular file based on ratings is
<br>
[<a href=\"files.php?action=file&id=$mostpopular[id]\"><b>$mostpopular[name]</b></a>]
<br>
[with a rating of <b>$most/10</b>. $stars_most]
<hr>
The least popular file based on downloads is
<br>
[<a href=\"files.php?action=file&id=$leastdownload[id]\"><b>$leastdownload[name]</b></a>]
<br>
[with <b>$leastdownload[dls]</b> downloads]
<hr>
The most popular file based on downloads is
[<a href=\"files.php?action=file&id=$mostdownload[id]\"><b>$mostdownload[name]</b></a>]
<br>
[with <b>$mostdownload[dls]</b> downloads]
<hr>
There have been <b>$totaldownloads</b> total downloads
<br>
The average file rating is <b>$average/10</b>. $stars_average
<br>
The average amount of downloads each file has is <b>$averagedownloads</b>
</marquee>";
}else{
$content = "Sorry!! But Download center v2 Does not exists.<br>You have to install it first.";
}

?>
