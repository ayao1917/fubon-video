<?php

error_reporting(0); 
//Cross domain
header("HTTP/1.0 200 OK");
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

define("__UPLOAD__", 1);
date_default_timezone_set('Asia/Taipei');
include_once("../../inc/config.php");
include_once("../../inc/utils.php");
include_once("../../inc/conf.php");
//include_once("../../inc/class_sso.php");

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
elseif(strpos($user_agent, "Android") !== FALSE)
$client = "Android";
elseif(strpos($user_agent, "Mac") !== FALSE)
$client = "Mac";

//$client = (isset($_SESSION['client']))?$_SESSION['client']:"win";
$version = (isset($_REQUEST['version']))?$_REQUEST['version']:"0.0.1";

if (!isset($_REQUEST['syscode'])) fail("缺少必要參數121");
if (!isset($_REQUEST['tokenid'])) fail("缺少必要參數122");


$sysCode = $_REQUEST['syscode'];
$tokenId = $_REQUEST['tokenid'];

$response = doSSO($sysCode, $tokenId);

// converting
$response1 = str_replace("<soap:Body>","",$response);
$response2 = str_replace("</soap:Body>","",$response1);

// convertingc to XML
$parser = simplexml_load_string($response2);

$data_obj = $parser->GetAgentDataResponse->GetAgentDataResult;

debug($response);
$data = (array) $data_obj;

$result_code = $data['Code'];
$feedback = $data['Msg'];
$user_id = $data['Id']."";
$user_name = $data['Name']."";
$user_rank = $data['JobName']."";
$user_unitcode = $data['DeptNo'];
$user_unitcode = str_replace("+", ".", $user_unitcode);


if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else{
    $ip = $_SERVER['REMOTE_ADDR'];
}


$response = array();
//if ($parser->SalesmanLoginResponse->SalesmanLoginResult->SalesmanLoginOutBound['QueryStatus']=="00") {
if ($result_code=="00") {

    $response['result'] = 'success';
    $response['message'] = '登入成功';
    $response['id'] = $user_id;
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
}


llog($client, $user_id, $ip, $version, $feedback);

if (isset($_REQUEST['callback'])) {
    echo  $_REQUEST['callback'].'('.json_encode($response) .')';
} else {
    echo  json_encode($response);
}


function doSSO($sysCode, $tokenId) {

$data = array();

$ch = curl_init ();

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "content-type: text/xml; charset=utf-8",
    "SOAPAction: http://www.fubon.com/sso/webservices/GetAgentData"
    ));

//$url = "http://61.218.16.154/FBMAPPService/SingleSignOnService.asmx";
$url = __WEB_SERVICE_SSO__;

$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <SecurityToken xmlns="http://www.fubon.com/sso/webservices/">
      <Key>fbmapp</Key>
    </SecurityToken>
  </soap:Header>
  <soap:Body>
  <GetAgentData xmlns="http://www.fubon.com/sso/webservices/">
    <sysCode>' . $sysCode . '</sysCode>
    <tokenId>' . $tokenId . '</tokenId>
  </GetAgentData>
  </soap:Body>
</soap:Envelope>
';


//debug($xml_post_string);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $xml_post_string);

$response = curl_exec ($ch);
curl_close($ch);


return $response;


}



function fail($msg) {
    $response = array();
    $response['result'] = 'fail';
    $response['message'] = $msg;
    die(json_encode($response));
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

