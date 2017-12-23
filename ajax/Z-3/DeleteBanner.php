<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_banner.php');
include_once('../../inc/class_cache.php');

include_once('../../log4php/Logger.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

Logger::configure('../../inc/log_config.xml');
$log = Logger::getLogger('fubonLogger');

$ret = false;

if (isset($_REQUEST['ID'])) {
    $banner_id = $_REQUEST['ID'];
} else if (isset($_REQUEST)) {
    $banner_id = $_REQUEST;
} else {
    $jTableResult = array();
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = "未指定";
    print json_encode($jTableResult);
    die();
}

$banner = new Banner();
$banner->init();

$log->debug("delete banner: " . $banner_id);

if ($banner_id != '') {
    $ret = $banner->deleteBanner($banner_id);
    $cache = new Cache();
    $cache->updateAllCache();

    deleteFile(__DATA_PATH__."/video/banner/$banner_id.mp4");
    deleteFile(__DATA_PATH__."/images/banner/$banner_id.jpg");
    deleteFile(__DATA_PATH__."/images/banner/$banner_id.png");
}

$jTableResult = array();
$jTableResult['Result'] = ($ret) ? "OK" : "ERROR";
$jTableResult['Message'] = ($ret) ? "OK" : "資料庫操作錯誤";
print json_encode($jTableResult);

$result = ($ret) ? "成功" : "失敗";
mlog("廣宣區管理", $USER_ID, "刪除", $banner_id, $result);

function deleteFile($path) {
    global $log;
    if (file_exists($path)) {
        if (unlink($path)) {
            $log->debug("banner file ".$path. " deleted");
        } else {
            $log->error("cannot delete banner file: ".$path);
        }
    } else {
        $log->debug("file not found: ".$path);
    }
}

?>
