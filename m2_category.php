<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_category.php');
include_once('inc/class_video.php');

header('Content-Type: application/javascript; charset=utf-8');
$data = main();

$json=json_encode($data);
echo (isset($_REQUEST['callback']))?$_REQUEST['callback'].'('.$json.');':$json;

function main() {

    $response = array();

    if (isset($_REQUEST['id'])) {
        $response["result"] = "success";
        $response["list"] = getVideoList($_REQUEST['id']);
    } else {
        $response["result"] = "fail";
        $response["message"] = "invalid parameters";
    }  

    return $response;
}


function getVideoList($id) {
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
        foreach ($temp as $item) {
            array_push($content, $item['SERIAL_NUMBER']);
        }
        $video_array["order_$key"] = $content;
    }

    return $video_array;
}


?>
