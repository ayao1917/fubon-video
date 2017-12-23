<?php

include_once('inc/config.php');
include_once('inc/global.php');
include_once('inc/class_category.php');
include_once('inc/class_video.php');


if (isset($_REQUEST['category_id'])) {
    $category_id = $_REQUEST['category_id'];
} else {
    die("參數錯誤");
}

$video_db = new Video();
$video_db->init();

$all_video = $video_db->loadAllPublishedVideo();

$category_db = new Category();
$category_db->init();

$category = $category_db->load($category_id);
$selected_video_array = $category_db->loadVideoArray($category_id);

$list_candidate = "";
$list_selected = "";
$video_name_array = array();

foreach ($all_video as $item) {
    $id = $item['SERIAL_NUMBER'];
    $video_name_array["$id"] = $item['TITLE'];

    if (!in_array($item['SERIAL_NUMBER'], $selected_video_array)) {
        $id = $item['SERIAL_NUMBER'];
        $class="";

        $list_candidate .= "<li id='sort_$id' class='ui-state-default $class'> $id". $item['TITLE'] .'</li>';
    }
}
foreach ($selected_video_array as $id) {
    $list_selected .= "<li id='sort_$id' class='ui-state-default'>". $id . '_' . $video_name_array["$id"] .'</li>';
}

if (count($category)==0) die ("錯誤");

$title = $category[0]['TITLE'];
$imgfile1 = $category[0]['ICON_NORMAL'];
$imgfile2 = $category[0]['ICON_PRESS'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Z-1 選擇影片</title>
    <link rel="stylesheet" href="css/global.css">

    <link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css">

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-all.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="js/jquery.form.js"></script>
    <script type="text/javascript" src="js/json2.js"></script>

    <script type="text/javascript">


        $(window).load(function() {
            $("button").button();

            $( "#candidate, #selected" ).sortable({
                connectWith: "ul",
                scroll: true,
                update: function(event, ui) {
                    var selected = $("#selected").sortable('toArray').toString();
                    $.get('ajax/Z-1/save_order.php?ts='+new Date().getMilliseconds(), {category_id: <?php echo $category_id; ?>, selected:selected});
                }
            });

            $( "#candicate, #selected" ).disableSelection();

        });


    </script>

    <style>
        #head {
            margin:20px 50px;
        }
        #working {
            margin:20px 50px;
        }
        #selected {
            margin:20px 50px;
        }
        #div_selected, #div_candidate { margin: 0; padding: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; border: solid 0px red; overflow:auto;}
        #selected, #candidate { list-style-type: none; margin: 0; padding: 0; margin-right: 10px; background: #fff; padding: 5px; width: 400px; height: 500px;}
        #selected li, #candidate li { margin: 5px; padding: 5px; font-size: 1.0em; width: 380px; overflow:hidden; }

        .published {background:#aff;}
        .not_published {background:#eee;}

    </style>
</head>

<body>
<div id="head">
    <button id="exitButton" onClick="window.location.href='Z-1_category_management.php';">返回分類列表</button>

    <hr>
    <div style="clear:both"> </div>
    <h2>分類名稱:<?php echo $title; ?></h2>
</div>

<div id="working">
    <div id="div_selected">
        <h2>分類影片</h2>
        <ul id="selected"> <?php echo $list_selected; ?></ul>
    </div>
    <div id="div_candidate">
        <h2>影片清單</h2>
        <ul id="candidate"> <?php echo $list_candidate; ?></ul>
    </div>
</div>
</body>
</html>
