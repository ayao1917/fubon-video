<?php

include_once('inc/config.php');
include_once('inc/global.php');
include_once('inc/utils.php');
include_once('inc/class_video.php');

if (isset($_REQUEST['video_id'])) { 
    $video_id = $_REQUEST['video_id'];
} else {
    die("參數錯誤");
}

$db2 = new Video();
$db2->init();

$video = $db2->loadVideo($video_id);

if (count($video)==0) die("錯誤；找不到記錄");

checkDir(__DATA_PATH__."/video/");
checkDir(__DATA_PATH__."/video/360p");
checkDir(__DATA_PATH__."/video/720p");
$title = $video['TITLE'];

$v360_file = __DATA_PATH__."/video/360p/$video_id.mp4";
$v360_url = __DATA_URL__."/video/360p/$video_id.mp4";

$v720_file = __DATA_PATH__."/video/720p/$video_id.mp4";
$v720_url = __DATA_URL__."/video/720p/$video_id.mp4";

$content1 = "目前無影片";
$content2 = "目前無影片";

if (file_exists($v360_file)) {
    $content1 = '<video preload controls loop width="320" height="180"> <source src="'. $v360_url .'" type="video/mp4" /> </video>';
}
if (file_exists($v720_file)) {
    $content2 = '<video preload controls loop width="320" height="180"> <source src="'. $v720_url .'" type="video/mp4" /> </video>';
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-0 上傳影片</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 

		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script type="text/javascript" src="js/json2.js"></script> 
		<script type="text/javascript" src="js/Z-0_upload.js"></script> 
<style>

table {
    border-collapse:collapse;
    margin: 0 auto;
}

td {
    border:solid 1px #cccccc;
    text-align:center;
}

#content {
    margin: 20px 30px;

}

</style>
	</head>
	
	<body>

<div id="content">

<button id="exitButton" onClick="window.location.href='Z-0_video_management.php';">返回影片列表</button>

<div style="clear:both"> </div>
<br/>

<table>
<tr> <td colspan="2"><h2><?php echo $title ?></h2> </td> </tr>
<tr> <td>360P影片</td> <td>720P影片 </td> </tr>
<tr> <td> <div id="output1"><?php echo $content1; ?></div> </td> <td>  <div id="output2"><?php echo $content2; ?></div> </td> </tr>
<tr> 

<td>
    <form action="ajax/Z-0/processuploadvideo.php" method="post" enctype="multipart/form-data" id="UploadForm1">
    <input name="VideoFile" type="file" />
    <input name="id" type="hidden" value="<?php echo $video_id ?>" />
    <input name="target" type="hidden" value="360p" />
    <input type="submit"  id="SubmitButton1" value="開始上傳360P影片" />
    </form>
</td> 
<td>

    <form action="ajax/Z-0/processuploadvideo.php" method="post" enctype="multipart/form-data" id="UploadForm2">
    <input name="VideoFile" type="file" />
    <input name="id" type="hidden" value="<?php echo $video_id ?>" />
    <input name="target" type="hidden" value="720p" />
    <input type="submit"  id="SubmitButton2" value="開始上傳720P影片" />
    </form>
</td>
</tr>
</table>

</div>
	</body>
</html>
