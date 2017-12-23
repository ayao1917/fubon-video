<?php
include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_log.php');
include_once('../../inc/php-excel.class.php');


$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$books = isset($_REQUEST['books'])?$_REQUEST['books']:'';
$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';
$uid = isset($_REQUEST['uid'])?$_REQUEST['uid']:'';

$jtStartIndex = isset($_REQUEST['jtStartIndex'])?$_REQUEST['jtStartIndex']:'';
$jtPageSize = isset($_REQUEST['jtPageSize'])?$_REQUEST['jtPageSize']:'';
$jtSorting = isset($_REQUEST['jtSorting'])?$_REQUEST['jtSorting']:'';
$jtSorting = urldecode($jtSorting);

$logdb = new Logs();
$logdb->init();

$range_modifier = '';
$order_modifier = '';

$qs = "select DATE, TIME, USER, m.book.TITLE as BOOK, ag.AgentInfo.AgentName AS NAME, ag.UnitInfo.UnitCode AS UC, ag.UnitInfo.UnitName AS UN, ag.AgentInfo.Rank AS RANK, ag.AgentInfo.curStatus AS STATUS from reading inner join ag.AgentInfo inner join ag.UnitInfo inner join m.book where ag.AgentInfo.UnitCode = ag.UnitInfo.UnitCode and ag.AgentInfo.AgentID=USER and m.book.SERIAL_NUMBER=BOOK";

$condition = array();

if ($from!='')  {
    $qs .= " AND (DATE>='$from') ";
}

if ($to!='')  {
    $qs .= " AND (DATE<='$to') ";
}

if ($books!='') {
    $t = explode(',', $books);
    $book_condition = array();

    foreach ($t as $record) {
        array_push($book_condition, " (BOOK = $record) ");
    }
    $qs .= " AND (" . join(" OR ", $book_condition). ") ";
}
if ($select==0) {
    if ($uid!='') {
        $qs .= " AND ( ag.AgentInfo.AgentID = '" .$uid . "') ";
    }
} else if ($units!='') {
    $t = explode(',', $units);
    $unit_condition = array();

    foreach ($t as $record) {
        array_push($unit_condition, " (ag.UnitInfo.UnitCode='$record') ");
    }
    $qs .= " AND (" . join(" OR ", $unit_condition). ") ";
}

if (($jtPageSize!='')&&($jtStartIndex != '')) {
    $range_modifier = "LIMIT $jtStartIndex, $jtPageSize";
}
if ($jtSorting!='') {
    $order_modifier = "ORDER BY $jtSorting";
}


$qs = "$qs $order_modifier $range_modifier";

file_put_contents("/tmp/aaa.out", $qs);

$rows = $logdb->search($qs);


$xls = new Excel_XML('UTF-8',true, "報表");
$xls->addArray($rows);

$xls->generateXML('test');



mlog("閱讀記錄查詢", $USER_ID, "匯出", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
