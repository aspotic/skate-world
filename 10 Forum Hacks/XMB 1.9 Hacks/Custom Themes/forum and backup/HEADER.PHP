<?php
/* $Id: header.php,v 1.3 2004/04/22 02:24:16 jleq Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

// Initialise pre-set Variables
    // Removal of the version information is against the XMB license agreement
    // You are NOT permitted to remove any copyright material from the XMB software
    // These strings can be pulled for use on any page as header is required by all XMB pages

    $alpha              = '';
    $beta               = 'beta';
    $gamma              = '';
    $service_pack       = '';

    $versioncompany     = 'Aventure Media & The XMB Group';
    $versionshort       = "XMB 1.9.0-dev";
    $versiongeneral     = 'XMB 1.9';
    $versionbuild       = 2004021722;
    $versionlong        = "Powered by XMB 1.9 Nexus ($alpha$beta$gamma$service_pack)";

    // Initialising certain key variables. These are default values, please don't change them!

    $server         = substr(getenv('SERVER_SOFTWARE'), 0, 3);

    $cookiepath     = '';
    $cookiedomain   = '';


    $onlinetime     = time();
    $mtime1         = explode(' ', microtime());
    $starttime      = $mtime1[1] + $mtime1[0];

    $navigation     = '';
    $bbcodescript       = '';
    $self           = array();
    $user           = (isset($user)) ? $user : '';
    $SETTINGS       = array();
    $THEME          = array();
    $links          = array();
    $lang           = array();
    $tempcache      = array();

    if(!defined('ROOT')){
        define('ROOT', './');
    }

    error_reporting(E_ALL&~E_NOTICE);


// Resolve Server specific issues
    if($server == 'Apa') {
        $wookie = $server;
        $url = getenv('REQUEST_URI');

    }elseif($server == 'Mic' || $server == 'Aby'){
        $protocol = (getenv('HTTPS') == 'off') ? ('http://') : ('https://');
        $query = (getenv('QUERY_STRING')) ? ('?'.getenv('QUERY_STRING')) : ('');
        $url = $protocol.getenv('SERVER_NAME').getenv('SCRIPT_NAME').$query;

    }elseif($server == 'Aby'){
        $protocol = (getenv('HTTPS') == 'on') ? ('https://') : ('http://');
        $query = (getenv('QUERY_STRING')) ? ('?'.getenv('QUERY_STRING')) : ('');
        $url = $protocol.getenv('SERVER_NAME').getenv('SCRIPT_NAME').$query;

    }else{
        $url = getenv('REQUEST_URI');
    }


// Required Files - XMB (Version/Patch File) Configuration File, Database Settings File
    require ROOT.'xmb.php';
    require ROOT.'config.php';
    require ROOT."db/$database.php";
    require ROOT.'functions.php';


// Cache-control
    header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

// Fix annoying bug in windows... *sigh*
    if($action != "attachment" && !($action == "templates" && isset($download)) && !($action == "themes" && isset($download)) && !($action == 'dbdump') && !($action == 'dump_attachments')){
        header("Content-type: text/html");
    }


// Get visitors IP address
    $onlineip = ((isset($_SERVER)) ? ((isset($_SERVER['HTTP_CLIENT_IP'])) ? ($_SERVER['HTTP_CLIENT_IP']) : (isset($_SERVER['HTTP_CLIENT_IP']) ? ($_SERVER['HTTP_CLIENT_IP']) : ($_SERVER['REMOTE_ADDR']))) : ((getenv('HTTP_CLIENT_IP')) ? (getenv('HTTP_CLIENT_IP')) : ((getenv('HTTP_CLIENT_IP')) ? (getenv('HTTP_CLIENT_IP')) : (getenv('REMOTE_ADDR')))));


// Security checks
    if (file_exists('./cinst.php') && !unlink('./cinst.php')) {
        exit('<h1>Error:</h1><br />The installation file (cinst.php) was found on the server, but could not be removed. Please remove it as soon as possible. If you have not yet installed XMB, please do so at this time. Instructions are included in the download.');
    }

    // Checks the format of the URL, blocks if necessary....
    if(eregi("\?[0-9]+$", $url)){
        exit("Invalid String Format, Please Check Your URL");
    }

    //Checks the IP-format, if it's not a IPv5, nor a IPv9 type, it will be blocked, safe to remove....
    if($ipcheck == 'on'){
        if(!eregi("^([0-9]{1,3}\.){3}[0-9]{1,3}$", $onlineip) && !eregi("^([a-z,0-9]{0,4}:){5}[a-z,0-9]{0,4}$", $onlineip)&& !stristr($onlineip, ':::::')){
            exit("Access to this website is currently not possible as your hostname/IP appears suspicous.");
        }
    }

    // Checks for various variables in the URL, if any of them is found, script is halted
    $url_check = Array('status=', 'xmbuser=', 'xmbpw=', '<script>');
    foreach ($url_check as $name) {
        if (strpos(urldecode($url), $name)){
            exit();
        }
    }


// Load Objects, and such
    $tables = array('attachments','banned','buddys','favorites','forums','logs','members','posts','ranks','restricted','settings','smilies','templates','themes','threads','u2u','whosonline','words', 'member_items', 'shop_auction_items', 'shop_bank', 'shop_cats', 'shop_comments', 'shop_items', 'shop_settings', 'shop_votes', 'membergroups');
    foreach($tables as $name) {
        ${'table_'.$name} = $tablepre.$name;
    }

    $db = new dbstuff;
    $db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);


// Load a few constants
    define('XMB_VERSION', $versiongeneral);
    define('XMB_BUILD', $versionbuild);

    define('X_REDIRECT_HEADER', 1);
    define('X_REDIRECT_JS', 2);


// Create cookie-settings
    if((!$full_url || empty($full_url)) && (!isset($ignore_fu) || $ignore_fu === false)){
        exit('<b>ERROR: </b><i>Please fill the $full_url variable in your config.php!</i>');
    }else{
        if(!(isset($ignore_fu) && $ignore_fu === true)){
            $array = parse_url($full_url);
            if(substr($array['host'], 0, 9) == 'localhost' || preg_match("/^([0-9]{1,3}\.){3}[0-9]{1,3}$/i", $array['host'])){
                $cookiedomain  = '';
            }else{
                $cookiedomain = str_replace('www', '', $array['host']);
            }
            $cookiepath = ($array['path'] == '/') ? ('') : ($array['path']);
        }
    }

// Set cookies
    $currtime1 = time() + (86400*365);
    $currtime2 = time() + 600;

    setcookie('xmblva', time(), $currtime1, $cookiepath, $cookiedomain);

    if(isset($xmblvb)){
        $thetime = $xmblvb;
    }elseif(isset($xmblva)){
        $thetime = $xmblva;
    }else{
        $thetime = time();
    }

    setcookie('xmblvb', $thetime, $currtime2, $cookiepath, $cookiedomain);

    $lastvisit = $thetime;
    $lastvisit2 = $lastvisit-540;

// Make all settings global, and put them in the $SETTINGS[] array
    $settingsquery = $db->query("SELECT * FROM $table_settings");
    foreach($db->fetch_array($settingsquery) as $key => $val) {
        $$key = $val;
        $SETTINGS[$key] = $val;
    }
	
// BEGIN PORTAL FUNCTIONS
    $pquery = mysql_query("SELECT * FROM ".$tablepre."portal_config");
    $PORTAL = mysql_fetch_array($pquery);
    $forumurl = "";
    $mainfor = "";
    switch($action) {
    case 'showindex':
    break;
    }
    if(isset($full_url) && $PORTAL['portal_status'] != "off") {
    $lang_pf = "Forums";
    $forumurl = " | <a href=\"index.php?action=showindex\">$lang_pf</a>";
    $pforumurl = "<a href=\"index.php?action=showindex\">$lang_pf</a>";
    mysql_query("UPDATE ".$tablepre."portal_config SET orderview='on'");
    }elseif(isset($full_url) && $PORTAL['portal_status'] == "off") {
    $lang_pf = "Portal";
    $lang_for = "Forums";
    $mainfor = "<li><a href=\"index.php\">$lang_for</a></li><br>";
    $forumurl = " | <a href=\"index.php?action=showportal\">$lang_pf</a>";
    $pforumurl = "<a href=\"index.php?action=showportal\">$lang_pf</a>";
    mysql_query("UPDATE ".$tablepre."portal_config SET orderview='off'");
    }
    if($action == "showindex" && $PORTAL['portal_status'] != "off") {
    $lang_pf = "Forums Page";
    $forumurl = " | <a href=\"index.php?action=showindex\">$lang_pf</a>";
    $pforumurl = "<a href=\"index.php?action=showindex\">$lang_pf</a>";
    mysql_query("UPDATE ".$tablepre."portal_config SET orderview='off'");
    }elseif($action == "showportal" && $PORTAL['portal_status'] == "off") {
    $lang_pf = "Portal Page";
    $lang_for = "Forums";
    $mainfor = "<li><a href=\"index.php\">$lang_for</a></li><br>";
    $forumurl = " | <a href=\"index.php?action=showportal\">$lang_pf</a>";
    $pforumurl = "<a href=\"index.php?action=showportal\">$lang_pf</a>";
    mysql_query("UPDATE ".$tablepre."portal_config SET orderview='on'");
    }
    // END PORTAL FUNCTIONS

// Get the user-vars, and make them semi-global
    if(!isset($xmbuser)){
        $xmbuser    = '';
        $xmbpw      = '';
    }

    if($xmbuser != ''){
        $query = $db->query("SELECT * FROM $table_members WHERE username='$xmbuser' AND password='$xmbpw'");
        $q = true;
    }else{
        $q = false;
    }

    if($q && $db->num_rows($query) === 1){
        foreach($db->fetch_array($query) as $key => $val) {
            $self[$key] = $val;
        }

        $timeoffset = $self['timeoffset'];
        $themeuser  = $self['theme'];
        $status     = $self['status'];
        $tpp        = $self['tpp'];
        $ppp        = $self['ppp'];
        $memtime    = $self['timeformat'];
        $memdate    = $self['dateformat'];
        $signature  = $self['sig'];
        $sig        = $signature;

        $invisible  = $self['invisible'];

        $time       = time();

        $langfile   = ($self['langfile'] == "" || !file_exists("lang/$self[langfile].lang.php")) ? $SETTINGS['langfile'] : $self['langfile'];

        if(!empty($theme)){
            $themeuser = $self['theme'];
        }
        $db->query("UPDATE $table_members SET lastvisit=".$db->time($onlinetime)." WHERE username='$xmbuser'");
    }else{
        $memtime    = '';
        $memdate    = '';
        $xmbuser    = '';
        $self       = array();
        $self['status'] = 'member';
        $xmbpw      = '';
        $sig        = '';
        $themeuser  = false;
        $invisible  = 0;
    }

    if($memtime == '') {
        if($timeformat == 24) {
            $timecode = "H:i";
        } else {
            $timecode = "h:i A";
        }
    } else {
        if($memtime == 24) {
            $timecode = "H:i";
        } else {
            $timecode = "h:i A";
        }
    }

    if($self['status'] == "Administrator" || $self['status'] == "Super Administrator" || $self['status'] == "Super Moderator" || $self['status'] == "Moderator") {
        define('X_STAFF', true);
    }else{
        define('X_STAFF', false);
    }
    if($self['status'] == 'Administrator' || $self['status'] == 'Super Administrator'){
        define('X_ADMIN', true);
    }else{
        define('X_ADMIN', false);
    }


// Get the required language file
    $bblang = $langfile;
    require ROOT."lang/$langfile.lang.php";
    require ROOT."lang/$langfile.shop.php";

// Checks for the possibility to register
    if($regstatus == "on" && empty($self['username'])) {
        $reglink = "- <a href=\"member.php?action=coppa\">$lang[textregister]</a>";
    }else{
        $reglink = '';
    }
	

// Creates login/logout links
    if($xmbuser && $xmbuser != '') {
        $loginout = "<a href=\"misc.php?action=logout\">$lang[textlogout]</a>";
        $memcp = "<a href=\"memcp.php\">Control Panel</a>";
        $onlineuser = $xmbuser;
				if($self['status'] == "Moderator") {
            $cplink4 = "- <a href=\"modcp.php?\">Moderator Panel</a>";
	}
		else{
            $cplink4 = '';
        }
				if($self['status'] == "Super Moderator") {
            $cplink3 = "- <a href=\"cp4.php?\">Moderator Panel</a>";
	}
		else{
            $cplink3 = '';
        }
				if($self['status'] == "Administrator") {
            $cplink2 = "- <a href=\"cp3.php?\">Administrator Panel</a>";
	}
		else{
            $cplink2 = '';
        }
		if($self['status'] == "Super Administrator") {
            $cplink = "- <a href=\"cp.php?\">Administrator Panel</a>";
			$webmail = "- <a href=\"webmail\">E-mail</a>";
			$cpanel = "- <a href=\"cpanel?\">CPanel</a>";
	}
		else{
            $cplink = '';
        }
        $notify = "$lang_loggedin $xmbuser<br>[$loginout - $memcp $cplink $cplink2 $cplink3 $cplink4 $cpanel $webmail]";
    } else {
        $loginout = "<a href=\"misc.php?action=login\">$lang[textlogin]</a>";
        $onlineuser = "xguest123";
        $self[status] = "";
        $notify = "$lang[notloggedin] [$loginout $reglink]";
    }
	
	
// Checks if the timeformat has been set, if not, use default
    if($memdate == "") {
        $dateformat = $dateformat;
    } else {
        $dateformat = $memdate;
    }

    $dformatorig = $dateformat;
    $dateformat = str_replace("mm", "n", $dateformat);
    $dateformat = str_replace("dd", "j", $dateformat);
    $dateformat = str_replace("yyyy", "Y", $dateformat);
    $dateformat = str_replace("yy", "y", $dateformat);


// Get themes, [fid, [tid]]
    if(isset($tid)){
        $query = $db->query("SELECT f.fid, f.theme FROM $table_forums f, $table_threads t WHERE f.fid=t.fid AND t.tid='$tid'");
        while($locate = $db->fetch_array($query)){
            $fid = $locate['fid'];
            $forumtheme = $locate['theme'];
        }
    }elseif(isset($fid)){
        $query = $db->query("SELECT theme, name FROM $table_forums WHERE fid='$fid'");
        while($locate = $db->fetch_array($query)){
            $forumtheme = $locate['theme'];
        }
    }

    $wollocation = addslashes($url);
    $newtime = time() - 600;

    $db->query("DELETE FROM $table_whosonline WHERE (ip='$onlineip' && username='xguest123') OR (username='$xmbuser') OR time<'$newtime'");
    $db->query("INSERT INTO $table_whosonline VALUES('$onlineuser', '$onlineip', ".$db->time($onlinetime).", '$wollocation', '$invisible')");

// Check what theme to use
    if($themeuser) {
        $theme = $themeuser;
    } elseif(!empty($forumtheme)) {
        $theme = $forumtheme;
    } else {
        $theme = $SETTINGS['theme'];
    }

// Make theme-vars semi-global
    if($self[CustomTheme] == "1|0" || $self[CustomTheme] == "1|1"){
        $query = $db->query("SELECT * FROM ".$tablepre."members_themes WHERE uid='$self[uid]'");
        foreach($db->fetch_array($query) as $key => $val) {
            if($key != "name") {
                $$key = $val;
            }
            $THEME[$key] = $val;
        }
        if(false === strstr($imgdir, ".")){ $imgdir = './'.$imgdir; }
        if(false === strstr($bgcolor, "http:")){  $Fcthv1 = "$imgdir/$bgcolor";    }else{ $Fcthv1 = $bgcolor; }
        if(false === strstr($catcolor, "http:")){ $Fcthv2 = "$imgdir/$catcolor";   }else{ $Fcthv2 = $catcolor; }
        if(false === strstr($top, "http:")){      $Fcthv3 = "$imgdir/$top";        }else{ $Fcthv3 = $top; }
        if(false === strstr($boardimg, "http:")){ $Fcthv4 = $imgdir.'/'.$boardimg; }else{ $Fcthv4 = $boardimg; }
    }else{
        $query = $db->query("SELECT * FROM $table_themes WHERE themeid='$theme'");
        foreach($db->fetch_array($query) as $key => $val) {
            if($key != "name") {
                $$key = $val;
            }
            $THEME[$key] = $val;
        }
        $imgdir = './'.$imgdir;
        $Fcthv1 = "$imgdir/$bgcolor";
        $Fcthv2 = "$imgdir/$catcolor";
        $Fcthv3 = "$imgdir/$top";
        $Fcthv4 = $imgdir.'/'.$boardimg;
    }

    // Alters certain visibility-variables
    if(false === strstr($bgcolor, ".")) {
        $bgcode = "background-color: $bgcolor;";
    } else {
        $bgcode = "background-image: url('$Fcthv1');";
    }

    if(false === strstr($catcolor, ".")) {
        $catbgcode  = "bgcolor=\"$catcolor\"";
        $catcss     = 'background-color: '.$catcolor.';';
    } else {
        $catbgcode  = "style=\"background-image: url($Fcthv2)\"";
        $catcss     = 'background-image: url('.$Fcthv2.');';
    }

    if(false === strstr($top, ".")) {
        $topbgcode = "bgcolor=\"$top\"";
    } else {
        $topbgcode = "style=\"background-image: url($Fcthv3)\"";
    }

    if(false !== strpos($boardimg, ",")){
        $flashlogo = explode(",",$boardimg);
        $logo = '
             <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=6,0,0,0" ID=main WIDTH="'.$flashlogo[1].'" HEIGHT="'.$flashlogo[2].'">
                <PARAM NAME=movie VALUE="'.$imgdir.'/'.$flashlogo[0].'">
                <PARAM NAME="loop" VALUE="false">
                <PARAM NAME="menu" VALUE="false">
                <PARAM NAME="quality" VALUE="best">
                <EMBED src="'.$imgdir.'/'.$flashlogo[0].'" loop="false" menu="false" quality="best" WIDTH="'.$flashlogo[1].'" HEIGHT="'.$flashlogo[2].'" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
                </EMBED>
              </OBJECT>
             ';
    } else {
        $logo = '<a href="index.php"><img src="'.$Fcthv4.'" alt="'.$bbname.'" border="0" /></a>';
    }

// Font stuff...
    $fontedit = preg_replace('#(\D)#', '', $fontsize);
    $fontsuf  = preg_replace('#(\d)#', '', $fontsize);
    $font1 = $fontedit-1 . $fontsuf;
    $font3 = $fontedit+2 . $fontsuf;

// Update lastvisit in the header shown
    if(isset($lastvisit) && $xmbuser && $self['username'] != "") {
        $lastdate = gmdate("$dateformat",$xmblva + ($timeoffset * 3600) + ($addtime * 3600));
        $lasttime = gmdate("$timecode",$xmblva + ($timeoffset * 3600) + ($addtime * 3600));
        $lastvisittext = "$lang[lastactive] $lastdate $lang[textat] $lasttime";
    } else {
        $lastvisittext = "$lang[lastactive] $lang[textnever]";
    }

// Checks for various settings
    // Gzip-compression
    if (empty($action)) {
        $action = NULL;
    } else {
        $action = $action;
    }

    if($SETTINGS['gzipcompress'] == "on" && $action != "attachment") {
        if(function_exists('gzopen'))
        {
            ini_set('zlib.output_compression', 'On');
            ini_set('zlib.output_compression_level', '3');
        }
    }


//NAVIGATION BAR

	  $links[] = " [ <a href=\"index.php?action=showindex\"><font class=\"navtd\">Forums</font></a>";
	
	  $links[] = "<a href='#' onClick=\"Popup('chat.php', 'chat', 600, 350)\"><font class=\"navtd\">Chat Room</font></a>";
     
	  $links[] = "<a href=\"shoutbox.php\"><font class=\"navtd\">Shout Box</font></a>";

      $links[] = "<a href=\"shop.php\"><font class=\"navtd\">Shop</font></a> ] ";

      $links[] = " [ <a href=\"forumdisplay.php?fid=36\"><font class=\"navtd\">$lang[tricktips]</font></a>";

      $links[] = "<a href=\"forumdisplay.php?fid=37\"><font class=\"navtd\">$lang[reviews]</font></a>";

	  $links[] = "<a href=\"dictionary.php\"><font class=\"navtd\">$lang[dictionary]</font></a>";
   
      $links[] = "<a href=\"forumdisplay.php?fid=38\"><font class=\"navtd\">$lang[pictures]</font></a> ] ";
  
      $links[] = " [ <a href=\"links.php\"><font class=\"navtd\">$lang[links]</font></a> ]";


    // Search-link
    if($SETTINGS['searchstatus'] == "on") {
        $links[] = " [ <a href=\"misc.php?action=search\"><font class=\"navtd\">$lang[textsearch]</font></a>";
    }

    // Memberlist-link
    if($SETTINGS['memliststatus'] == "on") {
        $links[] = "<a href=\"misc.php?action=list\"><font class=\"navtd\">$lang[textmemberlist]</font></a>";
    }

    // Stats-link
    if($SETTINGS['stats'] == "on") {
        $links[] = "<a href=\"stats.php?action=view\"><font class=\"navtd\">$lang[navstats]</font></a>";
    }

    // Today's posts-link
    if($SETTINGS['todaysposts'] == "on") {
        $links[] = "<a href=\"today.php\"><font class=\"navtd\">$lang[navtodaysposts]</font></a>";
    }

    // Faq-link
    if($SETTINGS['faqstatus'] == "on") {
        $links[] = "<a href=\"faq.php\"><font class=\"navtd\">$lang[textfaq]</font></a> ] ";
    }

//END NAVIGATION BAR


    // 'Forum Rules'-link
    if($SETTINGS['bbrules'] == "on"){
        $links[] = "<img src=\"$imgdir/bbrules.gif\" alt=\"$lang[altrules]\" border=\"0\" /> <a href=\"faq.php?page=forumrules\"><font class=\"navtd\">$lang[textbbrules]</font></a>";
    }

    $links = implode(' &nbsp; ', $links);

	// Users Viewing Page Start

$listusers = "";
$countguests = 0;
$query = $db->query("SELECT username FROM $table_whosonline WHERE
location = '$wollocation' order by username");
while ($users = $db->fetch_array($query))
{
if ($users[username] != "xguest123")
{
if ($listusers != "")
$listusers .= ", " . "<a
href=\"member.php?action=viewpro&member=".rawurlencode($users[username]
)."\">$users[username]</a>";
else
$listusers = "<a
href=\"member.php?action=viewpro&member=".rawurlencode($users[username]
)."\">$users[username]</a>";
}
else
{
$countguests++;
}
}

// if no members, display "None"
if ($listusers == "") $listusers = "$lang[textnone]";

// display Guests if there are any
if ($countguests == 1) $listusers .= " (1 $lang[textguest1])";
if ($countguests > 1) $listusers .= " ($countguests $lang[textguests])";

// Users Viewing Page End
	
//Show all plugins
    if(!isset($plugname)){
        $plugname = array();
    }
    $pluglinks = array();

    for($plugnum=1; $plugnum <= count($plugname); $plugnum++) {
        if(!empty($plugurl[$plugnum]) && !empty($plugname[$plugnum])) {
            if(trim($plugimg[$plugnum]) != ''){
                $img = '<img src="'.$plugimg[$plugnum].'" border="0" />';
            }else{
                $img = '';
            }

            if($plugadmin[$plugnum] != "yes"){
                $pluglinks[] = "$img<a href=\"$plugurl[$plugnum]\"><font class=\"navtd\">$plugname[$plugnum]</font></a>";
            }elseif($self[status] == 'Administrator' || $self[status] == 'Super Administrator'){
                $pluglinks[] = "$img<a href=\"$plugurl[$plugnum]\"><font class=\"navtd\">$plugname[$plugnum]</font></a>";
            }
        }
    }
    if(count($pluglinks) == 0){
        $pluglink = '';
    }else{
        $pluglink = implode(' &nbsp; ', $pluglinks);
    }


// If the board is offline, display an appropriate message
    if($bbstatus == "off" && !($self[status] == "Administrator" || $self[status] == "Super Administrator") && false === strpos($url, "misc.php") && false === strpos($url, "member.php")){
        eval('$css = "'.template('css').'";');
        error(stripslashes($bboffreason));
    }

// If the board is set to 'reg-only' use, check if someone is logged in, and if not display a message
    if($regviewonly == "on") {
        if($onlineuser == "xguest123" && $action != "reg" && $action != "login" && $action != "lostpw" && $action != "coppa") {
            if($coppa == 'on'){
                $message = "In order to use this site you must be registered.  Please <a href=\"http://www.skate-world.net/member.php?action=coppa\">REGISTER</a> or <a href=\"http://www.skate-world.net/misc.php?action=login\">LOGIN</a>";
            }else{
                $message = "In order to use this site you must be registered.  Please <a href=\"http://www.skate-world.net/member.php?action=coppa\">REGISTER</a> or <a href=\"http://www.skate-world.net/misc.php?action=login\">LOGIN</a>";
            }
            eval('$css = "'.template('css').'";');
            error($message);
        }
    }

// Check if the user is ip-banned
    $ips = explode(".", $onlineip);
    // also disable 'ban all'-possibility
    $query = $db->query("SELECT id FROM $table_banned WHERE ((ip1='$ips[0]' OR ip1='-1') AND (ip2='$ips[1]' OR ip2='-1') AND (ip3='$ips[2]' OR ip3='-1') AND (ip4='$ips[3]' OR ip4='-1')) AND NOT (ip1='-1' AND ip2='-1' AND ip3='-1' AND ip4='-1')");
    $result = $db->fetch_array($query);

    // don't *ever* ban a (super-)admin!
    if(!X_ADMIN && ($self['status'] == "Banned" || $result )) {
        eval('$css = "'.template('css').'";');
        error($lang['bannedmessage']);
    }

// if the user is registered, check for new u2u's
    if($xmbuser) {
        $query = $db->query("SELECT * FROM $table_u2u WHERE owner='$self[username]' AND folder='Inbox' AND readstatus='no'");
        $newu2unum = $db->num_rows($query);
        if($newu2unum > 0) {
            $newu2umsg = "<a href=\"#\" onclick=\"Popup('u2u.php', 'Window', 700, 450);\">$lang[newu2u1] $newu2unum $lang[newu2u2]</a>";
        }else{
            $newu2umsg = '';
        }
    }else{
        $newu2umsg = '';
    }
	
$pquery = mysql_query("SELECT * FROM ".$tablepre."portal_config");
    $PORTAL = mysql_fetch_array($pquery);

?>
