<?php

include_once('inc/global.php');

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-13</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 


		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/Z-13.css"> 
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
		<script type="text/javascript" src="js/Z-13.js"></script> 

	</head>
	
	<body>

<h1> 操作記錄查詢 </h1>
<button id="toggle_filter"> 隱藏/顯示查詢條件 </button>
		<div id="content">
                        <div id="selection">
				<ul id="filter">

					<li class="condition">
						起始日期：<input type="text" id="from" name="from" /> <br/>
						結束日期：<input type="text" id="to" name="to" />
					</li>
					<li class="condition">
						操作命令： <br />
						<select id="type" name="type" multiple="multiple"> </select>
					</li>
					<li class="condition">
						管理者ID： <br />
						<select id="user" name="user" multiple="multiple"> </select>
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

			<div id="LogListContainer">
			</div>


		</div>

	</body>
</html>
