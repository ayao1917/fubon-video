<?php

include_once('inc/global.php');
include_once('inc/utils.php');
include_once('inc/class_log.php');


if (isset($_REQUEST['schedule'])) {
    if ($_REQUEST['schedule'] === "now") {
        $scheduledTime = urlencode(date("Y-m-d H:i"));
    } else {
        $scheduledTime =  urlencode(preg_replace("/[^\-:0-9,. ]/", "", $_REQUEST['schedule']));
    }
    `curl http://fg3.moker.com.tw/schedule.php?set=$scheduledTime`;
}
if (isset($_REQUEST['cancel'])) {
    `curl http://fg3.moker.com.tw/schedule.php?clear`;
}

$pushLog ="";
if (isset($_REQUEST['push'])) {
    $pushLog = `curl http://fg3.moker.com.tw/pull-from-staging.php`;
}

$scheduled_time = `curl http://fg3.moker.com.tw/schedule.php?getscheduledtime`;
$published_time = `curl http://fg3.moker.com.tw/schedule.php?getpublishedtime`;

if ($scheduled_time== "") $scheduled_time = "無";
if ($published_time== "") $published_time = "無";

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-5</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 


		<link rel="stylesheet" href="css/Z-5.css"> 
                <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/ >

 		
		<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script> 
                <script type="text/javascript" src="js/jquery-ui-all.min.js"></script>
                <script type="text/javascript" src="js/jquery.datetimepicker.full.min.js"></script>
		
		<script type="text/javascript" src="js/json2.js"></script> 
		<script type="text/javascript" src="js/Z-5.js"></script> 

	</head>
	
	<body>



		<div id="content">

                <h1> 發佈管理 </h1>

                <!--<p>*將測試環境的內容發佈到正式環境，可選擇立即發佈，或者排程發佈。正式環境每分鐘檢查有沒有新的更新任務</p>-->
                <p style="color:red">現在資料已經同步，此功能已經停用囉！</p>

		<ol>

		<li><button id="push-assets" disabled>傳送影片和圖片檔</button></li>

		<li><p>傳送影片metadata、App首畫面更新資訊</p>
		
                目前排程：<span id="scheduled_at"><?=$scheduled_time?></span> <span id="schedule-cancel" class="action">取消</span> <br/>
                上次發佈：<span id="published_at"><?=$published_time?></span> <br/>

                <span id="publish-now-disabled" class="action-disabled">立即發佈</span>或設定排程：<input id="scheduled-time" type="text"/> <button id="schedule-confirm" disabled>確定</button> <br/>

		</li>
		</ol>

		<pre><?=$pushLog?></pre>


	</body>
</html>
