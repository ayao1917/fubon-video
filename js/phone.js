
var buttonArray = [];
var tag_video_list = [];
var category_video_list = [];
var $vid_obj = null;
var previous_page;
var current_page = "menu_home";
var home_player;

//var CONFIG.SERVER_ROOT = 'http://fubon.moker.com.tw/';


/* store page contents  */
/*
var g_category_page1;
var g_category_page2;
var g_tag_page1;
var g_tag_page2;
var g_hot1_page;
var g_hot2_page;
*/

var g_user;
var g_rank;
var g_unitcode;
var g_serverPort;
var g_appVersion;
var g_download_task_all = [];
var g_download_task_finished = [];
var g_download_progress_array = [];
var g_current_video;
var g_video_sd;
var g_video_hd;
var g_webmode = 1;


var g_category_loaded=false;
var g_tag_loaded=false;
var g_download_loaded=false;

var g_width;
var g_clickEvent = "touchstart";
//var g_clickEvent = "click";
var g_ua = navigator.userAgent;

var $home_content;
var $category_content;
var $tag_content;
var $video_content;
var $search_content;
var $download_content;
var $menu_home;
var $menu_category;
var $menu_tag;
var $menu_download;



/*
function initButtonArray() {

    buttonArray = [{
        id: 'menu_home',
        normal: 'images/phone/bottom_banner/bottom_banner_icon01_normal.png',
        press:  'images/phone/bottom_banner/bottom_banner_icon01_over.png',
        link: 'home'     
    },{
        id: 'menu_category',
        normal: 'images/phone/bottom_banner/bottom_banner_icon02_normal.png',
        press:  'images/phone/bottom_banner/bottom_banner_icon02_over.png',
        link: 'category'     
    },{
        id: 'menu_tag',
        normal: 'images/phone/bottom_banner/bottom_banner_icon03_normal.png',
        press:  'images/phone/bottom_banner/bottom_banner_icon03_over.png',
        link: 'tag'     
    },{
        id: 'menu_download',
        normal: 'images/phone/bottom_banner/bottom_banner_icon04_normal.png',
        press:  'images/phone/bottom_banner/bottom_banner_icon04_over.png',
        link: 'download'     
    }];
    
}
*/


function initCategory() {
    $('#categories').html(g_category_list);
    onCategorySortClicked(0);
    g_category_loaded=true;

    $(".category_button").on("click", function(event) {
        $(".category_button").each(function() { $(this).attr("src", $(this).data("normal"));});
        $(this).attr("src", $(this).data("press"));
        loadCategoryContent($(this).data("id"));
    });

}

function initTag() {
    $('#tags').html(g_tag_list);
    onTagSortClicked(0);
    g_tag_loaded=true;
    $(".tag_button").on("click", function(event) {
        $(".tag_button").each(function() { $(this).attr("src", $(this).data("normal"));});
        $(this).attr("src", $(this).data("press"));
        loadTagContent($(this).data("id"));
    });
}

function fixIOSBounce() {
    if (((typeof g_ua)=="undefined") || (g_ua== "")) g_ua= navigator.userAgent;
    if (g_ua.match(/iPad/i) || g_ua.match(/iPhone/i)) {
           
        document.addEventListener('touchmove', function(e) {
            e.preventDefault();
        }, false);
      
        var elem = document.getElementsByClassName ('scrolling');
        for (var i = 0, len = elem.length; i < len; i++) {
            elem[i].addEventListener('touchstart', function(event) {


                this.allowUp = (this.scrollTop > 0);
                this.allowDown = (this.scrollTop < this.scrollHeight - this.clientHeight);
                this.prevTop = null;
                this.prevBot = null;
              this.lastY = event.pageY;
            });
        }
        for (var i = 0, len = elem.length; i < len; i++) {

            elem[i].addEventListener('touchmove', function(event) {
                var up = (event.pageY > this.lastY),
                    down = !up;

                this.lastY = event.pageY;

                if ((up && this.allowUp) || (down && this.allowDown))
                    event.stopPropagation();
                else
                    event.preventDefault();
            });
        }
    }
}

function setEventHandler() {
    $("#home_container").delegate('.v_action', g_clickEvent, function() {
        onMenuClicked('video', 'video', $(this).data('id'));
    });
    $("#category_container").delegate('.v_action', g_clickEvent, function() {
        onMenuClicked('video', 'video', $(this).data('id'));
    });
    $("#tag_container").delegate('.v_action', g_clickEvent, function() {
        onMenuClicked('video', 'video', $(this).data('id'));
    });
    $(".menu-item").on(g_clickEvent, function(event) {
        $(".menu-item").css("background-color", "#1D8DCC");
        $(this).css("background-color", "#555555");
        onMenuClicked($(this).data("target"));    
    });
    $("#dosearch").on("click", function(event) {
       doSearch(); 
    });
    $("#search").on("click", function(event) {
       toggleSearch(); 
    });
    $("#new_area_txt").on(g_clickEvent, function(event) {
       onHotClicked('hot1');
    });
     $("#hot_area_txt").on(g_clickEvent, function(event) {
       onHotClicked('hot2');
    });   
    $("#category_hot_btn").on(g_clickEvent, function(event) {
      onCategorySortClicked(0);
    });
    $("#category_new_btn").on(g_clickEvent, function(event) {
      onCategorySortClicked(1);
    });    
    $("#tag_hot_btn").on(g_clickEvent, function(event) {
      onTagSortClicked(0);
    });
    $("#tag_new_btn").on(g_clickEvent, function(event) {
      onTagSortClicked(1);
    });    
    $("#logo_btn").on(g_clickEvent, function(event) {
      onAboutClicked();
    });    
    $("#return_btn").on(g_clickEvent, function(event) {
      onReturnClicked();
    });    
    $('img').bind('contextmenu', function(e) {
        return false;
    });
}

var idleTime;
var idleInteval;

$(document).ready(function() {

            idleTime = 0; 
            idleInterval = setInterval(timerIncrement, 1000);

    showMessage('重大通知：富邦新視界已更新版本，目前版本將於6/15停用，請儘速至行動e市集更新；電腦版APP將停用，請直接進入行動辦公室網頁版使用');

            function timerIncrement() {
                idleTime++;

                if (idleTime > 7200) {

                    clearTimeout(idleInteval);
                    timeoutHandler();
                }
            }

            $(document).bind("touchstart", function(e){
                idleTime = 0; 
            });

            function timeoutHandler() {
                $("#wrapper").css("display", "none");
                new Messi('登入已逾時，請重新登入', {
                    title: '訊息視窗', 
                    modal: true,
                    width: '300px',
                    padding: '0px',
                    buttons: [{id: 0, label: '確定', val: 'Y'}], 
                    callback: function(val) { 
                        location.href='http://127.0.0.1:'+g_serverPort+"/login_phone_o365_release.html?true";
                    }
                });
            }

    if (( navigator.userAgent.indexOf("iPhone"))!=-1) {
        $('#categories').css('height','42px');
    }

//    initButtonArray();

setTimeout(function(){
    g_width = $(document).width();
    h = $(document).height()-85;

    if (g_width==320) {
        g_width = getParameterByName('w');
        h = getParameterByName('h')-85;
    }

    $("#viewport>ul>li").width(g_width);
    $("#viewport>ul>li").css("height", "1000px");
    $("#viewport>ul>li").css("position", "relative");
    $("#viewport>ul>li>div").css("height", h+"px");
    $("#viewport>ul>li>div").width(g_width);

    $(".slides li").width(g_width);
    $(".slides").height(g_width/1.77);
    $('#home_banner_slider').resize();
  }, 3000);

    url = window.location.toString();

    g_user = getParameterByName('i');
    g_rank = getParameterByName('r');
    g_unitcode = getParameterByName('u');
    if (getParameterByName('p')==0) {
        g_clickEvent="click";
    }

    fixIOSBounce();

    g_width = $(document).width();
    h = $(document).height()-85;
    if (g_width==320) {
        g_width = getParameterByName('w');
        h = getParameterByName('h')-85;
    }

    $("#viewport>ul>li").width(g_width);
    $("#viewport>ul>li").css("height", "1000px");
    $("#viewport>ul>li").css("position", "relative");
    $("#viewport>ul>li>div").css("height", h+"px");
    $("#viewport>ul>li>div").width(g_width);
 
    $(".slides li").width(g_width);
    $(".slides").height(g_width/1.77);

    $home_content = $("#home_content");
    $category_content = $("#category_content");
    $tag_content = $("#tag_content");
    $video_content = $("#video_content");
    $search_content = $("#search_content");
    $download_content = $("#download_content");

    $menu_home = $(".menu-item").eq(0);
    $menu_category= $(".menu-item").eq(1);
    $menu_tag= $(".menu-item").eq(2);
    $menu_download= $(".menu-item").eq(3);

    home_makeBanner();

    setEventHandler();
    
    $(".menu-item ").css("background-color", "#1D8DCC");
    $(".menu-item").eq(0).css("background-color", "#555555");
    
    //    onMenuClicked("menu_home");
    $("#wrapper").css('visibility', "visible");
//    setTimeout('refreshDownload()', 1000); 

});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function downloadFileComplete(url) {
    var intent = url+'#Intent;scheme=file;action=android.intent.action.VIEW;type=application/vnd.android.package-archive;launchFlags=0x10000000;end';

    intent.replace('file', 'intent');
    StageWebViewBridge.call('openURL', null, intent);
}

/************************* Download Layout *****************************/
function download_layout() {
    
    
    len = g_download_task_all.length;
    $(".download_item").unbind("click");
    var html_content = '';


    if (len<=0) return;
    
    //alert(len);
    
    for (i = 0; i < len; i++) {
        id = g_download_task_all[i];
            
        cell = '<li style="height:120px; width:100%; margin: 10px; position:relative; border-bottom: solid 3px #aaa">' +
              '<span id="item_'+id+'" style="position:absolute; width:100%; height:100%; ">'+
               '<div style="position:absolute; width:20%; left:0; top:0; bottom:0; text-align:center;"> '+
                '<img src="' + CONFIG.SERVER_ROOT + 'DATA/images/cover150/' + id + '.png" style="height:110px;"/> '+
                '<br/>' +
                '<div class="progress" id="progress_'+id+'"></div>'+
                '</div>' +
            
    //            '<div style="position:absolute; left:25%; right:0%; top:10px; bottom:10px;">踏出夢想的第一步(第一集)：奇異旅程<br/>影片長度：25分10秒</div>'+


                '<span class="download_controler">' +
                '<div class="download_item" data-id="' + id + '" data-action="play" style="background-image:url(images/downloads/play_normal.png);"></div>' +
                '<div class="download_item" data-id="' + id + '" data-action="pause" style="background-image:url(images/downloads/pause_normal.png);"></div>' +
                '<div class="download_item" data-id="' + id + '" data-action="resume" style="background-image:url(images/downloads/resume_normal.png);"></div>' +
                '<div class="download_item" data-id="' + id + '" data-action="delete" style="background-image:url(images/downloads/delete_normal.png);"></div>' +
                '</span>' +
                '</span>' +
                '</li>';
            
        html_content += cell;
    }

    $("#download_container").html(html_content);
    
        $(".download_item").click(function() {

        var $id = $(this).data("id");
        switch ($(this).data("action")) {
            case "play":
                onOfflinePlay($id);
                break;
            case "pause":
                onPause($id);
                break;
            case "resume":
                onResume($id);
                break;
            case "delete":
                onDelete($id);
                break;
        }
    });;


    $.each(g_download_progress_array, function(key, value) {

        switch (value.status) {
            case 0: //STATUS_DOWNLOADING

                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').show();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;
            case 1: //STATUS_COMPLETE
                $('#item_' + value.id + ' .progress').hide();
                $('#item_' + value.id + ' [data-action="play"]').show();
                $('#item_' + value.id + ' [data-action="pause"]').hide();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();

                value.progress = 100;

                break;
            case 2: //STATUS_PAUSE
                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').hide();
                $('#item_' + value.id + ' [data-action="resume"]').show();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;

            case 3: //STATUS_PENDING
                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').show();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;
            case 4: //STATUS_ERROR
                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').hide();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;

        }
        if ((current_page == "video") && (value.id == g_current_video) && (value.progress == 100)) {

//            $("#video_sd_button").hide();
//            $("#video_hd_button").hide();
            $("#video_download_button").hide();
            $("#video_downloading_button").hide();
            $("#video_downloaded_button").show();
        }

    });


    
    return;

    len = g_download_task_all.length;

    $(".download_item").unbind("click");

    html_content = '<div id="download_list" style="margin-left:auto; margin-right:auto;;height:97%; width:97%; border: solid 0px red;">';

    for (i = 0; i < len; i++) {
        id = g_download_task_all[i];

        cell = '<div id = "item_' + id + '" style="position:relative; margin: 10px 5px; float:left; width: 18%; height: 48%; border: solid 0px red;">' +
            '<img style="width:100%;height:90%" src="DATA/images/cover/' + id + '.png"/>' +
            '<span style="position:absolute;left:0; bottom:0px; width:100%; height: 10%">' +
            '<div class="download_item" data-id="' + id + '" data-action="play" style="background-image:url(images/downloads/play_normal.png);"></div>' +
            '<div class="download_item" data-id="' + id + '" data-action="pause" style="background-image:url(images/downloads/pause_normal.png);"></div>' +
            '<div class="download_item" data-id="' + id + '" data-action="resume" style="background-image:url(images/downloads/resume_normal.png);"></div>' +
            '<div class="download_item" data-id="' + id + '" data-action="delete" style="background-image:url(images/downloads/delete_normal.png);"></div>' +
            '</span>' +
            '<div class="progress" id="progress_' + id + '"></div>' +
            '</div>';

        html_content += cell;
    }
    html_content += "</div>";

    $("#downloadlist").html(html_content);

    $(".download_item").click(function() {

        var $id = $(this).data("id");
        switch ($(this).data("action")) {
            case "play":
                onOfflinePlay($id);
                break;
            case "pause":
                onPause($id);
                break;
            case "resume":
                onResume($id);
                break;
            case "delete":
                onDelete($id);
                break;
        }
    });;


    $.each(g_download_progress_array, function(key, value) {

        switch (value.status) {
            case 0: //STATUS_DOWNLOADING

                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').show();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;
            case 1: //STATUS_COMPLETE
                $('#item_' + value.id + ' .progress').hide();
                $('#item_' + value.id + ' [data-action="play"]').show();
                $('#item_' + value.id + ' [data-action="pause"]').hide();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();

                value.progress = 100;

                break;
            case 2: //STATUS_PAUSE
                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').hide();
                $('#item_' + value.id + ' [data-action="resume"]').show();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;

            case 3: //STATUS_PENDING
                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').show();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;
            case 4: //STATUS_ERROR
                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').hide();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;

        }
        if ((current_page == "video") && (value.id == g_current_video) && (value.progress == 100)) {

            $("#video_sd_button").hide();
            $("#video_hd_button").hide();
            $("#video_download_button").hide();
            $("#video_downloading_button").hide();
            $("#video_downloaded_button").show();
        }

    });

    return;

    StageWebViewBridge.call('getDownloadedList', function(data) {
        download_make_video_list(data);
        /*
            $.each(data, function(key, value) {
                $('#downloaded_icon_'+value.id).css("display", "inline-block"); 
            });
*/
    });
}

function setupDownloadListData(data) {
    g_download_task_all = [];
    g_download_task_finished = [];
    g_download_progress_array = [];

    len = data.length;
    for (var i = 0; i < len; i++) {
        id = data[i].id;
        g_download_task_all.push(id);

        var z = new Object;
        z.id = data[i].id;
        z.status = data[i].status;
        z.progress = data[i].progress;
        g_download_progress_array.push(z);

        if (z.status == 1) g_download_task_finished.push(z.id);
    }
}

function refreshDownloadList(callback) {

if ((typeof StageWebViewBridge)== "undefined") return;
//        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'entry_phone.html');
////        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'entry_tablet.html');

    if (g_serverPort==0) {

        StageWebViewBridge.call('serverPort', function(data) {
            g_serverPort = data;
        });
    }
    
    StageWebViewBridge.call('getDownloadedList', function(data) {
        if (data == null) data = [];
        setupDownloadListData(data);
        clearDownloadIcon($("#viewport")); 
        markDownloadIcon($("#viewport")); 
        if ((typeof callback) == 'function') {
            callback();
        }
    });
}


function download_make_video_list(data) {

    g_download_task_all = [];
    g_download_task_finished = [];
    g_download_progress_array = [];

    len = data.length;

    $(".download_item").unbind("click");

    html_content = '<div id="download_list" style="margin-left:auto; margin-right:auto;;height:97%; width:97%; border: solid 0px red;">';

    for (i = 0; i < len; i++) {
        id = data[i].id;
        g_download_task_all.push(id);

        cell = '<div id = "item_' + id + '" style="position:relative; margin: 10px 5px; float:left; width: 18%; height: 48%; border: solid 0px red;">' +
            '<img style="width:100%;height:90%" src="DATA/images/cover/' + id + '.png"/>' +
            '<span style="position:absolute;left:0; bottom:0px; width:100%; height: 10%">' +
            '<div class="download_item" data-id="' + id + '" data-action="play" style="background-image:url(images/downloads/play_normal.png);"></div>' +
            '<div class="download_item" data-id="' + id + '" data-action="pause" style="background-image:url(images/downloads/pause_normal.png);"></div>' +
            '<div class="download_item" data-id="' + id + '" data-action="resume" style="background-image:url(images/downloads/resume_normal.png);"></div>' +
            '<div class="download_item" data-id="' + id + '" data-action="delete" style="background-image:url(images/downloads/delete_normal.png);"></div>' +
            '</span>' +
            '<div class="progress" id="progress_' + id + '"></div>' +
            '</div>';

        html_content += cell;

        var z = new Object;
        z.id = data[i].id;
        z.status = data[i].status;
        z.progress = data[i].progress;
        g_download_progress_array.push(z);

        if (z.status == 1) g_download_task_finished.push(z.id);
    }
    html_content += "</div>";

    $("#downloadlist").html(html_content);


    $(".download_item").click(function() {

        var $id = $(this).data("id");
        switch ($(this).data("action")) {
            case "play":
                onOfflinePlay($id);
                break;
            case "pause":
                onPause($id);
                break;
            case "resume":
                onResume($id);
                break;
            case "delete":
                onDelete($id);
                break;

        }
    });;


    $.each(g_download_progress_array, function(key, value) {

        switch (value.status) {
            case 0: //STATUS_DOWNLOADING

                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').show();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;
            case 1: //STATUS_COMPLETE
                $('#item_' + value.id + ' .progress').hide();
                $('#item_' + value.id + ' [data-action="play"]').show();
                $('#item_' + value.id + ' [data-action="pause"]').hide();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();

                value.progress = 100;

                break;
            case 2: //STATUS_PAUSE
                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').hide();
                $('#item_' + value.id + ' [data-action="resume"]').show();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;

            case 3: //STATUS_PENDING
                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').show();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;
            case 4: //STATUS_ERROR
                $('#item_' + value.id + ' .progress').show().progressbar({
                    value: value.progress,
                    background: "#B637E6"
                });
                $('#item_' + value.id + ' [data-action="play"]').hide();
                $('#item_' + value.id + ' [data-action="pause"]').hide();
                $('#item_' + value.id + ' [data-action="resume"]').hide();
                $('#item_' + value.id + ' [data-action="delete"]').show();
                break;

        }
        if ((current_page == "video") && (value.id == g_current_video) && (value.progress == 100)) {

            $("#video_sd_button").hide();
            $("#video_hd_button").hide();
            $("#video_download_button").hide();
            $("#video_downloading_button").hide();
            $("#video_downloaded_button").show();
        }

    });
}

function onPause(id) {
    StageWebViewBridge.call('pauseDownload', null, id);

    $('#item_' + id + ' [data-action="pause"]').hide();
    $('#item_' + id + ' [data-action="resume"]').show();
    /*
        refreshDownloadList(function() {
            download_layout();
        });
*/
}

function onResume(id) {
    StageWebViewBridge.call('resumeDownload', null, id);
    $('#item_' + id + ' [data-action="pause"]').show();
    $('#item_' + id + ' [data-action="resume"]').hide();
    /*
        refreshDownloadList(function() {
            download_layout();
        });
*/
}


function onDelete(video_id) {

//        buttons: [{id: 0, label: '確定', val: 'Y', class: 'btn-success'}, {id: 1, label: '取消', val: 'N', class: 'btn-danger'}],
    new Messi('確定要刪除嗎?', {
        title: '訊息視窗',
        modal: true,
        width: '300px',
        buttons: [{id: 0, label: '確定', val: 'Y'}, {id: 1, label: '取消', val: 'N'}],
        callback: function(val) {
            if (val == 'Y') {
                    StageWebViewBridge.call('deleteVideo', null, video_id);
                    refreshDownloadList(function() {
                        download_layout();
                        if (g_download_task_all.length==0) {
                            $("#download_container").html('<div style="position: absolute; top:40%; margin:0 auto; "><h2>下載列表為空</h2></div>');
                        }
                    });
            }
        }
    });
}

function onOfflinePlay1(id, path) {
    
    var prefix = 'http://127.0.0.1:' +g_serverPort + '/';
    var h=$(document).height();
    var w=$(document).width();
    var video_h = h-20;
    var html_content = "<html><head><meta name='viewport' content='width=device-width, user-scalable=no'><script src='"+prefix+"js/jquery.min.js'><\/script><script src='"+prefix+"js/StageWebViewBridge.js'><\/script><script> function onKeyDown(code) {goBack(); } function goBack(){$('#vplayer').get(0).pause(); $('#back').css('display', 'none'); StageWebViewBridge.call('encFile', null, " + id + "); StageWebViewBridge.call('removeFile', null,'p.html');  location.replace('http://fubon.moker.com.tw/phone?download'); } <\/script></head><body style='background-color:#000;'><img id='back' src='"+prefix +"images/close_box_gray.png' style='position:fixed; left:0; top:0; height:20px; z-Index:1000;' onClick='goBack();'/><video id='vplayer' style='position:absolute; left:0; top:20px; z-Index:999' poster='"+prefix+"images/poster_360p.jpg' controls autoplay preload='yes' width='"+w+"' height='"+video_h+"'> <source src='" + id + ".mp4' type='video/mp4'></video></body></html>";

    StageWebViewBridge.call('toOriginal', function(data){
       var launch=true;
//       StageWebViewBridge.call('setOrientation', null, 1);

//        alert(html_content);
        StageWebViewBridge.call('file_put_contents', null, "p.html", html_content, launch);
   }, id ); 
}


function showWait(on_off, h) {

    if (typeof h=="undefined") h=130;

    if (on_off==1) {

        var show = '<img src="images/animal0020.gif"/><span id="waiting"></span>';

        new Messi(show, {
            title: '準備中', 
            modal: true,
            width: h+'px',
            height: '130px',
            padding: '0px',
            callback: function(val) { 
            }    
        });
    } else {
        $('.messi,.messi-modal').remove();
    }
}


function onOfflinePlay(id, path) {

    showWait(1);
    prefix = 'http://127.0.0.1:' +g_serverPort + '/';

    var w=$(document).height();
    var h=$(document).width();
    var video_h = h-40;
    //var html_content = "<html><head><meta name='viewport' content='width=device-width, user-scalable=no'><script src='"+prefix+"js/jquery.min.js'><\/script><script src='"+prefix+"js/StageWebViewBridge.js'><\/script><script> $(window).load(function(){StageWebViewBridge.call('ready');}); function onKeyDown(code) {goBack(); } function goBack(){StageWebViewBridge.call('stopPlay', null, "+ id +");} <\/script></head><body style='background-color:#000;'><img id='back' src='"+prefix +"images/close_box_gray.png' style='position:fixed; right:0; top:0; height:40px; z-Index:1000;' onClick='goBack();'/><div style='position:absolute; top:40px; left:0; right:0; bottom:0;'><video id='vplayer' poster='"+prefix+"images/poster_360p.jpg' controls autoplay preload='yes' width='100%' height='100%'> <source src='" + id + ".mp4' type='video/mp4'></video></body></html>";
    var html_content = "<html><head><meta name='viewport' content='width=device-width, user-scalable=no'><script src='"+prefix+"js/jquery.min.js'><\/script><script src='"+prefix+"js/StageWebViewBridge.js'><\/script><script> function onKeyDown(code) {goBack(); } function goBack(){StageWebViewBridge.call('stopPlay', null, "+ id +");} <\/script></head><body style='background-color:#000;'><img id='back' src='"+prefix +"images/close_box_gray.png' style='position:fixed; right:0; top:0; height:40px; z-Index:1000;' onClick='goBack();'/><div style='position:absolute; top:40px; left:0; right:0; bottom:0;'><video id='vplayer' poster='"+prefix+"images/poster_360p.jpg' controls autoplay preload='yes' width='100%' height='100%'> <source src='" + id + ".mp4' type='video/mp4'></video></body></html>";
    StageWebViewBridge.call('toOriginal', function(data){
        var url=data;
        var launch=true;
           
//           StageWebViewBridge.call('setOrientation', null, 1);  //change to landscape mode
        StageWebViewBridge.call('file_put_contents', null, "p.html", html_content, launch);

           var pathArray = url.split( '/' );
           pathArray[pathArray.length-1] = "p.html";

           var newPathname = "";
           for (i = 0; i < pathArray.length; i++) {
             newPathname += "/";
             newPathname += pathArray[i];
           }

       }, id );
}

function onPlay1(id, path, title) {

    showWait(1);
//    StageWebViewBridge.call('setOrientation', null, 0);  //change to portrait mode            

//    StageWebViewBridge.call('setOrientation', null, 1);  //change to landscape mode  
    StageWebViewBridge.call('loadURL2', null, encodeURI(CONFIG.SERVER_ROOT+"phone_play.php?i="+id+"&v="+path+"&t="+title ));
}

function onPlay(id, path) {
    prefix = '';
    if (g_serverPort != 0) {
        prefix = 'http://127.0.0.1:' + g_serverPort + '/';
    } else {
        prefix = CONFIG.SERVER_ROOT;
    }
    if ((typeof path) == 'undefined') {
        if (g_serverPort != 0) {
            v_url = prefix + id + '.mp4';
        } else {
            v_url = CONFIG.SERVER_ROOT + 'DATA/video/360p/' + id + '.mp4';

        }
    } else {
        v_url = path;
    }

    //v_url = 'file:///storage/sdcard0/FubonVideo_beta/102021909.mp4';

    var html = '<video id="vplayer" style="position:absolute;" controls autoplay preload="yes" width="95%" height="95%"> <source src=\'' + v_url + '\' type="video/mp4"></video>';

    $("#viewer").append(html).lightbox_me({
        closeClick: true,
        closeEsc: false,
        centered: true,
        overlaySpeed: 50,
        overlayCSS: {
            background: 'black',
            opacity: .5
        },
        onClose: function() {
            StageWebViewBridge.call('setOrientation', null, 0);  //change to portrait mode            
        },
        onLoad: function() {
            StageWebViewBridge.call('setOrientation', null, 1);  //change to landscape mode  
            
            setTimeout('$("#viewer").trigger("reposition");',1000),
            
            $("#vplayer").get(0).play();
        }
    });
}

function refreshDownload() {
    $("#download_container").html('<div style="position: absolute; top:45%; left:45%; "><h2>載入中</h2></div>');
    //$("#download_container").html('');

    refreshDownloadList(function() {
        download_layout();
        
        
        if (g_download_task_all.length==0) {
//alert(g_download_task_all.length);
            $("#download_container").html('<div style="position: absolute; top:40%; margin:0 auto; "><h2>下載列表為空</h2></div>');
//            $("#download_container").html('<li>下載列表為空</li>');
        }
        /*
        if (!g_download_loaded) {
            StageWebViewBridge.call('serverPort', function(data) {
            g_serverPort = data;
            });
        }
        */
        

        g_download_loaded=true;
    });

    /*
        StageWebViewBridge.call('getDownloadedList', function(data) {
            download_layout();

            len = data.length;
            for (i=0; i<len; i++) {
                StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'DATA/images/cover/'+data[i].id+'.png');
            }
        });
*/
}

function download_onDownloadClicked(data) {
    //             data = {id:102021911, url:'http://fubon.moker.com.tw/DATA/video/720p/102021911.mp4', filesize:25957492};
    StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT + 'DATA/images/cover/' + data.id + '.png');
    StageWebViewBridge.call('downloadVideo', null, data);
    refreshDownloadList(function() {
        download_layout();
    });

    //            $('#module').contents().find('html').html(localS);
}

function openURL(url) {
    StageWebViewBridge.call('openURL', null, url);
}

function saveFileWithDialog(url) {
    StageWebViewBridge.call('saveFileWithDialog', null, url);
}

function doDownload() {
//            buttons: [{id: 0, label: '確定', val: 'Y', class: 'btn-success'}], 
    if (!download_canDownload()) {
        new Messi('已達到下載上限(10支影片)', {
            title: '訊息視窗', 
            modal: true,
            width: '300px',
            padding: '0px',
            buttons: [{id: 0, label: '確定', val: 'Y'}], 
            callback: function(val) { 
            }    
        });
        return;
    }

    var video_data_sd = CONFIG.SERVER_ROOT + 'DATA/video/360p/' + g_current_video + '.mp4';
    var video_data_hd = CONFIG.SERVER_ROOT + 'DATA/video/720p/' + g_current_video + '.mp4';

    sd_mb = Math.floor(g_video_sd.filesize / 1048576);
    hd_mb = Math.floor(g_video_hd.filesize / 1048576);

    label_sd =  '一般版(SD)(' + sd_mb + 'MB)';
    label_hd =  '高清版(HD)(' + hd_mb + 'MB)';
//        buttons: [{id: 0, label: label_sd, val: 'SD', class: 'btn-success'}, {id: 1, label: label_hd, val: 'HD', class: 'btn-primary'}], 

    new Messi('', {
        title: '下載品質選擇', 
        modal: true,
        width: '320px',
        padding: '0px',
        buttons: [{id: 0, label: label_sd, val: 'SD'}, {id: 1, label: label_hd, val: 'HD'}], 
        callback: function(val) { 
            if (val == 'SD') {
                isDownload();
                download_onDownloadClicked(g_video_sd);
                $("#video_download_button").hide();
                $("#video_downloading_button").show();
                $.post("ajax/main/writeLog.php", {type:'download', id:g_current_video, user: g_user});
            } else {
                isDownload();
                download_onDownloadClicked(g_video_hd);
                $("#video_download_button").hide();
                $("#video_downloading_button").show();
                $.post("ajax/main/writeLog.php", {type:'download', id:g_current_video, user: g_user});
            } 
        }    
    });
}

function download_canDownload() {
    return (g_download_task_all.length < 10);
}

function download_ifDownloaded(id) {
    return ($.inArray(id, g_download_task_all));
}

function download_checkVideoDownloadStatus(id) {

    if ($.inArray(id, g_download_task_finished) != -1) return 1; //download complete
    if ($.inArray(id, g_download_task_all) != -1) return 2; // downloading 
    return 0; //Not in download task
}

function isDownload() {
    $('#video_metadata').append('<img src="images/downloads.png" style="position:absolute; width:31px; height:31px; right:10%; top:5px" />');
}

function onAboutClicked() {

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


    new Messi('程式版本：<span class="version_label">'+version_label+'</span><br>使用者：'+g_user, {
        title: '訊息視窗',
        modal: true,
        width: '300px',
        buttons: [{id: 0, label: '確定', val: 'Y'}],
    });
    
}

/************************* Tag Layout *****************************/
function tag_layout(id) {
    $("#tag_info").css("background", "url(DATA/images/tag/" + id + "_info.png) no-repeat center center");
    $("#tag_info").css("background-size", "contain");
    $.ajax({
        url: CONFIG.SERVER_ROOT + "tag/" + id,
        beforeSend: function() {
            $("#tag_container").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                tag_make_video_list(data.list);
            }
        }
    });
}

function tag_make_video_list(video_array) {
    var imgs1 = [];
    $.each(video_array, function(key, id) {
        var item = {
            id: id,
            url: 'DATA/images/cover/' + id + '.png'
        };
        imgs1.push(item);
    });

    $("#tag_container").html(MLayout({
        container_width: $("#tag_container").width(),
        container_height: $("#tag_container").height(),
        row: 1,
        column: 0,
        item_width: 177,
        item_height: 250,
        click_callback: "onCoverClicked",
        items: imgs1
    })).flexslider({
        animation: "slide",
        animationLoop: false,
        touch: true,
        useCSS: true,
        slideshow: false,
        controlNav: true,
        multipleKeyboard: false,
        directionNav: true
    });
}

/************************* Search Layout *****************************/
function video_layout(id) {
    $("#video_metadata").html("");
    $("#video_relate_list_container").html("");
    $("#video_container").html("");
    
    $.ajax({
        url: CONFIG.SERVER_ROOT + "video/" + id,
        beforeSend: function() {
            $("#video_container").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {

            if (data.result == "success") {
                var metadata_html = "<img src='DATA/images/cover/" + data.id + ".png' style=' margin-top: 10px;'/> &nbsp;<br/> <br/>";
                metadata_html += data.metadata1 + data.metadata2;

                $.each(data.books, function(key, value) {
                    metadata_html += '<img src="' + value.img + '" style="width:107px ; cursor:pointer;" onclick="$.post(\'ajax/main/writeLog.php\', {type:\'book\', id:'+value.id+', user: g_user}); openURL(\'' + value.url + '\');" />';
                });

                $("#video_metadata").html(metadata_html);

                if (download_ifDownloaded(id) >= 0) isDownload();

                if ($vid_obj != null) {
                    $vid_obj.dispose();
                    $vid_obj = null;
                }
                var video_html = ' <video id="video_player" class="video-js vjs-default-skin" controls preload="auto" width="100%" height="100%"> <source src="' + data.video_sd.url + '" type="video/mp4"> <p class="vjs-no-js"></p> </video> ';
                $("#video_container").html(video_html);

                //                $vid_obj = _V_("video_player");
                g_video_sd = data.video_sd;
                g_video_hd = data.video_hd;

                //               $("#video_sd_button").trigger("click");

                /*
         vjsOptions={poster:'/images/poster_360p.jpg', nativeControlsForTouch: true};
         $vid_obj =  videojs('video_player', vjsOptions, function() {
         var videoJSPlayer = this;
         videoJSPlayer.ready(function() {
             videoJSPlayer.poster(vjsOptions.poster);
         });
         });
*/
                video_make_video_list(data.related_list);
            }
        }
    });
    video_setupDownloadButtons(id);
}

function video_make_video_list(video_array) {
    var imgs1 = [];
    $.each(video_array, function(key, id) {
        var item = {
            id: id,
            url: 'DATA/images/cover150/' + id + '.png'
        };

        imgs1.push(item);
    });
    $("#video_relate_list_container").html("<div id='video_relate_list' class='flexslider'></div>");

    $("#video_relate_list").html(MLayout({
        container_width: $("#video_relate_list").width(),
        container_height: $("#video_relate_list").height(),
        row: 1,
        column: 0,
        item_width: 120,
        item_height: 170,
        click_callback: "onCoverClicked",
        items: imgs1
    })).flexslider({
        animation: "slide",
        animationLoop: false,
        touch: true,
        useCSS: true,
        slideshow: false,
        controlNav: true,
        multipleKeyboard: false,
        directionNav: true
    });
}

function video_loadVideo(type) {
    var url = (type == "video_sd") ? g_video_sd.url : g_video_hd.url;
    $("#video_player").attr("src", url);
    //             $(".vjs-big-play-button").show();
    $("#video_player").removeClass("vjs-playing").addClass("vjs-paused");
    // load the new sources
    $vid_obj.load();
    $("#div_video_html5_api").show();

}

function video_setupDownloadButtons(id) {
    
    if (typeof(download_checkVideoDownloadStatus) == "function") {
        
        code = download_checkVideoDownloadStatus(id);

        switch (code) {
            case 2: //downloading
                $("#video_sd_button").show();
                $("#video_hd_button").show();
                $("#video_download_button").hide();
                $("#video_downloading_button").show();
                $("#video_downloaded_button").hide();
                break;
            case 1: //downloaded
                $("#video_sd_button").show();
                $("#video_hd_button").show();
                $("#video_download_button").hide();
                $("#video_downloading_button").hide();
                $("#video_downloaded_button").show();
                break;
            case 0: //not in download task
                $("#video_sd_button").show();
                $("#video_hd_button").show();
                $("#video_download_button").show();
                $("#video_downloading_button").hide();
                $("#video_downloaded_button").hide();
                break;
        }
    }

}

/************************* Search Layout *****************************/
function search_layout(query) {
    $("#search_term").text(query);
    $.ajax({
        url: CONFIG.SERVER_ROOT + "search/" + query,
        beforeSend: function() {
            $("#search_container").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                $("#search_hit").text(data.list.length);
                search_make_video_list(data.list);
            }
        }
    });
}

function search_make_video_list(video_array) {
    var w = $("#search_container").width();
    var h = $("#search_container").height();

    var imgs1 = [];
    $.each(video_array, function(key, id) {
        var item = {
            id: id,
            url: 'DATA/images/cover/' + id + '.png'
        };
        imgs1.push(item);
    });
    $("#search_container").remove();
    $("#search_content").append("<div id='search_container'></div>");

    $("#search_container").html(MLayout({
        container_width: w,
        container_height: h,
        row: 2,
        column: 5,
        click_callback: 'onCoverClicked',
        items: imgs1
    })).flexslider({
        animation: "slide",
        slideshow: false,
        touch: true,
        animationLoop: true,
        useCSS: true,
        itemHeight: h,
        itemWidth: w,
        controlNav: true,
        directionNav: false,
    });
}

/************************* Category Layout *****************************/
function category_layout(id) {
    $.ajax({
        url: CONFIG.SERVER_ROOT + "category/" + id,
        beforeSend: function() {
            $("#category_container").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                category_video_list[0] = data.list.order_0;
                category_video_list[1] = data.list.order_1;
                category_make_video_list(0);
            }
        }
    });
}

function category_make_video_list(list) {
    if (list === 0) {
        $("#sort_hot").attr("src", "images/index/index_icon01_press.png");
        $("#sort_date").attr("src", "images/index/index_icon02_normal.png");
    } else if (list === 1) {
        $("#sort_hot").attr("src", "images/index/index_icon01_normal.png");
        $("#sort_date").attr("src", "images/index/index_icon02_press.png");
    }

    var video_array = category_video_list[list];
    var nVideos = video_array.length;
    var h = $("#category_container").height();
    var w = $("#category_container").width();

    var imgs1 = [];
    $.each(video_array, function(key, id) {
        var item = {
            id: id,
            url: 'DATA/images/cover/' + id + '.png'
        };
        imgs1.push(item);
    });

    $("#category_container").remove();
    $("#category_content").append("<div id='category_container'></div>");

    $("#category_container").html(MLayout({
        container_width: $("#category_container").width(),
        container_height: $("#category_container").height(),
        row: 2,
        column: 5,
        click_callback: 'onCoverClicked',
        items: imgs1
    })).flexslider({
        animation: "slide",
        slideshow: false,
        touch: true,
        animationLoop: true,
        useCSS: true,
        itemHeight: h,
        itemWidth: w,
        controlNav: true,
        directionNav: true
    });
}

/************************* Home Layout *****************************/
function home_makeBanner() {
    home_processBanner(1);
}

function home_processBanner(items) {
    var total = $('#home_banner_slider li').length;
    if (total<=0) return;

    $('#home_banner_slider').flexslider({
        animation: "slide",
        touch: true,
        useCSS: false,
        animationLoop: true,
        smoothHeight: false,

        before: function(slider) {
            home_hidePlayButton();

/*
            next = slider.slides[slider.currentSlide%total];
            next2 = slider.slides[(slider.currentSlide+1)%total];

            item = $("img", next)[0];
            item2 = $("img", next2)[0];

            if (item.src=='') item.src=$(item).data("src");
            if (item2.src=='') item2.src=$(item2).data("src");
*/
        },
        start: function(slider) {
            var current = slider.slides[slider.currentSlide];

            if ($(current).data("video") == true) {
                home_showPlayButton($(current).data("id"));
            }
        },
        after: function(slider) {
            var current = slider.slides[slider.currentSlide];

            if ($(current).data("video") == true) {
                home_showPlayButton($(current).data("id"));
            }
        },
        controlNav: true,
        multipleKeyboard: false,
        directionNav: false,
        itemMargin: 5
    });
}

function home_prepareHotArea() {

    $.ajax({
        url: CONFIG.SERVER_ROOT + "list/hot1",
        beforeSend: function() {
            $("#new_area_video_list").html('<img src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                home_make_video_list($("#new_area_video_list"), data.list);
            }
        }
    });
    $.ajax({
        url: CONFIG.SERVER_ROOT + "list/hot2",
        beforeSend: function() {
            $("#hot_area_video_list").html('<img src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                home_make_video_list($("#hot_area_video_list"), data.list);
            }
        }
    });

}

function home_make_video_list($target, list) {

    var imgs1 = [];
    $.each(list, function(key, id) {
        var item = {
            id: id,
            url: 'DATA/images/cover150/' + id + '.png'
        };
        imgs1.push(item);
    });

    $target.html(MLayout({
        container_width: $target.width(),
        container_height: $target.height(),
        row: 1,
        column: 0,
        item_width: 120,
        item_height: 170,
        click_callback: 'onCoverClicked',
        items: imgs1
    })).flexslider({
        animation: "slide",
        animationLoop: false,
        touch: true,
        useCSS: true,
        slideshow: false,
        before: function(slider) {
            var $b = $("#new_area_video_list");
            var $dir_left = ($target.is($b)) ? $("#new_prev") : $("#hot_prev");
            var $dir_right = ($target.is($b)) ? $("#new_next") : $("#hot_next");

            $dir_left.css("visibility", (slider.animatingTo == 0) ? "hidden" : "visible");
            $dir_right.css("visibility", (slider.animatingTo == slider.count - 1) ? "hidden" : "visible");
        },
        controlNav: false,
        multipleKeyboard: false,
        directionNav: false
    });

}

function home_hidePlayButton(id) {
    $(".play_button").css("display", "none");
    $(".play_button").off("click");
}

function home_showPlayButton(id) {
    if (id > 0) {
        $(".play_button").css("display", "block");
        $(".play_button").click(function() {

            onPlay1(0, CONFIG.SERVER_ROOT+'DATA/video/banner/'+id+'.mp4', "廣宣影片");
            return;
            $("#home_banner_slider").flexslider("pause");

            $('#viewer').append('<video id="vplayer" style="position:absolute;" controls autoplay preload="yes" width="95%" height="95%"> <source src="' + CONFIG.SERVER_ROOT + '/DATA/video/banner/' + id + '.mp4" type="video/mp4"></video>');

            //                        home_player = videojs("vplayer", {}, function(){ });

            //                        home_player.src('/DATA/video/banner/'+id+'.mp4');
            //                        $("#vplayer").append('<source src="/DATA/video/banner/'+id+'.mp4" type="video/mp4">');

            $("#viewer").lightbox_me({
                closeClick: true,
                closeEsc: false,
                centered: true,
                overlaySpeed: 50,
                overlayCSS: {
                    background: 'black',
                    opacity: .5
                },
                onLoad: function() {
                    $("#vplayer").get(0).play();
                }
            });

        });
    }
}

function home_onPlay(id) {

}

function setAutoPlay(s) {
    switch (s) {
        case true:
            $('.coverflow').roundabout("startAutoplay");
            break;
        case false:
            $('.coverflow').roundabout("stopAutoplay");
            break;
    }
}

function onCoverClicked(id) {
    onMenuClicked("video", "video", id);
}

function onWindowSize() {

    w = $(window).width();
    h = $(window).height();


    if (w < 900) w = 900;
    if (h < 550) h = 550;

    $('#wrapper').width(w);
    $('#wrapper').height(h);
}

function onLightboxClose() {
    $("#viewer").trigger("close");

    //if (!home_player.paused()) home_player.dispose();
    //            home_player.dispose();

    
    $("#vplayer").get(0).pause();
    
//    document.getElementById('vplayer').pause();
    $("#vplayer").remove();
    $(".lb_overlay").remove();

    if (current_page == "menu_home") {
        $("#home_banner_slider").flexslider("play");
    }
}

function updateList($dom, list) {
    
    var html = '';
//        '<img src="http://fubon.moker.com.tw/DATA/images/cover150/' + value.id +'.png" style="height:90%;"/>' +
    
    $.each(list, function(key, value) {
        var class_name = (download_ifDownloaded(value.id)>=0)?'inDownload':''; 

        html += '<li class="v_item item_'+value.id+'">';
        html += '<div class="v_cover '+class_name+'" style="background-image: url(DATA/images/cover150/' + value.id + '.png);"></div>';
        html += '<div class="v_desc">' + value.title  +'<br/>影片長度：' + value.duration + '</div>';
        html += '<div class="v_action" onClick="onMenuClicked(\'video\', \'video\', ' + value.id +');"></div>';
        html += '</li>';
        
/*
        html += '<li style="height:120px; width:100%; margin: 5px; position:relative; border-bottom: solid 3px #aaa">' +
        '<span style="position:absolute; width:100%; height:100%; ">' +
        '<div style="position:absolute; width:80px; left:0; top:0; bottom:0; text-align:center;">' +
        '<img src="http://fubon.moker.com.tw/DATA/images/cover150/' + value.id +'.png" style="max-height:90%;"/>' +
            
            
        '</div>' +
        '<div style="position:absolute; left:85px; right:50px; top:10px; bottom:10px; overflow:hidden">' + value.title  +'<br/>影片長度：' + value.duration + '</div>' +
        '<div style="position:absolute; width:50px; right:0; top:0; bottom:0; text-align:center;">' +
            '<img class="detail" data-id="' + value.id+ '" src="images/phone/content/content_icon_01.png" style="width:40px; height:40px; margin-top:40px;" onClick="onMenuClicked(\'video\', \'video\', ' + value.id +');" />' +
        '</div>' +
        '</span>' +
        '</li>';
*/
    });
    
//        '<img src="http://127.0.0.1:'+g_serverPort + '/images/cover150/' + value.id +'.png" style="max-height:90%;"/>' +
    
      //       '<img class="detail" data-id="' + value.id+ '" src="images/phone/content/content_icon_01.png" style="width:40px; height:40px; margin-top:40px;"/>' +
//'<img class="jail" src="images/ajax-loader.gif" data-src="http://fubon.moker.com.tw/DATA/images/cover150/' + value.id +'.png" style="max-height:90%;"/>' +

    $dom.html(html);
    
    if ($dom.is($("#home_container"))) {
        $scroll_target = $("#home_content");
    } else {
        $scroll_target = $dom;
    }
/*
    $dom.find("img.jail").jail({
            triggerElement:$scroll_target,
            event: 'scroll'
    });
  */  
    $scroll_target.scrollTop();
    
}

//function onCategoryClicked(area, type, target) {
function loadCategoryContent(target) {
/*
    $.each(categoryArray, function(key, val) {
        if (val.id == area) {
            selection = val;
            $('#' + val.id).attr('src', CONFIG.SERVER_ROOT+val.press);
        } else {
            $('#' + val.id).attr('src', CONFIG.SERVER_ROOT+val.normal);
        }

    });
*/
    $.ajax({
        url: CONFIG.SERVER_ROOT + "category2/" + target,
        beforeSend: function() {
            $("#category_container").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                category_video_list[0] = data.list.order_0;
                category_video_list[1] = data.list.order_1;

                g_category_page1 = data.html_0;
                g_category_page2 = data.html_1;
                onCategorySortClicked(0);
            }
        }
    });
    
}


//function onTagClicked(area, type, target) {
function loadTagContent(target) {
/*
    $.each(tagArray, function(key, val) {
        if (val.id == area) {
            selection = val;
            $('#' + val.id).attr('src', CONFIG.SERVER_ROOT+val.press);
        } else {
            $('#' + val.id).attr('src', CONFIG.SERVER_ROOT+val.normal);
        }

    });   
*/

//alert(target);

    if (target=="-1") {
        g_tag_page1='<li class="v_item"><div class="v_cover" style="background-image:url(DATA/images/hiring/cover/1.png);"></div><div class="v_desc">【房仲業銷售業務員】<br/>增員資料夾</div><div class="v_action" data-id="-1"></div></li>';
        g_tag_page2=g_tag_page1;

        //document.getElementById('tag_container').innerHTML = g_tag_page1;
        $("#tag_container").html(g_tag_page1);

        return;
    }
    
    $.ajax({
        url: CONFIG.SERVER_ROOT + "tag2/" + target,
        beforeSend: function() {
            $("#tag_container").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                //updateList($("#tag_container"), data.list);
                tag_video_list[0] = data.list.order_0;
                tag_video_list[1] = data.list.order_1;                

                g_tag_page1 = data.html_0;
                g_tag_page2 = data.html_1;

                //onCategorySortClicked(0);
                onTagSortClicked(0);
            }
        }
    });    
    
    
}

function onSearchClicked(area, type, target) {
/*
    $.each(tagArray, function(key, val) {
        if (val.id == area) {
            selection = val;
            $('#' + val.id).attr('src', CONFIG.SERVER_ROOT+val.press);
        } else {
            $('#' + val.id).attr('src', CONFIG.SERVER_ROOT+val.normal);
        }

    });   
*/
    
    $.ajax({
        url: CONFIG.SERVER_ROOT + "search2/" + target,
        beforeSend: function() {
            $("#search_container").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                $("#search_hit").text(data.list.length);
                $("#search_term").text(target);                
//alert(data.list.length);
                updateList($("#search_container"), data.list);
            }
        }
    });    
}

function onCategorySortClicked(select) {
document.getElementById('category_container').innerHTML = '';
     if (select==0) {
        $("#category_hot_btn").attr("src", $("#category_hot_btn").data("press"));
        $("#category_new_btn").attr("src", $("#category_new_btn").data("normal"));

//        $("#category_container").html(g_category_page1);
document.getElementById('category_container').innerHTML = g_category_page1;

    } else {
        $("#category_hot_btn").attr("src", $("#category_hot_btn").data("normal"));
        $("#category_new_btn").attr("src", $("#category_new_btn").data("press"));
//        $("#category_container").html(g_category_page2);
document.getElementById('category_container').innerHTML = g_category_page2;
    }
    markDownloadIcon($("#category_container")); 

//    var list = category_video_list[select];
//    updateList($("#category_container"), list);
}

function onTagSortClicked(select) {
     if (select==0) {
        $("#tag_hot_btn").attr("src", $("#tag_hot_btn").data("press"));
        $("#tag_new_btn").attr("src", $("#tag_new_btn").data("normal"));
//        $("#tag_container").html(g_tag_page1);
document.getElementById('tag_container').innerHTML = g_tag_page1;
        
    } else {
        $("#tag_hot_btn").attr("src", $("#tag_hot_btn").data("normal"));
        $("#tag_new_btn").attr("src", $("#tag_new_btn").data("press"));
//        $("#tag_container").html(g_tag_page2);
document.getElementById('tag_container').innerHTML = g_tag_page2;
    }
    markDownloadIcon($("#tag_container")); 

//    var list = tag_video_list[select];
//    updateList($("#tag_container"), list);
}
function clearDownloadIcon($area) {
    var $item = $(".inDownload", $area);
    $item.removeClass("inDownload");

}

function markDownloadIcon($area) {

    var len = g_download_task_all.length;

    if (len<=0) return;
    
    for (var i = 0; i < len; i++) {
        id = g_download_task_all[i];
        var $item = $(".item_"+id+">.v_cover", $area);
            $item.removeClass("inDownload");
            $item.addClass("inDownload");
    }
}

function onHotClicked(area, type, target) {
 
    if (area=='hot1') {
        $("#new_area_txt").attr("src", "images/phone/0526/mobile_topbar_01_word_over.png");
        $("#hot_area_txt").attr("src", "images/phone/0526/mobile_topbar_02_word_normal.png");
        $("#home_container").html(g_hot_page1);
        
    } else {
        $("#new_area_txt").attr("src", "images/phone/0526/mobile_topbar_01_word_normal.png");
        $("#hot_area_txt").attr("src", "images/phone/0526/mobile_topbar_02_word_over.png");
        $("#home_container").html(g_hot_page2);
    }

    markDownloadIcon($("#home_container")); 
return;
    $.ajax({
        url: CONFIG.SERVER_ROOT + "list2/" + area,
        beforeSend: function() {
            $("#home_container").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {               
                updateList($("#home_container"), data.list);
            }
        }
    });    
}


function onReturnClicked() {
    $("#back_container").css("display", "none");

    if (current_page=="video") {
        onMenuClicked(previous_page, "return");
    }

}

function onDetailClicked(id, from) {
 

    $("#back_container").css("display", "block");

    $("#video_cover").css("visibility", "hidden");
    $("#video_buttons").css("display", "none");
    $("#video_name").html('<br/>');
    $("#metadata1").html('');
    $("#video_metadata").html('<img id="video_cover" style="height:140px; margin-top:15px;"/>');
    $.ajax({
//        url: CONFIG.SERVER_ROOT + "video2/" + id,
        url: CONFIG.SERVER_ROOT+"video2/"+id+"?r="+g_rank+"&u="+g_unitcode+"&i="+g_user, 
        beforeSend: function() {
          //  $("#metadata1").html('<img class="loading" src="images/loading.gif" />');
            $("#metadata2").html('<img class="loading" src="images/loading.gif" />');
            
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {               
                
                
                $("#video_name").html('<p>'+data.title+'</p>');
                $("#video_cover").attr("src", CONFIG.SERVER_ROOT + "/DATA/images/cover150/" + data.id + ".png");
//                $("#video_cover").css("visibility", "visible");

                $("#video_buttons").css("display", "block");
                
                var book_link='';
                $.each(data.books, function(key, value) {
                    
                    book_link += '<img src="'+value.img+'" style="cursor:pointer;" onclick="$.post(\'ajax/main/writeLog.php\', {type:\'book\', id:'+value.id+', user: g_user}); openURL(\'' + value.url+'&m=1\');" />';
                });             
                if (book_link!='') {
                    data.metadata2 = data.metadata2 + book_link;
                }
                $("#metadata1").html(data.metadata1);
                $("#metadata2").html(data.metadata2);
                
                if (download_ifDownloaded(id) >= 0) isDownload();
                
                video_setupDownloadButtons(data.id);
                g_video_sd = data.video_sd;
                g_video_hd = data.video_hd;
                
                $("#video_sd_button").unbind("click").bind("click", function() {
                    onPlay1(data.id, CONFIG.SERVER_ROOT + "DATA/video/360p/" + data.id + ".mp4", data.title);
                });
                $("#video_hd_button").unbind("click").bind("click", function() {
                    onPlay1(data.id, CONFIG.SERVER_ROOT + "DATA/video/720p/" + data.id + ".mp4", data.title);
                });

            }
        }
    });
    
}

/*
function loadCategories() {
    $.ajax({
        url: CONFIG.SERVER_ROOT + "list/category",
        beforeSend: function() {
            $("#categories").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                items = data.list;
                var html = "";
                var dom_id;
                $.each(items, function(key, item) {
                    dom_id = 'category_button_' + item.id;
                    categoryArray.push({
                        id: dom_id,
                        normal: item.normal,
                        press: item.press,
                        link: 'category',
                        target_id: item.id
                    });
//                    html += "<li style='float:left; width:142px; height:37px;' ><img id='" + dom_id + "' onClick='onCategoryClicked(\"" + dom_id + "\", \"category\", " + item.id + ")' src='" + CONFIG.SERVER_ROOT + item.normal + "' /></li>";
                    html += "<img id='" + dom_id + "' style='width:142px; height:42px;display:inline-block;' onClick='onCategoryClicked(\"" + dom_id + "\", \"category\", " + item.id + ")' src='" + CONFIG.SERVER_ROOT + item.normal + "' />";

                });
                $("#categories").html(html);
                g_category_loaded=true;
                
                $("#categories img").eq(0).trigger("click");

                

            }
        }
    });
}

function loadTags() {
    $.ajax({
        url: CONFIG.SERVER_ROOT + "list/tag",
        beforeSend: function() {
            $("#tags").html('<img class="loading" src="images/loading.gif" />');
        },
        type: 'post',
        dataType: 'jsonp',
        crossDomain: true,
        success: function(data) {
            if (data.result == "success") {
                items = data.list;
                html = "";
                $.each(items, function(key, item) {
                    dom_id = 'tag_button_' + item.id;
                    tagArray.push({
                        id: dom_id,
                        normal: item.normal,
                        press: item.press,
                        link: 'tag',
                        target_id: item.id
                    });
                    //html += "<li style='float:left; width:150px; height:50px;'><img id='" + dom_id + "' onClick='onTagClicked(\"" + dom_id + "\", \"tag\", " + item.id + ")' src='" +CONFIG.SERVER_ROOT + item.normal + "' /></li>";
                    
                    html += "<img id='" + dom_id + "' style='width:150px; height:50px;display:inline-block;' onClick='onTagClicked(\"" + dom_id + "\", \"category\", " + item.id + ")' src='" + CONFIG.SERVER_ROOT + item.normal + "' />";
                });
                $("#tags").html(html);
                g_tag_loaded=true;

                $("#tags  img").eq(0).trigger("click");
            }
        }
    });
}

*/

function onMenuClicked(area, type, target) {
    var link = '';

    var selection;
    /*
    $.each(buttonArray, function(key, val) {
        if (val.id == area) {
            selection = val;
            $('#' + val.id).attr('src', val.press);
        } else {
            $('#' + val.id).attr('src', val.normal);
        }

    });
      */      
    
    if ((area=='search') && (target == '')) return;

    if ((type!="return") && (current_page == "video")) {
        $("#video_container").html("");
    }
/*
    $("#home_content").css("display", "none");
    $("#category_content").css("display", "none");
    $("#tag_content").css("display", "none");
    $("#video_content").css("display", "none");
    $("#search_content").css("display", "none");
    $("#download_content").css("display", "none");
*/
/*
    $home_content.css("display", "none");
    $category_content.css("display", "none");
    $tag_content.css("display", "none");
    $video_content.css("display", "none");
    $search_content.css("display", "none");
    $download_content.css("display", "none");
*/
/*
    $home_content.css("visibility", "hidden");
    $category_content.css("visibility", "hidden");
    $tag_content.css("visibility", "hidden");
    $video_content.css("visibility", "hidden");
    $search_content.css("visibility", "hidden");
    $download_content.css("visibility", "hidden");
*/

    $("#back_container").css("display", "none");
    switch (area) {
        case 'menu_home':
//            $home_content.css("visibility", "visible");

    $("#viewport>ul").css("margin-left", 0);
            break;
        case 'menu_category':
//            category_layout(target);
//            $category_content.css("visibility", "visible");
offset = g_width*1;
    $("#viewport>ul").css("margin-left", "-"+offset+ "px");
            //$category_content.show();
            if (!g_category_loaded) {
                initCategory();
            }
            break;
        case 'menu_tag':
//            tag_layout(target);
offset = g_width*2;
            $("#viewport>ul").css("margin-left", "-"+offset+"px");
            if (!g_tag_loaded) {
                initTag();
            }
            break;
        case 'search':
//            search_layout(target);
            if (type!="return") onSearchClicked(area, type, target);
//            $search_content.css("visibility", "visible");
offset = g_width*3;
            $("#viewport>ul").css("margin-left", "-"+offset+"px");
            
            break;
        case 'video':
if (target>0) {
//            video_layout(target);
            g_current_video = target;
            onDetailClicked(target, current_page);
//            $video_content.css("visibility", "visible");
offset = g_width*4;
} else {
offset = g_width*6;
}
            $("#viewport>ul").css("margin-left", "-"+offset+"px");
            break;
        case 'menu_download':
            //download_layout(target);
            if (!g_download_loaded) refreshDownload();
    
            //$download_content.css("visibility", "visible");
offset = g_width*5;
            $("#viewport>ul").css("margin-left", "-"+offset+"px");
            break;
        default:
            //TweenLite.to($("#home"), 0.5, {top:"1500px", bottom:"-1500px"}); 
            $("#home").css("visibility", "visible");
    }
    previous_page = current_page;
    current_page = area;
}



/***************** Bridge Functions ******************/

function readyForDownload(data) {
    //alert("ready");
if ((typeof StageWebViewBridge)== "undefined") return;
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_phone.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_tablet.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'sso_mobile.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'js/config.js');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'js/messi.min.js');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'css/messi.min.css');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_phone_o365_release.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_tablet_o365_release.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_desktop_o365_release.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_phone_o365_beta.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_tablet_o365_beta.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_desktop_o365_beta.html');
    if ((typeof StageWebViewBridge) == "object" ) {
//        alert("download");
        if (data == null) data = [];
        setupDownloadListData(data);
        download_layout();
        markDownloadIcon($("#viewport")); 

        StageWebViewBridge.call('startDownload');
        StageWebViewBridge.call('serverPort', function(data) {
            g_serverPort = data;
        });


        StageWebViewBridge.call('setConfig', null, "disk_space_limit", "200000000");
    // StageWebViewBridge.call('getConfig', function(data) { alert(data);}, "data_version");

/*
        StageWebViewBridge.call('getVersion', function(data) {
            g_appVersion = data;
        });
*/


        StageWebViewBridge.call('reportLog', function(data) {
        //                $.post("ajax/main/writeLog.php", {type:'offline', user:'<?php echo $_SESSION['user_id'];?>', content: JSON.stringify(data)}, function () { });
        });

            StageWebViewBridge.call('getVersion', function(data) {
                g_appVersion = data;

               var link;
                if (g_ua.match(/iPhone/i)) {
                    link='itms-services://?action=download-manifest&url=https://fubonevideo.moker.com/downloads/beta_1.0.3.0806.1.plist';
                    if (g_appVersion<'1.0.3.1006.1') {
                        showForceUpgradeMessage(g_appVersion, '1.0.4.0326.1');
                    } else if (g_appVersion<'1.0.4.0326.1')  {
                        showUpgradeMessage(g_appVersion, '1.0.4.0326.1');
                    }
                } else {
                    link='https://fubonevideo.moker.com/downloads/FubonVideoUAT_1.0.3.0806.1.apk';
                    if (g_appVersion<'1.0.3.0808.1') {
                        showUpgradeMessage(g_appVersion, '1.0.3.0808.1');
                    }
                }

/*
                if (g_appVersion>'1.0.3.0806.1') {
                    link='https://fubonevideo.moker.com/downloads/beta';
                    doUpgrade(g_appVersion, '1.0.3.0806.1', link);
                } else if (g_appVersion<'1.0.3.0806.1') {
                    if (g_ua.match(/iPhone/i)) {
                        doUpgrade(g_appVersion, '1.0.3.0806.1', link);
                    } else {
                        doUpgrade2(g_appVersion, '1.0.3.0806.1', link);
                    }
                }
*/

            });
    }

}
        function showUpgradeMessage(cv, nv) {

            new Messi('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/>請至「行動業務市集」更新。', {
                title: '訊息視窗', 
                modal: true,
                width: '300px',
                padding: '10px',
                buttons: [{id: 0, label: "OK", val: 'Y'}], 
                callback: function(val) { 
                }    
            });
        }
        function showForceUpgradeMessage(cv, nv) {

            new Messi('<p style="color:red; font-size:1.2em">富邦新視界有新版本：修正iOS 8.0版問題</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/>請移除本版APP, 並至「行動業務市集」下載新版。', {
                title: '訊息視窗', 
                modal: true,
                width: '300px',
                padding: '10px',
                buttons: [{id: 0, label: "OK", val: 'Y'}], 
                callback: function(val) { 
                }    
            });
        }


        function doUpgrade2(cv, nv, download_link) {

        new Messi('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/>您所使用的版本已失效，必須升級到最新版本。', {
            title: '訊息視窗', 
            modal: true,
            width: '300px',
            padding: '10px',
            buttons: [{id: 0, label: "升級到最新版程式", val: 'Y'}], 
            callback: function(val) { 
                if (val == 'Y') {
                    StageWebViewBridge.call('doDownloadFile', null, download_link, 'fv.apk');
                    showWait(1, 220);
                }
            }    
        });

/*
            $("#dialog-confirm").attr('title', '訊息視窗').html('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/>您所使用的版本已失效，必須升級到最新版本。選擇直接升級時，系統會在背景下載新版程式完成後自動安裝').dialog({
                  resizable: false,
                  closeOnEscape: false,
                  open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog || ui).hide(); },
                  height:440,
                  width:300,
                  modal: true,
                  buttons: {
                    "下載新版安裝程式": function() {
                         StageWebViewBridge.call('openURL', null, download_link); 
                         $( this ).dialog( "close" );
                    },
                    "直接升級(測試)": function() {
                         StageWebViewBridge.call('doDownloadFile', null, 'https://fubonevideo.moker.com/downloads/FubonVideo1.2.9.apk', 'fv1.2.9.apk');
                         showWait(1);
                         $( this ).dialog( "close" );
                    }    
                  }    
              });  
*/
        }
        function downloadProgress(name, progress) {

            $('#waiting').html(progress + "%");
            if (progress>=100) showWait(0);
        }

        function doUpgrade(cv, nv, download_link) {
        new Messi('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/>您所使用的版本已失效，必須升級到最新版本。', {
            title: '訊息視窗', 
            modal: true,
            width: '300px',
            padding: '10px',
            buttons: [{id: 0, label: "升級到最新版程式", val: 'Y'}], 
            callback: function(val) { 
                if (val == 'Y') {
                      StageWebViewBridge.call('openURL', null, download_link);
                }
            }    
         });

/*
            $("#dialog-confirm").attr('title', '訊息視窗').html('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/><br/>您所使用的版本已失效，必須升級到最新版本。').dialog({                  resizable: false,                  closeOnEscape: false,
                  open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog || ui).hide(); },
                  height:440,
                  width:300,
                  modal: true,
                  buttons: {
                    "下載新版安裝程式": function() {
                         StageWebViewBridge.call('openURL', null, download_link); 
                      $( this ).dialog( "close" );
                    }    
                  }    
              });
*/
        }

function updateDownloadList(id, progress) {
    if (progress != 100) {
        //$("#progress_"+id).progressbar({ value: progress, background: "#B637E6" });
        $("#item_" + id + " .progress").progressbar({
            value: progress,
            background: "#B637E6"
        });
    } else {
        refreshDownloadList(function() {
            download_layout();
        });
    }
}

function onKeyDown(code) {

    if (code ==16777238) {


        if (current_page == 'video') {
            onReturnClicked();
            return;

        }
//            buttons: [{id: 0, label: '確定', val: 'Y', class: 'btn-success'}, {id: 1, label: '取消', val: 'N', class: 'btn-danger'}], 
        new Messi('確定要離開嗎?', {
            title: '訊息視窗', 
            modal: true,
            width: '300px',
            padding: '10px',
            buttons: [{id: 0, label: '確定', val: 'Y'}, {id: 1, label: '取消', val: 'N'}], 
            callback: function(val) { 
                if (val == 'Y') {
                    StageWebViewBridge.call('exitApp');
                }
            }    
        });
    }

}

function showMessage(msg_id) {
    switch (msg_id) {
        case 0: // LOW SPACE
            msg = "磁碟空間不夠，已暫停下載工作";
            break;
        default:
            msg = msg_id;
    }

//        buttons: [{id: 0, label: '確定', val: 'Y', class: 'btn-success'}], 
    new Messi(msg, {
        title: '訊息視窗', 
        modal: true,
        width: '250px',
        padding: '0px',
        buttons: [{id: 0, label: '確定', val: 'Y'}], 
        callback: function(val) { 
        }    
    });
}

function doSearch() {
        onMenuClicked('search', 'search', $('#query').val());
}

function toggleSearch() {
    $q = $("#query_container");
    
    if ($q.css('display') == 'block') {
        $q.css('display', 'none');
        $q.data("show", "0");

    } else {
        $q.css('display', 'block');
        $q.data("show", "1");
        $('#query').focus();
    }

}
