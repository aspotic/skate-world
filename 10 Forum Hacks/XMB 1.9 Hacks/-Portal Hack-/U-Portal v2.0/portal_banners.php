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

function viewbanner() {
    global $prefix, $status, $sitename, $adminmail, $full_url;
    $numrows = mysql_num_rows(mysql_query("SELECT * FROM ".$prefix."_banner WHERE type='0' AND active='1'"));

/* Get a random banner if exist any. */
/* More efficient random stuff, thanks to Cristian Arroyo from http://www.planetalinux.com.ar */

    if ($numrows>1) {
	$numrows = $numrows-1;
	mt_srand((double)microtime()*1000000);
	$bannum = mt_rand(0, $numrows);
    } else {
	$bannum = 0;
    }
    $sql = "SELECT bid, imageurl, clickurl, alttext FROM ".$prefix."_banner WHERE type='0' AND active='1' LIMIT $bannum,1";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $bid = $row['bid'];
    $imageurl = $row['imageurl'];
    $clickurl = $row['clickurl'];
    $alttext = $row['alttext'];
    
    if ($status != "Super Administrator" && $status != "Administrator") {
    	mysql_query("UPDATE ".$prefix."_banner SET impmade=impmade+1 WHERE bid='$bid'");
    }
    if($numrows>0) {
	$sql2 = "SELECT cid, imptotal, impmade, clicks, date FROM ".$prefix."_banner WHERE bid='$bid'";
	$result2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($result2);
	$cid = $row2['cid'];
	$imptotal = $row2['imptotal'];
	$impmade = $row2['impmade'];
	$clicks = $row2['clicks'];
	$date = $row2['date'];

	if (($imptotal <= $impmade) AND ($imptotal != 0)) {
	    mysql_query("UPDATE ".$prefix."_banner SET active='0' WHERE bid='$bid'");
	    $sql3 = "SELECT name, contact, email FROM ".$prefix."_bannerclient WHERE cid='$cid'";
	    $result3 = mysql_query($sql3);
	    $row3 = mysql_fetch_array($result3);
	    $c_name = $row3['name'];
	    $c_contact = $row3['contact'];
	    $c_email = $row3['email'];
	    if ($c_email != "") {
		$from = "$sitename <$adminmail>";
		$to = "$c_contact <$c_email>";
		$message = "Hello $c_contact:\n\n";
		$message .= "This is an automated email to let you know that your banner advertising in our site has been completed.\n\n";
		$message .= "The results of your campaign are as follows:\n\n";
		$message .= "Total Impression Made: $imptotal\n";
		$message .= "Clicks Received: $clicks\n";
		$message .= "Image URL $imageurl\n";
		$message .= "Click URL: $clickurl\n";
		$message .= "Alternate Text: $alttext\n\n";
		$message .= "Hope you liked our service. We'll look forward to having you as an advertising customer again soon.\n\n";
		$message .= "Thanks for your Support\n\n";
		$message .= "- $sitename Team\n";
		$message .= "$full_url";
		$subject = "$sitename: Banners Ads Finished";
		mail($to, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
	    }
	}
    echo"<center><a href=\"portal_banners.php?op=click&amp;bid=$bid\" target=\"_blank\"><img src=\"$imageurl\" border=\"0\" alt='$alttext' title='$alttext'></a></center>";
    }
}

function clickbanner($bid) {
    global $prefix;
    $sql = "SELECT clickurl FROM ".$prefix."_banner WHERE bid='$bid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    mysql_query("UPDATE ".$prefix."_banner SET clicks=clicks+1 WHERE bid='$bid'");
    Header("Location: $row[clickurl]");
}

function clientlogin() {
    echo"
    <html>
    <body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\">
    <center><br><br><br><br>
    <table width=\"200\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\" bgcolor=\"#000000\"><tr><td>
    <table width=\"100%\" cellpadding=\"5\" cellspacing=\"1\" border=\"0\" bgcolor=\"#FFFFFF\"><tr><td bgcolor=\"#EECFA1\">
    <center><b>Advertising Statistics</b></center>
    </td></tr><tr><td bgcolor=\"#FFFACD\">
    <form action=\"portal_banners.php\" method=\"post\">
    Login: <input type=\"text\" name=\"login\" size=\"12\" maxlength=\"10\"><br>
    Password: <input type=\"password\" name=\"pass\" size=\"12\" maxlength=\"10\"><br>
    <input type=\"hidden\" name=\"op\" value=\"Ok\">
    <input type=\"submit\" value=\"Login\">
    </td></tr><tr><td bgcolor=\"#EECFA1\">
    <font class=\"content\">
    <center>Please type your client information</center>
    </font></form>
    </td></tr></table></td></tr></table>
    </body>
    </html>
    ";
}

function bannerstats($login, $pass) {
    global $prefix, $sitename;
    $sql = "SELECT cid, name, passwd FROM ".$prefix."_bannerclient WHERE login='$login'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $cid = $row['cid'];
    $name = $row['name'];
    $passwd = $row['passwd'];
    if($login=="" AND $pass=="" OR $pass=="") {
	echo "<center><br>Login Incorrect!!!<br><br><a href=\"javascript:history.go(-1)\">Back to Login Screen</a></center>";
    } else {
    
    if ($pass==$passwd) {
    
    echo"
    <html>
    <body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\">
    <center>
    <table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td>
    <table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"#FFFACD\"><tr><td>
    <font class=\"option\">
    <center><b>Current Active Banners for $name</b></center><br>
    </font>
    <table width=\"100%\" border=\"0\"><tr>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>ID</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Imp. Made</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Imp. Total</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Imp. Left</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Clicks</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>% Clicks</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Functions</b></td><tr>";
    $sql = "SELECT bid, imptotal, impmade, clicks, date FROM ".$prefix."_banner WHERE cid='$cid' AND active='1'";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
	$bid = $row['bid'];
	$imptotal = $row['imptotal'];
	$impmade = $row['impmade'];
	$clicks = $row['clicks'];
	$date = $row['date'];
        if($impmade==0) {
    	    $percent = 0;
        } else {
    	    $percent = substr(100 * $clicks / $impmade, 0, 5);
        }
        if($imptotal==0) {
    	    $left = "Unlimited";
        } else {
    	    $left = $imptotal-$impmade;
        }
        echo "
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$bid</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$impmade</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$imptotal</td>
	    <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$left</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$clicks</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$percent%</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\"><a href=\"portal_banners.php?op=EmailStats&login=$login&cid=$cid&bid=$bid\">E-mail Stats</a></td><tr>";
    }
    echo "
    </table>
    <center><br><br>
    Following are your running Banners in $sitename<br><br>";

    $sql = "SELECT bid, imageurl, clickurl, alttext FROM ".$prefix."_banner WHERE cid='$cid' AND active='1'";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
	$bid = $row['bid'];
	$imageurl = $row['imageurl'];
	$clickurl = $row['clickurl'];
	$alttext = $row['alttext'];

	$numrows = mysql_num_rows($result);
	if ($numrows>1) {
	    echo "<hr noshade width=\"80%\"><br>";
	}

	echo "<img src=\"$imageurl\" border=\"1\"><br>
	<font class=\"content\">Banner ID: $bid<br>
	Send <a href=\"portal_banners.php?op=EmailStats&login=$login&cid=$cid&bid=$bid\">E-Mail Stats</a> for this Banner<br>
	This Banners points to <a href=\"$clickurl\">this URL</a><br>
	<form action=\"portal_banners.php\" method=\"submit\">
	Change URL: <input type=\"text\" name=\"url\" size=\"50\" maxlength=\"200\" value=\"$clickurl\"><br>
	Change Text: <input type=\"text\" name=\"alttext\" size=\"50\" maxlength=\"255\" value=\"$alttext\"><br>
	<input type=\"hidden\" name=\"login\" value=\"$login\">
	<input type=\"hidden\" name=\"bid\" value=\"$bid\">
	<input type=\"hidden\" name=\"pass\" value=\"$pass\">
	<input type=\"hidden\" name=\"cid\" value=\"$cid\">
	<input type=\"submit\" name=\"op\" value=\"Change\"></form></font>";
    }
    echo "
    </td></tr></table></td></tr></table>
    ";
    
    echo "
    <center><br>
    <table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"000000\"><tr><td>
    <table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"#FFFACD\"><tr><td>
    <font class=\"option\">
    <center><b>Banners Finished for $name</b></center><br>
    </font>
    <table width=\"100%\" border=\"0\"><tr>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>ID</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Impressions</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Clicks</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>% Clicks</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Start Date</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>End Date</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Functions</b></td></tr>";
    $sql = "SELECT bid, impmade, clicks, imageurl, date, dateend FROM ".$prefix."_banner WHERE cid='$cid' AND active='0'";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
	$bid = $row['bid'];
	$impmade = $row['impmade'];
	$clicks = $row['clicks'];
	$imageurl = $row['imageurl'];
	$date = $row['date'];
	$dateend = $row['dateend'];
        $percent = substr(100 * $clicks / $impmade, 0, 5);
	echo "
        <tr><td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$bid</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$impmade</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$clicks</td>
	    <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$percent%</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$date</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$dateend</td>
	<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\"><a href=\"$imageurl\" target=\"new\">View Banner</a></td></tr>";
    }
    } else {
	echo "<center><br>Login Incorrect!!!<br><br><a href=\"javascript:history.go(-1)\">Back to Login Screen</a></center>";
    }
}
}

function EmailStats($login, $cid, $bid, $pass) {
    global $prefix, $db, $bbname, $adminemail;
    $sql = "SELECT name, email FROM ".$prefix."_bannerclient WHERE cid='$cid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $name = $row['name'];
    $email = $row['email'];
    if ($email=="") {
	echo "
	<html>
	<body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\">
	<center><br><br><br>
	<b>Statistics for Banner No. $bid can't be send because<br>
	there isn't an email associated with client $name<br>
	Please contact the Administrator<br><br></b>
	<a href=\"javascript:history.go(-1)\">Back to Banners Stats</a>
	";
    } else {
	$sql2 = "SELECT bid, imptotal, impmade, clicks, imageurl, clickurl, date FROM ".$prefix."_banner WHERE bid='$bid' AND cid='$cid'";
	$result2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($result2);
	$bid = $row2['bid'];
	$imptotal = $row2['imptotal'];
	$impmade = $row2['impmade'];
	$clicks = $row2['clicks'];
	$imageurl = $row2['imageurl'];
	$clickurl = $row2['clickurl'];
	$date = $row2[date];
        if($impmade==0) {
    	    $percent = 0;
        } else {
    	    $percent = substr(100 * $clicks / $impmade, 0, 5);
        }
        if($imptotal==0) {
    	    $left = "Unlimited";
	    $imptotal = "Unlimited";
        } else {
    	    $left = $imptotal-$impmade;
        }
	$fecha = date("F jS Y, h:iA.");
	$subject = "Your Banner Statistics at $sitename";
	$message = "Following are the complete stats for your advertising investment at $sitename:\n\n\nClient Name: $name\nBanner ID: $bid\nBanner Image: $imageurl\nBanner URL: $clickurl\n\nImpressions Purchased: $imptotal\nImpressions Made: $impmade\nImpressions Left: $left\nClicks Received: $clicks\nClicks Percent: $percent%\n\n\nReport Generated on: $fecha";
	$from = "$sitename";
	mail("$email", "$subject", "$message", "From: $bbname <$adminemail>\nX-Sender: <$adminemail]>\nX-Mailer: PHP\nX-Priority: 2\nReturn-Path: <$adminemail>\nContent-Type: text/plain; charset=ASCII\n");
	echo "
	<html>
	<body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\">
	<center><br><br><br>
	<b>Statistics for Banner No. $bid has been send to<br>
	<i>$email</i> of $name<br><br></b>
	<a href=\"javascript:history.go(-1)\">Back to Banners Stats</a>
	";
    }
}

function change_banner_url_by_client($login, $pass, $cid, $bid, $url, $alttext) {
    global $prefix;
    $sql = "SELECT passwd FROM ".$prefix."_bannerclient WHERE cid='$cid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $passwd = $row['passwd'];
    if (!empty($pass) AND $pass==$passwd) {
	$alttext = ereg_replace("\"", "", $alttext);
	$alttext = ereg_replace("'", "", $alttext);
	mysql_query("UPDATE ".$prefix."_banner SET clickurl='$url', alttext='$alttext' WHERE bid='$bid'");
	echo "<br><center>";
	if ($url != "") {
	    echo "You changed the URL<br>";
	}
	if ($alttext != "") {
	    echo "You changed the Alternate Text";
	}
	echo "<br><br><a href=\"javascript:history.go(-1)\">Back to Stats Page</a></center>";
    } else {
	echo "<center><br>Your login/password doesn't match.<br><br>Please <a href=\"portal_banners.php?op=login\">login again</a></center>";
    }
    
}

switch($op) {

    case "click":
	clickbanner($bid);
	break;

    case "login":
	clientlogin();
	break;

    case "Ok":
	bannerstats($login, $pass);
	break;

    case "Change":
	change_banner_url_by_client($login, $pass, $cid, $bid, $url, $alttext);
	break;

    case "EmailStats":
	EmailStats($login, $cid, $bid, $pass);
	break;
	
    default:
	viewbanner();
	break;
}

?>
