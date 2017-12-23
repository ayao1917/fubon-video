<?php
include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_video.php');
include_once('../../inc/class_book.php');
include_once('../../inc/class_cache.php');

if (!isset($_REQUEST['type'])) {
    fail("param 'type' undefined");
};
$type = $_REQUEST['type'];
if (!isset($_REQUEST['id'])) {
    fail("param 'id' undefined");
};
$id = $_REQUEST['id'];

$video = new Video();
$video->init();
$videoTitle = $video->getVideoInfo($id, "TITLE");

if ($videoTitle == null || $videoTitle == "") {
    fail("video $id not found");
}

$book = new Book();
$book->init();
$bookData = array();
$bookData['ID'] = $id;
$bookData['TITLE'] = $videoTitle;
$bookData['TYPE'] = $type;
$book->updateVideoBook($bookData);

$response = array();
$response['result'] = 'success';
$response['message'] = "update book $id $videoTitle complete";
echo  $_REQUEST['callback'].'('.json_encode($response) .')';

function fail($msg) {
    $response = array();
    $response['result'] = 'fail';
    $response['message'] = $msg;
    $response['code'] = "moker";
    die($_REQUEST['callback'].'('.json_encode($response) .')');
}
