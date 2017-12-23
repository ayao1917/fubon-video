<?php
header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

define(__UPLOAD__, 1);

include('../../inc/config.php');
include('../../inc/utils.php');

if (!isset($_REQUEST['type'])) return;

$timestamp = getTime();

switch ($_REQUEST['type']) {

    case 'download':
        $user = (isset($_REQUEST['user']))? $_REQUEST['user']:'guest';
        $id = (isset($_REQUEST['id']))? $_REQUEST['id']:'0';

        $log = fopen(__FDATA_PATH__."/logs/download.log", "a");
        fputs($log, "$timestamp $user $id\n");
        fclose($log);
        break;

    case 'offline':
        $user = (isset($_REQUEST['user']))? $_REQUEST['user']:'guest';
        $content = (isset($_REQUEST['content']))? $_REQUEST['content']:'';

        if ($content!='') {
            $log = fopen(__FDATA_PATH__."/logs/offline.log", "a");
            fputs($log, "$timestamp $user $content\n");
            fclose($log);
        }
        break;
    case 'book':
        $user = (isset($_REQUEST['user']))? $_REQUEST['user']:'guest';
        $id = (isset($_REQUEST['id']))? $_REQUEST['id']:'0';

        $log = fopen(__FDATA_PATH__."/logs/book.log", "a");
        fputs($log, "$timestamp $user $id\n");
        fclose($log);
        break;
}


?>
