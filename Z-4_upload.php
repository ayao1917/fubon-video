<?php

include_once('inc/config.php');
include_once('inc/global.php');
include_once('inc/class_tag.php');

if (isset($_REQUEST['tag_id'])) { 
    $tag_id = $_REQUEST['tag_id'];
} else {
    die("參數錯誤");
}

$db2 = new Tag();
$db2->init();

$tag = $db2->load($tag_id);

if (count($tag)==0) die ("錯誤");

$title = $tag[0]['TITLE'];
$imgfile1 = $tag[0]['ICON_NORMAL'];
$imgfile2 = $tag[0]['ICON_PRESS'];

$content1 = "目前無icon圖片";
$content2 = "目前無icon圖片";
if ($imgfile1 !=null) {
    $content1 = "<img src='".$imgfile1."' />";
}

if ($imgfile1 !=null) {
    $content2 = "<img src='".$imgfile2."' />";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-4 上傳ICON</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 

		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script type="text/javascript" src="js/json2.js"></script> 

		<script type="text/javascript" src="js/Z-4_upload.js"></script> 
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
<button id="exitButton" onClick="window.location.href='Z-4_tag_management.php';">返回系列列表</button>

<hr>
<div style="clear:both"> </div>

<h3>系列名稱: <?php echo $title ?></h3> 
<p>尺寸建議: (寬x高): 180x250</p>
</div>

<table>
<tr> <td><h3>目前系列圖示</h3></td> <!-- <td><h3>目前系列圖示(點選狀態)</h3></td> --></tr>

<tr><td> <div id="output1"><?php echo $content1; ?></div> </td> <!-- <td> <div id="output2"><?php echo $content2; ?></div> </td> --></tr>
<tr>
<td>
<form action="ajax/Z-4/processupload.php" method="post" enctype="multipart/form-data" id="UploadForm1">
<input name="ImageFile" type="file" />
<input name="Tag" type="hidden" value="<?php echo $tag_id ?>" />
<input name="IconType" type="hidden" value="normal" />
<input name="target" type="hidden" value="tag" />
<input name="maxsize" type="hidden" value="288" />
<input type="submit"  id="SubmitButton1" value="開始上傳" />
</form>
 </td> 
<!--
<td>
<form action="ajax/Z-4/processupload.php" method="post" enctype="multipart/form-data" id="UploadForm2">
<input name="ImageFile" type="file" />
<input name="Tag" type="hidden" value="<?php echo $tag_id ?>" />
<input name="IconType" type="hidden" value="press" />
<input name="target" type="hidden" value="tag" />
<input name="maxsize" type="hidden" value="288" />
<input type="submit"  id="SubmitButton2" value="開始上傳" />
</form>

</td>
-->
</tr>
</table>

	</body>
</html>
