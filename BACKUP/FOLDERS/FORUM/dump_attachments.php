<?php
/* $Id: dump_attachments.php,v 1.3 2004/04/17 19:10:01 IT Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

require './header.php';

if(!X_ADMIN || $self['status'] != 'Super Administrator') {
    eval("\$notadmin = \"".template("error_nologinsession")."\";");
    echo $notadmin;
    end_time();
    eval("\$footer = \"".template("footer")."\";");
    echo $footer;
    exit();
}

if($action == 'restore_attachments'){
    $i = 0;
    if(!is_readable('./attachments')){
        exit('attachments directory ("./attachments") should be chmodded to 777 so XMB can write to it.');
    }

    $trans = array( 0=> 'aid',
            1=> 'tid',
            2=> 'pid',
            3=> 'filename',
            4=> 'filetype',
            5=> 'filesize',
            6=> 'downloads'
            );

    $mainstream = fopen('./attachments/index.inf', 'r');
    while(($line = fgets($mainstream)) !== false){
        $attachment = array();

        $attachment = array_keys2keys(explode('//||//', $line), $trans);

        $stream = fopen('./attachments/'.$attachment['aid'].'.xmb', 'r');
        $attachment['attachment'] = fread($stream, filesize('./attachments/'.$attachment['aid'].'.xmb'));
        fclose($stream);

        $db->query("DELETE FROM $table_attachments WHERE aid='$attachment[aid]' AND tid='$attachment[tid]' AND pid='$attachment[pid]'");
        $db->query("INSERT INTO $table_attachments VALUES ('$attachment[aid]', '$attachment[tid]', '$attachment[pid]', '$attachment[filename]', '$attachment[filetype]', '$attachment[filesize]', '$attachment[attachment]', '$attachment[downloads]')");

        $i++;
    }

    fclose($mainstream);

    echo $i.' attachments stored';
}elseif($action == 'dump_attachments'){
    $i = 0;

    if(!is_writable('./attachments')){
        exit('attachments directory ("./attachments") should be chmodded to 777 so XMB can write to it.');
    }

    $query = $db->unbuffered_query("SELECT * FROM $table_attachments");
    while($attachment = $db->fetch_array($query)){
        $stream = @fopen('./attachments/'.$attachment['aid'].'.xmb', 'w+');
        fwrite($stream, $attachment['attachment'], strlen($attachment['attachment']));
        fclose($stream);

        unset($attachment['attachment']);
        $info_string = implode('//||//', $attachment)."\n";

        $stream2 = @fopen('./attachments/index.inf', 'a+');
        fwrite($stream2, $info_string, strlen($info_string));
        fclose($stream2);

        $i++;
    }

    echo $i.' attachments stored';
}
?>