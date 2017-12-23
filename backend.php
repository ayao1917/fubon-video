<?php
include_once('inc/config.php');
include_once('inc/class_manager.php');

if (!isset($USER_ID)) die;

$mgrDB = new Manager();
$mgrDB->init();
$result = $mgrDB->load($USER_ID);
if (count($result)<1) die("$USER_ID 無使用後台權限 "); 
$permission = $result[0];

?>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/backend.css">
</head>

<body>



<ul id="all">

<?php if ($permission["VIDEO"]>0) { ?>
    <li class="item">
        <img src="images/backend/h1.png" />
        <ul>
            <li class="action"><a href="Z-0_video_management.php">影片管理</a></li>
        </ul>
    </li>
<?php } ?>

<?php if ($permission["CATEGORY"] + $permission["FRONTPAGE"]+$permission["BANNER"]>0) { ?>

    <li class="item">
        <img src="images/backend/h2.png" />
        <ul>
<?php if ($permission["CATEGORY"]>0) { ?>
            <li class="action"><a href="Z-1_category_management.php">分類管理</a></li>
            <li class="action"><a href="Z-4_tag_management.php">系列管理</a></li>
<?php } ?>
<?php if ($permission["FRONTPAGE"]>0) { ?>
            <li class="action"><a href="Z-2_hot_management.php">本月新片管理</a></li>
<?php } ?>
<?php if ($permission["BANNER"]>0) { ?>
            <li class="action"><a href="Z-3_banner_management.php">廣宣區設定</a></li>
<?php } ?>
        </ul>
    </li>

<?php } ?>

<?php if ($permission["REPORT"]>0) { ?>
    <li class="item">
        <img src="images/backend/h3.png" />
        <ul>
            <li class="action"><a href="X-5_log_trend.php">使用統計</a></li>
        </ul>
    </li>
<?php } ?>

<?php if ($permission["SYSTEM"]>0) { ?>
    <li class="item">
        <img src="images/backend/h4.png" />
        <ul>
            <li class="action"><a href="Z-11_user_management.php">後台帳號管理</a></li>
        </ul>
    </li>
<?php } ?>
</ul>


</body>



</html>
