<?php
//    if(!isset($_POST)) die();

    include_once('../../inc/config.php');
    include_once('../../inc/global.php');
    include_once('../../inc/class_video.php');
    include_once('../../inc/class_cache.php');
    include_once('../../inc/class_banner.php');

    $randomNumber = rand(0, 9999999999);
    $displayScale="20%";

    if (isset($_REQUEST['displayscale'])) $displayScale = $_REQUEST['displayscale'] ;

    if (!isset($_REQUEST['target'])) die();
    $target = $_REQUEST['target'];
    if (! in_array($target, ['banner'])) die();

    if (!isset($_REQUEST['Banner'])) die();
    $id = $_REQUEST['Banner'];
    if (! is_numeric($id)) die();

    $baseImageDirectory   = __DATA_PATH__.'/images'; 
    $imageName = $id.'.png';
    $outputImagePath = "$baseImageDirectory/$target/$imageName"; 
    
    if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name']))
    {
            die('上傳失敗!'); // output error when above checks fail.
    }

    $tempFile = $_FILES['ImageFile']['tmp_name']; // Tmp name of image file stored in PHP tmp folder

    if (file_exists($tempFile)) { 
        exec("/usr/bin/convert $tempFile $outputImagePath");
        $imageName = $id.'.jpg';
        $outputImagePath = "$baseImageDirectory/$target/$imageName";
        unlink($outputImagePath);
        exec("/usr/bin/convert $tempFile $outputImagePath");
    }

    if (file_exists($outputImagePath)) {
        if ($target=="banner") {
            exec("/usr/bin/convert -resize 370x $outputImagePath $baseImageDirectory/banner250/$imageName");
        }

        $url = __DATA_URL__."/images/$target/$imageName";
        echo "<img src='$url?$randomNumber' style='width:$displayScale' alt='Image' />";

        $banner = new Banner();
        $banner->updateField($id, "BANNER", $url);

        $cache = new Cache();
        $cache->updateAllCache();

    } else {
        die('錯誤'); //output error
    }
