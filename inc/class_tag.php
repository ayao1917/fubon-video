<?php

include_once('config.php');
include_once('utils.php');

include_once('class_db_main.php');
class Tag{

    private $columns;
    public function init() {

        $this->columns = array("ID", "ENABLED", "TITLE", "ICON_NORMAL", "ICON_PRESS", "WEIGHT");

        $sth = MainDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS TAG(ID INTEGER PRIMARY KEY, ENABLED INTEGER, TITLE TEXT,  ICON_NORMAL TEXT, ICON_PRESS TEXT, WEIGHT INTEGER)');
        $sth->execute();

        $sth = MainDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS TAG_VIDEO(TAG_ID INTEGER, VIDEO_ID INTEGER, WEIGHT INTEGER)');
        $sth->execute();

    }

    public function deleteTag($id="") {
        $sql = "DELETE FROM TAG ";
        if ($id != '') $sql .= "WHERE ID = '$id'";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function addColumn($name, $type) {
        $sql = "ALTER TABLE TAG ADD COLUMN $name $type ";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function create($data) {
        $data = filterInput($this->columns, $data);
        $sth = MainDB::getConnection()->prepare('INSERT INTO TAG (ENABLED, TITLE,ICON_NORMAL, ICON_PRESS, WEIGHT) VALUES (:ENABLED, :TITLE, :ICON_NORMAL, :ICON_PRESS, :WEIGHT)');
        $sth->execute((array) $data);
    }

    public function updateTag($data) {
        $new_data = array();
        if (isset($data['ID'])) {
            $sth = MainDB::getConnection()->prepare('SELECT * FROM TAG WHERE ID='.$data['ID']);
            $sth->execute();
            $new_data = $sth->fetch(PDO::FETCH_ASSOC);
            foreach ($new_data as $k=>$v) {
                if (isset($data[$k])) $new_data[$k] = $data[$k];
            }
        }

//        $data = filterInput($this->columns, $data);
        $sth = MainDB::getConnection()->prepare('INSERT OR REPLACE INTO TAG ( ID, ENABLED, TITLE, ICON_NORMAL, ICON_PRESS, WEIGHT) VALUES (:ID, :ENABLED, :TITLE, :ICON_NORMAL, :ICON_PRESS, :WEIGHT)');
        $sth->execute((array) $new_data);
    }
    public function updateField($id, $field, $content) {
        $sth = MainDB::getConnection()->prepare("UPDATE TAG SET $field='".$content."' WHERE ID=".$id);
        $sth->execute();
    }
    public function loadVideoArray($tag_id) {
        $result = array();

        //$sql = "SELECT * FROM TAG_VIDEO WHERE TAG_ID = $tag_id ORDER BY WEIGHT DESC";
        $sql = "SELECT * FROM TAG_VIDEO WHERE TAG_ID = $tag_id ";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $item) {
            array_push($result, $item['VIDEO_ID']);
        }
        return $result;
    }
    public function loadPublishedVideo($tag_id, $order_modifier="") {
        $result = array();

        //$sql = "SELECT * FROM TAG_VIDEO WHERE TAG_ID = $tag_id ORDER BY WEIGHT DESC";
        $sql = "SELECT * FROM TAG_VIDEO JOIN VIDEO ON TAG_VIDEO.VIDEO_ID=VIDEO.SERIAL_NUMBER WHERE TAG_ID = $tag_id AND VIDEO.STATUS=1 $order_modifier";

        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function updateVideoList($tag_id, $video_id_array) {
        $sql = "DELETE FROM TAG_VIDEO WHERE TAG_ID = ". $tag_id;
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();

debug("Tag-updateVideoList");

debug($tag_id);
debug($video_id_array);
        
        $w = 0;
        foreach ($video_id_array as $v) {

            if (strlen($v)==0) continue;

            $data["TAG_ID"] = $tag_id;
            $data["VIDEO_ID"] = $v;
            $data["WEIGHT"] = $w;
           
            $sth = MainDB::getConnection()->prepare('INSERT INTO TAG_VIDEO ( TAG_ID, VIDEO_ID, WEIGHT) VALUES (:TAG_ID, :VIDEO_ID, :WEIGHT)');
            $sth->execute((array) $data);
            $w++;
        }
    }

    public function load($id=-1, $range_modifier='', $order_modifier='') {

        $sql = "SELECT * FROM TAG";
        if ($id!=-1) $sql .= " WHERE ID = $id ";
	$sql .= " $order_modifier $range_modifier ";

        $sth = MainDB::getConnection()->prepare($sql);

        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function loadAllPublishedTag(){
        $sql = "SELECT * FROM TAG WHERE ENABLED=1 ORDER BY WEIGHT DESC";

        $sth = MainDB::getConnection()->prepare($sql);

        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCache() {

        $rows = loadAllPublishedTag();
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
