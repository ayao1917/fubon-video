<?php

include_once('inc/config.php');
include_once('inc/global.php');
include_once('inc/class_category.php');

if (isset($_REQUEST['category_id'])) { 
    $category_id = $_REQUEST['category_id'];
} else {
    die("參數錯誤");
}

$db2 = new Category();
$db2->init();

$category = $db2->load($category_id);

if (count($category)==0) die ("錯誤");

$title = $category[0]['TITLE'];
$imgfile1 = $category[0]['ICON_NORMAL'];
$imgfile2 = $category[0]['ICON_PRESS'];

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
		<title>Z-1 上傳ICON</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 

		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script type="text/javascript" src="js/json2.js"></script> 

		<script type="text/javascript" src="js/Z-1_upload.js"></script> 
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
<button id="exitButton" onClick="window.location.href='Z-1_category_management.php';">返回分類列表</button>

<hr>
<div style="clear:both"> </div>

<h3>分類名稱: <?php echo $title ?></h3> 
<p>尺寸建議: (寬x高): 288x114 </p>
</div>

<table>
<tr> <td><h3>目前分類圖示(正常狀態)</h3></td>  <td><h3>目前分類圖示(點選狀態)</h3></td> </tr>

<tr><td> <div id="output1"><?php echo $content1; ?></div> </td>  <td> <div id="output2"><?php echo $content2; ?></div> </td> </tr>
<tr>
<td>
<form action="ajax/Z-1/processupload.php" method="post" enctype="multipart/form-data" id="UploadForm1">
<input name="ImageFile" type="file" />
<input name="Category" type="hidden" value="<?php echo $category_id ?>" />
<input name="IconType" type="hidden" value="normal" />
<input name="target" type="hidden" value="category" />
<input name="maxsize" type="hidden" value="288" />
<input type="submit"  id="SubmitButton1" value="開始上傳" />
</form>
 </td> 
<td>
<form action="ajax/Z-1/processupload.php" method="post" enctype="multipart/form-data" id="UploadForm2">
<input name="ImageFile" type="file" />
<input name="Category" type="hidden" value="<?php echo $category_id ?>" />
<input name="IconType" type="hidden" value="press" />
<input name="target" type="hidden" value="category" />
<input name="maxsize" type="hidden" value="288" />
<input type="submit"  id="SubmitButton2" value="開始上傳" />
</form>

</td>
</tr>
</table>

	</body>
</html>
