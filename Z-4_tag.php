<?php

include_once('inc/config.php');
include_once('inc/global.php');
include_once('inc/class_tag.php');
include_once('inc/class_video.php');


if (isset($_REQUEST['tag_id'])) { 
    $tag_id = $_REQUEST['tag_id'];
} else {
    die("參數錯誤");
}

$video_db = new Video();
$video_db->init();

$all_video = $video_db->loadAllVideo();


$tag_db = new Tag();
$tag_db->init();

$tag = $tag_db->load($tag_id);
$selected_video_array = $tag_db->loadVideoArray($tag_id);


$list_candidate = "";
$list_selected = "";
$video_name_array = array();

foreach ($all_video as $item) {
    $id = $item['SERIAL_NUMBER'];
    $video_name_array["$id"] = $item['TITLE']; 

    if (!in_array($item['SERIAL_NUMBER'], $selected_video_array)) {
        $id = $item['SERIAL_NUMBER'];
$class="";
//        $class = ($item['STATUS']==1)? "published":"not_published";

        $list_candidate .= "<li id='sort_$id' class='ui-state-default $class'> $id". $item['TITLE'] .'</li>';
    }
}
foreach ($selected_video_array as $id) {
        $list_selected .= "<li id='sort_$id' class='ui-state-default'>". $id . '_' . $video_name_array["$id"] .'</li>';
}

if (count($tag)==0) die ("錯誤");

$title = $tag[0]['TITLE'];
$imgfile1 = $tag[0]['ICON_NORMAL'];
$imgfile2 = $tag[0]['ICON_PRESS'];

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-4 選擇影片</title> 	
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
                                $.get('ajax/Z-4/save_order.php?ts='+new Date().getMilliseconds(), {tag_id: <?php echo $tag_id; ?>, selected:selected});
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
                <button id="exitButton" onClick="window.location.href='Z-4_tag_management.php';">返回系列列表</button>

                <hr>
                <div style="clear:both"> </div>
                <h2>系列名稱:<?php echo $title; ?></h2>
            </div>

            <div id="working">
                <div id="div_selected"> 
                    <h2>系列影片</h2>
                    <ul id="selected"> <?php echo $list_selected; ?></ul>
                </div>
                <div id="div_candidate"> 
                    <h2>影片清單</h2>
                    <ul id="candidate"> <?php echo $list_candidate; ?></ul>
                </div>
            </div>
	</body>
</html>
