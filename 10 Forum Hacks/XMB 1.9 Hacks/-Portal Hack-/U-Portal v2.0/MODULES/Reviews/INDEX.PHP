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

function alpha() {
    global $module_name;
    $alphabet = array ("A","B","C","D","E","F","G","H","I","J","K","L","M",
                       "N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");
    $num = count($alphabet) - 1;
    echo "<center>[ ";
    $counter = 0;
    while (list(, $ltr) = each($alphabet)) {
    echo "<a href=\"modules.php?modulename=$module_name&rop=$ltr\">$ltr</a>";
    if ( $counter == round($num/2) ) {
    echo " ]\n<br>\n[ ";
    } elseif ( $counter != $num ) {
    echo "&nbsp;|&nbsp;\n";
    }
    $counter++;
    }
    echo " ]</center><br><br>\n\n\n";
    echo "<center>[ <a href=\"modules.php?modulename=$module_name&rop=write_review\">Write a Review</a> ]</center><br><br>\n\n";
}

function display_score($score) {
    global $portal_images;
    $image = "<img src=\"$portal_images/blue.gif\" alt=\"\">";
    $halfimage = "<img src=\"$portal_images/bluehalf.gif\" alt=\"\">";
    $full = "<img src=\"$portal_images/star.gif\" alt=\"\">";

    if ($score == 10) {
	for ($i=0; $i < 5; $i++)
	    echo "$full";
    } else if ($score % 2) {
	$score -= 1;
	$score /= 2;
	for ($i=0; $i < $score; $i++)
	    echo "$image";
	    echo "$halfimage";
    } else {
	$score /= 2;
	for ($i=0; $i < $score; $i++)
	    echo "$image";
    }
}

function write_review() {
    global $status, $bbname, $xmbuser, $cookie, $prefix, $table_members, $db, $module_name, $bordercolor, $tablespace, $borderwidth, $altbg1, $altbg2;
    include ('portal_header.php');
    OpenTable();
    echo "<center><b>Write a Review for $bbname</b><br><br>
    <i>Please enter information according to the specifications</i></center><br><br>
    <form method=\"post\" action=\"modules.php?modulename=$module_name\">
    <table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">
    <tr><td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"20%\"><b>Product Title:</b></td>
    <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"title\" size=\"50\" maxlength=\"150\">
    <br><i>Name of the Reviewed Product.</i></td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Review:</b></td>
    <td bgcolor=\"$altbg2\" class=\"tablerow\"><textarea name=\"message\" rows=\"15\" wrap=\"virtual\" cols=\"60\"></textarea><br>";
    if ($status == "Super Administrator" || $status == "Administrator") {
	echo "If you want multiple pages you can write <b>&lt;!--pagebreak--&gt;</b> where you want to cut.</font><br>";
    }
    echo "<i>Your actual review. Please observe proper grammar! Make it at least 100 words, OK? You may also use HTML tags if you know how to use them.</i>";
    echo "</td></tr>";
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Your Name:</b></td>";
    if ($xmbuser) {
        $result= $db->query("select username, email from $table_members where username='$xmbuser'");
        list($username, $email) = $db->fetch_row($result);
    }
    echo "<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"reviewer\" size=\"41\" maxlength=\"40\" value=\"$username\">
    <br><i>Your Name. Required.</i></td></tr>
    <tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Your Email:</b></td>
    <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"email\" size=\"40\" maxlength=\"80\" value=\"$email\">
    <br><i>Your E-mail address. Required.</i></td></tr>
    <tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Score:</b></td>
    <td bgcolor=\"$altbg2\" class=\"tablerow\"><select name=\"score\">
    <option name=\"score\" value=\"10\">10</option>
    <option name=\"score\" value=\"9\">9</option>
    <option name=\"score\" value=\"8\">8</option>
    <option name=\"score\" value=\"7\">7</option>
    <option name=\"score\" value=\"6\">6</option>
    <option name=\"score\" value=\"5\">5</option>
    <option name=\"score\" value=\"4\">4</option>
    <option name=\"score\" value=\"3\">3</option>
    <option name=\"score\" value=\"2\">2</option>
    <option name=\"score\" value=\"1\">1</option>
    </select>
    <br><i>This Product Score</i></td></tr>
    <tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Related Link:</b></td>
    <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"url\" size=\"40\" maxlength=\"100\" value=\"http://\"><br>
    <i>Product Official Website. Make sure your URL starts with \"http://\"</i></td></tr>
    <tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Link Title:</b></td>
    <td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"url_title\" size=\"40\" maxlength=\"50\"><br>
    <i>Required if you have a related link, otherwise not required.</i></td></tr>
    ";
    if($status == "Super Administrator" || $status == "Administrator") {
	echo "
	<tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Image Filename:</b></td>
	<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"text\" name=\"cover\" size=\"40\" maxlength=\"100\"><br>
	<i>Name of the cover image, located in images/portal_images/reviews/. Not required.</i></td></tr>";
    }
    echo "<tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><i>Please make sure that the information entered is 100% valid and uses proper grammar and capitalization. For instance, please do not enter your text in ALL CAPS, as it will be rejected.</i><br></td></tr>";
    echo "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"rop\" value=\"preview_review\">
    <input type=\"submit\" value=\"Preview\"> <input type=\"button\" onClick=\"history.go(-1)\" value=\"Cancel\"></td></tr></table></form>";
    CloseTable();
    include ("portal_footer.php");
}

function preview_review($date, $title, $message, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id) {
    global $status, $module_name, $tablespace, $borderwidth, $bordercolor, $altbg1, $portal_images;
    if (eregi("<!--pagebreak-->", $message)) {
	$message = ereg_replace("<!--pagebreak-->","&lt;!--pagebreak--&gt;",$message);
    }
    $title = stripslashes(check_html($title, "nohtml"));
    $message = stripslashes(check_html($message, ""));
    $reviewer = stripslashes(check_html($reviewer, "nohtml"));
    $url_title = stripslashes(check_html($url_title, "nohtml"));
    include ('portal_header.php');
    OpenTable();
    echo "<form method=\"post\" action=\"modules.php?modulename=$module_name\">";

    if ($title == "") {
    	$error = 1;
	echo "Invalid Title... Can't be blank<br>";
    }
    if ($message == "") {
    	$error = 1;
	echo "Invalid Review Text... Can't be blank<br>";
    }
    if (($score < 1) || ($score > 10)) {
	$error = 1;
	echo "Invalid Score... Must be between 1 and 10<br>";
    }
    if (($hits < 0) && ($id != 0)) {
	$error = 1;
	echo "Hits must be a positive integer<br>";
    }
    if ($reviewer == "" || $email == "") {
	$error = 1;
	echo "You must enter both your name and your email<br>";
    } else if ($reviewer != "" && $email != "")
	if (!(eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$",$email))) {
	    $error = 1;
	    echo "Invalid email (eg: you@hotmail.com)<br>";
	}
	if (($url_title != "" && $url =="") || ($url_title == "" && $url != "")) {
	    $error = 1;
	    echo "You must enter BOTH a link title and a related link or leave both blank<br>";
	} else if (($url != "") && (!(eregi('(^http[s]*:[/]+)(.*)', $url))))
    $url = "http://" . $url;
	if ($error == 1)
	    echo "<br>[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
	else
	{
	if ($date == "")
	    $date = date("Y-m-d", time());
	    $year2 = substr($date,0,4);
	    $month = substr($date,5,2);
	    $day = substr($date,8,2);
	    $fdate = date("F jS Y",mktime (0,0,0,$month,$day,$year2));
        echo "<table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\"><tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">";
	    echo "<p><font class=\"subject\"><i><b>$title</b></i></font><br>";
	    echo "<blockquote><p>";
	    if ($cover != "")
    	echo "<img src=\"$portal_images/reviews/$cover\" align=\"right\" border=\"0\" vspace=\"2\" alt=\"\">";
	    echo "$message<p>";
	    echo "<b>Added:</b> $fdate<br>";
	    echo "<b>Reviewer:</b> <a href=\"mailto:$email\">$reviewer</a><br>";
	    echo "<b>Score:</b> ";
	    display_score($score);
	    if ($url != "")
		echo "<br><b>Related Link:</b> <a href=\"$url\" target=\"new\">$url_title</a>";
	    if ($id != 0) {
		echo "<br><b>Review ID:</b> $id<br>";
		echo "<b>Hits:</b> $hits<br>";
	    }
	    echo "</font></blockquote>";
	    echo "</td></tr></table>";
	    $message = urlencode($message);
	    echo "<p><i>Does this looks right?</i> ";
	    echo "<input type=\"hidden\" name=\"id\" value=$id>
		  <input type=\"hidden\" name=\"hits\" value=\"$hits\">
		  <input type=\"hidden\" name=\"rop\" value=send_review>
		  <input type=\"hidden\" name=\"date\" value=\"$date\">
		  <input type=\"hidden\" name=\"title\" value=\"$title\">
		  <input type=\"hidden\" name=\"message\" value=\"$message\">
		  <input type=\"hidden\" name=\"reviewer\" value=\"$reviewer\">
		  <input type=\"hidden\" name=\"email\" value=\"$email\">
		  <input type=\"hidden\" name=\"score\" value=\"$score\">
		  <input type=\"hidden\" name=\"url\" value=\"$url\">
		  <input type=\"hidden\" name=\"url_title\" value=\"$url_title\">
		  <input type=\"hidden\" name=\"cover\" value=\"$cover\">";
		echo "<input type=\"submit\" name=\"rop\" value=\"Yes\"> <input type=\"button\" onClick=\"history.go(-1)\" value=\"No\">";
	    if($id != 0)
    	$word = "modified";
	    else
    	$word = "added";
	    if($status == "Super Administrator" || $status == "Administrator")
    	echo "<br><br><b>Note:</b> Currently logged in as admin... this review will be immediately $word.";
	}
    CloseTable();
    include ("portal_footer.php");
}

function send_review($date, $title, $message, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id) {
    global $status, $EditedMessage, $prefix, $db, $module_name;
    include ('portal_header.php');
    if (eregi("<!--pagebreak-->", $message)) {
	$message = ereg_replace("<!--pagebreak-->","&lt;!--pagebreak--&gt;;",$message);
    }
    $title = stripslashes(FixQuotes(check_html($title, "nohtml")));
    $message = stripslashes(Fixquotes(urldecode(check_html($message, ""))));
    if (eregi("&lt;!--pagebreak--&gt;", $message)) {
	$message = ereg_replace("&lt;!--pagebreak--&gt;","<!--pagebreak-->",$message);
    }
    OpenTable();
    echo "<br><center>Thanks for submitting this review";
    if ($id != 0)
	echo " modification";
    else
	echo ", $reviewer";
    echo "!<br>";
    if (($status == "Super Administrator" || $status == "Administrator") && ($id == 0)) {
	$db->query("INSERT INTO ".$prefix."_reviews VALUES (NULL, '$date', '$title', '$message', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
	echo "It is now available in the reviews database.";
    }elseif(($status == "Super Administrator" || $status == "Administrator") && ($id != 0)) {
	$db->query("UPDATE ".$prefix."_reviews SET date='$date', title='$title', text='$message', reviewer='$reviewer', email='$email', score='$score', cover='$cover', url='$url', url_title='$url_title', hits='$hits' where id = $id");
	echo "It is now available in the reviews database.";
    }else{
	$db->query("INSERT INTO ".$prefix."_reviews_add VALUES (NULL, '$date', '$title', '$message', '$reviewer', '$email', '$score', '$url', '$url_title')");
	echo "The editors will look at your submission. It should be available soon!";
    }
    echo "<br><br>[ <a href=\"modules.php?modulename=$module_name\">Back to Reviews Index</a> ]<br></center>";
    CloseTable();
    include ("portal_footer.php");
}

function reviews_index() {
    global $altbg1, $altbg2, $prefix, $db, $module_name, $borderwidth, $tablespace, $bordercolor;
    include ('portal_header.php');
    OpenTable();
    echo "<table border=\"0\" width=\"95%\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" align=\"center\" bgcolor=\"$bordercolor\">
    <tr><td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\"><center><font class=\"subject\">Welcome to Reviews Section</font></center><br><br><br>";
    $result = $db->query("select title, description from ".$prefix."_reviews_main");
    list($title, $description) = $db->fetch_row($result);
    echo "<center><b>$title</b><br><br>$description</center>";
    echo "<br><br><br>";
    alpha();
    echo "</td></tr>";
    echo "<tr><td width=\"50%\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>10 most popular reviews</b></td>";
    echo "<td width=\"50%\" bgcolor=\"$altbg2\" class=\"tablerow\"><b>10 most recent reviews</b></td></tr>";
    $result_pop = $db->query("select id, title, hits from ".$prefix."_reviews order by hits DESC limit 10");
    $result_rec = $db->query("select id, title, date, hits from ".$prefix."_reviews order by date DESC limit 10");
    $y = 1;
    for ($x = 0; $x < 10; $x++)	{
	$myrow = $db->fetch_array($result_pop);
	$id = $myrow["id"];
	$title = $myrow["title"];
	$hits = $myrow["hits"];
	echo "<tr><td width=\"50%\" bgcolor=\"$altbg2\" class=\"tablerow\">$y) <a href=\"modules.php?modulename=$module_name&rop=showcontent&amp;id=$id\">$title</a></td>";
	$myrow = $db->fetch_array($result_rec);
	$id = $myrow["id"];
	$title = $myrow["title"];
	$hits = $myrow["hits"];
	echo "<td width=\"50%\" bgcolor=\"$altbg2\" class=\"tablerow\">$y) <a href=\"modules.php?modulename=$module_name&rop=showcontent&amp;id=$id\">$title</a></td></tr>";
	$y++;
    }
    echo "<tr><td colspan=\"2\" bgcolor=\"$altbg2\" class=\"tablerow\"><br></td></tr>";
    $result = $db->query("SELECT * FROM ".$prefix."_reviews");
    $numresults = $db->num_rows($result);
    echo "<tr><td colspan=\"2\" bgcolor=\"$altbg2\" class=\"tablerow\"><br><center>There are $numresults Reviews in the Database</center></td></tr></table>";
    CloseTable();
    include ("portal_footer.php");
}

function reviews($letter, $field, $order) {
    global $altbg1, $altbg2, $sitename, $prefix, $db, $module_name, $borderwidth, $tablespace, $bordercolor, $portal_images;
    include ('portal_header.php');
    OpenTable();
    echo "<center><b>$sitename Reviews</b><br>";
    echo "<i>Reviews for letter \"$letter\"</i><br><br>";
    switch ($field) {
	case "reviewer":
	$result = $db->query("SELECT id, title, hits, reviewer, score FROM ".$prefix."_reviews WHERE UPPER(title) LIKE '$letter%' ORDER by reviewer $order");
	break;

	case "score":
	$result = $db->query("SELECT id, title, hits, reviewer, score FROM ".$prefix."_reviews WHERE UPPER(title) LIKE '$letter%' ORDER by score $order");
	break;

	case "hits":
	$result = $db->query("SELECT id, title, hits, reviewer, score FROM ".$prefix."_reviews WHERE UPPER(title) LIKE '$letter%' ORDER by hits $order");
	break;

	default:
	$result = $db->query("SELECT id, title, hits, reviewer, score FROM ".$prefix."_reviews WHERE UPPER(title) LIKE '$letter%' ORDER by title $order");
	break;

    }
    $numresults = $db->num_rows($result);
    if ($numresults == 0) {
	echo "<i><b>There isn't any Review for letter \"$letter\"</b></i><br><br>";
    } elseif ($numresults > 0) {
	echo "<TABLE BORDER=\"0\" width=\"100%\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\">
		<tr>
		<td width=\"50%\" bgcolor=\"$altbg1\" class=\"tablerow\">
		<P ALIGN=\"LEFT\"><a href=\"modules.php?modulename=$module_name&rop=$letter&amp;field=title&amp;order=ASC\"><img src=\"$portal_images/up.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\"Sort Ascending\"></a><B> Product Title </B><a href=\"modules.php?modulename=$module_name&rop=$letter&amp;field=title&amp;order=DESC\"><img src=\"$portal_images/down.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\"Sort Descending\"></a>
		</td>
		<td width=\"18%\" bgcolor=\"$altbg1\" class=\"tablerow\">
		<P ALIGN=\"CENTER\"><a href=\"modules.php?modulename=$module_name&rop=$letter&amp;field=reviewer&amp;order=ASC\"><img src=\"$portal_images/up.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\"Sort Ascending\"></a><B> Reviewer: </B><a href=\"modules.php?modulename=$module_name&rop=$letter&amp;field=reviewer&amp;order=desc\"><img src=\"$portal_images/down.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\"Sort Descending\"></a>
		</td>
		<td width=\"18%\" bgcolor=\"$altbg1\" class=\"tablerow\">
		<P ALIGN=\"CENTER\"><a href=\"modules.php?modulename=$module_name&rop=$letter&amp;field=score&amp;order=ASC\"><img src=\"$portal_images/up.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\"Sort Ascending\"></a><B> Score: </B><a href=\"modules.php?modulename=$module_name&rop=$letter&amp;field=score&amp;order=DESC\"><img src=\"$portal_images/down.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\"Sort Descending\"></a>
		</td>
		<td width=\"14%\" bgcolor=\"$altbg1\" class=\"tablerow\">
		<P ALIGN=\"CENTER\"><a href=\"modules.php?modulename=$module_name&rop=$letter&amp;field=hits&amp;order=ASC\"><img src=\"$portal_images/up.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\"Sort Ascending\"></a><B> Hits </B><a href=\"modules.php?modulename=$module_name&rop=$letter&amp;field=hits&amp;order=DESC\"><img src=\"$portal_images/down.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\"Sort Descending\"></a>
		</td>
		</tr>";
	    while($myrow = $db->fetch_array($result)) {
	    $title = $myrow["title"];
	    $id = $myrow["id"];
	    $reviewer = $myrow["reviewer"];
	    $email = $myrow["email"];
	    $score = $myrow["score"];
	    $hits = $myrow["hits"];
	    echo "<tr>
		    <td width=\"50%\" bgcolor=\"$altbg2\" class=\"tablerow\"><a href=\"modules.php?modulename=$module_name&rop=showcontent&amp;id=$id\">$title</a></td>
		    <td width=\"18%\" bgcolor=\"$altbg2\" class=\"tablerow\">";
	    if ($reviewer != "")
		echo "<center>$reviewer</center>";
	    echo "</td><td width=\"18%\" bgcolor=\"$altbg2\" class=\"tablerow\"><center>";
	    display_score($score);
	    echo "</center></td><td width=\"14%\" bgcolor=\"$altbg2\" class=\"tablerow\"><center>$hits</center></td>
		  </tr>";
	}
	echo "</TABLE>";
	echo "<br>$numresults Total Review(s) found.<br><br>";
    }
    echo "[ <a href=\"modules.php?modulename=$module_name\">Return to Main Menu</a> ]";
    CloseTable();
    include ("portal_footer.php");
}

function postcomment($id, $title) {
    global $xmbuser, $AllowableHTML, $anonymous, $module_name;
    include("portal_header.php");
    $title = urldecode($title);
    OpenTable();
    echo "<center><font class=option><b>Comment on the Review: $title</b><br><br></font></center>"
	."<form action=modules.php?modulename=$module_name method=post>";
    if (!$xmbuser) {
	echo "<b>Your Nickname:</b> $anonymous [ <a href=member.php?action=reg>Create</a> an account ]<br><br>";
	$uname = $anonymous;
    } else {
	echo "<b>Your Nickname:</b> $xmbuser<br>
	<input type=checkbox name=xanonpost> Post Anonymously<br><br>";
	$uname = $xmbuser;
    }
    echo "
    <input type=hidden name=uname value=$uname>
    <input type=hidden name=id value=$id>
    <b>This Product Score</b>
    <select name=score>
    <option name=score value=10>10</option>
    <option name=score value=9>9</option>
    <option name=score value=8>8</option>
    <option name=score value=7>7</option>
    <option name=score value=6>6</option>
    <option name=score value=5>5</option>
    <option name=score value=4>4</option>
    <option name=score value=3>3</option>
    <option name=score value=2>2</option>
    <option name=score value=1>1</option>
    </select><br><br>
    <b>Your Comment:</b><br>
    <textarea name=comments rows=10 cols=70></textarea><br>
    Allowed HTML:<br>";
    while (list($key,)= each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "<br><br>
    <input type=hidden name=rop value=savecomment>
    <input type=submit value=Submit>
    </form>
    ";
    CloseTable();
    include("portal_footer.php");
}

function savecomment($xanonpost, $uname, $id, $score, $comments) {
    global $anonymous, $xmbuser, $cookie, $prefix, $db, $module_name;
    if ($xanonpost) {
	$uname = $anonymous;
    }
    $comments = stripslashes(FixQuotes(check_html($comments)));
    $db->query("insert into ".$prefix."_reviews_comments values (NULL, '$id', '$uname', now(), '$comments', '$score')");
    Header("Location: modules.php?modulename=$module_name&rop=showcontent&id=$id");
}

function r_comments($id, $title) {
    global $status, $prefix, $db, $module_name;
    $result = $db->query("select cid, userid, date, comments, score from ".$prefix."_reviews_comments where rid='$id' ORDER BY date DESC");
    while(list($cid, $uname, $date, $comments, $score) = $db->fetch_row($result)) {
	OpenTable();
	$title = urldecode($title);
	echo "
	<b>$title</b><br>";
	if ($uname == "Anonymous") {
    echo "Posted by $uname on $date<br>";
	} else {
    echo "Posted by <a href=\"member.php?action=viewpro&member=$uname\">$uname</a> on $date<br>";
	}
	echo "My Score: ";
	display_score($score);
	if ($status == "Super Administrator" || $status == "Administrator") {
    echo "<br><b>Admin:</b> [ <a href=\"modules.php?modulename=$module_name&rop=del_comment&amp;cid=$cid&amp;id=$id\">Delete</a> ]</font><hr noshade size=1><br><br>";
	} else {
    echo "</font><hr noshade size=1><br><br>";
	}
	$comments = FixQuotes(nl2br(filter_text($comments)));
	echo "
	$comments
	";
	CloseTable();
	echo "<br>";
    }
}

function showcontent($id, $page) {
    global $status, $uimages, $prefix, $db, $module_name, $portal_images;
    include ('portal_header.php');
    OpenTable();
    if (($page == 1) OR ($page == "")) {
	$db->query("UPDATE ".$prefix."_reviews SET hits=hits+1 WHERE id=$id");
    }
    $result = $db->query("SELECT * FROM ".$prefix."_reviews WHERE id=$id");
    $myrow =  $db->fetch_array($result);
    $id =  $myrow["id"];
    $date = $myrow["date"];
    $year = substr($date,0,4);
    $month = substr($date,5,2);
    $day = substr($date,8,2);
    $fdate = date("F jS Y",mktime (0,0,0,$month,$day,$year));
    $title = $myrow["title"];
    $message = $myrow["text"];
    $cover = $myrow["cover"];
    $reviewer = $myrow["reviewer"];
    $email = $myrow["email"];
    $hits = $myrow["hits"];
    $url = $myrow["url"];
    $url_title = $myrow["url_title"];
    $score = $myrow["score"];
    $contentpages = explode( "<!--pagebreak-->", $message );
    $pageno = count($contentpages);
    if ( $page=="" || $page < 1 )
	$page = 1;
    if ( $page > $pageno )
	$page = $pageno;
    $arrayelement = (int)$page;
    $arrayelement --;
    echo "<p><i><b><font class=\"title\">$title</b></i></font><br>";
    echo "<BLOCKQUOTE><p align=justify>";
    if ($cover != "")
    echo "<img src=\"$portal_images/reviews/$cover\" align=right border=0 vspace=2 alt=\"\">";
    echo "$contentpages[$arrayelement]
    </BLOCKQUOTE><p>";
    if ($status == "Super Administrator" || $status == "Administrator")
	echo "<b>Admin:</b> [ <a href=\"modules.php?modulename=$module_name&rop=mod_review&amp;id=$id\">Edit</a> | <a href=modules.php?modulename=$module_name&rop=del_review&amp;id_del=$id>Delete</a> ]<br>";
    echo "<b>Added:</b> $fdate<br>";
    if ($reviewer != "")
	echo "<b>Reviewer:</b> <a href=mailto:$email>$reviewer</a><br>";
    if ($score != "")
	echo "<b>Score:</b> ";
    display_score($score);
    if ($url != "")
		echo "<br><b>Related Link:</b> <a href=\"$url\" target=new>$url_title</a>";
    echo "<br><b>Hits:</b> $hits";
    if ($pageno > 1) {
	echo "<br><b>Page:</b> $page/$pageno<br>";
    }
    echo "</font>";
    echo "</CENTER>";
    $title = urlencode($title);
    if($page >= $pageno) {
	  $next_page = "";
    } else {
	$next_pagenumber = $page + 1;
	if ($page != 1) {
	    $next_page .= "<img src=\"$portal_images/blackpixel.gif\" width=\"10\" height=\"2\" border=\"0\" alt=\"\"> &nbsp;&nbsp; ";
	}
	$next_page .= "<a href=\"modules.php?modulename=$module_name&rop=showcontent&amp;id=$id&amp;page=$next_pagenumber\">Next Page ($next_pagenumber/$pageno)</a> <a href=\"modules.php?modulename=$module_name&rop=showcontent&amp;id=$id&amp;page=$next_pagenumber\"><img src=\"$portal_images/right.gif\" border=\"0\" alt=\"Next Page\"></a>";
    }
    if($page <= 1) {
	$previous_page = "";
    } else {
	$previous_pagenumber = $page - 1;
	$previous_page = "<a href=\"modules.php?modulename=$module_name&rop=showcontent&amp;id=$id&amp;page=$previous_pagenumber\"><img src=\"$portal_images/left.gif\" border=\"0\" alt=\"Previous Page\"></a> <a href=\"modules.php?modulename=$module_name&rop=showcontent&amp;id=$id&amp;page=$previous_pagenumber\">Previous Page ($previous_pagenumber/$pageno)</a>";
    }
    echo "<center>"
	."$previous_page &nbsp;&nbsp; $next_page<br><br>"
	."[ <a href=\"modules.php?modulename=$module_name\">Back to Reviews Index</a> | "
	."<a href=\"modules.php?modulename=$module_name&rop=postcomment&amp;id=$id&amp;title=$title\">Post Comment</a> ]";
    CloseTable();
    if (($page == 1) OR ($page == "")) {
	echo "<br>";
	r_comments($id, $title);
    }
    include ("portal_footer.php");
}

function mod_review($id) {
	global $status, $prefix, $db, $module_name, $borderwidth, $tablespace, $bordercolor, $altbg1, $altbg2;
	include ('portal_header.php');
	OpenTable();
	if (($id == 0) AND ($status != "Super Administrator" || $status != "Administrator"))
    echo "This function must be passed argument id, or you are not admin.";
	else if (($id != 0) && ($status == "Super Administrator" || $status == "Administrator")){
		$result = $db->query("select * from ".$prefix."_reviews where id = $id");
		while($myrow = $db->fetch_array($result)){
			$id =  $myrow["id"];
			$date = $myrow["date"];
			$title = $myrow["title"];
			$message = $myrow["text"];
			$cover = $myrow["cover"];
			$reviewer = $myrow["reviewer"];
			$email = $myrow["email"];
			$hits = $myrow["hits"];
			$url = $myrow["url"];
			$url_title = $myrow["url_title"];
			$score = $myrow["score"];
		}
		echo "<center><b>Review Modification</b></center><br><br>";
		echo "<form method=POST action=modules.php?modulename=$module_name&rop=preview_review><input type=hidden name=id value=$id>";
		echo "<TABLE border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\" bgcolor=\"$bordercolor\">
		<tr>
		<td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Date:</b></td>
		<td bgcolor=\"$altbg2\" class=\"tablerow\"><INPUT TYPE=text NAME=date SIZE=15 VALUE=\"$date\" MAXLENGTH=10></td>
		</tr>
		<tr>
		<td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Title:</b></td>
		<td bgcolor=\"$altbg2\" class=\"tablerow\"><INPUT TYPE=text NAME=title SIZE=50 MAXLENGTH=150 value=\"$title\"></td>
		</tr>";
		echo "<tr>
		     <td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Text:</b></td>
		    <td bgcolor=\"$altbg2\" class=\"tablerow\"><TEXTAREA class=textbox name=message rows=20 wrap=virtual cols=60>$message</TEXTAREA></td>
			</tr>
			<tr>
			<td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Reviewer:</b></td>
			<td bgcolor=\"$altbg2\" class=\"tablerow\"><INPUT TYPE=text NAME=reviewer SIZE=41 MAXLENGTH=40 value=\"$reviewer\"></td>
			</tr>
			<tr>
			<td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Email:</b></td>
			<td bgcolor=\"$altbg2\" class=\"tablerow\"><INPUT TYPE=text NAME=email value=\"$email\" SIZE=30 MAXLENGTH=80></td>
			</tr>
			<tr>
			<td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Score:</b></td>
			<td bgcolor=\"$altbg2\" class=\"tablerow\"><INPUT TYPE=text NAME=score value=\"$score\" size=3 maxlength=2></td>
			</tr>
			<tr>
			<td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Link:</b></td>
			<td bgcolor=\"$altbg2\" class=\"tablerow\"><INPUT TYPE=text NAME=url value=\"$url\" size=30 maxlength=100></td>
			</tr>
			<tr>
			<td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Link Title:</b></td>
			<td bgcolor=\"$altbg2\" class=\"tablerow\"><INPUT TYPE=text NAME=url_title value=\"$url_title\" size=30 maxlength=50></td>
			</tr>
			<tr>
			<td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Cover Image:</b></td>
			<td bgcolor=\"$altbg2\" class=\"tablerow\"><INPUT TYPE=text NAME=cover value=\"$cover\" size=30 maxlength=100></td>
			</tr>
			<tr>
			<td bgcolor=\"$altbg1\" class=\"tablerow\" width=12%><b>Hits:</b></td>
			<td bgcolor=\"$altbg2\" class=\"tablerow\"><INPUT TYPE=text NAME=hits value=\"$hits\" size=5 maxlength=5></td>
			</tr>
		    </TABLE>";
		echo "<input type=hidden name=rop value=preview_review><input type=submit value=\"Preview Modifications\">&nbsp;&nbsp;<input type=button onClick=history.go(-1) value=Cancel></form>";
	}
	CloseTable();
	include ("portal_footer.php");
}

function del_review($id_del) {
    global $status, $prefix, $db, $module_name;
    if ($status == "Super Administrator" || $status == "Administrator") {
   	$db->query("delete from ".$prefix."_reviews where id = $id_del");
	$db->query("delete from ".$prefix."_reviews_comments where rid='$id_del'");
	Header("Location: modules.php?modulename=$module_name");
    } else {
   	echo "ACCESS DENIED";
    }
}

function del_comment($cid, $id) {
    global $status, $prefix, $db, $module_name;
    if ($status == "Super Administrator" || $status == "Administrator") {
        $db->query("delete from ".$prefix."_reviews_comments where cid='$cid'");
        Header("Location: modules.php?modulename=$module_name&rop=showcontent&id=$id");
    } else {
        echo "ACCESS DENIED";
    }
}

switch($rop) {

	case "A":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(A, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "B":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(B, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "C":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(C, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "D":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(D, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "E":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(E, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "F":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(F, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "G":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(G, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "H":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(H, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "I":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(I, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "J":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(J, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "K":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(K, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "L":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(L, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "M":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(M, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "N":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(N, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "O":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(O, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "P":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(P, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "Q":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(Q, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "R":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(R, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "S":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(S, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "T":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(T, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "U":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(U, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "V":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(V, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "W":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(W, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "X":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(X, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "Y":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(Y, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "Z":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(Z, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "1":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(1, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "2":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(2, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "3":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(3, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "4":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(4, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "5":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(5, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "6":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(6, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "7":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(7, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "8":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(8, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "9":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews(9, $field, $order);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "showcontent":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	showcontent($id, $page);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "write_review":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	write_review();
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "preview_review":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	preview_review($date, $title, $message, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    break;

	case "Yes":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	send_review($date, $title, $message, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "del_review":
	del_review($id_del);
	break;

	case "mod_review":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	mod_review($id);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "postcomment":
    eval("\$header = \"".template("header")."\";");
    echo $header;
	postcomment($id, $title);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;

	case "savecomment":
	savecomment($xanonpost, $uname, $id, $score, $comments);
	break;

	case "del_comment":
	del_comment($cid, $id);
	break;

	default:
     eval("\$header = \"".template("header")."\";");
    echo $header;
	reviews_index();
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
	break;
}

?>
