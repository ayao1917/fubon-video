<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/class_category.php');
include_once('../../inc/class_cache.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

if (!isset($_REQUEST['category_id'])) die();

$category = new Category();
$category->init();


$category_id = $_REQUEST['category_id'];
$selected = explode(",", $_REQUEST['selected']);

for ($i=0; $i<count($selected); $i++) {
    $selected[$i] = substr($selected[$i], 5);   // sort_<ID>
}

$category->updateVideoList($category_id, $selected);
$cache = new Cache();
$cache->updateAllCache();

$jTableResult = array();
$jTableResult['Result'] = "OK";
print json_encode($jTableResult);

$result = "成功";
mlog("分類管理", $USER_ID, "編輯", $_REQUEST['TITLE'], $result);

function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
