<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_tag.php');
include_once('phone_info_inc.php');

header('Content-Type: application/javascript; charset=utf-8');
$data = main();

$json=json_encode($data);
echo (isset($_REQUEST['callback']))?$_REQUEST['callback'].'('.$json.');':$json;

function main() {

    $response = array();

    if (isset($_REQUEST['id'])) {
        $response["result"] = "success";

        $data = getVideoList($_REQUEST['id']);
        $response["list"] = $data;

        $response["html_0"] = getItemListHTML($data["order_0"]);
        $response["html_1"] = getItemListHTML($data["order_1"]);

    } else {
        $response["result"] = "fail";
        $response["message"] = "invalid parameters";
    }  

    return $response;
}

function getVideoList($id) {
    $tag_db = new Tag();
    $tag_db->init();

    $order = "ORDER BY STICKY,PUBLISH_DATE DESC";

    $orders = array(
        'ORDER BY STICKY,PUBLISH_DATE DESC',
        'ORDER BY PUBLISH_DATE DESC, STICKY ',
        'ORDER BY TITLE'
    );

    $video_array = array();

    $video_array["tag_id"] = $id;

    foreach ($orders as $key=>$item) {

        $temp = $tag_db->loadPublishedVideo($id, $order);

        $content = array();
        foreach ($temp as $video_info) {
            $info = array();
            $info["id"] = $video_info['SERIAL_NUMBER'];
            $info["title"] = $video_info['TITLE'];
            $info["date"] = ""; 
            if ($video_info["PUBLISH_DATE"]!="") {
                $year = date("Y", strtotime($video_info["PUBLISH_DATE"]))-1911;
                $month = date("m", strtotime($video_info["PUBLISH_DATE"]));
                $day = date("d", strtotime($video_info["PUBLISH_DATE"]));
                $info["date"] = $year."年".$month."月".$day."日";
            }

            $info["duration"] = ""; 
            $data = explode(":", $video_info["VIDEO_LENGTH"]);
            if (count($data)==3) {

                if ($data[0]!="00") $info["duration"] .= $data[0]."時";    
                if ($data[1]!="00") $info["duration"] .= $data[1]."分";    
                $sec = explode(".", $data[2]);
                if ((count($sec)>1) && ($sec[0]!="00")) $info["duration"] .= $sec[0]."秒";
            }

            array_push($content, $info);
        }

        $video_array["order_$key"] = $content;
    }
    return $video_array;
}


?>
