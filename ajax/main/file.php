<?php

if (!isset($_REQUEST['id'])) die("error");
if (!isset($_REQUEST['sid'])) die("error");

$mobile = (isset($_REQUEST['m']));

$user_agent = getenv("HTTP_USER_AGENT");
if (!$mobile) {
    if(strpos($user_agent, "Win") !== FALSE)
        $os = "Windows";
    elseif(strpos($user_agent, "Mac") !== FALSE)
        $os = "Mac";
}


session_id($_REQUEST['sid']);
session_start();


include_once('../../inc/config.php');
include_once('../../inc/utils.php');

//mb_internal_encoding('utf-8');

$files = array(
    1 => "使用手冊(快速版).pdf",
    2 => "增員資料夾使用手冊(房仲).pdf",
    3 => "增員資料夾(房仲).ppt",
    4 => "增員資料夾(房仲).pptx",
    5 => "增員資料夾(房仲).pptx"
);

$id = $_REQUEST['id'];


$filename = "";

switch ($id) {
    case 1:
        $file = "/home/fubon/www/Vanin/下載檔案/使用手冊(快速版).pdf";
	$filename = urldecode($files[1]);
        $ext = "pdf";
        break;
    case 2:
    //    $file = "/home/fubon/www/Vanin/下載檔案/使用手冊(房仲).pdf";
        $file = "/home/fubon/www/Vanin/下載檔案/增員資料夾使用手冊(房仲).pdf";
        $filename = urldecode($files[2]);
        $ext = "pdf";
        break;
    case 3:
        $file = "/home/fubon/www/Vanin/下載檔案/增員資料夾(房仲).ppt";
        $filename = urldecode($files[3]);
        $ext = "vnd.ms-powerpoint";
        break;
    case 4:
        $file = "/home/fubon/www/Vanin/下載檔案/增員資料夾(房仲).pptx";
        $filename = urldecode($files[4]);
        $ext = "application/vnd.openxmlformats-officedocument.presentationml.presentation";

        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(stripos($ua,'android') !== false) { // && stripos($ua,'mobile') !== false) {

            $ext = "vnd.ms-powerpoint";
        }

        break;
    case 6:
        $file = "/home/fubon/www/Vanin/下載檔案/增員資料夾-使用手冊(汽車業代)-vanin校.pdf";
        $filename = urldecode($files[4]);
        $ext = "pdf";
        break;
    case 5:
        $file = "/home/fubon/www/Vanin/下載檔案/增員資料夾(房仲).pptx";
        $filename = urldecode($files[5]);
        $ext = "vnd.ms-powerpoint";
        break;
    default:
}

if ((!$mobile) && ($os=="win")) $filename = mb_convert_encoding($filename,"BIG-5","UTF-8");


if (filename !="") {

    header('Content-type: application/' . $ext );
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($file));
    header('Accept-Ranges: bytes');

    @readfile($file);

} else {
        die("不存在");
}
?>
