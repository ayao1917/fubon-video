<?php
if(isset($_POST))
{
    include_once('../../inc/config.php');
    include_once('../../inc/global.php');
    include_once('../../inc/utils.php');
    include_once('../../inc/class_banner.php');
    include_once('../../inc/class_cache.php');


    if (!isset($_REQUEST['target'])) die();
    $target = $_REQUEST['target'];
    if (!isset($_REQUEST['banner'])) die();
    $id = $_REQUEST['banner'];

    
    checkDir(__DATA_PATH__);
    $DestinationDirectory   = __DATA_PATH__.'/video/'; //Upload Directory ends with / (slash)
    checkDir($DestinationDirectory);
    $DestinationDirectory   .= "/$target/"; //Upload Directory ends with / (slash)
    checkDir($DestinationDirectory);

    // check $_FILES['ImageFile'] array is not empty
    // "is_uploaded_file" Tells whether the file was uploaded via HTTP POST
    if(!isset($_FILES['VideoFile']) || !is_uploaded_file($_FILES['VideoFile']['tmp_name']))
    {
            die('上傳失敗!'); // output error when above checks fail.
    }

    // Random number for both file, will be added after image name
    $RandomNumber   = rand(0, 9999999999);


    $allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma");
    $extension = pathinfo($_FILES['VideoFile']['name'], PATHINFO_EXTENSION);

    if ((($_FILES["VideoFile"]["type"] == "video/mp4")
    || ($_FILES["VideoFile"]["type"] == "audio/mp3")
    || ($_FILES["VideoFile"]["type"] == "audio/wma")
    || ($_FILES["VideoFile"]["type"] == "image/pjpeg")
    || ($_FILES["VideoFile"]["type"] == "image/gif")
    || ($_FILES["VideoFile"]["type"] == "image/jpeg"))

    && in_array($extension, $allowedExts))
    {
        if ($_FILES["VideoFile"]["error"] > 0) {
            die("Return Code: " . $_FILES["VideoFile"]["error"] . "<br />");
        } else {
/*
            echo "Upload: " . $_FILES["VideoFile"]["name"] . "<br />";
            echo "Type: " . $_FILES["VideoFile"]["type"] . "<br />";
            echo "Size: " . ($_FILES["VideoFile"]["size"] / 1024) . " Kb<br />";
            echo "Temp file: " . $_FILES["VideoFile"]["tmp_name"] . "<br />";
*/


            $VideoName = $id.".mp4";
            $v = __DATA_PATH__."/video/$target/$VideoName";

            move_uploaded_file($_FILES["VideoFile"]["tmp_name"], $v); 
            $url = __DATA_URL__."/video/$target/$VideoName";

            $cache = new Cache();
            $cache->updateAllCache();
            $url = $url."?".time();
            echo '<video preload controls loop width="320" height="180"> <source src="'. $url .'" type="video/mp4" /> </video>';

/*
            if (file_exists("upload/" . $_FILES["VideoFile"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists. ";
            } else {
                move_uploaded_file($_FILES["VideoFile"]["tmp_name"], "upload/" . $_FILES["file"]["name"]); 
                echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
            }
*/
        }
    } else {
        echo "上傳檔案格式錯誤";
    }
}


?>


