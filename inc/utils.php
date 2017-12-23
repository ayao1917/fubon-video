<?php
date_default_timezone_set('Asia/Taipei');

// Input: $obj: json object
function json_search($obj, $field, $value)
{
    foreach($obj as $item)
    {
        if(isset($item->$field) && $item->$field == $value)
            {

                return $item;
            }
    }
    return null;
}
function unescape($str){  
   $str = rawurldecode($str);  
   preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|.+/U",$str,$r);  
   $ar = $r[0];  
  
   foreach($ar as $k=>$v){  
       if(substr($v,0,2)=="%u"){  
           $ar[$k]=iconv("UCS-2","UTF-8",pack("H4",substr($v,-4)));}  
       elseif(substr($v,0,3)=="&#x"){  
           $ar[$k]=iconv("UCS-2","UTF-8",pack("H4",substr($v,3,-1)));}  
       elseif(substr($v,0,2)=="&#"){  
           $ar[$k]=iconv("UCS-2","UTF-8",pack("n",substr($v,2,-1)));}  
   }  
   return join("",$ar);  
}  


function json_update($filename, $key_name, $obj) {

    $obj = (object) $obj;
    $hit = 0;

//file_put_contents("/tmp/a.txt", json_encode($obj)."===$key_name===$filename===");

    $new_array = array();
    if (file_exists($filename)) {
        $rows = json_decode(file_get_contents($filename));

        foreach ($rows as $row) {
            if ($row->$key_name == $obj->$key_name) {
                $hit++;
                foreach ($obj as $key=>$value) {
                    $row->$key = $value;
                }
            }
            array_push($new_array, $row);
        }
    } 


    if ($hit==0) array_push($new_array,  $obj);
    
    file_put_contents($filename, json_encode($new_array));
}

function getDate1($timestamp = null) {

    if ($timestamp==null) $timestamp=time();
    $year = date("Y", $timestamp)-1911;
    return $year.date("md", $timestamp);
}

function getDate2($timestamp = null) {
    if ($timestamp==null) $timestamp=time();
    $year = date("Y", $timestamp)-1911;
    return $year.date(".m.d", $timestamp);
}
function getDate3($timestamp = null) {
    if ($timestamp==null) $timestamp=time();
    $year = date("Y", $timestamp)-1911;
    return $year.date("/m/d", $timestamp);
}
function getTime3($timestamp = null) {
    if ($timestamp==null) $timestamp=time();
    return date("H:i:s", $timestamp);
}

function getTime($timestamp = null) {
    if ($timestamp==null) $timestamp=time();
    $year = date("Y", $timestamp)-1911;
    return $year.date(".m.d H:i:s", $timestamp);
}

function h($k) {

    return md5($k);
}

function bucket($m){
    return substr($m, 0, 2);
}

function mlog($type, $user, $action, $object, $misc) {

    $timestamp = getTime();
/*
    $log = fopen(__PDATA_PATH__."/logs/access.log", "a");
    fputs($log, $timestamp." $user $type $action $object $misc\n");
    fclose($log);
*/

    include_once('class_log.php');
    include_once('class_manager.php');

    $logDB = new Logs();
    $logDB->init();

    $entry["DATE"] = getDate3();
    $entry["TIME"] = getTime3();
    $entry["USER"] = $user;
    $entry["ACTION"] = $action;
    $entry["TYPE"] = $type;
    $entry["TARGET"] = $object;
    $entry["EXTRA"] = $misc;

    $mgrDB = new Manager();
    $mgrDB->init();
    $result = $mgrDB->load($user);
    if (count($result)>0) {
        $logDB->insertManagement($entry);
    } else {
        $logDB->insertManagement_IT($entry);
    }
    
}  
function ulog($user, $action, $object="-", $misc="-") {

    $timestamp = getTime();
    $log = fopen(__FDATA_PATH__."/logs/user.log", "a");
    fputs($log, $timestamp." $user $action $object $misc\n");
    fclose($log);
}  
function llog($type, $user, $ip, $ua, $result) {

    $timestamp = getTime();
    $log = fopen(__FDATA_PATH__."/logs/login.log", "a");
    fputs($log, $timestamp." $user $ip $type $ua $result\n");
    fclose($log);
}  

function utf8_substr($StrInput,$strStart,$strLen)
{
    $StrInput = mb_substr($StrInput,$strStart,mb_strlen($StrInput));
    $iString = urlencode($StrInput);
    $lstrResult="";
    $istrLen = 0;
    $k = 0;
    do{
        $lstrChar = substr($iString, $k, 1);
        if($lstrChar == "%"){
            $ThisChr = hexdec(substr($iString, $k+1, 2));
            if($ThisChr >= 128){
                if($istrLen+3 < $strLen){
                    $lstrResult .= urldecode(substr($iString, $k, 9));
                    $k = $k + 9;
                    $istrLen+=3;
                }else{
                    $k = $k + 9;
                    $istrLen+=3;
                }
            }else{
                $lstrResult .= urldecode(substr($iString, $k, 3));
                $k = $k + 3;
                $istrLen+=2;
            }
        }else{
            $lstrResult .= urldecode(substr($iString, $k, 1));
            $k = $k + 1;
            $istrLen++;
        }
    }while ($k < strlen($iString) && $istrLen < $strLen); 
    return $lstrResult;
}

/*
function wlog($type, $user, $action, $object, $misc) {

    $timestamp = getTime();
    $log = fopen("/var/www/html/DATA/logs/access.log");
    fputs($log, $timestamp." $user $type $action $object $misc\n");
    fclose($log);

}  
*/

function startsWith($haystack, $needle)
{
    return !strncmp($haystack, $needle, strlen($needle));
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function filterInput($allow_array, $data) {

    if (!is_array($data)) return $data; 

    if (is_array($allow_array)) {
        $output = array();
        foreach ($data as $key=>$val) {
            if (in_array($key, $allow_array)) $output[$key] = $val;
        }
    } else {
        $output = $data;
    } 
debug($allow_array);
    return $output;
}

function debug($data) {
    $str = (is_array($data))? print_r($data, true):$data;
    file_put_contents("/tmp/zzz", $str."\n", FILE_APPEND);
}

?>
