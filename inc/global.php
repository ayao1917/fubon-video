<?php
if (isset($_REQUEST['user_id'])) {
   $EDITOR_ID = $_REQUEST['user_id'];
} else {
   $EDITOR_ID = "Unknown user";
}




		
function checkDir($filename) {
    if (!file_exists($filename)) {
        if (mkdir ($filename)) return 0; else return -1;
    } 
    return 1;
}
function removeFile($filename) {
    if (file_exists($filename)) {
        unlink ($filename);
    } 
}

function HTTP_Error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
