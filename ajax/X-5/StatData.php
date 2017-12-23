<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/class_log.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';


$logdb = new Logs();
$logdb->init();

$range_modifier = '';
$order_modifier = '';
$condition = '';
$query = '';

if ($from!='')  {
    $condition .= " AND (DATE>='$from') ";
}

if ($to!='')  {
    $condition .= " AND (DATE<='$to') ";
}

switch ($select) {
    case '0': $query = "select date as key, sum(1) as value from login where 1"; break;
    case '1': $query = "select date as key, count(distinct upper(user)) as value from login where 1"; break;
    case '2': $query = "select date as key, sum(1) as value from view"; break;
    case '3': $query = "select date as key, count(distinct upper(user)) as value from view"; break;
    case '4': $query = "select date as key, count(distinct upper(book||user)) as value from view"; break;
    case '5': $query = "select date as key, count(USER) as value FROM (select DATE, UPPER(USER) AS USER from LOGIN GROUP BY USER ORDER BY DATE) WHERE 1"; break;
    case '6': $query = "select date as key, count(USER) as value FROM (select substr(DATE,0,7) AS DATE, UPPER(USER) AS USER from LOGIN GROUP BY USER ORDER BY DATE) WHERE 1"; break;
}

$qs = "$query $condition group by date ";

$result = $logdb->search($qs);
file_put_contents("/tmp/aaaaa", $qs);

$jTableResult['Result'] = "OK";
$jTableResult['TotalRecordCount'] = count($result);
$jTableResult['Records'] = $result;
print json_encode($jTableResult);

//$result = "成功";
//mlog("記錄概要", $USER_ID, "瀏覽", $select, $result);

function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
