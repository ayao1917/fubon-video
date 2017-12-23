<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/class_video.php');
include_once('../../inc/class_category.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


$category_id = isset($_REQUEST['categoryID'])?$_REQUEST['categoryID']:-1;
$jtStartIndex = isset($_REQUEST['jtStartIndex'])?$_REQUEST['jtStartIndex']:'';
$jtPageSize = isset($_REQUEST['jtPageSize'])?$_REQUEST['jtPageSize']:'';
$jtSorting = isset($_REQUEST['jtSorting'])?$_REQUEST['jtSorting']:'';
$jtSorting = urldecode($jtSorting);
$keyword = isset($_REQUEST['name'])?$_REQUEST['name']:'';
$keyword = urldecode($keyword);



$video = new Video();

$video->init();

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

$cat = array();
//$t = $video->loadvideoFromCategory($categoryID, $range_modifier, $order_modifier);
//if (count($t)>0) $rows = array_merge($rows, $t);

$total = 0;
$rows = $video->loadVideoFromCategoryWithFilter($category_id, -1, $keyword);
$total = count($rows);

$rows = $video->loadVideoFromCategoryWithFilter($category_id, -1, $keyword, $range_modifier, $order_modifier);


$jTableResult['Result'] = "OK";
$jTableResult['TotalRecordCount'] = $total;
$jTableResult['Records'] = $rows;
print json_encode($jTableResult);

$result = "成功";
mlog("影片管理", $USER_ID, "瀏覽", "", $result);

function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
