<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_video.php');
include_once('../../inc/class_cache.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

$ret = false;
$video_id="";
file_put_contents("/tmp/zzz", print_r($_REQUEST, TRUE));

if (isset($_REQUEST['SERIAL_NUMBER'])) {
    $video_id = $_REQUEST['SERIAL_NUMBER'];
} else {
    $jTableResult = array();
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = "未指定書籍";
    print json_encode($jTableResult);
    die();
}

$video = new Video();
$video->init();

if ($video_id!='')  {
    $ret = $video->deleteVideo($video_id);

    $cache=new Cache();
    $cache->updateAllCache();
}


$jTableResult = array();
$jTableResult['Result'] = ($ret)?"OK":"ERROR";
$jTableResult['Message'] = ($ret)? "OK":"資料庫操作錯誤";
print json_encode($jTableResult);

$result = ($ret)?"成功":"失敗";
mlog("影片管理", $USER_ID, "刪除", $video_id, $result);


?>
