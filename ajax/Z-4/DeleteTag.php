<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_tag.php');
include_once('../../inc/class_cache.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

$ret = false;

if (isset($_REQUEST['ID'])) {
    $tag_id = $_REQUEST['ID'];
} else if (isset($_REQUEST)) {
    $tag_id = $_REQUEST;
} else {
    $jTableResult = array();
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = "未指定";
    print json_encode($jTableResult);
    die();
}

$tag = new Tag();
$tag->init();


if ($tag_id!='') {
    $ret = $tag->deleteTag($tag_id);
    $cache = new Cache();
    $cache->updateAllCache();
}

$jTableResult = array();
$jTableResult['Result'] = ($ret)?"OK":"ERROR";
$jTableResult['Message'] = ($ret)? "OK":"資料庫操作錯誤";
print json_encode($jTableResult);

$result = ($ret)?"成功":"失敗";
mlog("系列管理", $USER_ID, "刪除", $tag_id, $result);

?>
