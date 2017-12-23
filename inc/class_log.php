<?php

include_once('config.php');
include_once('utils.php');
include_once('class_db_log.php');

class Logs{

    public function init() {
        $sth = LogDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS MANAGEMENT(DATE TEXT, TIME TEXT, USER TEXT, ACTION TEXT, TYPE TEXT, TARGET TEXT, EXTRA TEXT)');
        $sth->execute();
/*

        $sth = LogDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS READING(DATE TEXT, TIME TEXT, USER TEXT, TYPE TEXT, ACTION TEXT, BOOK INTEGER, DURATION INTEGER, LAST_PAGE INTEGER)');
        $sth->execute();
        $sth = LogDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS MANAGEMENT_IT(DATE TEXT, TIME TEXT, USER TEXT, ACTION TEXT, TYPE TEXT, TARGET TEXT, EXTRA TEXT)');
        $sth->execute();
        $sth = LogDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS LOGIN(DATE TEXT, TIME TEXT, USER TEXT, TYPE TEXT, IP TEXT, UA TEXT)');
        $sth->execute();
        $sth = LogDB::getConnection()->prepare('CREATE TABLE IF NOT EXISTS CACHE(DATE TEXT, TIME TEXT, QUERY TEXT, RESULT TEXT)');
        $sth->execute();
*/

/*
        $sth = LogDB::getConnection()->prepare('CREATE INDEX DATE_INDEX ON READING(DATE ASC)');
        $sth->execute();
        $sth = LogDB::getConnection()->prepare('CREATE INDEX TIME_INDEX ON READING(TIME ASC)');
        $sth->execute();
        $sth = LogDB::getConnection()->prepare('CREATE INDEX USER_INDEX ON READING(USER ASC)');
        $sth->execute();
        $sth = LogDB::getConnection()->prepare('CREATE INDEX BOOK_INDEX ON READING(BOOK ASC)');
        $sth->execute();
*/

/*
        $bb = LogDB::getConnection()->query("pragma database_list")->fetchAll(PDO::FETCH_ASSOC);       
        if (count($bb)<=1) {


            $aaa = "attach '".__DATABASE__."' as m";
            $sth = LogDB::getConnection()->prepare($aaa);
            $sth->execute();

            $aaa = "attach '".__PDATA_PATH__."/u.new' as ag1";
            $sth = LogDB::getConnection()->prepare($aaa);
            $sth->execute();

            $aaa = "attach '".__PDATA_PATH__."/u.new' as ag2";
            $sth = LogDB::getConnection()->prepare($aaa);
            $sth->execute();

            $aaa = "attach '".__PDATA_PATH__."/u.new' as ag3";
            $sth = LogDB::getConnection()->prepare($aaa);
            $sth->execute();
        }
*/

    }

    public function insert($data) {

        $data['USER'] = strtoupper($data['USER']);

        $sth = LogDB::getConnection()->prepare('INSERT INTO READING (DATE, TIME, USER, TYPE, ACTION, BOOK, DURATION, LAST_PAGE) VALUES (:DATE, :TIME, :USER, :TYPE, :ACTION, :BOOK, :DURATION, :LAST_PAGE)');
        $sth->execute((array) $data);
    }
    public function writeCache($data) {
return;
        $sth = LogDB::getConnection()->prepare('INSERT INTO CACHE (DATE, TIME, QUERY, RESULT) VALUES (:DATE, :TIME, :QUERY, :RESULT)');
        $sth->execute((array) $data);
    }
    public function checkCache($query) {
        $data["QUERY"] = $query;
        $sth = LogDB::getConnection()->prepare("SELECT * FROM CACHE WHERE QUERY= :QUERY");
        $sth->execute($data);
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows)>0) return $rows[0];
        return null;
    }

    public function insertLoginLog($data) {
        $data['USER'] = strtoupper($data['USER']);

        //$query = "SELECT COUNT(*) FROM LOGIN WHERE DATE='$data["DATE"]' and TIME='$data["TIME"]' and USER='$data["USER"]'";
        $query = 'SELECT COUNT(*) as C FROM LOGIN WHERE DATE=:DATE AND TIME=:TIME AND USER=:USER and TYPE=:TYPE and IP=:IP and UA=:UA';

        $sth = LogDB::getConnection()->prepare($query);
        $sth->execute($data);
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($rows[0]["C"]==0) {

            $sth = LogDB::getConnection()->prepare('INSERT INTO LOGIN (DATE, TIME, USER, TYPE, IP, UA) VALUES (:DATE, :TIME, :USER, :TYPE, :IP, :UA)');
            $sth->execute($data);
        }
    }
    public function resetUserLog() {
        $sth = LogDB::getConnection()->prepare('DELETE FROM READING');
        $sth->execute();
    }
    public function resetManagementLog() {
        $sth = LogDB::getConnection()->prepare('DELETE FROM MANAGEMENT');
        $sth->execute();
    }
    public function insertManagement($data) {
        $data['USER'] = strtoupper($data['USER']);

        try { 
              $sth = LogDB::getConnection()->prepare('INSERT INTO MANAGEMENT (DATE, TIME, USER, ACTION, TYPE, TARGET, EXTRA) VALUES (:DATE, :TIME, :USER, :ACTION, :TYPE, :TARGET, :EXTRA)');
              $sth->execute((array) $data);
              return LogDB::getConnection()->lastInsertId(); 
          } catch(PDOExecption $e) { 
              return $e->getMessage(); 
          } 

    }

    // from Management to Management_IT
    public function moveLog($id) {
        try { 
              $sth = LogDB::getConnection()->prepare("SELECT * FROM MANAGEMENT WHERE USER='$id'");
              $sth->execute();
              $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

              foreach ($rows as $row) {
                  $this->insertManagement_IT($row);
              }
              $sth = LogDB::getConnection()->prepare("DELETE FROM MANAGEMENT WHERE USER='$id'");
              $sth->execute();
              return LogDB::getConnection()->lastInsertId(); 
          } catch(PDOExecption $e) { 
              return $e->getMessage(); 
          } 
    }

    public function insertManagement_IT($data) {
        $data['USER'] = strtoupper($data['USER']);

        try { 
              $sth = LogDB::getConnection()->prepare('INSERT INTO MANAGEMENT_IT (DATE, TIME, USER, ACTION, TYPE, TARGET, EXTRA) VALUES (:DATE, :TIME, :USER, :ACTION, :TYPE, :TARGET, :EXTRA)');
              $sth->execute((array) $data);
              return LogDB::getConnection()->lastInsertId(); 
          } catch(PDOExecption $e) { 
              return $e->getMessage(); 
          } 

    }

    public function import($path) {

        $log_file    = file_get_contents($path);
        $rows        = explode("\n", $log_file);

        LogDB::getConnection()->beginTransaction();

        foreach($rows as $row => $item) {
            //get row data
            $row_data = explode(' ', $item);

            if (sizeof($row_data)<7) continue;

            $data['DATE']       = $row_data[0];
            $data['TIME']       = $row_data[1];
            $data['USER']       = $row_data[2];
            $data['TYPE']       = $row_data[3];
            $data['ACTION']     = $row_data[4];
            $data['BOOK']       = $row_data[5];
            list($data['DURATION'],  $data['LAST_PAGE']) = explode('|', $row_data[6]);

            $data['USER'] = strtoupper($data['USER']);

            $this->insert($data);
        }

        LogDB::getConnection()->commit();
    }
    public function importLoginLog($path) {

        $log_file    = file_get_contents($path);
        $rows        = explode("\n", $log_file);

        LogDB::getConnection()->beginTransaction();

        foreach($rows as $row => $item) {
            //get row data
            $row_data = explode(' ', $item);

            $nItems = sizeof($row_data);
            if ($nItems<7) continue;

            $data['DATE']       = $row_data[0];
            $data['TIME']       = $row_data[1];
            $data['USER']       = $row_data[2];
            $data['TYPE']       = $row_data[3];
            $data['IP']     = $row_data[4];
            $data['UA']     = $row_data[5];
           
            for ($i=6; $i<$nItems; $i++) {
                $data['UA']    .= " ".$row_data[$i];
            }

            $data['USER'] = strtoupper($data['USER']);

            $this->insertLoginLog($data);
        }

        LogDB::getConnection()->commit();
    }


    public function search($query) {

        $t = getDate3() ." ". getTime3() . " ";
        $start = microtime(true);

        $in_cache = "search";

//        $cacheData = $this->checkCache($query);
$cacheData=null;

        if ($cacheData !=null) {
            $in_cache = "cache";
            $rows = json_decode($cacheData['RESULT'], true); 
        } else {
            $sth = LogDB::getConnection()->prepare($query);
            $sth->execute();
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

            $data['DATE'] = getDate3();
            $data['TIME'] = getTime3();
            $data['QUERY'] = $query;
            $data['RESULT'] = json_encode($rows);

            $this->writeCache($data);
        }

        $finish = microtime(true);

        $elapse = round($finish-$start, 3);


//        file_put_contents("/tmp/search.log", $t ." " . $in_cache ." " . $elapse . " " .$query."\n", FILE_APPEND) ;


        return $rows;
    }
    public function loadManagementTypeList() {

        $sql = "select distinct type as type from MANAGEMENT";

        $sth = LogDB::getConnection()->prepare($sql);

        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function getLastLoginCountWithinTime($minute=10) {

        $start = time() - ( $minute * 60);
        $d = getDate2($start);
        $t = getTime3($start);

        $result = $this->search("select count(date) as c from LOGIN WHERE date>='$d' and time >='$t'");
        return $result[0]['c'];
    }
}

?>
