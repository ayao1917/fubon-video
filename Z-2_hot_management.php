<?php

include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/global.php');
include_once('inc/class_video.php');
include_once('inc/class_newordername.php');

include_once('inc/class_manager.php');

$pdb = new Manager();
$pdb->init();

$permission = $pdb->check("FRONTPAGE", $USER_ID);

if ($permission==null){ die(" $USER_ID 無權限"); }
if ($permission<1) { die(" $USER_ID 無權限"); }
$canCreate = ($permission>2)?"true":"false" ;
$canEdit = ($permission>1)?"true":"false" ;


$db = new Video();

$db->init();
$newVideo0 = $db->loadNewVideo(0);
$newVideo1 = $db->loadNewVideo(1);
$newVideo2 = $db->loadNewVideo(2);
$allPublishedVideo = $db->loadAllPublishedVideo("","ORDER BY PUBLISH_DATE DESC");

debug($allPublishedVideo);
$db1 = new NewOrderName();
$db1->init();
$area1 = $db1->load(1);
$area2 = $db1->load(2);

$selected1 = array();
$selected2 = array();
foreach ($newVideo1 as $item) {
    array_push($selected1, $item['SERIAL_NUMBER']);
}
foreach ($newVideo2 as $item) {
    array_push($selected2, $item['SERIAL_NUMBER']);
}


$option_list_1="";
$option_list_2="";
foreach ($allPublishedVideo as $item) {
    $id = $item['SERIAL_NUMBER'];
    $title = $item['TITLE'];

    $selected_flag="";
    if (in_array($id, $selected1)) $selected_flag = "selected='selected'";
    $option_list_1 .= "<option value='$id' $selected_flag>$id $title</option>";
        
    $selected_flag="";
    if (in_array($id, $selected2)) $selected_flag = "selected='selected'";
    $option_list_2 .= "<option value='$id' $selected_flag>$title</option>"; 
}

function prepareVideoListHTML($list) {
    $result = "";

    foreach ($list as $item) {
        $id = $item['SERIAL_NUMBER'];
        $f = "/images/cover/$id.png";
        $title = $item['TITLE'];
        if (file_exists(__DATA_PATH__.$f)) {
            $cover = '<img class="cover" src="'. __DATA_URL__ . $f .'"/>';
        } else {
            $cover = '<img class="cover" src="images/default_video.png"/>';
        }
	$result .=  "<li id='sort_$id' class='ui-widget-content ui-corner-tr' data-cover='$cover' >";

        $result .=  "<h4 class='ui-widget-header'>$title";
 //       $result .= '<a style="cursor:pointer; color:red;font-size:1.2em" onclick="removeFromList('.$id.', this);" >X</a>';
        $result .=   "</h4></li>\n";
    }

    return $result;
}


?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-2</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.3.custom.css"> 

		<link rel="stylesheet" href="css/Z-2.css"> 

        <script type="text/javascript"> var canEdit=<?php echo $canEdit; ?>; var canCreate=<?php echo $canCreate; ?>; </script>
 		
		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		<script type="text/javascript" src="js/Z-2.js"></script> 
		<script type="text/javascript" src="js/jquery.multiselect.min.js"></script> 
		<script type="text/javascript" src="js/jquery.multiselect.filter.js"></script> 
        
        <link rel="stylesheet" href="css/jquery.multiselect.css"> 
		<link rel="stylesheet" href="css/jquery.multiselect.filter.css"> 

	</head>
	
	<body>

<h1>
熱門設定 - 用滑鼠Drag - Drop 方式操作排序，操作後自動儲存
</h1>


<div id="area1">
<p>
<?php echo $area1; ?>
</p>
<select id="select_area1" name="type" multiple="multiple">
<?php echo $option_list_1; ?>    
</select>
<ul id="VideoListContainer1" class="gallery ui-helper-reset ui-helper-clearfix">

			<?php print prepareVideoListHTML($newVideo1); ?>
</ul>

</div>
<div id="area2">
<p>
<?php echo $area2; ?>
</p>
<select id="select_area2" name="type" multiple="multiple"> 
<?php echo $option_list_2; ?>        
</select>
    
<ul id="VideoListContainer2" class="gallery ui-helper-reset ui-helper-clearfix">
			<?php echo prepareVideoListHTML($newVideo2); ?>
</ul>
</div>


		

	</body>
    
      <script>
  $(function() {
    $( document ).tooltip({
      items: "[data-cover]",
    position: { my: "left+15 center", at: "right center" },
      content: function() {
        var element = $( this );
        if ( element.is( "[data-cover]" ) ) {
          var url = element.attr("data-cover");
          return url;
        }
      }
    });
  });
  </script>
</html>
