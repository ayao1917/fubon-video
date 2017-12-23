<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_log.php');
include_once('../../inc/php-excel.class.php');

$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
$user = isset($_REQUEST['user'])?$_REQUEST['user']:'';

$jtStartIndex = isset($_REQUEST['jtStartIndex'])?$_REQUEST['jtStartIndex']:'';
$jtPageSize = isset($_REQUEST['jtPageSize'])?$_REQUEST['jtPageSize']:'';
$jtSorting = isset($_REQUEST['jtSorting'])?$_REQUEST['jtSorting']:'';
$jtSorting = urldecode($jtSorting);

$logdb = new Logs();
$logdb->init();

$range_modifier = '';
$order_modifier = '';

$qs = "select DATE, TIME, USER, ACTION, TYPE, TARGET, EXTRA from MANAGEMENT WHERE 1";

$condition = array();

if ($from!='')  {
    $qs .= " AND (DATE>='$from') ";
}

if ($to!='')  {
    $qs .= " AND (DATE<='$to') ";
}

if ($user!='') {
    $user = "'" . str_replace(",", "','", $user) . "'";
    $qs .= " AND (USER IN ($user)) ";
}
if ($type!='') {

    $type = "'" . str_replace(",", "','", $type) . "'";
    $qs .= " AND (TYPE IN ($type)) ";
}


if (($jtPageSize!='')&&($jtStartIndex != '')) {
    $range_modifier = "LIMIT $jtStartIndex, $jtPageSize";
}
if ($jtSorting!='') {
    $order_modifier = "ORDER BY $jtSorting";
} else {
    $order_modifier = "ORDER BY DATE, TIME";
}


$qs = "$qs $order_modifier $range_modifier";

$rows = $logdb->search($qs);

$xls = new Excel_XML('UTF-8',true, "報表");
$xls->addArray($rows);
$xls->generateXML('editor report');

$result = "成功";
mlog("操作記錄查詢", $USER_ID, "匯出", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
