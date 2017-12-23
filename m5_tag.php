<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_tag.php');

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
    $tag_db = new Tag();
    $tag_db->init();

    $order = "ORDER BY STICKY,PUBLISH_DATE DESC";

    $temp = $tag_db->loadPublishedVideo($id, $order);

    $content = array();
    foreach ($temp as $item) {
        array_push($content, $item['SERIAL_NUMBER']);
    }

    return $content;
}


?>
