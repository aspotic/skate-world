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

function listsections() {
    global $sitename, $prefix, $db, $module_name, $borderwidth, $tablespace, $altbg1, $portal_images;
    include ('portal_header.php');
    $result = $db->query("select secid, secname, image from ".$prefix."_sections order by secname");
    OpenTable();
    echo "
    <center>Welcome to the Special Sections at $sitename.<br><br>
    Here you can find some cool articles not presents in the Home.</center><br><br>
    <table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" align=\"center\">";
    $count = 0;
    while (list($secid, $secname, $image) = $db->fetch_row($result)) {
        if ($count==2) {
        echo "<tr>";
        $count = 0;
        }
        echo "<td class=\"tablerow\"><a href=\"modules.php?modulename=$module_name&op=listarticles&secid=$secid\"><img src=\"$portal_images/sections/$image\" border=\"0\" Alt=\"$secname\"></a>";
        $count++;
        if ($count==2) {
        echo "</tr>";
        }
        echo "</td>";
    }
    echo "</table></center>";
    CloseTable();
    include ('portal_footer.php');
}

function listarticles($secid) {
    global $prefix, $db, $module_name, $borderwidth, $tablespace, $bordercolor, $altbg2, $portal_images;
    include ('portal_header.php');
    $result = $db->query("select secname from ".$prefix."_sections where secid=$secid");
    list($secname) = $db->fetch_row($result);
    $result = $db->query("select artid, secid, title, content, counter from ".$prefix."_seccont where secid=$secid");
    OpenTable();
    $result2 = $db->query("select image from ".$prefix."_sections where secid=$secid");
    list($image) = $db->fetch_row($result2);
    echo "<center><img src=\"$portal_images/sections/$image\" border=\"0\" alt=\"\"><br><br><font class=\"subject\">
    This is Section <b>$secname</b>.<br>Following are the articles published under this section.</font></center><br><br>
    <table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" bgcolor=\"$bordercolor\" align=\"center\">";
    while (list($artid, $secid, $title, $content, $counter) = $db->fetch_row($result)) {
        echo "
        <tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"left\" nowrap><font class=\"tablerow\">
        <li><a href=\"modules.php?modulename=$module_name&op=viewarticle&artid=$artid\">$title</a> ($counter reads)
        <a href=\"modules.php?modulename=$module_name&op=printpage&artid=$artid\"><img src=\"$portal_images/printable.gif\" border=\"0\" Alt=\"Printer Friendly Page\" width=\"15\" height=\"11\"></a>
        </td></tr>
        ";
    }
    echo "</table>
    <br><br><br><center>
    [ <a href=\"modules.php?modulename=$module_name\">Return to Sections Index</a> ]</center>";
    CloseTable();
    include ('portal_footer.php');
}

function viewarticle($artid, $page) {
    global $prefix, $db, $module_name, $altbg1, $portal_images;
    include("portal_header.php");
    if (($page == 1) OR ($page == "")) {
	$db->query("update ".$prefix."_seccont set counter=counter+1 where artid='$artid'");
    }
    $result = $db->query("select artid, secid, title, content, counter from ".$prefix."_seccont where artid=$artid");
    list($artid, $secid, $title, $content, $counter) = $db->fetch_row($result);

    $result2 = $db->query("select secid, secname from ".$prefix."_sections where secid=$secid");
    list($secid, $secname) = $db->fetch_row($result2);
    $words = sizeof(explode(" ", $content));
    echo "<center>";
    OpenTable();
    $contentpages = explode( "<!--pagebreak-->", $content );
    $pageno = count($contentpages);
    if ( $page=="" || $page < 1 )
	$page = 1;
    if ( $page > $pageno )
	$page = $pageno;
    $arrayelement = (int)$page;
    $arrayelement --;
    echo "<b>$title</b><br><br>";
    if ($pageno > 1) {
	echo "Page: $page/$pageno<br>";
    }
    echo "($words total words in this text)<br>"
	."($counter reads) &nbsp;&nbsp;"
	."<a href=\"modules.php?modulename=$module_name&op=printpage&amp;artid=$artid\"><img src=\"$portal_images/printable.gif\" border=\"0\" Alt=\"Printer Friendly Page\" width=\"15\" height=\"11\"></a>"
	."</font><br><br><br><br>";
    echo "$contentpages[$arrayelement]";
    if($page >= $pageno) {
	  $next_page = "";
    } else {
	$next_pagenumber = $page + 1;
	if ($page != 1) {
	    $next_page .= "<img src=\"$portal_images/blackpixel.gif\" width=\"10\" height=\"2\" border=\"0\" alt=\"\"> &nbsp;&nbsp; ";
	}
	$next_page .= "<a href=\"modules.php?modulename=$module_name&op=viewarticle&amp;artid=$artid&amp;page=$next_pagenumber\">Next Page ($next_pagenumber/$pageno)</a> <a href=\"modules.php?modulename=$module_name&op=viewarticle&artid=$artid&page=$next_pagenumber\"><img src=\"$portal_images/right.gif\" border=\"0\" alt=\"Next Page\"></a>";
    }

    if($page <= 1) {
	$previous_page = "";
    } else {
	$previous_pagenumber = $page - 1;
	$previous_page = "<a href=\"modules.php?modulename=$module_name&op=viewarticle&amp;artid=$artid&amp;page=$previous_pagenumber\"><img src=\"$portal_images/left.gif\" border=\"0\" alt=\"Previous Page\"></a> <a href=\"modules.php?modulename=$module_name&op=viewarticle&artid=$artid&page=$previous_pagenumber\">Previous Page ($previous_pagenumber/$pageno)</a>";
    }
    echo "</td></tr>"
	."<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\">"
	."$previous_page &nbsp;&nbsp; $next_page<br><br>"
	."[ <a href=\"modules.php?modulename=$module_name&op=listarticles&amp;secid=$secid\">Back to $secname</a> | "
	."<a href=\"modules.php?modulename=$module_name\">Sections Index</a> ]";
    CloseTable();
    echo "</center>";
    include ('portal_footer.php');
}

function PrintSecPage($artid) {
    global $logo, $full_url, $sitename, $datetime, $prefix, $db, $module_name;
    $result = $db->query("select title, content from ".$prefix."_seccont where artid=$artid");
    list($title, $content) = $db->fetch_row($result, $dbi);
    echo "
    <html>
    <head><title>$sitename - $title</title>
    <style type=\"text/css\">
    a {
	color: Black;
	text-decoration: none;
    }
	a:hover {
    color: Red;
	text-decoration: underline;
    }
    .subject {
	font-size: 10px;
	font-family: Verdana;
	font-weight: bold;
     }
    .tablerow {
	font-family: Verdana;
	color: #000000;
	font-size: 10px;
    }
    </style>
    </head>
    <body bgcolor=\"#ffffff\" text=\"#000000\">
    <table border=\"0\" align=\"center\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"#ffffff\"><tr><td class=\"tablerow\">
    <center>
    $logo<br><br>
    <font class=\"tablerow\">
    <b>$title</b></font><br>
    </center><font class=\"tablerow\">
    $content<br><br>";
    echo "</td></tr></table></td></tr></table>
    <br><br><center>
    <font class=\"nav\">
    This article comes from $sitename<br>
    <a href=\"".$full_url."portal.php\">".$full_url."portal.php</a><br><br>
    The URL for this story is:<br>
    <a href=\"".$full_url."modules.php?modulename=$module_name&op=viewarticle&amp;artid=$artid\">".$full_url."modules.php?modulename=$module_name&op=viewarticle&amp;artid=$artid</a>
    </font>
    </td></tr></table>
    </body>
    </html>
    ";
}

switch($op) {

    case "viewarticle":
    eval("\$header = \"".template("header")."\";");
    echo $header;
    viewarticle($artid, $page);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    break;

    case "listarticles":
    eval("\$header = \"".template("header")."\";");
    echo $header;
    listarticles($secid);
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    break;

    case "printpage":
    PrintSecPage($artid);
    break;

    default:
    eval("\$header = \"".template("header")."\";");
    echo $header;
    listsections();
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    break;

}

?>
