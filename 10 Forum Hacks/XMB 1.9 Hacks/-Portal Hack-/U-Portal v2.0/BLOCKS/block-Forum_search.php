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

if (eregi("block-Forum_search.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}
global $lang_textsearchfor, $lang_textsrchuname, $lang_srchbyforum, $lang_textlfrom, $lang_day1, $lang_aweek, $lang_month1, $lang_month3, $lang_month6, $lang_lastyear, $lang_textsearch;

$content = "
<form method=\"post\" action=\"misc.php?action=search\">
<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"9%\">$lang_textsearchfor</td>
<td bgcolor=\"$altbg2\"><input type=\"text\" name=\"srchtxt\" size=\"13\" maxlength=\"40\" /></td>
</tr>
<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"9%\">$lang_textsrchuname</td>
<td bgcolor=\"$altbg2\"><input type=\"text\" name=\"srchuname\" size=\"13\" maxlength=\"40\" /></td>
</tr>
<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\" width=\"9%\">$lang_textlfrom</td>
<td bgcolor=\"$altbg2\"><select name=\"srchfrom\">
<option value=\"86400\">$lang_day1</option>
<option value=\"604800\">$lang_aweek</option>
<option value=\"2592000\">$lang_month1</option>
<option value=\"7948800\">$lang_month3</option>
<option value=\"15897600\">$lang_month6</option>
<option value=\"31536000\">$lang_lastyear</option>
<option value=\"0\" selected=\"selected\">beginning</option>
</select></td>
</tr>
<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\" colspan=\"2\">
<input type=\"submit\" name=\"searchsubmit\" value=\"$lang_textsearch\" /></td></tr>
</form>";

?>
