<?php

if (!isset($_REQUEST["v"])) die();
if (!isset($_REQUEST["i"])) die();

$id = $_REQUEST["i"];
$path = $_REQUEST["v"];

?>
<html>
<head>
<meta name='viewport' content='width=device-width, user-scalable=no'>
<script src='js/jquery.min.js'></script>
<script src='js/StageWebViewBridge.js'></script>
<script> 
/*
$(window).load(function(){
    StageWebViewBridge.call('ready');
}); 
*/
function onKeyDown(code) {goBack(); } 
function goBack(){StageWebViewBridge.call('stopPlay', null, "<?php echo $id;?>");} 
</script>
</head>
<body style='background-color:#000;'>
<img id='back' src='images/close_box_gray.png' style='position:fixed; right:0; top:0; height:30px; z-Index:1000;' onClick='goBack();'/>
<div style="position:absolute; top:30px; bottom:0; left:0; right:0">
<video id='vplayer' poster='images/poster_360p.jpg' controls autoplay width='100%' height='100%'> <source src='<?php echo $path;?>' type='video/mp4'></video>
</div>

</body></html>
