<?php

include_once('inc/config.php');
include_once('inc/class_category.php');
include_once('inc/class_tag.php');
include_once('inc/class_video.php');
include_once('inc/class_banner.php');



function getResourceList($type) {

    $response = array();

    switch($type) {
            case 'category': 
                return getCategoryList();
                break;
            case 'tag': 
                return getTagList();
                break;
            case 'banner': 
                return getBannerList();
                break;
            case 'hot1': 
                return getHotList(1);
                break;
            case 'hot2': 
                return getHotList(2);
                break;
    }

    return array();
}



function getCategoryList() {
    $orders = array(
        'ORDER BY STICKY,PUBLISH_DATE DESC',
        'ORDER BY PUBLISH_DATE DESC, STICKY ',
        'ORDER BY TITLE'
    );  

    $category_db = new Category();
    $category_db->init();
    $video_db = new Video();
    $video_db->init();

    $category_data = $category_db->loadAllPublishedCategory();

    $category_array = array();
    foreach ($category_data as $item) {
        $id = $item["ID"];
        $content = array();
        $content["id"] = $id;
        $content["normal"] = "DATA/images/category/$id"."_normal.png"; 
        $content["press"] = "DATA/images/category/$id"."_press.png"; 

        foreach ($orders as $key=>$item) {
            $temp = $video_db->loadVideoFromCategory($id, 1, '', $item);
            $list = array();
            foreach ($temp as $item) {
                array_push($list, $item['SERIAL_NUMBER']);
            }   
            $content["order_$key"] = $list;
        }   

        array_push($category_array, $content);
    }
    return $category_array;
}

function getTagList() {
    $tag_db = new Tag();
    $tag_db->init();

    $order = "ORDER BY STICKY,PUBLISH_DATE DESC";

    $tag_data = $tag_db->loadAllPublishedTag();
    $tag_array = array();

    foreach ($tag_data as $item) {
        $id = $item["ID"];
        $content = array();
        $content["id"] = $id;
        $content["normal"] = "DATA/images/tag/$id"."_normal.png"; 
        $content["press"] = "DATA/images/tag/$id"."_press.png"; 

        $temp = $tag_db->loadPublishedVideo($id, $order);

        $content["list"] = array();
        foreach ($temp as $item) {
            array_push($content["list"], $item['SERIAL_NUMBER']);
        }   

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
