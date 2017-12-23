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

$qs = '';
$qs1 = '';

$condition = array();
$time_constraint = "";
$unit_constraint = "";
$rank_constraint = "";
$user_constraint = "";
$video_constraint = "";

if ($from!='')  {
    $qs .= " AND (DATE>='$from') ";
    $qs1 .= " AND (DATE>='$from') ";
    $time_constraint .= " AND (DATE>='$from') ";
}

if ($to!='')  {
    $qs .= " AND (DATE<='$to') ";
    $qs1 .= " AND (DATE<='$to') ";
    $time_constraint .= " AND (DATE<='$to') ";
}

if ($videos!='') {
    $qs .= " AND VIDEO=$video ";
    $qs1 .= " AND VIDEO=$video ";
    $video_constraint .= " AND VIDEO=$videos ";
}
if ($select=='0') {
    if ($uid!='') {
        $qs .= " AND ( ag1.AgentInfo.AgentID = '" .$uid . "') ";
        $qs1 .= " AND ( ag1.AgentInfo.AgentID = '" .$uid . "') ";
        $user_constraint .= " AND ( USER = '" .$uid . "') ";
    }
} else if ($units!='') {
    $units = "'" . str_replace(",", "','", $units) . "'";
    $qs .= " AND ag1.UnitInfo.UnitCode IN ($units) ";
    $qs1 .= " AND ag1.UnitInfo.UnitCode IN ($units) ";
    $unit_constraint = " AND UnitCode IN ($units) ";
}


$ranks1 = "'" . str_replace(",", "','", $ranks) . "'";
if ($rank_select=='0') {
    if ($ranks!='') {
        $qs .= " AND ag1.AgentInfo.Rank IN ($ranks1) ";
        $qs1 .= " AND ag1.AgentInfo.Rank IN ($ranks1) ";
        $rank_constraint = " AND Rank IN ($ranks1) ";
    }
} else if ($ranks!='') {
    $rank_constraint = " AND RANK NOT IN ($ranks1) ";
    $qs .= " AND ag1.AgentInfo.Rank NOT IN ($ranks1) ";
    $qs1 .= " AND ag1.AgentInfo.Rank NOT IN ($ranks1) ";
    $rank_constraint = " AND Rank NOT IN ($ranks1) ";
}

//$qs = "select LOG.DATE AS DATE, LOG.TIME AS TIME, VIDEOINFO.SERIAL_NUMBER AS SERIAL_NUMBER, VIDEOINFO.TITLE as VIDEO, AG.AgentName as NAME, LOG.USER AS USER, Parent.UnitName as PUN, AG.UnitCode as UC, UNIT.UnitName as UN, AG.Rank as RANK, AG.curStatus as STATUS, count(*) as NUM, sum(LOG.DURATION) as TOTAL_TIME FROM (select DATE, TIME, upper(USER) as USER, VIDEO, DURATION FROM reading WHERE ACTION='VIDEODETAIL' $time_constraint $user_constraint $video_constraint) as LOG join (SELECT AgentID, AgentName, Rank, curStatus, UnitCode FROM ag1.AgentInfo WHERE 1 $rank_constraint) as AG on AG.AgentID=LOG.USER join (SELECT UnitName, UnitCode, RegionCode from ag1.UnitInfo WHERE 1 $unit_constraint) as UNIT ON UNIT.UnitCode=AG.UnitCode left join (SELECT UnitName, UnitCode from ag1.UnitInfo) as Parent on Parent.Unitcode=UNIT.RegionCode left join m.VIDEO as VIDEOINFO on VIDEOINFO.SERIAL_NUMBER=LOG.VIDEO GROUP BY USER ";
$qs = "select LOG.DATE AS DATE, LOG.TIME AS TIME, VIDEOINFO.SERIAL_NUMBER AS SERIAL_NUMBER, VIDEOINFO.TITLE as VIDEO, AG.AgentName as NAME, LOG.USER AS USER, Parent.UnitName as PUN, AG.UnitCode as UC, UNIT.UnitName as UN, AG.Rank as RANK, AG.curStatus as STATUS, count(*) as NUM FROM (select DATE, TIME, USER, VIDEO FROM VIEW WHERE 1 $time_constraint $user_constraint $video_constraint) as LOG join (SELECT AgentID, AgentName, Rank, curStatus, UnitCode FROM ag1.AgentInfo WHERE 1 $rank_constraint) as AG on AG.AgentID=LOG.USER join (SELECT UnitName, UnitCode, RegionCode from ag1.UnitInfo WHERE 1 $unit_constraint) as UNIT ON UNIT.UnitCode=AG.UnitCode left join (SELECT UnitName, UnitCode from ag1.UnitInfo) as Parent on Parent.Unitcode=UNIT.RegionCode left join m.VIDEO as VIDEOINFO on VIDEOINFO.SERIAL_NUMBER=LOG.VIDEO GROUP BY USER ";

$qs1 = $qs;


if (($jtPageSize!='')&&($jtStartIndex != '')) {
    $range_modifier = "LIMIT $jtStartIndex, $jtPageSize";
}
if ($jtSorting!='') {
    $order_modifier = "ORDER BY $jtSorting";
} else {

    $order_modifier = "ORDER BY DATE, TIME";

}

//$group_modifier = " GROUP BY upper(USER) ";
$group_modifier = "";


$qs = "$qs $group_modifier $order_modifier $range_modifier ";
$qs1 = "$qs1 $group_modifier ";

file_put_contents("/tmp/aaaaaaaa", $qs);
$rows = $logdb->search($qs);

$total = $logdb->search($qs1);

$jTableResult['Result'] = "OK";
$jTableResult['TotalRecordCount'] = count($total);
$jTableResult['Records'] = $rows;
print json_encode($jTableResult);


$result = "成功";
mlog("閱讀記錄查詢", $USER_ID, "瀏覽", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
