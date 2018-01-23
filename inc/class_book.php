<?php

include_once('config.php');
include_once('utils.php');
include_once('class_db_main.php');
include_once('class_cache.php');

class Book{

    private $columns;
    public function init() {

        $this->columns = array('ID', 'TITLE', 'TYPE');

        $sth = MainDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS BOOK(ID TEXT PRIMARY KEY, TYPE INTEGER, TITLE TEXT)');
        $sth->execute();

        $sth = MainDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS VIDEO_BOOK(ID TEXT PRIMARY KEY, TYPE INTEGER, TITLE TEXT, WORD_BOOK TEXT, GUIDE_BOOK TEXT, TECH_BOOK TEXT)');
        $sth->execute();
    }

    public function loadVideoBook($id=-1, $range_modifier='', $order_modifier='') {
        $sql = "SELECT * FROM VIDEO_BOOK ";
        if ($id != -1) $sql .= "WHERE ID = " . $id ;

        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows)>0) return $rows[0];
        return null;
    }

    public function loadBook($id=-1, $range_modifier='', $order_modifier='') {
        $sql = "SELECT * FROM BOOK ";
        if ($id != -1) $sql .= "WHERE ID = " . $id ;

        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows)>0) return $rows[0];
        return null;
    }

    public function deleteBook($id="") {
        $sql = "DELETE FROM Book ";
        if ($id != '') $sql .= "WHERE ID = '$id'";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function addColumn($name, $type) {
        $sql = "ALTER TABLE BOOK ADD COLUMN $name $type ";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function create($data) {
        $data = filterInput($this->columns, $data);
        $sth = MainDB::getConnection()->prepare('INSERT INTO BOOK (ID, TITLE) VALUES (:ID, :TITLE)');
        $sth->execute((array) $data);
    }

    public function updateBook($data) {
        $new_data = array();
        if (isset($data['ID'])) {
            $sth = MainDB::getConnection()->prepare('SELECT * FROM BOOK WHERE ID='.$data['ID']);
            $sth->execute();
            $row = $sth->fetch();
            $new_data['ID'] = isset($data['ID']) ? $data['ID'] : $row['ID'];
            $new_data['TITLE'] = isset($data['TITLE']) ? $data['TITLE'] : $row['TITLE'];
            $new_data['TYPE'] = isset($data['TYPE']) ? $data['TYPE'] : $row['TYPE'];
        }

        $sth = MainDB::getConnection()->prepare('INSERT OR REPLACE INTO BOOK (ID, TITLE, TYPE) VALUES (:ID, :TITLE, :TYPE)');
        $sth->execute((array) $new_data);
    }

    public function updateVideoBook($data) {
        $new_data = array();
        if (isset($data['ID'])) {
            $sth = MainDB::getConnection()->prepare('SELECT * FROM VIDEO_BOOK WHERE ID='.$data['ID']);
            $sth->execute();
            $row = $sth->fetch();
            $new_data['ID'] = isset($data['ID']) ? $data['ID'] : $row['ID'];
            $new_data['TITLE'] = isset($data['TITLE']) ? $data['TITLE'] : $row['TITLE'];
            $new_data['TYPE'] = isset($data['TYPE']) ? $data['TYPE'] : $row['TYPE'];
            $new_data['WORD_BOOK'] = isset($data['WORD_BOOK']) ? $data['WORD_BOOK'] : $row['WORD_BOOK'];
            $new_data['GUIDE_BOOK'] = isset($data['GUIDE_BOOK']) ? $data['GUIDE_BOOK'] : $row['GUIDE_BOOK'];
            $new_data['TECH_BOOK'] = isset($data['TECH_BOOK']) ? $data['TECH_BOOK'] : $row['TECH_BOOK'];
            $new_data['PRES_BOOK'] = isset($data['PRES_BOOK']) ? $data['PRES_BOOK'] : $row['PRES_BOOK'];
            $new_data['DOWN_BOOK'] = isset($data['DOWN_BOOK']) ? $data['DOWN_BOOK'] : $row['DOWN_BOOK'];
        }

        $sth = MainDB::getConnection()->prepare('INSERT OR REPLACE INTO VIDEO_BOOK (ID, TITLE, TYPE, WORD_BOOK, GUIDE_BOOK, TECH_BOOK, PRES_BOOK, DOWN_BOOK) VALUES (:ID, :TITLE, :TYPE, :WORD_BOOK, :GUIDE_BOOK, :TECH_BOOK, :PRES_BOOK, :DOWN_BOOK)');
        $sth->execute((array) $new_data);
    }

    public function updateBookField($id, $field, $content) {
        $sth = MainDB::getConnection()->prepare("UPDATE BOOK SET $field='".$content."' WHERE ID=".$id);
        $sth->execute();
    }

    public function updateVideoBookField($id, $field, $content) {
        $sth = MainDB::getConnection()->prepare("UPDATE VIDEO_BOOK SET $field='".$content."' WHERE ID=".$id);
        $sth->execute();
    }

    public function loadBookArray($type) {
        $sql = "SELECT * FROM VIDEO_BOOK WHERE TYPE = $type ";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function getVideoBooks() {
        $result = array();
        $sql = "SELECT * FROM VIDEO_BOOK WHERE TYPE = 1";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $item) {
            $result[$item['ID']] = array($item['TITLE'], $item['WORD_BOOK'], $item['GUIDE_BOOK']);
        }
        return $result;
    }

    public function getVideoBooks2() {
        $result = array();
        $sql = "SELECT * FROM VIDEO_BOOK WHERE TYPE = 3";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $item) {
            $result[$item['ID']] = array($item['TITLE'], $item['PRES_BOOK'], $item['DOWN_BOOK']);
        }
        return $result;
    }

    public function getBookNames() {
        $result = array();
        $sql = "SELECT * FROM BOOK";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $item) {
            $result[$item['ID']] = $item['TITLE'];
        }
        return $result;
    }

    public function getBooksByType($type) {
        $result = array();
        $sql = "SELECT VIDEO_BOOK.ID AS ID, BOOK.TITLE AS TITLE, BOOK.ID AS BID FROM VIDEO_BOOK LEFT JOIN BOOK ON VIDEO_BOOK.TECH_BOOK=BOOK.ID WHERE VIDEO_BOOK.TYPE = ".$type;

        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $item) {
            $result[$item['ID']] = array($item['TITLE'], $item['BID']);
        }
        return $result;
    }

    public function getNewBookId() {
        $t = getDate1();
        $c=0;
        $sn="";

        do {
            $c++;
            $sn = sprintf("%s%02d", $t, $c);
        } while ($this->getVideoInfo($sn, "ID")==$sn);
        return $sn;
    }

    public function getBookInfo($id, $field) {
        $sql = "SELECT $field FROM BOOK WHERE ID = '$id'";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        return $sth->fetchColumn();
    }
}