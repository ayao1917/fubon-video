<?php

include_once('inc/global.php');




?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>B-19</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 


		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
<!-- 		<link href="js/jstree/themes/default/style.css" rel="stylesheet" type="text/css" />
 -->		<link rel="stylesheet" href="css/X-3.css"> 
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
		<script type="text/javascript" src="js/X-3.js"></script> 

	</head>
	
	<body>

<h1> 閱讀記錄查詢 </h1>
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
						業務員對象： <br />
						<input type="text" id="uid" value="業務員ID或姓名" /> <br />
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
			<div id="query1">
			</div>

			<div id="LogListContainer1">
			</div>


		</div>

	</body>
</html>
