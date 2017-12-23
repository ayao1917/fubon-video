<?php

include_once('inc/config.php');
include_once('inc/global.php');
include_once('inc/class_banner.php');

if (isset($_REQUEST['banner_id'])) { 
    $banner_id = $_REQUEST['banner_id'];
} else {
    die("參數錯誤");
}

$db2 = new Banner();
$db2->init();

$banner = $db2->load($banner_id);

if (count($banner)==0) die ("錯誤");

$imgfile1 = $banner[0]['BANNER'];

$content1 = "目前無廣告圖片";
$content2 = "目前無廣告影片";
if ($imgfile1 !=null) {
    $content1 = "<img src='".$imgfile1."' style='width: 400px'/>";
}
$video_file = __DATA_PATH__."/video/banner/$banner_id.mp4";
if (file_exists($video_file)) {
    $url = __DATA_URL__."/video/banner/$banner_id.mp4?".time();
    $content2 = '<video preload controls loop width="320" height="180"> <source src="'. $url .'" type="video/mp4" /> </video>';
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-3 上傳廣告</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 

		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script type="text/javascript" src="js/json2.js"></script> 

		<script type="text/javascript" src="js/Z-3_upload.js"></script> 
<style>

table {
    border-collapse:collapse;
    margin: 0 auto;
}

td {
    border:solid 1px #cccccc;
    text-align: center;
}

#head {
    margin:20px 50px;
}

</style>
	</head>
	
	<body>

<div id="head">
<button id="exitButton" onClick="window.location.href='Z-3_banner_management.php';">返回列表</button>

<hr>
<div style="clear:both"> </div>

<p>尺寸建議: (寬x高): 1280x720 </p>
</div>

<table>

<tr><td> <div>圖片</div> </td> </tr>
<tr><td> <div id="output1"><?php echo $content1; ?></div> </td> </tr>
<tr>
<td>
<form action="ajax/Z-3/processupload.php" method="post" enctype="multipart/form-data" id="UploadForm1">
<input name="ImageFile" type="file" />
<input name="Banner" type="hidden" value="<?php echo $banner_id ?>" />
<input name="target" type="hidden" value="banner" />
<input name="maxsize" type="hidden" value="1280" />
<input name="displayscale" type="hidden" value="20%" />
<input type="submit"  id="SubmitButton1" value="開始上傳圖片" />
</form>
 </td> 
</tr>
</table>

<br/>

<table>

<tr><td> <div>影片</div> </td> </tr>
<tr><td> <div id="output2"><?php echo $content2; ?></div> </td> </tr>

<tr>
<td>
<form action="ajax/Z-3/processuploadvideo.php" method="post" enctype="multipart/form-data" id="UploadForm2">
<input name="VideoFile" type="file" />
<input name="banner" type="hidden" value="<?php echo $banner_id ?>" />
<input name="target" type="hidden" value="banner" />
<input type="submit"  id="SubmitButton2" value="開始上傳影片" />
</form>
 </td> 
</tr>


</table>

	</body>
</html>
