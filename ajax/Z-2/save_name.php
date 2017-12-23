<?php

include_once('../../inc/config.php');
include_once('../../inc/class_newordername.php');
include_once('../../inc/class_cache.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


if (!isset($_REQUEST['id'])) die();
$area = $_REQUEST['id'];
$name = $_REQUEST['name'];

    
$db = new NewOrderName();
$db->init();
$db->update($area, $name);

$cache = new Cache();
$cache->updateAllCache();

$result = "成功";
mlog("熱門設定", $USER_ID, "編輯", "更名$area 區:$name", $result);

?>
