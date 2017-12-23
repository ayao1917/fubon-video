<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_tag.php');
include_once('inc/class_video.php');

$tag_db = new Tag();
$video_db = new Video();

$tag_db->init();
$video_db->init();

if (!isset($_REQUEST['id'])) die('參數錯誤');
$sorting = (isset($_REQUEST['sorting']))?$_REQUEST['sorting']:'0';


$id = $_REQUEST['id'];

$order = "ORDER BY STICKY,PUBLISH_DATE DESC";
switch($sorting) {
    case '0':$order = 'ORDER BY STICKY,PUBLISH_DATE DESC'; break;
    case '1':$order = 'ORDER BY PUBLISH_DATE DESC'; break;
    case '2':$order = 'ORDER BY TITLE'; break;
}    
//sort by default
$temp = $tag_db->loadPublishedVideo($id, $order);

$b = array();
foreach ($temp as $item) {
    array_push($b, $item['SERIAL_NUMBER']);
}
$count=count($b);
$video_list = join(",", $b);

ulog($_SESSION['user_id'], "tag", $id, "-");


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
        #serial_info {
            position:absolute; 
            bottom:52%; left:10px; right:10px; top: 60px; 
            border:solid 0px blue; 
            text-align:center;
            background:url(DATA/images/tag/<?php echo $id;?>_info.png) no-repeat center center;
            background-size: 100% auto;
        }

        #serial_header {
            position:absolute; 
            height:50px; left:10px; right:10px; top: 10px; 
            background-color: #1470CE;
        }
        #serial_icon {
            position:absolute; 
            height:40px; left:10px; top: 8px; 
        }
        #serial_txt {
            position:absolute; 
            height:30px; left:40px; top: 12px; 
        }
        #serial_info_img {
            margin-top:75px; 
            margin-left: auto;
            margin-right: auto;
            display:block;
            max-width:98%; max-height:98%;
        }

        
        #sorting_panel {
            background:url(images/top_list_bg.png) repeat-x; 
            position:absolute; 
            height:161px; left:0; right:0; top: -85px; 
             border:solid 0px red; 
      /*      box-shadow: 5px 5px 9px #888; text-align:center;  */
        }
        
        #sorting_panel ul {
            margin:0 auto;
            width: 570px;
            position: relative; margin:85px auto; list-style-type: none;
        }
        #sorting_panel li {
            float:left; margin: 1px 10px;
        }
        #sorting_panel img {
            position:relative;
            width: 130px;
            margin:0;
            cursor:pointer; 
        }
        
        .selected {
            border-bottom: solid 10px #2fccf0;   
        }
        
        #container {
            position:absolute; 
            height:330px; left:10px; right:10px; bottom: 5px; 
            border:solid 0px red; 
            text-align:center;
        }
        #video_list {
/*
            position:absolute; left:0px; right:0px; bottom: 0; top:0px ;margin:0 0;  border: solid 0px green;
*/
min-width: 720px;
/*max-width: 1000px;*/
height: 300px;
border: solid 0px red;
padding: 10px 20px;
position:relative; text-align:center;
margin: 10px auto;
        }
        
        #video_list>li{
            border: solid 0px red;
        }
        
        
        #video_list img {

/*
            display:inline-block;
            width: 120px;
            margin: 5px 20px;
*/
        }
.stretch {
    width: 100%;
    display: inline-block;
    font-size: 0;
    line-height: 0
}

        .slideshow {
            margin-left: 50px;
            margin-right:50px;
        }    

.flex-control-nav {
  bottom: 10px;
  position: absolute;
  text-align: center;
  z-index: 1000;
}

.flex-direction-nav a  {
    text-decoration:none;
}
    
    </style>
    <script type="text/javascript">
        var buttonArray=[];
        var pageCount = 0;
  
        $(document).ready(function() {
            
            $('img').bind('contextmenu', function(e) {
                return false;
            }); 

            $("#sorting_panel li").eq(<?php echo $sorting; ?>).addClass("selected");
            setUpPage();
            
            $(window).resize(function() {
                setUpPage();
            });
     

        });

        function setUpPage() {
            var currentPage;
            var whRate = (window.innerWidth / window.innerHeight);
            if (whRate > 1.3 && whRate <= 1.6) {
                currentPage = 6;
            } else if (whRate > 1.6 && whRate <= 1.9) {
                currentPage = 8;
            } else if (whRate > 1.9 && whRate <= 2.2) {
                currentPage = 10;
            } else if (whRate > 2.2) {
                currentPage = 12;
            } else {
                currentPage = 4;
            }

            if (currentPage != pageCount) {
                layoutVideoList(currentPage);
                pageCount = currentPage;
            }
        }
        
        function layoutVideoList(perPage) {
            var nVideos=<?php echo $count; ?>;
            var video_array = [<?php echo $video_list; ?> ];
            var cover_width=151;
            var cover_height=211;
            var ratio=1;
            cover_width *= .8;
            cover_height *= .8;
            
           // alert(cover_width+'x'+cover_height);
            
            var cover_margin_x = 20;
            var cover_margin_y = 5;
            
            var page_width=$("#container").width();
            var page_height=$("#container").height();
            
/*
            x = Math.floor(page_width/(cover_width*ratio+cover_margin_x*2));
            y = Math.floor(page_height/(cover_height*ratio+cover_margin_y*2));
            perPage = x*y;
            nPages = Math.floor((nVideos-1)/perPage+1);

            html_content='';

            remain=nVideos;
            i=0;
            while (remain>0) {

                html_content += '<li>';
                count =  (remain>perPage)? perPage:remain;

                for (j=0; j<count; j++) {
                    id = video_array[i];
                    html_content += '<img src="DATA/images/cover/' + id + '.png" onClick="onCoverClicked(' + id + ');"/>';
                    i++;
                }
                html_content += '</li>';
                remain -=count;
            }
*/
/*
            perPage = 4;
            nPages = Math.floor((nVideos-1)/perPage+1);

            html_content='';

            remain=nVideos;
            i=0;
            while (remain>0) {

                html_content += '<li>';
                count =  (remain>perPage)? perPage:remain;

                table = '<table style="table-layout:fixed; width:90%; margin-left:auto; margin-right:auto; border-spacing: 10px; border-collapse: separate; border: solid 0px red;"><tr>';

                for (j=0; j<count; j++) {
                    id = video_array[i];
                    table += '<td style="border:solid 0px red"><img style=" width:98%; height:90%;" src="DATA/images/cover/' + id + '.png" onClick="onCoverClicked(' + id + ');"/></td>';
                    i++;
                }
                for (j=count; (j%perPage)!=0; j++) {
                    table += '<td></td>';
                }


                html_content += table+'</tr></table></li>';
                remain -=count;
            }
*/

//            perPage = 4;
            nPages = Math.floor((nVideos-1)/perPage+1);

            html_content='';

            remain=nVideos;
            i=0;
            while (remain>0) {

                html_content += '<li style="padding:5px;">';
                count =  (remain>perPage)? perPage:remain;

                //html_content += '<div style="margin-left:auto; margin-right:auto;;height:97%; width:97%; border:solid 0px red;">';
                //html_content += '<div style="height: 250px;border: solid 1px red; text-align: justify;-ms-text-justify: distribute-all-lines; text-justify: distribute-all-lines; min-width: 720px;">';
//                ele_width = count*(($("#container").width()-120)/4);
//                html_content += '<div style="height: 250px; width: '+ ele_width + 'px; border: solid 0x red; text-align: justify;">';
                html_content += '<div style="height: 250px; border: solid 0x red; text-align: justify;">';

                for (j=0; j<count; j++) {
                    id = video_array[i++];
                    mark='';
//                    mark=(parent.ifDownloaded(id)!=-1)?'<img src="images/downloads.png" style="width:20%; height:15%; position:absolute; right:5px; top:-5px; border: solid 10px red;" />':'';

                    if (parent.ifDownloaded(id)!=-1)mark='<img src="images/downloads.png" style="position:absolute; width:31px; height:31px; right:5px; top: -5px; z-index:100;" />';
//                cell = '<div style="position:relative; margin: 10px 5px; float:left; width: 23%; height: 80%; border: solid 0px red;">' + mark +' <img style="width:100%; height:100%" src="DATA/images/cover/' + id + '.png" onClick="onCoverClicked(' + id + ');"/></div> ';
                //cell = '<div style="position:relative; display:inline-block; zoom:1; width: 177px; height: 250px; vertical-align:top; border: solid 0px red;">' + mark +' <img style="cursor:pointer" src="DATA/images/cover/' + id + '.png" onClick="onCoverClicked(' + id + ');"/></div> ';
                cell = '<div style="position:relative; display:inline-block; width: 177px; height: 250px; vertical-align:top; border: solid 0px red; background:url(DATA/images/cover/' +id + '.png) no-repeat center center; cursor:pointer; " onClick="onCoverClicked(' + id + ');">'+mark+ '</div> ';
                html_content+=cell;
                }
                for (j=count; j<perPage; j++) {
                cell = '<div style="position:relative; display:inline-block; width: 177px; height: 250px; vertical-align:top; border: solid 0px red;"> </div> ';
                html_content+=cell;
                }
                html_content +="<span class='stretch'> </span></div></li>";

                remain -=count;
            }
        
            showVideoList(html_content);
        }
        
        function onCoverClicked(id) {
            location.href= 'y3_video.php?id='+id;  
            
        }
        
        
        function showVideoList(html_content) {
            
            $('.slides').remove();
            
            $('#container').html('<div id="video_list"  class="flexslider"  ><ul class="slides">' + html_content + '</ul></div>');
            
            $('.flexslider').flexslider({
                animation: "slide",
                animationLoop: false,
                itemWidth: "100%",
                itemMargin: 20,
                controlNav: true
              }).flexslider("stop");
        }
        

        
        function onSortClicked(sorting) {
            location.href="y2_tag.php?tag_id=<?php echo $id ;?>&sorting="+sorting;
            
        }
        
    </script>
</head>
<body>
    <div id="content" >

        
<!--
        <div id="sorting_panel"> 
            <ul>
                <li><img id="sort_hot" src="images/sort_hot.png" onClick="onSortClicked(0);" /></li>
                <li><img id="sort_date" src="images/sort_date.png" onClick="onSortClicked(1);" /></li>
                <li><img id="sort_name" src="images/sort_name.png" onClick="onSortClicked(2);"/></li>
            </ul>       
        </div>
-->
            <span id="serial_header"> 
                <img id="serial_icon" src="images/serial/serial_icon.png" />
                <img id="serial_txt" src="images/serial/serial_txt.png" />
            </span>
        <div id="serial_info">
<!--
            <img id="serial_info_img" src="DATA/images/tag/<?php echo $id;?>_info.png" />
-->
        </div>
<!--
        <div id="serial_info">
            <span id="serial_header"> 
                <img id="serial_icon" src="images/serial/serial_icon.png" />
                <img id="serial_txt" src="images/serial/serial_txt.png" />
            </span>
            <img id="serial_info_img" src="DATA/images/tag/<?php echo $id;?>_info.png" />
        </div>
-->
        
        <div id="container"> 
        
        </div>        
        
    </div>    
</body>
</html>
