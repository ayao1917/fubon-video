<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('m1_list_inc.php');

if (!isset($_REQUEST['id'])) die("error");
if (!isset($_REQUEST['sid'])) die("error");

session_id($_REQUEST['sid']);
session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>富邦新視界下載專區</title>  
    

    <link href="css/flexslider.css" type="text/css" rel="Stylesheet" />
    <link rel="stylesheet" href="css/ui/jquery-ui.min.css"> 
    <link rel="stylesheet" href="css/messi.min.css"> 

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui-dialog-progressbar.min.js"></script>
    <script src="js/jquery.lightbox_me.js"></script>
    <script src="js/messi.min.js"></script>

    <script src="js/config.js"></script>

    <style>
        html {
          height: 100%;
          width: 100%;
          overflow: hidden;
        }

        body {
          height: 100%;
          width: 100%;
          overflow: auto;
          -webkit-tap-highlight-color:  rgba(255, 255, 255, 0); 
        }
        #wrapper {
          height: 100%;
          width: 100%;
          overflow: auto;
          background-size:contain;
          background-image:url(images/hiring/down.jpg);
          background-repeat: no-repeat;
          background-position: center; 
        }

    </style>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">

   <div id="wrapper">

    </div>

    <div id="dialog-confirm" > </div>

</body>

<script type="text/javascript">
    $(window).load(function() {
        setTimeout(function() {
            window.location.href = 'https://fubonevideo.moker.com/ajax/main/file.php?id=<?php echo $_REQUEST['id']; ?>&sid=<?php echo $_REQUEST['sid'] ?>';
        }, 3000);
    });
</script>

</html>
