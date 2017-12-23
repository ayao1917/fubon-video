<?php

date_default_timezone_set('Asia/Taipei');

require __DIR__. '/../../../lib/Moker/O365/vendor/autoload.php';
use Moker\O365\ChangePwdA5;

include_once("../../inc/utils.php");

//error_reporting(0); 
//Cross domain
header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

$data = array();

if (isset($_REQUEST["user"])) {
    $user = $_REQUEST["user"];
} else {
    fail('參數錯誤');
}

if (isset($_REQUEST["qPwd"])) {
    $qPwd = $_REQUEST["qPwd"];
} else {
    fail('參數錯誤');
}
if (isset($_REQUEST["nPwd"])) {
    $nPwd = $_REQUEST["nPwd"];
} else {
    fail('參數錯誤');
}
if (isset($_REQUEST["cPwd"])) {
    $cPwd = $_REQUEST["cPwd"];
} else {
    fail('參數錯誤');
}
if (isset($_REQUEST["Qes"])) {
    $qes = $_REQUEST["Qes"];
} else {
    fail('參數錯誤');
}
if (isset($_REQUEST["Ans"])) {
    $ans = $_REQUEST["Ans"];
} else {
    fail('參數錯誤');
}

$param = [
    'WsID' => "zaq12wsxcde34rfv",
    'WsPwd' => "vfr43edcxsw21qaz",
    'SystemCode' => "MDM-APP-I",
    'ClientIP' => "10.240.64.31",
    'QID' => $user,
    'QPwd' => $qPwd,
    'NPwd' => $nPwd,
    'CPwd' => $cPwd,
    'Qes' => $qes,
    'Ans' => $ans
];


$data = ChangePwdA5::go($param);
$info = (array) $data->ReturnData;
$o365Info = (array) $data->O365Return;

$response = array();

$response['result'] = "success";
$response['ReturnCode'] = $data->ReturnCode;
$response['ReturnMessage'] = $data->ReturnMessage."";
$response['ReturnData'] = $info['ReturnData'];
$response['O365ErrorCode'] = $o365Info['O365ErrorCode'];
$response['O365ErrorMsg'] = $o365Info['O365ErrorMsg'];

debug($_REQUEST['callback'].'('.json_encode($response) .')');
echo  $_REQUEST['callback'].'('.json_encode($response) .')';


function fail($msg) {
    $response = array();
    $response['result'] = 'fail';
    $response['message'] = $msg;
    die($_REQUEST['callback'].'('.json_encode($response) .')');
}

function getIP()
{
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
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

