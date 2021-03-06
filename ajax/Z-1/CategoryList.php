<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/class_category.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


$jtStartIndex = isset($_REQUEST['jtStartIndex'])?$_REQUEST['jtStartIndex']:'';
$jtPageSize = isset($_REQUEST['jtPageSize'])?$_REQUEST['jtPageSize']:'';
$jtSorting = isset($_REQUEST['jtSorting'])?$_REQUEST['jtSorting']:'';
$jtSorting = urldecode($jtSorting);

$category = new Category();
$category->init();

$range_modifier = '';
$order_modifier = '';


if (($jtPageSize!='')&&($jtStartIndex != '')) {
    $range_modifier = "LIMIT $jtStartIndex, $jtPageSize";
}
if ($jtSorting!='') {
    $order_modifier = "ORDER BY $jtSorting";
}

$total = $category->load(-1, '', '');
$rows = $category->load(-1, $range_modifier, $order_modifier);


$jTableResult['Result'] = "OK";
$jTableResult['TotalRecordCount'] = sizeof($total);
$jTableResult['Records'] = $rows;
print json_encode($jTableResult);

$result = "成功";
mlog("分類管理", $USER_ID, "瀏覽", '', $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
