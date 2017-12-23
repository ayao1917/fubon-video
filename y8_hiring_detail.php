<?php


include_once('inc/config.php');
include_once('inc/utils.php');

$id=$_REQUEST['id'];

$video_id = $id;
$related_video_html = "";
if ($id==1) {
    $title="【房仲業銷售業務員】增員資料夾";
    $date="104年4月2日";
    $detail="【房仲銷售業務員】增員資料夾，提供您增員房仲業務員時的輔助展示資料。使用手冊提供增員資料夾簡報檔內容解析以及示範問句，讓您了解如何於增員現場使用增員資料夾切進工作議題，並附錄房仲業五大理想工作要件分析作為參考資料。";
//    $video_list = 2;
    $video_list = -1;

    $book1 = 1;
    $book2 = 2;
    $book3 = 3;
    $book4 = 4;
} else {
    $title="【汽車銷售業務員】增員資料夾";
    $date="103年3月17日";
    $detail="這是你一旦想要增員汽車銷售業務員時，一定要看的秘籍大整理！";
    $video_list = 1;
    $book1 = 4;
    $book2 = 5;
    $book3 = 4;
}


$metadata = "<img src='DATA/images/hiring/cover/$id.png' style=' margin-top: 10px;'/> &nbsp;<br/> <br/><p>".$title."</span></p><br/><p>檔案更新日期：$date</p> <p>檔案簡介：</p><p>$detail</p>";

$video_books = array(
            103033104 => array("轉介紹", 103033101, 103033102),
            103033102 => array("工作體驗-市調問卷", 103033103, 103033104),
            103033103 => array("目標設定", 103033105, 103033106),
            103033101 => array("工作體驗-人脈樹狀圖", 103033107, 103033108),
            103063003 => array("100P的運用", 103063001, 103063002),
            103063004 => array("自我介紹", 103063003, 103063004),
            103063005 => array("保險的意義與保險業務工作的價值", 103063005, 103063006),
            103063006 => array("陌生開發", 103063007, 103063008),
            103063007 => array("商品FAB思考架構", 103063009, 103063010),
            103092903 => array("商品FAB銷售規劃(長照篇)", 103093001, 103093002),
            103092901 => array("工作計劃與時間管理", 103093003, 103093004),
            103092902 => array("說故事銷售", 103093005, 103093006),
            103112701 => array("建議書說明與促成", 103112701, 103112702),
            103112702 => array("安排約訪", 103112703, 103112704),
            103112703 => array("銷售面談", 103112705, 103112706),
            103112704 => array('抗拒處理(一)-不信任', 103112707, 103112708),
            103112705 => array('抗拒處理(二)-不需要', 103112709, 103112710),
            104012302 => array("建議書規劃", 104013001, 104013002),
            104012303 => array("業務制度", 104013003, 104013004),
            104012304 => array("抗拒處理(三)沒幫助", 104013005, 104013006),
            104012305 => array("抗拒處理(四)不著急", 104013007, 104013008)
);



//if ($video_id == 102021966) {
if (array_key_exists($video_id, $video_books)) {

    $allow_rank=array('99', 'AVP','SRM','ARM','VRM','DM','UM','AM','SP','CCP','SCP','CP','CCM','SCM','CM','CUM','CDM','CSM','CS','CFM');

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

$video_js="";
//$video_js = "var video_data_hd = $video_data_hd;\n";
//$video_js .= "var video_data_sd = $video_data_sd;\n";

//ulog($_SESSION['user_id'], "video", $video_id, "-");

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
            width: 540px;
            
            position: relative; margin:2px auto; list-style-type: none;
        }
        #video_buttons li {
/*
            float:left; margin: 1px 10px;
*/

margin: 1px 10px;
display:inline-block;

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
            background-color:#F55F2A;
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

<!--
<span id="loading" style="position:absolute; display:none; left:50%; top:50%;  margin-top: -64px; margin-left: -64px; z-index:1000;" > <img src="images/animal0020.gif"/> </span>
-->
<div style="position:absolute; left:0; right:0; top:0; bottom:0; background-image:url(DATA/images/hiring/content/<?php echo $id?>.jpg?1); background-size:contain; background-repeat: no-repeat; background-position:center center"></div>
<!--
                <div id="v1" style="z-index:10;"></div>
-->
            </div>
            
            <div id="video_buttons">
                <ul>
<!--
                    <li><img id="sd_button" src="images/film/film_banner_sd_press.png" onClick="$(this).attr('src', 'images/film/film_banner_sd_press.png');$('#hd_button').attr('src', 'images/film/film_banner_hd_normal.png');$('#loading').show(); loadVideo('<?php echo $video_id; ?>', '360p', true);"/> </li>
                    <li><img id="hd_button" src="images/film/film_banner_hd_normal.png" onClick="$(this).attr('src', 'images/film/film_banner_hd_press.png');$('#loading').show();$('#sd_button').attr('src', 'images/film/film_banner_sd_normal.png');
//changeSrc('DATA/video/720p/<?php echo $video_id;?>.mp4');  
loadVideo('<?php echo $video_id; ?>', '720p', true);

"/> </li>
                    <li><img id="download_button" src="images/film/film_banner_downloads_normal.png" onClick="doDownload();" /> </li>
                    <li><img id="downloading_button" style="display:none" src="images/downloads/downloading.png" /> </li>
                    <li><img id="downloaded_button" style="display:none" src="images/downloads/downloaded.png" /> </li>
-->
<!--
                    <li><img id="button1" src="DATA/images/hiring/button/increase_banner_book_quick_normal.png" onclick="parent.openURL('http://fubon.moker.com.tw/ajax/main/file.php?id=<?php echo $book1;?>&sid=<?php echo session_id(); ?>');" /> </li>
                    <li><img id="button2" src="DATA/images/hiring/button/increase_banner_book_full_normal.png" onclick="parent.openURL('http://fubon.moker.com.tw/ajax/main/file.php?id=<?php echo $book2;?>&sid=<?php echo session_id(); ?>');" /> </li>
                    <li><img id="button3" src="DATA/images/hiring/button/increase_banner_file_normal.png" onclick="parent.openURL('http://fubon.moker.com.tw/ajax/main/file.php?id=<?php echo $book3;?>&sid=<?php echo session_id(); ?>');" /> </li>
-->
<!--
                    <li><img id="button1" src="DATA/images/hiring/button/increase_banner_book_quick_normal.png" onclick="parent.openURL('http://fubon.moker.com.tw/download.php?id=<?php echo $book1;?>&sid=<?php echo session_id(); ?>');" /> </li>
                    <li><img id="button2" src="DATA/images/hiring/button/increase_banner_book_normal.png" onclick="parent.openURL('http://fubon.moker.com.tw/download.php?id=<?php echo $book2;?>&sid=<?php echo session_id(); ?>');" /> </li>
-->
                    <li><img id="button2" src="DATA/images/hiring/button/increase_banner_book_normal.png" onclick="parent.openURL('https://fubonevideo.moker.com/download.php?id=<?php echo $book2;?>&sid=<?php echo session_id(); ?>');" /> </li>
<!--
                    <li><img id="button3" src="DATA/images/hiring/button/increase_banner_file_ios_normal.png" onclick="parent.openURL('http://fubon.moker.com.tw/download.php?id=<?php echo $book3;?>&sid=<?php echo session_id(); ?>');" /> </li>
                    <li><img id="button4" src="DATA/images/hiring/button/increase_banner_file_normal.png" onclick="parent.openURL('http://fubon.moker.com.tw/download.php?id=<?php echo $book4;?>&sid=<?php echo session_id(); ?>');" /> </li>
-->
                    <li><img id="button4" src="DATA/images/hiring/button/increase_banner_file_normal.png" onclick="parent.openURL('https://fubonevideo.moker.com/download.php?id=<?php echo $book4;?>&sid=<?php echo session_id(); ?>');" /> </li>

                </ul>    
            </div>

            <span id="relate_txt"><img src="images/hiring/related_data.png" /> </span>

            <div id="relate_list"  class="flexslider"  > 

                  <ul class="slides">


                      <?php echo $related_video_html; ?>
                      
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
            for (j=0; j<video_array.length; j++) {
               mark='';
               id = video_array[j];
if (id<0) continue;
               if ((typeof(parent.ifDownloaded) == "function")&& parent.ifDownloaded(id)!=-1) {
                   mark='<img src="images/downloads.png" style="position:absolute; border:solid 0px red; width:31px; height:31px; right:5px; top: 0px; z-index:1000;" />';
               }

               video_list_html +="<li><div style='position:relative;display:block; background:url(DATA/images/hiring/cover150/"+id+".png) no-repeat center center; border: solid 0px red;width:120px; height:170px; ' onClick='onCoverClicked("+id+ ")' > "+mark+ " </div> </li>";

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
                        $("#sd_button").hide();
                        $("#hd_button").hide();
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
            location.href= 'y8_hiring_detail.php?id='+id;
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
                    $("#sd_button").hide();
                    $("#hd_button").hide();
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
