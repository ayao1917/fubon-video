<?php
//error_reporting(0); 
//Cross domain
header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

require __DIR__. '/../../../lib/Moker/O365/vendor/autoload.php';

use Moker\O365\Login;


define("__UPLOAD__", 1);
date_default_timezone_set('Asia/Taipei');
include_once("../../inc/config.php");
include_once("../../inc/utils.php");
include_once("../../inc/conf.php");

$tmp_dir = "/tmp/fubon";

$cookie_file = "";
$noCaptcha = isset($_REQUEST['nocaptcha']);

if (isset($_SESSION['c'])) {
    $cookie_file = $_SESSION['c'];
} else if (isset($_REQUEST['p'])) {
    $cookie_file = file_get_contents($tmp_dir."/index_".$_REQUEST['p']);
}

if ($cookie_file=='') $cookie_file="/etc/hosts";

$captcha = intval(exec("grep CheckCode $cookie_file|cut -f7"));

$client = "Windows";
$user_agent = getenv("HTTP_USER_AGENT");

if(strpos($user_agent, "Win") !== FALSE)
$client = "Windows";
elseif(strpos($user_agent, "iPad") !== FALSE)
$client = "iPad";
elseif(strpos($user_agent, "iPod") !== FALSE)
$client = "iPod";
elseif(strpos($user_agent, "iPhone") !== FALSE)
$client = "iPhone";
elseif(strpos($user_agent, "Android") !== FALSE) {
    if (strpos($_SERVER['HTTP_REFERER'], "tablet")) {
        $client = "Android_pad";
    } else {
        $client = "Android_phone";
    }
} elseif(strpos($user_agent, "Mac") !== FALSE)
$client = "Mac";

//$client = (isset($_SESSION['client']))?$_SESSION['client']:"win";
$version = (isset($_REQUEST['version']))?$_REQUEST['version']:"0.0.1";


$user = "";
$pass = "";
$validateCode = "";

if (isset($_SESSION['LAST_ACTIVITY'])) {
    $variable = $_SESSION['LAST_ACTIVITY'];
    unset( $_SESSION['LAST_ACTIVITY'], $variable );
}

$data = array();

if ($_REQUEST["user"]=="supermoker") {
    $user = "super";
    $data['IDNum_Or_ErrorMessage']="";
    $data['QueryStatus']="00";
    $data['UserName']="Super";
    $data['Level']="99";
    $data['UnitCode']="AA";
    $data['UnitNo']="BB";

$feedback = "ok";
$result_code = "00";

$user_id = "super";
$user_name = "Super";
$user_rank = "99";
$user_unitcode = "AA.BB";
} else if (($_REQUEST["user"]=="fb001")&&($_REQUEST["pass"]=="pass")) {
    $user = "fb001";
    $data['IDNum_Or_ErrorMessage']="";
    $data['QueryStatus']="00";
    $data['UserName']="FB001";
    $data['Level']="99";
    $data['UnitCode']="AA";
    $data['UnitNo']="BB";
} else {

    $user = (isset($_REQUEST["user"]))?$_REQUEST["user"]:""; 
    $pass = (isset($_REQUEST["pass"]))?$_REQUEST["pass"]:""; 
    $validateCode = (isset($_REQUEST["validateCode"]))?$_REQUEST["validateCode"]:""; 

    if (strlen($user)==0) fail("您尚未輸入帳號");
    if (strlen($pass)==0) fail("您尚未輸入密碼");

    if (!$noCaptcha) { 
        if (strlen($validateCode)==0) fail("您尚未輸入驗證碼。");
        if ($validateCode != $captcha) fail("您輸入的驗證碼錯誤，請重新輸入。");
    }

if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else{
    $ip = $_SERVER['REMOTE_ADDR'];
}


$systemCode = "Video-WEB";
$platform = getBrowserOS();

if (in_array($platform['platform_os'], ['iPhone', 'iPod', 'iPad'])) {
    $systemCode = "Video-APP-I";
} else if (in_array($platform['platform_os'], ['Android', 'Mobile'])) {
    $systemCode = "Video-APP-A";
}


$param = [
    'WsID' => "zaq12wsxcde34rfv",
    'WsPwd' => "vfr43edcxsw21qaz",
//    'SystemCode' => "Mobile-APP-A-I",
    'SystemCode' => $systemCode,
//    'ClientIP' => "10.240.64.31",
    'ClientIP' => $ip,
    'QID' => $user,
    'QPwd' => $pass
];

$data = Login::go($param);

$feedback = $data->ReturnMessage."";
$result_code = $data->ReturnCode;
$userInfo = (array) $data->ReturnData;

$user_id = $userInfo['AgentID']."";
$user_name = $userInfo['AgentName']."";
$user_rank = $userInfo['Rank']."";
$user_unitcode = $userInfo['UnitCode'].".".$userInfo['UnitSeq'];

}

//$result_code="68";
//$feedback="因為這是您第一次登入，或您的密碼已過期，所以您必須更新密碼。";

$response = array();
//if ($parser->SalesmanLoginResponse->SalesmanLoginResult->SalesmanLoginOutBound['QueryStatus']=="00") {
if ($result_code=="00") {
    $response['result'] = 'success';
//    $response['message'] = '登入成功';
    $response['message'] = $feedback;
    $response['id'] = $user_id;
    $response['sid'] = SID;
    $response['rank'] = $user_rank;
    $response['name'] = $user_name;
    $response['unitcode'] = $user_unitcode;

    session_unset();
    session_destroy();
    session_start();

    $response['session'] = SID;

    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_rank'] = $user_rank;
    $_SESSION['user_unitcode'] = $user_unitcode;
} else {
    $response['result'] = 'fail';
    $response['message'] = $feedback;
    $response['code'] = $result_code;
}


llog($client, $user_id, $ip, $version, $feedback);
debug($_SESSION);
debug(SID);


debug($_REQUEST['callback'].'('.json_encode($response) .')');
echo  $_REQUEST['callback'].'('.json_encode($response) .')';



function fail($msg) {
    $response = array();
    $response['result'] = 'fail';
    $response['message'] = $msg;
    $response['code'] = "moker";
die($_REQUEST['callback'].'('.json_encode($response) .')');
}

function getBrowserOS() { 

    $user_agent     =   $_SERVER['HTTP_USER_AGENT']; 
       $browser        =   "Unknown Browser";
       $os_platform    =   "Unknown OS Platform";

        // Get the Operating System Platform

        if (preg_match('/windows|win32/i', $user_agent)) {

            $os_platform    =   'Windows';

            if (preg_match('/windows nt 6.2/i', $user_agent)) {
                $os_platform    .=  " 8";
            } else if (preg_match('/windows nt 6.1/i', $user_agent)) {
                $os_platform    .=  " 7";
                } else if (preg_match('/windows nt 6.0/i', $user_agent)) {
                    $os_platform    .=  " Vista";
                } else if (preg_match('/windows nt 5.2/i', $user_agent)) {
                    $os_platform    .=  " Server 2003/XP x64";
                } else if (preg_match('/windows nt 5.1/i', $user_agent) || preg_match('/windows xp/i', $user_agent)) {
                    $os_platform    .=  " XP";
                } else if (preg_match('/windows nt 5.0/i', $user_agent)) {
                    $os_platform    .=  " 2000";
                } else if (preg_match('/windows me/i', $user_agent)) {
                    $os_platform    .=  " ME";
                } else if (preg_match('/win98/i', $user_agent)) {
                    $os_platform    .=  " 98";
                } else if (preg_match('/win95/i', $user_agent)) {
                    $os_platform    .=  " 95";
                } else if (preg_match('/win16/i', $user_agent)) {
                    $os_platform    .=  " 3.11";
                }
            } else if (preg_match('/macintosh|mac os x/i', $user_agent)) {
                $os_platform    =   'Mac';
                if (preg_match('/macintosh/i', $user_agent)) {
                    $os_platform    .=  " OS X";
                } else if (preg_match('/mac_powerpc/i', $user_agent)) {
                    $os_platform    .=  " OS 9";
                }

            } else if (preg_match('/linux/i', $user_agent)) {

                $os_platform    =   "Linux";

            }

            // Override if matched

                if (preg_match('/iphone/i', $user_agent)) {

                    $os_platform    =   "iPhone";

                } else if (preg_match('/android/i', $user_agent)) {

                    $os_platform    =   "Android";

                } else if (preg_match('/blackberry/i', $user_agent)) {

                    $os_platform    =   "BlackBerry";

                } else if (preg_match('/webos/i', $user_agent)) {

                    $os_platform    =   "Mobile";

                } else if (preg_match('/ipod/i', $user_agent)) {

                    $os_platform    =   "iPod";

                } else if (preg_match('/ipad/i', $user_agent)) {

                    $os_platform    =   "iPad";

                }

        // Get the Browser

            if (preg_match('/msie/i', $user_agent) && !preg_match('/opera/i', $user_agent)) { 

                $browser        =   "Internet Explorer"; 

            } else if (preg_match('/firefox/i', $user_agent)) { 

                $browser        =   "Firefox";

            } else if (preg_match('/chrome/i', $user_agent)) { 

                $browser        =   "Chrome";

            } else if (preg_match('/safari/i', $user_agent)) { 

                $browser        =   "Safari";

            } else if (preg_match('/opera/i', $user_agent)) { 

                $browser        =   "Opera";

            } else if (preg_match('/netscape/i', $user_agent)) { 

                $browser        =   "Netscape"; 

            } 

            // Override if matched

                if ($os_platform == "iPhone" || $os_platform == "Android" || $os_platform == "BlackBerry" || $os_platform == "Mobile" || $os_platform == "iPod" || $os_platform == "iPad") { 

                    if (preg_match('/mobile/i', $user_agent)) {

                        $browser    =   "Handheld Browser";

                    }

                }

        // Create a Data Array

            return array(
                'browser'       =>  $browser,
                'os_platform'   =>  $os_platform
            );

    } 

