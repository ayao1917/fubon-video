<?php
include_once('inc/config.php');
include_once('inc/class_category.php');
include_once('inc/class_tag.php');
include_once('inc/class_video.php');
include_once('inc/class_banner.php');
include_once('inc/utils.php');

function getItemListHTML($list) {
    $html='';

    foreach($list as $item) {
        $html .= '<li class="v_item item_'.$item['id'].'">';
        $html .= '<div class="v_cover" style="background-image: url(DATA/images/cover150/' . $item['id']. '.png);"></div>';
        $html .= '<div class="v_desc">' . $item["title"]  .'<br/>影片長度：' . $item["duration"] . '</div>';
        //$html .= '<div class="v_action" onClick="onMenuClicked(\'video\', \'video\', ' . $item['id'] .');"></div>';
        $html .= '<div class="v_action" data-id="'.$item['id'] .'"></div>';
        $html .= '</li>';
    }

    return $html;
}

if (isset($_REQUEST['type'])) {

    $data = processRequest($_REQUEST['type']);

    $json=json_encode($data);

    header('Content-Type: application/javascript; charset=utf-8');
    echo (isset($_REQUEST['callback']))?$_REQUEST['callback'].'('.$json.');':$json;
}


function processRequest($type, $param="") {

    $response = array();

        switch($type) {
            case 'category': 
                $response["result"] = "success";
                $response["list"] = getCategories();
                break;
            case 'tag': 
                $response["result"] = "success";
                $response["list"] = getTags();
                break;
            case 'category_list': 
                $response["result"] = "success";

                $data = getCategoryVideoList($param);
                $response["list"] = $data;
                $response["html_0"] = getItemListHTML($data["order_0"]);
                $response["html_1"] = getItemListHTML($data["order_1"]);
                break;
            case 'tag_list': 
                $data = getTagVideoList($param);
                $response["list"] = $data;
                $response["html_0"] = getItemListHTML($data["order_0"]);
                $response["html_1"] = getItemListHTML($data["order_1"]);
                break;
            case 'banner': 
                $response["result"] = "success";
                $response["list"] = getBanners();
                break;
            case 'hot': 
                $response["result"] = "success";
                $response["list_0"] = getHots(1);
                $response["list_1"] = getHots(2);
                $response["html_0"] = getItemListHTML(getHots(1));
                $response["html_1"] = getItemListHTML(getHots(2));
                break;
            default:
                $response["result"] = "fail";
                $response["message"] = "incorrect parameter";
        }

    return $response;
}

function getCategories() {
    $category_db = new Category();
    $category_db->init();

    $category_data = $category_db->loadAllPublishedCategory();

    $category_array = array();
    foreach ($category_data as $item) {
        $id = $item["ID"];
        $content = array();
        $content["id"] = $id;
        $content["normal"] = "DATA/images/category/$id"."_normal.png"; 
        $content["press"] = "DATA/images/category/$id"."_press.png"; 

        array_push($category_array, $content);
    }
    return $category_array;
}


function getTags() {
    $tag_db = new Tag();
    $tag_db->init();

    $tag_data = $tag_db->loadAllPublishedTag();
    $tag_array = array();

    foreach ($tag_data as $item) {
        $id = $item["ID"];
        $content = array();
        $content["id"] = $id;
        $content["normal"] = "DATA/images/tag/$id"."_normal.png"; 
        $content["press"] = "DATA/images/tag/$id"."_press.png"; 
        array_push($tag_array, $content);
    }
    return $tag_array;
}

function getBanners() {
    $banner_db = new Banner();
    $banner_db->init();
    $banner_array = array();

    $rows = $banner_db->load();
    foreach ($rows as $item) {
        if ($item['ENABLED']!=1) continue;

        $id = $item['ID'];

        $hasVideo = file_exists(__DATA_PATH__."/video/banner/$id.mp4")?"true":"false";

        $content = array();
        $content["id"] = $id;
        $content["hasVideo"] = $hasVideo; 
        array_push($banner_array, $content);
    }   
    return $banner_array;
}

function getHots($area) {
    $video_db = new Video();

    $video_db->init();
    $hot_array = array();

    $hot = $video_db->loadNewVideo($area);

    $hot_array = array();

    foreach ($hot as $video_info) {
        $info = array();
        $info["id"] = $video_info['SERIAL_NUMBER'];
        $info["title"] = $video_info['TITLE'];
        $info["date"] = ""; 
        if ($video_info["PUBLISH_DATE"]!="") {
            $year = date("Y", strtotime($video_info["PUBLISH_DATE"]))-1911;
            $month = date("m", strtotime($video_info["PUBLISH_DATE"]));
            $day = date("d", strtotime($video_info["PUBLISH_DATE"]));
            $info["date"] = $year."年".$month."月".$day."日";
        }

        $info["duration"] = ""; 
        $data = explode(":", $video_info["VIDEO_LENGTH"]);
        if (count($data)==3) {

            if ($data[0]!="00") $info["duration"] .= $data[0]."時";            
            if ($data[1]!="00") $info["duration"] .= $data[1]."分";            
            $sec = explode(".", $data[2]);
            if ((count($sec)>1) && ($sec[0]!="00")) $info["duration"] .= $sec[0]."秒";
        }


        array_push($hot_array, $info);
    }
    return $hot_array;
}

function getCategoryVideoList($id) {
    $category_db = new Category();
    $video_db = new Video();

    $category_db->init();
    $video_db->init();


    $orders = array(
        'ORDER BY STICKY,PUBLISH_DATE DESC',
        'ORDER BY PUBLISH_DATE DESC, STICKY ',
        'ORDER BY TITLE'
    );

    $video_array = array();

    $video_array["category_id"] = $id;

    foreach ($orders as $key=>$item) {
        $temp = $video_db->loadVideoFromCategory($id, 1, '', $item);
        $content = array();
        foreach ($temp as $video_info) {
            $info = array();
            $info["id"] = $video_info['SERIAL_NUMBER'];
            $info["title"] = $video_info['TITLE'];
            $info["date"] = ""; 
            if ($video_info["PUBLISH_DATE"]!="") {
                $year = date("Y", strtotime($video_info["PUBLISH_DATE"]))-1911;
                $month = date("m", strtotime($video_info["PUBLISH_DATE"]));
                $day = date("d", strtotime($video_info["PUBLISH_DATE"]));
                $info["date"] = $year."年".$month."月".$day."日";
            }

            $info["duration"] = ""; 
            $data = explode(":", $video_info["VIDEO_LENGTH"]);
            if (count($data)==3) {

                if ($data[0]!="00") $info["duration"] .= $data[0]."時";            
                if ($data[1]!="00") $info["duration"] .= $data[1]."分";            
                $sec = explode(".", $data[2]);
                if ((count($sec)>1) && ($sec[0]!="00")) $info["duration"] .= $sec[0]."秒";
            }

            array_push($content, $info);
        }
        $video_array["order_$key"] = $content;
    }

    return $video_array;
}


function getTagVideoList($id) {
    $tag_db = new Tag();
    $tag_db->init();

    $order = "ORDER BY STICKY,PUBLISH_DATE DESC";

    $orders = array(
        'ORDER BY STICKY,PUBLISH_DATE DESC',
        'ORDER BY PUBLISH_DATE DESC, STICKY ',
        'ORDER BY TITLE'
    );

    $video_array = array();

    $video_array["tag_id"] = $id;

    foreach ($orders as $key=>$item) {

        $temp = $tag_db->loadPublishedVideo($id, $order);

        $content = array();
        foreach ($temp as $video_info) {
            $info = array();
            $info["id"] = $video_info['SERIAL_NUMBER'];
            $info["title"] = $video_info['TITLE'];
            $info["date"] = ""; 
            if ($video_info["PUBLISH_DATE"]!="") {
                $year = date("Y", strtotime($video_info["PUBLISH_DATE"]))-1911;
                $month = date("m", strtotime($video_info["PUBLISH_DATE"]));
                $day = date("d", strtotime($video_info["PUBLISH_DATE"]));
                $info["date"] = $year."年".$month."月".$day."日";
            }

            $info["duration"] = ""; 
            $data = explode(":", $video_info["VIDEO_LENGTH"]);
            if (count($data)==3) {

                if ($data[0]!="00") $info["duration"] .= $data[0]."時";    
                if ($data[1]!="00") $info["duration"] .= $data[1]."分";    
                $sec = explode(".", $data[2]);
                if ((count($sec)>1) && ($sec[0]!="00")) $info["duration"] .= $sec[0]."秒";
            }

            array_push($content, $info);
        }

        $video_array["order_$key"] = $content;
    }
    return $video_array;
}


?>
