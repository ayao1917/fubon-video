<?php
//error_reporting(0); 
//Cross domain
header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

require __DIR__. '/../../../lib/Moker/O365/vendor/autoload.php';

use Moker\O365\GetActiveAppVersionEx;

include_once("../../inc/utils.php");

$user_agent = getenv("HTTP_USER_AGENT");

if(strpos($user_agent, "Win") !== FALSE)
$client = "Windows";
elseif(strpos($user_agent, "iPad") !== FALSE)
$client = "iPad";
elseif(strpos($user_agent, "iPod") !== FALSE)
$client = "iPod";
elseif(strpos($user_agent, "iPhone") !== FALSE)
$client = "iPhone";
elseif(strpos($user_agent, "Android") !== FALSE) {
    if (strpos($_SERVER['HTTP_REFERER'], "tablet")) {
        $client = "Android_pad";
    } else {
        $client = "Android_phone";
    }
} elseif(strpos($user_agent, "Mac") !== FALSE)
$client = "Mac";


$param = [
    'appCode' => "FubonVideo",
    'platformName' => "AndroidPad"
];
$result = GetActiveAppVersionEx::go($param);

$response = array();
$status = json_decode($result['Message']);

$response['EnableO365'] = $status->EnableO365;
$response['ForceUpdate'] = $status->ForceUpdate;
$response['CurrentVersion'] = $status->CurrentVersion;
$response['Description'] = $status->Description;

debug($_REQUEST['callback'].'('.json_encode($response) .')');
echo  $_REQUEST['callback'].'('.json_encode($response) .')';
