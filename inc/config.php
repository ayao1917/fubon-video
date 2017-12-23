<?php
ini_set('default_charset','utf-8');

// 更改主機設定

// 遠端目錄格式:  //10.0.0.1/dir1/dir2
//if (!defined("__DATA_PATH__")) define("__DATA_PATH__", "/inetpub/wwwroot/w/DATA");

// 必須是外界瀏覽器可以連結的目錄或虛擬目錄, 不需加上 http://host，只需要後面的路徑
//if (!defined("__DATA_URL__")) define("__DATA_URL__", "/w/DATA");

// 遠端目錄格式:  //10.0.0.1/dir
//if (!defined("__FDATA_PATH__")) define("__FDATA_PATH__", "/inetpub/wwwroot/w/PDATA");


//------------------------------------
define('__UPLOAD__', 1);
if (!defined("__DATA_PATH__")) define("__DATA_PATH__", "/home/fubon/www/DATA");
if (!defined("__FDATA_PATH__")) define("__FDATA_PATH__", "/home/fubon/FDATA");
if (!defined("__DATA_URL__")) define("__DATA_URL__", "/DATA");
//if (!defined("__URL_PREFIX__")) define("__URL_PREFIX__", "http://fubon.moker.com.tw/");
if (!defined("__URL_PREFIX__")) define("__URL_PREFIX__", "https://fubonevideo.moker.com/");
if (!defined("__FFMPEG__")) define("__FFMPEG__", "/usr/bin/ffmpeg");

if (!defined("__DATABASE__")) define("__DATABASE__", __FDATA_PATH__."/main.dat");
if (!defined("__LOG_DATABASE__")) define("__LOG_DATABASE__", __FDATA_PATH__."/log.dat");
if (!defined("__FEEDBACK_DATABASE__")) define("__FEEDBACK_DATABASE__", __FDATA_PATH__."/feedback.dat");
if (!defined("__EVENT_DATABASE__")) define("__EVENT_DATABASE__", __FDATA_PATH__."/event.dat");
if (!defined("__CACHE_DIR__")) define("__CACHE_DIR__", __FDATA_PATH__."/cache");
if (!defined("__SLT__")) define("__SLT__", "mj2d9dk");
if (!defined("__DATABASE_USER__")) define("__DATABASE_USER__", "");
if (!defined("__DATABASE_PASSWORD__")) define("__DATABASE_PASSWORD__", "");

$PERMISSION = array('CATEGORY', 'VIDEO', 'FRONTPAGE', 'BANNER', 'ANNOUNCEMENT', 'SYSTEM');
    
/* fubon testing environment
if (!defined("__USERINFO_HOST__")) define("__USERINFO_HOST__", "MTAuNDIuNzEuMTM4LDMzMDE=");
if (!defined("__USERINFO_DB__")) define("__USERINFO_DB__", "ZWJvb2s=");
if (!defined("__USERINFO_USER__")) define("__USERINFO_USER__", "RkJMRUJPT0s=");
if (!defined("__USERINFO_PWD__")) define("__USERINFO_PWD__", "NnlobiVUR0I=");
if (!defined("__USERINFO_DSN__")) define("__USERINFO_DSN__", "ZGJsaWI6U2VydmVyPTEwLjQyLjcxLjEzOCwzMzAxO0RhdGFiYXNlPWVib29r");
*/
if (!defined("__USERINFO_DSN__")) define("__USERINFO_DSN__", "c3FsaXRlOi9Vc2Vycy9tL2FnZW50LnNxbGl0ZQ==");
if (!defined("__USERINFO_USER__")) define("__USERINFO_USER__", "");
if (!defined("__USERINFO_PWD__")) define("__USERINFO_PWD__", "");

date_default_timezone_set('Asia/Taipei');

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
    // last request was more than 60 minutes ago


/*
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage
     die("<script>parent.forceLogin();</script>");
*/

//$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
//    header("location: index.php?msg=已超時");
//     die("<script>parent.showLogin();</script>");
    //die("請重新登錄");
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (isset($_SESSION['user_id'])) {
    $USER_ID = $_SESSION['user_id'];
} else {
    if (defined("__UPLOAD__"))  {

        $_SESSION['user_id']='guest';
        $USER_ID = 'guest';
    }else { 
    
        if (isset($_SERVER["REQUEST_URI"]) && basename($_SERVER["REQUEST_URI"], ".php")=="m") {
            header("location: index.php");
        }


        die("<script></script>");
        //die("請重新登錄");
    }
}

if (isset($_SESSION['user_name'])) {
    $USER_NAME = $_SESSION['user_name'];
} else {
    $USER_NAME = $USER_ID;
} 
if (isset($_SESSION['user_rank'])) {
    $USER_RANK = $_SESSION['user_rank'];
} else {
    $USER_RANK = "";
} 
if (isset($_SESSION['user_unitcode'])) {
    $USER_UNITCODE = $_SESSION['user_unitcode'];
} else {
    $USER_UNITCODE = "";
} 



?>
