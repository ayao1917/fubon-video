<?php

include_once('config.php');
include_once('utils.php');
include_once('class_db_main.php');
include_once('class_cache.php');

class NewOrderName{

    private $columns;
    public function init() {

        $this->columns = array('AREA', 'NAME');
        $query = 'CREATE TABLE IF NOT EXISTS NEW_ORDER_NAME(AREA INTEGER PRIMARY KEY, NAME TEXT)';        
        $sth = MainDB::getConnection()->prepare($query);
        $sth->execute();

        $data = array();

        if ($this->load(1)=='') { 
            $data['AREA']=1;
            $data['NAME']='強打主打星';
            $this->create($data);
        } 
        if ($this->load(2)==null) { 
            $data['AREA']=2;
            $data['NAME']='發燒新片';
            $this->create($data);
        } 
    }

    public function create($data) {
        if ($data==null) return;
        $data = filterInput($data);
        $sql = 'INSERT OR REPLACE INTO NEW_ORDER_NAME (AREA, NAME) VALUES (:AREA, :NAME)';
        $sth = MainDB::getConnection()->prepare($sql);

        $sth->execute((array) $data);
    }
    public function load($id) {
        $sql = "SELECT NAME FROM NEW_ORDER_NAME ";
        if ($id != -1) $sql .= "WHERE AREA = $id";

        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        return $sth->fetchColumn();
    }
    public function update($id, $name) {
        $sql = "UPDATE NEW_ORDER_NAME SET NAME='$name' WHERE AREA=$id";

        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }
    public function updateCache() {

        $cache = new Cache();
        $cache->write("home_area1_name", $this->getHTML(1));
        $cache->write("home_area2_name", $this->getHTML(2));

    }

    public function getHTML($area) {
        return $this->load($area);
    }
}

?>
