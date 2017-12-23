<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_log.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


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


//$qs = "select DATE, TIME, USER, ACTION, TYPE, TARGET, EXTRA from MANAGEMENT join ag.AgentInfo where ag.AgentInfo.AgentID=USER ";
//$qs1 = "select date from MANAGEMENT inner join ag.AgentInfo where AgentInfo.AgentID=USER ";
$qs = "select DATE, TIME, USER, ACTION, TYPE, TARGET, EXTRA from MANAGEMENT WHERE 1";
$qs1 = "select date from MANAGEMENT WHERE 1 ";

$condition = array();

if ($from!='')  {
    $qs .= " AND (DATE>='$from') ";
    $qs1 .= " AND (DATE>='$from') ";
}

if ($to!='')  {
    $qs .= " AND (DATE<='$to') ";
    $qs1 .= " AND (DATE<='$to') ";
}

if ($user!='') {
    $user = "'" . str_replace(",", "','", $user) . "'";
    $qs .= " AND (USER IN ($user)) ";
    $qs1 .= " AND (USER IN ($user)) ";
}
if ($type!='') {

    $type = "'" . str_replace(",", "','", $type) . "'";
    $qs .= " AND (TYPE IN ($type)) ";
    $qs1 .= " AND (TYPE IN ($type)) ";
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
$total = $logdb->search($qs1);

$jTableResult['Result'] = "OK";
$jTableResult['TotalRecordCount'] = count($total);
$jTableResult['Records'] = $rows;
print json_encode($jTableResult);

$result = "成功";
mlog("操作記錄查詢", $USER_ID, "瀏覽", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
