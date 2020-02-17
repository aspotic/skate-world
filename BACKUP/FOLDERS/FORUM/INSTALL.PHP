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

set_time_limit (1000);
require "./config.php";
require "./functions.php";
require "./db/$database.php";
require "./xmb.php";

$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
$hackname = "Xmb Ultimate Portal v 2.0.0";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<style type="text/css">
body{
background: #BEC6B9;
color:#000000;
font:12px Verdana,Arial,Helvetica,sans-serif;
scrollbar-3dlight-color:#d1d7dc;
scrollbar-arrow-color:#006699;
scrollbar-darkshadow-color:#98aab1;
scrollbar-face-color:#dee3e7;
scrollbar-highlight-color:#ffffff;
scrollbar-shadow-color:#dee3e7;
scrollbar-track-color:#efefef
}
font, th, td{color:#000000; font:12px Verdana,Arial,Helvetica,sans-serif}
a:link, a:active, a:visited, a.postlink:link{color:#465041;text-decoration:none}
a:hover{color:#dd6900;text-decoration:underline}
.bodyline{background:#ffffff;border:1px solid #465041}
.formline{background:#ffffff;border:1px solid #465041}
.copy{font:10px Verdana,Arial,Helvetica,sans-serif}
a.copy{color:#444444}
a.copy:hover{color:#000000;text-decoration:underline}
input, textarea, select{
border-color:#000000;
color:#000000;
font:normal 11px Verdana,Arial,Helvetica,sans-serif}
input.post, textarea.post, select{background:#ffffff}
input{text-indent:2px}
input.button{background:#efefef; color:#000000; font:11px Verdana,Arial,Helvetica,sans-serif}
.code{
	background:#fafafa;
	border:1px solid #d1d7dc;
	color:#006600;
	font:11px Courier,'Courier New',sans-serif;
	padding-bottom : 10px;
	padding-left : 10px;
	padding-right : 10px;
	padding-top : 11px;
	text-align : left;
	padding : 11px 11px 11px 11px;
	white-space : pre;
}
.header{color:#eaedf4; font:12px Verdana,Arial,Helvetica,sans-serif}
</style>
<title><?=$hackname?>  Installer</title>
</head>
<body>
<!-- <?=$hackname?> -->
<table cellspacing="0" cellpadding="100" border="0" width="99%" height="100%" align="center" class="bodyline">
<tr><td bgcolor="#FFFFFF" align="center" valign="center">
<?
if(empty($_POST['nextstep']) AND empty($_POST['agreesubmit']) AND empty($_POST['uninstallold']) AND empty($_POST['next1']) AND empty($_POST['doinstall']) AND empty($_POST['next2'])) {
?>
<form method="post" action="install.php">
<table cellpadding="1" cellspacing="1" border="0" width="60%" height="60%" class="formline">
<tr><td bgcolor="#FAFAFA">
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
<tr>
<td colspan="2" width="100%">
<table cellspacing="2" cellpadding="4" border="0" width="100%" align="center">
<tr>
<td bgcolor="#8c9b84" colspan="6" class="header"><b> <- Installation Instructions -></b></td>
</tr>
<tr>
<td bgcolor="#EAECE8">
<pre>
Thanks for choosing <b><?=$hackname?></b><br>
This installation file will Guide you through
to have a successful installation..
First make Sure you do not upload any files
right now In the next step you will be asking
if you Other version of portal ..
So this script will Uninstall the old version,
and delete any files and database & templates
that used by this hack.

To proceed Click Next.

</pre>
<input type="submit" name="nextstep" value=" Next ">
</td>
</tr>
</table>
</td>
</tr>
</table>
</table>
</form>

<?
}

if(isset($_POST['nextstep']) AND empty($_POST['agreesubmit']) AND empty($_POST['uninstallold']) AND empty($_POST['next1']) AND empty($_POST['doinstall']) AND empty($_POST['next2'])) {

?>
<form method="post" action="install.php">
<table cellpadding="1" cellspacing="1" border="0" width="60%" height="60%" class="formline">
<tr><td bgcolor="#FAFAFA">
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
<tr>
<td colspan="2" width="100%">
<table cellspacing="2" cellpadding="4" border="0" width="100%" align="center">
<tr>
<td bgcolor="#8c9b84" colspan="6" class="header"><b> <- License Agreement -></b></td>
</tr>
<tr>
<td bgcolor="#EAECE8">
<pre>
	This hack and all code contained within may not be posted or
    redistributed without permission from <b>Freewill46 Tech Forum</b>.
	All copyright information, found within this hack, may not be
    removed for any reason and must remain visible at all times.
    And can't be edited for any reason.
    
	Do you agree to these terms?
</pre>
<input type="submit" name="agreesubmit" value="Agree">&nbsp;&nbsp;&nbsp;<input type="submit" name="cancelsubmit" value="Disagree">
</td>
</tr>
</table>
</td>
</tr>
</table>
</table>
</form>

<?
}

if(isset($_POST['agreesubmit']) AND empty($_POST['uninstallold']) AND empty($_POST['next1']) AND empty($_POST['doinstall']) AND empty($_POST['next2'])) {
?>
<form method="post" action="install.php">
<table cellpadding="1" cellspacing="1" border="0" width="60%" height="60%" class="formline">
<tr><td bgcolor="#FAFAFA">
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
<tr>
<td colspan="2" width="100%">
<table cellspacing="2" cellpadding="4" border="0" width="100%" align="center">
<tr>
<td bgcolor="#8c9b84" colspan="6" class="header"><b> <- Version Check -></b></td>
</tr>
<tr>
<td bgcolor="#EAECE8">
<pre>

 In this Part we just want to make sure you are not
 Running any other version of this hack ..
 If you have the other version installed on your board
 Click on <b>Uninstall old</b> And the script will uninstall it
 For.. any files and data was used in this hack will be removed.
 Otherwise click on <b> Install fresh </b> if don't have the older
 Version running . decide what to do ..

</pre>
<input type="submit" name="uninstallold" value="Uninstall old">&nbsp;&nbsp;&nbsp;<input type="submit" name="doinstall" value="Install fresh">
</td>
</tr>
</table>
</td>
</tr>
</table>
</table>
</form>

<?
}

if(isset($_POST['uninstallold']) AND empty($_POST['next1']) AND empty($_POST['doinstall']) AND empty($_POST['next2'])) {

?>
<form method="post" action="install.php">
<table cellpadding="1" cellspacing="1" border="0" width="60%" height="60%" class="formline">
<tr><td bgcolor="#FAFAFA">
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
<tr>
<td colspan="2" width="100%">
<table cellspacing="2" cellpadding="4" border="0" width="100%" align="center">
<tr>
<td bgcolor="#8c9b84" colspan="6" class="header"><b> <- Uninstalling Old versions -></b></td>
</tr>
<tr>
<td bgcolor="#EAECE8">
<pre>

          Please wait ............
          
<?
             echo "<pre>";
         if(file_exists("./portal.php")) {
             $filename1 = "portal.php";
             echo "Deleting <b>$filename1</b> File from your forum Directory<br>";
             @unlink($filename1);
         }
         if(file_exists("./portal_admin.php")) {
             $filename2 = "portal_admin.php";
             echo "Deleting <b>$filename2</b> File from your forum Directory<br>";
             @unlink($filename2);
         }
         if(file_exists("./portal_pages.php")) {
             $filename3 = "portal_pages.php";
             echo "Deleting <b>$filename3</b> File from your forum Directory<br>";
             @unlink($filename3);
         }
         if(file_exists("./Shout.php")) {
             $filename4 = "Shout.php";
             echo "Deleting <b>$filename4</b> File from your forum Directory<br>";
             @unlink($filename4);
         }
         if(file_exists("./portal.xmb")) {
             $filename5 = "portal.xmb";
             echo "Deleting <b>$filename5</b> File from your forum Directory<br>";
             @unlink($filename5);
         }
             echo "Droping Table ".$tablepre."portal_config<br>";
         $db->query("DROP TABLE IF EXISTS ".$tablepre."portal_config;");
             echo "Droping Table <b>".$tablepre."portal_pages</b><br>";
         $db->query("DROP TABLE IF EXISTS ".$tablepre."portal_pages;");
             echo "Droping Table <b>".$tablepre."portal_pages_count</b><br>";
         $db->query("DROP TABLE IF EXISTS ".$tablepre."portal_pages_count;");
             echo "Droping Table <b>".$tablepre."portal_shoutbox</b><br>";
         $db->query("DROP TABLE IF EXISTS ".$tablepre."portal_shoutbox;");
             echo "Droping Table <b>".$tablepre."portal_templates</b><br>";
         $db->query("DROP TABLE IF EXISTS ".$tablepre."portal_templates;");
             echo "Droping Table <b>".$tablepre."portal_message</b><br>";
         $db->query("DROP TABLE IF EXISTS ".$tablepre."portal_message;");
             echo "Deleting Portal template From Table <b>".$tablepre."templates</b><br>";
	      $db->query("DELETE FROM ".$tablepre."templates WHERE name LIKE 'portal_%'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_editselect'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_blockedit'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_blockadd'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_blockselect'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_menu'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_settings'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_portal_pages'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_pages_select'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_pages_edit'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_pages_add'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name='p_admin_message'");
          $db->query("DELETE FROM ".$tablepre."templates WHERE name=''");
      
             echo "</pre>";
?>

         Uninstalling has been done successfully ...
         You can now click <b>next</b> To proceed the installation.
         
</pre>
<input type="submit" name="next1" value=" Next ">
</td>
</tr>
</table>
</td>
</tr>
</table>
</table>
</form>
<?
}

if(isset($_POST['next1']) AND empty($_POST['doinstall']) AND empty($_POST['next2'])) {
?>
<form method="post" action="install.php">
<table cellpadding="1" cellspacing="1" border="0" width="60%" height="60%" class="formline">
<tr><td bgcolor="#FAFAFA">
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
<tr>
<td colspan="2" width="100%">
<table cellspacing="2" cellpadding="4" border="0" width="100%" align="center">
<tr>
<td bgcolor="#8c9b84" colspan="6" class="header"><b> <- Installation Ready -></b></td>
</tr>
<tr>
<td bgcolor="#EAECE8">
<pre>
This part of the installation will create any necessary
database tables and insert any data that is needed.
In order to complete the installation of this hack,
you will need to follow the instructions provided
in the accompanied In the next step.
</pre>
<input type="submit" name="doinstall" value="Install">
</td>
</tr>
</table>
</td>
</tr>
</table>
</table>
</form>
<?
}

if(isset($_POST['doinstall']) AND empty($_POST['next2'])) {

?>
<form method="post" action="install.php">
<table cellpadding="1" cellspacing="1" border="0" width="60%" height="60%" class="formline">
<tr><td bgcolor="#FAFAFA">
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
<tr>
<td colspan="2" width="100%">
<table cellspacing="2" cellpadding="4" border="0" width="100%" align="center">
<tr>
<td bgcolor="#8c9b84" colspan="6" class="header"><b> <- Installation Progress -></b></td>
</tr>
<tr>
<td bgcolor="#EAECE8">
<pre>

  please Wait  .....
  Installation started ...
<?

//------------------------------------------------------------

echo "Creating ".$tablepre."portal_autonews<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_autonews;");
$db->query("CREATE TABLE ".$tablepre."portal_autonews (
  anid int(11) NOT NULL auto_increment,
  catid int(11) NOT NULL default '0',
  aid varchar(30) NOT NULL default '',
  title varchar(80) NOT NULL default '',
  time varchar(19) NOT NULL default '',
  hometext text NOT NULL,
  bodytext text NOT NULL,
  topic int(3) NOT NULL default '1',
  informant varchar(20) NOT NULL default '',
  notes text NOT NULL,
  ihome int(1) NOT NULL default '0',
  acomm int(1) NOT NULL default '0',
  associated text NOT NULL,
  PRIMARY KEY  (anid),
  KEY anid (anid)
) TYPE=MyISAM");

//--------------------------------------------------------------

echo "Creating ".$tablepre."portal_banner<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_banner;");
$db->query("CREATE TABLE ".$tablepre."portal_banner (
  bid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  imptotal int(11) NOT NULL default '0',
  impmade int(11) NOT NULL default '0',
  clicks int(11) NOT NULL default '0',
  imageurl varchar(100) NOT NULL default '',
  clickurl varchar(200) NOT NULL default '',
  alttext varchar(255) NOT NULL default '',
  date datetime default NULL,
  dateend datetime default NULL,
  type tinyint(1) NOT NULL default '0',
  active tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (bid),
  KEY bid (bid),
  KEY cid (cid)
) TYPE=MyISAM");

//---------------------------------------------------------------

echo "Creating ".$tablepre."portal_bannerclient<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_bannerclient;");
$db->query("CREATE TABLE ".$tablepre."portal_bannerclient (
  cid int(11) NOT NULL auto_increment,
  name varchar(60) NOT NULL default '',
  contact varchar(60) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  login varchar(10) NOT NULL default '',
  passwd varchar(10) NOT NULL default '',
  extrainfo text NOT NULL,
  PRIMARY KEY  (cid),
  KEY cid (cid)
) TYPE=MyISAM");

//---------------------------------------------------------------

echo "Creating ".$tablepre."portal_blocks<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_blocks;");
$db->query("CREATE TABLE ".$tablepre."portal_blocks (
  bid int(10) NOT NULL auto_increment,
  bkey varchar(15) NOT NULL default '',
  title varchar(60) NOT NULL default '',
  content text NOT NULL,
  url varchar(200) NOT NULL default '',
  bposition char(1) NOT NULL default '',
  weight int(1) NOT NULL default '1',
  active int(1) NOT NULL default '1',
  blockfile varchar(255) NOT NULL default '',
  view int(1) NOT NULL default '0',
  PRIMARY KEY  (bid),
  KEY title (title),
  KEY bid (bid)
) TYPE=MyISAM");

//--------------------------------------------------------------

echo "Creating ".$tablepre."portal_comments<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_comments;");
$db->query("CREATE TABLE ".$tablepre."portal_comments (
  tid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  storyid int(11) NOT NULL default '0',
  date datetime default NULL,
  name varchar(60) NOT NULL default '',
  email varchar(60) default NULL,
  url varchar(60) default NULL,
  host_name varchar(60) default NULL,
  subject varchar(85) NOT NULL default '',
  comment text NOT NULL,
  score tinyint(4) NOT NULL default '0',
  reason tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (tid),
  KEY tid (tid),
  KEY pid (pid),
  KEY sid (storyid)
) TYPE=MyISAM");

//---------------------------------------------------------------

echo "Creating ".$tablepre."portal_config<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_config;");
$db->query("CREATE TABLE ".$tablepre."portal_config (
  anonpost tinyint(1) NOT NULL default '0',
  commentlimit int(9) NOT NULL default '4096',
  anonymous varchar(255) NOT NULL default '',
  articlecomm tinyint(1) NOT NULL default '1',
  storyhome int(2) NOT NULL default '10',
  oldnum int(2) NOT NULL default '30',
  banners tinyint(1) NOT NULL default '1',
  locale varchar(10) NOT NULL default '',
  displaynews set('on','off') NOT NULL default '',
  numaccounts tinyint(2) NOT NULL default '-1',
  admingraphic tinyint(1) NOT NULL default '1',
  copyright text NOT NULL,
  orderview set('on','off') NOT NULL default '',
  news_fid tinyint(10) NOT NULL default '0',
  news_list_this_many tinyint(5) NOT NULL default '0',
  date_format varchar(50) NOT NULL default '',
  msg_length_limit varchar(32) NOT NULL default '',
  dgif varchar(50) NOT NULL default '',
  CensorMode tinyint(1) NOT NULL default '3',
  CensorReplace varchar(10) NOT NULL default '',
  portal_status set('on','off') NOT NULL default '',
  portal_invisible set('on','off') NOT NULL default '',
  portal_secure set('on','off') NOT NULL default '',
  portal_online set('on','off') NOT NULL default '',
  show_ticker set('on','off') NOT NULL default ''
) TYPE=MyISAM");

//----------------------------------------------------------------

echo "Creating ".$tablepre."portal_main<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_main;");
$db->query("CREATE TABLE ".$tablepre."portal_main (
  main_module varchar(255) NOT NULL default ''
) TYPE=MyISAM");

//---------------------------------------------------------------

echo "Creating ".$tablepre."portal_message<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_message;");
$db->query("CREATE TABLE ".$tablepre."portal_message (
  mid int(11) NOT NULL auto_increment,
  title varchar(100) NOT NULL default '',
  content text NOT NULL,
  date varchar(14) NOT NULL default '',
  expire int(7) NOT NULL default '0',
  active int(1) NOT NULL default '1',
  view int(1) NOT NULL default '1',
  PRIMARY KEY  (mid),
  UNIQUE KEY mid (mid)
) TYPE=MyISAM");

//--------------------------------------------------------------

echo "Creating ".$tablepre."portal_modules<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_modules;");
$db->query("CREATE TABLE ".$tablepre."portal_modules (
  mid int(10) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  custom_title varchar(255) NOT NULL default '',
  active int(1) NOT NULL default '0',
  view int(1) NOT NULL default '0',
  inmenu tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (mid),
  KEY title (title),
  KEY custom_title (custom_title)
) TYPE=MyISAM");

//--------------------------------------------------------------

echo "Creating ".$tablepre."portal_poll_check<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_poll_check;");
$db->query("CREATE TABLE ".$tablepre."portal_poll_check (
  ip varchar(20) NOT NULL default '',
  time varchar(14) NOT NULL default '',
  pollID int(10) NOT NULL default '0'
) TYPE=MyISAM");

//--------------------------------------------------------------

echo "Creating ".$tablepre."portal_poll_data<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_poll_data;");
$db->query("CREATE TABLE ".$tablepre."portal_poll_data (
  pollID int(11) NOT NULL default '0',
  optionText char(50) NOT NULL default '',
  optionCount int(11) NOT NULL default '0',
  voteID int(11) NOT NULL default '0'
) TYPE=MyISAM");

//---------------------------------------------------------------

echo "Creating ".$tablepre."portal_poll_desc<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_poll_desc;");
$db->query("CREATE TABLE ".$tablepre."portal_poll_desc (
  pollID int(11) NOT NULL auto_increment,
  pollTitle varchar(100) NOT NULL default '',
  timeStamp int(11) NOT NULL default '0',
  voters mediumint(9) NOT NULL default '0',
  artid int(10) NOT NULL default '0',
  PRIMARY KEY  (pollID),
  KEY pollID (pollID)
) TYPE=MyISAM");

//---------------------------------------------------------------

echo "Creating ".$tablepre."portal_queue<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_queue;");
$db->query("CREATE TABLE ".$tablepre."portal_queue (
  qid smallint(5) unsigned NOT NULL auto_increment,
  uid mediumint(9) NOT NULL default '0',
  uname varchar(40) NOT NULL default '',
  subject varchar(100) NOT NULL default '',
  story text,
  storyext text NOT NULL,
  timestamp datetime NOT NULL default '0000-00-00 00:00:00',
  topic varchar(20) NOT NULL default '',
  PRIMARY KEY  (qid),
  KEY qid (qid),
  KEY uid (uid),
  KEY uname (uname)
) TYPE=MyISAM");

//----------------------------------------------------------------

echo "Creating ".$tablepre."portal_related<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_related;");
$db->query("CREATE TABLE ".$tablepre."portal_related (
  rid int(11) NOT NULL auto_increment,
  tid int(11) NOT NULL default '0',
  name varchar(30) NOT NULL default '',
  url varchar(200) NOT NULL default '',
  PRIMARY KEY  (rid),
  KEY rid (rid),
  KEY tid (tid)
) TYPE=MyISAM");

//----------------------------------------------------------------

echo "Creating ".$tablepre."portal_reviews<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_reviews;");
$db->query("CREATE TABLE ".$tablepre."portal_reviews (
  id int(10) NOT NULL auto_increment,
  date date NOT NULL default '0000-00-00',
  title varchar(150) NOT NULL default '',
  text text NOT NULL,
  reviewer varchar(20) default NULL,
  email varchar(60) default NULL,
  score int(10) NOT NULL default '0',
  cover varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  url_title varchar(50) NOT NULL default '',
  hits int(10) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM");

//------------------------------------------------------------------

echo "Creating ".$tablepre."portal_reviews_add<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_reviews_add;");
$db->query("CREATE TABLE ".$tablepre."portal_reviews_add (
  id int(10) NOT NULL auto_increment,
  date date default NULL,
  title varchar(150) NOT NULL default '',
  text text NOT NULL,
  reviewer varchar(20) NOT NULL default '',
  email varchar(60) default NULL,
  score int(10) NOT NULL default '0',
  url varchar(100) NOT NULL default '',
  url_title varchar(50) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM");

//------------------------------------------------------------------

echo "Creating ".$tablepre."portal_reviews_comments<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_reviews_comments;");
$db->query("CREATE TABLE ".$tablepre."portal_reviews_comments (
  cid int(10) NOT NULL auto_increment,
  rid int(10) NOT NULL default '0',
  userid varchar(25) NOT NULL default '',
  date datetime default NULL,
  comments text,
  score int(10) NOT NULL default '0',
  PRIMARY KEY  (cid),
  KEY cid (cid),
  KEY rid (rid),
  KEY userid (userid)
) TYPE=MyISAM");

//--------------------------------------------------------------------

echo "Creating ".$tablepre."portal_reviews_main<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_reviews_main;");
$db->query("CREATE TABLE ".$tablepre."portal_reviews_main (
  title varchar(100) default NULL,
  description text
) TYPE=MyISAM");

//-------------------------------------------------------------------

echo "Creating ".$tablepre."portal_seccont<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_seccont;");
$db->query("CREATE TABLE ".$tablepre."portal_seccont (
  artid int(11) NOT NULL auto_increment,
  secid int(11) NOT NULL default '0',
  title text NOT NULL,
  content text NOT NULL,
  counter int(11) NOT NULL default '0',
  PRIMARY KEY  (artid),
  KEY artid (artid),
  KEY secid (secid)
) TYPE=MyISAM");

//------------------------------------------------------------------

echo "Creating ".$tablepre."portal_sections<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_sections;");
$db->query("CREATE TABLE ".$tablepre."portal_sections (
  secid int(11) NOT NULL auto_increment,
  secname varchar(40) NOT NULL default '',
  image varchar(50) NOT NULL default '',
  PRIMARY KEY  (secid),
  KEY secid (secid)
) TYPE=MyISAM");

//-------------------------------------------------------------------

echo "Creating ".$tablepre."portal_stories<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_stories;");
$db->query("CREATE TABLE ".$tablepre."portal_stories (
  storyid int(11) NOT NULL auto_increment,
  catid int(11) NOT NULL default '0',
  aid varchar(30) NOT NULL default '',
  title varchar(80) default NULL,
  time datetime default NULL,
  hometext text,
  bodytext text NOT NULL,
  comments int(11) default '0',
  counter mediumint(8) unsigned default NULL,
  topic int(3) NOT NULL default '1',
  informant varchar(20) NOT NULL default '',
  notes text NOT NULL,
  ihome int(1) NOT NULL default '0',
  acomm int(1) NOT NULL default '0',
  haspoll int(1) NOT NULL default '0',
  pollID int(10) NOT NULL default '0',
  score int(10) NOT NULL default '0',
  ratings int(10) NOT NULL default '0',
  associated text NOT NULL,
  PRIMARY KEY  (storyid),
  KEY sid (storyid),
  KEY catid (catid),
  KEY counter (counter),
  KEY topic (topic)
) TYPE=MyISAM");

//-------------------------------------------------------------------

echo "Creating ".$tablepre."portal_stories_cat<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_stories_cat;");
$db->query("CREATE TABLE ".$tablepre."portal_stories_cat (
  catid int(11) NOT NULL auto_increment,
  title varchar(20) NOT NULL default '',
  counter int(11) NOT NULL default '0',
  PRIMARY KEY  (catid),
  KEY catid (catid)
) TYPE=MyISAM");

//-------------------------------------------------------------------

echo "Creating ".$tablepre."portal_topics<br>";

$db->query("DROP TABLE IF EXISTS ".$tablepre."portal_topics;");
$db->query("CREATE TABLE ".$tablepre."portal_topics (
  topicid int(3) NOT NULL auto_increment,
  topicname varchar(20) default NULL,
  topicimage varchar(20) default NULL,
  topictext varchar(40) default NULL,
  counter int(11) NOT NULL default '0',
  PRIMARY KEY  (topicid),
  KEY topicid (topicid)
) TYPE=MyISAM");

//-------------------------------------------------------------------

echo "Inserting data into ".$tablepre."portal_blocks<br />";

$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (1, '', 'Search', '<script language=\'JavaScript\'> \r\nvar MyForm; \r\nfunction StartSearch(){ \r\ndocument.forms[MyForm+document.InputMyForm.SearchSelect.selectedIndex].elements[0].value=document.InputMyForm.SearchWords.value; \r\ndocument.forms[MyForm+document.InputMyForm.SearchSelect.selectedIndex].submit(); \r\n} \r\n</script> \r\n\r\n<font class=\'mediumtxt\'>\r\n<center><form Name=\'InputMyForm\'>  Search For: <br><input name=\'SearchWords\' type=text\r\nsize=20><br> \r\n<br>Engine: <br><select Name=\'SearchSelect\'> \r\n<option selected>Google  \r\n<option>Yahoo \r\n<option>Altavista \r\n</select> \r\n<br><br> \r\n<input type=button value=\'Search !\' onClick=\'StartSearch()\'> \r\n</form> \r\n \r\n<script language=\'JavaScript\'>MyForm=document.forms.length</script> \r\n \r\n<form method=GET action=\'http://www.google.com/search\' target=\'blank\'> \r\n<input TYPE=\'hidden\' name=q size=16 maxlength=100 value=''> \r\n</form> \r\n \r\n<form action=\'http://search.yahoo.com/bin/search\' method=get target=\'blank\'> \r\n<input type=\'hidden\' size=16 name=p> \r\n</form> \r\n \r\n<form action=\'http://www.altavista.com/sites/search/web\' name=mfrm target=\'blank\'> \r\n<INPUT TYPE=\'hidden\' class=s type=text name=q MAXLENGTH=100 SIZE=16> \r\n</FORM> \r\n</center> \r\n \r\n</font> ', '', 'r', 3, 1, '', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (2, '', 'Who\'s Online', '', '', 'l', 3, 1, 'block-whos_online.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (3, '', 'latest_replies', '', '', 'l', 4, 1, 'block-latest_replies.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (5, '', 'forum Staff', '', '', 'r', 4, 1, 'block-staff.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (7, '', 'Modules', '', '', 'l', 2, 1, 'block-modules.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (8, '', 'Survey', '', '', 'r', 6, 1, 'block-Survey.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (9, 'admin', 'Administration', '<strong><big>·</big></strong> <a href=\"portal_admin.php\">Administration</a><br>\r\n<strong><big>·</big></strong> <a href=\"portal_admin.php?op=adminStory\">New Story</a><br>\r\n<strong><big>·</big></strong> <a href=\"portal_admin.php?op=create\">Change Survey</a><br>\r\n<strong><big>·</big></strong> <a href=\"misc.php?action=logout\">Logout</a>', '', 'l', 5, 1, '', 2)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (14, '', 'main menu', '', '', 'c', 1, 1, 'block-main_menu.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (10, 'userbox', 'User\'s Custom Box', '', '', 'r', 5, 1, '', 1)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (11, '', 'Login', '', '', 'r', 1, 1, 'block-Login.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (12, '', 'Forum menu', '', '', 'l', 1, 1, 'block-Forum-menu.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (15, '', 'forum stats', '', '', 'r', 7, 1, 'block-forum_stats.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (16, '', 'Random member moods', '', '', 'l', 6, 1, 'block-random_member_moods.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (18, '', 'Latest members', '', '', 'l', 7, 1, 'block-Latest_members.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (19, '', 'Top members', '', '', 'r', 8, 1, 'block-Top_members.php', 0)");
$db->query("INSERT INTO ".$tablepre."portal_blocks VALUES (22, '', 'Quick register', '', '', 'r', 2, 1, 'block-Quick_register.php', 3)");

echo "Inserting data into ".$tablepre."portal_config<br />";

$db->query("INSERT INTO ".$tablepre."portal_config VALUES (1, 4096, 'Anonymous', 1, 5, 10, 0, 'en_US', 'on', 0, 1, '<p align=\"center\">Xmb Ultimate Portal v 2.0<br /><a href=\"http://www.fw46.com/eforum\" target=\"_blank\"><b>Freewill46</b></a> &copy; 2003 - 2004 </font></p>\r\n', 'on', 1, 10, 'jS F Y', '300', 'info.gif', 3, '*****', 'on', 'off', 'off', 'on', 'on')");

echo "Inserting data into ".$tablepre."portal_main<br />";

$db->query("INSERT INTO ".$tablepre."portal_main VALUES ('News')");

echo "Inserting data into ".$tablepre."portal_modules<br />";

$db->query("INSERT INTO ".$tablepre."portal_modules VALUES (1, 'News', 'News', 1, 0, 1)");
$db->query("INSERT INTO ".$tablepre."portal_modules VALUES (2, 'Reviews', 'Reviews', 1, 2, 1)");
$db->query("INSERT INTO ".$tablepre."portal_modules VALUES (3, 'Sections', 'Sections', 1, 0, 1)");
$db->query("INSERT INTO ".$tablepre."portal_modules VALUES (4, 'Topics', 'Topics', 1, 0, 1)");
$db->query("INSERT INTO ".$tablepre."portal_modules VALUES (5, 'Submit_News', 'Submit News', 1, 1, 1)");
$db->query("INSERT INTO ".$tablepre."portal_modules VALUES (6, 'Surveys', 'Surveys', 1, 0, 1)");
$db->query("INSERT INTO ".$tablepre."portal_modules VALUES (7, 'Member_Account', 'Member Account', 1, 1, 1)");

echo "Inserting data into ".$tablepre."portal_poll_check<br />";

$db->query("INSERT INTO ".$tablepre."portal_poll_check VALUES ('127.0.0.1', '1073341812', 1)");
$db->query("INSERT INTO ".$tablepre."portal_poll_check VALUES ('127.0.0.1', '1073341799', 4)");

echo "Inserting data into ".$tablepre."portal_poll_data<br />";

$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 12)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 11)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 10)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 9)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 8)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 7)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 6)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 5)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 4)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, '', 0, 3)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, 'Xmb Ultimate Portal v 2.0', 0, 2)");
$db->query("INSERT INTO ".$tablepre."portal_poll_data VALUES (5, 'Xmb Ultimate Portal v 1.0', 0, 1)");

echo "Inserting data into ".$tablepre."portal_poll_desc<br />";

$db->query("INSERT INTO ".$tablepre."portal_poll_desc VALUES (5, 'Which version of U-Portal is the best', 1073410484, 0, 0)");

echo "Inserting data into ".$tablepre."portal_reviews_main<br />";

$db->query("INSERT INTO ".$tablepre."portal_reviews_main VALUES ('Reviews Section Title', 'Reviews Section Long Description')");

echo "Inserting data into ".$tablepre."portal_stories<br />";

$db->query("INSERT INTO ".$tablepre."portal_stories VALUES (27, 0, 'freewill46', 'U-Portal Demo topic', '2004-01-08 21:42:51', 'Congratulations! You have now the Xmb Ultimate Portal  installed!  You can edit or change this message from the Administration page. \r\n\r\nIn Portal Cpanel You can control the whole portal ...\r\n\r\nEnjoy it ....', '', 0, 1, 4, 'freewill46', '', 0, 0, 0, 0, 0, 0, '')");

echo "Inserting data into ".$tablepre."portal_topics<br />";

$db->query("INSERT INTO ".$tablepre."portal_topics VALUES (4, 'U-Portal', 'topic.gif', 'About portal', 0)");
$db->query("ALTER TABLE ".$tablepre."members ADD ublockon tinyint(1) NOT NULL default '0'");
$db->query("ALTER TABLE ".$tablepre."members ADD ublock tinytext NOT NULL");
$db->query("ALTER TABLE ".$tablepre."members ADD counter int(11) NOT NULL default '0'");

?>
<br>
<br>
  Databases Installation Completed successfully
  Now click <b>Last Instructions</b>
  To See how to modify the needed files ..
</pre>
<input type="submit" name="next2" value="Last Instructions">
</td>
</tr>
</table>
</td>
</tr>
</table>
</table>
</form>

<?
}

if(isset($_POST['next2'])) {


?>
<form method="post" action="install.php">
<table cellpadding="1" cellspacing="1" border="0" width="100%" height="100%" class="formline">
<tr><td bgcolor="#FAFAFA">
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
<tr>
<td colspan="2" width="100%">
<table cellspacing="2" cellpadding="4" border="0" width="100%" align="left">
<tr>
<td bgcolor="#8c9b84" colspan="6" class="header"><b> <- Final Instructions -></b></td>
</tr>
<tr>
<td bgcolor="#EAECE8" align="left">
<pre>
Read carefully the instructions below
to add the codes in the right place
without facing any problems or errors..
</pre>

<b><br>
    Edit header.php:</b><p><b>find:</b></p>
    <blockquote class="code">
    // Make all settings global, and put them in the $SETTINGS[] array<br>
    $settingsquery = $db-&gt;query(&quot;SELECT * FROM $table_settings&quot;);<br>
    foreach($db-&gt;fetch_array($settingsquery) as $key =&gt; $val) {<br>
    $$key = $val;<br>
    $SETTINGS[$key] = $val;<br>
    }<br>
&nbsp;</blockquote>
<br>

    <b>ADD JUST BELOW THIS CODE </b><p>
    <blockquote class="code">

    <p>// BEGIN PORTAL FUNCTIONS<br>
    $pquery = mysql_query(&quot;SELECT * FROM &quot;.$tablepre.&quot;portal_config&quot;);<br>
    $PORTAL = mysql_fetch_array($pquery);<br>
    $forumurl = &quot;&quot;;<br>
    $mainfor = &quot;&quot;;<br>
    switch($action) {<br>
    case 'showindex':<br>
    break;<br>
    }<br>
    if(isset($full_url) &amp;&amp; $PORTAL['portal_status'] != &quot;off&quot;) {<br>
    $lang_pf = &quot;Forums Page&quot;;<br>
    $forumurl = &quot; | &lt;a href=\&quot;index.php?action=showindex\&quot;&gt;$lang_pf&lt;/a&gt;&quot;;<br>
    $pforumurl = &quot;&lt;a href=\&quot;index.php?action=showindex\&quot;&gt;$lang_pf&lt;/a&gt;&quot;;<br>
    mysql_query(&quot;UPDATE &quot;.$tablepre.&quot;portal_config SET orderview='on'&quot;);<br>
    }elseif(isset($full_url) &amp;&amp; $PORTAL['portal_status'] == &quot;off&quot;) {<br>
    $lang_pf = &quot;Portal Page&quot;;<br>
    $lang_for = &quot;Forums&quot;;<br>
    $mainfor = &quot;&lt;li&gt;&lt;a href=\&quot;index.php\&quot;&gt;$lang_for&lt;/a&gt;&lt;/li&gt;&lt;br&gt;&quot;;<br>
    $forumurl = &quot; | &lt;a href=\&quot;index.php?action=showportal\&quot;&gt;$lang_pf&lt;/a&gt;&quot;;<br>
    $pforumurl = &quot;&lt;a href=\&quot;index.php?action=showportal\&quot;&gt;$lang_pf&lt;/a&gt;&quot;;<br>
    mysql_query(&quot;UPDATE &quot;.$tablepre.&quot;portal_config SET orderview='off'&quot;);<br>
    }<br>
    if($action == &quot;showindex&quot; &amp;&amp; $PORTAL['portal_status'] != &quot;off&quot;) {<br>
    $lang_pf = &quot;Forums Page&quot;;<br>
    $forumurl = &quot; | &lt;a href=\&quot;index.php?action=showindex\&quot;&gt;$lang_pf&lt;/a&gt;&quot;;<br>
    $pforumurl = &quot;&lt;a href=\&quot;index.php?action=showindex\&quot;&gt;$lang_pf&lt;/a&gt;&quot;;<br>
    mysql_query(&quot;UPDATE &quot;.$tablepre.&quot;portal_config SET orderview='off'&quot;);<br>
    }elseif($action == &quot;showportal&quot; &amp;&amp; $PORTAL['portal_status'] == &quot;off&quot;) {<br>
    $lang_pf = &quot;Portal Page&quot;;<br>
    $lang_for = &quot;Forums&quot;;<br>
    $mainfor = &quot;&lt;li&gt;&lt;a href=\&quot;index.php\&quot;&gt;$lang_for&lt;/a&gt;&lt;/li&gt;&lt;br&gt;&quot;;<br>
    $forumurl = &quot; | &lt;a href=\&quot;index.php?action=showportal\&quot;&gt;$lang_pf&lt;/a&gt;&quot;;<br>
    $pforumurl = &quot;&lt;a href=\&quot;index.php?action=showportal\&quot;&gt;$lang_pf&lt;/a&gt;&quot;;<br>
    mysql_query(&quot;UPDATE &quot;.$tablepre.&quot;portal_config SET orderview='on'&quot;);<br>
    }<br>
    // END PORTAL FUNCTIONS</p>

</blockquote>
<br>
<b>FIND:</b><br>
<blockquote class="code">


<p>// if the user is registered, check for new u2u's<br>
if($xmbuser) {<br>
$query = $db-&gt;query(&quot;SELECT * FROM $table_u2u WHERE msgto = '$xmbuser' AND
folder = 'inbox' AND new = 'yes'&quot;);<br>
$newu2unum = $db-&gt;num_rows($query);<br>
if($newu2unum &gt; 0) {<br>
$newu2umsg = &quot;&lt;a href=\&quot;#\&quot; onclick=\&quot;Popup('u2u.php', 'Window', 550,
450);\&quot;&gt;$lang_newu2u1 $newu2unum $lang_newu2u2&lt;/a&gt;&quot;;<br>
}else{<br>
$newu2umsg = '';<br>
}<br>
}else{<br>
$newu2umsg = '';<br>
}</p>


</blockquote>
<br>
<b>ADD JUST BELOW THIS CODE :</b><br>
 <blockquote class="code">

  <p>$pquery = mysql_query(&quot;SELECT * FROM &quot;.$tablepre.&quot;portal_config&quot;);<br>
    $PORTAL = mysql_fetch_array($pquery);</p>

  </blockquote>
<br>
<b>EDIT index.php <br>
    <br>
    FIND THIS CODE:</b><br>
 <blockquote class="code">

<p>require &quot;./header.php&quot;;</p>

</blockquote>
<br>
<b>ADD JUST BELOW THIS CODE :</b><br>
 <blockquote class="code">

<p>if($PORTAL['orderview'] == &quot;on&quot; &amp;&amp; !$gid) {<br>
Header(&quot;Location: &quot;.$full_url.&quot;portal.php&quot;);<br>
}elseif($PORTAL['orderview'] == &quot;off&quot; || $gid) {</p>

</blockquote>
<br>
<b>FIND :</b><br>
 <blockquote class="code">
<p>eval(&quot;\$index = \&quot;&quot;.template(&quot;index&quot;).&quot;\&quot;;&quot;);<br>
$index = stripslashes($index);<br>
echo $index;<br>
<br>
end_time();<br>
<br>
eval(&quot;\$footer = \&quot;&quot;.template(&quot;footer&quot;).&quot;\&quot;;&quot;);<br>
echo $footer;</p>
</blockquote>
<br>
<b>ADD BELOW THIS :</b><br>
 <blockquote class="code">
<p>}</p>
</blockquote>
<br>
<b>EDIT cp.php, cp2.php, tools.php .</b><p><b>FIND:</b></p>
    <blockquote class="code">
    <p>&lt;a href=&quot;cp2.php?action=cplog&quot;&gt;&lt;?=$lang_cplog?&gt;&lt;/a&gt;</p>
    </blockquote>
<br>
<b>ADD AFTER THIS CODE:</b><br>
<blockquote class="code">
<p>&nbsp;- &lt;a href=&quot;portal_admin.php&quot;&gt;Portal Cpanel&lt;/a&gt;</p>
</blockquote>
<br>
    <b>Edit member.php </b>
    <br>
    <b>FIND: </b>
    <blockquote class="code">
    <p>$db-&gt;query(&quot;INSERT INTO $table_members VALUES</p>
    </blockquote>
<br>
<b>ADD ON THE SAME LINE BEFORE&nbsp;&nbsp;&nbsp; )&quot;);</b><blockquote class="code">
<p>, '', 0, '0'</p>
</blockquote>
<br>

    <b>Edit header template </b>
    <br>
    <b>FIND: </b>
    <blockquote class="code">
    <p>$stats</p>
    </blockquote>
<br>
<b>ADD AFTER THIS CODE:</b><br>
<blockquote class="code">
    <p>$forumurl</p>
    </blockquote>
<br>

<b>Upload all files and folder to your forum directory .<br>
    <br>
    Upload portal_images&nbsp; folder To your images&nbsp; folder .</b><p><b>
    That's all enjoy It ..</b></p>
<br>
<center><a href="index.php"><b>Go to your forum</b></a></center>
<br>
</td>
</tr>
</table>
</td>
</tr>
</table>
</table>
</form>

<?
}

?>
<center><br><font class="copy"><? echo $hackname?> <br /><a href="http://www.fw46.com/eforum" target="_blank"><b>Freewill46</b></a> &copy; 2003 - 2004</font></center>
</td>
</tr>
</table>
</body>
</html>
