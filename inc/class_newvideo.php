<?php

include_once('config.php');
include_once('utils.php');

include_once('class_db_main.php');
class NewVideo{
    private $columns;
    public function init() {
        $columns = array('SERIAL_NUMBER', 'RANKING', 'AREA');
        $query = 'CREATE TABLE IF NOT EXISTS NEW_ORDER(SERIAL_NUMBER TEXT, RANKING INTEGER, AREA INTEGER)';        
        $sth = MainDB::getConnection()->prepare($query);
        $sth->execute();
    }

    public function loadAll() {
        $sql = "SELECT * FROM NEW_ORDER ";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	return $rows;
    }

    public function deleteVideo($id=-1) {
        $sql = "DELETE FROM NEW_ORDER ";
        if ($id != -1) $sql .= "WHERE SERIAL_NUMBER = '$id'";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function saveOrder($data) {

        $data = filterInput($this->columns, $data);
        $sth = MainDB::getConnection()->prepare('INSERT OR REPLACE INTO NEW_ORDER ( SERIAL_NUMBER, RANKING, AREA) VALUES (:SERIAL_NUMBER, :RANKING, :AREA)');
        $sth->execute((array) $data);
    }
    public function load($area) {

        $sth = MainDB::getConnection()->prepare("SELECT * FROM NEW_ORDER WHERE AREA=$area");
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function saveAll($data0, $data1, $data2) {
          $this->deleteVideo();
          $rank=0;
          foreach ($data0 as $item) {
              if ($item == '') continue;
	      $data["SERIAL_NUMBER"] = $item;
	      $data["RANKING"] = $rank++;
	      $data["AREA"] = 0;
              $this->saveOrder($data);
          }
          foreach ($data1 as $item) {
              if ($item == '') continue;
	      $data["SERIAL_NUMBER"] = $item;
	      $data["RANKING"] = $rank++;
	      $data["AREA"] = 1;
              $this->saveOrder($data);
          }
          foreach ($data2 as $item) {
              if ($item == '') continue;
	      $data["SERIAL_NUMBER"] = $item;
	      $data["RANKING"] = $rank++;
	      $data["AREA"] = 2;
              $this->saveOrder($data);
          }
    }

}

?>
