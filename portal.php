<?php
include_once('inc/config.php');
include_once('inc/class_category.php');
include_once('inc/class_tag.php');

$test_user = array('SUPER', 'U220171224', 'B221680066', 'N122352461', 'N222753880', 'H120226044', 'K221999193', 'F223848891', 'A129365125');
file_put_contents("/tmp/uuu", $USER_ID);
$test_mode = in_array($USER_ID , $test_user);
$test_mode = (1===1);

$category_db = new Category();
$category_db->init();

$category_data = $category_db->loadAllPublishedCategory();

$category_link = "";
$category_js_array = array();

foreach ($category_data as $item) {
    $id = $item["ID"];
    $category_link .= "<li><img id='category_$id"."_button'  onClick='onMenuClicked(\"category_$id\")' /></li>";
    array_push($category_js_array, 
            "{id:'category_$id', normal:'DATA/images/category/$id"."_normal.png', press:'DATA/images/category/$id"."_press.png', link:'y2_category.php?category_id=$id'  }"
    );

}


$tag_db = new Tag();
$tag_db->init();

$tag_data = $tag_db->loadAllPublishedTag();

$tag_link = "";
$tag_js_array = array();

foreach ($tag_data as $item) {
    $id = $item["ID"];
    $tag_link .= "<li><img id='tag_$id"."_button'  onClick='onMenuClicked(\"tag_$id\")' /></li>";
    array_push($tag_js_array, 
            "{id:'tag_$id', normal:'DATA/images/tag/$id"."_normal.png', press:'DATA/images/tag/$id"."_press.png', link:'y5_tag.php?id=$id'  }"
    );
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

    <title>富邦新視界</title>   

    <link rel="stylesheet" href="css/ui/jquery-ui.min.css">
    <link rel="stylesheet" href="css/messi.min.css">

    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        body {
            background-color: #CECECE;
            margin:0;
        }

        #message {
            color:red;
        }
        #download_list span {
           text-align:center;
        }
        #download_list span img {
           width:45%;
           cursor:pointer;
        }
        #download_list span div {

           /*width:45%;*/
           width:60px;
           height:23px;
           display:inline-block;
           cursor:pointer;

        }
        .progress {
            left:8%;
            right:8%;
            bottom:25%;
            height:5%;
            position:absolute;
            background:#C4D0DA;
        }
        .ui-progressbar-value{
            background:#36A7E6;
        }
        #t {
            background-color:#ccc;
            border: solid 3px #333;
            border-radius: 20px;
            font-size: 1.1em;
        }
        
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
        
        #wrapper{
            visibility:hidden;
            position:absolute;
            font-family:SimHei, KaiTi, Microsoft JhengHei, DFKai-sb, PMingLiU, MingLiU, serif;
        }
        
        #top {
            position: absolute;
            top:0;
            background-color: #EDEDED;
            height:45px;
            
            z-Index:10;
            left:0;
            right:0;
        }
        
        #home_button {
            cursor:pointer;   
        }
        #menu {
            position: absolute;
            top:48px;
            bottom:10px;
            width:150px;
            background-color: #EDEDED;
            margin:0;
            z-Index:10;
        }
        #menu ul {
            margin:0px 0px;
            padding-left:1px;
            font-size:0;
            list-style-type: none;
        }
        #menu img {
            width: 144px;
            cursor:pointer;
        }

        #dialog-confirm {
            display:none;
        }

        .dialog-header {
            background-image: linear-gradient( to right, #29AAE2, #0D58B6);
            color: white;
            background-image: -webkit-gradient(
            	linear,
            	left top,
            	right bottom,
            	color-stop(0, #2E75A4),
            	color-stop(0.66, #4A9AC5)
            );
            background-image: -o-linear-gradient(right bottom, #2E75A4 0%, #4A9AC5 66%);
            background-image: -moz-linear-gradient(right bottom, #2E75A4 0%, #4A9AC5 66%);
            background-image: -webkit-linear-gradient(right bottom, #2E75A4 0%, #4A9AC5 66%);
            background-image: -ms-linear-gradient(right bottom, #2E75A4 0%, #4A9AC5 66%);
            background-image: linear-gradient(to right bottom, #2E75A4 0%, #4A9AC5 66%);
        }
            
        #main {
            margin:0;
            border: solid 0px red;
            background-color:#EDEDED;

            position:absolute;
            left: 153px;
            top:48px;
            bottom:10px;
            right:5px;
            margin-left:0;
        }
        #downloadpanel {
            display:none;
            margin:0;
            background-color:#EDEDED;

            position:absolute;
            left: 153px;
            top:48px;
            bottom:10px;
            right:5px;
            margin-left:0;
        }
        #downloadlist {
            position:absolute;
            left: 15px;
            top:70px;
            bottom:5px;
            right:15px;
        }
        #download_header {
            position:absolute; 
            height:50px; left:10px; right:10px; top: 10px; 
            background-color: #4E4E4E;
        }
        #download_icon {
            position:absolute; 
            height:40px; left:10px; top: 8px; 
        }
        #download_txt {
            position:absolute; 
            height:30px; left:40px; top: 12px; 
        }
        #download_refresh {
            position:absolute; 
            cursor: pointer;
            height:24px; right:10px; top: 16px; 
        }


        #viewer {
            display:none;
            position:absolute;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #4C4C4C), color-stop(1, #020202));
            width:80%; height:80%; 
            padding: 30px;  
            border-radius:10px;
        }
        
    </style>


</head>
        
<body>
    <div id="wrapper">
        <div id="top">
            <img id="home_button"  onClick="onMenuClicked('home')" style="position:absolute; left:20px; top:5px; height:35px"/>
            <img src="images/top/fubonvision_logo.png" onclick="onAboutClicked();" style="position:absolute; left:100px; height:35px;top:5px;"/>

            <input id="query" type="text" style="width: 200px; position:absolute; border-radius: 5px; font-size:1.2em; top:5px; right: 220px;" />
            <img id="search" src="images/top/top_search_normal.png" onClick="onSearchClicked()"  style="position:absolute; right:160px; height:35px;top:5px;cursor:pointer"/>
            <img id="showdownload_btn" src="images/top/top_downloads_normal.png" onClick="showDownloadList()" style="position:absolute; cursor:pointer; right:100px; height:35px;top:5px;"/>
            <img id="fullscreen" src="images/top/top_full.png" onClick="onFullClicked()"  style="position:absolute; right:40px; height:35px;top:5px;cursor:pointer"/>
        </div>
        <div id="menu">
            <ul>

             <?php echo $category_link; ?>
            <li><img src="images/left/left_banner_serial_title.png" /></li>
             <?php echo $tag_link; ?>
<?php if ($test_mode) { ?>
<li><img id='hiring_1_button' src="DATA/images/hiring/1_normal.png" width="78" height="40" onclick="onMenuClicked('hiring_1');"/></li>
<?php } ?>
            </ul>
        </div>
        
        <div id="main">
            <iframe name="module" id="module" style="width:100%; height:100%" frameborder="0" scrolling="no" src="y0_home.php"></iframe>
        </div>
        <div id="downloadpanel">

            <span id="download_header"> 
            <img id="download_icon" src="images/downloads/download_icon.png" />
            <img id="download_txt" src="images/downloads/download_txt.png" />
            <img id="download_refresh" onClick="refreshDownload()" src="images/refresh.png" />
            </span>
    
            <div id="downloadlist"> 
    
            </div>    

        </div>
    </div>

    <div id="dialog-confirm" title="刪除本影片嗎?">
    </div>

    <div id="viewer">
        <img src="images/close_box_gray.png" onClick='onLightboxClose()' style="position:absolute; cursor:pointer; width:25px; height: 25px; right:5px; top:2px"/>
        <div id="v1" style="position:absolute; left:0; right:0; top:0; bottom:0"> </div>
    </div>

</body>

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery-ui-all.min.js"></script>
    <script src="js/StageWebViewBridge.js"></script>
    <script src="js/jquery.lightbox_me.js"></script>
    <script src="js/swfobject.js"></script>
    <script src="js/jquery.strobemediaplayback.js"></script>
    <script src="js/messi.min.js"></script>

    <script src="js/config.js"></script>

    <script type="text/javascript">
        
        var buttonArray=[];
        var download_task_all=[];
        var download_task_finished=[];
        var download_progress_array=[];
        var serverPort=0;
        var appVersion='';
        var g_appVersion;

        var __URL_PREFIX__ = '<?php echo __URL_PREFIX__; ?>';

        var isLogin;


        var idleTime;

        function logoutHandler() {
            $("#dialog-confirm").attr('title', '訊息視窗').html('登入已逾時，請重新登入').dialog({
                resizable: false,
                height:200,
                width:300,
                modal: true,
                buttons: {
                    "確定": function() {
                        StageWebViewBridge.call('logOut');
                        $( this ).dialog( "close" );
                    }
                }
            });
        }

        $(document).ready(function(){
            showMessage('重大通知：富邦新視界已更新版本，目前版本將於6/15停用，請儘速至行動e市集更新；電腦版APP將停用，請直接進入行動辦公室網頁版使用');
            
            idleTime = 0;

            var idleInterval = setInterval(timerIncrement, 1000);

            function timerIncrement() {
                idleTime++;
                //$("#timer").html(idleTime);
                if (idleTime > 7200) {
                    logoutHandler();
                }
            }

            $(document).mousemove(function(e){
                idleTime = 0;
            });

            function timeoutHandler() {
                alert("登入已逾時，請重新登入");
                location.href='http://127.0.0.1:'+serverPort+"/entry.html?true";
            }
        });
                
        $(window).load(function() {

            isLogin = true;

            $("#wrapper").css('visibility', "visible");

	    $('img').bind('contextmenu', function(e) {
                return false;
            }); 
            
            buttonArray = 
                [
                {id:'home', normal:'images/top/top_home_01.png', press:'images/top/top_home_01.png', link:'y0_home.php'},
                {id:'hiring_1', normal:'DATA/images/hiring/1_normal.png', press:'DATA/images/hiring/1_press.png', link:'y7_hiring.php'},
                <?php echo join(",\n", $category_js_array); ?>,
                <?php echo join(",\n", $tag_js_array); ?>
                ];

            onMenuClicked("home");
            
            $(document).bind('touchmove', function(e){
                e.preventDefault();           
            });

            onWindowSize();

            $(window).resize(function() {
                onWindowSize();
            });
            $("#query").keyup(function(event){
                if(event.keyCode == 13){
                    $("#search").click();
                }
            }).focus();
        });

        function onAboutClicked() {
            if ((typeof StageWebViewBridge)== "undefined") return;

            var version_label;
            if ((typeof g_appVersion)== "undefined") {
                StageWebViewBridge.call('getVersion', function(data) {
                    g_appVersion = data;
                    $(".version_label").text(data);
                });
                version_label = "取得中...";
            } else {
                version_label = g_appVersion;
            }

            new Messi('程式版本：<span class="version_label">'+version_label+'</span><br>使用者：<?php echo $USER_ID;?>', {
                title: '關於',
                modal: true,
                width: '300px',
                /*                    autoclose: 5000, */
                center: false,
                viewport: {top:'30px', left:'100px'},
                buttons: [{id: 0, label: '確定', val: 'Y'}]
            });

        }

        function canDownload() {
            return (download_task_all.length<10);
        }

        function ifDownloaded(id) {
            return ($.inArray(id, download_task_all));
        }
        function checkVideoDownloadStatus(id) {

            if ($.inArray(id, download_task_finished)!=-1) return 1;  //download complete
            if ($.inArray(id, download_task_all)!=-1) return 2;  // downloading 
            return 0; //Not in download task
        }
        function showMessage(msg_id) {

             updateDownloadPage();
            switch (msg_id) {
                case 0: // LOW SPACE
                    msg = "磁碟空間不夠，已暫停下載工作";
                    break;
                default:
                    msg = msg_id;
            }

//            alert(msg);
            $("#dialog-confirm").attr('title', '訊息視窗').html(msg);
            $("#dialog-confirm").dialog({
                  resizable: false,
                  height:270,
                  width:400,
                  modal: true,
                  buttons: {
                    "確定": function() {
                      $( this ).dialog( "close" );
                    }
                  }
              });
        }
        function onWindowSize() {
		
            w = $(window).width();
            h = $(window).height();
				
            if (w<900) w=900;
            if (h<600) h=600;

            $('#wrapper').width(w);
            $('#wrapper').height(h);
          //  alert(w+'x'+h);
        }

        function onSearchClicked() {
            query = $("#query").val();
            //$("#downloadpanel").fadeOut(1000);
            $("#downloadpanel").hide();

            link=encodeURI("y6_search.php?query="+query);
             
            if (query !='') {
                $("#module").attr("src", link);
            }

        }

        function onFullClicked() {
            StageWebViewBridge.call('toggleFullScreen');
        }

        function showDownloadList() {
            onMenuClicked('');
            $("#downloadpanel").show();

        }

        function onLightboxClose() {
            $("#viewer").trigger("close");

            var link = $('#module').contents().get(0).location.href;

            if (link.indexOf("y0_home.php")!=-1) {
                document.getElementById("module").contentWindow.setAutoPlay(true); 
            }
        }

        function updateDownloadPage() {
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'entry.html');
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_desktop_o365_beta.html');
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_phone_o365_beta.html');
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_tablet_o365_beta.html');
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_desktop_o365_release.html');
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_phone_o365_release.html');
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_tablet_o365_release.html');

            StageWebViewBridge.call('getDownloadedList', function(data) {

                prepareDownloadPage(data);
                var link = $('#module').contents().get(0).location.href;

                if (link.indexOf("y0_home.php")!=-1) {
                    document.getElementById("module").contentWindow.prepareHotArea(); 
                }

            });
        }


        function readyForDownload(data) {

            if (data<=0) return;
             updateDownloadPage();
             StageWebViewBridge.call('startDownload'); 
             StageWebViewBridge.call('serverPort', function(data) {
                 serverPort = data;
             });

             StageWebViewBridge.call('setConfig', null, "disk_space_limit", "200000000");
            // StageWebViewBridge.call('getConfig', function(data) { alert(data);}, "data_version");

             StageWebViewBridge.call('getVersion', function(data) {
                 appVersion = data;
//                       onUpgrade(data, '1.1.0.311.1', 'http://fubon.moker.com.tw/downloads/update_1.1.0.exe');
//                   if (data!='0.9.5.311.1') {
                       //onUpgrade(data, '0.9.5.311.1', 'http://fubon.moker.com.tw/downloads/富邦新視界安装程式0.9.5.exe');
                       //onUpgrade(data, '0.9.5.311.1', 'http://fubon.moker.com.tw/downloads/富邦新視界_0311.zip');
//                       onUpgrade(data, '0.9.5.311.1', 'http://fubon.moker.com.tw/downloads/install_0311.zip');
 //                  }
             });
             
             StageWebViewBridge.call('reportLog', function(data) {
                 //alert(JSON.stringify(data));
                 $.post("ajax/main/writeLog.php", {type:'offline', user:'<?php echo $_SESSION['user_id'];?>', content: JSON.stringify(data)}, function () { });
             });
/*
             StageWebViewBridge.call('dbOperation', function(data) {
                 //alert(JSON.stringify(data));
//alert(JSON.stringify(data));
             }, "SELECT * FROM LOG", null);
*/
        }

        function forceLogin() {
            //http://127.0.0.1:"+_fs.getPort()+"/entry.html
            location.replace("http://127.0.0.1:"+serverPort+"/entry.html");
        }

        function refreshDownload() {
            $("#downloadlist").html('<div style="position: absolute; top:45%; left:45%; "><h2>載入中</h2></div>');
            
            StageWebViewBridge.call('getDownloadedList', function(data) {
                updateDownloadPage();

                len = data.length;
                for (i=0; i<len; i++) {
                    StageWebViewBridge.call('updateCache', null, __URL_PREFIX__+'DATA/images/cover/'+data[i].id+'.png');
                }
            });
        }

        function onDownloadClicked(data) {
 //             data = {id:102021911, url:'http://fubon.moker.com.tw/DATA/video/720p/102021911.mp4', filesize:25957492};
            StageWebViewBridge.call('updateCache', null, __URL_PREFIX__+'DATA/images/cover/'+data.id+'.png');
            StageWebViewBridge.call('downloadVideo', null, data );

            updateDownloadPage();
//            $('#module').contents().find('html').html(localS);
        }
        function openURL(url) {
            StageWebViewBridge.call('openURL', null, url );
        }
        function loadURL(url) {
            StageWebViewBridge.call('loadURL', null, url );
        }
        function saveFileWithDialog(url) {
            StageWebViewBridge.call('saveFileWithDialog', null, url );
        }

        function onMenuClicked (area) {
            var link='';

            
            $.each(buttonArray, function(key, val) {
                if (val.id == area) {
                    $('#'+val.id+'_button').attr('src', val.press);
                    link = val.link;
                } else {
                     $('#'+val.id+'_button').attr('src', val.normal);
                }
            
            });
            $("#module").attr("src", link);
            $("#downloadpanel").hide();
//            $("#downloadpanel").fadeOut(1000);

        }

        function server(data) {
            $('#module').contents().find('html').html(data);
        }

        function updateDownloadList(id, progress) {

// progress value might be wrong in version 1.1 and before 

/*
            if (appVersion <'2.1.0.313.2'){ 
                 StageWebViewBridge.call('getDownloadedList', function(data) {
                     len = data.length;


                     for (i=0; i<len; i++) {
                          if (data[i].id == id) {
                              if (data[i].status!=1) { 
                                  progress = data[i].progress;
                                alert("adjust progress to "  + progress);
                              }
                          }
                     }
           
                 });
            }
*/

/*
            if (progress!=100) {
                $("#progress_"+id).progressbar({ value: progress, background: "#B637E6" });
            } else {
                $("#progress_"+id).hide();
                $("#play_"+id).show();
                $("#pause_"+id).hide();
                $("#resume_"+id).hide();
            }
*/
/*
            if (progress!=100) {
                $("#progress_"+id).progressbar({ value: progress, background: "#B637E6" });
            } else {
*/

            var link = $('#module').contents().get(0).location.href;

            if ($("#downloadpanel").is(":visible") || (link.indexOf("y3_video.php")!=-1) ) {
                updateDownloadPage();
            }
/*
}
*/


/*
            if (link.indexOf("y3_video.php")!=-1) {
                document.getElementById("module").contentWindow.onDownloadProgress(id, progress);
            }
*/


        } 
        function onPause(id) {
            StageWebViewBridge.call('pauseDownload', null, id );
            updateDownloadPage();
        } 
        function onResume(id) {
            StageWebViewBridge.call('resumeDownload', null, id );
            updateDownloadPage();
        } 
        function onPlay(id, path) {
            var v_url, prefix;
            if (serverPort!=0) {
                prefix = 'http://127.0.0.1:' +serverPort + '/';
                v_url = (typeof path === 'undefined') ? prefix + id + '.mp4' : path;
            } else {
                prefix = __URL_PREFIX__;
                v_url = (typeof path === 'undefined') ? __URL_PREFIX__+'/DATA/video/720p/' +id + '.mp4' : path;
            }
            $("#viewer").lightbox_me({
                closeClick: true,
                closeEsc: false,
                centered: true,
                overlayCSS: {background: 'black', opacity: .5},
                onLoad: function() { 
                }
            });

            options = {
                src : v_url,
                //poster: 'http://fubon.moker.com.tw/images/poster_720p.jpg',
                swf: prefix+'StrobeMediaPlayback.swf',
                preload: 'auto',
                useHTML5: true,
                autoPlay: true,
                playButtonOverlay: true,
                height : "100%",
                width : "100%",
                favorFlashOverHtml5Video: true,
                javascriptCallbackFunction: "onJavaScriptBridgeCreated",
/*
                initialBufferTime: 60,
                bufferTime: 60,
                expandedBufferTime: 80,
*/
                scaleMode : 'letterbox'
//                showVideoInfoOverlayOnStartUp:true,
/*
                dynamicStreamBufferTime:60,
                optimizeBuffering: true,
                verbose: false,
*/
            };
            options = $.fn.adaptiveexperienceconfigurator.adapt(options);
            p = $("#v1").strobemediaplayback(options);
        } 
        function onUpgrade(cv, nv, download_link) {
            
//            $("#dialog-confirm").attr('title', '訊息視窗').html('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/><br/>下載後請先關閉富邦新視界，之後點選執行剛下載的安裝程式。如果無法正常下載，請檢查瀏覽器設定，或者登入菁英網下載最新版程式。').dialog({
            $("#dialog-confirm").attr('title', '訊息視窗').html('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/><br/>下載後請先關閉富邦新視界，再點選執行安裝程式。如果無法正常下載，請檢查瀏覽器設定，或者登入菁英網下載最新版程式。').dialog({
                  resizable: false,
                  height:340,
                  width:600,
                  modal: true,
                  buttons: {
                    "下載新版安裝程式": function() {

//                      if (cv<'0.9.5.311.1') {
//                          StageWebViewBridge.call('openURL', null, download_link);
//                      } else if (cv >'0.9.5.311.1') {
//                          StageWebViewBridge.call('doUpdate', null, __URL_PREFIX__+'/downloads/update_1.1.2.exe', 'update_1.1.2.exe');
//                      } else {
//                          StageWebViewBridge.call('saveFile', null, download_link);
//                      }
                      $( this ).dialog( "close" );
                    },
                    "取消": function() {
                      $( this ).dialog( "close" );
                    }
                  }
              });

        }
        function onDelete(id) {

            $(".ui-dialog-titlebar").css("background-color", "blue").addClass("dialog-header");
            
            $("#dialog-confirm").attr('title', '訊息視窗').html('確定要刪除嗎?').dialog({
                  resizable: false,
                  height:200,
                  width:220,
                  modal: true,
                  buttons: {
                    "確定": function() {
                      StageWebViewBridge.call('deleteVideo', null, id );
                      updateDownloadPage();
                      $( this ).dialog( "close" );
                    },
                    "取消": function() {
                      $( this ).dialog( "close" );
                    }
                  }
              });

        }

        function prepareDownloadPage(data) {

            download_task_all=[];
            download_task_finished=[];
            download_progress_array=[];

            len = data.length;

            html_content = '<div id="download_list" style="margin-left:auto; margin-right:auto;;height:97%; width:97%; border: solid 0px red;">';

            for (i=0; i<len; i++) {
                download_task_all.push(data[i].id);

                id = data[i].id;
                cell = '<div style="position:relative; margin: 10px 5px; float:left; width: 18%; height: 48%; border: solid 0px red;">'+' <div style="width:100%;height:90%;background:url(DATA/images/cover/' + id + '.png) no-repeat center center;background-size: contain"/><span id="func_'+ id+ '" style="position:absolute;left:0; bottom:0px; width:100%; height: 10%"><div id="play_'+id+'" style="background:url(images/downloads/play_normal.png) no-repeat center center; display:inline-block" onClick="onPlay('+data[i].id+')"></div> <div id="pause_'+id+'" style="background:url(images/downloads/pause_normal.png) no-repeat center center;" onClick="onPause('+data[i].id+')"></div> <div id="resume_'+id+'" style="background:url(images/downloads/resume_normal.png) no-repeat center center;" onClick="onResume('+data[i].id+')"></div> <div id="delete_'+id+'" style="background:url(images/downloads/delete_normal.png) no-repeat center center;" onClick="onDelete(' + data[i].id +')"></div></span><div class="progress" id="progress_'+ data[i].id + '"></div></div>';
                html_content+=cell;
                

                var z=new Object;
                z.id=data[i].id;
                z.status=data[i].status;
                z.progress=data[i].progress;
                download_progress_array.push(z);

                if (z.status==1) download_task_finished.push(z.id)
            }
            html_content +="</div>";

            $("#downloadlist").html(html_content);

            var link = $('#module').contents().get(0).location.href;

            var in_video_page=false;
            var w = document.getElementById("module").contentWindow;

            if (link.indexOf("y3_video.php")!=-1) {
                in_video_page = true;
            }
            
            $.each(download_progress_array, function(key, value) {

                switch(value.status) {
                    case 0:  //STATUS_DOWNLOADING
                        $("#progress_"+value.id).show().progressbar({ value: value.progress, background: "#B637E6" });
                        $("#play_"+value.id).hide();
                        $("#pause_"+value.id).show();
                        $("#resume_"+value.id).hide();
                        $("#delete_"+value.id).show();
                        break;
                    case 1:  //STATUS_COMPLETE
                        $("#progress_"+value.id).hide();
                        $("#play_"+value.id).show();
                        $("#pause_"+value.id).hide();
                        $("#resume_"+value.id).hide();
                        $("#delete_"+value.id).show();

                        value.progress=100;
                        break;
                    case 2:  //STATUS_PAUSE
                        $("#progress_"+value.id).show().progressbar({ value: value.progress, background: "#B637E6" });
                        $("#play_"+value.id).hide();
                        $("#pause_"+value.id).hide();
                        $("#resume_"+value.id).show();
                        $("#delete_"+value.id).show();
                        break;

                    case 3:  //STATUS_PENDING
                        $("#progress_"+value.id).show().progressbar({ value: value.progress, background: "#B637E6" });
                        $("#play_"+value.id).hide();
                        $("#pause_"+value.id).show();
                        $("#resume_"+value.id).hide();
                        $("#delete_"+value.id).show();
                        break;
                    case 4:  //STATUS_ERROR
                        $("#progress_"+value.id).show().progressbar({ value: value.progress, background: "#B637E6" });
                        $("#play_"+value.id).hide();
                        $("#pause_"+value.id).hide();
                        $("#resume_"+value.id).hide();
                        $("#delete_"+value.id).show();
                        break;

                }
                if (in_video_page) w.onDownloadProgress(value.id, value.progress); 
            });
        }
        
        
    </script>

</html>
