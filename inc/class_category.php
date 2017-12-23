<?php

include_once('config.php');
include_once('utils.php');

include_once('class_db_main.php');
class Category{

    private $columns;
    public function init() {

        $this->columns = array("ID", "ENABLED", "TITLE", "ICON_NORMAL", "ICON_PRESS", "WEIGHT");

        $sth = MainDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS CATEGORY(ID INTEGER PRIMARY KEY, ENABLED INTEGER, TITLE TEXT,  ICON_NORMAL TEXT, ICON_PRESS TEXT, WEIGHT INTEGER)');
        $sth->execute();


    }

    public function deleteCategory($id="") {
        $sql = "DELETE FROM CATEGORY ";
        if ($id != '') $sql .= "WHERE ID = '$id'";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function addColumn($name, $type) {
        $sql = "ALTER TABLE CATEGORY ADD COLUMN $name $type ";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function create($data) {
        $data = filterInput($this->columns, $data);
        $sth = MainDB::getConnection()->prepare('INSERT INTO CATEGORY (ENABLED, TITLE,ICON_NORMAL, ICON_PRESS, WEIGHT) VALUES (:ENABLED, :TITLE, :ICON_NORMAL, :ICON_PRESS, :WEIGHT)');
        $sth->execute((array) $data);
    }

    public function updateCategory($data) {
        $data = filterInput($this->columns, $data);
        $sth = MainDB::getConnection()->prepare('INSERT OR REPLACE INTO CATEGORY ( ID, ENABLED, TITLE, ICON_NORMAL, ICON_PRESS, WEIGHT) VALUES (:ID, :ENABLED, :TITLE, :ICON_NORMAL, :ICON_PRESS, :WEIGHT)');
        $sth->execute((array) $data);
    }

    public function updateField($id, $field, $content) {
        $sth = MainDB::getConnection()->prepare("UPDATE CATEGORY SET $field='".$content."' WHERE ID=".$id);
        $sth->execute();
    }

    public function loadVideoArray($category_id) {
        $result = array();

        $sql = "SELECT * FROM VIDEO WHERE CATEGORY = $category_id AND STATUS=1 ORDER BY STICKY ASC";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $item) {
            array_push($result, $item['SERIAL_NUMBER']);
        }
        return $result;
    }

    public function updateVideoList($category_id, $video_id_array) {
        $w = 0;
        foreach ($video_id_array as $v) {

            if (strlen($v)==0) continue;

            $sth = MainDB::getConnection()->prepare("UPDATE VIDEO SET STICKY='".$w."' WHERE SERIAL_NUMBER=".$v);
            $sth->execute();
            $w++;
        }
    }

    public function load($id=-1, $range_modifier='', $order_modifier='') {

        $sql = "SELECT * FROM CATEGORY";
        if ($id!=-1) $sql .= " WHERE ID = $id ";
	$sql .= " $order_modifier $range_modifier ";

        $sth = MainDB::getConnection()->prepare($sql);

        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function loadAllPublishedCategory(){
        $sql = "SELECT * FROM CATEGORY WHERE ENABLED=1 ORDER BY WEIGHT DESC";

        $sth = MainDB::getConnection()->prepare($sql);

        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCache() {

        $rows = loadAllPublishedCategory();
        $a = array();
        foreach ($rows as $item) {
            array_push($a, $rows['ID']);
        }

        $cache = new Cache();
        $cache->write("category", join(',', $a));

    }

    public function getHTML() {
        $range_modifier = "";
        $order_modifier = "ORDER BY WEIGHT DESC";

        $rows = $this->load(-1, $range_modifier, $order_modifier);

        $link_html = "";

        foreach ($rows as $item) {
            if ($item['ENABLED']==1) {
                $link_html .= '<img src="'.$item['ICON'].'" onclick="window.open(\''.$item['URL']  . '\', \'_blank\');" style="cursor:pointer; padding-left:20px; padding-top:10px; padding-bottom: 10px; " />';
            }
        }
        return $link_html;
    }
}

?>
