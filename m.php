<?php

include_once('inc/config.php');
include_once('inc/class_manager.php');
include_once('inc/utils.php');

if (!isset($USER_ID)) die;

$mgrDB = new Manager();
$mgrDB->init();

$result = $mgrDB->load($USER_ID);

if (count($result)<1) header( "Location: index.html" );
$permission = $result[0];


?>
<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>雲端電子影城管理系統</title> 	
		<link rel="stylesheet" href="css/global.css"> 

                <link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 
		<script type="text/javascript" src="js/jquery.min.js"></script> 
                <script type="text/javascript" src="js/jquery-ui.min.js"></script> 
                <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 

<script type="text/javascript">

  $(document).ready(function () {
    
        $("#logout").css("margin-left", "30px").click(function() {
            location.replace("index.html");
        });

        $("#main").load(function() {
            this.style.height = this.contentWindow.document.body.offsetHeight + 'px';

        });
  });

function menu_select(target, link) {
        $(".sub-menu li").removeClass('selected');
        $(target).addClass('selected');
        $("#main").attr("src", link);
}


</script>
			
	</head>
	
	<body>

		<div id="wrapper">
		<div id="top">
			<div id="logo" onclick='menu_select(this, "backend.php")'>
					<img src="images/fubon_logo.png" width="196" height="35">
			</div>

			<div id="title">
				<h1  onclick='menu_select(this, "backend.php")'>雲端電子影城</h1>
			</div>
		</div>	

		<div>
			<h2 id="name">歡迎您! <?php print $USER_NAME; ?> <button id="logout">登出</button> </h2>
		</div>
		
		<div id="left_menu">
			<ul class="main-nav">
<?php if ($permission["VIDEO"]>0) { ?>
				<li id='A' class="main-menu-title"> <span>影片管理</span>	</li>
					<ul class="sub-menu">
						<li onclick='menu_select(this, "Z-0_video_management.php")'><p>影片管理</p></li>
						<li onclick='menu_select(this, "Z-5_publish_management.php")'><p>發佈管理</p></li>
					</ul>
<?php }?>

				
				<li id='B' class="main-menu-title"> <span>內容設定</span> </li>
					<ul class="sub-menu">
<?php if ($permission["CATEGORY"]>0) { ?>
						<li onclick='menu_select(this, "Z-1_category_management.php")'><p>分類管理</p></li>
						<li onclick='menu_select(this, "Z-4_tag_management.php")'><p>系列管理</p></li>
                        <li onclick='menu_select(this, "Z-6_order_management.php")'><p>排序管理</p></li>
<?php }?>
<?php if ($permission["FRONTPAGE"]>0) { ?>
						<li onclick='menu_select(this, "Z-2_hot_management.php")'><p>本月新片管理</p></li>
<?php }?>
<?php if ($permission["BANNER"]>0) { ?>
						<li onclick='menu_select(this, "Z-3_banner_management.php")' ><p>廣宣區管理</p></li>
<?php }?>
					</ul>
<?php if ($permission["REPORT"]>0) { ?>

				<li id='D' class="main-menu-title"><span>統計報表</span> </li>
					<ul class="sub-menu">
						<li onclick='menu_select(this, "X-5_log_trend.php")'><p>使用統計</p></li>
						<li onclick='menu_select(this, "X-3_video_log_user.php")'><p>閱讀記錄查詢</p></li>
						<li onclick='menu_select(this, "X-2_login_log_lookup.php")'><p>單位登入率查詢</p></li>
						<li onclick='menu_select(this, "X-4_log_stat.php")'><p>影片排行查詢</p></li>
					</ul>			
<?php }?>
<?php if ($permission["SYSTEM"]>0) { ?>

				<li id='D' class="main-menu-title"><span>系統管理</span> </li>
					<ul class="sub-menu">
						<li onclick='menu_select(this, "Z-11_user_management.php")'><p>後台帳號管理</p></li>
					</ul>			
<?php }?>
			</ul>	
		</div>
		
		<div id="working_area">
			
			<iframe id="main" style="width:100%" frameborder="1" scrolling="yes" src="backend.php"></iframe>
			
		</div>
</div>
	</body>
</html>
