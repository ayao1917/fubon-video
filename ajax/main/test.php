<?php
require __DIR__. '/../../../lib/Moker/O365/vendor/autoload.php';

use Moker\O365\GetActiveAppVersionEx;

include_once("../../inc/utils.php");

$param = [
    'appCode' => "FubonVideo",
    'platformName' => "Android"
];
$result = GetActiveAppVersionEx::go($param);

$response = array();
$status = json_decode($result['Message']);

$response['EnableO365'] = $status->EnableO365;
$response['ForceUpdate'] = $status->ForceUpdate;
$response['CurrentVersion'] = $status->CurrentVersion;
$response['Description'] = $status->Description;

print_r($result);
