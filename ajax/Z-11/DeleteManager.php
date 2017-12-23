<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_manager.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

$ret = false;

if (isset($_REQUEST['ID'])) {
    $manager_id = $_REQUEST['ID'];
} else if (isset($_REQUEST)) {
    $manager_id = $_REQUEST;
} else {
    $jTableResult = array();
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = "未指定";
    print json_encode($jTableResult);
    die();
}

$manager = new Manager();
$manager->init();

if ($manager_id!='') {
    $ret = $manager->deleteManager($manager_id);
}

$jTableResult = array();
if ($ret) {
    $jTableResult['Result'] = "OK";
    $result = "成功";
} else {
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = "刪除失敗";
    $result = "失敗";
}
print json_encode($jTableResult);

mlog("後台帳號管理", $USER_ID, "刪除", $manager_id, $result);


?>
