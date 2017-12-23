<?php
header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

if (!isset($_REQUEST["id"]) || !isset($_REQUEST["name"])) {
    echo $_REQUEST['callback'].'failed, no id or name';
    die();
}

define("__UPLOAD__", 1);
date_default_timezone_set('Asia/Taipei');
include_once("../../inc/config.php");
include_once("../../inc/utils.php");
include_once("../../inc/conf.php");

$feedback = "ok";

$user_id = $_REQUEST["id"];
$user_name = $_REQUEST["name"];
$user_rank = (isset($_REQUEST["rank"])) ? $_REQUEST["rank"] : "0";
$user_unitcode = (isset($_REQUEST["code"])) ? $_REQUEST["code"] : "AA.BB";

$response = array();

$response['result'] = 'success';
$response['message'] = $feedback;
$response['id'] = $user_id;
$response['sid'] = SID;
$response['rank'] = $user_rank;
$response['name'] = $user_name;
$response['unitcode'] = $user_unitcode;

session_unset();
session_destroy();
session_start();

$response['session'] = SID;

$_SESSION['user_id'] = $user_id;
$_SESSION['user_name'] = $user_name;
$_SESSION['user_rank'] = $user_rank;
$_SESSION['user_unitcode'] = $user_unitcode;

echo  $_REQUEST['callback'].''.json_encode($response) .'';
//echo $_REQUEST['callback'].'success='.$feedback.'';