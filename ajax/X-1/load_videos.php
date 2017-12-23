<?php

include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/class_video.php');
include_once('../../inc/class_category.php');

header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");


$videoDB = new Video();
$videoDB->init();

$categoryDB = new Category();
$categoryDB->init();

$rows = $categoryDB->load();

$output = array();

foreach ($rows as $item) {

    $video_array = array();

    $temp["categoryID"] = $item["ID"];
    $temp["categoryNAME"] = $item["TITLE"];
    $videos = getVideoListfromCategory($item["ID"]);

    
    foreach ($videos as $b) {
        $obj = array();
        $obj["SERIAL_NUMBER"] = $b["SERIAL_NUMBER"];
        $obj["TITLE"] = $b["TITLE"];

        array_push($video_array, $obj);
    }

    $temp["videoList"] = $video_array;

    array_push($output, $temp);
}



function getVideoListfromCategory($categoryID) {

    global $categoryDB, $videoDB; 
    /*
    $cat = array();
    array_push($cat, $categoryID);

    $children = $categoryDB->getAllChildren($categoryID);
    if (($children != null) && (count($children))>0)
        foreach ($children as $child) {
        if ($child != null) array_push($cat, $child['ID']);
    }


    $range_modifier = '';
    $order_modifier = '';
    $rows = $videoDB->loadVideoFromCategorySet($cat, $range_modifier, $order_modifier);
*/
    $rows = $videoDB->loadVideoFromCategory($categoryID);
    return $rows;
}



print json_encode($output);

function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
