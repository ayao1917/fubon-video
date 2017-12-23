<?php

include_once('config.php');
include_once('utils.php');
include_once('class_db_main.php');
include_once('class_cache.php');

class Banner{

    private $columns;
    public function init() {

        $this->columns= array('ID', 'ENABLED', 'LINK', 'BANNER', 'WEIGHT');
        $sth = MainDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS BANNER(ID INTEGER PRIMARY KEY, ENABLED INTEGER, LINK TEXT, BANNER TEXT, WEIGHT INTEGER)');
        $sth->execute();

    }

    public function deleteBanner($id="") {
        $sql = "DELETE FROM BANNER ";
        if ($id != '') $sql .= "WHERE ID = '$id'";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function addColumn($name, $type) {
        $sql = "ALTER TABLE BANNER ADD COLUMN $name $type ";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function create($data) {
        $data = filterInput($this->columns, $data);
        $data["BANNER"]="";
        $sth = MainDB::getConnection()->prepare('INSERT INTO BANNER (ENABLED, LINK, BANNER, WEIGHT) VALUES (:ENABLED, :LINK, :BANNER, :WEIGHT)');
        $sth->execute((array) $data);
        return MainDB::getConnection()->lastInsertId('ID'); 
    }

    public function updateBanner($data) {
        $data = filterInput($this->columns, $data);
        $sth = MainDB::getConnection()->prepare('INSERT OR REPLACE INTO BANNER ( ID, ENABLED, LINK, BANNER, WEIGHT) VALUES (:ID, :ENABLED, :LINK, :BANNER, :WEIGHT)');
        $sth->execute((array) $data);
    }
/*
    public function update($data) {
        $sth = MainDB::getConnection()->prepare("UPDATE TOPIC SET TOPIC='".$data->TOPIC ."', ENABLED=".$data->ENABLED.", COVER='".$data->COVER ."' WHERE ID=".$data->ID);
        $sth->execute((array) $data);
    }
*/
    public function updateField($id, $field, $content) {
        $sth = MainDB::getConnection()->prepare("UPDATE BANNER SET $field='".$content."' WHERE ID=".$id);
        $sth->execute();
    }
    public function load($id=-1, $range_modifier='', $order_modifier=' ORDER BY WEIGHT DESC') {

        $sql = "SELECT * FROM BANNER";
        if ($id!=-1) $sql .= " WHERE ID = $id ";
	$sql .= " $order_modifier $range_modifier ";

        $sth = MainDB::getConnection()->prepare($sql);

        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function updateCache() {

        $cache = new Cache();
        $cache->write("banner", $this->getHTML());

    }

    public function getHTML() {
        $range_modifier = "";
        $order_modifier = "ORDER BY WEIGHT DESC";

        $banner_html = '<div id="kv">';

        $rows = $this->load(-1, $range_modifier, $order_modifier);
        $count=0;
        $banner_data = '';
        foreach ($rows as $item) {
            if ($item['ENABLED']==1) {
                $class_name =  ($count==0)?"show first":"show";
                $linkstyle="";
                $link = $item['LINK'];
                if ($link==null) $link="";
                if ($link!='') {
                    if (startsWith($link, "javascript:")) {
                        $link="onClick='$link'";
                    } else {
                        $link="onClick='window.open(\"$link\", \"_blank\");'";
                    }
                    $linkstyle="cursor:pointer;";
                }
                $banner_data .= '<div class="'.$class_name.'" '. $link .' style="background:url('.$item['BANNER'] .') no-repeat scroll 0 0 transparent;'.$linkstyle .'" ></div>';
                $count++;
            }
        }

        if ($count==1) $banner_data .= $banner_data;

        $banner_html .= $banner_data;
        $banner_html .= '</div>';

        return $banner_html;
    }
}

?>
