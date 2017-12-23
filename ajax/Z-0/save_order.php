<?php

include_once('../../inc/config.php');
include_once('../../inc/class_book.php');
include_once('../../inc/class_cache.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


if (!isset($_REQUEST['order'])) die();
$new_order = explode(",", $_REQUEST['order']);

$db = new Book();
$db->init();

for ($i=0; $i<count($new_order); $i++) {
    $id = substr($new_order[$i], 5);   // sort_<ID>
    $db->updateBookOrder($id, $i+1);
}

$cache = new Cache();
$cache->updateAllCache();
    

$result = "成功";
mlog("分類書籍順序設定", $USER_ID, "編輯", "設定順序", $result);
    

?>
