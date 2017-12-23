<?php
    include_once("m1_list_inc.php");
    include_once("phone_inc.php");

    $port = (isset($_REQUEST["p"]))?$_REQUEST["p"]:8888;

    $web_mode = ($port==0);

file_put_contents("/tmp/ppp", $USER_ID."-1\n", FILE_APPEND);
if (($USER_ID=="guest") && (isset($_REQUEST['i']))) {
    $USER_ID = trim($_REQUEST['i']);
    $USER_ID = strtoupper($USER_ID);
    $_SESSION['user_id'] = $USER_ID;
}
file_put_contents("/tmp/ppp", $USER_ID."-2\n", FILE_APPEND);


$test_user = array('SUPER', 'F223112412', 'U220171224', 'B221680066', 'N122352461', 'N222753880', 'H120226044', 'K221999193', 'F223848891', 'A129365125');
$test_mode = in_array($USER_ID , $test_user)? 'true' : 'false';
$test_mode = (1===1);

file_put_contents("/tmp/jjjj", $USER_ID." $test_mode\n", FILE_APPEND);

$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$iOS = ($iPod || $iPhone || $iPad) ? 'true' : 'false';


    $w=300;
    $h=0;

    if (isset($_REQUEST['w'])) $w = $_REQUEST['w'];
    if (isset($_REQUEST['h'])) $h = $_REQUEST['h'];

    $banner_w = $w;
    $banner_h = floor($w/1.77);

    $banners = getResourceList("banner");
    $banner_html = "";
    $banner1_html = "";

    $first_banner = '<img src="DATA/images/banner/' . $banners[0]["id"] . '.jpg" style="width:'.$banner_w.'px; height:'.$banner_h.'px"/>'."\n";
    $i=0;

    foreach ($banners as $item) {
        $tag = ($i == $i )? "src":"data-src";
 
        //$banner_html .= '<li data-video="' . $item["hasVideo"].  '" data-id="' . $item["id"]. '" style="list-style-type:none; width:'.$banner_w.'px; height:'.$banner_h.'px;"  >'."\n";
        //$banner1_html .= '<li class="bItem" data-video="' . $item["hasVideo"].  '" data-id="' . $item["id"]. '" style="list-style-type:none; width:'.$banner_w.'px; height:'.$banner_h.'px;"  >'."\n";
        $banner_html .= '<li data-video="' . $item["hasVideo"].  '" data-id="' . $item["id"]. '" style="list-style-type:none; width:100%;"  >'."\n";
        $banner1_html .= '<li class="bItem" data-video="' . $item["hasVideo"].  '" data-id="' . $item["id"]. '" style="list-style-type:none; width:100%;"  >'."\n";

        //echo '<div style="background-image:url(DATA/images/banner/' . $item["id"] . '.jpg); height:200px; width: background-repeat: no-repeat; background-position: center center; background-size:100% 100% ; "> </div>';

        //$banner_html .= '<div class="banner_item" style="background-image:url(DATA/images/banner250/' . $item["id"] . '.jpg); background-repeat: no-repeat; background-position: center center; background-size:contain ; "> </div>'."\n";

        $banner_path = ($w<400)? "DATA/images/banner250/":"DATA/images/banner/";

        $banner_html .= '<img class="banner_item" '.$tag.'="DATA/images/banner/' . $item["id"] . '.jpg" style="width:'.$banner_w.'px; height:'.$banner_h.'px"/>'."\n";
        //$banner1_html .= '<img class="banner_item" '.$tag.'="'.$banner_path . $item["id"] . '.jpg" style="width:'.$banner_w.'px; height:'.$banner_h.'px"/>'."\n";
        $banner1_html .= '<img class="banner_item" '.$tag.'="'.$banner_path . $item["id"] . '.jpg" style="width:100%"/>'."\n";
        $banner_html .= "</li>\n";
        $banner1_html .= "</li>\n";
//break;
        $i++;
    }

    $categories = getResourceList("category");
    $category_html = "";
    $category_array = array();

    $i = 0;
    foreach ($categories as $item) {
        $img = ($i==0)?$item["press"]:$item['normal'];
        $category_html .= "<img class='category_button' data-id='".$item["id"]."' data-normal='".$item["normal"]."' data-press='".$item["press"]."' src='". $img ."' />";
        $i++;
    }


    $tags = getResourceList("tag");
    $tag_html= "";
    $i = 0;
    foreach ($tags as $item) {
        $img = ($i==0)?$item["press"]:$item['normal'];
        $tag_html .= "<img class='tag_button' data-id='".$item["id"]."' data-normal='".$item["normal"]."' data-press='".$item["press"]."' src='". $img ."' />";
        $i++;
    }


    $hot = processRequest("hot");

    $hot_html1 = $hot["html_0"];
    $hot_html2 = $hot["html_1"];

    $category_list = processRequest("category_list", $categories[0]["id"]);

    $category_html1 = $category_list["html_0"];
    $category_html2 = $category_list["html_1"];

    $tag_list = processRequest("tag_list", $tags[0]["id"]);

    $tag_html1 = $tag_list["html_0"];
    $tag_html2 = $tag_list["html_1"];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>富邦新視界</title>  
    
    <link rel="stylesheet" href="css/ui/jquery-ui.min.css"> 
    <link rel="stylesheet" href="css/messi.min.css"> 

    <link href="css/flexslider.css" type="text/css" rel="Stylesheet" />
        <link rel="stylesheet" href="css/phone.css"> 

    <style>
        .section {
            width: <?php echo $w; ?>px;
            float:left;
         }


    </style>

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/messi.min.js"></script>
<!--
    <script src="js/jail.min.js"></script>
    <script src="js/jquery.lightbox_me.js"></script>    
-->
    <script src="js/jquery.flexslider.js"></script>
    <?php if (!$web_mode) echo '<script src="js/StageWebViewBridge.js"></script>'; ?>
    <script>
    var categoryArray = [<?php echo json_encode($category_array);  ?>];
    var g_hot_page1 = '<?php echo $hot_html1; ?>';
    var g_hot_page2 = '<?php echo $hot_html2; ?>';
    var g_category_page1 = '<?php echo $category_html1; ?>';
    var g_category_page2 = '<?php echo $category_html2; ?>';
    var g_tag_page1 = '<?php echo $tag_html1; ?>';
    var g_tag_page2 = '<?php echo $tag_html2; ?>';
    var g_category_list = '<?php echo addslashes($category_html); ?>';
    var g_tag_list = '<?php echo addslashes($tag_html); ?>';

    var g_test_mode = <?php echo $test_mode; ?>;
    var g_iOS = <?php echo $iOS; ?>;

<?php if ($test_mode == "true") { ?>
        g_tag_list += "<img class='tag_button' src='DATA/images/hiring/1_normal.png' data-id='-1' data-normal='DATA/images/hiring/1_normal.png' data-press='DATA/images/hiring/1_press.png'  width='150' height='50'/>";
<?php } ?>

    </script>
    <script src="js/config.js"></script>
    <script src="js/phone.js?123"></script>

</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
    <div  id="query_container" data-show="0" style="display:none; z-Index:1000; position:absolute; left: 0px; top:0px; right: 0px;bottom:0;background-color: rgba(0, 0, 0, 0.9);" onClick="$('#query_container').css('display', 'none');"  >
        <div style="position:absolute; left: 0; height: 30px; margin:1px; background-color:#2222; border-radius: 5px; font-size:1.2em;right: 115px;">
        <input id="query" type="text" style="position:absolute; left: 0; height: 30px; margin:1px; background-color: rgba(250, 250, 250, 0.9); border-radius: 5px; font-size:1.2em;right: 115px;" />
       
            </div>
        <img id="dosearch" src="images/top/top_search_normal.png" style="z-Index:1001; position:absolute; right:40px; height:35px;top:5px;cursor:pointer"/>
    </div>
    <div  id="back_container" style="display:none; z-Index:900; position:absolute; left: 0px; top:0px; right: 0px;height:45px;background-color: #ccc;"  >
            <img id="return_btn" src="images/back.png" style="position:absolute; left:20px; height:35px;top:5px;cursor:pointer"/>
    </div>

   <div id="wrapper">
        <div id="top">
            <img id="logo_btn" src="images/top/fubonvision_logo.png" style="position:absolute; left:10px; height:35px;top:5px;"/> 

            
            <img id="search" src="images/top/top_search_normal.png" style="position:absolute; right:40px; height:35px;top:5px;cursor:pointer"/>
            <img id="page_refresh" style="position:absolute; right:5px; top:10px; height:25px" onClick="location.reload();" src="images/refresh.png" />

        </div>
       
        <div id="bottom" style="display:block; z-Index: 1000; position:absolute; bottom:0; height:50px; left:0; right:0; ">

            <ul style="display:table; table-layout: fixed; height:100%; width: 100%; text-align:center; list-style-type: none;padding-left:0px; margin:0;background-color: #1D8DCC;">
                <li class="menu-item" data-target="menu_home" >
                    <img id="menu_home" src="images/phone/0526/bottom_banner_icon01_word.png" />
                </li>
                <li class="menu-item" data-target="menu_category" >
                    <img id="menu_category" src="images/phone/0526/bottom_banner_icon02_word.png"/>
                </li>
                <li class="menu-item" data-target="menu_tag" >
                    <img id="menu_tag" src="images/phone/0526/bottom_banner_icon03_word.png" />
                </li>
                <li class="menu-item" data-target="menu_download" >
                    <img id="menu_download" src="images/phone/0526/bottom_banner_icon04_word.png" />
                </li>
            </ul>
        </div>
       

<div id="viewport" style="position:absolute; top:45px; bottom:50px; left:0px; right:0px; display:block ">

<ul style="width:1000%; margin:0; list-style-type:none;padding-left:0;">
       
       <li class="section"> 
       <div id="home_content" class="scrolling" style="position: relative;overflow-x:hidden; overflow-y:auto; "> 

           <div id="home_banners">
               <div id="home_banner_slider" class="flexslider"> 
                   <ul class="slides" style="width:100%; border solid 1px red; height:200px;" > <?php echo $banner1_html; ?> </ul>
<!--
<?php echo $first_banner; ?>
-->

                   <div class="play_button" > </div>
               </div>
	   </div>
           <div id="hot_buttons" style="/*position:absolute; left:0px; right:0px; top:200px; */height:40px;">
               <span style="border:solid 0px red; position:absolute; left:0px; width:50%;top:0; height:40px; background-color: #1D8DCC; text-align:center;">
                    <img id="new_area_txt" src="images/phone/0526/mobile_topbar_01_word_over.png" style="margin: 8px;" />
               </span>
                <span style="position:absolute; right:0px; width:50%;top:0; height:40px; background-color: #229E9A; text-align:center;">
                    <img id="hot_area_txt" src="images/phone/0526/mobile_topbar_02_word_normal.png" style="margin: 8px;" />
               </span>
            </div>
           
            <ul id="home_container" style="position:relative; /*left:0px; right:0px; top:240px; */margin:0; list-style-type: none;padding-left:10px; padding-right:10px;background-color:#eee;">

<?php echo $hot_html1; ?>

            </ul>
       
       </div>
       </li>
       
       
       <li class="section">
           <div id="category_content" class="scrolling" style="position: relative;width:100%; overflow-x:hidden; overflow-y:auto; ">
               <div id="categories" class="scrolling" style="position:absolute; top:0; left:0; right:0; overflow-x:scroll; overflow-y:hidden; white-space:nowrap; margin:0; list-style-type: none; padding-left:0px;">
<?php
//    echo $category_html;
?>
            </div>
           <div  style="position:absolute; left:0px; right:0px; top:42px; height:40px; background-color: #1D8DCC;">
               <!--
                <img src="images/phone/mid_bot_banner/mid_bot_banner_icon_01.png" style="position: absolute; left: 10px; top:8px; width: 25px; height:25px;" />
-->
               <img id="category_hot_btn" src="images/sort_hot_press_phone.png" data-normal="images/sort_hot_normal_phone.png" data-press="images/sort_hot_press_phone.png" style="position: absolute; left: 60px; top:8px; /*width: 62px; height:25px;*/" />
                <img id="category_new_btn" src="images/sort_new_normal_phone.png"  data-normal="images/sort_new_normal_phone.png" data-press="images/sort_new_press_phone.png"   style="position: absolute; left: 130px; top:8px;/* width: 62px; height:25px;*/" />

            </div>
           
            <ul id="category_container" class="scrolling"  style="position:absolute; left:0px; right:0px; top:82px; bottom:0; overflow-y:scroll; overflow-x:hidden; margin:0; list-style-type: none;padding-left:10px; padding-right:10px;background-color:#eee;">

<?php 
//echo $category_html1; 
?>

            </ul>
       
       </div>
       </li>

       <li class="section"> 
       <div id="tag_content" class="scrolling" style="position:relative; width:100%; overflow-x:hidden; overflow-y:auto; "> 

            <div id="tags" class="scrolling" style="position:absolute; top:0; left:0; right:0; height:50px; overflow-x:scroll; overflow-y:hidden; white-space:nowrap; margin:0; list-style-type: none; padding-left:0px;"> 
<?php
    echo $tag_html;
?>
            </div>
           <div id="sort_buttons" style="position:absolute; left:0px; right:0px; top:50px; height:40px; background-color: #1D8DCC;">
<!--
               <img src="images/phone/mid_bot_banner/mid_bot_banner_icon_01.png" style="position: absolute; left: 10px; top:8px; width: 25px; height:25px;" />
-->
               <img id="tag_hot_btn" src="images/sort_hot_press_phone.png" data-normal="images/sort_hot_normal_phone.png" data-press="images/sort_hot_press_phone.png" style="position: absolute; left: 60px; top:8px; " />
                <img id="tag_new_btn" src="images/sort_new_normal_phone.png" data-normal="images/sort_new_normal_phone.png" data-press="images/sort_new_press_phone.png" style="position: absolute; left: 130px; top:8px; " />

            </div>
           
            <ul id="tag_container" class="scrolling"  style="position:absolute; left:0px; right:0px; top:90px; bottom:0; overflow:auto;; margin:0; list-style-type: none;padding-left:10px; padding-right:10px;background-color:#eee;">

<?php 
//echo $tag_html1; 
?>

            </ul>
       
       </div>
       </li>           
       
       <li class="section"> 
       <div id="search_content" class="scrolling" style="width:100%; overflow-x:hidden; overflow-y:auto; "> 



            <p style="position:absolute; left:0px; right:0px; margin: 0; padding: 10px 50px; top: 0px; height:40px; font-size:1.0em; color:#FFF;background-color: #1D8DCC; overflow:hidden">搜尋 <span id="search_term"> </span> 共找到 <span id="search_hit"> </span> 筆資料 </p> 
            <img src="images/search.png" style="position: absolute; left: 10px; top:8px; width: 25px; height:25px;" />

           
            <ul id="search_container" class="scrolling"  style="position:absolute; left:0px; right:0px; top:40px; bottom:0; overflow-y:auto; overflow-x:hidden; margin:0; list-style-type: none;padding-left:10px; padding-right:10px;background-color:#eee;">

            </ul>
       
       </div>
       </li> 
       
       <li class="section"> 
       <div id="video_content" class="scrolling" style="width:100%; overflow-x:hidden; overflow-y:auto; "> 
            <div id="video_name" style="/*position:absolute; top:0; left:0; right:0; height:60px;*/ background-color: #1D8DCC; color:white;font-size:14pt; text-align:center; margin: 0px 0px; padding :1px 10px;"> 
           
           </div>
           

            
           <div style="position:relative; /*left:0px; right:0px;  top:60px;*/ background-color:#eee; height:140px;margin:0; padding:10px 20px; ">
                <div id="video_metadata" style="position:absolute; width:100px; left:10px; top:0; bottom:0; text-align:center; "> 
                    <img id="video_cover" src="DATA/images/cover150/103043004.png" style="height:140px; margin-top:15px;"/> 
                </div>
                <div id="metadata1" style="position:absolute; left:120px; right:0; padding-left:0px; top:10px; bottom:10px; border-left: solid 0px #ccc; padding:00px;">
                    </div>

            </div>
           
           
            <div id="video_buttons" style="position:relative;/* left:0px; right:0px; top:220px; height:40px;*/ background-color: #1D8DCC; text-align:center;">

                <img id="video_sd_button" src="images/film/film_banner_sd_normal.png" onclick="$(this).attr('src', 'images/film/film_banner_sd_press.png');$('#video_hd_button').attr('src', 'images/film/film_banner_hd_normal.png');" />

                <img id="video_hd_button" src="images/film/film_banner_hd_normal.png" onclick="$(this).attr('src', 'images/film/film_banner_hd_press.png');$('#video_sd_button').attr('src', 'images/film/film_banner_sd_normal.png');" />
                <img id="video_download_button" src="images/film/film_banner_downloads_normal.png" onclick="doDownload();" /> 
                <img id="video_downloading_button" style="display: none;" src="images/downloads/downloading.png" /> 
                <img id="video_downloaded_button" style="display: none;" src="images/downloads/downloaded.png" /> 

            </div>
           
            <div id="metadata2" style="position:relative; left:0; right:0; bottom:0; top:0px; background-color: #eee; color:#666;font-size:12pt; text-align:justify; padding: 10px "> 
          

           </div>
       </div>
       </li>
       
       
       <li class="section"> 
       <div id="download_content" class="scrolling" style="width:100%; overflow-x:hidden; overflow-y:auto; "> 

           <div style="position:absolute; left:0px; right:0px; top:0px; height:40px; background-color: #1D8DCC;">
                <img src="images/downloads/download_icon.png" style="position: absolute; left: 10px; top:8px; width: 25px; height:25px;" />
                <img src="images/downloads/download_txt.png" style="position: absolute; left: 40px; top:8px; width: 90px; height:25px;" />

            </div>
           
            <ul id="download_container" class="scrolling"  style="position:absolute; left:0px; right:0px; top:40px; bottom:0px; overflow-y:auto; overflow-x:hidden; margin:0; list-style-type: none;padding-left:10px; padding-right:10px; background-color:#eee;">

            </ul>
       
       </div>
       </li>   

       
       <li class="section"> 
       <div id="hiring_detail_content" class="scrolling" style="width:100%; overflow-x:hidden; overflow-y:auto; background-color:#eee; border:0; "> 
            <div id="hiring_detail_name" style="background-color: #C1490A; color:white;font-size:14pt; text-align:center; margin: 0px 0px; padding :5px 10px;"> 
【房仲業銷售業務員】<br/>增員資料夾
           </div>
            
           <div style="position:relative; background-color:#eee; margin:0; padding:10px 20px; ">
                <div id="hiring_detail_img" style=" text-align:center; "> 
                    <img id="hiring_detail_cover" src="DATA/images/hiring/content/1.jpg" style="width:70%"/> 
                </div>
            </div>
           
            <div id="hiring_detail_update" style="position:relative; color: white; background-color: #C1490A; text-align:center; font-size:14pt; padding:10px 10px;">
檔案更新日期：104年4月2日
            </div>
           
            <div id="hiring_detail_metadata" style="background-color: #eee; color:#666;font-size:12pt; text-align:justify; padding: 1% 10%; border:solid 0px red; "> 
檔案簡介：<br/>
【房仲銷售業務員】增員資料夾，提供您增員房仲業務員時的輔助展示資料。 使用手冊提供增員資料夾簡報檔內容解析以及示範問句，讓您了解如何於增員現場使用增員資料夾切進工作議題，並附錄房仲業五大理想工作要件分析作為參考資料。
<br/>

<?php if ($iOS == "true") { ?>

<ul style="position:relative; text-align:center;margin: 30px auto; width: 70%;">
<li style="display:inline-block;"><img id="button1" style="height:35px;" src="DATA/images/hiring/button/increase_banner_book_normal.png" onclick="openURL('https://fubonevideo.moker.com/download.php?id=2&sid=<?php echo session_id(); ?>');" /></li>
<li style="display:inline-block;"><img id="button4" style="height:35px;" src="DATA/images/hiring/button/increase_banner_file_normal.png" onclick="openURL('https://fubonevideo.moker.com/download.php?id=4&sid=<?php echo session_id(); ?>');" /></li>
</ul>


<?php } else { ?>

<ul style="position:relative; text-align:center;margin: 30px auto; width: 70%;">
<li style="display:inline-block;"><img id="button1" style="height:35px;" src="DATA/images/hiring/button/increase_banner_book_normal.png" onclick="openURL('https://fubonevideo.moker.com/ajax/main/file.php?id=2&sid=<?php echo session_id(); ?>');" /></li>
<li style="display:inline-block;"><img id="button4" style="height:35px;" src="DATA/images/hiring/button/increase_banner_file_normal.png" onclick="openURL('https://fubonevideo.moker.com/ajax/main/file.php?id=4&sid=<?php echo session_id(); ?>');" /></li>
</ul>


<?php } ?>

<hr/>
           </div>
       </div>
       </li>

</ul>
</div>  <!--viewport end-->

        <div id="dialog-confirm" > </div>

        <div id="viewer" style="display:none">

	    <img src="images/close_box_gray.png" onClick='onLightboxClose()' style="z-Index:1000;position:absolute; cursor:pointer; width:25px; height: 25px; right:5px; top:2px"/>
	   </div>

    </div>
</body>
    
    



</html>
