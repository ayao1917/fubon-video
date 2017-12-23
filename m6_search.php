<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_video.php');

header('Content-Type: application/javascript; charset=utf-8');
$data = main();

$json=json_encode($data);
echo (isset($_REQUEST['callback']))?$_REQUEST['callback'].'('.$json.');':$json;

function main() {

    $response = array();

    if (isset($_REQUEST['q'])) {
        $response["result"] = "success";
        $response["list"] = getVideoList($_REQUEST['q']);
    } else {
        $response["result"] = "fail";
        $response["message"] = "invalid parameters";
    }  

    return $response;
}

function getVideoList($query) {
    $video_db = new Video();
    $video_db->init();

    $temp = $video_db->search($query);

    $content = array();
    foreach ($temp as $item) {
        array_push($content, $item['SERIAL_NUMBER']);
    }

    return $content;
}


?>
