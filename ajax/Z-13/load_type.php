<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/class_log.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


$logDB = new Logs();
$logDB->init();

$rows = $logDB->loadManagementTypeList();

print json_encode($rows);

function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
