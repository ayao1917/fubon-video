<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_category.php');
include_once('inc/class_video.php');
include_once('inc/class_banner.php');

$video_db = new Video();
$banner_db = new Banner();

$video_db->init();
$banner_db->init();

$rows = $banner_db->load();
$banner_id=-1;
$banner_array = array();
$banner_video = array();
$video_banner_array = array();

$js_script = '';

foreach ($rows as $item) {
    if ($item['ENABLED']==1) {
        $id = $item['ID'];
        if (file_exists(__DATA_PATH__."/video/banner/$id.mp4")) {
            array_push($video_banner_array, $id);

            //array_push($banner_array, '\'<img id="banner_'.$id.'" class="video_banner"  style="cursor:pointer; " src="'.__DATA_URL__.'/images/banner/'.$id.'.png" />\''); 
            array_push($banner_array, '\'<img src="'.__DATA_URL__.'/images/banner/'.$id.'.jpg" />\'');
            array_push($banner_video, $id);
        } else {
            array_push($banner_array, '\'<img src="'.__DATA_URL__.'/images/banner/'.$id.'.jpg" />\'');
            array_push($banner_video, -1);
        }
    }
}

$js_script .= "var video_banner = [" .join(',', $video_banner_array) ."];\n";

$hot1 = $video_db->loadNewVideo(1);
$hot2 = $video_db->loadNewVideo(2);

$i=0;
$hot1_array = array();
foreach ($hot1 as $item) {
    array_push($hot1_array, $item['SERIAL_NUMBER']);
}
$js_script .= "var hot1 = [" .join(',', $hot1_array) ."];\n";

$hot2_array = array();
foreach ($hot2 as $item) {
    array_push($hot2_array, $item['SERIAL_NUMBER']);
}
$js_script .= "var hot2 = [" .join(',', $hot2_array) ."];\n";

$hot1_html = makePageOutput2($hot1, 2);
$hot2_html = makePageOutput2($hot2, 4);





function makePageOutput2($data, $perPage) {
    $output = "<ul class='slides'>";

    $nVideos = count($data);
    $nPages = floor(($nVideos-1)/$perPage+1);

    $item_width = floor(95.0/$perPage);
    $item_width_relative = $item_width."%" ;
    $item_height_relative = "90%";

    $remain=$nVideos;
    $i=0;
    while ($remain>0) {

        $output .= '<li>';
        $nItems =  ($remain>$perPage)? $perPage:$remain;

        $output  .= '<div style="margin-left:auto; margin-right:auto;;height:97%; width:94%;border:solid 0px red">';

 //       $output .= '<div style="height: 250px; border: solid 0px red; margin:0 15px; text-align: justify;">';

        for ($j=0; $j<$nItems; $j++) {
            $id = $data[$i++]['SERIAL_NUMBER'];
            $download_mark="<img src='images/downloads.png' class='download_$id' style='width:20%; height:15%; position:absolute; right:5px; top:-5px' />";
            $cell = '<div style="position:relative; margin: 15px 1px; float:left; width: '.$item_width_relative.'; height: '.$item_height_relative .' ; border: solid 0px red;">' . $download_mark .' <img style="width:100%; height:100%" src="DATA/images/cover150/' . $id . '.png" onClick="onCoverClicked(' . $id . ');"/></div>';
//            $cell = '<div style="position:relative; margin: 5px 1px; float:left; border: solid 0px red;">' . $download_mark .' <img style="width:100%; height:100%" src="DATA/images/cover150/' . $id . '.png" onClick="onCoverClicked(' . $id . ');"/></div>';

//$cell = '<div style="position:relative; display:inline-block; zoom:1; width: 120px; height: 170px; vertical-align:top; border    : solid 0px red; background:url(DATA/images/cover150/' .$id . '.png) no-repeat center center; cursor:pointer; " onClick="onCoverClicked(' . $id . ');"><img src="images/downloads.png" class="download_'.$id.'" style="width:31px; height:31px; position:absolute; right:5px; top:5px" /> </div> ';

            $output.=$cell;
        }
        $output .=" <div style='width:100%; display:inline-block; font-size:0; line-height:0'> </div></div></li>";

        $remain -= $nItems;
    }

    $output .= "</ul>";
    return $output;
}


function makePageOutput($data, $perPage) {
    $output = "<ul class='slides'>";

    $i=0;
    foreach ($data as $item) {
        if (($i++ % $perPage) == 0) $output .= '<li><table style="table-layout:fixed; width:90%;margin-left:auto;margin-right:auto;  border-spacing: 0px; border-collapse: separate; border: solid 0px red;"><tr>';
        $id = $item['SERIAL_NUMBER'];
        $output .= '<td style="border:solid 0px red"><img style=" width:98%; height:90%;" src="DATA/images/cover/' . $id . '.png" onClick="onCoverClicked(' . $id . ');"/></td>';
        if (($i % $perPage) == 0) $output .= "</tr></table></li>\n";
    }
    if ($i % $perPage != 0) {
        while (($i++ % $perPage)!=0) {
            $output .= "\n<td></td>";
        }
        $output .= "</tr></table></li>\n";
    }

    $output .= "</ul>";

    return $output;
}


ulog($_SESSION['user_id'], "home","-", "-");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <title>富邦新視界</title>

    <link href="css/flexslider.css" type="text/css" rel="Stylesheet" />

    <script src="js/jquery.min.js"></script>
    <script src="js/ios6fix.js"></script>
    <script src="js/jquery.roundabout.js"></script>
    <script src="js/jquery.flexslider-min.js"></script>


    <style>

        #content {
            position:absolute; margin: 0 auto; top:0; left:0; right:0; bottom:0; background-color:#CECECE;
        }
        #ad {
            position:relative; margin: 0 auto; top:1px; height: 60px; left:0px; right:0; text-align:center;
        }
        #ad img{
            height:95%;
        }

        #container {
            position:absolute; top:5px; left:0;  right:0; top:0; height: 65%; text-align:center; border:solid 0px red;
/*
            position:absolute; top:5px; left:0;  right:0; top:0; bottom: 222px; text-align:center; border:solid 0px red;
*/
        }

        #carousel {
            position:relative; margin: 1px auto;
           /* width: 480px; height: 270px; */
           height:98%;
           width:80%;
            text-decoration:none; border:solid 0px green;
        }

        #carousel img {
            width:100%;
            height:100%;
        }
/*
        .play_button {
            position: absolute;
            display:none;
            left: 40%;
            top:47%;
            width: 128px;
            height: 128px;
            z-Index:1000;
            cursor:pointer;
        }
*/

        .play_button {
            background-image: url('images/play_normal.png');
            position: absolute;
            display:none;
            left: 40%;
            top:47%;
            width: 118px;
            height: 109px;
            z-Index:1000;
            cursor:pointer;
        }
        .play_button:hover {
            background-image: url('images/play_over.png');
        }


        .prev {
            position: absolute;
            left: 10%;
            top:47%;
            width: 40px;
            height: 40px;
            z-Index:1000;
            cursor:pointer;
        }
        .next {
            position: absolute;
            right: 10%;
            top:47%;
            width: 40px;
            height: 40px;
            z-Index:1000;
            cursor:pointer;
        }

        #new_area {
            background-color:#EDEDED;
            position:absolute;
            height:35%; left:0; width:33%;  bottom: 0px;
/*
            height:220px; left:0; width:33%;  bottom: 0px;
*/
        }
        #new_area_header {
            background-color:#1D8DCC;
            position:absolute;
            height:40px; left: 5px; right: 5px; top:8px;
        }
        #new_area_banner {
            position:absolute;
            height:40px; left: -2px; top:-2px;
        }
        #new_area_txt {
            position:absolute;
            height:25px; left: 30px; top:8px;
        }
        #new_area_video_list {
            position:absolute; left:0px; right:0px; bottom: 0; top:50px ;margin:0 0;  border: solid 0px green;
        }
        #new_area li {
            list-style-type: none;
        }
        #hot_area {
            background-color:#EDEDED;
            position:absolute;
            height:35%; right:0; width:66%;  bottom: 0px;
/*
            height:220px; right:0; width:66%;  bottom: 0px;
*/
        }
        #hot_area_header {
            background-color:#229E9A;
            position:absolute;
            height:40px; left: 5px; right: 5px; top:8px;
        }
        #hot_area_header img{
            position:absolute;
            height:25px; left: 30px; top:8px;
        }
        #hot_area_video_list {
            position:absolute; left:0px; right:0px; bottom: 0; top:50px ;margin:0 0;  border: solid 0px green;
        }
        #hot_area li {
            list-style-type: none;
        }
        #hot_area img, #new_area img {
            display:inline-block;
            cursor:pointer;
            max-width:100%;
            max-height:100%;
        }
        .slides{
            height:99%;
        }

        #item_list {
            position:absolute; left:0; right:0; bottom: 0; height: 190px;margin:0 0;
        }


        #item_list img {
            height: 180px;
            margin: 5px 20px;
            cursor:pointer;
        }

        .slideshow {
            margin-left: 50px;
            margin-right:50px;
        }

       .roundabout-holder {
          list-style: none;
          padding: 0;
          margin: 0;
          height: 100%;
          width: 100%;
       }
       .roundabout-moveable-item {
          height: 100%;
          width: 100%;
          cursor: pointer;
          background-color: #ccc;
          border: 0px solid #999;
       }
       .roundabout-in-focus {
          cursor: auto;
       }

    </style>
</head>
<body>
    <div id="content" >
        <div id="container">

        </div>

        <div id="new_area">
            <span id="new_area_header" ><img id="new_area_banner" src="images/new/banner.png" /><img id="new_area_txt" src="images/new/new_txt.png" /> </span>

            <div id="new_area_video_list" class="flexslider">
                <?php echo $hot1_html; ?>
            </div>
            <div style="position:absolute; width:20px; left:0px; top: 50px; height:100%;" >
                <img src="images/new_left_go.png" id="new_prev" onClick='$("#new_area_video_list").flexslider("prev"); ' style="position:absolute;left:50%; top:50%; margin: -10px 0 0 -10px; width:20px; height:20px;visibility:hidden" />
            </div>
            <div style="position:absolute; width:20px; right:0px; top: 50px; height:100%;" >
                <img src="images/new_right_go.png" id="new_next" onClick='$("#new_area_video_list").flexslider("next"); ' style="position:absolute;left:50%; top:50%; margin: -10px 0 0 -10px; width:20px; height:20px;" />
            </div>
        </div>
        <div id="hot_area">
            <span id="hot_area_header" ><img src="images/hot/hot_txt.png"/></span>
            <div id="hot_area_video_list" class="flexslider">
                <?php echo $hot2_html; ?>
            </div>
            <div style="position:absolute; width:5%; left:0px; top: 50px; height:100%;" >
                <img src="images/new_left_go.png" id="hot_prev" onClick='$("#hot_area_video_list").flexslider("prev"); ' style="position:absolute;left:50%; top:50%; margin: -10px 0 0 -10px; width:20px; height:20px;visibility:hidden" />
            </div>
            <div style="position:absolute; width:5%; right:0px; top: 50px; height:100%;" >
                <img src="images/new_right_go.png" id="hot_next" onClick='$("#hot_area_video_list").flexslider("next"); ' style="position:absolute;left:50%; top:50%; margin: -10px 0 0 -10px; width:20px; height:20px;" />
            </div>
        </div>

    </div>
</body>
    <script type="text/javascript">
        var newCount = 0;
        var hotCount = 0;
        var buttonArray=[];
        var id=[];
        var __URL_PREFIX__='<?php echo __URL_PREFIX__; ?>';
        var banners=[<?php echo join(',', $banner_array); ?>];
        var banner_video=[<?php echo join(',', $banner_video); ?>];
        var rtime;
        var timeout = false;
        var delta = 200;
        <?php echo $js_script; ?>

        $(window).load(function() {

            $('img').bind('contextmenu', function(e) {
                return false;
            });


            makeHighlightArea();
            adjustHighlightArea();

//            $(window).resize(function(){
//                  adjustHighlightArea();
//            });

            $.each(video_banner, function(key, id){
                if (typeof(parent.onPlay) == "function") {
                    $('#banner_'+id).click(function() {
                        parent.onPlay(0, __URL_PREFIX__+'/DATA/video/banner/'+id+'.mp4');
                    });
                }
            });
            adjustFlex();

            window.onresize = function() {
                adjustFlex();
                rtime = new Date();
                if (timeout === false) {
                    timeout = true;
                    setTimeout(resizeEnd, delta);
                }
            };
        });

        function resizeEnd() {
            if (new Date() - rtime < delta) {
                setTimeout(resizeEnd, delta);
            } else {
                timeout = false;
                $('.coverflow').roundabout("stopAutoplay");
                $('.coverflow').roundabout("destroy");
                var target = $('#container');
                target.before('<div id="container_temp" class="flexslider"></div>');
                target.remove();
                $('#container_temp').attr('id', 'container');
                makeHighlightArea();
            }
        }

        function adjustFlex() {
            var currentNew;
            var currentHot;
            var whRate;
            whRate = (window.innerWidth / window.innerHeight);
//            console.log(whRate);

//            if (window.innerHeight < 600) {
//                return;
//            }

            if (whRate > 1.8 && whRate <= 2.5) {
                currentNew = 3;
            } else if (whRate > 2.5 && whRate <= 3.0) {
                currentNew = 4;
            } else if (whRate > 3.0) {
                currentNew = 5;
            } else {
                currentNew = 2;
            }

            if (whRate > 1.34 && whRate <= 1.45) {
                currentHot = 5;
            } else if (whRate > 1.45) {
                currentHot = 6;
            } else {
                currentHot = 4;
            }

            if (currentNew != newCount) {
                resetFlex("new_area_video_list");
                makePageOutput(hot1, currentNew, "new_area_video_list_temp");
                $('#new_area_video_list_temp').attr('id', 'new_area_video_list');
                prepareNewArea('new_area_video_list', currentNew);
                newCount = currentNew;
            }

            if (currentHot != hotCount) {
                resetFlex("hot_area_video_list");
                makePageOutput(hot2, currentHot, "hot_area_video_list_temp");
                $('#hot_area_video_list_temp').attr('id', 'hot_area_video_list');
                prepareHotArea('hot_area_video_list', currentHot);
                hotCount = currentHot;
            }
        }

        function makePageOutput(data, perPage, divId) {
            var output = "<ul class='slides'>";
            var nVideos = data.length;
            var item_width = Math.floor(95.0 / perPage);
            var item_width_relative = item_width + "%";
            var item_height_relative = "90%";

            var remain = nVideos;
            var i = 0;
            while (remain > 0) {

                output += '<li>';
                var nItems = (remain > perPage) ? perPage : remain;
                output += '<div style="margin-left:auto; margin-right:auto;;height:97%; width:94%;border:solid 0px red">';

                for (var j = 0; j < nItems;j ++) {
                    var id = data[i++];
                    var download_mark = "<img src='images/downloads.png' class='download_" + id + "' style='width:20%; height:15%; position:absolute; right:5px; top:-5px'/>";
                    var cell = '<div style="position:relative; margin: 15px 1px; float:left; width: ' + item_width_relative + '; height: ' + item_height_relative + ' ; border: solid 0px red;">' + download_mark + '<img class="' + divId + '_img" style="display:block;width:auto;height:100%" src="DATA/images/cover150/' + id + '.png" onClick="onCoverClicked(' + id + ');"/></div>';
                    output += cell;
                }
                output += "<div style='width:100%; display:inline-block; font-size:0; line-height:0'> </div></div></li>";

                remain -= nItems;
            }

            output += "</ul>";
            $("#" + divId).html(output);
        }

        function resetFlex(id) {
            var target = $('#' + id);
            target.before('<div id="' +id + '_temp" class="flexslider"></div>');
            target.remove();
        }

        function prepareNewArea(newId, newCount) {
            $.each(hot1, function(key, id){
                if (typeof(parent.ifDownloaded) == "function")
                    if (parent.ifDownloaded(id)!=-1) {
                        $('.download_'+id).css("display", "block");
                    } else {
                        $('.download_'+id).css("display", "none");
                    }
            });

            $("#" + newId).flexslider({
                animation: "slide",
                animationLoop: false,

                slideshow: false,
                before: function(slider){
                    if (slider.animatingTo == 0) {
                        $("#new_prev").css("visibility", "hidden");
                    } else {
                        $("#new_prev").css("visibility", "visible");
                    }
                    if (slider.animatingTo == slider.count-1) {
                        $("#new_next").css("visibility", "hidden");
                    } else {
                        $("#new_next").css("visibility", "visible");
                    }
                },
                controlNav: false,
                multipleKeyboard: false,
                directionNav: false
            });
            $("#new_prev").css("visibility", "hidden");
            if (hot1.length <= newCount) {
                $("#new_next").css("visibility", "hidden");
            } else {
                $("#new_next").css("visibility", "visible");
            }
        }

        function prepareHotArea(hotId, hotCount){
            $.each(hot2, function(key, id){
               if (typeof(parent.ifDownloaded) == "function")
                if (parent.ifDownloaded(id)!=-1) {
                    $('.download_'+id).css("display", "block");
                } else {
                    $('.download_'+id).css("display", "none");
                }
            });

            $("#" + hotId).flexslider({
                animation: "slide",
                animationLoop: false,

                slideshow: false,
                before: function(slider){
                     if (slider.animatingTo == 0) {
                         $("#hot_prev").css("visibility", "hidden");
                     } else {
                         $("#hot_prev").css("visibility", "visible");
                     }
                     if (slider.animatingTo == slider.count-1) {
                         $("#hot_next").css("visibility", "hidden");
                     } else {
                         $("#hot_next").css("visibility", "visible");
                     }
                },
                controlNav: false,
                multipleKeyboard: false,
                directionNav: false
             });
             $("#hot_prev").css("visibility", "hidden");
             if (hot2.length <= hotCount) {
                 $("#hot_next").css("visibility", "hidden");
             } else {
                 $("#hot_next").css("visibility", "visible");
             }
        }


        function getPageWidth() {
            var h = $("#container").height();
            var w = $("#container").width()*2;

            if (w * 9 / 16 < h) {
                h = Math.round(w * 9 / 16);
                w = Math.round(w * .95);
            } else {
                w = Math.round(h * 16*.95 / 9);
            }

            return w;
        }

        function adjustHighlightArea() {

return;
            w = getPageWidth();
            h = w*9/16;

            $('ul.coverflow li').height(h);
            $('.roundabout-holder').height(h);

            $("#carousel").css('width', w + 'px');
            $("#carousel").css('height', h + 'px');
//            $("#carousel img").css('width', w + 'px');
        }

        function makeHighlightArea() {
//            if ($("#container .flexslider").length>0)  $("#container .flexslider").remove();

            var html_content ='<div id="carousel"> <ul class="coverflow">';
            $.each(banners, function(key, val) {
                html_content += '<li>' + val+'</li>';
            });
            html_content += '</ul></div>';

            html_content += '<img src="images/film-left-go.png" class="prev" />';
            html_content += '<img src="images/film-right-go.png" class="next" />';
            //html_content += '<img src="images/play.png" class="play_button" />';
            html_content += '<div class="play_button" ></div>';

            $("#container").html(html_content);

            $('.coverflow').roundabout({
                minScale: 0.85,
                autoplay: true,
                btnPrev: '.prev',
                btnNext: '.next',
                autoplayDuration: 3000,
                autoplayPauseOnHover: true,
                responsive:true
//            }).on('animationStart', function() {
//                $(".play_button").css("display", "none");

            //}).on('animationEnd', function() {
            }).on('childrenUpdated', function() {
                $(".play_button").css("display", "none");
                id = banner_video[$('.coverflow').roundabout("getChildInFocus")];
                showPlayButton(id);
            });
            showPlayButton(banner_video[$('.coverflow').roundabout("getChildInFocus")]);

        }

        function showPlayButton(id) {
                if (id >0) {
                    $(".play_button").css("display", "block");

                    if (typeof(parent.onPlay) == "function") {
                        $('.play_button').click(function() {
                            setAutoPlay(false);
                            parent.onPlay(0, __URL_PREFIX__+'/DATA/video/banner/'+id+'.mp4');
                        });
                    }
                }
        }
        function setAutoPlay(s) {
            switch(s) {
                case true:
                    $('.coverflow').roundabout("startAutoplay");
                    break;
                case false:
                    $('.coverflow').roundabout("stopAutoplay");
                    break;
            }
        }

        function onCoverClicked(id) {
            location.href= 'y3_video.php?id='+id;

        }


    </script>

</html>
