<?php

include_once('../../inc/config.php');
include_once('../../inc/class_newvideo.php');
include_once('../../inc/class_cache.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


if (!isset($_REQUEST['order0'])) die();
$new_order0 = explode(",", $_REQUEST['order0']);
$new_order1 = explode(",", $_REQUEST['order1']);
$new_order2 = explode(",", $_REQUEST['order2']);

$data = array();

for ($i=0; $i<count($new_order0); $i++) {
    $new_order0[$i] = substr($new_order0[$i], 5);   // sort_<ID>
}
for ($i=0; $i<count($new_order1); $i++) {
    $new_order1[$i] = substr($new_order1[$i], 5);   // sort_<ID>
}
for ($i=0; $i<count($new_order2); $i++) {
    $new_order2[$i] = substr($new_order2[$i], 5);   // sort_<ID>
}

    
$db = new NewVideo();
$db->init();
$db->saveAll($new_order0, $new_order1, $new_order2);

$cache = new Cache();
$cache->updateAllCache();

$result = "成功";
mlog("熱門設定", $USER_ID, "編輯", "設定圖片", $result);
    

?>
