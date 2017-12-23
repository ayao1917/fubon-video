<?php

include_once('inc/global.php');

include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_log.php');
include_once('inc/class_video.php');
include_once('inc/report_config.php');

$from = $_REQUEST['from'];
$to = $_REQUEST['to'];
$videos = $_REQUEST['videos'];
$uid = $_REQUEST['uid'];

$logdb = new Logs();
$logdb->init();
$videoDB = new Video();
$videoDB->init();

$all_videos = $videoDB->loadAllPublishedVideo();
$js_video_array_declare = "video_info=[];"; 
foreach ($all_videos as $key=>$item) {
    $js_video_array_declare .= 'video_info["'.$item["SERIAL_NUMBER"].'"]="'.addslashes($item['TITLE']).'";';
}

$user_query = "select ag1.AgentInfo.AgentName as NAME, ag1.AgentInfo.Rank as RANK, ag1.AgentInfo.CurStatus as STATUS, ag1.UnitInfo.UnitName as UNITNAME, ag1.UnitInfo.UnitCode as UNITCODE from ag1.AgentInfo inner join ag1.UnitInfo where ag1.AgentInfo.AgentID='$uid' and ag1.AgentInfo.UnitCode=ag1.UnitInfo.UnitCode"; 

$sth = $dbh->prepare($user_query);
$sth->execute();
$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

$u_name = '';
$u_rank = '';
$u_status = '';
$u_unitname = '';
$u_unitcode = '';

if (count($rows)>0) {
    $u_name = $rows[0]['NAME'];
    $u_rank = $rows[0]['RANK'];
    $u_status = $rows[0]['STATUS'];
    $u_unitname = $rows[0]['UNITNAME'];
    $u_unitcode = $rows[0]['UNITCODE'];

    $qs = "select DATE, TIME, upper(USER) as USER, VIDEO, count(*) as NUM from view WHERE 1 ";
    $qs .= ($from == '')? '' : " AND (DATE>='$from') ";
    $qs .= ($to == '')? '' : " AND (DATE<='$to') ";
    $qs .= ($videos =='') ? '' : " AND ViDEO IN ($videos) ";
    $qs .= ($uid == '') ? '' : " AND ( upper(USER) = '" .$uid . "') ";
    $qs .= "GROUP BY VIDEO";

    $rows = $logdb->search($qs);
    $video_array = array();

    $result = '';
    for ($i=0; $i< count($rows); $i++){

        $k = $videoDB->loadVideo($rows[$i]['VIDEO']);

       $result .= "$('#result').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { SERIAL_NUMBER:'". $rows[$i]['VIDEO'] . "', BOOK: '".addslashes($k['TITLE'])."', USER: '".  $uid. "',date: '". $rows[$i]['DATE'] . "', time: '".$rows[$i]['TIME']. "', count:". $rows[$i]['NUM'] .  "} });\n";
    }

} else {
    $result = "";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>記錄列表</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 

		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/X-3.css"> 
 		
		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		
		<script type="text/javascript" src="js/jtable/jquery.jtable.min.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine-zh_TW.js"></script> 	
		<script type="text/javascript" src="js/json2.js"></script> 
        <script type="text/javascript">

            var chtMessages= {
                serverCommunicationError: '連線失敗.',
                loadingMessage: '載入中...',
                noDataAvailable: '無資料!',
                addNewRecord: '+ 新增記錄',
                editRecord: '編輯',
                areYouSure: '你確定嗎?',
                deleteConfirmation: '即將刪除，你確定嗎?',
                save: '儲存',
                saving: '儲存中',
                cancel: '取消',
                deleteText: '刪除',
                deleting: '刪除中',
                error: '錯誤',
                close: '關閉',
                cannotLoadOptionsFor: '無法載入選項 {0}',
                pagingInfo: '顯示{0} 到 {1} / 共 {2} 筆',
                canNotDeletedRecords: '無法刪除 {0}/{1} !',
                deleteProggress: '已刪除{0} / {1} 筆記錄, 進行中...',
                pagingInfo: '顯示{0}-{1} 筆。總共 {2}筆',
                pageSizeChangeLabel: '每頁筆數',
                gotoPageLabel: '選擇頁碼'
            };


            function doExport() {
                document.getElementById('formid').submit();
            }
            $(document).ready(function() {

                $("button").button();

                var from = '<?php echo $from; ?>';
                var to = '<?php echo $to; ?>';
                var uid = '<?php echo $uid; ?>';
                var videos = '<?php echo $videos; ?>';

<?php echo $js_video_array_declare; ?> 

                $('#queryList').css('width', '500px');
                $('#queryList').css('margin-left', '20px');

                $('#queryList').jtable({
                    fields: {
                        label: {
                            title: '查詢條件',
                            width: '40%',
                            list: true
                        },
                        content: {
                            title: '內容',
                            width: '60%',
                            display: function (data) {
                                msg = data.record.content||'未指定';
                              
                                type= data.record.label;

                                separated = data.record.content.split(',');
                                $ret = separated.length;
                                if (separated.length>1) {

                                    $ret='<option disabled>共選擇' + separated.length + '筆</option>'; 
                                    $.each(separated, function(index, chunk) {
                                        chunk = (type=='書籍')?chunk + " " + video_info[chunk]:chunk;
                                        $ret += '<option disabled>' + chunk + '</option>';
                                    });
                                    $ret = '<select>' + $ret + '</select>';
                                } else {
                                    $ret=msg; 
                                } 
                                
                                return $ret;
                                return '<div style="position:relative; overflow:auto; width:100%">' + msg + '</div>';
                            },
                            list: true
                        }
                   }
                });
                $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '起始日', content: '<?php echo $from; ?>' } });
                $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '結束日', content: '<?php echo $to; ?>' } });
                $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '書籍', content: '<?php echo $videos; ?>' } });
                $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '單位名稱', content: '<?php echo $u_unitname; ?>' } });
                $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '單位代號', content: '<?php echo $u_unitcode; ?>' } });
                $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '業務員', content: '<?php echo $uid; ?>' } });
                $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '業務員姓名', content: '<?php echo $u_name; ?>' } });
                $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '職稱', content: '<?php echo $u_rank; ?>' } });
                $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '在職狀態', content: '<?php echo ($u_status<90)?'在職':$u_status; ?>' } });

                $('#result').css('width', '80%');
                $('#result').css('margin-left', '20px');
                $('#result').jtable({
                    sorting:false,
                    fields: {
                        BOOK: {
                            title: '影片名',
                            width: '35%',
                            list: true
                        },
                        SERIAL_NUMBER: {
                            title: '序號',
                            width: '35%',
                            list: false
                        },
                        USER: {
                            title: '業務員ID',
                            width: '35%',
                            list: false
                        },
                        NAME: {
                            title: '業務員',
                            width: '35%',
                            list: false
                        },
                        date: {
                            title: '最後觀看日期',
                            width: '10%',
                            list: true
                        },
                        time: {
                            title: '最後觀看時間',
                            width: '10%',
                            list: false
                        },
                        count: {
                            title: '觀看次數',
                            width: '10%',
                            list: true
                        }
                   }
                });

<?php echo $result;?>

            });

        </script>

	</head>
	
	<body>

        <button id="close" onclick="window.close();">關閉視窗</button>
        <button id="exportButton" onclick="doExport();">匯出Excel檔</button>
        <h1> 個人影片觀看記錄查詢結果 </h1>

        <form id='formid' method='post' action='ajax/X-3/toExcel2.php'>
            <input type='hidden' name='from' value='<?php echo $from; ?>'/>
            <input type='hidden' name='to' value='<?php echo $to; ?>'/>
            <input type='hidden' name='videos' value='<?php echo $videos; ?>'/>
            <input type='hidden' name='uid' value='<?php echo $uid; ?>'/>
        </form>

        <div id="content">
			<div id="queryList"> </div>
            <div class="clear"> </div>
            <hr />

			<div id="result"> </div>
            <div class="clear"> </div>
		</div>

	</body>
</html>
