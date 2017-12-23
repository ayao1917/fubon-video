<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_video.php');
include_once('../../inc/class_log.php');
include_once('../../inc/report_config.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$videos = isset($_REQUEST['videos'])?$_REQUEST['videos']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';

$jtStartIndex = isset($_REQUEST['jtStartIndex'])?$_REQUEST['jtStartIndex']:'';
$jtPageSize = isset($_REQUEST['jtPageSize'])?$_REQUEST['jtPageSize']:'';
$jtSorting = isset($_REQUEST['jtSorting'])?$_REQUEST['jtSorting']:'';
$jtSorting = urldecode($jtSorting);

$logdb = new Logs();
$logdb->init();

$videoDB = new Video();
$videoDB->init();

$range_modifier = '';
$order_modifier = '';


$condition= '';


if ($from!='')  {
    $condition .= " AND (DATE>='$from') ";
}

if ($to!='')  {
    $condition .= " AND (DATE<='$to') ";
}

if ($videos!='') {
    $condition .= " AND VIDEO IN ($videos) ";
}

$wanted = ($select==0)? "count(distinct upper(USER)) as USER_COUNT":"count(upper(USER)) as USER_COUNT";


$qs = "select view.video as SERIAL_NUMBER, VIDEOINFO.publish_date AS PUBLISH_DATE, VIDEOINFO.title AS NAME, $wanted from view join m.video as VIDEOINFO on view.video=VIDEOINFO.serial_number where 1 $condition group by view.video order by USER_COUNT desc";

file_put_contents("/tmp/aaa.q", $qs);


if (($jtPageSize!='')&&($jtStartIndex != '')) {
    $range_modifier = "LIMIT $jtStartIndex, $jtPageSize";
}
if ($jtSorting!='') {
    $order_modifier = "ORDER BY $jtSorting";
}


$total = $logdb->search($qs);

$qs = "$qs $order_modifier $range_modifier";


$rows = $logdb->search($qs);

$jTableResult['Result'] = "OK";
$jTableResult['TotalRecordCount'] = count($total);
$jTableResult['Records'] = $rows;
print json_encode($jTableResult);

$result = "成功";
//mlog("影片排行查詢", $USER_ID, "瀏覽", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
