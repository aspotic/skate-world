<?php
/* $Id: config.php,v 1.3 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

// Database connection settings
    $dbname         = 'switchsk_forum';       // Name of your database
    $dbuser         = 'switchsk_forum';       // Username used to access it
    $dbpw           = 'forum';      // Password used to access it
    $dbhost         = 'localhost';  // Database host, usually 'localhost'
    $database       = 'mysql';      // Database type, currently only mysql is supported.
    $pconnect       = 0;            // Persistent connection, 1 = on, 0 = off, use if 'too many connections'-errors appear

// Table Settings
    $tablepre       = 'xmb_';   // Table-pre

// Path-settings
    // In full_path, put the full URL you see when you go to your boards, WITHOUT the filename though!!
    // And please, don't forget the / at the end...
    $full_url       = 'http://www.switchskate.net/forum/';

// Other settings
    // the Maximum attachment-filesize, in bytes!
    // 1MB = 1024*1024 bytes (default is set to 1MB)
    $max_attach_size= (1024*1024);

    // There are situations where you don't want to see the <!-- template start: index -->...<!-- template end: index -->
    // tags around each template. In those cases, change the following to FALSE, or TRUE to turn it back on.
    // Default value: false;
    $comment_output = false;

// Debug-mode
    // To turn on DEBUG mode (you can then see ALL queries done at the bottom of each screen (except buddy-list & u2u)
    // just uncomment this variable. These queries are ONLY visible to the user currently loading that page
    // and ONLY visible to Super Administrators
    //
    // $debug = 1;
    //
    // uncomment that variable to start debug mode (1.9 alpha & beta only)

// Plugin Settings
    $i=1;
    // Start Plugin code

    $plugname[$i]    = '[ Awards';       // Added plugin name, to create another plugin, copy and change [1] to [2] etc
    $plugurl[$i]     = 'awedit.php?action=addawards';       // This is the location, link, or URL to the plugin
    $plugadmin[$i]   = true;    // Is this plugin only for admins? Set to TRUE for admins, FALSE when it's for public
    $plugimg[$i]     = '';       // This is the path to the image to show in front of the text.
    $i++;
	
	$plugname[$i]    = 'Membergroups ]';
	$plugurl[$i]     = 'mg.php';
	$plugadmin[$i]   = true;	
	$plugimg[$i]     = '';
	$i++;
	
    // End plugin code.
    //
    // To make multiple plugins, copy and paste this plugin-code, so you have multiple entries

// Registration settings
    /***************
     * To allow:
     * registrations from the same IP to happen more than once per 24 hours,
     * turn the following option 'off'. Meaning instead of the default:
     * $ipreg = 'on';
     *
     * change it to:
     * $ipreg = 'off';
     *
     ****************
     * The ipcheck, checks if your IP is a valid IPv4 or IPv9 type, if none of these, it will kill.
     * this might shut a few users out, so you can turn it off by changing the $ipcheck variable to 'off'
     ****************
     * The allow_spec_q variable specifies if Special queries (eg. USE databasr and SHOW DATABASES) are allowed.
     * By default, they are not, meaning $allow_spec_q = false;
     * To allow them, change $allow_spec_q to TRUE ($allow_spec_q = true;)
     ****************/


    $ipreg          = 'on';
    $ipcheck        = 'off';
    $allow_spec_q   = false;
?>