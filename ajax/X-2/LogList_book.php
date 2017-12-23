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
$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
$ranks = isset($_REQUEST['ranks'])?$_REQUEST['ranks']:'';
$rank_select = isset($_REQUEST['rank_select'])?$_REQUEST['rank_select']:'';

$jtStartIndex = isset($_REQUEST['jtStartIndex'])?$_REQUEST['jtStartIndex']:'';
$jtPageSize = isset($_REQUEST['jtPageSize'])?$_REQUEST['jtPageSize']:'';
$jtSorting = isset($_REQUEST['jtSorting'])?$_REQUEST['jtSorting']:'';
$jtSorting = urldecode($jtSorting);

$logdb = new Logs();
$logdb->init();

$range_modifier = '';
$order_modifier = '';
$time_constraint = "";
$unit_constraint = "";
$rank_constraint = "";

if ($from!='')  {
    $time_constraint .= " AND (DATE>='$from') ";
}
if ($to!='')  {
    $time_constraint .= " AND (DATE<='$to') ";
}

if ($units!='') {
    $units = "'" . str_replace(",", "','", $units) . "'";
    $unit_constraint .= " AND ag1.UnitInfo.UnitCode IN ($units) ";
}

$ranks1 = "'" . str_replace(",", "','", $ranks) . "'";
if ($rank_select=='0') {
    if ($ranks!='') {
        $rank_constraint .= " AND ag1.AgentInfo.Rank IN ($ranks1) ";
    }
} else if ($ranks!='') {
    $rank_constraint .= " AND ag1.AgentInfo.Rank NOT IN ($ranks1) ";
}


if ($jtSorting!='') {
    $order_modifier = " ORDER BY $jtSorting";
} else {

    $order_modifier = " ORDER BY USAGE DESC";

}

if (($jtPageSize!='')&&($jtStartIndex != '')) {
    $range_modifier = " LIMIT $jtStartIndex, $jtPageSize";
}

//$qs = " SELECT Ifnull(ag2.UnitInfo.UnitName, ag1.UNITS.PARENT) as PUN, UC, UN, NUM, RUN.HEADCOUNT, USAGE FROM (SELECT ag1.UnitInfo.UnitCode AS UC, ag1.UnitInfo.UnitName AS UN, count(LOG.USER) as NUM, ag1.UnitInfo.Headcount as HEADCOUNT, round((count(LOG.USER)*100.0/ag1.UnitInfo.Headcount), 2) as USAGE  FROM ag1.AgentInfo JOIN ag1.UnitInfo LEFT JOIN  (SELECT USER FROM LOGIN WHERE 1 $time_constraint GROUP by USER) as LOG ON ag1.AgentInfo.AgentID=LOG.USER WHERE ag1.AgentInfo.UnitCode = ag1.UnitInfo.UnitCode and ag1.AgentInfo.CurStatus<'90' $rank_constraint $unit_constraint GROUP by UC) as RUN LEFT join ag1.UNITS ON RUN.UC = ag1.UNITS.CODE LEFT join ag2.UnitInfo ON ag1.UNITS.PARENT=ag2.UnitInfo.UnitCode ";
$qs = " SELECT Ifnull(ag2.UnitInfo.UnitName, ag1.UNITS.PARENT) as PUN, UC, UN, NUM, RUN.HEADCOUNT, USAGE FROM (SELECT ag1.UnitInfo.UnitCode AS UC, ag1.UnitInfo.UnitName AS UN, count(LOG.USER) as NUM, ag1.UnitInfo.Headcount_AG as HEADCOUNT, round((count(LOG.USER)*100.0/ag1.UnitInfo.Headcount_AG), 2) as USAGE  FROM ag1.AgentInfo JOIN ag1.UnitInfo LEFT JOIN  (SELECT USER FROM LOGIN WHERE 1 $time_constraint GROUP by USER) as LOG ON ag1.AgentInfo.AgentID=LOG.USER WHERE ag1.AgentInfo.UnitCode = ag1.UnitInfo.UnitCode and ag1.AgentInfo.CurStatus<'90' $rank_constraint $unit_constraint GROUP by UC) as RUN LEFT join ag1.UNITS ON RUN.UC = ag1.UNITS.CODE LEFT join ag2.UnitInfo ON ag1.UNITS.PARENT=ag2.UnitInfo.UnitCode ";



debug($qs);
$rows = $logdb->search($qs.$order_modifier.$range_modifier);
$total = $logdb->search($qs);

$jTableResult['Result'] = "OK";
$jTableResult['TotalRecordCount'] = count($total);
$jTableResult['Records'] = $rows;
print json_encode($jTableResult);

$result = "成功";
mlog("單位登錄查詢", $USER_ID, "瀏覽", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
