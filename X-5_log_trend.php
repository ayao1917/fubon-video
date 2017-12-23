<?php

include_once('inc/global.php');
include_once('inc/utils.php');
include_once('inc/class_log.php');

$logdb=new Logs();

$logdb->init();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>X-5</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 


		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
<!--
		<link href="js/jstree/themes/default/style.css" rel="stylesheet" type="text/css" />
-->
		<link rel="stylesheet" href="css/X-5.css"> 
		<link rel="stylesheet" href="css/jquery.multiselect.css"> 
		<link rel="stylesheet" href="css/jquery.multiselect.filter.css"> 
		<link rel="stylesheet" href="css/validationEngine.jquery.css"> 

 		
		<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script> 
		<script type="text/javascript" src="js/jquery.flot.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
                <script type="text/javascript" src="js/jquery-ui-all.min.js"></script>
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		
		<script type="text/javascript" src="js/jtable/jquery.jtable.min.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine-zh_TW.js"></script> 	
		<script type="text/javascript" src="js/jquery.multiselect.min.js"></script> 
		<script type="text/javascript" src="js/jquery.multiselect.filter.js"></script> 
		<script type="text/javascript" src="js/json2.js"></script> 
		<script type="text/javascript" src="js/X-5.js"></script> 

	</head>
	
	<body>

<h1> 基本報表 </h1>
<input type="button" value="重新載入" onClick="window.location.reload()"> <span id="db_time"> </span> <br/>
<button id="toggle_filter"> 隱藏/顯示查詢條件 </button>
		<div id="content">
                        <div id="selection">
				<ul id="filter">

					<li class="condition">
						起始日期：<input type="text" id="from" name="from" /> <br/>
						結束日期：<input type="text" id="to" name="to" />
					</li>
					<li class="condition">
						統計對象： <br />
						<input type="radio" name="type" value="0" checked /> 登入人次  <br />
						<input type="radio" name="type" value="1" /> 登入人數  <br />
                                                <input type="radio" name="type" value="5" /> 首次登入人數(日統計) <br />
                                                <input type="radio" name="type" value="6" /> 首次登入人數(月統計) <br />
						<input type="radio" name="type" value="2" />  觀看人次 <br />
						<input type="radio" name="type" value="3" />  觀看總人數 <br />
					</li>
					<li>
						<button id="LoadRecordsButton">開始查詢</button>
						<button id="ClearSearchButton">清除搜尋條件</button>
					</li>

                        <div class="clear"> </div>

				</ul>
                        </div>

                        <div class="clear"> </div>
                        <hr />
			<div id="query">
			</div>


		<div class="chart-container">
			<div id="placeholder" class="chart-placeholder"></div>
		</div>
        

			<div id="LogListContainer">
			</div>


		</div>

	</body>
</html>
