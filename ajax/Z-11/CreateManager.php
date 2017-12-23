<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/class_manager.php');
include_once('../../inc/class_log.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

$list = new Manager();
$list->init();
$jTableResult = array();

if ((! isset($_REQUEST['ID'])) || (!validate($_REQUEST['ID']))) {
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = "建立失敗, ID名稱錯誤";
    $result = "失敗";
} else {

    $res = $list->create($_REQUEST);

    if (is_numeric($res)) {
        $jTableResult['Result'] = "OK";
        $result = "成功";
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "建立失敗,請檢查用戶序號是否重複";
        $result = "失敗";
    }
}
//$jTableResult['TotalRecordCount'] = sizeof($new_array);
$jTableResult['Record'] = $_REQUEST;
print json_encode($jTableResult);

mlog("後台帳號管理", $USER_ID, "建立", $_REQUEST['ID'], $result);


function error()
{
    header("HTTP/1.0 404 Not Found"); 
    die();
}

function validate($id)
{
    $rulePattern = '/^[a-zA-Z][12][0-9]{8}$/i';

    if (preg_match($rulePattern, $id) === 0) {
        return false;
    }
    return true;
}
?>
