<?php

include_once("../../inc/conf.php");

//$url = "https://m.fbl.com.tw:1114/Mobile/web/ValidateCode.aspx";
$url = __CAPTCHA_URL__;

$tmp_dir = "/tmp/fubon";

if (!isset($_REQUEST['p'])) die();

if (!file_exists($tmp_dir)) mkdir($tmp_dir, 0755);

session_start();
$ckfile = tempnam ($tmp_dir, "CURLCOOKIE_".session_id());

file_put_contents($tmp_dir."/index_".$_REQUEST['p'], $ckfile);

$_SESSION['c']=$ckfile;

$ch = curl_init ();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSLVERSION, 6);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds

$output = curl_exec ($ch);
//file_put_contents("/tmp/zzzzz", "111".print_r($output, true));
curl_close($ch); 

if ($output==false) $output = file_get_contents('../../images/login/failloading.png');

header("Content-Type: image/gif");
header("Content-Length: " . strlen($output));

echo $output;
exit;

?>
