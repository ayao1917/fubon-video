<?php

include_once('inc/config.php');
include_once('inc/global.php');
include_once('inc/class_manager.php');

$pdb = new Manager();
$pdb->init();

$permission = $pdb->check("SYSTEM", $USER_ID);

if ($permission==null){ die(" $USER_ID 無權限"); }
if ($permission<1) { die(" $USER_ID 無權限"); }
$canCreate = ($permission>2)?"true":"false" ;
$canEdit = ($permission>1)?"true":"false" ;

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Z-11</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 


		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/Z-11.css"> 
		<link rel="stylesheet" href="css/validationEngine.jquery.css"> 

                <script type="text/javascript"> var canEdit=<?php echo $canEdit; ?>; var canCreate=<?php echo $canCreate; ?>; </script>
 		
		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		
		<script type="text/javascript" src="js/jtable/jquery.jtable.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine-zh_TW.js"></script> 	
		<script type="text/javascript" src="js/json2.js"></script> 
		<script type="text/javascript" src="js/Z-11.js?20150823"></script> 
<!--
		<script type="text/javascript" src="js/ext.js"></script> 
-->

	</head>
	
	<body>
		<div id="content">
                        <h1>帳號和權限列表</h1>
			<div id="ManagerListContainer">
			</div>
<div>註：所建立的帳號必須是在富邦人壽使用的帳號名稱，登入驗證使用富邦人壽的登入機制。</div>
		</div>

	</body>
</html>
