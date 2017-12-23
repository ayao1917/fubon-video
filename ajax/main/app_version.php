<?php
header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

$response = array();
$response['CurrentVersion'] = "1.0.6.0112.1";
$response['PadVersion'] = "1.0.6.0112.1";
$response['PhoneVersion'] = "1.0.6.0112.1";
echo  $_REQUEST['callback'].'('.json_encode($response) .')';







//echo json_encode($response);
