<?php
/* $Id: mysql4.php,v 1.2 2004/04/18 11:39:19 Tularis Exp $ */
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
    var $duration   = 0;
    var $timer  = 0;

    function connect($dbhost="localhost", $dbuser, $dbpw, $dbname, $pconnect=0, $force_db=false) {
        $this->link = mysqli_init();
        $this->link->real_connect($dbhost, $dbuser, $dbpw, $dbname, null, null, MYSQLI_CLIENT_COMPRESS) or ($die=true);
        $this->link->autocommit(true);

        if($die){
            $num = $this->link->errno;
            $msg = $this->link->error;

            echo '<h3>Database connection error!!!</h3>';

            echo 'A connection to the Database could not be established.<br />';
            echo 'Please check your username, password, database name and host.<br />';
            echo 'Also make sure <i>config.php</i> is rightly configured!<br /><br />';

            echo 'When connecting, the database returned:<br />';
            echo '<i><b>Error '.$num.': </b>'.$msg.'</i>';
            exit();
        }
        unset($GLOBALS['dbhost'], $GLOBALS['dbuser'], $GLOBALS['dbpw']);
        return true;
    }

    function error() {
        if(isset($this->link->error) && strlen($this->link->error) > 0) {
            return $this->link->error;
        } else {
            return false;
        }
    }

    function free_result(&$query) {
        return $query->free();
    }

    function fetch_array(&$result, $type=MYSQLI_ASSOC) {
        $return = $result->fetch_array($type);
        if($return === NULL) {
            return false;
        } else {
            return $return;
        }
    }
    
    function field_name(&$query, $field) {
        $f = $query->fetch_fields();
        return $f[$field]->name;
    }

    function query($sql) {
        $this->start_timer();

        $this->link->real_query($sql) or die($this->link->error);

        $this->querynum++;
        $this->querylist[] = $sql;
        $this->stop_timer();

        return $this->link->store_result();
    }

    function unbuffered_query($sql) {
        return $this->query($sql);
    }

    function fetch_tables($dbname){
        $q = $this->query("SHOW TABLES FROM $dbname");
        while($table = $this->fetch_array($q, MYSQLI_NUM)){
            $array[] = $table[0];
        }
        return $array;
    }

    function result(&$query, $row, $field=0) {
        // row is not used at the moment, will add later
        $query->data_seek($row);
        $rows = $query->fetch_row();
        return $rows[$field];
    }

    function num_rows(&$query) {
        return $query->num_rows;
    }
    
    function num_fields(&$query) {
        return $query->field_count;
    }

    function insert_id() {
        return $this->link->insert_id;
    }

    function fetch_row(&$query) {
        return $query->fetch_row();
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
        $this->link->close();
        $this->link = '';
    }
}

define('SQL_NUM', MYSQLI_NUM);
define('SQL_BOTH', MYSQLI_BOTH);
define('SQL_ASSOC', MYSQLI_ASSOC);
?>