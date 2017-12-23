<?php
$params = "";
$maxHeight = 0;

if (isset($_REQUEST['h'])) $maxHeight = $_REQUEST['h'];
if (isset($_REQUEST['id'])) $params.="&i=".$_REQUEST['id'];
if (isset($_REQUEST['sid'])) $params.="&s=".$_REQUEST['sid'];
if (isset($_REQUEST['rank'])) $params.="&r=".$_REQUEST['rank'];
if (isset($_REQUEST['unitcode'])) $params.="&u=".$_REQUEST['unitcode'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <title>富邦新視界</title>
    <link rel="stylesheet" href="css/ui/jquery-ui.min.css" />
    <style>
        body {
            /*
                        background-color: #CECECE;
            */
            background-color: #000;
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
            width: 2px;
        }

        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            border-radius: 1px;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 11px;
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
            /*
                        background: url(images/top_bg_pattern.png) repeat-x;
            */
            background-color: #EDEDED;
            /*
                        text-align:center;
            */
            height:45px;

            z-Index:10;
            left:0;
            right:0;
        }

        #home_button {
            cursor:pointer;
        }

        #dialog-confirm {
            display:none;
            z-Index:1000;
        }

        #downloadpanel {
            margin:0;
            background-color:#EDEDED;

            position:absolute;
            left: 0;
            top:48px;
            bottom:0px;
            right:0px;
            margin-left:0;
        }
        #downloadlist {
            position:absolute;
            left: 15px;
            top:70px;
            bottom:5px;
            right:15px;
            overflow-x: hidden;
            overflow-y: auto;
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

        #viewer {
            display:none;
            position:absolute;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #4C4C4C), color-stop(1, #020202));
            width:80%; height:80%;
            padding: 30px;
            border-radius:10px;
        }


        .dialog {
            background-color: rgb(238, 239, 239);
            display:none;
        }
        .forget_option {
            color: #5D9ABC;
        }
        #rememberAccountAlert {
            background-color: rgb(238, 239, 239);
            display:none;
        }
        .change_item {
            text-align: right;
            color: #5D9ABC;
            font-size:1.4em;
        }

        .o365_not_activated {
            display:block;
        }
        .o365_activated {
            display:none;
        }

        }
        #home_button {
            position:absolute; left:20px; top:5px; height:35px;
        }

        #fubon_logo {
            position:absolute; left:100px; height:35px; top:5px;
        }

        #login {
            position: absolute; left: 50%; top: 50%; margin-left: -150px; margin-top: -150px; width: 300px; height: 325px; background-color: #EEEFEF; border-radius: 15px; display: none;
        }

        #login_logo {
            position:absolute; left:86px; top:10px; width:120px; height:25px;
        }

        /*#account_image {
            position : absolute; left: 20px; top: 45px; width: 54px; height: 26px;
        } */

        #account_image {
            position : absolute; left: 20px; top: 35px; width: 54px; height: 26px;
        }

        #password_image {
            position : absolute; left: 20px; top: 80px; width: 54px; height: 26px;
        }

        #captcha_image {
            position : absolute; left: 20px; top: 105px; width: 54px; height: 26px;
        }

        #validateImage {
            position:absolute; border: dashed 2px #ccc; left:155px; width:54px; height:26px; top:110px
        }

        #changecode {
            position:absolute; width:72px; left: 225px; top:110px;cursor:pointer;
        }

        #btn {
            position:absolute; width:72px; left: 70px; top:150px ;cursor:pointer;
        }

        #account_policy {
            position:absolute; width:100px; left: 150px; top:150px ; font-size:0.8em; text-decoration:underline; cursor:pointer; display:none;
        }

        #forget_password_button {
            position:absolute;  left: 225px; width: 72px; top:78px ;cursor:pointer;
        }

        /*#remember_account_prompt {
            position:absolute; width:72px; left: 225px; top:50px ; font-size:0.7em; cursor:pointer;
        }
        */

        #remember_account_prompt {
            position:absolute; width:80px; left: 70px; top:62px ; font-size:12px; color:#777; cursor:pointer;
        }

        #remember_account_checkbox {
            /*margin:0 auto;width: 15px;height: 15px;*/ margin-right:10px;
        }

        /*#user {
            position: absolute; left:70px; width: 135px; top:50px; font-size:12pt;
        }
        */

        #user {
            position: absolute; left:70px; width: 128px; top:38px; font-size:12px; height:14px;
        }

        #pass {
            position: absolute; left:70px; width: 128px;  top:85px; font-size:12px; height:14px;
        }

        #validateCode {
            position: absolute; left:80px; top:110px; width:60px; font-size:12px; height:14px;
        }

        #offline_panel {
            position:absolute; left:0px; bottom:0px; height:55px;  width:100%; background: rgba(73,155,234,1); background: -webkit-linear-gradient(left, rgba(73,155,234,1) 0%, rgba(73,155,234,1) 13%, rgba(73,155,234,1) 52%, rgba(32,124,229,1) 82%, rgba(32,124,229,1) 100%);
        }

        #offline_btn {
            position:absolute; left:30px; bottom:10px; width:240px; cursor:pointer;
        }

        #message {
            position:absolute; left: 10px; bottom:-60px; width: 300px; height: 60px; border:solid 0px red; font-size:16pt;
        }

        #viewer_close_btn {
            position:absolute; cursor:pointer; width:25px; height: 25px; right:5px; top:2px;
        }

        #v1 {
            position:absolute; left:0; right:0; top:0; bottom:0;
        }

        .ui-widget {
            font-size:0.8em;
        }

        .change_item {
            text-align: right;
            color: #5D9ABC;
            font-size:1em;
        }

        .download_controler{
            text-align:center;
            position:absolute;
            left:25%; right:0px; bottom:10px;
            height: 30px;
        }

        .download_controler div {
            width:60px;
            height:23px;
            display:inline-block;
            cursor:pointer;
        }

        .progress {
            display:inline-block;
            width:70px;
            height:10px;
            bottom:20px;
            position:relative;
            background:#C4D0DA;
        }
    </style>

</head>

<body ondragstart="return false;" ondrop="return false;">
<div id="wrapper">
    <div id="top">
        <img id="home_button" src="images/top/top_home_01.png" onClick="showLogin()" />
        <img id="fubon_logo" src="images/top/fubonvision_logo.png" />
    </div>
    <div id="downloadpanel">
            <span id="download_header">
                <img id="download_icon" src="images/downloads/download_icon.png" />
                <img id="download_txt" src="images/downloads/download_txt.png" />
            </span>

        <div id="downloadlist" class="scrolling"> </div>
    </div>
</div>

<div id="login">
    <img id="login_logo" src="images/top/fubonvision_logo2.png" />
    <img id="account_image" src="images/login/account.png" />
    <img id="password_image" src="images/login/password.png" />
    <img id="captcha_image" src="images/login/validatecode.png" />
    <img id="validateImage" src="images/login/loading.png" />
    <img id="changecode" src="images/login/change_code.png" />
    <img id="btn" src="images/login/login.png" />
    <span id="account_policy" >帳號使用說明</span>
    <img id="forget_password_button" src="images/login/forget_password.png" />
    <span id="remember_account_prompt" >
        <input type="checkbox" id="remember_account_checkbox" />記住帳號</span>
    <input id="user" name="user" type="text" maxlength="50" />
    <input id="pass" name="pass" type="password" />
    <input id="validateCode" name="validateCode" type="text" pattern="[0-9]*" />
    <div id="offline_panel">
        <img id="offline_btn" src="images/login/offline.png" />
    </div>
    <span id="message" > &nbsp; </span>
</div>

<div id="version_label" style="position: absolute; bottom: 0; right: 0; color: red">loading...</div>

<div id="dialog-confirm"> </div>

<div id="viewer">

    <div id="v1"> </div>
</div>

<div id="forgetPasswordDialog" class="dialog scrolling" title="忘記密碼 - 請選擇下列方式之一取得隨機新密碼">
    <span class="forget_option"><input type="radio" id="forget_check_online" name="forget" value="online" checked="checked" />線上立即查詢</span>

    <ul>
        以下是您選擇的通關題目，請直接輸入通關答案，答對系統會給您一個隨機密碼 <br/>
        <table>
            <tr><td>通關題目</td> <td><div id="forget_password_quesion"></div></td> </tr>
            <tr><td>通關答案</td> <td><input id="forget_password_answer"/></td> </tr>
        </table>
    </ul>

    <span class="forget_option"><input type="radio" id="forget_check_phone" name="forget" value="phone" />撥打業務人員專線 <a id="forget_call" href="javascript:openURL('tel:0800023686')">0800-023-686</a></span>
    <ul>
        服務時間:週一至週五(國定假日除外) <br/>上午9:00-12:00 下午1:30-5:30
    </ul>
</div><div id="changePasswordDialog" class="dialog scrolling" title="更改密碼">
    <br/>
    <table style="margin: 0 auto;">
        <tr><td class="change_item">舊密碼</td> <td><input id="change_password_old" type="password" /></td> </tr>
        <tr><td class="change_item">新密碼</td> <td><input id="change_password_new" type="password" /></td> </tr>
        <tr><td class="change_item">密碼確認</td> <td><input id="change_password_confirm" type="password" /></td> </tr>
    </table>

    <br/>
    <h4>優質密碼設定準則</h4>
    建立新密碼時請遵循下列方針：<br/>
    <ul class="o365_not_activated">
        <li>密碼長度最少6碼，最長12碼</li>
        <li>需英文字、數字夾雜，不能為全是英文字或數字</li>
        <li>使用英文字可全為大寫或全為小寫，若使用大、小寫夾雜時，輸入時必須注意大、小寫區分</li>
        <li>不可使用特殊符號，例如：!@#$%^;:?</li>
        <li>不可為您身份證字號</li>
        <li>不可為相同英文字、數字連續達4次(含)以上，例如：1111、AAAA</li>
        <li>不可為連續性之英文字、數字達4次(含)以上，例如：1234 或 4321 或 abcd 或 dcba</li>
        <li>不可重複使用前3次舊密碼</li>
        <li>每90天必須變更一次密碼</li>
        <li>連續輸入密碼錯誤達5次，帳號將鎖住。</li>
    </ul>

    <ol class="o365_activated">
        <li>使用 8 至 16 個字元，至少必須符合下列 3 項條件：
            <ul>
                <li>小寫字母</li>
                <li>大寫字母</li>
                <li>數字 (0-9)</li>
                <li>符號，包含：! @ # $ % ^ & * - _ + = [ ] { } | \ : ‘ , . ? / ` ~ “ < > ( ) ; </li>
            </ul>
        </li>
        <li>不可重覆使用前一次舊密碼。</li>
        <li>每90天必須變更一次密碼。</li>
    </ol>

</div><div id="changePasswordA5Dialog" class="dialog scrolling" title="更改密碼">
    <br/>
    <table style="margin: 0 auto;">
        <tr><td class="change_item">舊密碼</td><td><input type="password" id="change_password_a5_old"/></td> </tr>
        <tr><td class="change_item">新密碼</td><td><input type="password" id="change_password_a5_new"/></td> </tr>
        <tr><td class="change_item">密碼確認</td><td><input type="password" id="change_password_a5_confirm" /></td> </tr>
        <tr><td class="change_item">通關題目</td> <td>
                <select id="change_password_a5_question">
                    <option value="我的出生地？">我的出生地？</option>
                    <option value="我畢業的小學名稱？">我畢業的小學名稱？</option>
                    <option value="我奶奶的名字？ ">我奶奶的名字？ </option>
                    <option value="我爺爺的名字？">我爺爺的名字？</option>
                    <option value="我家寵物的名字？">我家寵物的名字？</option>
                    <option value="我父母親結婚那一年(民國)？">我父母親結婚那一年(民國)？</option>
                    <option value="我的出生年月日(西元年)？">我的出生年月日(西元年)？</option>

                </select>
            </td> </tr>
        <tr><td class="change_item">通關答案</td> <td><input id="change_password_a5_answer" /></td> </tr>
    </table>

    <br/>
    <h4>優質密碼設定準則</h4>
    建立新密碼時請遵循下列方針：<br/>
    <ul class="o365_not_activated">
        <li>密碼長度最少6碼，最長12碼</li>
        <li>需英文字、數字夾雜，不能為全是英文字或數字</li>
        <li>使用英文字可全為大寫或全為小寫，若使用大、小寫夾雜時，輸入時必須注意大、小寫區分</li>
        <li>不可使用特殊符號，例如：!@#$%^;:?</li>
        <li>不可為您身份證字號</li>
        <li>不可為相同英文字、數字連續達4次(含)以上，例如：1111、AAAA</li>
        <li>不可為連續性之英文字、數字達4次(含)以上，例如：1234 或 4321 或 abcd 或 dcba</li>
        <li>不可重複使用前3次舊密碼</li>
        <li>每90天必須變更一次密碼</li>
        <li>連續輸入密碼錯誤達5次，帳號將鎖住。</li>
    </ul>

    <ol class="o365_activated">
        <li>使用 8 至 16 個字元，至少必須符合下列 3 項條件：
            <ul>
                <li>小寫字母</li>
                <li>大寫字母</li>
                <li>數字 (0-9)</li>
                <li>符號，包含：! @ # $ % ^ & * - _ + = [ ] { } | \ : ‘ , . ? / ` ~ “ < > ( ) ; </li>
            </ul>
        </li>
        <li>不可重覆使用前一次舊密碼。</li>
        <li>每90天必須變更一次密碼。</li>
    </ol>

    <br/> 設立通關題目及通關答案之目的<br/>
    <ul>
        <li>
            當您忘記密碼時，系統會自動顯示您設定的通關題目，並要求您輸入通關答案，只要答案正確，便可立即取得隨機新密碼。
        </li>
    </ul>

</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui-dp.min.js"></script>
<script src="js/jquery.lightbox_me.js"></script>

<script src="js/StageWebViewBridge.js"></script>


<script type="text/javascript">

    // For simulation
    var g_browser_mode = false;

    if (g_browser_mode) {
        var StageWebViewBridge = {

            'call' :function(param, callback, param3) {
                console.log(param + ' ' + param3 + ' ' + callback);

                switch (param) {
                    case 'getConfig':
                        if (param3 === 'remember') callback(false);
                        if (param3 === 'account') callback('a12345@hotmail.com');
                        return;
                    case 'getDownloadedList':

                        callback([
                            { id:123, status: 1, progress: 40 }
                        ]);
                        return;
                }

                if (typeof param3 !== "undefined") {
                    console.log(param3);
                }

                if (typeof callback == 'function') {
                    callback();
                }
            }
        };
    } else {
        var console = {
            'log': function(data) {
                StageWebViewBridge.call('trace', null, data);
            }
        }
    }

    var g_CAPTCHA_URL = 'https://fubonevideo.moker.com/ajax/main/validateImg.php';
    //var g_LOGIN_URL='http://fubon.moker.com.tw/ajax/main/checkSSO_x.php';
    var g_MAIN_PROG_URL = 'https://fubonevideo.moker.com/phone';

    var g_LOGIN_URL = 'https://fubonevideo.moker.com/ajax/main/o365_login.php';
    var g_APP_STATUS_URL = 'https://fubonevideo.moker.com/ajax/main/o365_app_status.php';
    var g_FORGET_PASSWORD_URL = 'https://fubonevideo.moker.com/ajax/main/o365_forget_password.php';
    var g_CHANGE_PASSWORD_URL = 'https://fubonevideo.moker.com/ajax/main/o365_change_password.php';
    var g_CHANGE_PASSWORD_A5_URL = 'https://fubonevideo.moker.com/ajax/main/o365_change_password_a5.php';
    var g_APP_VERSION_URL = 'https://fubonevideo.moker.com/ajax/main/app_version.php';

    var buttonArray=[];
    var download_task_all=[];
    var download_task_finished=[];
    var download_progress_array=[];

    var g_serverPort;
    var appVersion='';
    var today;

    var g_o365_activated=false;
    var g_o365_status_checked=false;
    var random = Math.random();

    function main() {
        prepareLogin();
        showLogin();

        if (g_o365_status_checked) return;

        fixIOSBounce();
        checkO365Status(function(result) {
            if (!result) {
                window.setTimeout(function() {
                    checkO365Status(function(result) {
                        if (!result) {
                            showInfoDialog("網路連線異常");
                        }
                    });
                }, 3000);
            }
        });
    }

    $(window).load(function() {

        g_serverPort = window.location.port;

        url = window.location.toString();
        if(url.indexOf("?")!=-1){
            today = (url.indexOf("true")!=-1);
        }
        if (g_browser_mode) main();

        $('img').bind('contextmenu', function(e) {
            return false;
        });

        buttonArray =
            [
                {id:'home', normal:'images/top/top_home_01.png', press:'images/top/top_home_01.png', link:'y0_home.php'}
            ];

        onWindowSize();

        $(window).resize(function() {
            onWindowSize();
        });

        url = window.location.toString();
        if(url.indexOf("download")!=-1){
            $("#offline_btn").trigger("click");
        }

        afterSuccessLogin();
    });

    function showVersion() {
        var versionLabel = $("#version_label");

        if (versionLabel.text() === "loading...") {
            StageWebViewBridge.call('getVersion', function(data) {
                $("#version_label").text(data);

                setTimeout(function(){
                    checkVersion(data);
                }, 1500);
            });

            setTimeout(function(){
                showVersion();
            }, 3000);
        }
    }

    function checkVersion(currentVersion) {
        $.ajax({
            url: g_APP_VERSION_URL,
            type: 'get',
            dataType: 'jsonp',
            contentType: 'application/json',
            crossDomain: true,
            success: function (data) {
                var serverVersion = data.PhoneVersion;
                var currentPart = currentVersion.split(".");
                var serverPart = serverVersion.split(".");

                if (versionExceed(serverPart, currentPart)) {
                    $("#dialog-confirm").html("您非使用最新版本，請進行更新：" + serverVersion).dialog({
                        autoOpen: true,
                        title: '訊息視窗',
                        resizable: false,
                        autoResize: true,
                        modal: true,
                        buttons: {
                            "確定": function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    });
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {

            }
        });
    }

    function versionExceed(v1parts, v2parts) {
        while (v1parts.length < v2parts.length) v1parts.push("0");
        while (v2parts.length < v1parts.length) v2parts.push("0");

        for (var i = 0;i < v1parts.length;++ i) {
            if (v2parts.length == i) {
                return true;
            }

            if (v1parts[i] == v2parts[i]) {
                continue;
            } else {
                return v1parts[i] > v2parts[i];
            }
        }
    }

    function checkValidateImg() {
        var width = $("#validateImage").width();
        if ($("#validateImage").naturalWidth === 0) {
            showValidateImage();
            setTimeout(function(){
                showValidateImage();
            }, 1000);
        }
    }

    function fixIOSBounce() {
        if (((typeof g_ua)=="undefined") || (g_ua== "")) g_ua= navigator.userAgent;
        if (g_ua.match(/iPad/i) || g_ua.match(/iPhone/i)) {

            document.addEventListener('touchmove', function(e){
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


                    event.stopPropagation();


                });
            }
        }
    }

    function checkO365Status(callback) {
        $.ajax({
            url: g_APP_STATUS_URL,
            type: 'get',
            dataType: 'jsonp',
            contentType: 'application/json',
            crossDomain: true,
            success: function (data) {

                g_o365_status_checked=true;
                g_o365_activated=data.EnableO365;

                if (g_o365_activated)
                {
                    $("#account_policy").css("display", "block");
                    $(".o365_not_activated").css("display", "none");
                    $(".o365_activated").css("display", "block");

                } else {
                    $(".o365_not_activated").css("display", "block");
                    $(".o365_activated").css("display", "none");
                }
                if (typeof callback === 'function') {
                    callback(true);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (typeof callback === 'function') {
                    callback(false);
                }
            }
        });
    }

    function showLogin() {

        $("#wrapper").css('visibility', "hidden");
        $("#user, #pass, #validateCode").val('');

        getConfig('remember', function(data) {
            if (data) {
                getConfig('account', function(account){
                    $("#remember_account_checkbox").prop('checked', true);
                    var masked_account;

                    if (account.indexOf('@')>-1) {  // an e-mail account
                        masked_account = '****' + account.slice(4);
                    } else {
                        masked_account = account.slice(0, 5) + '**' + account.slice(7);
                    }
                    $("#user").val(masked_account)
                        .data('actual', account);
                })
            }
        })

        if (!today) {
            $("#offline_panel").hide();
            $("#login").css("height", "185px");

        } else {
            $("#offline_panel").show();
            $("#login").css("height", "240px");
        }

        $("#login").css("display", "block");
    }

    function showValidateImage() {
        var validateImageURL = g_CAPTCHA_URL+'?p='+random;

        $("#validateImage")
            .one('load', function() { //Set something to run when it finishes loading
                $(this).fadeIn(); //Fade it in when loaded
            })
            .attr('src', validateImageURL) //Set the source so it begins fetching
            .each(function() {
                //Cache fix for browsers that don't trigger .load()
                if(this.complete) $(this).trigger('load');
            });
    }

    function prepareLogin() {

//            $("#validateImage").attr('src', validateImageURL) //Set the source so it begins fetching

//            $("#validateImage").attr('src', "images/login/loading.png");
//            showValidateImage();
        $(document).ajaxStart(function() {
            $("#message").html('');
            $( "#message" ).show();
        });
        //$("#user").focus();

        $("#user").keyup(function() {
            var v = $('#user').val();
            $('#user').data('actual', v)
        })
            .keypress(function(e) {
                code= (e.keyCode ? e.keyCode : e.which);
                if (code == 13) {
                    if ($("#user").val()!=null) $("#pass").focus().select();
                    e.preventDefault();
                }
            });
        $('#pass').keypress(function(e) {
            code= (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
//                    if ($("#pass").val()!=null) doLogin();
                if ($("#pass").val()!=null) $("#validateCode").focus().select();
                e.preventDefault();
            }
        });
        $('#validateCode').keypress(function(e) {
            code= (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                if ($("#validateCode").val()!=null) doLogin();
                e.preventDefault();
            }
        });

        $("#btn").click(function() { doLogin()});
        $("#offline_btn").click(function() { doOffline()});

        $("#changecode").click(function() {
            $("#validateImage").attr('src', "images/login/loading.png");
            showValidateImage();
        });

        $("#forget_password_button").click(function() {
            var user =$('#user').val();
            if (user === '') {
                message = '您尚未輸入帳號';
                $('#message').hide().html(message).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });

            } else {
                var param = {
                    user : user,
                    actionType: 1,
                    qes: '',
                    ans: ''
                };
                getForgetPasswordParameters(param, function(data) {
                    switch (data.ReturnCode) {
                        case '00' :
                            $("#forget_password_quesion").html(data.Ques);
                            forgetPasswordDialog(data, user);
                            break;
                        case '28' :
                            var dialog = $( "#dialog-confirm" ).html(data.ReturnMessage).dialog({
                                autoOpen: false,
                                title: '確認',
                                autoResize: true,
                                modal: true,
                                buttons: {
                                    "前往網站": function(){

                                        dialog.dialog( "close" );
                                        openURL(data.ReturnURL);

                                    },
                                    "取消": function() {
                                        dialog.dialog( "close" );
                                    }
                                },
                                close: function() {
                                    // form[ 0 ].reset();
                                    // allFields.removeClass( "ui-state-error" );
                                }
                            });
                            dialog.dialog( "open" );
                            break;
                        default:
                            $('#message').hide().html(data.ReturnMessage).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
                    }

                });
            }
        });

        $("#account_policy").click(function() {
            showInfoDialog('倘未啟用Office365雲端服務，「帳號」欄位請輸入身分證字號，倘已啟用請輸入公司email帳號。')
        });

    }

    function showChangePasswordAlert(type, usr, message) {
        $(".ui-dialog-titlebar").css("background-color", "blue");

        $("#dialog-confirm").html('因為這是您第一次登入，或您的密碼已過期，所以您必須更新密碼。').dialog({
            title: '訊息視窗',
            resizable: false,
            autoResize: true,
            modal: true,
            buttons: {
                "確定": function() {
                    $( this ).dialog( "close" );
                    if (type === "change") {
                        changePasswordDialog(user, message);
                    } else {
                        prepareChangePasswordA5Dialog(user, message);
                    }
                },
                "取消": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }

    function showInfoDialog(message, callback) {

        if ((typeof message === "undefined") || (!message)) {
            if (typeof callback === 'function') {
                callback();
                return;
            } else {
                return;
            }
        }

        $(".ui-dialog-titlebar").css("background-color", "blue");

        $("#dialog-confirm").html(message).dialog({
            autoOpen: true,
            title: '訊息視窗',
            resizable: false,
            autoResize: true,
            modal: true,
            buttons: {
                "確定": function() {
                    $( this ).dialog( "close" );
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            }
        });
    }

    function getForgetPasswordParameters(param, callback) {
        var xhr = $.ajax({
            url: g_FORGET_PASSWORD_URL,
            type: 'get',
            dataType: 'jsonp',
            contentType: 'application/json',
            crossDomain: true,
            data: {
                sysCode: param.sysCode || 'Mobile-APP-A-I',
                user: param.user,
                actionType : param.actionType,
                qes: param.qes,
                ans: param.ans,
                p:random
            },
            success: function (data) {

                response = data.result;
                showLoginMessage(0);
                if ((typeof callback) === "function") {

                    callback(data);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                showLoginMessage(0);
                $("#changecode").trigger("click");
                message = '連線失敗:' + textStatus;
                $('#message').hide().html(message).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
            }
        });
        showLoginMessage(1, "處理中", xhr);
    }



    function forgetPasswordDialog(data, user) {
        var dialog = $( "#forgetPasswordDialog" ).dialog({
            autoOpen: false,
            autoResize: true,
            width: 300,
            modal: true,
            buttons: {
                "確定": function(){
                    if ($("#forget_check_online").is(":checked")) {
                        var answer= $("#forget_password_answer").val();
                        if (answer === '') {
                            showInfoDialog('答案不可輸入空白。');
                        } else {
                            verifyForgetPasswordProcess(user, dialog);
                        }
                    } else {
                        showInfoDialog('請撥打業務員數位諮詢專線：0800-023-686', function(){
                            $("#forget_call")[0].click();
                            dialog.dialog( "close" );
                        });
                    }
                },
                "取消": function() {
                    dialog.dialog( "close" );
                }
            },
            close: function() {
                // form[ 0 ].reset();
                // allFields.removeClass( "ui-state-error" );
            }
        });
        dialog.dialog( "open" );
    }

    function verifyForgetPasswordProcess(user, dialog) {
        var param = {
            user : user,
            actionType: 2,
            qes: $("#forget_password_quesion").html(),
            ans: $("#forget_password_answer").val()
        };
        getForgetPasswordParameters(param, function(data) {
            switch (data.ReturnCode) {
                case '00':
                    dialog.dialog( "close" );
                    showInfoDialog('您現在的密碼是 ' + data.NPwd + '<br/>強烈建議您進入行動辦公室網站後，立即至『個人資料』重新變更密碼。');
                    break;
                case '28':
                    dialog.dialog("close");
                    StageWebViewBridge.call('openURL', null, data.ReturnURL);
                    break;
                case '74':
                    showInfoDialog(data.ReturnMessage);
                    break;
                default:
                    showInfoDialog(data.ReturnMessage);
                    $('#message').hide().html(data.ReturnMessage).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
            }

        });
    }

    $('#remember_account_checkbox').change(function() {
        if($(this).is(":checked")) {
            showInfoDialog('若此裝置有與他人共用情況，強烈建議請勿勾選記住帳號功能');
            return;
        } else {
            //   eraseCookie("account");
        }
        //'unchecked' event code
    });

    function getChangePasswordParameters(param, callback) {
        var xhr = $.ajax({
            url: g_CHANGE_PASSWORD_URL,
            type: 'get',
            dataType: 'jsonp',
            contentType: 'application/json',
            crossDomain: true,
            data: {
                user: param.user,
                qPwd : param.qPwd,
                nPwd: param.nPwd,
                cPwd: param.cPwd,
                p:random
            },
            success: function (data) {

                response = data.result;
                showLoginMessage(0);
                if ((typeof callback) === "function") {
                    callback(data);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                showLoginMessage(0);
                $("#changecode").trigger("click");
                message = '連線失敗:' + textStatus;
                $('#message').hide().html(message).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
            }
        });
        showLoginMessage(1, "處理中", null);
    }

    function changePasswordDialog(user, message) {

        var dialog = $( "#changePasswordDialog" ).dialog({
            autoOpen: false,
            title: '更改密碼',
            autoResize: true,
            width: 300,
            modal: true,
            buttons: {
                "確定": function(){
                    var qPwd = $("#change_password_old").val(),
                        nPwd = $("#change_password_new").val(),
                        cPwd = $("#change_password_confirm").val();

                    if (qPwd === '') {
                        showInfoDialog('您尚未輸入舊密碼。');
                    } else if (nPwd === '') {
                        showInfoDialog('您尚未輸入新密碼。');
                    } else if (qPwd===nPwd) {
                        showInfoDialog('變更密碼不得與前次密碼相同。');
                    } else if (nPwd!==cPwd) {
                        showInfoDialog('新密碼與確認密碼不一致。');
                    } else {
                        verifyChangePasswordProcess(user, dialog);
                    }
                },
                "取消": function() {
                    dialog.dialog( "close" );
                }
            },
            close: function() {
                // form[ 0 ].reset();
                // allFields.removeClass( "ui-state-error" );
            }
        });
        dialog.dialog( "open" );
    }

    function verifyChangePasswordProcess(user, dialog) {
        var param = {
            user : user,
            qPwd: $("#change_password_old").val(),
            nPwd: $("#change_password_new").val(),
            cPwd: $("#change_password_confirm").val()
        };
        getChangePasswordParameters(param, function(data) {
            if (typeof data === "undefined") {
                var data = {};
            }
            if (typeof data.ReturnMessage === "undefined") {
                data.ReturnMessage = "";
            }
            switch (data.ReturnCode) {
                case '00':
                    StageWebViewBridge.call('getVersion', function(version) {

                        showInfoDialog(data.ReturnMessage, function() {
                            appVersion = version;
                            authenticationWithoutCaptcha(user, $("#change_password_new").val());
                            dialog.dialog("close");
                        });
                    });
                    //$('#message').hide().html(data.ReturnMessage).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
                    break;
                default:
                    showInfoDialog(data.ReturnMessage);
                //$('#message').hide().html(data.ReturnMessage).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
            }
        });
    }

    function getChangePasswordA5Parameters(param, callback) {
        var xhr = $.ajax({
            url: g_CHANGE_PASSWORD_A5_URL,
            type: 'get',
            dataType: 'jsonp',
            contentType: 'application/json',
            crossDomain: true,
            data: {
                user: param.user,
                qPwd : param.qPwd,
                nPwd: param.nPwd,
                cPwd: param.cPwd,
                Qes: param.Qes,
                Ans: param.Ans,
                p:random
            },
            success: function (data) {

                response = data.result;
                showLoginMessage(0);
                if ((typeof callback) === "function") {
                    callback(data);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                showLoginMessage(0);
                $("#changecode").trigger("click");
                message = '連線失敗:' + textStatus;
                $('#message').hide().html(message).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
            }
        });
        showLoginMessage(1, "處理中", null);
    }

    function prepareChangePasswordA5Dialog(user, message) {
        var param = {
            sysCode : 'Mobile-APP-A-I',
            user : user,
            actionType: 1,
            qes: '',
            ans: ''
        };
        getForgetPasswordParameters(param, function(data) {
            switch (data.ReturnCode) {
                case '00' :
                    $("#change_password_a5_question").val(data.Ques);
                    $("#change_password_a5_answer").val(data.Ans);
                    changePasswordA5Dialog(user, message);
                    break;
                default:
                    $('#message').hide().html(data.ReturnMessage).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
            }
        });
    }

    function changePasswordA5Dialog(user, message) {
        var height= "auto";
        var width= "auto";
        var wHeight = $(window).height();
        var mHeight = wHeight*0.9;

        if (g_ua.match(/iPad/i)) {
            width = 600;
        } else if (g_ua.match(/iPhone/i)){
            //height = 400;
        }
        var dialog = $( "#changePasswordA5Dialog" ).dialog({
            autoOpen: false,
            title: '更改密碼',
            autoResize: true,
            width: width,
            height: height,
            maxHeight: mHeight,
            //width: 300,
            modal: true,
            buttons: {
                "確定": function(){
                    var qPwd = $("#change_password_a5_old").val(),
                        nPwd = $("#change_password_a5_new").val(),
                        cPwd = $("#change_password_a5_confirm").val(),
                        Qes = $("#change_password_a5_question").val(),
                        Ans = $("#change_password_a5_answer").val();

                    if (qPwd === '') {
                        showInfoDialog('您尚未輸入舊密碼。');
                    } else if (nPwd === '') {
                        showInfoDialog('您尚未輸入新密碼。');
                    } else if (qPwd===nPwd) {
                        showInfoDialog('變更密碼不得與前次密碼相同。');
                    } else if (nPwd!==cPwd) {
                        showInfoDialog('新密碼與確認密碼不一致。');
                    } else if (Ans ==='') {
                        showInfoDialog('答案不可輸入空白。');
                    } else {
                        verifyChangePasswordA5Process(user, dialog);
                    }
                },
                "取消": function() {

                    dialog.dialog( "close" );
                }
            },
            close: function() {
                // form[ 0 ].reset();
                // allFields.removeClass( "ui-state-error" );
            }
        });
        dialog.dialog( "open" );
    }

    function verifyChangePasswordA5Process(user, dialog) {
        var param = {
            user : user,
            qPwd: $("#change_password_a5_old").val(),
            nPwd: $("#change_password_a5_new").val(),
            cPwd: $("#change_password_a5_confirm").val(),
            Qes: $("#change_password_a5_question").val(),
            Ans: $("#change_password_a5_answer").val()
        };
        getChangePasswordA5Parameters(param, function(data) {
            if (typeof data === "undefined") {
                var data = {};
            }
            if (typeof data.ReturnMessage === "undefined") {
                data.ReturnMessage = "";
            }
            switch (data.ReturnCode) {
                case '00':
                    StageWebViewBridge.call('getVersion', function(version) {
                        showInfoDialog(data.ReturnMessage, function() {
                            appVersion = version;
                            authenticationWithoutCaptcha(user, $("#change_password_a5_new").val());
                            dialog.dialog("close");
                        });
                    });
                    break;
                default:
                    showInfoDialog(data.ReturnMessage);
                    $('#message').hide().html(data.ReturnMessage).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
            }
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
    function doOffline() {
        readyForDownload(1);
        $("#login").trigger('close');
        $("#login").css("display", "none");
        $("#wrapper").css('visibility', "visible");
    }
    function doLogin() {

        user =$('#user').data('actual');
        pass =$('#pass').val();
        validateCode =$('#validateCode').val();

        var NET_SessionId;
        $("#message").html('登入中..').show();
        StageWebViewBridge.call('getVersion', function(data) {
            appVersion = data;

            if (g_o365_status_checked) {
                authentication(user, pass, validateCode);
            } else {
                checkO365Status(function(result) {
                    if (result) {
                        authentication(user, pass, validateCode);
                    } else {
                        showInfoDialog("網路連線異常");
                        $("#message").html('').show();
                    }
                })
            }

        });

    }
    function authenticationWithoutCaptcha(user, pass) {

        var xhr = $.ajax({
            url: g_LOGIN_URL + "?nocaptcha",
            type: 'get',
            dataType: 'jsonp',
            contentType: 'application/json',
            crossDomain: true,
            data: {user: user, pass: pass, validateCode: 'magic', version: appVersion,p:random},
            success: function (data) {
                var response = data.result,
                    message = data.message,
                    session = data.session||'';

                showLoginMessage(0);
                if(response == 'success') {
                    //$("#message").html('登入成功').show();
                    showLoginMessage(1, "處理中...");
                    showInfoDialog(message, function() {
                        afterSuccessLogin(user, data);
                    });
                } else {
                    $('#message').hide().html(message).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                showLoginMessage(0);
                $("#changecode").trigger("click");
                var message = '連線失敗:' + textStatus;
                $('#message').hide().html(message).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
            }
        });
        showLoginMessage(1, "登入中", xhr);
    }

    function authentication(user, pass, validateCode) {

        var xhr = $.ajax({
            url: g_LOGIN_URL,
            type: 'get',
            dataType: 'jsonp',
            contentType: 'application/json',
            crossDomain: true,
            data: {user: user, pass: pass, validateCode: validateCode, version: appVersion,p:random},
            success: function (data) {
                var response = data.result,
                    message = data.message,
                    session = data.session||'';

                showLoginMessage(0);

                if(response == 'success') {
                    if ($('#remember_account_checkbox').is(":checked")) {
                        setConfig('account', user);
                        setConfig('remember', true);
                    } else {
                        setConfig('account', '');
                        setConfig('remember', '');
                    }
                    //$("#message").html('登入成功').show();

                    showLoginMessage(1, "處理中...");

                    showInfoDialog(message, function() {
                        afterSuccessLogin(user, data);
                    });
                    /*
                     $('#message').hide().html(message).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
                     $("#login, #login2").trigger('close');
                     $("#wrapper").css('visibility', "visible");
                     $("#query").focus();
                     */
                } else {
//                        $("#changecode").trigger("click");
                    $("#message").html('').show();
                    switch (data.code) {

                        case '79' :
                            showChangePasswordAlert("changeA5", user, data.message);

                            break;
                        case '68' :
                            showChangePasswordAlert("change", user, data.message);

                            break;
                        default:
                            $('#message').hide().html(message).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
                            $("#user").focus().select();
                    }

                }

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                showLoginMessage(0);
                $("#changecode").trigger("click");
                message = '連線失敗:' + textStatus;
                $('#message').hide().html(message).fadeIn("fast", function() {  window.setTimeout(function() { $('#message').fadeOut("slow"); }, 10000); });
            }
        });
        showLoginMessage(1, "登入中", xhr);
    }

    function afterSuccessLogin() {
        var destination = g_MAIN_PROG_URL+"?p="+g_serverPort;
        destination += "&w=" + $(window).width();
        var h = $(window).height() > <?php echo $maxHeight?> ? $(window).height() : <?php echo $maxHeight?>;
        destination += "&h=" + h;
        destination += "<?php echo $params?>";
        StageWebViewBridge.call('loadURL', null, destination);

        setTimeout(function(){
            afterSuccessLogin();
        }, 1500);
    }

    function onWindowSize() {

        w = $(window).width();
        h = $(window).height();

//            if (w<900) w=900;
//            if (h<600) h=600;

        $('#wrapper').width(w);
        $('#wrapper').height(h);

    }


    function showDownloadList() {
        $("#downloadpanel").show();

    }
    function updateDownloadPage() {
        StageWebViewBridge.call('getDownloadedList', function(data) {
            preparePhoneDownloadPage(data);
        });
    }

    function readyForDownload(data) {
        /*
         StageWebViewBridge.call('g_serverPort', function(data) {
         g_serverPort = data;
         });
         */
        if (g_o365_status_checked) return;

        main();
        if (data<=0) return;
        updateDownloadPage();

    }

    function openURL(url) {
        StageWebViewBridge.call('openURL', null, url );
    }

    function updateDownloadList(id, progress) {

        if (progress!=100) {
            $("#progress_"+id).progressbar({ value: progress, background: "#B637E6" });
            $("#play_"+id).hide();
            $("#pause_"+id).show();
            $("#resume_"+id).hide();
        } else {

            $("#progress_"+id).hide();
            $("#play_"+id).show();
            $("#pause_"+id).hide();
            $("#resume_"+id).hide();
        }

    }
    function onPause(id) {
        StageWebViewBridge.call('pauseDownload', null, id );
        updateDownloadPage();
    }
    function onResume(id) {
        StageWebViewBridge.call('resumeDownload', null, id );
        updateDownloadPage();
    }

    function showLoginMessage(on_off, msg, xhr) {

        if (on_off==1) {

            show = typeof msg !== 'undefined' ? msg : '請稍候';
            xhr = typeof xhr !== 'undefined' ? xhr : null;

            var buttons = {};
            if (xhr != null) {
                buttons["取消"] = function() {xhr.abort();  $( this ).dialog( "close" );};
            }

            $("#dialog-confirm").html(show).dialog({
                title: '訊息視窗',
                resizable: false,
                draggable: false,
                autoResize: true,
                closeOnEscape: false,
                open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog || ui).hide(); $(".ui-dialog-titlebar-close", $(this).parent()).hide();},
//            position: { my: "center", at: "center", of: window },
                modal: true,
                buttons:buttons
            });
        } else {
            $("#dialog-confirm").html('').dialog("close");
        }
    }

    function showWait(on_off) {

        if (on_off==1) {

            var show = '<img src="http://127.0.0.1:'+g_serverPort+'/images/animal0020.gif"/> ';

            $("#dialog-confirm").html(show).dialog({
                title: '準備中',
                resizable: false,
                autoResize: true,
                position: { my: "center", at: "center", of: window },
                modal: true,
                buttons:[]
            });
        } else {
            $("#dialog-confirm").html('').dialog("close");
        }
    }

    function onPlayWithFlash(id, path) {
        var v_url;

        if ((typeof path) === 'undefined') {
            if (g_serverPort!=0) {
                v_url = 'http://127.0.0.1:' +g_serverPort + '/'+id + '.mp4';
            } else {
                v_url = 'http://fubon.moker.com.tw/DATA/video/720p/' +id + '.mp4';
            }
        } else {
            v_url = path;
        }

        $("#viewer").lightbox_me({
            closeClick: true,
            closeEsc: false,
            centered: true,
            overlayCSS: {background: 'black', opacity: .5},
            onLoad: function() {
            }
        });


        var options = {
            src : v_url,
            preload: 'auto',
            autoPlay: true,
            useHTML5: true,
            playButtonOverlay: true,
            height : "100%",
            width : "100%",
            favorFlashOverHtml5Video: true,
            javascriptCallbackFunction: "onJavaScriptBridgeCreated",
            scaleMode : 'letterbox'
        };
        options = $.fn.adaptiveexperienceconfigurator.adapt(options);
        p = $("#v1").strobemediaplayback(options);
    }


    function onPlayWithHtml5(id, path) {
        showWait(1);
        StageWebViewBridge.call('videoLog', null, id);
        var prefix = 'http://127.0.0.1:' +g_serverPort + '/';

        var h=$(document).height();
        var w=$(document).width();


        var video_h = h-20;
        var html_content = "<html><head><meta name='viewport' content='width=device-width, user-scalable=no'><script src='"+prefix+"js/jquery.min.js'><\/script><script src='"+prefix+"js/StageWebViewBridge.js'><\/script><script> $(window).load(function(){StageWebViewBridge.call('ready'); var elem = document.getElementById('vplayer');if (elem.requestFullscreen) {elem.requestFullscreen();} });function onKeyDown(code) {goBack(); } function goBack(){StageWebViewBridge.call('stopPlay', null, "+ id +");} <\/script></head><body style='background-color:#000;'><img id='back' src='"+prefix +"images/close_box_gray.png' style='position:fixed; right:0; top:0; height:40px; z-Index:1000;' onClick='goBack();'/><div style='position:absolute; top:40px; left:0; right:0; bottom:0;'><video id='vplayer' style='position:absolute;left:0; top:20px; z-Index:999' poster='"+prefix+"images/poster_360p.jpg' controls autoplay preload='yes' width='100%' height='100%'> <source src='" + id + ".mp4' type='video/mp4'></video></div></body></html>";


        StageWebViewBridge.call('toOriginal', function(data){
            var url=data;
            var launch=true;
            StageWebViewBridge.call('file_put_contents', null, "p.html", html_content, launch);
        }, id );
    }

    function onDelete(id) {
        $(".ui-dialog-titlebar").css("background-color", "blue");

        $("#dialog-confirm").html('').dialog({
            title: '刪除本影片嗎?',
            resizable: false,
            autoResize: true,
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

    function preparePhoneDownloadPage(data) {

        download_task_all=[];
        download_task_finished=[];
        download_progress_array=[];

        len = data.length;

        html_content = ' <ul id="download_container" style="position:absolute; left:0px; right:0px; top:30px; border: solid 0px red; bottom:0; margin:0; list-style-type: none;padding-left:20px; padding-right:40px; background-color:#eee;">';

        for (i=0; i<len; i++) {

            download_task_all.push(data[i].id);

            id = data[i].id;

            cell = '<li style="height:120px; width:100%; margin: 10px; position:relative; border-bottom: solid 3px #aaa">' +
                '<span id="item_'+id+'" style="position:absolute; width:100%; height:100%; ">'+
                '<div style="position:absolute; width:20%; left:0; top:0; bottom:0; text-align:center;"> '+
                '<img src="DATA/images/cover/' + id + '.png" style="height:110px;"/> '+
                '<br/>' +
                '<div class="progress" id="progress_'+id+'"></div>'+
                '</div>' +

                '<span class="download_controler">' +
                '<div class="download_item" data-id="' + id + '" data-action="play" style="background-image:url(images/downloads/play_normal.png);"></div>' +
                '<div class="download_item" data-id="' + id + '" data-action="pause" style="background-image:url(images/downloads/pause_normal.png);"></div>' +
                '<div class="download_item" data-id="' + id + '" data-action="resume" style="background-image:url(images/downloads/resume_normal.png);"></div>' +
                '<div class="download_item" data-id="' + id + '" data-action="delete" style="background-image:url(images/downloads/delete_normal.png);"></div>' +
                '</span>' +
                '</span>' +
                '</li>';


            html_content+=cell;

            var z=new Object;
            z.id=data[i].id;
            z.status=data[i].status;
            z.progress=data[i].progress;
            download_progress_array.push(z);

            if (z.status==1) download_task_finished.push(z.id)
        }
        html_content +="</ul>";

        $("#downloadlist").html(html_content);

        $(".download_item").click(function() {

            var $id = $(this).data("id");
            switch ($(this).data("action")) {
                case "play":
                    onPlayWithHtml5($id);
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
        });

        $.each(download_progress_array, function(key, value) {
            switch(value.status) {
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
        });
    }

    function prepareDownloadPage(data) {

        var playFunctionName = "onPlayWithHtml5";

        download_task_all=[];
        download_task_finished=[];
        download_progress_array=[];

        len = data.length;

        html_content = '<div id="download_list" style="margin-left:auto; margin-right:auto;;height:97%; width:97%; border: solid 0px red;">';

        for (i=0; i<len; i++) {

            download_task_all.push(data[i].id);
            // if (data[i].status != 1) continue;

            id = data[i].id;
            cell = '<div style="position:relative; margin: 10px 5px; float:left; width: 177px; height: 250px; border: solid 0px red;">'+' <div style="width:100%;height:90%;background:url(DATA/images/cover/' + id + '.png) no-repeat center center;background-size: contain"/><span id="func_'+ id+ '" style="position:absolute;left:0; bottom:0px; width:100%; height: 10%"><div id="play_'+id+'" style="background:url(images/downloads/play_normal.png) no-repeat center center; display:inline-block" onClick="' + playFunctionName + '('+data[i].id+')"></div> <div id="pause_'+id+'" style="background:url(images/downloads/pause_normal.png) no-repeat center center;" onClick="onPause('+data[i].id+')"></div> <div id="resume_'+id+'" style="background:url(images/downloads/resume_normal.png) no-repeat center center;" onClick="onResume('+data[i].id+')"></div> <div id="delete_'+id+'" style="background:url(images/downloads/delete_normal.png) no-repeat center center;" onClick="onDelete(' + data[i].id +')"></div></span><div class="progress" id="progress_'+ data[i].id + '"></div></div>';
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
        });
    }

    function onKeyDown(code) {

        if (code ==16777238) {
            $("#dialog-confirm").html('').dialog({
                title: '確認離開?',
                resizable: false,
                autoResize: true,
                modal: true,
                buttons:[
                    {
                        text:"是",
                        "id": "idYes",
                        click:function(){
                            $( this ).dialog( "close" );
                            StageWebViewBridge.call('exitApp');
                        }
                    },
                    {
                        text:"否",
                        "id": "idNo",
                        click:function(){
                            $( this ).dialog( "close" );
                        }
                    }
                ]
            });
        }

    }

    function getConfig(name, callback) {
        StageWebViewBridge.call('getConfig', function(data) {
            if (typeof callback == 'function') {
                callback(data);
            }
        }, name);
    }

    function setConfig(name, value) {
        StageWebViewBridge.call('setConfig', null, name, value);
    }    </script>
</html>

