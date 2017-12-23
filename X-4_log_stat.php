<?php

include_once('inc/global.php');




?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>B-11</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 


		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/X-4.css"> 
		<link rel="stylesheet" href="css/jquery.multiselect.css"> 
		<link rel="stylesheet" href="css/jquery.multiselect.filter.css"> 
		<link rel="stylesheet" href="css/validationEngine.jquery.css"> 

 		
		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		
		<script type="text/javascript" src="js/jtable/jquery.jtable.min.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine-zh_TW.js"></script> 	
		<script type="text/javascript" src="js/jquery.multiselect.min.js"></script> 
		<script type="text/javascript" src="js/jquery.multiselect.filter.js"></script> 
		<script type="text/javascript" src="js/json2.js"></script> 
		<script type="text/javascript" src="js/X-4.js"></script> 

	</head>
	
	<body>

<h1> 影片排行統計 </h1>
<span id="db_time"> </span> <br/>
<button id="toggle_filter"> 隱藏/顯示查詢條件 </button>
		<div id="content">
                        <div id="selection">
				<ul id="filter">

					<li class="condition">
						起始日期：<input type="text" id="from" name="from" /> <br/>
						結束日期：<input type="text" id="to" name="to" />
					</li>
					<li class="condition">
						選擇分類與影片： <br />
						<select id="book_level_1" name="book_level_1" multiple="multiple">
						</select>
						<select id="book_level_2" name="book_level_2" multiple="multiple">
						</select>
					</li>
					<li class="condition">
						統計對象： <br />
						<input type="radio" name="type" value="0" /> 觀看人數  <br />
						<input type="radio" name="type" value="1" checked />  觀看人次 <br />
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
<!--
			<div id="query">
			</div>
-->

			<div id="LogListContainer">
			</div>


		</div>

	</body>
</html>
