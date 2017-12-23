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
$videos = isset($_REQUEST['videos'])?$_REQUEST['videos']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';
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

$time_constraint='';
$video_constraint='';
$unit_constraint='';
$rank_constraint='';


if ($from!='')  {
    $time_constraint .= " AND (DATE>='$from') ";
}

if ($to!='')  {
    $time_constraint .= " AND (DATE<='$to') ";
}

if ($videos!='') {
    $video_constraint .= " AND VIDEO IN ($videos) ";
}
if ($units!='') {
    $units = "'" . str_replace(",", "','", $units) . "'";
    $unit_constraint = "  AND ag1.AgentInfo.UnitCode IN ($units) ";
}

$ranks1 = "'" . str_replace(",", "','", $ranks) . "'";
if ($rank_select=='0') {
    if ($ranks!='') {
        $rank_constraint .= " AND ag1.AgentInfo.Rank IN ($ranks1) ";
    }   
} else if ($ranks!='') {
    $rank_constraint .= " AND ag1.AgentInfo.Rank NOT IN ($ranks1) ";
}


$wanted = ($select==0)? "count(distinct upper(USER)) as USER_COUNT":"count(USER) as USER_COUNT";
//$qs = "select $wanted, VIDEO AS SERIAL_NUMBER,ifnull(m.VIDEO.TITLE, VIDEO) as NAME from (select upper(USER) as USER, VIDEO FROM reading WHERE ACTION='VIDEODETAIL' $time_constraint $video_constraint) left join (SELECT AgentID FROM ag1.AgentInfo WHERE 1 $unit_constraint $rank_constraint) as AG on AG.AgentID=USER left join m.VIDEO on m.VIDEO.SERIAL_NUMBER=VIDEO GROUP BY SERIAL_NUMBER ORDER BY USER_COUNT DESC ";
$qs = "select $wanted, VIDEO AS SERIAL_NUMBER,ifnull(m.VIDEO.TITLE, VIDEO) as NAME from (select upper(USER) as USER, VIDEO FROM view WHERE 1 $time_constraint $video_constraint) join (SELECT AgentID FROM ag1.AgentInfo WHERE 1 $unit_constraint $rank_constraint) as AG on AG.AgentID=USER left join m.VIDEO on m.VIDEO.SERIAL_NUMBER=VIDEO GROUP BY SERIAL_NUMBER ORDER BY USER_COUNT DESC ";


if (($jtPageSize!='')&&($jtStartIndex != '')) {
    $range_modifier = "LIMIT $jtStartIndex, $jtPageSize";
}
if ($jtSorting!='') {
    $order_modifier = "ORDER BY $jtSorting";
}

debug($qs);

$total = $logdb->search($qs);

$qs = "$qs $order_modifier $range_modifier";


$rows = $logdb->search($qs);

$jTableResult['Result'] = "OK";
$jTableResult['TotalRecordCount'] = count($total);
$jTableResult['Records'] = $rows;
print json_encode($jTableResult);

$result = "成功";
mlog("觀看記錄統計", $USER_ID, "瀏覽", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
