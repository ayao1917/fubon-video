<?php

if (!isset($_REQUEST['id'])) die("參數錯誤");
$video_id=$_REQUEST['id'];

include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_category.php');
include_once('inc/class_video.php');
include_once('inc/class_book.php');

$category_db = new Category();
$video_db = new Video();
$book_db = new Book();

$video_db->init();
$category_db->init();
$book_db->init();

$rows = $video_db->loadVideo($video_id);

$related_video_array = $video_db->loadRelatedVideo($video_id);

$info = $category_db->load($rows["CATEGORY"]);

$related_video_html = "";

$b = array();
foreach ($related_video_array as $item) {
    $id = $item["SERIAL_NUMBER"];

    array_push($b, $id);
    //$related_video_html .="<li><img src='DATA/images/cover/$id.png' onClick='onCoverClicked($id)' /> </li>";
    $related_video_html .="<li><div style='background:url(DATA/images/cover150/$id.png) no-repeat center center;background-size: contain; border: solid 0px red;width:120px; height:170px;' onClick='onCoverClicked($id)' > </div>  </li>";
}
$video_list = join(",", $b);


$publish_date = "";
if ($rows["PUBLISH_DATE"]!="") {
    $year = date("Y", strtotime($rows["PUBLISH_DATE"]))-1911;
    $month = date("m", strtotime($rows["PUBLISH_DATE"]));
    $day = date("d", strtotime($rows["PUBLISH_DATE"]));
    $publish_date = $year."年".$month."月".$day."日";
}

$tag_name = $video_db->getTagName($video_id);

$tag_info = "";
/*
if ($tag_name != null) {
    $tag_info = "<p>系列專題：$tag_name</p>";
}
*/


$duration = "";

$data = explode(":", $rows["VIDEO_LENGTH"]);
if (count($data)==3) {

    if ($data[0]!="00") $duration .= $data[0]."時";
    if ($data[1]!="00") $duration .= $data[1]."分";
    $sec = explode(".", $data[2]);
    if ((count($sec)>1) && ($sec[0]!="00")) $duration .= $sec[0]."秒";
}

//$metadata = "<img src='DATA/images/cover/". $rows["SERIAL_NUMBER"] .".png' style=' margin-top: 10px;'/> &nbsp;<br/> <br/><p>".$rows["TITLE"]."</span></p><br/><p>序號：".$rows["SERIAL_NUMBER"]."</p> <p>上映日期：".$publish_date."</p> <p>影片類別：".$info[0]["TITLE"]."</p>". $tag_info . " <p>影片長度：".$duration."</p> <p>影片簡介：</p><p>".$rows["DETAIL"]."</p>";
$metadata = "<img src='DATA/images/cover/". $rows["SERIAL_NUMBER"] .".png' style=' margin-top: 10px;'/> &nbsp;<br/> <br/><p>".$rows["TITLE"]."</span></p><br/><p>上映日期：".$publish_date."</p> <p>影片類別：".$info[0]["TITLE"]."</p>". $tag_info . " <p>影片長度：".$duration."</p> <p>影片簡介：</p><pre>".$rows["DETAIL"]."</pre>";

//$bookInfo = json_decode(file_get_contents('config/ebooks.json'), true);
//$video_books = $bookInfo["videoBooks"];
$video_books = $book_db->getVideoBooks();

//$video_books_for_all = array (
//    104062201 => array("【特色單位-陌生開發】教戰手冊", 104062201),
//    105053003 => array("【產險理賠小幫手-強制汽車責任保險】教戰手冊", 105053003),
//    105110101 => array("【產險理賠小幫手-汽機車竊盜險】教戰手冊", 105110102),
//    106032901 => array("【退休規劃議題】教戰手冊", 106032902),
//    106040701 => array("【XEU XLT】教戰手冊", 106040702)
//);
$video_books_for_all = $book_db->getTechBooks();
$video_books_pres = $book_db->getPresBooks();

if (array_key_exists($video_id, $video_books_for_all)) {
    $id1 = $video_books_for_all[$video_id][1];
    $url1 = __URL_PREFIX__."ajax/main/book.php?id=$id1&sid=".session_id();
    $metadata .= "<br/>";
    $metadata .= '<img src="images/film_index/film_index_techbook.png" style="width:107px ; cursor:pointer;" onclick="parent.openURL(\'' . $url1. '\');" />';
}

if (array_key_exists($video_id, $video_books_pres)) {
    $id1 = $video_books_pres[$video_id][1];
    $url1 = __URL_PREFIX__."ajax/main/book.php?id=$id1&sid=".session_id();
    $metadata .= "<br/>";
    $metadata .= '<img src="images/film_index/film_index_download.png" style="width:107px ; cursor:pointer;" onclick="parent.openURL(\'' . $url1. '\');" />';
}

if (array_key_exists($video_id, $video_books)) {

    $allow_rank=array('99', 'STF','AVP','SRM','ARM','VRM','DM','UM','AM','SP','CCP','SCP','CP','CCM','SCM','CM','CUM','CDM','CSM','CS','CFM');

    $id1 = $video_books[$video_id][1];
    $id2 = $video_books[$video_id][2];

    $metadata .= "<br/>";
    //$url1 = __URL_PREFIX__."ajax/main/book.php?id=103031201&sid=".session_id();
    //$url2 = __URL_PREFIX__."ajax/main/book.php?id=103031202&sid=".session_id();
    $url1 = __URL_PREFIX__."ajax/main/book.php?id=$id1&sid=".session_id();
    $url2 = __URL_PREFIX__."ajax/main/book.php?id=$id2&sid=".session_id();
    $metadata .= '<img src="images/film_index/film_index_wordbook.png" style="width:107px ; cursor:pointer;" onclick="parent.openURL(\'' . $url1. '\');" />';

    if ( in_array($USER_RANK , $allow_rank)) {
        $metadata .= '<img src="images/film_index/film_index_guidebook.png" style="width:107px ; cursor:pointer;" onclick="parent.openURL(\'' . $url2. '\');" />';
    }

}



$video_data_hd = video_info($video_id, "720p");
$video_data_sd = video_info($video_id, "360p");


$video_js = "var video_data_hd = $video_data_hd;\n";
$video_js .= "var video_data_sd = $video_data_sd;\n";

ulog($_SESSION['user_id'], "video", $video_id, "-");

function video_info($video_id, $resolution) {
    $size=0;
    $video_file = __DATA_PATH__."/video/$resolution/$video_id.mp4";
    $video_url = __URL_PREFIX__.__DATA_URL__."/video/$resolution/$video_id.mp4";
    if (file_exists($video_file)) {
        $size = filesize($video_file);
    } else {
        $video_file = __DATA_PATH__."/video/$resolution/$video_id.mp4";
        $video_url = __URL_PREFIX__.__DATA_URL__."/video/$resolution/$video_id.mp4";
        if (file_exists($video_file)) {
            $size = filesize($video_file);
        }
    }

    return  "{id:$video_id, url:'$video_url',filesize:$size}";

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <title>富邦新視界</title>  
    
    <link href="css/flexslider.css" type="text/css" rel="Stylesheet" />
    <link rel="stylesheet" href="css/ui/jquery-ui.min.css"> 

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui-all.min.js"></script>
    <script src="js/ios6fix.js"></script>    
    <script src="js/swfobject.js"></script>
    <script src="js/jquery.strobemediaplayback.js"></script>
    
    <script src="js/jquery.flexslider-min.js"></script>
    
    
    <style>
        ::-webkit-scrollbar {
                width: 12px;
            }
             
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
            border-radius: 10px;
        }
         
        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
        }

        #content {
            background-color:#CECECE;
            height: 100%;
            position: absolute;
            left:0; right:0; top:0; bottom:0;

        }
        #metadata {
            background-color:#EDEDED;
            position:absolute; left:0; width: 297px;top:0; bottom:0;overflow:auto; text-align:center;   
        }
        
        #detail span { 

            font-weight:normal;
            font-family:"新細明體",Arial,SimHei, KaiTi, Microsoft JhengHei, DFKai-sb, PMingLiU, MingLiU, serif;
            color: #555;
        }
        #detail p, #detail>div>div {
            text-align:left;
            font-weight:normal;
            -webkit-font-smoothing: subpixel-antialiased ;
            -webkit-font-smoothing: antialiased ;
            margin: 4px 20px;
            color: #555;
            font-family: "新細明體",Arial,"HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 
            text-align: justify;
            text-justify:inter-ideograph;
            filter: chroma( color=#CCCCCC);
        }

        #detail pre {
            text-align:left;
            font-weight:normal;
            -webkit-font-smoothing: subpixel-antialiased ;
            -webkit-font-smoothing: antialiased ;
            margin: 4px 20px;
            color: #555;
            font-family: "新細明體",Arial,"HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            text-align: justify;
            text-justify:inter-ideograph;
            filter: chroma( color=#CCCCCC);
            white-space: pre-wrap;
            text-indent: 2em;
        }
        
        #video_container {
            position:absolute; left: 300px; right:0; top:0px; bottom:260px;text-align:center;
            background-color:#EDEDED;
        }
        
        
        #video_buttons {
            position:absolute; 
            height:40px; left:300px; right:0; bottom: 215px;
            text-align:center;  
            background-color:#EDEDED;
        }
        
        #video_buttons ul { 
            margin:0 auto;
            width: 400px;
            
            position: relative; margin:2px auto; list-style-type: none;
        }
        #video_buttons li {
            float:left; margin: 1px 10px;
        }
        #video_buttons img {
            position:relative;
            height: 35px;
            cursor:pointer;
        }
        
        #relate_list {
            position:absolute; left:300px; right:0; bottom: 0; height: 170px;
            background-color:#EDEDED;
        }
        #relate_txt {
            position:absolute; left:300px; right:0; bottom: 180px; height: 30px; 
            background-color:#1D8DCC;
        }
        #relate_txt>img {
            position:absolute;
            left:7px; top:5px;
            height: 20px;
        }
         
        .flexslider {
            height: 180px;
            margin: 0px 0px;
            cursor:pointer;            
        }
        
        .flexslider li {
            margin: 0 3px;
        }
        
        /*ul.slides {*/
            /*overflow: hidden;*/
        /*}*/
      
/*
        .slides img {
            height: 180px;
            margin: 5px 0px;
        }
*/

    
    </style>
</head>
<body>
    <div id="content" >

        
        <div id="detail" style="display:block">
           
            
            <div id="metadata">
                <?php echo $metadata;?>
            </div>
            
            <div id="video_container" >
<span id="loading" style="position:absolute; display:none; left:50%; top:50%;  margin-top: -64px; margin-left: -64px; z-index:1000;" > <img src="images/animal0020.gif"/> </span>
                <div id="v1" style="z-index:10;"></div>
            </div>
            
            <div id="video_buttons">
                <ul>
                    <li><img id="sd_button" src="images/film/film_banner_sd_press.png" onClick="$(this).attr('src', 'images/film/film_banner_sd_press.png');$('#hd_button').attr('src', 'images/film/film_banner_hd_normal.png');$('#loading').show(); loadVideo('<?php echo $video_id; ?>', '360p', true);"/> </li>
                    <li><img id="hd_button" src="images/film/film_banner_hd_normal.png" onClick="$(this).attr('src', 'images/film/film_banner_hd_press.png');$('#loading').show();$('#sd_button').attr('src', 'images/film/film_banner_sd_normal.png');
//changeSrc('DATA/video/720p/<?php echo $video_id;?>.mp4');  
loadVideo('<?php echo $video_id; ?>', '720p', true);

"/> </li>
                    <li><img id="download_button" src="images/film/film_banner_downloads_normal.png" onClick="doDownload();" /> </li>
                    <li><img id="downloading_button" style="display:none" src="images/downloads/downloading.png" /> </li>
                    <li><img id="downloaded_button" style="display:none" src="images/downloads/downloaded.png" /> </li>

                </ul>    
            </div>

            <span id="relate_txt"><img src="images/relate/related.png" /> </span>

            <div id="relate_list"  class="flexslider"  > 

                  <ul class="slides">


                      <?php echo $related_video_html1; ?>
                      
                  </ul>

            </div>
            
            
            
        </div>

    </div>    
    <div id="dialog-confirm" style="display:none" title="已達到下載上限">
    </div>
</body>

    <script type="text/javascript">
        var buttonArray=[];
        var player;
        var video_array = [<?php echo $video_list; ?> ];
        var __URL_PREFIX__ = '<?php echo __URL_PREFIX__; ?> ';


        <?php echo $video_js; ?>

  
        $(document).ready(function() {

	    $('img').bind('contextmenu', function(e) {
                return false;
            }); 

            video_list_html = '';
            var max = video_array.length <= 55 ? video_array.length : 55;
            for (j=0; j<max; j++) {
               mark='';
               id = video_array[j];
               if ((typeof(parent.ifDownloaded) == "function")&& parent.ifDownloaded(id)!=-1) {
                   mark='<img src="images/downloads.png" style="position:absolute; border:solid 0px red; width:31px; height:31px; right:5px; top: 0px; z-index:1000;" />';
               }

               video_list_html +="<li><div style='position:relative;display:block; background:url(DATA/images/cover150/"+id+".png) no-repeat center center;background-size: contain; border: solid 0px red;width:120px; height:170px; ' onClick='onCoverClicked("+id+ ")' > "+mark+ " </div> </li>";

            }
//console.log(video_list_html+'111');

            $(".slides").html(video_list_html);

            $('.flexslider').flexslider({
                animation: "slide",
                animationLoop: false,
                slideshow: false,
                itemWidth: 120,
                start: function(slider) {

                     $('.flexslider').resize();
                },
/*
                itemMargin: 10,
                itemHeight: 170,
minItems:0,
maxItems:6,
*/
                controlNav: true
              });

            if ((typeof(parent.ifDownloaded) == "function")&& parent.ifDownloaded(<?php echo $video_id; ?>)!=-1) {
                isDownload();
            }

            setupDownloadButtons();

            loadVideo('<?php echo $video_id; ?>', '360p', false); 
            
            $(window).resize(function() { onResize(); });


            
        });

        function setupDownloadButtons() {
            if (typeof(parent.checkVideoDownloadStatus) == "function") {
                code = parent.checkVideoDownloadStatus(<?php echo $video_id; ?>);
                switch (code) {
                    case 2:    //downloading
                        $("#download_button").hide();
                        $("#downloading_button").show();
                        break;
                    case 1:    //downloaded
//                        $("#sd_button").hide();
//                        $("#hd_button").hide();
                        $("#download_button").hide();
                        $("#downloaded_button").show();
                        break;
                    case 0:    //not in download task
                        break;
                }
            }

        }

        function isDownload() {
                $('#metadata').append('<img src="images/downloads.png" style="position:absolute; width:31px; height:31px; right:30%; top:5px" />');
        }
        
        function onCoverClicked(id) {
            location.href= 'y3_video.php?id='+id;
        }
        
        function loadVideo(id, quality, autoPlay) {
            
//            $("#loading").show();
            options = {
                src : 'DATA/video/'+quality+'/'+id + '.mp4',
//		src: 'http://127.0.0.1:8888/102021941.mp4',
                poster: 'images/poster_'+quality+'.jpg',
                preload: 'auto',
                useHTML5: true,
                autoPlay: autoPlay,
                playButtonOverlay: true,
//                showVideoInfoOverlayOnStartUp:true,
                height : "100%",
                width : "100%",
                favorFlashOverHtml5Video: true,
                javascriptCallbackFunction: "onJavaScriptBridgeCreated",
                scaleMode : 'letterbox'
            };
            options = $.fn.adaptiveexperienceconfigurator.adapt(options);
            p = $("#v1").strobemediaplayback(options);

        }  
        function changeSrc(url) {

		if (player == null) {
                    player.setMediaResourceURL(url);
                }
        }
        function onMediaPlayerStateChange(state, playerId)
        {
            var newstate = player.getState();
//console.log(newstate);
        }

	function onJavaScriptBridgeCreated(id, eventName, updatedProperties) {			
//if (eventName!="progress")console.log(eventName+ " " + updatedProperties);

if (eventName=="waiting") {
                $("#loading").show();
}
if (eventName=="play") {
                $("#loading").show();
}
/*

if (eventName=="loadedmetadata") {
                $("#loading").hide();

}
            if (eventName=="progress") {
                $("#loading").hide();
            }
*/
            if (eventName=="timeupdate") {
                $("#loading").hide();
            }
	    if (player == null) {
		player = document.getElementById(id);
                player.addEventListener("mediaPlayerStateChange", "onMediaPlayerStateChange");
            }
	}

        function onResize() {
/*
            $("object").each(function() {

console.log($("#video_container")[0].offsetWidth);
                $(this).width($("#video_container")[0].offsetWidth);
                $(this).height($("#video_container")[0].offsetHeight);
            });
            $("video").each(function() {
                $(this).width($("#video_container")[0].offsetWidth);
                $(this).height($("#video_container")[0].offsetHeight);
            });
*/
        }
        function onDownloadProgress(id, progress) {

            if (id== <?php echo $video_id; ?>) {

                if (progress==100) {
//                    $("#sd_button").hide();
//                    $("#hd_button").hide();
                    $("#download_button").hide();
                    $("#downloading_button").hide();
                    $("#downloaded_button").show();
                }
            }


        }
        function doDownload() {
            if (parent.canDownload()) { 

                $("#dialog-confirm").attr('title', '下載品質選擇').dialog({
                  resizable: false,
                  height:140,
                  width:250,
                  modal: true,
                  buttons:[
                      {
                          text:"SD", 
                          "id": "btnSD", 
                          click:function(){
                              isDownload();
                              parent.onDownloadClicked(video_data_sd);
                              $("#download_button").hide(); 
                              $("#downloading_button").show();  
                              $.post("ajax/main/writeLog.php", {type:'download', id:<?php echo $video_id; ?>, user: '<?php echo  $_SESSION['user_id']; ?>'});
                              $( this ).dialog( "close" );
                          } 
                      }, 
                      {
                          text:"HD", 
                          "id": "btnHD", 
                          click:function(){
                              isDownload();
                              parent.onDownloadClicked(video_data_hd);
                              $("#download_button").hide(); 
                              $("#downloading_button").show();  
                              $.post("ajax/main/writeLog.php", {type:'download', id:<?php echo $video_id; ?>, user: '<?php echo  $_SESSION['user_id']; ?>'});
                              $( this ).dialog( "close" );
                          } 
                      }
                  ]
/*
 {
                    "一般版(SD)": function() {
                    },
                    "高清版(HD)": function() {
                        isDownload();
                        parent.onDownloadClicked(video_data_hd);
                        $("#download_button").hide(); 
                        $("#downloading_button").show();  
                        $.post("ajax/main/writeLog.php", {type:'download', id:<?php echo $video_id; ?>, user: '<?php echo  $_SESSION['user_id']; ?>'});
                       $( this ).dialog( "close" );
                    }
                  }
*/
              });
sd_mb = Math.floor(video_data_sd.filesize/1048576);
hd_mb = Math.floor(video_data_hd.filesize/1048576);


 $("#btnSD").html('<span class="ui-button-text">'+ '一般版(SD)('+sd_mb +'MB)</span>');
 $("#btnHD").html('<span class="ui-button-text">'+ '高清版(HD)('+hd_mb +'MB)</span>');

            } else {

                $("#dialog-confirm").attr('title', '已達到下載上限(10支影片)').dialog({
                  resizable: false,
                  height:140,
                  modal: true,
                  buttons: {
                    "OK": function() {
                      $( this ).dialog( "close" );
                    }
                  }
              });
            }
        }

        
    </script>
</html>
