<?php

include_once('../../inc/config.php');
include_once('../../inc/class_cache.php');
include_once('../../inc/class_category.php');
include_once('../../inc/class_tag.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


if (!isset($_REQUEST['category_order'])) die();
if (!isset($_REQUEST['tag_order'])) die();
$category_order = explode(",", $_REQUEST['category_order']);
$tag_order = explode(",", $_REQUEST['tag_order']);

$dbCategory = new Category();
$dbCategory->init();

$dbTag = new Tag();
$dbTag->init();

for ($i = 0;$i < count($category_order);$i ++) {
    $id = substr($category_order[$i], 5);
    $dbCategory->updateField($id, "WEIGHT", count($category_order) - $i);
}

for ($i = 0;$i < count($tag_order);$i ++) {
    $id = substr($tag_order[$i], 5);
    $dbTag->updateField($id, "WEIGHT", count($tag_order) - $i);
}

$cache = new Cache();
$cache->updateAllCache();

$result = "成功";
mlog("排序設定", $USER_ID, "編輯", "設定排序", $result);

?>