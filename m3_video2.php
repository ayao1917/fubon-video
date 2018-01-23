<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_video.php');
include_once('inc/class_category.php');
include_once('inc/class_book.php');

header('Content-Type: application/javascript; charset=utf-8');
$data = main();

$json=json_encode($data);
echo (isset($_REQUEST['callback']))?$_REQUEST['callback'].'('.$json.');':$json;

$log = fopen(__FDATA_PATH__."/logs/user.log", "a");

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $user = $_REQUEST['i'];
    
    $timestamp = getTime();
    fputs($log, "$timestamp $user video $id -\n");
    fclose($log);

}

function main() {

    $response = array();

    if (isset($_REQUEST['id'])) {
        $video_id = $_REQUEST['id'];
        $response["result"] = "success";
        $response["id"] = $video_id;

        $video_db = new Video();
        $video_db->init();
        $category_db = new Category();
        $category_db->init();
        $video_info = $video_db->loadVideo($video_id);

        $category_info = $category_db->load($video_info["CATEGORY"]);
        $response["title"] = $video_info['TITLE'];

        if ($USER_RANK=='') {
            if (isset($_REQUEST['r'])) {
                $USER_RANK=$_REQUEST['r'];
            }
        }
        if ($USER_UNITCODE=='') {
            if (isset($_REQUEST['u'])) {
                $USER_UNITCODE=$_REQUEST['u'];
            }   
        }

        $publish_date = "";
        if ($video_info["PUBLISH_DATE"]!="") {
            $year = date("Y", strtotime($video_info["PUBLISH_DATE"]))-1911;
            $month = date("m", strtotime($video_info["PUBLISH_DATE"]));
            $day = date("d", strtotime($video_info["PUBLISH_DATE"]));
            $publish_date = $year."年".$month."月".$day."日";
        }

        $duration = "";
        $data = explode(":", $video_info["VIDEO_LENGTH"]);
        if (count($data)==3) {

            if ($data[0]!="00") $duration .= $data[0]."時";
            if ($data[1]!="00") $duration .= $data[1]."分";
            $sec = explode(".", $data[2]);
            if ((count($sec)>1) && ($sec[0]!="00")) $duration .= $sec[0]."秒";
        }

        $response["metadata1"] = "<p>上映日期：".$publish_date."</p> <p>影片類別：".$category_info[0]["TITLE"]."</p> <p>影片長度：".$duration."</p>";
        $response["metadata2"] = " <p>影片簡介：</p><p>".$video_info["DETAIL"]."</p>";



        $related_video_array = $video_db->loadRelatedVideo($video_id);

        $b = array();
        foreach ($related_video_array as $item) {
            array_push($b, $item["SERIAL_NUMBER"]);
        }
        $response["related_list"] = $b;

        $book_info = array();
        $book_db = new Book();
        $book_db->init();

        $video_books_for_all = $book_db->getBooksByType(2);
        $video_books_pres = $book_db->getBooksByType(3);

        if (array_key_exists($video_id, $video_books_for_all)) {
            $id1 = $video_books_for_all[$video_id][1];

            $book1 = array();
            $book1["id"] = $id1;
            $book1["img"] = "images/film_index/film_index_techbook.png";
            $book1["url"] = __URL_PREFIX__."ajax/main/book.php?id=$id1&sid=".session_id();

            $books = array();
            array_push($books, $book1);

            $book_info["name"] = $video_books_for_all[$video_id][0];
            $book_info["list"] = $books;
        }

        if (array_key_exists($video_id, $video_books_pres)) {
            $id1 = $video_books_pres[$video_id][1];
            $id2 = $video_books_pres[$video_id][2];

            $book1 = array();
            $book1["id"] = $id1;
            $book1["img"] = "images/film_index/film_index_download.png";
            $book1["url"] = __URL_PREFIX__."ajax/main/book.php?id=$id1&sid=".session_id();

            $books = array();
            array_push($books, $book1);

            $book2 = array();
            $book2["id"] = $id2;
            $book2["img"] = "images/film_index/film_index_bookdownload.png";
            $book2["url"] = __URL_PREFIX__."ajax/main/book.php?id=$id2&sid=".session_id();
            array_push($books, $book2);

            $book_info["name"] = $video_books_pres[$video_id][0];
            $book_info["list"] = $books;
        }

        $video_books = $book_db->getVideoBooks();

        if (array_key_exists($video_id, $video_books)) {

            $allow_rank=array('99','STF','AVP','SRM','ARM','VRM','DM','UM','AM','SP','CCP','SCP','CP','CCM','SCM','CM','CUM','CDM','CSM','CS','CFM');
            $allow_unit=array("VB110.0000","VB170.0000","VB180.0000","VB190.0000","VB1A0.0000","VB250.0000","VB280.0000","VB290.0000","VB2A0.0000","VB2B0.0000","VB390.0000","VB3A0.0000","VB3B0.0000","VB3C0.0000","VB3D0.0000","VB450.0000","VB460.0000","VB470.0000","VB480.0000","VB490.0000","VBE00.0000","VBE01.0000","VBE02.0000","VBE04.0000","VBF00.0000","VBF01.0000","VBF02.0000","VBF04.0000","VBF05.0000","VBF06.0000","VBF08.0000","VBF0A.0000","XB010.0000","XB020.0000","XB030.0000","XB040.0000");

            $id1 = $video_books[$video_id][1];
            $id2 = $video_books[$video_id][2];

            $book1 = array();
            $book1["id"] = $id1; 
            $book1["img"] = "images/film_index/film_index_wordbook.png";
            $book1["url"] = __URL_PREFIX__."ajax/main/book.php?id=$id1&sid=".session_id();

            $books = array();
            array_push($books, $book1);

            if ( in_array($USER_RANK , $allow_rank) || in_array($USER_UNITCODE , $allow_unit) ) {
                $book2 = array();
                $book2["id"] = $id2; 
                $book2["img"] = "images/film_index/film_index_guidebook.png";
                $book2["url"] = __URL_PREFIX__."ajax/main/book.php?id=$id2&sid=".session_id();
                array_push($books, $book2);
            }

            $book_info["name"] = $video_books[$video_id][0]; 
            $book_info["list"] = $books;
        }
        $response["books"] = (count($book_info)>0)? $book_info["list"]:array(); 

        $response["video_hd"] = video_info($video_id, "720p");
        $response["video_sd"] = video_info($video_id, "360p");

    } else {
        $response["result"] = "fail";
        $response["message"] = "invalid parameters";
    }  

    return $response;
}

    
function video_info($video_id, $resolution) {
    $size=0;
    $video_file = __DATA_PATH__."/video/$resolution/$video_id.mp4";
    $video_url = __URL_PREFIX__.__DATA_URL__."/video/$resolution/$video_id.mp4";
    if (file_exists($video_file)) {
        $size = filesize($video_file);
    } else {
        $video_file = __DATA_PATH__."/video/$resolution/$video_id.mp4";
        $video_url = __URL_PREFIX__.__DATA_URL__."/video/$resolution/$video_id.mp4";
        if (file_exists($video_file)) {
            $size = filesize($video_file);
        }
    }
    
    $info = array();
    $info["id"] = $video_id;
    $info["url"] = $video_url;
    $info["filesize"] = $size;

    return $info;

}   

?>
