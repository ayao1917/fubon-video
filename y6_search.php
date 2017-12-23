<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_video.php');

$video_db = new Video();

$video_db->init();

if (!isset($_REQUEST['query'])) die('參數錯誤');

$query = $_REQUEST['query'];

$temp = $video_db->search($query);
$b = array();
foreach ($temp as $item) {
    array_push($b, $item['SERIAL_NUMBER']);
    
}
$count=count($b);
$video_list = join(",", $b);


ulog($_SESSION['user_id'], "search", "\"$query\"", $count);
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
            background-color:#1470CE;

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
        var buttonArray=[];
  
        $(document).ready(function() {

            $('img').bind('contextmenu', function(e) {
                return false;
            }); 
            

//            $("#sorting_panel li img").eq(<?php echo $sorting; ?>).addClass("selected");
//            pid = <?php echo $sorting;?>+1;
//            $("#sorting_panel li img").eq(<?php echo $sorting; ?>).attr("src", "images/index/index_icon0"+pid+"_press.png");

            layoutVideoList();
            
            $(window).resize(function() {
                layoutVideoList();
            });
     

        });
        
        function layoutVideoList() {
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
            perPage = 10;
            nPages = Math.floor((nVideos-1)/perPage+1);

            html_content='';

            remain=nVideos;
            i=0;
            while (remain>0) {
                
                html_content += '<li>';
                count =  (remain>perPage)? perPage:remain;

                table = '<table style="table-layout:fixed; width:98%;  border-spacing: 0px; border-collapse: separate; border: solid 0px red;">';
                
                for (j=0; j<count; j++) {
                    if (j%5==0) { table += '<tr>'; }
                    id = video_array[i];
                    table += '<td style="border:solid 0px red"><img style=" width:98%; height:90%;" src="DATA/images/cover/' + id + '.png" onClick="onCoverClicked(' + id + ');"/></td>';
                    i++;
                    if ((j+1)%5==0) { table += '</tr>'; }
                }
                for (j=count; (j%perPage)!=0; j++) {
                    if (j%5==0) { table += '<tr>'; }

                    table += '<td></td>';
                    if ((j+1)%5==0) { table += '</tr>'; }

                } 


                html_content += table+'</table></li>';
                remain -=count;
            }
*/
            perPage = 10;
            nPages = Math.floor((nVideos-1)/perPage+1);

            html_content='';

            remain=nVideos;
            i=0;
            while (remain>0) {
                
                html_content += '<li>';
                count =  (remain>perPage)? perPage:remain;

                html_content += '<div style="margin-left:auto; margin-right:auto;;height:97%; width:97%;">';

                for (j=0; j<count; j++) {
                    id = video_array[i++];
                    mark=(parent.ifDownloaded(id)!=-1)?'<img src="images/downloads.png" style="width:20%; height:15%; position:absolute; right:5px; top:-5px" />':'';
                cell = '<div style="position:relative; margin: 10px 5px; float:left; width: 18%; height: 40%; border: solid 0px red;">' + mark +' <img style="width:100%; height:100%" src="DATA/images/cover/' + id + '.png" onClick="onCoverClicked(' + id + ');"/></div>';
                html_content+=cell;
                }
                html_content +="</div></li>";

                remain -=count;
            }
        
            showVideoList(html_content);
        }
        
        function onCoverClicked(id) {
            location.href= 'y3_video.php?id='+id;  
            
        }
        
        
        function showVideoList(html_content) {
            
            $('.slides').remove();
            
            $('#container').append('<div id="video_list"  class="flexslider"  ><ul class="slides">' + html_content + '</ul></div>');
            
            $('.flexslider').flexslider({
                animation: "slide",
                animationLoop: false,
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

        
<!--
        <div id="sorting_panel1"> 
            <img src="images/index/index_icon.png" style="position:absolute; left:3px; top:7px;" />
            <ul>
                <li><img id="sort_hot" src="images/index/index_icon01_normal.png" onClick="onSortClicked(0);" /></li>
                <li><img id="sort_date" src="images/index/index_icon02_normal.png" onClick="onSortClicked(1);" /></li>
            </ul>       
        </div>
-->
<p style="position:absolute; left:5px; right:5px; margin: 0; padding: 10px 50px; top: 5px; background-color:#1470CE; font-size:1.5em; color:#FFF;">搜尋結果：搜尋 <?php echo $query; ?> 共找到 <?php echo $count; ?> 筆資料 </p>
<p style="position:absolute; left:5px; right:5px; margin: 0; padding: 10px 50px; top: 5px; background-color:#239F98; font-size:1.5em; color:#FFF;">搜尋結果：搜尋 <?php echo $query; ?> 共找到 <?php echo $count; ?> 筆資料 </p>
<img src="images/search.png" style="position:absolute; left:10px; top:15px; width:32px; height:32px;" />

        
        <div id="container"> 
        
        </div>        
        
    </div>    
</body>
</html>
