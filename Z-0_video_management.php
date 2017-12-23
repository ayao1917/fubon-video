<?php

include_once('inc/global.php');
include_once('inc/class_manager.php');
include_once('inc/class_category.php');
include_once('inc/class_tag.php');

header("Cache-Control: no-cache, must-revalidate"); 

$pdb = new Manager();
$pdb->init();

$permission = $pdb->check("VIDEO", $USER_ID);

if ($permission==null){ die(" $USER_ID 無權限"); }
if ($permission<1) { die(" $USER_ID 無權限"); }
$canCreate = ($permission>2)?"true":"false" ;
$canEdit = ($permission>1)?"true":"false" ;

$category = new Category();
$category->init();
$tag = new Tag();
$tag->init();

$allCategory = $category->load();
$allTag = $tag->load();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-0</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 


		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/Z-0.css"> 
		<link rel="stylesheet" href="css/jquery.cleditor.css"> 
		<link rel="stylesheet" href="css/validationEngine.jquery.css"> 
                <link rel="stylesheet" href="css/jquery.multiselect.css"> 
                <link rel="stylesheet" href="css/jquery.multiselect.filter.css"> 


	</head>
	
	<body>
		
		<div id="content">
                        <h1>影片管理</h1>

            <div class="filtering">
                <form>
                    關鍵字: <input type="text" name="name" id="name" />
<!--                    City:-->
<!--                    <select id="cityId" name="cityId">-->
<!--                        <option selected="selected" value="0">All cities</option>-->
<!--                        <option value="1">Adana</option>-->
<!--                    </select>-->
                    <button type="button" id="LoadVideosButton">搜尋</button>
                </form>
            </div><br/>

			<div id="VideoListContainer">
			</div>
		</div>

                <div id="reorder_panel" style="display:none"><ul id="sortable"></ul></div>

                <script type="text/javascript"> var EDITOR = "<?php echo $USER_ID; ?>"; var canEdit=<?php echo $canEdit; ?>; var canCreate=<?php echo $canCreate; ?>; </script>
 		
		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		
		<script type="text/javascript" src="js/jtable/jquery.jtable.min.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine-zh_TW.js"></script> 	
		<script type="text/javascript" src="js/jquery.cleditor.min.js"></script> 
                <script type="text/javascript" src="js/jquery.multiselect.min.js"></script> 
                <script type="text/javascript" src="js/jquery.multiselect.filter.js"></script> 
		<script type="text/javascript" src="js/json2.js"></script> 
                <script>
                    var category_array = new Object;
                    category_array["0"] = '未分類';
                    var tag_array = new Object;
                    tag_array["0"] = '未指定';

                    <?php 
                        foreach ($allCategory as $item) {
                            echo 'category_array["'. $item['ID'] .'"] = "' . $item['TITLE'] . '";';
                        }
                        foreach ($allTag as $item) {
                            echo 'tag_array["'. $item['ID'] .'"] = "' . $item['TITLE'] . '";';
                        }
                    ?> 
                </script>
		<script type="text/javascript" src="js/Z-0.js"></script> 

	</body>
</html>
