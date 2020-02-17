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

if (eregi("block-staff.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

global $xmbuser, $emailcheck;

if (!$xmbuser OR $xmbuser == ""){
if($emailcheck != "on") {
$pwtd .="<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\">$lang_textpassword</td>
<td bgcolor=\"$altbg2\"class=\"tablerow\"><input type=\"password\" name=\"password\" size=\"15\" /></td>
</tr>
<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\">$lang_textretypepw</td>
<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"password\" name=\"password2\" size=\"15\" /></td>
</tr>";
}
$content .="<form method=\"post\" action=\"member.php?action=reg\">
<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\" >$lang_textusername</td>
<td bgcolor=\"$altbg2\" class=\"tablerow\">
<input type=\"text\" name=\"username\" size=\"15\" maxlength=\"25\" /></td>
</tr>
$pwtd
<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\">$lang_textemail</td>
<td bgcolor=\"$altbg2\" class=\"tablerow\">
<input type=\"text\" name=\"email\" size=\"15\" /></td></tr>
<tr><td td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\"><center>
<input type=\"submit\" name=\"regsubmit\" value=\"$lang_textregister\" /></td>
</tr>
</font></td></tr></form>";

}

?>
