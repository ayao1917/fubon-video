<?php

include_once('inc/config.php');
include_once('inc/global.php');
include_once('inc/utils.php');
include_once('inc/class_video.php');
include_once('inc/class_book.php');

if (isset($_REQUEST['video_id'])) {
    $video_id = $_REQUEST['video_id'];
} else {
    die("參數錯誤");
}

$db = new Book();
$db->init();
$db2 = new Video();
$db2->init();

$video = $db2->loadVideo($video_id);
$video_book = $db->loadVideoBook($video_id);

if (count($video)==0) die("錯誤；找不到記錄");

checkDir(__FDATA_PATH__."/books/");
$title = $video['TITLE'];

$book_type = $video_book['TYPE'];
$word_id = $video_book['WORD_BOOK'];
$guide_id = $video_book['GUIDE_BOOK'];
$tech_id = $video_book['TECH_BOOK'];
$word_file = __FDATA_PATH__."/books/$word_id.pdf";
$guide_file = __FDATA_PATH__."/books/$guide_id.pdf";
$tech_file = __FDATA_PATH__."/books/$tech_id.pdf";

$content1 = "目前無檔案";
$content2 = "目前無檔案";
$content3 = "目前無檔案";

if (file_exists($word_file)) {
    $book = $db->loadBook($word_id);
    $content1 = $book['TITLE'];
}

if (file_exists($guide_file)) {
    $book = $db->loadBook($guide_id);
    $content2 = $book['TITLE'];
}

if (file_exists($tech_file)) {
    $book = $db->loadBook($tech_id);
    $content3 = $book['TITLE'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Z-0 上傳教材</title>
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

    <fieldset>
        <legend>選擇類型</legend>
        <label for="radio-1">不使用手冊</label>
        <input type="radio" name="radio-1" id="radio-1" onclick="onChangeType(0)" <?php if ($book_type == 0) echo "checked"?>>
        <label for="radio-2">學員+指導手冊</label>
        <input type="radio" name="radio-1" id="radio-2" onclick="onChangeType(1)" <?php if ($book_type == 1) echo "checked"?>>
        <label for="radio-3">教戰手冊</label>
        <input type="radio" name="radio-1" id="radio-3" onclick="onChangeType(2)" <?php if ($book_type == 2) echo "checked"?>>
    </fieldset>

    <br/>

    <table>
        <tr><td colspan="2"><h2><?php echo $title ?></h2></td></tr>
        <tr class="type1"><td>學員手冊</td><td>指導手冊</td></tr>
        <tr class="type1"><td><div id="output1"><?php echo $content1; ?></div></td><td><div id="output2"><?php echo $content2; ?></div></td></tr>
        <tr class="type1">
            <td>
                <form action="ajax/Z-0/processuploadbook.php" method="post" enctype="multipart/form-data" id="UploadForm1">
                    <input name="BookFile" type="file" />
                    <input name="id" type="hidden" value="<?php echo $video_id ?>" />
                    <input name="target" type="hidden" value="WORD_BOOK" />
                    <input type="submit"  id="SubmitButton1" value="開始上傳學員手冊" />
                </form>
            </td>
            <td>
                <form action="ajax/Z-0/processuploadbook.php" method="post" enctype="multipart/form-data" id="UploadForm2">
                    <input name="BookFile" type="file" />
                    <input name="id" type="hidden" value="<?php echo $video_id ?>" />
                    <input name="target" type="hidden" value="GUIDE_BOOK" />
                    <input type="submit"  id="SubmitButton2" value="開始上傳指導手冊" />
                </form>
            </td>
        </tr>
        <tr class="type2"><td>教戰手冊</td></tr>
        <tr class="type2"><td><div id="output3"><?php echo $content3; ?></div></td></tr>
        <tr class="type2">
            <td>
                <form action="ajax/Z-0/processuploadbook.php" method="post" enctype="multipart/form-data" id="UploadForm3">
                    <input name="BookFile" type="file" />
                    <input name="id" type="hidden" value="<?php echo $video_id ?>" />
                    <input name="target" type="hidden" value="TECH_BOOK" />
                    <input type="submit"  id="SubmitButton3" value="開始上傳教戰手冊" />
                </form>
            </td>
        </tr>
    </table>
</div>
<script>
    var currentType = 0;

    $(window).load(function() {
        if ($("#radio-1").is(':checked')) {
            $(".type1").hide();
            $(".type2").hide();
            currentType = 0;
        } else if ($("#radio-2").is(':checked')) {
            $(".type1").show();
            $(".type2").hide();
            currentType = 1;
        } else if ($("#radio-3").is(':checked')) {
            $(".type1").hide();
            $(".type2").show();
            currentType = 1;
        }
    });

    function onChangeType(type) {
        if (type == 0) {
            $(".type1").hide();
            $(".type2").hide();
        } else if (type == 1) {
            $(".type1").show();
            $(".type2").hide();
        } else if (type == 2) {
            $(".type1").hide();
            $(".type2").show();
        }
        currentType = type;
        updateBookType(type);
    }

    function updateBookType(type) {
        $.ajax({
            url: "ajax/Z-0/UpdateBookType.php",
            type: 'get',
            dataType: 'jsonp',
            contentType: 'application/json',
            crossDomain: true,
            data: {id: "<?php echo $video_id ?>", type: type},
            success: function (data) {
                var response = data.result,
                    message = data.message;
                if(response == 'success') {
                    console.log(message);
                } else {
                    console.log(message);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                var message = '連線失敗:' + textStatus;
                console.log(message);
            }
        });
    }
</script>
</body>
</html>
