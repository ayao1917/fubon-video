<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('m1_list_inc.php');

$port = (isset($_REQUEST["p"]))?$_REQUEST["p"]:8888;
$web_mode = ($port==0);

if (($USER_ID=="guest") && (isset($_REQUEST['i']))) {
    $USER_ID = trim($_REQUEST['i']);
    $_SESSION['user_id'] = $USER_ID;
}

$test_user = array('SUPER', 'U220171224', 'B221680066', 'N122352461', 'N222753880', 'H120226044', 'K221999193', 'F223848891', 'A129365125');
file_put_contents("/tmp/uuu", $USER_ID);
file_put_contents("/tmp/rrr", $USER_RANK);
$test_mode = in_array($USER_ID , $test_user);
$test_mode = (1===1);

$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");

ulog($_SESSION['user_id'], "home","-", "-");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>富邦新視界</title>  
    

    <link href="css/flexslider.css" type="text/css" rel="Stylesheet" />
    <link rel="stylesheet" href="css/ui/jquery-ui.min.css"> 
    <link rel="stylesheet" href="css/messi.min.css"> 

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui-dialog-progressbar.min.js"></script>
    <script src="js/jquery.lightbox_me.js"></script>
    <script src="js/jquery.flexslider.js"></script>
    <script src="js/layout.js"></script>
    <script src="js/messi.min.js"></script>

    <script src="js/config.js"></script>

    <?php if (!$web_mode) echo '<script src="js/StageWebViewBridge.js"></script>'; ?>


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

        ::-webkit-scrollbar {
                width: 10px;
            }

        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
        }

        .loading {
            position:absolute;
            left: 50%;
            top: 50%;
            margin-left: -64px;
            margin-top: -64px;
        }

        .vjs-default-skin .vjs-play-progress,
        .vjs-default-skin .vjs-volume-level { background-color: #40e6dd }
        .vjs-default-skin .vjs-control-bar { font-size: 113% }

        #category_content {
            position:absolute; margin: 0 auto; top:50px; left:155px; right:0; bottom:0; background-color:#CECECE; display:none;    
        }
        #category_sorting_panel {

            position:absolute; 
            height:61px; left:5px; right:5px; top: 5px;
            border:solid 2px gray;
            border-radius: 10px;

        }

        #category_sorting_panel ul {
            margin:0 auto;
            width: 570px;
            position: absolute; top:0; left: 10px; margin:0px auto; list-style-type: none;
        }
        #category_sorting_panel li {
            float:left; margin: 7px 5px;
        }
        #category_sorting_panel img {
            position:relative;
            height: 45px;
            margin:0;
            cursor:pointer;
        }

        #category_container {
            position:absolute; left:5px; right:5px; bottom: 5px; top:75px ;margin:0 0;  border: solid 0px blue; overflow:hidden;
        }
        #category_video_list {
            position:absolute; left:0px; right:0px; bottom: 0; top:0px ;margin:0 0;  border: solid 0px green;
        }

        #category_video_list>li{
            border: solid 0px red;
        }
        #tag_content {
            position:absolute; margin: 0 auto; top:50px; left:155px; right:0; bottom:0; background-color:#CECECE; display:none; overflow:hidden; 
        }

        #tag_info {
            position:absolute;
            bottom:290px; left:10px; right:10px; top: 60px;
            border:solid 0px blue;
            text-align:center;

        }

        #tag_header {
            position:absolute;
            height:50px; left:10px; right:10px; top: 10px;
            background-color: #1470CE;
        }
        #tag_icon {
            position:absolute;
            height:40px; left:10px; top: 8px;
        }
        #tag_txt {
            position:absolute;
            height:30px; left:40px; top: 12px;
        }
        #tag_info_img {
            margin-top:75px;
            margin-left: auto;
            margin-right: auto;
            display:block;
            max-width:98%; max-height:98%;
        }
        #tag_container {
            position:absolute;
            height:280px; left:10px; right:10px; bottom: 5px;
            border:solid 0px red;
            text-align:center;
        }
        #tag_video_list {
            min-width: 720px;
            max-width: 1000px;
            height: 250px;
            border: solid 0px red;
            padding: 10px 20px;
            position:relative; text-align:center;
            margin: 10px auto;
        }

        #tag_video_list>li{
            border: solid 0px red;
        }
        #tag_video_list img {
        }

        #search_content {
            position:absolute; margin: 0 auto; top:50px; left:155px; right:0; bottom:0; background-color:#CECECE; display:none;    overflow:hidden;
        }
        #search_container {
            position:absolute; left:5px; right:5px; bottom: 5px; top:75px ;margin:0 0;  border: solid 0px blue; overflow:hidden;
        }
        #search_video_list {
            position:absolute; left:0px; right:0px; bottom: 0; top:0px ;margin:0 0;  border: solid 0px green;
        }

        #search_video_list>li{
            border: solid 0px red;
        }
        
        #home {
            position:absolute; margin: 0 auto; top:50px; left:155px; right:0; bottom:0; background-color:#CECECE;    
        }
        #download_content {
            position:absolute; margin: 0 auto; top:50px; left:155px; right:0; bottom:0; background-color:#EDEDED; display:none;
        }
        #downloadlist {
            position:absolute;
            left: 15px;
            top:70px;
            bottom:5px;
            right:15px;
        }
        #download_header {
            position:absolute;
            height:50px; left:10px; right:10px; top: 10px;
            background-color: #4E4E4E;
        }
        #download_icon {
            position:absolute;
            height:40px; left:10px; top: 8px;
        }
        #download_txt {
            position:absolute;
            height:30px; left:40px; top: 12px;
        }
        #download_refresh {
            position:absolute;
            cursor: pointer;
            height:24px; right:10px; top: 16px;
        }
        .download_item {
            background-repeat: no-repeat;
            background-position: center center;
            display: inline-block;
        }
        #hiring_detail_content {
            position:absolute; margin: 0 auto; top:50px; left:150px; right:0; bottom:0; background-color:#CECECE; display:none;    overflow:hidden;
        }

        #hiring_detail_metadata {
            background-color:#EDEDED;
            position:absolute; left:0; width: 297px;top:0; bottom:0;overflow:auto; text-align:center;
        }

        #hiring_detail span {

            font-weight:normal;
            font-family:"新細明體",Arial,SimHei, KaiTi, Microsoft JhengHei, DFKai-sb, PMingLiU, MingLiU, serif;
            color: #555;
        }
        #hiring_detail p, #hiring_detail>div>div {
            text-align:left;
            font-weight:normal;
            -webkit-font-smoothing: subpixel-antialiased ;
            -webkit-font-smoothing: antialiased ;
            margin: 4px 20px;
            color: #555;
            font-family: "新細明體",Arial,"HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            text-align: justify;
            text-justify:inter-ideograph;
            filter: chroma( color=#CCCCCC);
        }

        #hiring_detail_container {
            position:absolute; left: 300px; right:0; top:0px; bottom:260px;text-align:center;
            background-color:#EDEDED;
        }
        #hiring_buttons {
            position:absolute;
            height:40px; left:260px; right:0; bottom: 215px;
            text-align:center;
            background-color:#EDEDED;
        }
        #hiring_buttons ul {
            margin:0 auto;
            width: 600px;

            position: relative; margin:2px auto; list-style-type: none;
        }
        #hiring_buttons li {
/*
            float:left; margin: 1px 1px;
*/
display:inline-block; margin: 1px 10px;

        }
        #hiring_buttons img {
            position:relative;
            height: 35px;
            cursor:pointer;
        }
        #hiring_relate_list_container {
            position:absolute; left:300px; right:0; bottom: 0px; height: 180px;
            background-color:#EDEDED;
        }
        #hiring_relate_list {
            position:absolute; left:0px; right:0px; bottom: 0; top:0px ;margin:0 0;  border: solid 0px green;
        }

        #hiring_relate_txt {
            position:absolute; left:300px; right:0; bottom: 180px; height: 30px;
            background-color:#F55F2A;
        }
        #hiring_relate_txt>img {
            position:absolute;
            left:7px; top:5px;
            height: 20px;
        }

        #video_content {
            position:absolute; margin: 0 auto; top:50px; left:150px; right:0; bottom:0; background-color:#CECECE; display:none;    overflow:hidden;
        }

        #video_metadata {
            background-color:#EDEDED;
            position:absolute; left:0; width: 297px;top:0; bottom:0;overflow:auto; text-align:center;
        }

        #video_detail span {

            font-weight:normal;
            font-family:"新細明體",Arial,SimHei, KaiTi, Microsoft JhengHei, DFKai-sb, PMingLiU, MingLiU, serif;
            color: #555;
        }
        #video_detail p, #video_detail>div>div {
            text-align:left;
            font-weight:normal;
            -webkit-font-smoothing: subpixel-antialiased ;
            -webkit-font-smoothing: antialiased ;
            margin: 4px 20px;
            color: #555;
            font-family: "新細明體",Arial,"HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            text-align: justify;
            text-justify:inter-ideograph;
            filter: chroma( color=#CCCCCC);
        }

        #video_container {
            position:absolute; left: 300px; right:0; top:0px; bottom:260px;text-align:center; 
            background-color:#EDEDED;
        }


        #video_buttons {
            position:absolute;
            height:40px; left:300px; right:0; bottom: 215px;
            text-align:center;
            background-color:#EDEDED;
        }
        #video_buttons ul {
            margin:0 auto;
            width: 400px;

            position: relative; margin:2px auto; list-style-type: none;
        }
        #video_buttons li {
            float:left; margin: 1px 10px;
        }
        #video_buttons img {
            position:relative;
            height: 35px;
            cursor:pointer;
        }

        #video_relate_list_container {
            position:absolute; left:300px; right:0; bottom: 0px; height: 180px;
            background-color:#EDEDED;
        }
        #video_relate_list {
            position:absolute; left:0px; right:0px; bottom: 0; top:0px ;margin:0 0;  border: solid 0px green;
        }
        #video_relate_txt {
            position:absolute; left:300px; right:0; bottom: 180px; height: 30px;
            background-color:#1D8DCC;
        }
        #video_relate_txt>img {
            position:absolute;
            left:7px; top:5px;
            height: 20px;
        }
        
        #home_banners {
            position:absolute; top:5px; left:0;  right:0; top:0; bottom: 232px; text-align:center; overflow:hidden;
        }
        
        #home_banner_slider {
            position:absolute; margin: 1px auto; 
            height:98%;
            width:100%;
            text-decoration:none; border:solid 0px green;
        }

        .flex-control-nav {
            bottom: 20px;
            position: absolute;
            left:20px;
            right: 20px;
            text-align: center;
            width: auto;
            z-index: 99;
        }

        .play_button {
            background-image: url('images/play_normal.png');
            position: absolute;
            display:none;
            left: 40%;
            top:40%;
            width: 118px;
            height: 109px;
            z-Index:100;
            cursor:pointer;
        }
        .play_button:hover {
            background-image: url('images/play_over.png');
        }

        .prev {
            position: absolute;
            left: 10%;
            top:47%;
            width: 40px;
            height: 40px;
            z-Index:1000;
            cursor:pointer;
        }
        .next {
            position: absolute;
            right: 10%;
            top:47%;
            width: 40px;
            height: 40px;
            z-Index:1000;
            cursor:pointer;
        }
        
        #new_area {
            background-color:#EDEDED;
            position:absolute; 
            height:230px; left:0; width:300px;  bottom: 0px; margin:0; overflow:hidden;
        }
        #new_area_header {
            background-color:#1D8DCC;
            position:absolute; 
            height:40px; left: 5px; right: 5px; top:8px;
        }
        #new_area_banner {
            position:absolute; 
            height:40px; left: -2px; top:-2px;
        }
        #new_area_txt {
            position:absolute; 
            height:25px; left: 30px; top:8px;
        }
        #new_area_video_list {
            position:absolute; left:20px; right:20px; bottom: 0; top:50px ;margin:0 0;  border: solid 0px green;
        }
        #new_area li {
            list-style-type: none;
        }
        #hot_area {
            background-color:#EDEDED;
            position:absolute; 
            height:230px; left: 305px; right:0; bottom: 0px; overflow:hidden;
        }
        #hot_area_header {
            background-color:#229E9A;
            position:absolute; 
            height:40px; left: 5px; right: 5px; top:8px;
        }
        #hot_area_header img{
            position:absolute; 
            height:25px; left: 30px; top:8px;
        }
        #hot_area_video_list {
            position:absolute; left:20px; right:20px; bottom: 0; top:50px ;margin:0 0;  border: solid 0px green;
        }
        #hot_area li {
            list-style-type: none;
        }
        #hot_area , #new_area  {
            display:inline-block;
            cursor:pointer;
            max-width:100%;
            max-height:100%;
        }
        .slides{
        }

        #item_list {
            position:absolute; left:0; right:0; bottom: 0; height: 190px;margin:0 0;
        }
        
        
        #item_list img {
            height: 180px;
            margin: 5px 20px;
            cursor:pointer;
        }

        .slideshow {
            margin-left: 50px;
            margin-right:50px;
        }    

        .roundabout-holder {
            list-style: none;
            padding: 0;
            margin: 0;
            height: 100%;
            width: 100%;
        }
        .roundabout-moveable-item {
            height: 100%;
            width: 100%;
            cursor: pointer;
            background-color: #ccc;
            border: 0px solid #999;
        }
        .roundabout-in-focus {
            cursor: auto;
        }

        #dialog-confirm {
            display:none;
        }
        #viewer {
            display:none;
            position:absolute;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #4C4C4C), color-stop(1, #020202));
            width:80%; height:80%;
            padding: 30px;
            border-radius:10px;
        }
        #wrapper{
            visibility:hidden;
            position:absolute;
            overflow:hidden;

            font-family:SimHei, KaiTi, Microsoft JhengHei, DFKai-sb, PMingLiU, MingLiU, serif;
        }

        #top {
            position: absolute;
            top:0;
            background-color: #EDEDED;
            height:45px;

            z-Index:10;
            left:0;
            right:0;
        }

        #home_button {
            cursor:pointer;
        }
        #menu::-webkit-scrollbar {
            display:none;

        }
        #menu {
            position: absolute;
            top:48px;
            bottom:10px;
            width:150px;
            background-color: #EDEDED;
            overflow-y:auto;
            overflow-x:hidden;
            margin:0;
            z-Index:10;
        }
        #menu ul {
            margin:0px 0px;
            padding-left:1px;
            font-size:0;
            list-style-type: none;
        }
        #menu img {
            width: 144px;
            cursor:pointer;
        }
        #download_list span {
           text-align:center;
        }
        #download_list span img {
           width:45%;
           cursor:pointer;
        }
        #download_list span div {

           /*width:45%;*/
           width:60px;
           height:23px;
           display:inline-block;
           cursor:pointer;

        }
        .progress {
            left:8%;
            right:8%;
            bottom:25%;
            height:5%;
            position:absolute;
            background:#C4D0DA;
        }
        .ui-progressbar-value{
            background:#36A7E6;
        }
        #hiring_content {
            position:absolute; margin: 0 auto; top:50px; left:155px; right:0; bottom:0; background-color:#CECECE; display:none; overflow:hidden;
        }

        #hiring_info {
            position:absolute;
            bottom:290px; left:10px; right:10px; top: 60px;
            border:solid 0px blue;
            text-align:center;

        }

        #hiring_header {
            position:absolute;
            height:50px; left:10px; right:10px; top: 10px;
            background-color: #C1490A;
        }
        #hiring_icon {
            position:absolute;
            height:40px; left:10px; top: 8px;
        }
        #hiring_txt {
            position:absolute;
            height:30px; left:40px; top: 12px;
        }
        #hiring_info_img {
            margin-top:75px;
            margin-left: auto;
            margin-right: auto;
            display:block;
            max-width:98%; max-height:98%;
        }
        #hiring_container {
            position:absolute;
            height:280px; left:10px; right:10px; bottom: 5px;
            border:solid 0px red;
            text-align:center;
        }
        #hiring_video_list {
            min-width: 720px;
            max-width: 1000px;
            height: 250px;
            border: solid 0px red;
            padding: 10px 20px;
            position:relative; text-align:center;
            margin: 10px auto;
        }

        #hiring_video_list>li{
            border: solid 0px red;
        }
        #hiring_video_list img {
        }
    
    </style>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">

   <div id="wrapper">
        <div id="top">
            <img id="home_button" src="images/top/top_home_01.png" onClick="onMenuClicked('home', 'home', 'home')" style="position:absolute; left:20px; top:5px; height:35px"/>
            <img src="images/top/fubonvision_logo.png" onclick="onAboutClicked(); " style="position:absolute; left:100px; height:35px;top:5px;"/>

            <input id="query" type="text" style="width: 200px; position:absolute; border-radius: 5px; font-size:1.2em; top:5px; right: 160px;" />
            <img id="search" src="images/top/top_search_normal.png" onClick="onMenuClicked('search', 'search', $('#query').val());"   style="position:absolute; right:100px; height:35px;top:5px;cursor:pointer"/>
            <img id="showdownload_btn" src="images/top/top_downloads_normal.png" onClick="onMenuClicked('download', 'download', 'download')" style="position:absolute; cursor:pointer; right:40px; height:35px;top:5px;"/>
            <img id="page_refresh" style="position:absolute; right:5px; top:10px; height:25px" onClick="location.reload();" src="images/refresh.png" />
    
        </div>
        <div id="menu">

            <ul>

            <div id="categories">
            </div>

            <li><img src="images/left/left_banner_serial_title.png" /></li>

            <div id="tags">
            </div>

<?php if ($test_mode) { ?>
<li><img id='hiring_1' src="DATA/images/hiring/1_normal.png" width="78" height="40" onclick="onMenuClicked('hiring_1', 'hiring', 'hiring');"/></li>
<?php } ?>

            </ul>
        </div>


        <div id="viewer">

	    <img src="images/close_box_gray.png" onClick='onLightboxClose()' style="z-Index:1000;position:absolute; cursor:pointer; width:25px; height: 25px; right:5px; top:2px"/>
	</div>


	<div id="home" >
	    <div id="home_banners">

	    </div>

	    <div id="new_area"> 
		<span id="new_area_header" ><img id="new_area_banner" src="images/new/banner.png" /><img id="new_area_txt" src="images/new/new_txt.png" /> </span>

		<div id="new_area_video_list" class="flexslider">
		</div>
		<div style="position:absolute; width:20px; left:0px; top: 50px; bottom:0;" > 
		    <img src="images/new_left_go.png" id="new_prev" onClick='$("#new_area_video_list").flexslider("prev"); ' style="position:absolute;left:0; top:45%; margin: 0; width:20px; height:20px;visibility:hidden" />
		</div>
		<div style="position:absolute; width:20px; right:0px; top: 50px; bottom:0;" > 
		    <img src="images/new_right_go.png" id="new_next" onClick='$("#new_area_video_list").flexslider("next"); ' style="position:absolute;left:0; top:45%; margin: 0; width:20px; height:20px;" />
		</div>
	    </div>
	    <div id="hot_area"> 
		<span id="hot_area_header" ><img src="images/hot/hot_txt.png" /></span>
		<div id="hot_area_video_list" class="flexslider">
		</div>
		<div style="position:absolute; width:20px; left:0px; top: 50px; bottom:0;" > 
		    <img src="images/new_left_go.png" id="hot_prev" onClick='$("#hot_area_video_list").flexslider("prev"); ' style="position:absolute;left:0; top:45%; margin: 0; width:20px; height:20px;visibility:hidden" />
		</div>
		<div style="position:absolute; width:20px; right:0px; top: 50px; bottom:0;" > 
		    <img src="images/new_right_go.png" id="hot_next" onClick='$("#hot_area_video_list").flexslider("next"); ' style="position:absolute;left:0; top:45%; margin: 0; width:20px; height:20px;" />
		</div>
	    </div>

	</div>    

	<div id="category_content" >
	
	    <div id="category_sorting_panel"> 
		<img src="images/index/index_icon.png" style="position:absolute; left:3px; top:7px;" />
		<ul>
		    <li><img id="sort_hot" src="images/index/index_icon01_normal.png" onClick="category_make_video_list(0);" /></li>
		    <li><img id="sort_date" src="images/index/index_icon02_normal.png" onClick="category_make_video_list(1);" /></li>
		</ul>    
	    </div>
	
	    <div id="category_container"> 
	
	    </div>    
		
	</div>  
	<div id="tag_content" >
		<span id="tag_header"> 
		    <img id="tag_icon" src="images/serial/serial_icon.png" />
		    <img id="tag_txt" src="images/serial/serial_txt.png" />
		</span>
	    <div id="tag_info">
	    </div>
	    <div id="tag_container"> 
	    </div>    
	</div>   
        <div id="hiring_content" >
                <span id="hiring_header">
                    <img id="hiring_icon" src="images/hiring/hiring_icon.png" />
                    <img id="hiring_txt" src="images/hiring/hiring_txt.png" />
                </span>
            <div id="hiring_info">
            </div>
            <div id="hiring_container">
            </div>
        </div>
        <div id="hiring_detail_content" >
            <div id="hiring_detail" style="display:block">
                <div id="hiring_detail_metadata" >
                </div>
            </div>
            <div id="hiring_detail_container" >
            </div>

            <div id="hiring_buttons">
                <ul>

                </ul>
            </div>

            <span id="hiring_relate_txt"><img src="images/hiring/related_data.png" /> </span>

            <div id="hiring_relate_list_container"> </div>
        </div>
	<div id="search_content" >
	    <p style="position:absolute; left:5px; right:5px; margin: 0; padding: 10px 50px; top: 5px; background-color:#239F98; font-size:1.5em; color:#FFF;">搜尋結果：搜尋 <span id="search_term"> </span> 共找到 <span id="search_hit"> </span> 筆資料 </p> 
	    <img src="images/search.png" style="position:absolute; left:10px; top:15px; width:32px; height:32px;" />

	    <div id="search_container"> 
	    </div>    
	</div> 

	<div id="video_content" >
	    <div id="video_detail" style="display:block">
		<div id="video_metadata" >
		</div>
	    </div>
	    <div id="video_container" >
    <!--
		    <span id="loading" style="position:absolute; display:none; left:50%; top:50%;  margin-top: -64px; margin-left: -64px; z-index:1000;" > <img src="images/animal0020.gif"/> </span>
		    <div id="v1" style="z-index:10;"></div>
    -->
	    </div>

	    <div id="video_buttons">
		<ul>
		    <li><img id="video_sd_button" src="images/film/film_banner_sd_press.png" onClick="$(this).attr('src', 'images/film/film_banner_sd_press.png');$('#video_hd_button').attr('src', 'images/film/film_banner_hd_normal.png');$('#loading').show(); video_loadVideo('video_sd');"/> </li>
		    <li><img id="video_hd_button" src="images/film/film_banner_hd_normal.png" onClick="$(this).attr('src', 'images/film/film_banner_hd_press.png');$('#loading').show();$('#video_sd_button').attr('src', 'images/film/film_banner_sd_normal.png'); video_loadVideo('video_hd'); "/> </li>
		    <li><img id="video_download_button" src="images/film/film_banner_downloads_normal.png" onClick="doDownload();" /> </li>
		    <li><img id="video_downloading_button" style="display:none" src="images/downloads/downloading.png" /> </li>
		    <li><img id="video_downloaded_button" style="display:none" src="images/downloads/downloaded.png" /> </li>
		</ul>
	    </div>

	    <span id="video_relate_txt"><img src="images/relate/related.png" /> </span>

	    <div id="video_relate_list_container"> </div>
	</div>   

	<div id="download_content">

	    <span id="download_header"> 
	    <img id="download_icon" src="images/downloads/download_icon.png" />
	    <img id="download_txt" src="images/downloads/download_txt.png" />
	    <img id="download_refresh" onClick="refreshDownload()" src="images/refresh.png" />
	    </span>
	
	    <div id="downloadlist"> </div>    

	</div>

    </div>
        <div id="dialog-confirm" > </div>
</body>

	<script type="text/javascript">
	    var buttonArray=[];
	    var video_list_0=[];
	    var video_list_1=[];
	    var $vid_obj=null;
	    var previous_page;
	    var current_page="home";
	    var home_player;

            var g_serverPort=8888;
            var g_use_local_cache=false;
            var g_user;
            var g_rank;
            var g_unitcode;
            var g_appVersion;
            var g_download_task_all=[];
            var g_download_task_finished=[];
            var g_download_progress_array=[];
            var g_current_video;
	    var g_video_sd;
	    var g_video_hd;
            var g_webmode = <?php echo ($web_mode)?"true":"false"; ?>;


            var g_category_list = <?php echo json_encode(getResourceList("category")); ?>;
            var g_tag_list = <?php echo json_encode(getResourceList("tag")); ?>;
            var g_banner_list = <?php echo json_encode(getResourceList("banner")); ?>;
            var g_hot1_list = <?php echo json_encode(getResourceList("hot1")); ?>;
            var g_hot2_list = <?php echo json_encode(getResourceList("hot2")); ?>;


	    function initButtonArray() {

		buttonArray = [
		    {id:'home', normal:'images/top/top_home_01.png', press:'images/top/top_home_01.png', link:'home'},
                    {id:'hiring_1', normal:'DATA/images/hiring/1_normal.png', press:'DATA/images/hiring/1_press.png', link:'hiring'}
		    ];
	    }

        function logoutHandler() {
            $("#dialog-confirm").attr('title', '訊息視窗').html('登入已逾時，請重新登入').dialog({
                resizable: false,
                height:200,
                width:300,
                modal: true,
                buttons: {
                    "確定": function() {
                        StageWebViewBridge.call('logOut');
                        $( this ).dialog( "close" );
                    }
                }
            });
        }

            var idleTime;
            var idleInterval;
	    $(document).ready(function() {

            showMessage('重大通知：富邦新視界已更新版本，目前版本將於6/15停用，請儘速至行動e市集更新；電腦版APP將停用，請直接進入行動辦公室網頁版使用');

                idleTime = 0;

                idleInterval = setInterval(timerIncrement, 1000);

                function timerIncrement() {
                    idleTime++;
                    //$("#timer").html(idleTime);
                    if (idleTime > 7200) {
                        clearTimeout(idleInterval);
                        timeoutHandler();
                    }
                }

                $(document).bind("touchstart", function(e){
                    idleTime = 0;
                });

                function timeoutHandler() {
                    $("#wrapper").css("display", "none");
                    new Messi('登入已逾時，請重新登入', {
                        title: '訊息視窗', 
                        modal: true,
                        width: '300px',
                        padding: '0px',
                        buttons: [{id: 0, label: '確定', val: 'Y'}], 
                        callback: function(val) { 
                            location.href='http://127.0.0.1:'+g_serverPort+"/login_tablet_o365_release.html?true";
                        }
                    });
                }

                url = window.location.toString();

                g_user = getParameterByName('i');
                g_rank = getParameterByName('r');
                g_unitcode = getParameterByName('u');

		initButtonArray();
		$('img').bind('contextmenu', function(e) {
		    return false;
		}); 

		$("#wrapper").css('visibility', "visible");

                var html="";
	        var dom_id;
                $.each(g_category_list, function(key, item) {
	            dom_id = 'category_button_'+item.id;
                   if (!g_webmode && g_use_local_cache) {
                       item.normal = "http://127.0.0.1:"+g_serverPort+"/"+item.normal;
                       item.press = "http://127.0.0.1:"+g_serverPort+"/"+item.press;
                   }
	            buttonArray.push({id: dom_id, normal: item.normal, press:item.press, link:'category', target_id: item.id, index:key});
	            //html+= "<li><img id='"+dom_id+"' onClick='onMenuClicked(\""+dom_id+"\", \"category\", "+item.id+")' src='" + item.normal +"' /></li>";
	            //html+= "<li><img id='"+dom_id+"' onClick='onMenuClicked(\""+dom_id+"\", \"category\", "+key+")' src='" + item.normal +"' /></li>";
		    html+= "<li><img id='"+dom_id+"' data-id='"+dom_id+"' data-key='"+key+"' src='" + item.normal +"' /></li>";
	        });
		$("#categories").html(html);
$("#categories img").on("touchstart", function() {
    onMenuClicked($(this).data("id"),"category", $(this).data("key"))
});

	       html="";
	       $.each(g_tag_list, function(key, item) {
	           dom_id = 'tag_button_'+item.id;
                   if (!g_webmode && g_use_local_cache) {
                       item.normal = "http://127.0.0.1:"+g_serverPort+"/"+item.normal;
                       item.press = "http://127.0.0.1:"+g_serverPort+"/"+item.press;
                   }
		   buttonArray.push({id: dom_id, normal: item.normal, press:item.press, link:'tag', target_id: item.id, index:key});
		   //html+= "<li><img id='"+dom_id+"' onClick='onMenuClicked(\""+dom_id+"\", \"tag\", "+item.id+")' src='" + item.normal +"' /></li>";
//		   html+= "<li><img id='"+dom_id+"' onClick='onMenuClicked(\""+dom_id+"\", \"tag\", "+key+")' src='" + item.normal +"' /></li>";
		   html+= "<li><img id='"+dom_id+"' data-id='"+dom_id+"' data-key='"+key+"' src='" + item.normal +"' /></li>";
               });
	       $("#tags").html(html);

$("#tags img").on("touchstart", function() {
    onMenuClicked($(this).data("id"),"tag", $(this).data("key"))
});


		var event = "click";
		var ua = navigator.userAgent;
		if (ua.match(/iPad/i)) {
		    event = "touchstart";
		    document.addEventListener( 'touchmove', function(e) {    
//			       e.preventDefault();
                        if (!$(e.target).is("video")) e.preventDefault();
		    }, false);

		    var elem = document.getElementById('video_metadata');

		    elem.addEventListener('touchstart', function(event){
			this.allowUp = (this.scrollTop > 0);
			this.allowDown = (this.scrollTop < this.scrollHeight - this.clientHeight);
			this.prevTop = null; 
			this.prevBot = null;
			this.lastY = event.pageY;
		    });

		    elem.addEventListener('touchmove', function(event){
			var up = (event.pageY > this.lastY), 
			    down = !up;
		
			this.lastY = event.pageY;
		
			if ((up && this.allowUp) || (down && this.allowDown)) 
			    event.stopPropagation();
			else 
			    event.preventDefault();
		    });
		}

		onWindowSize();
		$(window).resize(function() {
		    onWindowSize();
		});
		


		if (g_webmode==1) { 
                    home_layout();
                } else {
                home_layout();
//            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'entry_phone.html');
//            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'entry_tablet.html');
//            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'images/animal0020.gif');
//            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'images/poster_360p.jpg', "<?php echo date("YmdHis", filemtime("/home/fubon/www/images/poster_360p.jpg"));?>");
//            StageWebViewBridge.call('openURL', null, 'https://fubonevideo.moker.com/downloads/FubonVideo1.2.3_arm.apk');
//            StageWebViewBridge.call('doDownloadFile', null, 'https://fubonevideo.moker.com/downloads/FubonVideo1.2.3_arm.apk', 'fv.apk');
               }
if ((typeof StageWebViewBridge)== "undefined") return;
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_phone.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_tablet.html');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'js/config.js');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'js/messi.min.js');
        StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'css/messi.min.css');

            });

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

            function downloadFileComplete(url) {
var intent = url+'#Intent;scheme=file;action=android.intent.action.VIEW;type=application/vnd.android.package-archive;launchFlags=0x10000000;end';

intent.replace('file', 'intent');

               // StageWebViewBridge.call('openURL', null, 'intent:///storage/sdcard0/Download/FubonVideo1.2.3_arm.apk#Intent;scheme=file;action=android.intent.action.VIEW;type=application/vnd.android.package-archive;launchFlags=0x10000000;end');
               StageWebViewBridge.call('openURL', null, intent);


            }

	    /************************* Download Layout *****************************/
	    function download_layout() {

                len = g_download_task_all.length;

                $(".download_item").unbind("click");

                html_content = '<div id="download_list" style="margin-left:auto; margin-right:auto;;height:97%; width:97%; border: solid 0px red;">';

                for (i=0; i<len; i++) {
                    id = g_download_task_all[i];

                    cell = '<div id = "item_'+id+'" style="position:relative; margin: 10px 5px; float:left; width: 18%; height: 48%; border: solid 0px red;">'+
                           '<div style="width:100%;height:90%;background:url(DATA/images/cover/' + id + '.png) no-repeat center center;background-size: contain"/>' +
                           '<span style="position:absolute;left:0; bottom:0px; width:100%; height: 10%">' + 
                           '<div class="download_item" data-id="'+id+'" data-action="play" style="background-image:url(images/downloads/play_normal.png);"></div>' +
                           '<div class="download_item" data-id="'+id+'" data-action="pause" style="background-image:url(images/downloads/pause_normal.png);"></div>' +
                           '<div class="download_item" data-id="'+id+'" data-action="resume" style="background-image:url(images/downloads/resume_normal.png);"></div>'+
                           '<div class="download_item" data-id="'+id+'" data-action="delete" style="background-image:url(images/downloads/delete_normal.png);"></div>' +
                           '</span>' +
                           '<div class="progress" id="progress_'+ id + '"></div>' + 
                           '</div>';

                    html_content+=cell;
                }
                html_content +="</div>";

                $("#downloadlist").html(html_content);

                $(".download_item").click(function() {

                    var $id = $(this).data("id");
                    switch ($(this).data("action")) {
                        case "play": onPlay($id); break;
                        case "pause": onPause($id); break;
                        case "resume": onResume($id); break;
                        case "delete": onDelete($id); break;
                    }
                });;


                $.each(g_download_progress_array, function(key, value) {

                    switch(value.status) {
                        case 0:  //STATUS_DOWNLOADING
                            
                            $('#item_'+value.id + ' .progress').show().progressbar({ value: value.progress, background: "#B637E6" });
                            $('#item_'+value.id + ' [data-action="play"]').hide();
                            $('#item_'+value.id + ' [data-action="pause"]').show();
                            $('#item_'+value.id + ' [data-action="resume"]').hide();
                            $('#item_'+value.id + ' [data-action="delete"]').show();
                            break;
                        case 1:  //STATUS_COMPLETE
                            $('#item_'+value.id + ' .progress').hide();
                            $('#item_'+value.id + ' [data-action="play"]').show();
                            $('#item_'+value.id + ' [data-action="pause"]').hide();
                            $('#item_'+value.id + ' [data-action="resume"]').hide();
                            $('#item_'+value.id + ' [data-action="delete"]').show();

                            value.progress=100;
    
                            break;
                        case 2:  //STATUS_PAUSE
                            $('#item_'+value.id + ' .progress').show().progressbar({ value: value.progress, background: "#B637E6" });
                            $('#item_'+value.id + ' [data-action="play"]').hide();
                            $('#item_'+value.id + ' [data-action="pause"]').hide();
                            $('#item_'+value.id + ' [data-action="resume"]').show();
                            $('#item_'+value.id + ' [data-action="delete"]').show();
                            break;
    
                        case 3:  //STATUS_PENDING
                            $('#item_'+value.id + ' .progress').show().progressbar({ value: value.progress, background: "#B637E6" });
                            $('#item_'+value.id + ' [data-action="play"]').hide();
                            $('#item_'+value.id + ' [data-action="pause"]').show();
                            $('#item_'+value.id + ' [data-action="resume"]').hide();
                            $('#item_'+value.id + ' [data-action="delete"]').show();
                            break;
                        case 4:  //STATUS_ERROR
                            $('#item_'+value.id + ' .progress').show().progressbar({ value: value.progress, background: "#B637E6" });
                            $('#item_'+value.id + ' [data-action="play"]').hide();
                            $('#item_'+value.id + ' [data-action="pause"]').hide();
                            $('#item_'+value.id + ' [data-action="resume"]').hide();
                            $('#item_'+value.id + ' [data-action="delete"]').show();
                            break;

                    }
                    if ((current_page=="video")&&(value.id==g_current_video)&&(value.progress==100)) {

                        $("#video_sd_button").hide();
                        $("#video_hd_button").hide();
                        $("#video_download_button").hide();
                        $("#video_downloading_button").hide();
                        $("#video_downloaded_button").show();
                    }

                });


                return;

                StageWebViewBridge.call('getDownloadedList', function(data) {
                    download_make_video_list(data);
/*
                    $.each(data, function(key, value) {
                        $('#downloaded_icon_'+value.id).css("display", "inline-block"); 
                    });
*/
                });
            }

            function setupDownloadListData(data) {
                g_download_task_all=[];
                g_download_task_finished=[];
                g_download_progress_array=[];

                len = data.length;
                for (var i=0; i<len; i++) {
                    id = data[i].id;
                    g_download_task_all.push(id);

                    var z=new Object;
                    z.id=data[i].id;
                    z.status=data[i].status;
                    z.progress=data[i].progress;
                    g_download_progress_array.push(z);

                    if (z.status==1) g_download_task_finished.push(z.id);
                }
            }
	    function refreshDownloadList(callback) {

                StageWebViewBridge.call('getDownloadedList', function(data) {
                    if (data==null) data=[];
                    setupDownloadListData(data);
                    if ((typeof callback) == 'function') {
                        callback();
                    }
                });
            }


            function download_make_video_list(data) {

                g_download_task_all=[];
                g_download_task_finished=[];
                g_download_progress_array=[];

                len = data.length;

                $(".download_item").unbind("click");

                html_content = '<div id="download_list" style="margin-left:auto; margin-right:auto;;height:97%; width:97%; border: solid 0px red;">';

                for (i=0; i<len; i++) {
                    id = data[i].id;
                    g_download_task_all.push(id);

                    cell = '<div id = "item_'+id+'" style="position:relative; margin: 10px 5px; float:left; width: 18%; height: 48%; border: solid 0px red;">'+
                           '<div style="width:100%;height:90%;background:url(DATA/images/cover/' + id + '.png) no-repeat center center;background-size: contain"/>' +
                           '<span style="position:absolute;left:0; bottom:0px; width:100%; height: 10%">' + 
                           '<div class="download_item" data-id="'+id+'" data-action="play" style="background-image:url(images/downloads/play_normal.png);"></div>' +
                           '<div class="download_item" data-id="'+id+'" data-action="pause" style="background-image:url(images/downloads/pause_normal.png);"></div>' +
                           '<div class="download_item" data-id="'+id+'" data-action="resume" style="background-image:url(images/downloads/resume_normal.png);"></div>'+
                           '<div class="download_item" data-id="'+id+'" data-action="delete" style="background-image:url(images/downloads/delete_normal.png);"></div>' +
                           '</span>' +
                           '<div class="progress" id="progress_'+ id + '"></div>' + 
                           '</div>';

                    html_content+=cell;
                
                    var z=new Object;
                    z.id=data[i].id;
                    z.status=data[i].status;
                    z.progress=data[i].progress;
                    g_download_progress_array.push(z);

                    if (z.status==1) g_download_task_finished.push(z.id);
                }
                html_content +="</div>";

                $("#downloadlist").html(html_content);


                $(".download_item").click(function() {

                    var $id = $(this).data("id");
                    switch ($(this).data("action")) {
                        case "play": onPlay($id); break;
                        case "pause": onPause($id); break;
                        case "resume": onResume($id); break;
                        case "delete": onDelete($id); break;

                    }
                });;


                $.each(g_download_progress_array, function(key, value) {

                    switch(value.status) {
                        case 0:  //STATUS_DOWNLOADING
                            
                            $('#item_'+value.id + ' .progress').show().progressbar({ value: value.progress, background: "#B637E6" });
                            $('#item_'+value.id + ' [data-action="play"]').hide();
                            $('#item_'+value.id + ' [data-action="pause"]').show();
                            $('#item_'+value.id + ' [data-action="resume"]').hide();
                            $('#item_'+value.id + ' [data-action="delete"]').show();
                            break;
                        case 1:  //STATUS_COMPLETE
                            $('#item_'+value.id + ' .progress').hide();
                            $('#item_'+value.id + ' [data-action="play"]').show();
                            $('#item_'+value.id + ' [data-action="pause"]').hide();
                            $('#item_'+value.id + ' [data-action="resume"]').hide();
                            $('#item_'+value.id + ' [data-action="delete"]').show();

                            value.progress=100;
    
                            break;
                        case 2:  //STATUS_PAUSE
                            $('#item_'+value.id + ' .progress').show().progressbar({ value: value.progress, background: "#B637E6" });
                            $('#item_'+value.id + ' [data-action="play"]').hide();
                            $('#item_'+value.id + ' [data-action="pause"]').hide();
                            $('#item_'+value.id + ' [data-action="resume"]').show();
                            $('#item_'+value.id + ' [data-action="delete"]').show();
                            break;
    
                        case 3:  //STATUS_PENDING
                            $('#item_'+value.id + ' .progress').show().progressbar({ value: value.progress, background: "#B637E6" });
                            $('#item_'+value.id + ' [data-action="play"]').hide();
                            $('#item_'+value.id + ' [data-action="pause"]').show();
                            $('#item_'+value.id + ' [data-action="resume"]').hide();
                            $('#item_'+value.id + ' [data-action="delete"]').show();
                            break;
                        case 4:  //STATUS_ERROR
                            $('#item_'+value.id + ' .progress').show().progressbar({ value: value.progress, background: "#B637E6" });
                            $('#item_'+value.id + ' [data-action="play"]').hide();
                            $('#item_'+value.id + ' [data-action="pause"]').hide();
                            $('#item_'+value.id + ' [data-action="resume"]').hide();
                            $('#item_'+value.id + ' [data-action="delete"]').show();
                            break;

                    }
                    if ((current_page=="video")&&(value.id==g_current_video)&&(value.progress==100)) {

                        $("#video_sd_button").hide();
                        $("#video_hd_button").hide();
                        $("#video_download_button").hide();
                        $("#video_downloading_button").hide();
                        $("#video_downloaded_button").show();
                    }

                });
            }

            function onPause(id) {
                StageWebViewBridge.call('pauseDownload', null, id );

                $('#item_'+id + ' [data-action="pause"]').hide();
                $('#item_'+id + ' [data-action="resume"]').show();
/*
                refreshDownloadList(function() {
                    download_layout();
                });
*/
            } 
            function onResume(id) {
                StageWebViewBridge.call('resumeDownload', null, id );
                $('#item_'+id + ' [data-action="pause"]').show();
                $('#item_'+id + ' [data-action="resume"]').hide();
/*
                refreshDownloadList(function() {
                    download_layout();
                });
*/
            } 
            function onDelete(id) {

                $(".ui-dialog-titlebar").css("background-color", "blue").addClass("dialog-header");
            
                $("#dialog-confirm").html('確定要刪除嗎?').dialog({
                    title: '訊息視窗',
                    resizable: false,
                    height:250,
                    width:220,
                    modal: true,
                    position: { my: "center", at: "center", of: window },
                    buttons: {
                        "確定": function() {
                            StageWebViewBridge.call('deleteVideo', null, id );
                            refreshDownloadList(function() {
                                download_layout();
                            });
                            $( this ).dialog( "close" );
                        },
                        "取消": function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });

            }


            function showWait(on_off, h) {
                if (typeof h=="undefined") h=200;

                if (on_off==1) {
            
                    var show;
                    if (!g_webmode && g_use_local_cache) {
                        show = '<img src="http://127.0.0.1:'+ g_serverPort+'/images/animal0020.gif"/> ';
                    } else {
                        show = '<img src="images/animal0020.gif"/><span id="waiting"></span> ';
                    } 

                    $("#dialog-confirm").html(show).dialog({
                        title: '準備中',
                        resizable: false,
                        height:h,
                        width:100,
                        position: { my: "center", at: "center", of: window },
                        modal: true,
                        buttons:[]
                    });
               } else {
                    $("#dialog-confirm").html('').dialog("close");
               }
            }

            function onPlay(id, path) {
                showWait(1);
                var prefix;

                    if (!g_webmode && g_use_local_cache) {
                        prefix = 'http://127.0.0.1:'+ g_serverPort+'/';
                    } else {
                        prefix = CONFIG.SERVER_ROOT;
                    } 

                    var h=$(document).height();
                    var w=$(document).width();
                    var video_h = h-40;
                    var html_content = "<html><head><meta name='viewport' content='width=device-width, user-scalable=no'><script src='"+prefix+"js/jquery.min.js'><\/script><script src='"+prefix+"js/StageWebViewBridge.js'><\/script><script> $(window).load(function(){StageWebViewBridge.call('ready');});function onKeyDown(code) {goBack(); } function goBack(){StageWebViewBridge.call('stopPlay', null, "+ id +");} <\/script></head><body style='background-color:#000;'><img id='back' src='"+prefix +"images/close_box_gray.png' style='position:fixed; right:0; top:0; height:40px; z-Index:1000;' onClick='goBack();'/><video id='vplayer' style='position:absolute;left:0; top:40px; z-Index:999' poster='"+prefix+"images/poster_360p.jpg' controls autoplay preload='yes' width='"+w+"' height='"+video_h+"'> <source src='" + id + ".mp4' type='video/mp4'></video></body></html>";


                           StageWebViewBridge.call('toOriginal', function(data){
                               var url=data;
                               var launch=true;

                               StageWebViewBridge.call('getVersion', function(version) {
                                   var targetVersion = ["1", "0", "5", "0915", "1"];
                                   var current = version.split(".");
                                   if (versionExceed(current, targetVersion)) {
                                       StageWebViewBridge.call('getOs', function(result) {
                                           var temp = result.split(" ");
                                           var targetVersion = [10, 0, 0];
                                           var osPart = temp[2].split(".");

                                           if (temp[0] === "iPhone" && versionExceed(osPart, targetVersion)) {
                                               var device = temp[3].substring(4, 5);

                                               if (device < "4") {
                                                   showWait(1, 210);
                                                   StageWebViewBridge.call('playNative', null, url);
                                               } else {
                                                   StageWebViewBridge.call('file_put_contents', null, "p.html", html_content, launch);
                                               }
                                           } else {
                                               StageWebViewBridge.call('file_put_contents', null, "p.html", html_content, launch);
                                           }
                                       });
                                   } else {
                                       StageWebViewBridge.call('file_put_contents', null, "p.html", html_content, launch);
                                   }
                               });

//                               StageWebViewBridge.call('file_put_contents', null, "p.html", html_content, launch);

//                               var pathArray = url.split( '/' );
//                               pathArray[pathArray.length-1] = "p.html";
//
//                               var newPathname = "";
//                               for (i = 0; i < pathArray.length; i++) {
//                                 newPathname += "/";
//                                 newPathname += pathArray[i];
//                               }

                           }, id );

            } 

            function refreshDownload() {
                $("#downloadlist").html('<div style="position: absolute; top:45%; left:45%; "><h2>載入中</h2></div>');

                refreshDownloadList(function() {
                    download_layout();
                });
            
            }

            function download_onDownloadClicked(data) {
                StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'DATA/images/cover/'+data.id+'.png');
                StageWebViewBridge.call('downloadVideo', null, data );
                refreshDownloadList(function() {
                    download_layout();
                });

//            $('#module').contents().find('html').html(localS);
            }
            function openURL(url) {
                StageWebViewBridge.call('openURL', null, url );
            }
            function saveFileWithDialog(url) {
                StageWebViewBridge.call('saveFileWithDialog', null, url );
            }

            function doDownload() {
                if (!download_canDownload()) { 
                    $("#dialog-confirm").html('').dialog({
                        title: '已達到下載上限(10支影片)',
                        resizable: false,
                        height:140,
                        width:300,
                        position: {  at: "right bottom", of: window },
                        modal: true,
                        buttons: {
                            "OK": function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    });
                    return;
                }

                var video_data_sd = CONFIG.SERVER_ROOT + 'DATA/video/360p/'+g_current_video + '.mp4';
                var video_data_hd = CONFIG.SERVER_ROOT + 'DATA/video/720p/'+g_current_video + '.mp4';


                $("#dialog-confirm").html('').dialog({
                    title: '下載品質選擇',
                    resizable: false,
                    height:140,
                    width:250,
position: { my: "left top", at: "left bottom", of: $("#video_download_button") },
                    modal: true,
                    buttons:[
                        {
                            text:"SD", 
                            "id": "btnSD", 
                            click:function(){
                                isDownload();
                                download_onDownloadClicked(g_video_sd);
                                $("#video_download_button").hide(); 
                                $("#video_downloading_button").show();  
                                $.post("ajax/main/writeLog.php", {type:'download', id:g_current_video, user: g_user});
                                $( this ).dialog( "close" );
                            } 
                        }, 
                        {
                            text:"HD", 
                            "id": "btnHD", 
                            click:function(){
                                isDownload();
                                download_onDownloadClicked(g_video_hd);
                                $("#video_download_button").hide(); 
                                $("#video_downloading_button").show();  
                                $.post("ajax/main/writeLog.php", {type:'download', id:g_current_video, user: g_user});
                                $( this ).dialog( "close" );
                            } 
                        }
                    ]
                });

                sd_mb = Math.floor(g_video_sd.filesize/1048576);
                hd_mb = Math.floor(g_video_hd.filesize/1048576);

                $("#btnSD").html('<span class="ui-button-text">'+ '一般版(SD)('+sd_mb +'MB)</span>');
                $("#btnHD").html('<span class="ui-button-text">'+ '高清版(HD)('+hd_mb +'MB)</span>');

            }
            function download_canDownload() {
                return (g_download_task_all.length<10);
            }

            function download_ifDownloaded(id) {
                return ($.inArray(id, g_download_task_all));
            }
            function download_checkVideoDownloadStatus(id) {

                if ($.inArray(id, g_download_task_finished)!=-1) return 1;  //download complete
                if ($.inArray(id, g_download_task_all)!=-1) return 2;  // downloading 
                return 0; //Not in download task
            }
            function isDownload() {
                $('#video_metadata').append('<img src="images/downloads.png" style="position:absolute; width:31px; height:31px; right:30%; top:5px" />');
            }

            function onAboutClicked() {

//                if (!g_webmode) StageWebViewBridge.call('toggleFullScreen');
                if ((typeof StageWebViewBridge)== "undefined") return;

                var version_label;
                if ((typeof g_appVersion)== "undefined") {
                    StageWebViewBridge.call('getVersion', function(data) {
                        g_appVersion = data;
                        $(".version_label").text(data);
                    });
                    version_label = "取得中...";
                } else {
                    version_label = g_appVersion;
                }    


                new Messi('程式版本：<span class="version_label">'+version_label+'</span><br>使用者：'+g_user, {
                    title: '關於',
                    modal: true,
                    width: '300px',
/*                    autoclose: 5000, */
                    center: false,
                    viewport: {top:'30px', left:'100px'},
                    buttons: [{id: 0, label: '確定', val: 'Y'}],
                });  
    
            }
            /************************* Hiring Layout *****************************/
            function hiring_layout() {

                $("#hiring_info").css("background", "url(DATA/images/hiring/1_info.png) no-repeat center center");
                $("#hiring_info").css("background-size", "contain");
            }
            function hiring_make_video_list() {
                var imgs1 = [];
//              var video_array = [1,2];
                var video_array = [1];
                $.each(video_array, function(key, id){
                   var item;
                   if (!g_webmode && g_use_local_cache) {
                       item = {id: id, url: 'http://127.0.0.1:'+g_serverPort +'/DATA/images/hiring/cover/'+id+'.png'};
                   } else {
                       item = {id: id, url: 'DATA/images/hiring/cover/'+id+'.png'};
                   }
                   imgs1.push(item);
                });

                $("#hiring_container").remove();
                $("#hiring_content").append("<div id='hiring_container'></div>");

                $("#hiring_container").html( MLayout ( {
                    container_width: $("#hiring_container").width(),
                    container_height: $("#hiring_container").height(),
                    row: 1,
                    column: 0,
                    item_width: 177,
                    item_height: 250,
                    click_callback: "onHiringCoverClicked",
                    items: imgs1
                })).flexslider({
                    animation: "slide",
                    animationLoop: false,
                    touch: true,
                    useCSS: true,
                    slideshow: false,
                    controlNav: true,
                    multipleKeyboard: false,
                    directionNav: true
                 });
            }


            function onHiringCoverClicked(id) {
                onMenuClicked("hiring_detail", "hiring_detail", id);
            }


	    /************************* Tag Layout *****************************/
	    function tag_layout(id) {

		$("#tag_info").css("background", "url(DATA/images/tag/"+id+"_info.png) no-repeat center center");
		$("#tag_info").css("background-size", "contain");
	    }
	    function tag_make_video_list(video_array) {
		var imgs1 = [];
		$.each(video_array, function(key, id){
		   var item;
                   if (!g_webmode && g_use_local_cache) {
		       item = {id: id, url: 'http://127.0.0.1:'+g_serverPort +'/DATA/images/cover/'+id+'.png'};
		   } else {
		       item = {id: id, url: 'DATA/images/cover/'+id+'.png'};
		   }
		   imgs1.push(item);
		});

                $("#tag_container").remove();
                $("#tag_content").append("<div id='tag_container'></div>");

		$("#tag_container").html( MLayout ( {
		    container_width: $("#tag_container").width(),
		    container_height: $("#tag_container").height(),
		    row: 1,
		    column: 0,
		    item_width: 177,
		    item_height: 250,
		    click_callback: "onCoverClicked",
		    items: imgs1
		})).flexslider({
		    animation: "slide",
		    animationLoop: false,
		    touch: true,
		    useCSS: true,
		    slideshow: false,
		    controlNav: true,
		    multipleKeyboard: false, 
		    directionNav: true
		 });
	    }

            /************************* Hiring Detail Layout *****************************/
            function hiring_detail_layout(id) {

                if (id==1) {
                    title="【房仲業銷售業務員】增員資料夾";
                    pdate="104年4月2日";
                    detail="【房仲銷售業務員】增員資料夾，提供您增員房仲業務員時的輔助展示資料。使用手冊提供增員資料夾簡報檔內容解析以及示範問句，讓您了解如何於增員現場使用增員資料夾切進工作議題，並附錄房仲業五大理想工作要件分析作為參考資料。";
                    //video_list = 2;
                    video_list = -1;
                    book1 = 1;
                    book2 = 2;
                    book3 = 3;
                    book4 = 4;
                } else {
                    title="【汽車銷售業務員】增員資料夾";
                    pdate="103年3月17日";
                    detail="這是你一旦想要增員汽車銷售業務員時，一定要看的秘籍大整理！";
                    video_list = 1;
                    book1 = 4;
                    book2 = 5;
                    book3 = 4;
                }

                var metadata = "<img src='DATA/images/hiring/cover/" + id + ".png' style=' margin-top: 10px;'/> &nbsp;<br/> <br/><p>" + title + "</span></p><br/><p>檔案>更新日期：" + pdate + "</p> <p>檔案簡介：</p><p> " + detail + "</p>";

                 $("#hiring_detail_metadata").html(metadata);
                 //var img_url = 'DATA/images/hiring/cover150/' +video_list + '.png';
                 var img_url = '';

//               $("#hiring_relate_list_container").html("<img src='" + img_url + "'  onClick='onHiringCoverClicked("+video_list+ ")' />");

                 $("#hiring_detail_container").html('<div style="position:absolute; left:0; right:0; top:0; bottom:0; background-image:url(DATA/images/hiring/content/' + id + '.jpg?1); background-size:contain; background-repeat: no-repeat; background-position:center center"></div>');

/*
                 var buttons = '<li><img id="button1" src="DATA/images/hiring/button/increase_banner_book_quick_normal.png" onclick="openURL(\'http://fubon.moker.com.tw/ajax/main/file.php?id=' + book1 + '&sid=<?php echo session_id(); ?>\');" /> </li>' +
                    '<li><img id="button2" src="DATA/images/hiring/button/increase_banner_book_full_normal.png" onclick="openURL(\'http://fubon.moker.com.tw/ajax/main/file.php?id=' + book2 + '&sid=<?php echo session_id(); ?>\');" /> </li>' +
                    '<li><img id="button3" src="DATA/images/hiring/button/increase_banner_file_ios_normal.png" onclick="openURL(\'http://fubon.moker.com.tw/ajax/main/file.php?id=' + book3 + '&sid=<?php echo session_id(); ?>\');" /> </li>' +
                    '<li><img id="button4" src="DATA/images/hiring/button/increase_banner_file_android_normal.png" onclick="openURL(\'http://fubon.moker.com.tw/ajax/main/file.php?id=' + book4 + '&sid=<?php echo session_id(); ?>\');" /> </li>';
*/
<?php if ($iPad) { ?>
                 var buttons = '<li><img id="button1" src="DATA/images/hiring/button/increase_banner_book_normal.png" onclick="openURL(\'https://fubonevideo.moker.com/download.php?id=' + book2 + '&sid=<?php echo session_id(); ?>\');" /> </li>' +
                    '<li><img id="button4" src="DATA/images/hiring/button/increase_banner_file_normal.png" onclick="openURL(\'https://fubonevideo.moker.com/download.php?id=' + book4 + '&sid=<?php echo session_id(); ?>\');" /> </li>';

<?php } else { ?>
                 var buttons = '<li><img id="button1" src="DATA/images/hiring/button/increase_banner_book_normal.png" onclick="openURL(\'https://fubonevideo.moker.com/ajax/main/file.php?id=' + book2 + '&sid=<?php echo session_id(); ?>\');" /> </li>' +
                    '<li><img id="button4" src="DATA/images/hiring/button/increase_banner_file_normal.png" onclick="openURL(\'https://fubonevideo.moker.com/ajax/main/file.php?id=' + book4 + '&sid=<?php echo session_id(); ?>\');" /> </li>';

<?php } ?>
                 $("#hiring_buttons").html("<ul>" + buttons + "</ul>");
            }

	    /************************* Search Layout *****************************/
	    function video_layout(id) {
		 $("#video_metadata").html("");
		 $("#video_relate_list_container").html("");
		 $("#video_container").html("");
		 $.ajax({
		     url: CONFIG.SERVER_ROOT+"video/"+id+"?r="+g_rank+"&u="+g_unitcode+"&i="+g_user, 
		     beforeSend: function() {
			 $("#video_container").html('<img class="loading" src="images/loading.gif" />');
		     },
		     type: 'post',
		     dataType: 'jsonp', 
                     crossDomain: true, 
		     success: function (data) { 

			 if (data.result == "success") {
			      //var metadata_html = "<img src='DATA/images/cover/"+data.id+".png' style=' margin-top: 10px;'/> &nbsp;<br/> <br/>";

       		             if (!g_webmode&&g_use_local_cache) {
	          	         cover = 'http://127.0.0.1:'+g_serverPort+'/DATA/images/cover/'+id+'.png';
          		     } else {
          		         cover = 'DATA/images/cover/'+id+'.png';
          		     }
			      //var metadata_html = "<img src='http://127.0.0.1:"+g_serverPort+"/DATA/images/cover/"+data.id+".png' style=' margin-top: 10px;'/> &nbsp;<br/> <br/>";
                              var metadata_html = '<img id="return_btn" onClick="onReturnClicked();" src="images/back.png" style="position:absolute;left:10px;height:35px;top:5px;cursor:pointer"/>';
			      metadata_html += "<img src='"+cover+"' style=' margin-top: 10px;'/> &nbsp;<br/> <br/>";
			      metadata_html += data.metadata1+data.metadata2;

			     $.each(data.books, function(key, value) {
				 metadata_html += '<img src="'+value.img+'" style="width:107px ; cursor:pointer;" onclick="$.post(\'ajax/main/writeLog.php\', {type:\'book\', id:'+value.id+', user: g_user}); openURL(\'' + value.url+'&m=1\');" />';
			     });
     
			     $("#video_metadata").html(metadata_html);

                             if (download_ifDownloaded(id)>=0) isDownload();

			     if ($vid_obj!=null) {
				 $vid_obj.dispose();
				 $vid_obj=null;
			     }
       		             if (!g_webmode && g_use_local_cache) {
	          	         poster = 'http://127.0.0.1:'+g_serverPort+'/images/poster_360p.jpg';
          		     } else {
          		         poster = CONFIG.SERVER_ROOT+'images/poster_360p.jpg';
          		     }
			     //var video_html = ' <video id="video_player" class="video-js vjs-default-skin" style="-webkit-transform-style: preserve-3d; z-Index:0;" poster="http://fubon.moker.com.tw/images/poster_360p.jpg" controls autoplay preload="auto" width="100%" height="100%"> <source src="' +data.video_sd.url +'" type="video/mp4"> <p class="vjs-no-js"></p> </video> ';
			     //var video_html = ' <video id="video_player" poster="http://fubon.moker.com.tw/images/poster_360p.jpg" controls autoplay preload="auto" width="100%" height="100%"> <source src="' +data.video_sd.url +'" type="video/mp4"> <p class="vjs-no-js"></p> </video> ';
			     var video_html = ' <video id="video_player" poster="'+poster+'" controls autoplay preload="auto" width="100%" height="100%"> <source src="' +data.video_sd.url +'" type="video/mp4"> <p class="vjs-no-js"></p> </video> ';
                 $("#video_container").html(video_html);

	     //                $vid_obj = _V_("video_player");
			     g_video_sd = data.video_sd;
			     g_video_hd = data.video_hd;

	      //               $("#video_sd_button").trigger("click");

/*
			     vjsOptions={poster:'/images/poster_360p.jpg', nativeControlsForTouch: true};
			     $vid_obj =  videojs('video_player', vjsOptions, function() {
				 var videoJSPlayer = this;
				 videoJSPlayer.ready(function() {
				     videoJSPlayer.poster(vjsOptions.poster);
				 });
			     });
*/
			     video_make_video_list(data.related_list);
                 $(document).ready(function() {
                     setTimeout(function(){
                         checkDevice(data.video_sd.url);
                     }, 1000);
                 });
			 }
		     }
		 });
                 video_setupDownloadButtons(id);
	    }

        function checkDevice(url) {
            StageWebViewBridge.call('getOs', function(result) {
                var temp = result.split(" ");
                var targetVersion = [10, 0, 0];
                var osPart = temp[2].split(".");
                if (temp[0] === "iPhone" && versionExceed(osPart, targetVersion)) {
                    var device = temp[3].substring(4, 5);

                    if (device < "4") {
                        if (!g_webmode && g_use_local_cache) {
                            imgSrc = 'http://127.0.0.1:'+g_serverPort+'/images/static_player.png';
                        } else {
                            imgSrc = CONFIG.SERVER_ROOT+'images/static_player.png';
                        }
                        $("#video_container").html('<img id="img_player" src="'+imgSrc+'" width="100%" style="margin-top: 67px;" >');
                        $("#img_player").click(function() {
                            showWait(1, 210);
                            StageWebViewBridge.call('playNative', null, url);
                        });

//                        if ($("#img_player").attr('src') == undefined) {
//                            setTimeout(function(){
//                                checkDevice(url);
//                            }, 1500);
//                        }
                    }
                }
            });
        }

        function versionExceed(v1parts, v2parts) {
            while (v1parts.length < v2parts.length) v1parts.push("0");
            while (v2parts.length < v1parts.length) v2parts.push("0");

            for (var i = 0;i < v1parts.length;++ i) {
                if (v2parts.length == i) {
                    return true;
                }

                if (v1parts[i] == v2parts[i]) {
                    continue;
                } else {
                    return v1parts[i] > v2parts[i];
                }
            }
        }

	    function video_make_video_list(video_array) {
		var imgs1 = [];
		$.each(video_array, function(key, id){
		   if (!g_webmode && g_use_local_cache) {
		       item = {id: id, url: 'http://127.0.0.1:'+g_serverPort+'/DATA/images/cover150/'+id+'.png'};
		    } else {
		       item = {id: id, url: 'DATA/images/cover150/'+id+'.png'};
		    }

		   imgs1.push(item);
		});
		$("#video_relate_list_container").html("<div id='video_relate_list' class='flexslider'></div>");

		$("#video_relate_list").html( MLayout ( {
		    container_width: $("#video_relate_list").width(),
		    container_height: $("#video_relate_list").height(),
		    row: 1,
		    column: 0,
		    item_width: 120,
		    item_height: 170,
		    click_callback: "onCoverClicked",
		    items: imgs1
		})).flexslider({
		    animation: "slide",
		    animationLoop: false,
		    touch: true,
		    useCSS: true,
		    slideshow: false,
		    controlNav: true,
		    multipleKeyboard: false, 
		    directionNav: true
		 });
	    }

	    function video_loadVideo(type) {
		 var url = (type=="video_sd")?g_video_sd.url:g_video_hd.url;
		 $("#video_player").attr("src", url);
    //             $(".vjs-big-play-button").show();
		 $("#video_player").removeClass("vjs-playing").addClass("vjs-paused");
		// load the new sources
//		 $vid_obj.load();
//		 $("#div_video_html5_api").show();

	    }

            function video_setupDownloadButtons(id) {
                if (typeof(download_checkVideoDownloadStatus) == "function") {
                    code = download_checkVideoDownloadStatus(id);
                    switch (code) {
                        case 2:    //downloading
                            $("#video_sd_button").show();
                            $("#video_hd_button").show();
                            $("#video_download_button").hide();
                            $("#video_downloading_button").show();
                            $("#video_downloaded_button").hide();
                            break;
                        case 1:    //downloaded
                            $("#video_sd_button").show();
                            $("#video_hd_button").show();
                            $("#video_download_button").hide();
                            $("#video_downloading_button").hide();
                            $("#video_downloaded_button").show();
                            break;
                        case 0:    //not in download task
                            $("#video_sd_button").show();
                            $("#video_hd_button").show();
                            $("#video_download_button").show();
                            $("#video_downloading_button").hide();
                            $("#video_downloaded_button").hide();
                            break;
                    }
                }

        }

	    /************************* Search Layout *****************************/
	    function search_layout(query) {
		 $("#search_term").text(query);
		 $.ajax({
		     url: CONFIG.SERVER_ROOT+"search/"+query, 
		     beforeSend: function() {
			 $("#search_container").html('<img class="loading" src="images/loading.gif" />');
		     },
		     type: 'post',
		     dataType: 'jsonp', 
                     crossDomain: true, 
		     success: function (data) { 
			 if (data.result == "success") {
			     $("#search_hit").text(data.list.length);
			     search_make_video_list(data.list);
			 }
		     }
		 });
	    }

	    function search_make_video_list(video_array) {
		var w=$("#search_container").width();
		var h=$("#search_container").height();            

		var imgs1 = [];
		$.each(video_array, function(key, id){
		   var item;
		   if (!g_webmode && g_use_local_cache) {
		       item = {id: id, url: 'http://127.0.0.1:'+g_serverPort+'/DATA/images/cover/'+id+'.png'};
		    } else {
		       item = {id: id, url: 'DATA/images/cover/'+id+'.png'};
		    }
		   imgs1.push(item);
		});
		$("#search_container").remove();
		$("#search_content").append("<div id='search_container'></div>");

		$("#search_container").html( MLayout ( {
		    container_width: w,
		    container_height: h,
		    row: 2,
		    column: 5,
		    click_callback: 'onCoverClicked',
		    items: imgs1
		})).flexslider({
		    animation: "slide",
		    slideshow: false,
		    touch: true,
		    animationLoop: false,
		    useCSS: true,
		    itemHeight: h,
		    itemWidth: w,
		    controlNav: true,
		    directionNav: false,
		});
	    }

	    /************************* Category Layout *****************************/
	    function category_layout(id) {



		 $.ajax({
		     url: CONFIG.SERVER_ROOT+"category/"+id, 
		     beforeSend: function() {
			 $("#category_container").html('<img class="loading" src="images/loading.gif" />');
		     },
		     type: 'post',
		     dataType: 'jsonp', 
                     crossDomain: true, 
		     success: function (data) { 
			 if (data.result == "success") {
			     video_list_0 = data.list.order_0;
			     video_list_1 = data.list.order_1;
			     category_make_video_list(0);
			 }
		     }
		 });
	    }

	    function category_make_video_list(list) {
			 $("#category_container").html('<img class="loading" src="images/loading.gif" />');
                if (list===0) {
                    $("#sort_hot").attr("src", "images/index/index_icon01_press.png");
                    $("#sort_date").attr("src", "images/index/index_icon02_normal.png");
                } else if (list ===1) {
                    $("#sort_hot").attr("src", "images/index/index_icon01_normal.png");
                    $("#sort_date").attr("src", "images/index/index_icon02_press.png");
                } 

		var video_array = (list==0)? video_list_0:video_list_1;
		var nVideos=video_array.length;
		var h = $("#category_container").height();
		var w = $("#category_container").width();
		var imgs1 = [];
		$.each(video_array, function(key, id){
                   //StageWebViewBridge.call('updateCache', null, 'http://fubon.moker.com.tw/DATA/images/cover/'+id+'.png');
                   var item;
                   if (!g_webmode && g_use_local_cache) {
		       item = {id: id, url: 'http://127.0.0.1:'+g_serverPort+'/DATA/images/cover/'+id+'.png'};
                   } else {
		       item = {id: id, url: 'DATA/images/cover/'+id+'.png'};
                   }
		   //var item = {id: id, url: 'http://127.0.0.1:'+g_serverPort +'/DATA/images/cover/'+id+'.png'};
		   imgs1.push(item);
		});

		html=MLayout ( {
		    container_width: $("#category_container").width(),
		    container_height: $("#category_container").height(),
		    row: 2,
		    column: 5,
		    click_callback: 'onCoverClicked',
		    items: imgs1
		});

		$("#category_container").remove();
		$("#category_content").append("<div id='category_container'></div>");

		$("#category_container").html(html).flexslider({
		    animation: "slide",
		    slideshow: false,
		    touch: true,
		    animationLoop: false,
		    useCSS: true,
		    itemHeight: h,
		    itemWidth: w,
		    controlNav: true,
		    directionNav: true
		});
	    }

	    /************************* Home Layout *****************************/
	    function home_layout() {
		home_makeBanner();
                home_prepareHotArea();
	    }

	    function home_makeBanner() {
                home_processBanner(g_banner_list);
	    }
		 
	    function home_processBanner(items) {

		var h = $("#home_banners").height();
		var w = $("#home_banners").width();
		//var w = h*16/9;

		var html ='<div id="home_banner_slider" style="height:'+h+'px; width:'+w+'px; border: solid 0px blue;" class="flexslider"> <ul class="slides">';
	 
		$.each(items, function(key, val) {
                    var bg;
                    if (!g_webmode && g_use_local_cache) {
                        bg = 'http://127.0.0.1:'+g_serverPort+'/DATA/images/banner/'+val.id+'.jpg';
                    } else {
                        bg = '/DATA/images/banner/'+val.id+'.jpg';
                    }
		    html += '<li data-video="'+val.hasVideo+'" data-id="'+val.id+'"><div style="border: solid 0px red; height:'+h+'px; width:'+w+'px; background-image:url(/DATA/images/banner/'+val.id+'.jpg); background-repeat: no-repeat; background-position: center center; background-size:100% 100% ; "> </div></li>';
		});
		html += '</ul></div>';

		html += '<div class="play_button" ></div>';

		$("#home_banners").html(html);

		$('#home_banner_slider').flexslider({
		    animation: "slide",
		    touch: true,
		    animationLoop: true,
		    useCSS: false,
		    smoothHeight: true,
/*
		    itemHeight: h,
		    itemWidth: w,
*/
		    before: function(slider){
			home_hidePlayButton();
		    },
		    start: function(slider){
			var current = slider.slides[slider.currentSlide];

			if ($(current).data("video") == true) { 
			    home_showPlayButton($(current).data("id"));
			}
		    },
		    after: function(slider){
			var current = slider.slides[slider.currentSlide];

			if ($(current).data("video") == true) { 
			    home_showPlayButton($(current).data("id"));
			}
		    },
		    controlNav: true,
		    multipleKeyboard: false, 
		    directionNav: false,
		    itemMargin: 5
		});
	    }

	    function home_prepareHotArea(){

                home_make_video_list($("#new_area_video_list"), g_hot1_list);
                home_make_video_list($("#hot_area_video_list"), g_hot2_list);
	     }

	     function home_make_video_list($target, list) {

		var imgs1 = [];
		$.each(list, function(key, id){
                   var item;
                   if (!g_webmode && g_use_local_cache) {
		       item = {id: id, url: 'http://127.0.0.1:'+g_serverPort +'/DATA/images/cover150/'+id+'.png'};
                   } else {
		       item = {id: id, url: 'DATA/images/cover150/'+id+'.png'};
                   }
		   imgs1.push(item);
		});

		$target.html( MLayout ( {
		    container_width: $target.width(),
		    container_height: $target.height(),
		    row: 1,
		    column: 0,
		    item_width: 120,
		    item_height: 170,
		    click_callback: 'onCoverClicked',
		    items: imgs1
		})).flexslider({
		    animation: "slide",
		    animationLoop: false,
		    touch: true,
		    useCSS: true,
		    slideshow: false,
		    before: function(slider){
			 var $b = $("#new_area_video_list");
			 var $dir_left = ($target.is($b)) ? $("#new_prev"):$("#hot_prev");
			 var $dir_right = ($target.is($b)) ? $("#new_next"):$("#hot_next");

			 $dir_left.css("visibility", (slider.animatingTo==0) ? "hidden" : "visible");
			 $dir_right.css("visibility", (slider.animatingTo==slider.count-1) ? "hidden" : "visible");
		    },
		    controlNav: false,
		    multipleKeyboard: false, 
		    directionNav: false
		});

	    }

	    function home_hidePlayButton(id) {
		$(".play_button").css("display", "none");
                $(".play_button").off("click");
	    }
	    function home_showPlayButton(id) {
		if (id >0) {
		    $(".play_button").css("display", "block");
		    $(".play_button").click(function () {

                StageWebViewBridge.call('getVersion', function(version) {
                    var targetVersion = ["1", "0", "5", "0915", "1"];
                    var current = version.split(".");
                    if (versionExceed(current, targetVersion)) {
                        StageWebViewBridge.call('getOs', function(result) {
                            var temp = result.split(" ");
                            var targetVersion = [10, 0, 0];
                            var osPart = temp[2].split(".");

                            if (temp[0] === "iPhone" && versionExceed(osPart, targetVersion)) {
                                var device = temp[3].substring(4, 5);

                                if (device < "4") {
                                    showWait(1, 210);
                                    StageWebViewBridge.call('playNative', null, "https://fubonevideo.moker.com/DATA/video/banner/"+id+".mp4");
                                } else {
                                    play_banner(id);
                                }
                            } else {
                                play_banner(id);
                            }
                        });
                    } else {
                        play_banner(id);
                    }
                });



                });
            }
        }

        function play_banner(id) {
            $("#home_banner_slider").flexslider("pause");

            $('#viewer').append('<video id="vplayer" style="position:absolute;" poster="'+CONFIG.SERVER_ROOT+'images/poster_360p.jpg" controls autoplay preload="yes" width="95%" height="95%"> <source src="/DATA/video/banner/'+id+'.mp4" type="video/mp4"></video>');

            $("#viewer").lightbox_me({
                closeClick: true,
                closeEsc: false,
                centered: true,
                overlaySpeed: 50,
                overlayCSS: {background: 'black', opacity: .5},
                onLoad: function() {
                    $("#vplayer").get(0).play();
                }
            });
        }

        function home_onPlay(id) {

        }
        function setAutoPlay(s) {
            switch(s) {
                case true:
                    $('.coverflow').roundabout("startAutoplay");
                    break;
                case false:
                    $('.coverflow').roundabout("stopAutoplay");
                    break;
            }
        }
        
        function onCoverClicked(id) {
            onMenuClicked("video", "video", id);
        }

        function onReturnClicked() {
            if (current_page=="video") {
                onMenuClicked(previous_page, "return");
            }    
        }
        
        function onWindowSize() {
                
            w = $(window).width();
            h = $(window).height();
                                

            if (w<800) w=800;
            if (h<550) h=550;

            $('#wrapper').width(w);
            $('#wrapper').height(h);
        }

        function onLightboxClose() {
            $("#viewer").trigger("close");

            //if (!home_player.paused()) home_player.dispose();
//            home_player.dispose();

            $("#vplayer").get(0).pause();
            $("#vplayer").remove();
            $(".lb_overlay").remove(); 

            if (current_page == "home") {
                $("#home_banner_slider").flexslider("play");
            }
        }

        function onMenuClicked (area, type, target) {
            
            var selection;
            $.each(buttonArray, function(key, val) {
    
                if (val.id == area) {
                    selection = val;
                    $('#'+val.id).attr('src', val.press);
                } else {
                     $('#'+val.id).attr('src', val.normal);
                }
            
            });

            if (current_page == "video") {
                $("#video_container").html("");
            }

            $("#home").css("display", "none");
            $("#category_content").css("display", "none");
            $("#tag_content").css("display", "none");
            $("#video_content").css("display", "none");
            $("#hiring_detail_content").css("display", "none");
            $("#search_content").css("display", "none");
            $("#hiring_content").css("display", "none");
            $("#download_content").css("display", "none");

            switch (type) {
                case 'home':
//location.reload();
//break;
                    $("#home").css("display", "block");

                    break;
                case 'category':

                    $("#category_content").css("display", "block");
			     //video_list_0 = g_category_list[selection.index].order_0;
			     //video_list_1 = g_category_list[selection.index].order_1;
			     video_list_0 = g_category_list[target].order_0;
			     video_list_1 = g_category_list[target].order_1;
			     category_make_video_list(0);

//                    category_layout(target);
                    break;
                case 'tag':

                    $("#tag_content").css("display", "block");
		    tag_make_video_list(g_tag_list[target].list);
                    tag_layout(g_tag_list[target].id);
                    break;
                case 'hiring':
                    $("#hiring_content").css("display", "block");
//                  tag_make_video_list(g_tag_list[target].list);
                    hiring_layout();
                    hiring_make_video_list();
                    break;
                case 'hiring_detail':
                    hiring_detail_layout(target);
                    $("#hiring_detail_content").css("display", "block");
                    break;
                case 'search':
                    search_layout(target);
                    $("#search_content").css("display", "block");
                    break;
                case 'video':
                    video_layout(target);
                    g_current_video = target;
                    $("#video_content").css("display", "block");
                    break;
                case 'download':
                    //download_layout(target);
                    $("#download_content").css("display", "block");
                    break;
                case 'return':
                    if (area=="home") $("#home").css("display", "block");
                    if (area=="category") $("#category_content").css("display", "block");
                    if (area=="tag") $("#tag_content").css("display", "block");
                    if (area=="search") $("#search_content").css("display", "block");
                    current_page=previous_page;
                    break;
                default:
                    //TweenLite.to($("#home"), 0.5, {top:"1500px", bottom:"-1500px"}); 
                    $("#home").css("display", "block");
             }
            if (current_page!='video') previous_page = current_page;
            if (type!="return") current_page = type;
        }



        /***************** Bridge Functions ******************/

        function readyForDownload(data) {
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_phone_o365_release.html');
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_tablet_o365_release.html');
            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'login_desktop_o365_release.html');
            
            if (data==null) data=[];
            setupDownloadListData(data);
            download_layout();

            StageWebViewBridge.call('serverPort', function(data) {
                g_serverPort = data;
            });


            StageWebViewBridge.call('startDownload'); 

//            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'entry_phone.html');
//            StageWebViewBridge.call('updateCache', null, CONFIG.SERVER_ROOT+'entry_tablet.html');

            StageWebViewBridge.call('setConfig', null, "disk_space_limit", "200000000");
            // StageWebViewBridge.call('getConfig', function(data) { alert(data);}, "data_version");

            StageWebViewBridge.call('getVersion', function(data) {
                g_appVersion = data;

               var link;
		var ua = navigator.userAgent;
		if (ua.match(/iPad/i)) {
                    link='itms-services://?action=download-manifest&url=https://fubonevideo.moker.com/downloads/beta_1.0.3.0806.1.plist';
                    if (g_appVersion<'1.0.3.1006.1') {
                        showForceUpgradeMessage(g_appVersion, '1.0.4.0326.1');
                    } else if (g_appVersion<'1.0.4.0326.1')  {
                        showUpgradeMessage(g_appVersion, '1.0.4.0326.1');
                    }
                } else {
                    link='https://fubonevideo.moker.com/downloads/FubonVideoUAT_1.0.3.0806.1.apk';
                    if (g_appVersion<'1.0.3.0808.1') {
                        showUpgradeMessage(g_appVersion, '1.0.3.0808.1');
                    }
                }


/*
                if (g_appVersion>'1.0.3.0806.1') {
                    link='https://fubonevideo.moker.com/downloads/beta';
                    doUpgrade(g_appVersion, '1.0.3.0806.1', link);
                } else if (g_appVersion<'1.0.3.0806.1') {
                    if (ua.match(/iPad/i)) {
                        doUpgrade(g_appVersion, '1.0.3.0806.1', link);
                    } else {
                        doUpgrade2(g_appVersion, '1.0.3.0806.1', link);
                    }
                }
*/
                
            });

             

            StageWebViewBridge.call('reportLog', function(data) {
                $.post("ajax/main/writeLog.php", {type:'offline', user:g_user, content: JSON.stringify(data)}, function () { });
            });
            if ((typeof StageWebViewBridge)== "undefined") return;
        }

        function showUpgradeMessage(cv, nv) {

                new Messi('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/>請至「行動業務市集」更新。', {
                title: '訊息視窗',
                modal: true,
                width: '300px',
                padding: '10px',
                buttons: [{id: 0, label: "OK", val: 'Y'}],
                callback: function(val) {
                }
            });
        }
        function showForceUpgradeMessage(cv, nv) {

            new Messi('<p style="color:red; font-size:1.2em">富邦新視界有新版本，修正iOS 8.0版問題</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/>請移除本版APP, 並至「行動業務市集」下載新版。', {
                title: '訊息視窗',
                modal: true,
                width: '300px',
                padding: '10px',
                buttons: [{id: 0, label: "OK", val: 'Y'}],
                callback: function(val) {
                }
            });
        }

        function doUpgrade2(cv, nv, download_link) {

            $("#dialog-confirm").attr('title', '訊息視窗').html('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p><br/>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/>您所使用的版本已失效，必須升級到最新版本。').dialog({
                  resizable: false,
                  closeOnEscape: false,
                  open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog || ui).hide(); },
                  height:340,
                  width:600,
                  modal: true,
                  buttons: {
/*
                    "下載新版安裝程式": function() {
                         StageWebViewBridge.call('openURL', null, download_link); 
                      $( this ).dialog( "close" );
                    },
*/
                    "升級到最新版程式": function() {
                         StageWebViewBridge.call('doDownloadFile', null, download_link, 'fv.apk');
                         showWait(1, 220);
                         //$( this ).dialog( "close" );
                    }
                  }
              });
        }

        function downloadProgress(name, progress) {

            $('#waiting').html(progress + "%");
            if (progress>=100) showWait(0);
        }

        function doUpgrade(cv, nv, download_link) {

            $("#dialog-confirm").attr('title', '訊息視窗').html('<p style="color:red; font-size:1.2em">富邦新視界有新版本！</p>您正使用的版本號：'+cv+'<br/>最新版本號：' + nv +'<br/>您所使用的版本已失效，必須升級到最新版本。選擇安裝後，系統會在背景下載新版富邦新視界APP，下載完成後請按照指示安裝').dialog({
                  resizable: false,
                  closeOnEscape: false,
                  open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog || ui).hide(); },
                  height:340,
                  width:600,
                  modal: true,
                  buttons: {
                    "安裝新版程式": function() {
                         StageWebViewBridge.call('openURL', null, download_link); 
                      $( this ).dialog( "close" );
                    }
                  }
              });
        }

        function updateDownloadList(id, progress) {
            if (progress!=100) {
                //$("#progress_"+id).progressbar({ value: progress, background: "#B637E6" });
                $("#item_"+id + " .progress").progressbar({ value: progress, background: "#B637E6" });
            } else {
                refreshDownloadList(function() {
                    download_layout();
                });
            }
        }
        function onKeyDown(code) {

            if (code ==16777238) {

                if (current_page == 'video') {
                    onReturnClicked();
                    return;
                }
                $("#dialog-confirm").html('').dialog({
                    title: '確認離開?',
                    resizable: false,
                    height:140,
                    width:250,
                    position: { my: "center", at: "center", of: window },
                    modal: true,
                    buttons:[
                        {
                            text:"是", 
                            "id": "idYes", 
                            click:function(){
                                $( this ).dialog( "close" );
                                StageWebViewBridge.call('exitApp');
                            } 
                        }, 
                        {
                            text:"否", 
                            "id": "idNo", 
                            click:function(){
                                $( this ).dialog( "close" );
                            } 
                        }
                    ]
                });
            }

        }
        function serverPort(port) {

            g_serverPort = port;
         //   alert(g_serverPort);
        }

        function showMessage(msg_id) {
            switch (msg_id) {
                case 0: // LOW SPACE
                    msg = "磁碟空間不夠，已暫停下載工作";
                    break;
                default:
                    msg = msg_id;
            }

            $("#dialog-confirm").html(msg);
            $("#dialog-confirm").dialog({
                title: '訊息視窗',
                resizable: true,
                height:270,
                width:400,
                modal: true,
                buttons: {
                    "確定": function() {
                      $(this).dialog( "close" );
                    }
                }
            });
        }
        
    </script>

</html>
