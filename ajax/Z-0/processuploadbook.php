<?php
if (isset($_POST)) {
    include_once('../../inc/config.php');
    include_once('../../inc/global.php');
    include_once('../../inc/utils.php');
    include_once('../../inc/class_video.php');
    include_once('../../inc/class_book.php');
    include_once('../../inc/class_cache.php');

    if (!isset($_REQUEST['target'])) die();
    $target = $_REQUEST['target'];
    if (!isset($_REQUEST['id'])) die();
    $id = $_REQUEST['id'];

    checkDir(__FDATA_PATH__);
    $DestinationDirectory = __FDATA_PATH__ . "/books/"; //Upload Directory ends with / (slash)
    checkDir($DestinationDirectory);

    debug(print_r($_FILES['BookFile'], true));

    // check $_FILES['ImageFile'] array is not empty
    // "is_uploaded_file" Tells whether the file was uploaded via HTTP POST
    if (!isset($_FILES['BookFile']) || !is_uploaded_file($_FILES['BookFile']['tmp_name'])) {
        die('上傳失敗!'); // output error when above checks fail.
    }

    $allowedExts = array("pdf");
    $extension = pathinfo($_FILES['BookFile']['name'], PATHINFO_EXTENSION);
    $fileName = get_basename($_FILES['BookFile']['name']);
    $bookIndex = 0;
    switch ($target) {
        case "WORD_BOOK":
            $bookIndex = 0;
            break;

        case "GUIDE_BOOK":
            $bookIndex = 1;
            break;

        case "TECH_BOOK":
            $bookIndex = 2;
            break;

        case "PRES_BOOK":
            $bookIndex = 3;
            break;
    }

    if ((($_FILES["BookFile"]["type"] == "application/pdf")) && in_array($extension, $allowedExts)) {
        if ($_FILES["BookFile"]["error"] > 0) {
            die("Return Code: " . $_FILES["BookFile"]["error"] . "<br />");
        } else {
            $newId = $id.$bookIndex;
            $BookName = $newId . ".pdf";
            $v = __FDATA_PATH__ . "/books/$BookName";

            move_uploaded_file($_FILES["BookFile"]["tmp_name"], $v);
            $url = __FDATA_PATH__ . "/books/$BookName";

            $video = new Video();
            $video->init();
            $videoTitle = $video->getVideoInfo($id, "TITLE");

            $book = new Book();
            $book->init();
            $videoData = array(
                "ID" => $id,
                "TITLE" => $videoTitle
            );
            $book->updateVideoBook($videoData);
            $book->updateVideoBookField($id, $target, $newId);

            $bookData = array(
                "ID" => $newId,
                "TITLE" => $fileName,
                "TYPE" => $bookIndex
            );
            $book->updateBook($bookData);

            $cache = new Cache();
            $cache->updateAllCache();
            echo $fileName . ".pdf";
        }
    } else {
        echo "上傳檔案格式錯誤";
    }
}

function get_basename($filename){
    $result = preg_replace('/^.+[\\\\\\/]/', '', $filename);
    if (false !== $pos = strripos($result, '.')) {
        $result = substr($result, 0, $pos);
    }
    return $result;
}
