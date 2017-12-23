<?php
if(isset($_POST))
{
    include_once('../../inc/config.php');
    include_once('../../inc/global.php');
    include_once('../../inc/class_category.php');
    include_once('../../inc/class_cache.php');

     //Some Settings
    $ThumbSquareSize        = 200; //Thumbnail will be 200x200
    $BigImageMaxSize        = 512; //Image Maximum height or width
    $ThumbPrefix            = "thumb_"; //Normal thumb Prefix
    checkDir(__DATA_PATH__);
    $DestinationDirectory   = __DATA_PATH__.'/images/'; //Upload Directory ends with / (slash)

    if (!isset($_REQUEST['target'])) die();
    $target = $_REQUEST['target'];
    if (!isset($_REQUEST['Category'])) die();
    $id = $_REQUEST['Category'];
    $IconType = $_REQUEST['IconType'];

    if (isset($_REQUEST['maxsize'])) $BigImageMaxSize=$_REQUEST['maxsize'];
    
    checkDir($DestinationDirectory);
    $DestinationDirectory   .= "/$target/"; //Upload Directory ends with / (slash)
    checkDir($DestinationDirectory);
    
        //for windows users path starts with drive letter
       //$DestinationDirectory  = 'D:/website/uploader/uploads/'; //Upload Directory ends with / (slash)
    $Quality                = 100;

    // check $_FILES['ImageFile'] array is not empty
    // "is_uploaded_file" Tells whether the file was uploaded via HTTP POST
    if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name']))
    {
            die('上傳失敗!'); // output error when above checks fail.
    }

    // Random number for both file, will be added after image name
    $RandomNumber   = rand(0, 9999999999);

    // Elements (values) of $_FILES['ImageFile'] array
    //let's access these values by using their index position
    $ImageName      = str_replace(' ','-',strtolower($_FILES['ImageFile']['name']));
    $ImageSize      = $_FILES['ImageFile']['size']; // Obtain original image size
    $TempSrc        = $_FILES['ImageFile']['tmp_name']; // Tmp name of image file stored in PHP tmp folder
    $ImageType      = $_FILES['ImageFile']['type']; //Obtain file type, returns "image/png", image/jpeg, text/plain etc.


    //Let's use $ImageType variable to check wheather uploaded file is supported.
    //We use PHP SWITCH statement to check valid image format, PHP SWITCH is similar to IF/ELSE statements
    //suitable if we want to compare the a variable with many different values
    switch(strtolower($ImageType))
    {
        case 'image/png':
            $CreatedImage =  imagecreatefrompng($_FILES['ImageFile']['tmp_name']);
            break;
        case 'image/gif':
            $CreatedImage =  imagecreatefromgif($_FILES['ImageFile']['tmp_name']);
            break;
        case 'image/jpeg':
        case 'image/pjpeg':
            $CreatedImage = imagecreatefromjpeg($_FILES['ImageFile']['tmp_name']);
            break;
        default:
            die('Unsupported File!'); //output error and exit
    }

    //PHP getimagesize() function returns height-width from image file stored in PHP tmp folder.
    //Let's get first two values from image, width and height. list assign values to $CurWidth,$CurHeight
    list($CurWidth,$CurHeight)=getimagesize($TempSrc);

//Get file extension from Image name, this will be re-added after random name
    $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
    $ImageExt = str_replace('.','',$ImageExt);

    //remove extension from filename
    $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);

    //Construct a new image name (with random number added) for our new image.
//    $NewImageName = $ImageName.'-'.$RandomNumber.'.'.$ImageExt;

    $ImageType="Image/png";
    $NewImageName = $id.'_'.$IconType.'.png';

    //set the Destination Image
    $thumb_DestRandImageName    = $DestinationDirectory.$ThumbPrefix.$NewImageName; //Thumb name
    $DestRandImageName          = $DestinationDirectory.$NewImageName; //Name for Big Image

    //Resize image to our Specified Size by calling resizeImage function.
    if(resizeImage($CurWidth,$CurHeight,$BigImageMaxSize,$DestRandImageName,$CreatedImage,$Quality,$ImageType))
    {
        //Create a square Thumbnail right after, this time we are using cropImage() function
        if(!cropImage($CurWidth,$CurHeight,$ThumbSquareSize,$thumb_DestRandImageName,$CreatedImage,$Quality,$ImageType))
            {
                echo 'Error Creating thumbnail';
            }
        /*
        At this point we have succesfully resized and created thumbnail image
        We can render image to user's browser or store information in the database
        For demo, we are going to output results on browser.
        */

        $url = __DATA_URL__."/images/$target/$NewImageName";
        echo '<img src="'.$url.'?'.$RandomNumber.'" alt="Image" />';

        $db = new Category();
        $db->init();

        $db->updateField($id, "ICON_".strtoupper($IconType), $url);

        $cache = new Cache();
        $cache->updateAllCache();
        /*
        // Insert info into database table!
        mysql_query("INSERT INTO myImageTable (ImageName, ThumbName, ImgPath)
        VALUES ($DestRandImageName, $thumb_DestRandImageName, 'uploads/')");
        */

    }else{
        die('Resize Error'); //output error
    }
}

// This function will proportionally resize image
function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$ImageType)
{
    //Check Image size is not 0
    if($CurWidth <= 0 || $CurHeight <= 0)
    {
        return false;
    }

    //Construct a proportional size of new image
    $ImageScale         = min($MaxSize/$CurWidth, $MaxSize/$CurHeight);
    $NewWidth           = ceil($ImageScale*$CurWidth);
    $NewHeight          = ceil($ImageScale*$CurHeight);
    $NewCanves          = imagecreatetruecolor($NewWidth, $NewHeight);
$color = imagecolorallocatealpha($NewCanves, 0, 0, 0, 127);
imagefill($NewCanves, 0, 0, $color);


    imagealphablending($SrcImage, true);

    // Resize Image
    if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
    {
        switch(strtolower($ImageType))
        {
            case 'image/png':
                imagealphablending($NewCanves, false);
                imagesavealpha($NewCanves, true);  
                imagepng($NewCanves,$DestFolder);
                break;
            case 'image/gif':
                imagegif($NewCanves,$DestFolder);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($NewCanves,$DestFolder,$Quality);
                break;
            default:
                return false;
        }
        //Destroy image, frees up memory
        if(is_resource($NewCanves)) { imagedestroy($NewCanves); }
    return true;
    }

}

//This function corps image to create exact square images, no matter what its original size!
function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality,$ImageType)
{
    //Check Image size is not 0
    if($CurWidth <= 0 || $CurHeight <= 0)
    {
        return false;
    }

    //abeautifulsite.net has excellent article about "Cropping an Image to Make Square"
    //http://www.abeautifulsite.net/blog/2009/08/cropping-an-image-to-make-square-thumbnails-in-php/
    if($CurWidth>$CurHeight)
    {
        $y_offset = 0;
        $x_offset = ($CurWidth - $CurHeight) / 2;
        $square_size    = $CurWidth - ($x_offset * 2);
    }else{
        $x_offset = 0;
        $y_offset = ($CurHeight - $CurWidth) / 2;
        $square_size = $CurHeight - ($y_offset * 2);
    }

    $NewCanves  = imagecreatetruecolor($iSize, $iSize);
    if(imagecopyresampled($NewCanves, $SrcImage,0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size))
    {
        switch(strtolower($ImageType))
        {
            case 'image/png':
                imagepng($NewCanves,$DestFolder);
                break;
            case 'image/gif':
                imagegif($NewCanves,$DestFolder);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($NewCanves,$DestFolder,$Quality);
                break;
            default:
                return false;
        }
    //Destroy image, frees up memory
        if(is_resource($NewCanves)) { imagedestroy($NewCanves); }
    return true;

    }

}
?>

