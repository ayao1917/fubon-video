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

checkDir(__DATA_PATH__."/images/");
checkDir(__DATA_PATH__."/images/cover");
checkDir(__DATA_PATH__."/images/highlight");
$title = $video['TITLE'];

$cover_file = __DATA_PATH__."/images/cover/$video_id.png";
$cover_url = __DATA_URL__."/images/cover/$video_id.png";

$highlight_file = __DATA_PATH__."/images/highlight/$video_id.png";
$highlight_url = __DATA_URL__."/images/highlight/$video_id.png";

$content1 = "目前無封面圖片";
$content2 = "目前無精選圖片";
if (file_exists($cover_file)) {
    $content1 = "<img src='".$cover_url."' />";
}
if (file_exists($highlight_file)) {
    $content2 = "<img src='".$highlight_url."' width=\"400\" />";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-0 上傳封面</title> 	
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

}

</style>
	</head>
	
	<body>


<button id="exitButton" onClick="window.location.href='Z-0_video_management.php';">返回影片列表</button>

<div style="clear:both"> </div>
<br/>

<table>
<tr> <td>影片名稱</td> <td> <h2> <?php echo $title ?></h2> </td> </tr>
<tr> <td>封面尺寸建議</td> <td> (寬x高): 180x250 </td> </tr>
<tr> <td>封面目前設定</td> <td> <div id="output1"><?php echo $content1; ?></div> </td> </tr>
<tr> <td colspan="2">
    <form action="ajax/Z-0/processuploadimage.php" method="post" enctype="multipart/form-data" id="UploadForm1">
    <input name="ImageFile" type="file" />
    <input name="id" type="hidden" value="<?php echo $video_id ?>" />
    <input name="target" type="hidden" value="cover" />
    <input name="maxsize" type="hidden" value="250" />
    <input type="submit"  id="SubmitButton1" value="開始上傳封面圖片" />
    </form>
 </td> </tr>
</table>

<br /> <br /> <br />

<table>
<tr> <td>精選圖片尺寸建議</td> <td> (寬x高): 1280x720 </td> </tr>
<tr> <td>精選圖片目前設定</td> <td width="400"> <div id="output2"><?php echo $content2; ?></div> </td> </tr>
<tr> <td colspan="2">
    <form action="ajax/Z-0/processuploadimage.php" method="post" enctype="multipart/form-data" id="UploadForm2">
    <input name="ImageFile" type="file" />
    <input name="id" type="hidden" value="<?php echo $video_id ?>" />
    <input name="target" type="hidden" value="highlight" />
    <input name="maxsize" type="hidden" value="1280" />
    <input name="displayscale" type="hidden" value="20%" />
    <input type="submit"  id="SubmitButton2" value="開始上傳精選圖片" />
    </form>
 </td> </tr>
</table>
	</body>
</html>
