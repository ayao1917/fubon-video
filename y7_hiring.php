<?php
include_once('inc/config.php');
include_once('inc/utils.php');

$id = 1;

//$video_list = "1,2";
$video_list = "1";
$count=2;

ulog($_SESSION['user_id'], "hiring", $id, "-");


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
        #hiring_info {
            position:absolute; 
            bottom:52%; left:10px; right:10px; top: 60px; 
            border:solid 0px blue; 
            text-align:center;
            background:url(DATA/images/hiring/<?php echo $id;?>_info.png) no-repeat center center;
            background-size: 100% auto;
        }

        #hiring_header {
            position:absolute; 
            height:50px; left:10px; right:10px; top: 10px; 
            background-color: #C1490A;
        }
        #hiring_icon {
            position:absolute; 
            height:40px; left:10px; top: 8px; 
        }
        #hiring_txt {
            position:absolute; 
            height:30px; left:40px; top: 12px; 
        }
        #hiring_info_img {
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
max-width: 1000px;
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
  
        $(document).ready(function() {
            
            $('img').bind('contextmenu', function(e) {
                return false;
            }); 

            $("#sorting_panel li").eq(<?php echo $sorting; ?>).addClass("selected");
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
            
            perPage = 4;
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
                cell = '<div style="position:relative; display:inline-block; width: 177px; height: 250px; vertical-align:top; border: solid 0px red; background:url(DATA/images/hiring/cover/' +id + '.png) no-repeat center center; cursor:pointer; " onClick="onCoverClicked(' + id + ');">'+mark+ '</div> ';
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
            location.href= 'y8_hiring_detail.php?id='+id;  
            
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
            <span id="hiring_header"> 
                <img id="hiring_icon" src="images/hiring/hiring_icon.png" />
                <img id="hiring_txt" src="images/hiring/hiring_txt.png" />
            </span>
        <div id="hiring_info">
        </div>
        
        <div id="container"> 
        
        </div>        
        
    </div>    
</body>
</html>
