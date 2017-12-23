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
$video = isset($_REQUEST['video'])?$_REQUEST['video']:'';
$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';
$uid = isset($_REQUEST['uid'])?$_REQUEST['uid']:'';
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
$time_constraint = '';
$unit_constraint = '';
$video_constraint= "";
$rank_constraint = '';

if ($from!='')  {
    $time_constraint .= " AND (DATE>='$from') ";
}
if ($to!='')  {
    $time_constraint .= " AND (DATE<='$to') ";
}

if ($units!='') {
    $units = "'" . str_replace(",", "','", $units) . "'";
    $unit_constraint .= " AND UC IN ($units) ";
}
if ($video=='') {
    $video=0;
    $video_constraint = "";
} 

$ranks1 = "'" . str_replace(",", "','", $ranks) . "'";
if ($rank_select=='0') {
    if ($ranks!='') {
        $rank_constraint = " AND RANK IN ($ranks1) ";
    }
} else if ($ranks!='') {
    $rank_constraint = " AND RANK NOT IN ($ranks1) ";
}


if ($jtSorting!='') {
    $order_modifier = " ORDER BY $jtSorting";
} else {
    $order_modifier = " ORDER BY USAGE DESC";
}

if (($jtPageSize!='')&&($jtStartIndex != '')) {
    $range_modifier = " LIMIT $jtStartIndex, $jtPageSize";
}


if ($video!="0") {
    $qs1 = "select PUN, UC, UN, count(*) as HEADCOUNT, SUM(CASE WHEN U IS NULL THEN 0 ELSE 1 END) AS NUM, round(SUM(CASE WHEN U IS NULL THEN 0 ELSE 1 END)*100.0/count(*), 2) AS USAGE FROM (select PARENT.UnitName AS PUN, AG.UnitCode AS UC, AG.Rank as RANK, ag1.UnitInfo.UnitName AS UN, AgentID, LOG.USER as U from (select * FROM ag1.AgentInfo WHERE CurStatus<90) as AG join ag1.UnitInfo on AG.UnitCode = ag1.UnitInfo.UnitCode join ag1.UnitInfo as PARENT on ag1.UnitInfo.RegionCode=PARENT.UnitCode left join (select USER , count(*) as C from VIEW  WHERE 1 AND VIDEO='$video' $time_constraint GROUP BY USER) as LOG ON AG.AgentId =  LOG.USER) WHERE 1 $unit_constraint $rank_constraint GROUP BY UC " ;
} else {
    $qs1 = "select PUN, UC, UN, count(*) as HEADCOUNT, SUM(CASE WHEN U IS NULL THEN 0 ELSE 1 END) AS NUM, round(SUM(CASE WHEN U IS NULL THEN 0 ELSE 1 END)*100.0/count(*), 2) AS USAGE FROM (select PARENT.UnitName AS PUN, AG.UnitCode AS UC, AG.Rank as RANK, ag1.UnitInfo.UnitName AS UN, AgentID, LOG.USER as U from (select * FROM ag1.AgentInfo WHERE CurStatus<90) as AG join ag1.UnitInfo on AG.UnitCode = ag1.UnitInfo.UnitCode join ag1.UnitInfo as PARENT on ag1.UnitInfo.RegionCode=PARENT.UnitCode left join (select USER , count(*) as C from VIEW  WHERE 1 $time_constraint GROUP BY USER) as LOG ON AG.AgentId =  LOG.USER) WHERE 1 $unit_constraint $rank_constraint GROUP BY UC " ;

}
$qs = $qs1.$order_modifier.$range_modifier;

file_put_contents("/tmp/aaa_browser.txt", $qs1);
$rows = $logdb->search($qs);

$total = $logdb->search($qs1);

$jTableResult['Result'] = "OK";
$jTableResult['TotalRecordCount'] = count($total);
$jTableResult['Records'] = $rows;
print json_encode($jTableResult);

$result = "成功";
//mlog("閱讀記錄查詢", $USER_ID, "瀏覽", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
