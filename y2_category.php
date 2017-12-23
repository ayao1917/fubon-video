<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_category.php');
include_once('inc/class_video.php');

$category_db = new Category();
$video_db = new Video();

$category_db->init();
$video_db->init();

if (!isset($_REQUEST['category_id'])) die('參數錯誤');
$sorting = (isset($_REQUEST['sorting']))?$_REQUEST['sorting']:'0';


$id = $_REQUEST['category_id'];

$order = "ORDER BY STICKY,PUBLISH_DATE DESC";
switch($sorting) {
    case '0':$order = 'ORDER BY STICKY,PUBLISH_DATE DESC'; break;
    case '1':$order = 'ORDER BY PUBLISH_DATE DESC, STICKY '; break;
    case '2':$order = 'ORDER BY TITLE'; break;
}    
//sort by default
$temp = $video_db->loadVideoFromCategory($id, 1, '', $order);
$b = array();
foreach ($temp as $item) {
    array_push($b, $item['SERIAL_NUMBER']);
    
}
$count=count($b);
$video_list = join(",", $b);

//debug($_SESSION);
ulog($_SESSION['user_id'], "category", $id, "-");

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
    
    <script src="js/jquery.flexslider-min.js"></script>
    
    
    <style>
.flex-control-nav {
  bottom: 10px;
  position: absolute;
  text-align: center;
  z-index: 1000;
}

.flex-direction-nav a  { 
    text-decoration:none; 
}
        
        #sorting_panel {

/*
            background:url(images/top_list_bg.png) repeat-x; 
*/
/*
            background:url(images/index/index_banner.png) no-repeat;
            background-size: 100% Auto; 
*/


            position:absolute; 
            height:61px; left:5px; right:5px; top: 5px; 
            border:solid 2px gray; 
            border-radius: 10px;

      /*      box-shadow: 5px 5px 9px #888; text-align:center;  */
        }
        
        #sorting_panel ul {
            margin:0 auto;
            width: 570px;
            position: absolute; top:0; left: 10px; margin:0px auto; list-style-type: none;
        }
        #sorting_panel li {
            float:left; margin: 7px 5px; 
        }
        #sorting_panel img {
            position:relative;
            height: 45px;
            margin:0;
            cursor:pointer; 
        }
        
        .selected {
/*            border-bottom: solid 10px #2fccf0;   */
        }
        
        #container {
            position:absolute; left:5px; right:5px; bottom: 5px; top:75px ;margin:0 0;  border: solid 0px blue;
        }
        #video_list {
            position:absolute; left:0px; right:0px; bottom: 0; top:0px ;margin:0 0;  border: solid 0px green;
        }
        
        #video_list>li{
            border: solid 0px red;
        }
        
        
        #video_list img {

            display:inline-block;
            width: 120px;
         /*   margin: 5px 20px;*/
            cursor:pointer;
        }

        .slideshow {
            margin-left: 50px;
            margin-right:50px;
        }    
    
    </style>
    <script type="text/javascript">
        var buttonArray = [];
        var pageCount = 0;
  
        $(document).ready(function() {
            $('img').bind('contextmenu', function(e) {
                return false;
            }); 

//            $("#sorting_panel li img").eq(<?php echo $sorting; ?>).addClass("selected");
            pid = <?php echo $sorting;?>+1;
            $("#sorting_panel li img").eq(<?php echo $sorting; ?>).attr("src", "images/index/index_icon0"+pid+"_press.png");

            setUpPage();
            
            $(window).resize(function() {
                setUpPage();
            });
        });

        function setUpPage() {
            var currentPage;
            var rate = 177 / 250;
            var currentWidth = $("#container").height() * 0.45 * rate;
            currentPage = Math.round($("#container").width() * 0.95 / currentWidth) * 2;
//            alert($("#container").width() + " " + $("#container").height() + " " + $(".test123").first().width() + " " + $(".test123").first().height());

//            var whRate = (window.innerWidth / window.innerHeight);
//            if (whRate > 1.5 && whRate <= 1.7) {
//                currentPage = 10;
//            } else if (whRate > 1.7 && whRate <= 2.0) {
//                currentPage = 12;
//            } else if (whRate > 2.0 && whRate <= 2.4) {
//                currentPage = 14;
//            } else if (whRate > 2.4) {
//                currentPage = 16;
//            } else {
//                currentPage = 8;
//            }

            if (currentPage != pageCount) {
                layoutVideoList(currentPage);
                pageCount = currentPage;
            }
        }
        
        function layoutVideoList(perPage) {
            var nVideos=<?php echo $count; ?>;
            var video_array = [<?php echo $video_list; ?> ];
            var width = 90 / (perPage / 2);
            var height = (width / 18) * 40;

//            var perPage = 10;

            var html_content = '';

            var remain = nVideos;
            var i = 0;
            while (remain>0) {
                html_content += '<li>';
                var count = (remain>perPage)? perPage:remain;

                html_content += '<div style="margin-left:auto; margin-right:auto;;height:97%; width:97%;">';

                for (var j = 0;j < count;j ++) {
                    var id = video_array[i++];
                    var mark = (parent.ifDownloaded(id)!=-1)?'<img src="images/downloads.png" style="width:20%; height:15%; position:absolute; right:5px; top:-5px" />':'';
                    html_content += '<div style="position:relative; margin: 10px 5px; float:left; width: ' + width + '%; height: 45%; border: solid 0px red;">' + mark + ' <img style="display:block;width:100%;height:100%" src="DATA/images/cover/' + id + '.png" onClick="onCoverClicked(' + id + ');"/></div>';
                }
                html_content += "</div></li>";

                remain -= count;
            }
        
            showVideoList(html_content);
        }
        
        function onCoverClicked(id) {
            location.href= 'y3_video.php?id='+id;  
            
        }
        
        
        function showVideoList(html_content) {
            
            $('.slides').remove();
            $('.flex-control-nav').remove();
            
            $('#container').append('<div id="video_list"  class="flexslider"  ><ul class="slides">' + html_content + '</ul></div>');
            
            $('.flexslider').flexslider({
                animation: "slide",
                animationLoop: true,
                keyboard: false,
/*
                before: function(slider){
                     if (slider.animatingTo == 0) {
                         $(".flex_prev").css("display", "none");
                     } else {
                         $(".flex_prev").css("display", "block");
                     }
                     if (slider.animatingTo == slider.count-1) {
                         $("#flex_next").css("display", "none");
                     } else {
                         $("#flex_next").css("display", "block");
                     }
                },
*/
                itemWidth: "100%",
                itemMargin: 20,
                controlNav: true
              }).flexslider("stop");
        }
        

        
        function onSortClicked(sorting) {
            location.href="y2_category.php?category_id=<?php echo $id ;?>&sorting="+sorting;
            
        }
        
    </script>
</head>
<body>
    <div id="content" >

        
        <div id="sorting_panel"> 
            <img src="images/index/index_icon.png" style="position:absolute; left:3px; top:7px;" />
            <ul>
                <li><img id="sort_hot" src="images/index/index_icon01_normal.png" onClick="onSortClicked(0);" /></li>
                <li><img id="sort_date" src="images/index/index_icon02_normal.png" onClick="onSortClicked(1);" /></li>
<!--
                <li><img id="sort_name" src="images/sort_name.png" onClick="onSortClicked(2);"/></li>
-->
            </ul>       
        </div>
        
        <div id="container"> 
        
        </div>        
        
    </div>    
</body>
</html>
