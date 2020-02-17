<?php
/*
	-----------------------------------------------------
	Bank
	
	Script by FunForum

	This PHP file is for XMB 1.9 Nexus

	Keep all copyrights/links/names on the script visible
  	-----------------------------------------------------
						  version 2.4
*/

// Get required files
    require "./header.php";

// Pre-load templates
    loadtemplates('header','footer','css','footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','functions_smilieinsert','functions_smilieinsert_smilie','functions_bbcode','functions_bbcodeinsert');
    eval("\$css = \"".template('css')."\";");

// Cache Smilies
    smcwcache();

// Define stuff
//  $lang_bank_name = $lang_shop_textbank;

    if(($xmbuser && $xmbuser != '') && !empty($self['username'])) {
        define('X_USER', true);
    }else{
        define('X_USER', false);
    }
    if (!function_exists('bank_su')) { // Function by FunForum
        function bank_su($here, $gimme) {
            $go = $gimme - 1;
            $bsu = explode("|", $here);
            return $bsu[$go];
        }
    }
    function bank_u2u($touser, $fromuser, $subject, $message, $box='Inbox') { // Function by FunForum
        GLOBAL $db, $table_u2u;
        if($touser == "" || $fromuser == ""){ exit("Error code: Bank 0169"); } 
        if($box == 'Inbox'){ $type = 'incoming'; }elseif($box == 'Outbox'){ $type = 'outgoing'; }
        $db->query("INSERT INTO $table_u2u VALUES('', '$touser', '$fromuser', '$type', '$touser', '$box', '$subject', '$message', '" . time() . "', 'no', 'yes')");
    }

// Create Navigation
    if(!$folder){
        $navigation = '&raquo; '.$lang[bank_name];
    }else{
        $navigation = '&raquo; <a target="_self" href="bank.php">'.$lang[bank_name].'</a>';
    }

// Check if logged in
    if(empty($xmbuser) || empty($xmbpw)){
        error($lang['bank_o67'], true, '', '', false, true, false, true);
        exit;
    }

// set values
    $xmblistname = $xmbuser . ",";
    $statuslistname = $status . ",";

    $Boutput = ''; $menu = ''; $wi = '0';

    $FFcurrtime  = time();
    $FFcurrmonth = gmdate("n", $FFcurrtime);
    $FFcurryear  = gmdate("Y", $FFcurrtime);

    $nextmonth = 0; 
    $nextyear  = 0; 
    $lastmonth = 0; 
    $lastyear  = 0; 

    if(!$month){ $month=$FFcurrmonth; }
    if(!$year){ $year=$FFcurryear; }

    if($month == 12){
        $nextmonth = 1;
        $nextyear  = $year + 1;
    }else{
        $nextmonth = $month + 1;
        $nextyear  = $year;
    }

    if($month == 1){
        $lastmonth = 12;
        $lastyear  = $year - 1;
    }else{
        $lastmonth = $month - 1;
        $lastyear  = $year;
    }

    $monthNames = array("offset", $lang['textjan'], $lang['textfeb'], $lang['textmar'], $lang['textapr'], $lang['textmay'], $lang['textjun'], $lang['textjul'], $lang['textaug'], $lang['textsep'], $lang['textoct'], $lang['textnov'], $lang['textdec']);

    $monthbar = "<select name=\"month\" size=\"1\" onchange=\"if(this.options[this.selectedIndex].value != '') { window.location=('bank.php?folder=".$folder."&amp;s=".$s."&amp;year=".$year."&amp;month='+this.options[this.selectedIndex].value) }\">";
    for ($i = 1; $i <= 12; $i++) {
        $monthbar .= '<option value="'.$i.'"';
        if ($month == $i){ $monthbar .= ' selected="selected"'; }
        $monthbar .= '>'.$monthNames[$i].'</option>';
    }
    $monthbar .= '</select>';
    $yearbar = "<select name=\"year\" size=\"1\" onchange=\"if(this.options[this.selectedIndex].value != '') { window.location=('bank.php?folder=".$folder."&amp;s=".$s."&amp;month=".$month."&amp;year='+this.options[this.selectedIndex].value) }\">";
    for ($i = $FFcurryear - 2; $i <= $FFcurryear + 2; $i++) {
        $yearbar .= '<option value="'.$i.'"';
        if ($year == $i) { $yearbar .= ' selected="selected"'; }
        $yearbar .= '>'.$i.'</option>';
    }
    $yearbar .= '</select>';
    if($folder == "undohistory"){ $bnplc='8'; }else{ $bnplc='6'; }
    $nextprevlinks = "<tr class='tablerow' bgcolor='$altbg1'><td colspan='".$bnplc."'> <table width='100%'><tr class='mediumtxt'><td width='25%' align='left'>&laquo; <a target='_self' href='bank.php?folder=".$folder."&amp;s=".$s."&amp;year=".$lastyear."&amp;month=".$lastmonth."'>".$monthNames[$lastmonth]." ".$lastyear."</a> &laquo;</td><td width='50%' align='center'>".$monthbar."&nbsp;".$yearbar."</td><td width='25%' align='right'>&raquo; <a target='_self' href='bank.php?folder=".$folder."&amp;s=".$s."&amp;year=".$nextyear."&amp;month=".$nextmonth."'>".$monthNames[$nextmonth]." ".$nextyear."</a> &raquo;</td></tr></table></td></tr>";



// Menu
    $menu .= '<table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="150">';

    $reqquery = $db->query("SELECT * FROM $table_bank_transfer WHERE (touid='$self[uid]' AND type='Request' AND confirmline='0' AND statusline='Open' AND UndoI='0')");
    $newreqnum = $db->num_rows($reqquery);
    if($newreqnum > 0) {
        if($newreqnum == 1) {
            $menu .= '<tr><td class="mediumtxt" bgcolor="'.$altbg1.'"><a href="bank.php?folder=requested&amp;s=out" target="_self"> <img border="0" src="images/newbankrequest.gif" />'.$lang[bank_newreq1].'</a></td></tr>';
        }else{
            $menu .= '<tr><td class="mediumtxt" bgcolor="'.$altbg1.'"><a href="bank.php?folder=requested&amp;s=out" target="_self"> <img border="0" src="images/newbankrequest.gif" />'.$lang[bank_newreq2a].''.$newreqnum.''.$lang[bank_newreq2b].'</a></td></tr>';
        }
    }

    if(!$folder || $folder == "") {
    //    $bank_status = " &raquo; ".$lang[bank_status01];
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\">- ".$lang[bank_status01]."</td></tr>";
    } else {
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status01]."</td></tr>";
    }

    if($folder == "settings") {
        $bank_status = " &raquo; ".$lang[bank_status02];
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=settings'\">- ".$lang[bank_status02]."</td></tr>";
    } else {
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=settings'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status02]."</td></tr>";
    }

    if($folder == "flow") {
        $bank_status = " &raquo; ".$lang[bank_status03];
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=flow'\">- ".$lang[bank_status03]."</td></tr>";
    } else {
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=flow'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status03]."</td></tr>";
    }

    if($folder == "in") {
        $bank_status = " &raquo; ".$lang[bank_status04];
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=in'\">- ".$lang[bank_status04]."</td></tr>";
    } else {
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=in'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status04]."</td></tr>";
    }

    if($folder == "request") {
        $bank_status = " &raquo; ".$lang[bank_status05];
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=request'\">- ".$lang[bank_status05]."</td></tr>";
    } else {
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=request'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status05]."</td></tr>";
    }

    if($folder == "requested") {
        $bank_status = " &raquo; ".$lang[bank_status06];
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=requested'\">- ".$lang[bank_status06]."</td></tr>";
    } else {
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=requested'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status06]."</td></tr>";
    }

    if($folder == "out") {
        $bank_status = " &raquo; ".$lang[bank_status07];
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=out'\">- ".$lang[bank_status07]."</td></tr>";
    } else {
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=out'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status07]."</td></tr>";
    }

    if($folder == "wire") {
        $bank_status = " &raquo; ".$lang[bank_status08];
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=wire'\">- ".$lang[bank_status08]."</td></tr>";
    } else {
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=wire'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status08]."</td></tr>";
    }

    if($folder == "details" || $folder == "info") {
        $bank_status = " &raquo; ".$lang[bank_status09];
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=details'\">- ".$lang[bank_status09]."</td></tr>";
    } else {
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=details'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status09]."</td></tr>";
    }

    if(X_ADMIN){
        $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\">Admin Options</td></tr>";
        if($folder == "undo") {
            $bank_status = " &raquo; ".$lang[bank_status10];
            $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=undo'\">- ".$lang[bank_status10]."</td></tr>";
        } else {
            $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=undo'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status10]."</td></tr>";
        }
        if($folder == "undohistory") {
            $bank_status = " &raquo; ".$lang[bank_status11];
            $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg1\" onClick=\"location.href='bank.php?folder=undohistory'\">- ".$lang[bank_status11]."</td></tr>";
        } else {
            $menu .= "<tr><td class=\"mediumtxt\" align=\"left\" valign=\"middle\" bgcolor=\"$altbg2\" onClick=\"location.href='bank.php?folder=undohistory'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='$altbg1'; \"onmouseout=\"this.style.backgroundColor='$altbg2';\">- ".$lang[bank_status11]."</td></tr>";
        }
    }
    $menu .= "</table>";



// create the tables
    $Boutput .= '<table cellspacing="0" cellpadding="0" border="0" width="'.$tablewidth.'" align="center">
    <tr><td bgcolor="'.$bordercolor.'">
    <table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="100%">
    <tr>
    <td colspan="6" class="header" align="left"> '.$lang[bank_name].''.$bank_status.' </td>
    </tr>
    </table>
    </td> </tr> </table>';

    $Boutput .= '<table cellspacing="0" cellpadding="0" border="0" width="'.$tablewidth.'" align="center" style="border-left: 1px solid '.$bordercolor.'; border-bottom: 1px solid '.$bordercolor.'; border-right: 1px solid '.$bordercolor.';">
    <tr><td width="100%" valign="top" bgcolor="'.$altbg2.'" height="100%">
    <table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="100%">';



// default action
    if(!$folder || $folder == "") {
        $Boutput .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\">".$lang[bank_welcome]."</td></tr>";
    }



// my cash
    elseif($folder == "flow") {

        $query = $db->query("SELECT amount FROM $table_bank_transfer WHERE ((fromuid='$self[uid]' AND type!='Request' AND UndoI='0') OR (touid='$self[uid]' AND type='Request' AND confirmline!='0' AND statusline='Completed'))");
        while($detailso = $db->fetch_array($query)){ $totmoneyo[] = $detailso[amount]; }
        if($totmoneyo!=""){ $ototal = array_sum($totmoneyo); }else{ $ototal="0"; }

        $query = $db->query("SELECT amount FROM $table_bank_transfer WHERE ((touid='$self[uid]' AND type!='Request' AND UndoI='0') OR (fromuid='$self[uid]' AND type='Request' AND confirmline!='0' AND statusline='Completed'))");
        while($detailsi = $db->fetch_array($query)){ $totmoneyi[] = $detailsi[amount]; }
        if($totmoneyi!=""){ $itotal = array_sum($totmoneyi); }else{ $itotal="0"; }

        $Boutput .= '<tr class="mediumtxt"><td><br /></td></tr>';
        $Boutput .= '<tr class="mediumtxt"><td bgcolor="'.$altbg1.'">'.$lang[bank_o01].'</td></tr>';
        $Boutput .= '<tr class="mediumtxt"><td>'.$self[money].'<br /></td></tr>';
        $Boutput .= '<tr class="mediumtxt"><td bgcolor="'.$altbg1.'">'.$lang[bank_o02].'</td></tr>';
        $Boutput .= '<tr class="mediumtxt"><td>'.$itotal.'<br /></td></tr>';
        $Boutput .= '<tr class="mediumtxt"><td bgcolor="'.$altbg1.'">'.$lang[bank_o03].'</td></tr>';
        $Boutput .= '<tr class="mediumtxt"><td>'.$ototal.'</td></tr>';
    }



// options
    elseif($folder == "settings") {
        if(!$optionssubmit) {
            if(bank_su($self[banksettings], 1) == "1"){ $bo1cy = 'checked'; $bo1cn = ''; }else{ $bo1cy = ''; $bo1cn = 'checked'; }
            if(bank_su($self[banksettings], 2) == "1"){ $bo2cy = 'checked'; $bo2cn = ''; }else{ $bo2cy = ''; $bo2cn = 'checked'; }
            if(bank_su($self[banksettings], 3) == "1"){ $bo3cy = 'checked'; $bo3cn = ''; }else{ $bo3cy = ''; $bo3cn = 'checked'; }
            if(bank_su($self[banksettings], 4) == "1"){ $bo4cy = 'checked'; $bo4cn = ''; }else{ $bo4cy = ''; $bo4cn = 'checked'; }

            $Boutput .= '<form method="post" action="bank.php?folder=settings">';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o04].'</td>	<td bgcolor="'.$altbg1.'" class="tablerow"><input type="radio" value="1" '.$bo1cy.' name="bo1" /> '.$lang[textyes].' '.$lang_4spaces.' '.$lang_4spaces.' <input type="radio" value="0" '.$bo1cn.' name="bo1" /> '.$lang[textno].'</td></tr>';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o05].'</td>	<td bgcolor="'.$altbg1.'" class="tablerow"><input type="radio" value="1" '.$bo2cy.' name="bo2" /> '.$lang[textyes].' '.$lang_4spaces.' '.$lang_4spaces.' <input type="radio" value="0" '.$bo2cn.' name="bo2" /> '.$lang[textno].'</td></tr>';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o06].'</td>	<td bgcolor="'.$altbg1.'" class="tablerow"><input type="radio" value="1" '.$bo3cy.' name="bo3" /> '.$lang[textyes].' '.$lang_4spaces.' '.$lang_4spaces.' <input type="radio" value="0" '.$bo3cn.' name="bo3" /> '.$lang[textno].'</td></tr>';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o07].'</td>	<td bgcolor="'.$altbg1.'" class="tablerow"><input type="radio" value="1" '.$bo4cy.' name="bo4" /> '.$lang[textyes].' '.$lang_4spaces.' '.$lang_4spaces.' <input type="radio" value="0" '.$bo4cn.' name="bo4" /> '.$lang[textno].'</td></tr>';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o08].'</td>	<td bgcolor="'.$altbg1.'" class="tablerow"><input type="submit" name="optionssubmit" value="'.$lang[bank_o09].'" /></td></tr>';
            $Boutput .= '</form>';
        }else{
            $db->query("UPDATE $table_members SET banksettings='$bo1|$bo2|$bo3|$bo4' WHERE uid='$self[uid]'");
            $Boutput .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" valign=\"middle\">".$lang[bank_o10]."</td></tr>";
            redirect("bank.php?folder=settings", 2, X_REDIRECT_JS);
        }
    }



// incoming money
    elseif($folder == "in") {
        $Boutput .= $nextprevlinks;
        $Boutput .= '<tr class="tablerow">
            <td align="center" valign="middle">'.$lang[bank_o11].'</td>
            <td align="center" valign="middle">'.$lang[bank_o12].'</td>
            <td align="center" valign="middle">'.$lang[bank_o13].'</td>
            <td align="center" valign="middle">'.$lang[bank_o14].'</td>
            <td align="center" valign="middle">'.$lang[bank_o15].'</td>
            <td align="center" valign="middle">'.$lang[bank_o16].'</td>
        </tr>';

        $query = $db->query("SELECT * FROM $table_bank_transfer WHERE ((touid='$self[uid]' AND type!='Request' AND UndoI='0') OR (fromuid='$self[uid]' AND type='Request' AND confirmline!='0' AND statusline='Completed')) AND monthnum='$month' AND yearnum='$year' ORDER BY dateline DESC");
        while($details = $db->fetch_array($query)) {

            $wiredate = gmdate("F dS", $details[dateline] + ($timeoffset * 3600) + ($addtime * 3600));
            $wiredon = "$wiredate $wiretime";
            if($details[comment] != ""){ $comad1 = "<i>"; $comad2 = "</i>"; }else{ $comad1 = ""; $comad2 = ""; }

            $wi++;
            $totmoney[] = $details[amount];

            if ($wi % 2) $background_color = $altbg1;
            else $background_color = $altbg2;

            if($details[fromuid] == "del"){ $delperson = '<i>Deleted</i>'; }
            elseif($details[fromuid] == "0"){ $delperson = 'Shop'; }
            else{ if($details[type] != "Request"){ $toto = "$details[fromuid]"; }else{ $toto = "$details[touid]"; }
                $memberquery = $db->query("SELECT username FROM $table_members WHERE uid='$toto'"); $username = rawurlencode($db->result($memberquery, 0));
                $delperson = '<a alt="'.$lang_bank_o20.'" href="member.php?action=viewpro&amp;member='.$username.'">'.$username.'</a>'; 
            }

            $Boutput .= '<tr class="tablerow" bgcolor="'.$background_color.'">
                <td align="center" valign="middle">'.$wiredate.'</td>
                <td align="center" valign="middle">'.$delperson.'</td>
                <td align="center" valign="middle">'.$details[type].'</td>
                <td align="center" valign="middle">'.$comad1.'<a title="'.$lang_bank_o18.' '.$details[wireid].'" href="bank.php?folder=info&amp;tid='.$details[wireid].'">'.$details[statusline].'</a>'.$comad2.'</td>
                <td align="center" valign="middle">'.postify(stripslashes($details[subject]), on, on, on, off, on, off).'</td>
                <td align="center" valign="middle">'.$details[amount].'</td>
            </tr>';
        }
        if ($wi % 2) $background_color = $altbg2;
        else $background_color = $altbg1;
        if($totmoney!=""){ $tttotal = array_sum($totmoney); }else{ $tttotal="0"; }
        $Boutput .= '<tr><td colspan="6" align="center" class="mediumtxt" bgcolor="'.$background_color.'">'.$lang[bank_o19b].' '.$monthNames[$month].' '.$year.': '.$tttotal.'</td></tr>';
    }



// outcoming money
    elseif($folder == "out") {
        $Boutput .= $nextprevlinks;
        $Boutput .= '<tr class="tablerow">
            <td align="center" valign="middle">'.$lang[bank_o11].'</td>
            <td align="center" valign="middle">'.$lang[bank_o17].'</td>
            <td align="center" valign="middle">'.$lang[bank_o13].'</td>
            <td align="center" valign="middle">'.$lang[bank_o14].'</td>
            <td align="center" valign="middle">'.$lang[bank_o15].'</td>
            <td align="center" valign="middle">'.$lang[bank_o16].'</td>
        </tr>';

        $query = $db->query("SELECT * FROM $table_bank_transfer WHERE ((fromuid='$self[uid]' AND type!='Request' AND UndoI='0') OR (touid='$self[uid]' AND type='Request' AND confirmline!='0' AND statusline='Completed')) AND monthnum='$month' AND yearnum='$year' ORDER BY dateline DESC");
        while($details = $db->fetch_array($query)) {

            $wiredate = gmdate("F dS", $details[dateline] + ($timeoffset * 3600) + ($addtime * 3600));
            $wiredon = "$wiredate $wiretime";
            if($details[comment] != ""){ $comad1 = "<i>"; $comad2 = "</i>"; }else{ $comad1 = ""; $comad2 = ""; }

            $wi++;
            $totmoney[] = $details[amount];

            if ($wi % 2) $background_color = $altbg1;
            else $background_color = $altbg2;

            if($details[touid] == "del"){ $delperson = '<i>Deleted</i>'; }
            elseif($details[touid] == "0"){ $delperson = 'Shop'; }
            else{ $memberquery = $db->query("SELECT username FROM $table_members WHERE uid='$details[touid]'"); $username = rawurlencode($db->result($memberquery, 0));
                $delperson = '<a alt="'.$lang_bank_o20.'" href="member.php?action=viewpro&amp;member='.$username.'">'.$username.'</a>'; 
            }

            $Boutput .= '<tr class="tablerow" bgcolor="'.$background_color.'">
                <td align="center" valign="middle">'.$wiredate.'</td>
                <td align="center" valign="middle">'.$delperson.'</td>
                <td align="center" valign="middle">'.$details[type].'</td>
                <td align="center" valign="middle">'.$comad1.'<a title="'.$lang_bank_o18.' '.$details[wireid].'" href="bank.php?folder=info&amp;tid='.$details[wireid].'">'.$details[statusline].'</a>'.$comad2.'</td>
                <td align="center" valign="middle">'.postify(stripslashes($details[subject]), on, on, on, off, on, off).'</td>
                <td align="center" valign="middle">'.$details[amount].'</td>
            </tr>';
        }
        if ($wi % 2) $background_color = $altbg2;
        else $background_color = $altbg1;
        if($totmoney!=""){ $tttotal = array_sum($totmoney); }else{ $tttotal="0"; }
        $Boutput .= '<tr><td colspan="6" align="center" class="mediumtxt" bgcolor="'.$background_color.'">'.$lang[bank_o19a].' '.$monthNames[$month].' '.$year.': '.$tttotal.'</td></tr>';
    }



// send money
    elseif($folder == "wire" || $folder == "request") {
        if($amount){ $amount = checkInput($amount); }
        if(!$wiresubmit) { $touser = $username;
            $query = $db->query("SELECT uid, username FROM $table_members ORDER BY username");
            $wireclient = "<select name=\"wireto\">";
            if(!$touser) { $wireclient .= "<option value=\"\" selected></option>"; }
            while($members = $db->fetch_array($query)) {
                if($touser && $members[username] == $touser) { $wireclient .= "<option value=\"" . $members[uid] . "\" selected>" . $members[username] . "</option>"; }
                else { $wireclient .= "<option value=\"" . $members[uid] . "\">" . $members[username] . "</option>"; }
            }
            $wireclient .= "<\select>";

            $Boutput .= '<form method="post" action="bank.php?folder=wire">';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" width="100" class="tablerow">'.$lang[bank_o21].': </td>	<td bgcolor="'.$altbg2.'" class="tablerow">'.$wireclient.'</td><tr>';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o16].': </td>		<td bgcolor="'.$altbg2.'" class="tablerow"><input type="text" name="amount" value="'.$amount.'" size="41" maxlength="10"> '.$lang_shop_currency.'</td><tr>';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o13].': </td>		<td bgcolor="'.$altbg2.'" class="tablerow">';
            if($folder == "wire"){
                $Boutput .= '<select name="ttype">
                    <option value="" selected></option>
                    <option value="Goods">Goods</option>
                    <option value="Instant">Instant Cash</option>
                    <option value="Payment">Payment</option>
                </select></td><tr>';
                $bank_wire_button = $lang[bank_o22];
            }else{
                $Boutput .= $lang[bank_o23].'<input type="hidden" name="ttype" value="Request" /></td><tr>';
                $bank_wire_button = $lang[bank_o23];
            }
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o15].': </td>		<td bgcolor="'.$altbg2.'" class="tablerow"><input type="text" name="subject" size="41" maxlength="20" value="Optional" /></td></tr>';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o24].': </td>		<td bgcolor="'.$altbg2.'" class="tablerow"><textarea name="comment" cols="40" rows="5" class="textarea">Optional</textarea></td><tr>';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow">'.$lang[bank_o08].' </td>		<td bgcolor="'.$altbg2.'" class="tablerow"><input type="submit" name="wiresubmit" value="'.$bank_wire_button.'" /></td></tr>';
            $Boutput .= '</form>';
        }else{
            if($wireto == ""){
                $Boutput .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" valign=\"middle\">".$lang[bank_o25]."</td></tr>";
            }else{
                if($ttype == ""){
                    $Boutput .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" valign=\"middle\">".$lang[bank_o26]."</td></tr>";
                }else{

                    if($amount == ""){ $amount = "0"; }

                    if($subject == "Optional"){ $subject = ''; }
                    $subject = checkInput($subject);
                    $subject = addslashes($subject);

                    if($comment == "Optional"){ $comment = ''; }
                    $comment = checkInput($comment);
                    $comment = addslashes($comment);

                    $querf = $db->query("SELECT username, money FROM $table_members WHERE uid='$self[uid]'");
                    $memberdetails = $db->fetch_array($querf);
                    $moneyfrom = $memberdetails[username];

                    $querg = $db->query("SELECT username, status, banksettings FROM $table_members WHERE uid='$wireto'");
                    $receiverdetails = $db->fetch_array($querg);
                    $moneyto = $receiverdetails[username];

                //  if($receiverdetails[status] == "Banned"){
                //      $Boutput .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" valign=\"middle\">".$lang[bank_o27]."</td></tr>";
                //  }else{
                        if(($ttype != "Request" && $memberdetails[money] >= $amount) OR ($ttype == "Request" && $amount >= 0)){
                            do_bank_dates();

                            if($ttype != "Request"){
                                $db->query("UPDATE $table_members SET money=money+$amount WHERE uid='$wireto'");
                                $db->query("UPDATE $table_members SET money=money-$amount WHERE uid='$self[uid]'");
                                $db->query("INSERT INTO $table_bank_transfer VALUES('', '$self[uid]', '$wireto', '$loltime', '$amount', '$comment', '$att2', '$att4', '$att5', '$ttype', '$subject', 'Completed', '0', '0')");
                                if(bank_su($receiverdetails[banksettings], 1) == "1"){
                                    $btid = $db->insert_id();
                                    $u2usubject = $lang['bank_u2u_01']; $u2usubject = str_replace("*moneyfrom*", $moneyfrom, $u2usubject); $u2usubject = str_replace("*moneyto*", $moneyto, $u2usubject);
                                    $u2umessage = $lang['bank_u2u_02']; $u2umessage = str_replace("*moneyfrom*", $moneyfrom, $u2umessage); $u2umessage = str_replace("*moneyto*", $moneyto, $u2umessage); $u2umessage = str_replace("*btid*", $btid, $u2umessage);
                                    bank_u2u($moneyto, 'Bank', $u2usubject, $u2umessage);
                                }
                                $Boutput .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" valign=\"middle\">".$lang[bank_o28]."</td></tr>";
                            }else{
                                $db->query("INSERT INTO $table_bank_transfer VALUES('', '$self[uid]', '$wireto', '$loltime', '$amount', '$comment', '$att2', '$att4', '$att5', '$ttype', '$subject', 'Open', '0', '0')");
                                if(bank_su($receiverdetails[banksettings], 2) == "1"){
                                    $btid = $db->insert_id();
                                    $u2usubject = $lang['bank_u2u_03']; $u2usubject = str_replace("*moneyfrom*", $moneyfrom, $u2usubject); $u2usubject = str_replace("*moneyto*", $moneyto, $u2usubject);
                                    $u2umessage = $lang['bank_u2u_04']; $u2umessage = str_replace("*moneyfrom*", $moneyfrom, $u2umessage); $u2umessage = str_replace("*moneyto*", $moneyto, $u2umessage); $u2umessage = str_replace("*btid*", $btid, $u2umessage);
                                    bank_u2u($moneyto, 'Bank', $u2usubject, $u2umessage);
                                }
                                $Boutput .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" valign=\"middle\">".$lang[bank_o29]."</td></tr>";

                            }
                            redirect("bank.php", 2, X_REDIRECT_JS);
                        }else{
                            $Boutput .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" valign=\"middle\">".$lang[bank_o30]."</td></tr>";
                        }
                //  }
                }
            }
        }
    }



// requested money
    elseif($folder == "requested") {

        if(!$s){
            $Boutput .= "<tr><td height=\"50%\" align=\"center\" valign=\"middle\" class=\"mediumtxt\" onClick=\"location.href='bank.php?folder=requested&amp;s=in'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='".$altbg1."'; \"onmouseout=\"this.style.backgroundColor='".$altbg2."'; \">".$lang[bank_o31]."</td></tr>";
            $Boutput .= "<tr><td height=\"50%\" align=\"center\" valign=\"middle\" class=\"mediumtxt\" onClick=\"location.href='bank.php?folder=requested&amp;s=out'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='".$altbg1."'; \"onmouseout=\"this.style.backgroundColor='".$altbg2."'; \">".$lang[bank_o32]."</td></tr>";
        }else{
            if($tid){
                if($p == "1"){
                    $query = $db->query("SELECT * FROM $table_bank_transfer WHERE touid='$self[uid]' AND wireid='$tid' AND statusline='Open'");
                    while($details = $db->fetch_array($query)) {

                        $db->query("UPDATE $table_bank_transfer SET statusline='Denied', confirmline='" . time() . "' WHERE touid='$self[uid]' AND wireid='$tid'");

                        $querg = $db->query("SELECT username, status, banksettings FROM $table_members WHERE uid='$details[fromuid]'");
                        $receiverdetails = $db->fetch_array($querg);
                        $moneyto = $receiverdetails[username];

                        if(bank_su($receiverdetails[banksettings], 3) == "1"){
                            $querf = $db->query("SELECT username, money FROM $table_members WHERE uid='$self[uid]'");
                            $memberdetails = $db->fetch_array($querf);
                            $moneyfrom = $memberdetails[username];
                            do_bank_dates();
                            $u2usubject = $lang['bank_u2u_05']; $u2usubject = str_replace("*moneyfrom*", $moneyfrom, $u2usubject); $u2usubject = str_replace("*moneyto*", $moneyto, $u2usubject);
                            $u2umessage = $lang['bank_u2u_06']; $u2umessage = str_replace("*moneyfrom*", $moneyfrom, $u2umessage); $u2umessage = str_replace("*moneyto*", $moneyto, $u2umessage); $u2umessage = str_replace("*btid*", $btid, $u2umessage);
                            bank_u2u($moneyto, 'Bank', $u2usubject, $u2umessage);
                        }
                    }
                }else{
                    $query = $db->query("SELECT * FROM $table_bank_transfer WHERE touid='$self[uid]' AND wireid='$tid' AND statusline='Open'");
                    while($details = $db->fetch_array($query)) {
                        $querg = $db->query("SELECT username, status, banksettings FROM $table_members WHERE uid='$details[fromuid]'");
                        $receiverdetails = $db->fetch_array($querg);
                        $moneyto = $receiverdetails[username];

                    //  if($receiverdetails[status] == "Banned"){
                    //      $Boutput .= "<tr><td colspan=\"6\" bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\" valign=\"middle\">".$lang[bank_o27]."</td></tr>";
                    //  }else{
                            $querf = $db->query("SELECT username, money FROM $table_members WHERE uid='$details[touid]'");
                            $memberdetails = $db->fetch_array($querf);
                            $moneyfrom = $memberdetails[username];

                            if($memberdetails[money] >= "$details[amount]") {
                                $db->query("UPDATE $table_bank_transfer SET statusline='Completed', confirmline='" . time() . "' WHERE touid='$self[uid]' AND wireid='$tid'");
                                $db->query("UPDATE $table_members SET money=money+$details[amount] WHERE uid='$details[fromuid]'");
                                $db->query("UPDATE $table_members SET money=money-$details[amount] WHERE uid='$details[touid]'");

                                do_bank_dates();

                             // $db->query("INSERT INTO $table_bank_transfer VALUES('', '$self[uid]', '$details[fromuid]', '$loltime', '$details[amount]', '$comment', '$att2', '$att4', '$att5', '$ttype', '$subject', 'Open', '0', '0')");
                                if(bank_su($receiverdetails[banksettings], 4) == "1"){
                                    $u2usubject = $lang['bank_u2u_07']; $u2usubject = str_replace("*moneyfrom*", $moneyfrom, $u2usubject); $u2usubject = str_replace("*moneyto*", $moneyto, $u2usubject);
                                    $u2umessage = $lang['bank_u2u_08']; $u2umessage = str_replace("*moneyfrom*", $moneyfrom, $u2umessage); $u2umessage = str_replace("*moneyto*", $moneyto, $u2umessage); $u2umessage = str_replace("*btid*", $btid, $u2umessage);
                                    bank_u2u($moneyto, 'Bank', $u2usubject, $u2umessage);
                                }
                            }else{
                                $Boutput .= '<tr><td colspan="6" bgcolor="'.$altbg2.'" class="tablerow" align="center">'.$lang[bank_o30].'</td></tr>';
                            }
                    //  }
                    }
                }
            }

            if($s == "in"){
                $fromto = "fromuid";     $fromtb = $lang[bank_o17];     $reqedtot = $lang[bank_o31];
            }else{
                $fromto = "touid";       $fromtb = $lang[bank_o12];     $reqedtot = $lang[bank_o32];
            }

            $Boutput .= $nextprevlinks;
            $Boutput .= '<tr class="tablerow">
                <td align="center" valign="middle">'.$lang[bank_o11].'</td>
                <td align="center" valign="middle">'.$fromtb.'</td>
                <td align="center" valign="middle">'.$lang[bank_o13].'</td>
                <td align="center" valign="middle">'.$lang[bank_o14].'</td>
                <td align="center" valign="middle">'.$lang[bank_o15].'</td>
                <td align="center" valign="middle">'.$lang[bank_o16].'</td>
            </tr>';

            $query = $db->query("SELECT * FROM $table_bank_transfer WHERE ($fromto='$self[uid]' AND type='Request' AND UndoI='0') AND monthnum='$month' AND yearnum='$year' ORDER BY dateline DESC");
            while($details = $db->fetch_array($query)) {
                if($s == "in"){ $fromta = "$details[touid]"; }else{ $fromta = "$details[fromuid]"; }

                $wiredate = gmdate("F dS", $details[dateline] + ($timeoffset * 3600) + ($addtime * 3600));
                $subject = stripslashes($details[subject]);
                $subject = postify($subject, on, on, on, off, on, off);
                if($details[comment] != ""){ $comad1 = "<i>"; $comad2 = "</i>"; }else{ $comad1 = ""; $comad2 = ""; }

                $wi++;
                if($details[statusline] != 'Open'){
                $totmoney2[] = $details[amount]; $totmoney1[] = $details[amount];
                }else{
                $totmoney1[] = $details[amount];
                }

                if ($wi % 2) $background_color = $altbg1;
                else $background_color = $altbg2;

                if($details[statusline] == "Open" && $details[touid] == "$self[uid]"){
                    $pd = ' (<a title="'.$lang[bank_o33].'" href="bank.php?folder=requested&amp;s='.$s.'&amp;tid='.$details[wireid].'&amp;p=0">+</a>/<a title="'.$lang[bank_o34].'" href="bank.php?folder=requested&amp;s='.$s.'&amp;tid='.$details[wireid].'&amp;p=1">-</a>)';
                }else{
                    $pd = '';
                }

                $memberquery = $db->query("SELECT username FROM $table_members WHERE uid='$fromta'");
                $username = $db->result($memberquery, 0); $encodeuser = rawurlencode($username);
                $Boutput .= '<tr class="tablerow" bgcolor="'.$background_color.'">
                    <td align="center" valign="middle">'.$wiredate.'</td>
                    <td align="center" valign="middle"><a alt="'.$lang[bank_o20].'" href="member.php?action=viewpro&amp;member='.$username.'">'.$username.'</a></td>
                    <td align="center" valign="middle">'.$details[type].'</td>
                    <td align="center" valign="middle">'.$comad1.'<a title="'.$lang[bank_o18].' '.$details[wireid].'" href="bank.php?folder=info&amp;tid='.$details[wireid].'">'.$details[statusline].'</a>'.$comad2.''.$pd.'</td>
                    <td align="center" valign="middle">'.$details[subject].'</td>
                    <td align="center" valign="middle">'.$details[amount].'</td>';

                $Boutput .= '</tr>';
            }
            if ($wi % 2) $background_color = $altbg2;
            else $background_color = $altbg1;
            if($totmoney1!=""){ $tttotal1 = array_sum($totmoney1); }else{ $tttotal1="0"; }
            if($totmoney2!=""){ $tttotal2 = array_sum($totmoney2); }else{ $tttotal2="0"; }
            $Boutput .= '<tr><td colspan="6" align="center" class="mediumtxt" bgcolor="'.$background_color.'">'.$lang[bank_o66].' '.$reqedtot.' in '.$monthNames[$month].' '.$year.': '.$tttotal2.' / '.$tttotal1.'</td></tr>';

        }
    }



// Transfer Details
    elseif($folder == "details" || $folder == "info") {
        if($folder == "details" && !$detailsubmit) {
            $Boutput .= '<form method="post" action="bank.php?folder=details">';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow" align="center">'.$lang[bank_o35].'</td></tr>';
            $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow" align="center"><input type="text" name="tid" size="10" maxlength="10" value="" /><br /><br /><input type="submit" name="detailsubmit" value="'.$lang[bank_o36].'" /></td></tr>';
            $Boutput .= '</form>';
        }else{
            $tid = checkInput($tid);
            $query = $db->query("SELECT * FROM $table_bank_transfer WHERE wireid='$tid'"); 
            $is_there = $db->fetch_array($query); $there_is = $is_there['wireid'];
            if($there_is != "$tid"){ 
                $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow" align="center" valign="middle">'.$lang[bank_o37].'</td></tr>';
            }elseif($is_there[fromuid] != "$self[uid]" && $is_there[touid] != "$self[uid]" && $status != "Super Administrator"){
                $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow" align="center" valign="middle">'.$lang[bank_o38].'</td></tr>';
            }else{
                if($is_there[fromuid] == "0"){
                    $fromuser = "Shop";
                }else{
                    $memberquerya = $db->query("SELECT username FROM $table_members WHERE uid='$is_there[fromuid]'");
                    $usernamea = $db->result($memberquerya, 0); $fromuser = rawurlencode($usernamea);
                }
                if($is_there[touid] == "0"){
                    $touser = "Shop";
                }else{
                    $memberqueryb = $db->query("SELECT username FROM $table_members WHERE uid='$is_there[touid]'");
                    $usernameb = $db->result($memberqueryb, 0); $touser = rawurlencode($usernameb);
                }

                $wiredate = gmdate("l, F dS, Y", $is_there[dateline] + ($timeoffset * 3600) + ($addtime * 3600));
                if($timeformat == "24" || $memtime == "24"){ $Btimecode = "H:i:s"; }else{ $Btimecode = "h:i:s A"; }
                $wiretime = gmdate("$Btimecode", $is_there[dateline] + ($timeoffset * 3600) + ($addtime * 3600));

                $subject = postify(stripslashes($is_there[subject]), on, on, on, off, on, off);
                if($subject == ""){ $subject = "<i>None</i>"; }

                $comment = postify(stripslashes($is_there[comment]), on, on, on, off, on, off);
                if($comment == ""){ $comment = "<i>None</i>"; }

                $Boutput .= '<tr><td colspan="2" bgcolor="'.$altbg2.'" class="tablerow" align="center"><b>'.$lang[bank_o39].' '.$there_is.' '.$lang[bank_o40].'</b></td></tr>'; 

                if($is_there[type] == "Request"){
                    $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o41].'</td>				<td class="tablerow" bgcolor="'.$altbg2.'">'.$fromuser.'</td></tr>'; 
                    $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o42].'</td>				<td class="tablerow" bgcolor="'.$altbg2.'">'.$touser.'</td></tr>'; 
                    if($is_there[confirmline] != "0"){
                        $awiredate = gmdate("l, F dS, Y", $is_there[confirmline] + ($timeoffset * 3600) + ($addtime * 3600));
                        $awiretime = gmdate("$Btimecode", $is_there[confirmline] + ($timeoffset * 3600) + ($addtime * 3600));
                    }else{
                        $awiredate = $lang[bank_o43];
                        $awiretime = $lang[bank_o43];
                    }
                    $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o44].'</td>				<td class="tablerow">'.$wiredate.'</td></tr>'; 
                    $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o45].'</td>				<td class="tablerow">'.$wiretime.'</td></tr>'; 
                    if($is_there[touid] == "$self[uid]" && $is_there[statusline] == "Open"){
                        $Boutput .= '<tr bgcolor="'.$altbg1.'"><td class="tablerow" align="right">&curren; <a href="bank.php?folder=requested&amp;s=out&amp;tid='.$is_there[wireid].'&amp;p=0">'.$lang[bank_o33].'</a> &curren;</td>		<td class="tablerow" align="left">&curren; <a href="bank.php?folder=requested&amp;s=out&amp;tid='.$is_there[wireid].'&amp;p=1">'.$lang[bank_o34].'</a> &curren;</td></tr>'; 
                        $Boutput .= '<tr><td class="tablerow" colspan="2"></td></tr>'; 
                    }else{
                        $Boutput .= '<tr bgcolor="'.$altbg1.'"><td colspan="2" class="tablerow">'.$lang[bank_o46].'</td></tr>'; 
                        $Boutput .= '<tr bgcolor="'.$altbg1.'"><td class="tablerow">'.$lang[bank_o11].'</td>	<td class="tablerow">'.$awiredate.'</td></tr>'; 
                        $Boutput .= '<tr bgcolor="'.$altbg1.'"><td class="tablerow">'.$lang[bank_o47].'</td>	<td class="tablerow">'.$awiretime.'</td></tr>'; 
                    }
                }else{
                    $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o12].':</td>				<td class="tablerow">'.$fromuser.'</td></tr>'; 
                    $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o17].':</td>				<td class="tablerow">'.$touser.'</td></tr>'; 
                    $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o11].':</td>				<td class="tablerow">'.$wiredate.'</td></tr>'; 
                    $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o47].'</td>				<td class="tablerow">'.$wiretime.'</td></tr>'; 
                }

                $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o16].':</td>					<td class="tablerow">'.$is_there[amount].'</td></tr>'; 
                $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o48].'</td>					<td class="tablerow">'.$is_there[type].'</td></tr>'; 

                $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o15].':</td>					<td class="tablerow">'.$subject.'</td></tr>'; 
                $Boutput .= '<tr><td class="tablerow">'.$lang[bank_o24].':</td>					<td class="tablerow">'.$comment.'</td></tr>'; 

                if($is_there[UndoI] != "0"){
                    $bul = explode("|#|#|#|#|", $is_there[UndoI]); 	// $bul[0]  is the date. $bul[1] is the uid
                    $undodate = gmdate("l, F dS, Y", $bul[0] + ($timeoffset * 3600) + ($addtime * 3600));
                    $undotime = gmdate("$Btimecode", $bul[0] + ($timeoffset * 3600) + ($addtime * 3600));
                    $memberquery0 = $db->query("SELECT username FROM $table_members WHERE uid='$bul[1]'");
                    $undop = $db->result($memberquery0, 0); $undop = rawurlencode($undop); 
                    $undoperson = '<a alt="'.$lang[bank_o20].'" href="member.php?action=viewpro&amp;member='.$undop.'">'.$undop.'</a>'; 
                    $Boutput .= '<tr bgcolor="'.$altbg1.'"><td colspan="2" class="tablerow">'.$lang[bank_o49].'</td></tr>'; 
                    $Boutput .= '<tr bgcolor="'.$altbg1.'"><td class="tablerow">'.$lang[bank_o50].'</td>	<td class="tablerow">'.$undodate.'</td></tr>'; 
                    $Boutput .= '<tr bgcolor="'.$altbg1.'"><td class="tablerow">'.$lang[bank_o51].'</td>	<td class="tablerow">'.$undotime.'</td></tr>'; 
                    $Boutput .= '<tr bgcolor="'.$altbg1.'"><td class="tablerow">'.$lang[bank_o52].'</td>	<td class="tablerow">'.$undoperson.'</td></tr>'; 
                }
            }
        }
    }



// undo a transfer
    elseif($folder == "undo") {
        if($status != "Administrator" && $status != "Super Administrator"){
            eval('echo stripslashes("'.template('error_nologinsession').'");'); end_time(); eval('echo stripslashes("'.template('footer').'");'); exit(); 
        }else{
            if(!$undosubmit){
                $Boutput .= '<form method="post" action="bank.php?folder=undo">';
                $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow" align="center">'.$lang[bank_o53].'</td><tr>';
                $Boutput .= '<tr><td bgcolor="'.$altbg1.'" class="tablerow" align="center">'.$lang[bank_o54].' <tr>';
                $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow" align="center"><input type="text" name="tid" size="15" maxlength="13" style="text-align: center" /></td><tr>';

                $Boutput .= '<tr><td bgcolor="'.$altbg1.'" class="tablerow" align="center">'.$lang[bank_o55].'<tr>';
                $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow" align="center"><textarea name="comment" cols="40" rows="5" class="textarea">Optional</textarea></td><tr>';
                $Boutput .= '<tr><td bgcolor="'.$altbg1.'" class="tablerow" align="center"><input type="submit" name="undosubmit" value="'.$lang[bank_o56].'" /></td></tr>';
                $Boutput .= '</form>';
            }else{
                $tid = checkInput($tid); if($tid == "" || $tid == "0"){ header("Location: bank.php?folder=undo"); exit(); }
                $query = $db->query("SELECT * FROM $table_bank_transfer WHERE wireid='$tid'"); 
                $is_there = $db->fetch_array($query); $there_is = $is_there['wireid'];
                if($there_is != "$tid"){ 
                    $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow" align="center" valign="middle">'.$lang[bank_o57].'</td></tr>';
                }else{
                    if($is_there[statusline] == "Completed" && $is_there[type] != "Request"){
                        if($is_there[fromuid] != "0" && $is_there[fromuid] != "del"){ $db->query("UPDATE $table_members SET money=money+$is_there[amount] WHERE uid='$is_there[fromuid]'"); }
                        if($is_there[touid] != "0" && $is_there[touid] != "del"){     $db->query("UPDATE $table_members SET money=money-$is_there[amount] WHERE uid='$is_there[touid]'"); }
                    }elseif($is_there[statusline] == "Completed" && $is_there[type] == "Request"){
                        if($is_there[touid] != "0" && $is_there[touid] != "del"){     $db->query("UPDATE $table_members SET money=money+$is_there[amount] WHERE uid='$is_there[touid]'"); }
                        if($is_there[fromuid] != "0" && $is_there[fromuid] != "del"){ $db->query("UPDATE $table_members SET money=money-$is_there[amount] WHERE uid='$is_there[fromuid]'"); }
                    }
                    $db->query("UPDATE $table_bank_transfer SET UndoI = '".time()."|#|#|#|#|$self[uid]' WHERE wireid='$is_there[wireid]'");

                    $u2usubject = $lang['bank_u2u_09']; 
                    $u2umessage = $lang['bank_u2u_10']; $u2umessage = str_replace("*btid*", $is_there[wireid], $u2umessage);

                    if($comment != "Optional" && $comment != ""){ 
                        $comment = checkInput($comment);
                        $comment = addslashes($comment);
                        $lang_cancelmoney_message .= "<br /><br />Staff Message:<br />$comment";
                    }
                    if($is_there[fromuid] != "0" && $is_there[fromuid] != "del"){ 
                        $query = $db->query("SELECT username FROM $table_members WHERE uid='$is_there[fromuid]'"); $U_fromuid = $db->result($query,0);
                        bank_u2u($U_fromuid, 'Bank', $u2usubject, $u2umessage);
                    }
                    if($is_there[touid] != "0" && $is_there[touid] != "del"){
                        $query = $db->query("SELECT username FROM $table_members WHERE uid='$is_there[touid]'"); $U_touid = $db->result($query,0);
                        bank_u2u($U_touid, 'Bank', $u2usubject, $u2umessage);
                    }
                    $Boutput .= '<tr><td bgcolor="'.$altbg2.'" class="tablerow" align="center" valign="middle">'.$lang[bank_o58].'</td></tr>';
                }
            }
        }
    }



// undo history
    elseif($folder == "undohistory") {
        if($status != "Administrator" && $status != "Super Administrator"){
            eval('echo stripslashes("'.template('error_nologinsession').'");'); end_time(); eval('echo stripslashes("'.template('footer').'");'); exit(); 
        }else{
            if(!$s){
                $Boutput .= "<tr><td height=\"50%\" align=\"center\" valign=\"middle\" class=\"mediumtxt\" onClick=\"location.href='bank.php?folder=undohistory&amp;s=all'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='".$altbg1."'; \"onmouseout=\"this.style.backgroundColor='".$altbg2."'; \">".$lang[bank_o59]."</td></tr>";
                $Boutput .= "<tr><td height=\"50%\" align=\"center\" valign=\"middle\" class=\"mediumtxt\" onClick=\"location.href='bank.php?folder=undohistory&amp;s=list'\" style=\"cursor:hand;\" onmouseover=\"this.style.backgroundColor='".$altbg1."'; \"onmouseout=\"this.style.backgroundColor='".$altbg2."'; \">".$lang[bank_o60]."</td></tr>";
            }else{
                if($s != "all"){ $Boutput .= $nextprevlinks; }
                $Boutput .= '<tr class="tablerow">
                    <td align="center" valign="middle">'.$lang[bank_o61].'</td>
                    <td align="center" valign="middle">'.$lang[bank_o12].'</td>
                    <td align="center" valign="middle">'.$lang[bank_o17].'</td>
                    <td align="center" valign="middle">'.$lang[bank_o14].'</td>
                    <td align="center" valign="middle">'.$lang[bank_o15].'</td>
                    <td align="center" valign="middle">'.$lang[bank_o16].'</td>
                    <td align="center" valign="middle">'.$lang[bank_o62].'</td>
                    <td align="center" valign="middle">'.$lang[bank_o63].'</td>
                </tr>';

                if($s == "all"){
                    $query = $db->query("SELECT * FROM $table_bank_transfer WHERE UndoI != '0'");
                }else{
                    $query = $db->query("SELECT * FROM $table_bank_transfer WHERE UndoI != '0' AND monthnum='$month' AND yearnum='$year' ORDER BY dateline DESC");
                }
                while($details = $db->fetch_array($query)) {

                    $wiredate = gmdate("F dS", $details[dateline] + ($timeoffset * 3600) + ($addtime * 3600));
                    $subject = stripslashes($details[subject]);
                    $subject = postify($subject, on, on, on, off, on, off);
                    if($details[comment] != ""){ $comad1 = "<i>"; $comad2 = "</i>"; }else{ $comad1 = ""; $comad2 = ""; }

                    $wi++;
                    if ($wi % 2) $background_color = $altbg1;
                    else $background_color = $altbg2;

                    $bul = explode("|#|#|#|#|", $details[UndoI]); 	// $bul[0]  is the date. $bul[1] is the uid
                    $undodate = gmdate("F dS", $bul[0] + ($timeoffset * 3600) + ($addtime * 3600));
                    $memberquery0 = $db->query("SELECT username FROM $table_members WHERE uid='$bul[1]'");
                    $undop = $db->result($memberquery0, 0); $undop = rawurlencode($undop); 
                    $undoperson = '<a alt="'.$lang[bank_o20].'" href="member.php?action=viewpro&amp;member='.$undop.'">'.$undop.'</a>'; 

                    if($details[type] != "Request"){ $dtoto = "$details[touid]"; }else{ $dtoto = "$details[fromuid]"; } 
                    if($dtoto == "del"){ $toperson = "<i>Deleted</i>"; }elseif($dtoto == "0"){ $totoperson = "Shop"; }else{ 
                        $memberquery1 = $db->query("SELECT username FROM $table_members WHERE uid='$dtoto'");
                        $toto = $db->result($memberquery1, 0); $toto = rawurlencode($toto);
                        $totoperson = '<a alt="'.$lang[bank_o20].'" href="member.php?action=viewpro&amp;member='.$toto.'">'.$toto.'</a>'; 
                    }

                    if($details[type] != "Request"){ $dfromp = "$details[fromuid]"; }else{ $dfromp = "$details[touid]"; } 
                    if($dfromp == "del"){ $fromperson = "<i>Deleted</i>"; }elseif($dfromp == "0"){ $fromperson = "Shop"; }else{ 
                        $memberquery2 = $db->query("SELECT username FROM $table_members WHERE uid='$dfromp'");
                        $fromp = $db->result($memberquery2, 0); $fromp = rawurlencode($fromp);
                        $fromperson = '<a alt="'.$lang[bank_o20].'" href="member.php?action=viewpro&amp;member='.$fromp.'">'.$fromp.'</a>'; 
                    }

                    $Boutput .= '<tr class="tablerow" bgcolor="'.$background_color.'">
                        <td align="center" valign="middle">'.$wiredate.'</td>
                        <td align="center" valign="middle">'.$fromperson.'</td>
                        <td align="center" valign="middle">'.$totoperson.'</td>
                        <td align="center" valign="middle">'.$comad1.'<a title="'.$lang[bank_o18].' '.$details[wireid].'" href="bank.php?folder=info&amp;tid='.$details[wireid].'">'.$details[statusline].'</a>'.$comad2.'</td>
                        <td align="center" valign="middle">'.$details[subject].'</td>
                        <td align="center" valign="middle">'.$details[amount].'</td>
                        <td align="center" valign="middle">'.$undoperson.'</td>
                        <td align="center" valign="middle">'.$undodate.'</td>
                    </tr>';
                }
            }
        }
    }



// non excistant action
    else{
        $bank_status = " &raquo; ".$lang[bank_o64];
        $Boutput .= "<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\">".$lang[bank_o65]."</td></tr>";
    }



$Boutput .= '</table>
</td>
<td width="2" bgcolor="'.$altbg2.'" style="border-left:1px solid '.$bordercolor.'; border-right:1px solid '.$bordercolor.'; ">&nbsp;</td>
<td valign="top" bgcolor="'.$altbg2.'">'.$menu.'</td> </tr> </table>';

// Create and show header
    $navigation .= $bank_status;
    eval('echo stripslashes("'.template('header').'");');

// Show Page
    $Boutput .= '<br /><table cellspacing="0" cellpadding="0" border="0" width="'.$tablewidth.'" align="center"><tr><td bgcolor="'.$bordercolor.'">  <table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="100%"><tr><td bgcolor="'.$altbg1.'" class="smalltxt" align="right" width="100%">The Bank V2.4; FunForum, &copy; 2003-2004</td></tr></table>  </td></tr></table>';
    echo stripslashes($Boutput);

// Create and show footer
    end_time();
    eval('echo stripslashes("'.template('footer').'");');
    exit;
?>