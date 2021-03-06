<?php

if (!isset($_REQUEST['id'])) die("error");
if (!isset($_REQUEST['sid'])) die("error");

$mobile = (isset($_REQUEST['m']));

$user_agent = getenv("HTTP_USER_AGENT");
if (!$mobile) {
    if(strpos($user_agent, "Win") !== FALSE)
        $os = "Windows";
    elseif(strpos($user_agent, "Mac") !== FALSE)
        $os = "Mac";
}


session_id($_REQUEST['sid']);
session_start();


include_once('../../inc/config.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_book.php');

//mb_internal_encoding('utf-8');
//$bookInfo = json_decode(file_get_contents('../../config/ebooks.json'), true);
//$books = $bookInfo["bookNames"];
$book_db = new Book();
$book_db->init();
$books = $book_db->getBookNames();

$id = $_REQUEST['id'];

if (array_key_exists($id, $books)) {
    $bookname = $books[$id];

    $file = __FDATA_PATH__.'/books/'.$id.".pdf";
    if (!file_exists($file)) die("error3");

    ulog($_SESSION['user_id'], "book", $id);

    $filename = urldecode($bookname).'.pdf'; /* Note: Always use .pdf at the end. */

    if ((!$mobile) && ($os=="win")) $filename = mb_convert_encoding($filename,"BIG-5","UTF-8");

    if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Edge') !== false) {
        header('Content-Disposition: attachment; filename=' . urlencode($filename));
    } else {
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }

    header('Pragma: ');
    header('Cache-Control: ');
    header('Content-type: application/pdf');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($file));
    header('Accept-Ranges: bytes');

    @readfile($file);

} else {
        die("不存在");
}
?>
