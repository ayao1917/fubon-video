<?php
//    if(!isset($_POST)) die();

    include_once('../../inc/config.php');
    include_once('../../inc/global.php');
    include_once('../../inc/class_video.php');
    include_once('../../inc/class_cache.php');

    $randomNumber = rand(0, 9999999999);

    if (!isset($_REQUEST['target'])) die();
    $target = $_REQUEST['target'];
    if (! in_array($target, ['cover', 'highlight'])) die();

    if (!isset($_REQUEST['id'])) die();
    $id = $_REQUEST['id'];
    if (! is_numeric($id)) die();

    $baseImageDirectory   = __DATA_PATH__.'/images'; 
    $imageName = $id.'.png';
    $outputImagePath = "$baseImageDirectory/$target/$imageName"; 
    
    if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name']))
    {
            die('上傳失敗!'); // output error when above checks fail.
    }

    $tempFile = $_FILES['ImageFile']['tmp_name']; // Tmp name of image file stored in PHP tmp folder

    if (file_exists($tempFile)) exec("/usr/bin/convert -resize 177x $tempFile $outputImagePath");

    if (file_exists($outputImagePath)) {
        if ($target=="cover") {
            exec("/usr/bin/convert -resize 150x $outputImagePath $baseImageDirectory/cover150/$imageName");
        }

        $url = __DATA_URL__."/images/$target/$imageName";
        echo "<img src='$url?$randomNumber' alt='Image' />";

        $cache = new Cache();
        $cache->updateAllCache();

    } else {
        die('錯誤'); //output error
    }
