<?php

include_once('config.php');
include_once('utils.php');

class LogDB{

    private static $_instance;
    private $_dbh;

    function __construct() {
        $dsn = "sqlite:".__LOG_DATABASE__;

        $this->_dbh = new PDO($dsn, __DATABASE_USER__, __DATABASE_PASSWORD__, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $this->_dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    public static function getConnection()
    {
        if (self::$_instance === null) {
            self::$_instance = new LogDB();
        }
        return self::$_instance;
    }
    public function prepare($sql) {
        return $this->_dbh->prepare($sql);
    }
    public function lastInsertId($col="") {
        return $this->_dbh->lastInsertId($col);
    }

    public function __clone() {
        return false;
    }
    public function __wakeup() {
        return false;
    }
}

?>
