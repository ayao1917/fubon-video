<?php

include_once('inc/global.php');
include_once('inc/class_manager.php');

$pdb = new Manager();
$pdb->init();

$permission = $pdb->check("BANNER", $USER_ID);

if ($permission==null){ die(" $USER_ID 無權限"); }
if ($permission<1) { die(" $USER_ID 無權限"); }
$canCreate = ($permission>2)?"true":"false" ;
$canEdit = ($permission>1)?"true":"false" ;


?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-3</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 


		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/Z-3.css"> 
		<link rel="stylesheet" href="css/validationEngine.jquery.css"> 

                <script type="text/javascript"> var canEdit=<?php echo $canEdit; ?>; var canCreate=<?php echo $canCreate; ?>; </script>
 		
		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		
		<script type="text/javascript" src="js/jtable/jquery.jtable.min.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine-zh_TW.js"></script> 	
		<script type="text/javascript" src="js/json2.js"></script> 
		<script type="text/javascript" src="js/Z-3.js"></script> 

	</head>
	
	<body>
		<div id="content">
                        <h1>廣宣區管理</h1>
			<div id="BannerListContainer">
			</div>
		</div>

	</body>
</html>
