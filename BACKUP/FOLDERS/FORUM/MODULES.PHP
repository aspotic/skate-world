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

require_once("portal_main.php");
loadtemplates('header,footer');
$module = 1;

if (isset($modulename)) {
    global $xmbuser, $prefix, $status;
    $sql = "SELECT active, view FROM ".$prefix."_modules WHERE title='$modulename'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $mod_active = $row['active'];
    $view = $row['view'];
    if (($mod_active == 1) OR ($mod_active == 0)) {
	if (!isset($mop)) { $mop="modload"; }
	if (!isset($modulefile)) { $modulefile="index"; }
	if (ereg("\.\.",$modulename) || ereg("\.\.",$modulefile) || ereg("\.\.",$mop)) {
	}
	    if ($view == 0) {
		$modpath = "modules/$modulename/$modulefile.php";
    		if (file_exists($modpath)) {
		    include($modpath);
    		} else {
		    die ("Sorry, such file doesn't exist...");
		}
	    }
	    if ($view == 1 AND $xmbuser) {
	    $modpath .= "modules/$modulename/$modulefile.php";
   		if (file_exists($modpath)) {
	    include($modpath);
   		}else{
	     die ("Sorry, such file doesn't exist...");
		}
	    }elseif ($view == 1 AND !$xmbuser) {
        eval("\$header = \"".template("header")."\";");
        echo $header;
		include("portal_header.php");
		title("$bbname - :!: Access Denied :!:");
		OpenTable();
		echo "<center><b>You are trying to access a restricted area.</b><br><br>"
        ."We are Sorry, but this section of our site is for <i>Registered Users Only.</i><br><br>You can register for free by clicking <a href=\"member.php?action=reg\"><b> Here </b></a> , then you can<br>access this section without restrictions. Thanks.<br><br>"
        ."[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
		CloseTable();
		include("portal_footer.php");
		end_time();
        eval("\$footer = \"".template("footer")."\";");
       	echo $footer;
		die();
	    }
	    if ($view == 2 AND $status == "Super Administrator" || $status == "Administrator") {
		$modpath .= "modules/$modulename/$modulefile.php";
		if (file_exists($modpath)) {
        include($modpath);
		} else {
        die ("Sorry, such file doesn't exist...");
		}	
	    }elseif($view == 2 AND $status != "Super Administrator" || $status != "Administrator") {
        eval("\$header = \"".template("header")."\";");
        echo $header;
		include("portal_header.php");
        title("$bbname - :!: Access Denied :!:");
		OpenTable();
		echo "<center><b>You are trying to access a restricted area.</b><br><br>"
        ."We are Sorry but this section of our site is for <i>Administrators Only.</i><br><br>"
        ."[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
		CloseTable();
		include("portal_footer.php");
		end_time();
        eval("\$footer = \"".template("footer")."\";");
       	echo $footer;
		die();
	    }
        }else {
	    include("portal_header.php");
	    OpenTable();
	    echo "<center>Sorry, this Module isn't active!<br><br>"
	    ."[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]</center>";
	    CloseTable();
	    include("portal_footer.php");
       }
       }else{
    die ("Sorry, you can't access this file directly...");
}

?>
