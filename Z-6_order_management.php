<?php
include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/global.php');

include_once('inc/class_manager.php');
include_once('inc/class_category.php');
include_once('inc/class_tag.php');

$pdb = new Manager();
$pdb->init();

$permission = $pdb->check("CATEGORY", $USER_ID);

if ($permission==null){ die(" $USER_ID 無權限"); }
if ($permission<1) { die(" $USER_ID 無權限"); }
$canCreate = ($permission>2)?"true":"false" ;
$canEdit = ($permission>1)?"true":"false" ;

$dbCategory = new Category();
$dbCategory->init();
$category = $dbCategory->loadAllPublishedCategory();

$option_list_1="";
foreach ($category as $item) {
    $id = $item['ID'];
    $title = $item['TITLE'];

    $selected_flag="";
//    if (in_array($id, $selected1)) $selected_flag = "selected='selected'";
    $option_list_1 .= "<option value='$id' $selected_flag>$id $title</option>";
}

$dbTag = new Tag();
$dbTag->init();
$tag = $dbTag->loadAllPublishedTag();

$option_list_2="";
foreach ($tag as $item) {
    $id = $item['ID'];
    $title = $item['TITLE'];

    $selected_flag="";
//    if (in_array($id, $selected1)) $selected_flag = "selected='selected'";
    $option_list_2 .= "<option value='$id' $selected_flag>$id $title</option>";
}

function prepareOrderListHTML($list, $type) {
    $result = "";

    foreach ($list as $item) {
        $id = $item['ID'];
        $f = "/images/$type/".$id."_normal.png";
        $title = $item['TITLE'];
        if (file_exists(__DATA_PATH__.$f)) {
            $cover = '<img class="cover" src="'. __DATA_URL__ . $f .'"/>';
        } else {
            $cover = '<img class="cover" src="images/default_video.png"/>';
        }
        $result .=  "<li id='sort_$id' class='ui-widget-content ui-corner-tr' data-cover='$cover' >";
        $result .=  "<h4 class='ui-widget-header'>$title";
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

    <script type="text/javascript">
        var canEdit=<?php echo $canEdit; ?>;
        var canCreate=<?php echo $canCreate; ?>;
    </script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-all.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="js/Z-6.js"></script>
    <script type="text/javascript" src="js/jquery.multiselect.min.js"></script>
    <script type="text/javascript" src="js/jquery.multiselect.filter.js"></script>

    <link rel="stylesheet" href="css/jquery.multiselect.css">
    <link rel="stylesheet" href="css/jquery.multiselect.filter.css">
</head>

<body>

<h1>
    排序設定 - 用滑鼠Drag - Drop 方式操作排序，操作後自動儲存
</h1>

<div id="area1">
    <p>類別</p>
    <ul id="VideoListContainer1" class="gallery ui-helper-reset ui-helper-clearfix">
        <?php print prepareOrderListHTML($category, "category"); ?>
    </ul>

</div>

<div id="area2">
    <p>系列</p>
    <ul id="VideoListContainer2" class="gallery ui-helper-reset ui-helper-clearfix">
        <?php echo prepareOrderListHTML($tag, "tag"); ?>
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