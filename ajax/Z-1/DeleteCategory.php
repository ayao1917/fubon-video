<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_category.php');
include_once('../../inc/class_cache.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

$ret = false;

if (isset($_REQUEST['ID'])) {
    $category_id = $_REQUEST['ID'];
} else if (isset($_REQUEST)) {
    $category_id = $_REQUEST;
} else {
    $jTableResult = array();
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = "未指定";
    print json_encode($jTableResult);
    die();
}

$category = new Category();
$category->init();


if ($category_id!='') {
    $ret = $category->deleteCategory($category_id);
    $cache = new Cache();
    $cache->updateAllCache();
}

$jTableResult = array();
$jTableResult['Result'] = ($ret)?"OK":"ERROR";
$jTableResult['Message'] = ($ret)? "OK":"資料庫操作錯誤";
print json_encode($jTableResult);

$result = ($ret)?"成功":"失敗";
mlog("分類管理", $USER_ID, "刪除", $category_id, $result);

?>
