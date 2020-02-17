<?php
/* $Id: xmb.php,v 1.3 2004/04/17 18:46:44 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

// This makes XMB compatible with the latest PHP changes (4.2.*) (mainly 4.2.1 and 4.2.2)
if (!isset($_SERVER)) {
    $_GET        = &$HTTP_GET_VARS;
    $_POST        = &$HTTP_POST_VARS;
    $_ENV        = &$HTTP_ENV_VARS;
    $_SERVER    = &$HTTP_SERVER_VARS;
    $_COOKIE    = &$HTTP_COOKIE_VARS;
    $_FILES        = &$HTTP_POST_FILES;
    $_REQUEST    = array_merge($_GET, $_POST, $_COOKIE);
}

$global = @array(0 => $_GET, 1 => $_POST, 2 => $_ENV, 3=> $_COOKIE, 4=> $_SESSION, 5 => $_SERVER, 6 => $_FILES);

if (get_magic_quotes_gpc() === 0) {
    foreach ($global as $keyg => $valg) {
        if (is_array($valg)) {
            foreach ($valg as $keya => $vala) {
                if (is_array($vala)) {
                    foreach ($vala as $keyv => $valv) {
                        if (gettype($valv) == "string") {
                            $global[$keyg][$keya][$keyv] = addslashes($valv);
                        }
                    }
                }
                elseif (gettype($vala) == "string") {
                    $global[$keyg][$keya] = addslashes($vala);
                }
            }
        }
    }
    foreach ($global as $num => $array) {
        if (is_array($array)) {
            extract($array, EXTR_OVERWRITE);
        }
    }
}
else {
    foreach ($global as $num => $array) {
        if (is_array($array)) {
            extract($array, EXTR_OVERWRITE);
        }
    }
}
?>