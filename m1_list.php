<?php

include_once('inc/config.php');
include_once('inc/class_category.php');
include_once('inc/class_tag.php');
include_once('inc/class_video.php');
include_once('inc/class_banner.php');


header('Content-Type: application/javascript; charset=utf-8');
$data = main();

$json=json_encode($data);
echo (isset($_REQUEST['callback']))?$_REQUEST['callback'].'('.$json.');':$json;


function main() {

    $response = array();

    if (isset($_REQUEST["type"])) {
        switch($_REQUEST["type"]) {
            case 'category': 
                $response["result"] = "success";
                $response["list"] = getCategoryList();
                break;
            case 'tag': 
                $response["result"] = "success";
                $response["list"] = getTagList();
                break;
            case 'banner': 
                $response["result"] = "success";
                $response["list"] = getBannerList();
                break;
            case 'hot1': 
                $response["result"] = "success";
                $response["list"] = getHotList(1);
                break;
            case 'hot2': 
                $response["result"] = "success";
                $response["list"] = getHotList(2);
                break;
            default:
                $response["result"] = "fail";
                $response["message"] = "incorrect parameter";
        }
    } else {
        $response["result"] = "fail";
        $response["message"] = "invalid parameters";
    }  

    return $response;
}



function getCategoryList() {
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


function getTagList() {
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

function getBannerList() {
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

function getHotList($area) {
    $video_db = new Video();

    $video_db->init();
    $hot_array = array();

    $hot = $video_db->loadNewVideo($area);

    $hot_array = array();

    foreach ($hot as $item) {
        array_push($hot_array, $item['SERIAL_NUMBER']);
    }
    return $hot_array;
}

?>
