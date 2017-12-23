<?php

include_once('config.php');
include_once('utils.php');
include_once('class_newvideo.php');
include_once('class_newordername.php');
include_once('class_cache.php');

include_once('class_db_main.php');
class Video{

    private $columns;
    public function init() {
       
        $this->columns = array('SERIAL_NUMBER', 'TITLE', 'CATEGORY', 'ORDER_NEW', 'STICKY', 'AUTHOR', 'DETAIL', 'EDITOR_ID', 'STATUS', 'PUBLISH_DATE', 'OFF_DATE', 'VIDEO_LENGTH', 'VIDEOS');
        $query = 'CREATE TABLE IF NOT EXISTS VIDEO(SERIAL_NUMBER TEXT PRIMARY KEY, TITLE TEXT, CATEGORY INTEGER, ORDER_NEW INTEGER, STICKY INTEGER, AUTHOR TEXT, DETAIL TEXT, EDITOR_ID TEXT, STATUS INTEGER, PUBLISH_DATE TEXT, OFF_DATE TEXT, VIDEO_LENGTH TEXT, VIDEOS INTEGER)';
        $sth = MainDB::getConnection()->prepare($query);
        $sth->execute();

        $neworder = new NewVideo();
        $neworder->init();

        $newordername = new NewOrderName();
        $newordername->init();
    }

    public function loadVideo($id=-1, $range_modifier='', $order_modifier='') {
        $sql = "SELECT * FROM VIDEO ";
        if ($id != -1) $sql .= "WHERE SERIAL_NUMBER = " . $id ;

        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	    if (count($rows)>0) return $rows[0];
	    return null;
    }
    public function getTagName($id=-1) {
        $sql = "SELECT TAG.TITLE AS TITLE FROM TAG_VIDEO JOIN TAG ON TAG.ID=TAG_VIDEO.TAG_ID WHERE TAG_VIDEO.VIDEO_ID = $id"; 
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows[0])>0) {
            $result = array();
            foreach ($rows as $item) {
                array_push($result, $item["TITLE"]);
            }
            return join(",", $result);
        }

        return null;
    }

    public function loadAllVideo($id=-1, $range_modifier='', $order_modifier='') {
        $sql = "SELECT * FROM VIDEO ";
        if ($id != -1) $sql .= "WHERE SERIAL_NUMBER = " . $id ;

        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function addColumn($name, $type) {
        $sql = "ALTER TABLE VIDEO ADD COLUMN $name $type ";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function loadAllPublishedVideo($range_modifier='', $order_modifier='') {
        $sql = "SELECT * FROM VIDEO WHERE STATUS=1 ";
        $sql .= " $order_modifier $range_modifier ";

        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	    return $rows;
    }


    public function loadNewVideo($area) {

        $sql = "SELECT distinct VIDEO.* FROM VIDEO LEFT JOIN NEW_ORDER ON VIDEO.SERIAL_NUMBER=NEW_ORDER.SERIAL_NUMBER WHERE VIDEO.ORDER_NEW = '1' AND VIDEO.STATUS = '1' AND NEW_ORDER.AREA=$area ORDER BY NEW_ORDER.RANKING ASC";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }


    public function loadVideoFromSet($video_list) {
        if ($video_list=='') return null;

        $temp = explode(",", $video_list);

        $set_order="";
        $count=0;
        foreach ($temp as $item) {
            $set_order .= " WHEN $item THEN $count ";
            $count++;
        }


        $sql = "SELECT * FROM VIDEO WHERE SERIAL_NUMBER IN ($video_list) ORDER BY CASE SERIAL_NUMBER $set_order END";


        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function loadVideoFromCategory($id=-1, $publish=-1, $range_modifier='', $order_modifier='ORDER BY STICKY,PUBLISH_DATE DESC') {
        if ($id == null) return;
        $sql = "SELECT * FROM VIDEO LEFT JOIN (SELECT VIDEO_ID, GROUP_CONCAT(TAG_ID) AS TAG FROM TAG_VIDEO GROUP BY VIDEO_ID) ON SERIAL_NUMBER=VIDEO_ID WHERE 1 ";
        //$sql = "SELECT * FROM VIDEO WHERE 1";
        if ($id != -1) $sql .= " AND CATEGORY = " . $id;
        if ($publish!=-1) $sql .= " AND STATUS = " . $publish ;

        $sql .= " $order_modifier $range_modifier ";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $key => $val) {
            if ($val == null) $rows[$key]='無';
        }
        return $rows;
    }

    public function loadVideoFromCategoryWithFilter($id=-1, $publish=-1, $t='', $range_modifier='', $order_modifier='ORDER BY STICKY,PUBLISH_DATE DESC') {
        if ($id == null) return;
        $sql = "SELECT * FROM VIDEO LEFT JOIN (SELECT VIDEO_ID, GROUP_CONCAT(TAG_ID) AS TAG FROM TAG_VIDEO GROUP BY VIDEO_ID) ON SERIAL_NUMBER=VIDEO_ID WHERE 1 ";
        //$sql = "SELECT * FROM VIDEO WHERE 1";
        if ($id != -1) $sql .= " AND CATEGORY = " . $id;
        if ($publish!=-1) $sql .= " AND STATUS = " . $publish ;

        if ($t != '') {
            $ta = explode(" ", $t);
            $sa = array();
            foreach ($ta as $t) {
                array_push($sa, "(TITLE LIKE '%$t%')");
            }

            $s = join(" OR ", $sa);
            $sql .= " AND ($s) ";
        }

        $sql .= " $order_modifier $range_modifier ";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $key => $val) {
            if ($val == null) $rows[$key]='無';
        }
        return $rows;
    }

    public function loadRelatedVideo($video_id) {

        $result = array();
        $order_modifier='ORDER BY STICKY,PUBLISH_DATE DESC';


        $category_id = $this->getVideoInfo($video_id, "CATEGORY");

        if ($category_id != null)  {

            $sql = "SELECT * FROM VIDEO WHERE 1";
            $sql .= " AND CATEGORY = " . $category_id;
            $sql .= " AND STATUS = 1" ;
            $sql .= " AND SERIAL_NUMBER != ".$video_id ;

            $sql .= " $order_modifier  ";
            $sth = MainDB::getConnection()->prepare($sql);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }    

    public function deleteVideo($id=-1) {
        $sql = "DELETE FROM VIDEO ";
        if ($id != -1) $sql .= "WHERE SERIAL_NUMBER = '$id'";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }
    
    public function getVideoInfo($id, $field) {

        $sql = "SELECT $field FROM VIDEO WHERE SERIAL_NUMBER = '$id'";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        return $sth->fetchColumn();
    }   


    public function updateVideoOrder($id, $order) {

        $sql = 'UPDATE VIDEO SET STICKY=' . $order . ' WHERE SERIAL_NUMBER = "' . $id . '"'; 
        $sth = MainDB::getConnection()->prepare($sql); 
        return $sth->execute();


    }

    public static function getVideoCount($id) {
        $count=0;
        if (file_exists(__DATA_PATH__."/video/360p/$id.mp4")) $count++;
        if (file_exists(__DATA_PATH__."/video/720p/$id.mp4")) $count++;
        return $count;
    }

    public static function getVideoDuration($v) {
        ob_start();
        passthru(__FFMPEG__." -i \"{$v}\" 2>&1");
        $duration = ob_get_contents();
        ob_end_clean();

        $search='/Duration: (.*?),/';
        $duration=preg_match($search, $duration, $matches, PREG_OFFSET_CAPTURE, 3); 
        return  $matches[1][0];
    }

    public function updateField($id, $field, $content) {
        //$sth = MainDB::getConnection()->prepare("UPDATE VIDEO SET $field='".$content."' WHERE SERIAL_NUMBER=".$id);
//        $sth->execute();
        $sth = MainDB::getConnection()->prepare("UPDATE VIDEO SET $field=:$field WHERE SERIAL_NUMBER=".$id);

        $sth->bindParam(":$field", $content);
        $sth->execute();
    } 

    public function search($t) {

        $ta = explode(" ", $t);

        $sa = array();
        foreach ($ta as $t) {
            array_push($sa, "(TITLE LIKE '%$t%')");
//            array_push($sa, "(DETAIL LIKE '%$t%')");
        }   

        $s = join(" OR ", $sa); 


        $sql = "SELECT * FROM VIDEO WHERE ($s) AND (STATUS=1)";

        $sth = MainDB::getConnection()->prepare($sql);

        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }   

    public function updateVideo($data) {

 //       $set_list = array(); 
        $prepared_param = array(); 
        $output_data = array(); 
        if (!isset($data['SERIAL_NUMBER'])) return -1;
        
        $valid_key_array = array("SERIAL_NUMBER", "TITLE", "CATEGORY", "ORDER_NEW", "STICKY", "AUTHOR", "DETAIL", "EDITOR_ID", "STATUS", "PUBLISH_DATE", "OFF_DATE", "VIDEO_LENGTH", "VIDEOS");
        foreach ($data as $key => $val) {

            if (! in_array($key, $valid_key_array)) continue;
            switch ($key) {
                case 'SERIAL_NUMBER' : $index = $val; break;
                case 'ORDER_NEW' :
                    if ($this->getVideoInfo($data['SERIAL_NUMBER'], "ORDER_NEW") != (int)$val) {
        
                        if ($val == "1") {
                            $sql = "SELECT RANKING FROM NEW_ORDER ";
                            $sth = MainDB::getConnection()->prepare($sql);
                            $sth->execute();
                            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

                            $max = 0;
                            foreach ($rows as $item) { if ($item['RANKING']>$max) $max = $item['RANKING']; }
                            $rank = $max+1;
                            $id = $data['SERIAL_NUMBER'];

                            $tmp['SERIAL_NUMBER'] = $id;
                            $tmp['RANKING'] = $rank;
                            $tmp['AREA'] = 0;
                            $sql = "INSERT INTO NEW_ORDER (SERIAL_NUMBER, RANKING, AREA) VALUES (:SERIAL_NUMBER, :RANKING, :AREA)"; 
                            $sth = MainDB::getConnection()->prepare($sql);
                            $sth->execute($tmp);
                        } else {
                            $this->removeFromNewOrder($data['SERIAL_NUMBER']); 
			}
                    }
                    break;

/*
                case 'STATUS' :
                    if ($this->getVideoInfo($data['SERIAL_NUMBER'], "STATUS") != (int)$val) {
                        $now = getTime();

                        if ($val == "1") { 
                              $data["PUBLISH_DATE"] = $now;
                        } else if ($val == "2") { 
                              $data["OFF_DATE"] = $now;
                              $data["ORDER_NEW"] = "0";
                              $this->removeFromNewOrder($data['SERIAL_NUMBER']); 
                        }
                    }
                    array_push($set_list, "$key = '$val'");
                    break;
                case 'PUBLISH_DATE' :
                    break;
                case 'OFF_DATE' :
                    break;
*/
                default : 
//                    $val=str_replace("'","\\'",$val);
//                    array_push($set_list, "$key = '$val'");
                    array_push($prepared_param, "$key = :$key");
                    $output_data["$key"] = $val;
               
            }
        }

        if (isset($data['PUBLISH_DATE'])){  
//            array_push($set_list, "PUBLISH_DATE = '".$data['PUBLISH_DATE']."'");
            $key = 'PUBLISH_DATE';
            array_push($prepared_param, "$key = :$key");
            $output_data["$key"] = $data[$key];
        }
        if (isset($data['OFF_DATE'])){
//            array_push($set_list, "OFF_DATE = '".$data['OFF_DATE']."'");
            $key = 'OFF_DATE';
            array_push($prepared_param, "$key = :$key");
            $output_data["$key"] = $data[$key];
        }
        if (isset($data['ORDER_NEW'])){  
//            array_push($set_list, "ORDER_NEW = '".$data['ORDER_NEW']."'");
            $key = 'ORDER_NEW';
            array_push($prepared_param, "$key = :$key");
            $output_data["$key"] = $data[$key];
        }

//        if (count($set_list)<1) return -1;
        if (count($prepared_param)<1) return -1;

//        $set_string = join(',', $set_list);
        $prepared_params = join(',', $prepared_param);
        
//        $sql = 'UPDATE VIDEO SET ' . $set_string . ' WHERE SERIAL_NUMBER = "' . $index . '"'; 
        $sql1 = 'UPDATE VIDEO SET ' . $prepared_params . ' WHERE SERIAL_NUMBER = "' . $index . '"'; 

//        $sth = MainDB::getConnection()->prepare($sql); 
//        return $sth->execute();
        $sth = MainDB::getConnection()->prepare($sql1); 
        return $sth->execute($output_data);

    }

    private function removeFromNewOrder($id) {
        $sql = "DELETE FROM NEW_ORDER WHERE SERIAL_NUMBER='" . $id . "'"; 
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
    }

    public function doCommand($command) {
        
        $sth = MainDB::getConnection()->prepare($command); 
        return $sth->execute();
    }

    public function saveVideo($data) {

        $data = filterInput($this->columns, $data);
        if ($data["SERIAL_NUMBER"]=='新影片') {

            $t = getDate1();
            $c=0;
            $sn="";

            do {
                $c++;
                $sn = sprintf("%s%02d", $t, $c);
            } while ($this->getVideoInfo($sn, "SERIAL_NUMBER")==$sn);
            $data["SERIAL_NUMBER"]=$sn;
        }

        $sth = MainDB::getConnection()->prepare('INSERT OR REPLACE INTO VIDEO (SERIAL_NUMBER, TITLE, CATEGORY, ORDER_NEW, STICKY, AUTHOR, DETAIL, EDITOR_ID, STATUS, PUBLISH_DATE, OFF_DATE, VIDEO_LENGTH, VIDEOS) VALUES (:SERIAL_NUMBER, :TITLE, :CATEGORY, :ORDER_NEW, :STICKY, :AUTHOR, :DETAIL, :EDITOR_ID, :STATUS, :PUBLISH_DATE, :OFF_DATE, :VIDEO_LENGTH, :VIDEOS)');
        $fields= array('SERIAL_NUMBER', 'TITLE', 'PUBLISH_DATE', 'OFF_DATE', 'ORDER_NEW', 'EDITOR_ID', 'DETAIL');
        $fields_0= array('STATUS', 'STICKY', 'VIDEO_LENGTH', 'VIDEOS');
        $fields_1= array('CATEGORY');

        foreach ($fields as $field) {
           if (isset($data[$field])) { 
               $buffer[$field] = $data[$field]; 
           } else {
               $buffer[$field]='';
           }
        }
        foreach ($fields_0 as $field) {
           if (isset($data[$field])) { 
               $buffer[$field] = $data[$field]; 
           } else {
               $buffer[$field]=0;
           }
        }
        foreach ($fields_1 as $field) {
           if (isset($data[$field])) { 
               $buffer[$field] = $data[$field]; 
           } else {
               $buffer[$field]=1;
           }
        }

        return $sth->execute($buffer);
    }

    public function updateCache() {

        $cache = new Cache();
        $cache->write("home_video_area1", $this->getHTML($this->loadNewVideo(1)));
        $cache->write("home_video_area2", $this->getHTML($this->loadNewVideo(2)));

        $rows = $this->loadAllPublishedVideo();
        foreach ($rows as $video) {
            $cache->write("video_".$video["SERIAL_NUMBER"], serialize($video));
        }

    }

    public function getHTML($books) {

return;

        $img_url = __DATA_URL__ . "/books/contents/";
        $img_path = __DATA_PATH__ . "/books/contents/";

        $count = 0;
        $output = "<ul style='list-style-type: none; padding:0px; border: solid 0px red; clear:both;'>";

        foreach ($books as $book) {
            $book_id = $book['SERIAL_NUMBER'];
            $title = $book['TITLE'];

            $image_file = $img_path.$book_id. '/index/0001.jpg';

            $cover_url = (file_exists($image_file))? $img_url.$book_id. '/index/0001.jpg': 'images/book.png';

            $output .=  "<li style='float:left; margin: 1px; position: relative; width: 165px; height:130px; text-align:center' class='ui-widget-content1 ui-corner-tr'>";
            $output .=  "<a href=\"bookdetail.php?book_id=$book_id\"><img src='$cover_url' alt='cover' style='border: solid 1px #999' /></a> \n";
            $output .=  "<h4 class='ui-widget-header1' style='text-align:center' >$title</h4>\n";
            $output .=   "</li>\n";
            $count++;
        }
        $output .= "</ul>";

        if ($count==0) { $output = "無資料"; }

        return $output;
    }


}

?>
