<?php
/* $Id: sqlite.php,v 1.1 2004/04/17 10:39:29 Tularis Exp $ */
/*
    XMB 1.9
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB
*/

class dbstuff {
    var $querynum   = 0;
    var $querylist  = array();
    var $link   = '';
    var $timer  = 0;

    function connect($dbhost="xmb", $dbuser, $dbpw, $dbname, $pconnect=0, $force_db=false) {
        
        if($pconnect == 0) {
            $this->link = sqlite_open($dbhost, 0666, $error) or ($die=true);
        } else {
            $this->link = sqlite_popen($dbhost, 0666, $error) or ($die=true);
        }

        if($die){
            echo '<h3>Database connection error!!!</h3>';

            echo 'A connection to the Database could not be established.<br />';
            echo 'Please check your username, password, database name and host.<br />';
            echo 'Also make sure <i>config.php</i> is rightly configured!<br /><br />';

            echo 'When connecting, the database returned:<br />';
            echo '<i><b>Error: </b>'.$error.'</i>';
            exit();
        }
        unset($GLOBALS['dbhost'], $GLOBALS['dbuser'], $GLOBALS['dbpw']);
        return true;
    }
    
    function error() {
        return sqlite_error_string(sqlite_last_error($this->link));
    }

    function free_result($query) {
        // non-existant
    }

    function fetch_array($result, $type=SQLITE_ASSOC) {
        return sqlite_fetch_array($result, $type);
    }

    function query($sql) {
        $this->start_timer();

        $result = sqlite_query($this->link, $sql) or die(sqlite_error_string(sqlite_last_error($this->link)));

        $this->querynum++;
        $this->querylist[] = $sql;
        $this->stop_timer();

        return $result;
    }

    function unbuffered_query($sql) {
        $this->start_timer();

        $result = sqlite_unbuffered_query($this->link, $sql) or die(sqlite_error_string(sqlite_last_error($this->link)));

        $this->querynum++;
        $this->querylist[] = $sql;
        $this->stop_timer();

        return $result;
    }

    function fetch_tables($dbname){
        $q = $this->query("SHOW TABLES FROM $dbname");
        while($table = $this->fetch_array($q, SQLITE_NUM)){
            $array[] = $table[0];
        }
        return $array;
    }
    
    function field_name($query, $field) {
        return sqlite_field_name($query, $field);
    }

    function result($query, $row=0, $field=NULL) {
        // row is not used at the moment, will add later
        sqlite_seek($query, $row);
        return sqlite_column($query, $field)
    }

    function num_rows($query) {
        return sqlite_num_rows($query);
    }
    
    function num_fields($query) {
        return sqlite_num_fields($query);
    }

    function insert_id() {
        return sqlite_last_insert_rowid($this->link);
    }

    function fetch_row($query) {
        return sqlite_current($query);
    }

    function time($time=NULL){
        if($time === NULL){
            $time = time();
        }
        return "LPAD('".$time."', '15', '0')";
    }

    function start_timer() {
        $mtime = explode(" ", microtime());
        $this->timer = $mtime[1] + $mtime[0];

        return true;
    }

    function stop_timer() {
        $mtime = explode(" ", microtime());
        $endtime = $mtime[1] + $mtime[0];

        $this->duration += ($endtime - $this->timer);
        $this->timer = 0;
        return true;
    }

    function close() {
        sqlite_close($this->link);
        $this->link = '';
    }
}
define('SQL_NUM', SQLITE_NUM);
define('SQL_BOTH', SQLITE_BOTH);
define('SQL_ASSOC', SQLITE_ASSOC);
?>