<?php

include_once('inc/global.php');

include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_video.php');

$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$videos = isset($_REQUEST['videos'])?$_REQUEST['videos']:'';
$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'0';
$rank_select = isset($_REQUEST['rank_select'])?$_REQUEST['rank_select']:'';
$ranks = isset($_REQUEST['ranks'])?$_REQUEST['ranks']:'';

$report_name = ($select=="0")?"閱讀人數":"閱讀人次";

$videodb = new Video();
$videodb->init();

$all_videos = $videodb->loadAllPublishedVideo();
$js_video_array_declare = "video_info=[];"; 

foreach ($all_videos as $key=>$item) {

    $name = $item['TITLE'];
    $js_video_array_declare .= 'video_info["video'.$item["SERIAL_NUMBER"].'"]="'.addslashes($name).'";';
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $report_name; ?></title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 

		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
<!--
		<link rel="stylesheet" href="css/B-22.css"> 
-->
 		
		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script type="text/javascript" src="js/jquery-ui.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		
		<script type="text/javascript" src="js/jtable/jquery.jtable.min.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine-zh_TW.js"></script> 	
		<script type="text/javascript" src="js/json2.js"></script> 
                <script type="text/javascript">

                    var from = '<?php echo $from; ?>';
                    var to = '<?php echo $to; ?>';
                    var uid = '';
                    var select = '<?php echo $select; ?>';
                    var videos = '<?php echo $videos; ?>';
                    var units = "<?php echo $units; ?>";
                    var ranks = '<?php echo $ranks; ?>';
                    var rank_select = "<?php echo $rank_select; ?>";

var chtMessages= {
    serverCommunicationError: '連線失敗.',
    loadingMessage: '載入中...',
    noDataAvailable: '無資料!',
    addNewRecord: '新增記錄',
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
function doExportReport() {
    keys=['from', 'to', 'uid', 'select', 'video', 'units', 'videos', 'ranks', 'rank_select'];
    values=[from, to, uid, select, currentVideo, units, videos, ranks, rank_select];

    var html = ""; 
    
    var form_id = 'video_formid' + Math.round(Math.random()*1000+1);
    html += "<form id='" + form_id + "' method='post' action='ajax/X-1/toExcel2.php'>";
    if (keys && values && (keys.length == values.length)) {
        for (var i = 0; i < keys.length; i++)  {
            html += "<input type='hidden' name='" + keys[i] + "' value='" + values[i] + "'/>";
        }
    }
    html += "</form>";

    $("body").append(html);

    document.getElementById(form_id).submit();

}


function showDetail(serial_number) {

    keys=['videos', 'from', 'to', 'units', 'ranks', 'rank_select'];

    values=[serial_number, from, to, units, ranks, rank_select];
    openWindowWithPost('B-18_showlog.php', '',  keys, values);
}

function openWindowWithPost(url, name, keys, values) {
    var newWindow = window.open(url, name);
    if (!newWindow) return false;
    var html = "";
    html += "<html><head></head><body><form id='formid' method='post' action='" + url + "'>";
    if (keys && values && (keys.length == values.length)) {
        for (var i = 0; i < keys.length; i++) html += "<input type='hidden' name='" + keys[i] + "' value='" + values[i] + "'/>";
    }

    html += "</form><script type='text/javascript'>document.getElementById('formid').submit()<\/script><\/body><\/html>";
    newWindow.document.write(html);
    return newWindow;
}

                $(document).ready(function() {


                    $("button").button();

        var table={
            title: '統計列表',
            messages: chtMessages,
            paging: true, //Enable paging
            pageSize: 20, //Set page size (default: 10)
            sorting: false, //Enable sorting
            actions: {
                listAction: 'ajax/X-1-2/LogList.php',
            },  
            toolbar: {
                items: [{
                    text: '匯出Excel檔',
                    click: function () {
                            doExport();
                    }   
                }]  
            },  
            fields: {
                SERIAL_NUMBER: {
                    title: '序號',
                    list: false
                },  
                NAME: {
                    title: '影片名稱',
                    display: function(data) {
                        url = "B-18_showlog.php?videos="+data.record.SERIAL_NUMBER+"&from="+from+"&to="+to;
                        return "<a href='#' onClick='showDetail(" +data.record.SERIAL_NUMBER + ")' >"+data.record.NAME+"</a>";
                        return "<a href='"+url+"' target='_new'>"+data.record.NAME+"</a>";
                    },  
                    width: '60%',
                    list: true
                },  
                USER_COUNT: {
                    title: '次數',
                    width: '40%',
                    list: true,
                }     
            }
        };

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
                                    if (msg=='未指定') {
                                        $ret=msg;
                                    } else if (separated.length==1){
                                        msg = data.record.content;
                                        if (type=='影片') {
                                            msg += " " + video_info['video'+data.record.content];
                                        }
                                        $ret=msg;
                                    } else {

                                        $ret='<option disabled>共選擇' + separated.length + '筆</option>'; 
                                        $.each(separated, function(index, chunk) {
                                            chunk = (type=='影片')?chunk + " " + video_info['video'+chunk]:chunk;
                                            $ret += '<option disabled>' + chunk + '</option>';
                                        });
                                        $ret = '<select>' + $ret + '</select>';
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
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '影片', content: '<?php echo $videos; ?>' } });
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '單位', content: '<?php echo $units; ?>' } });
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '統計類型', content: '<?php echo ($select==0)?'閱讀人數':'閱讀人次'; ?>' } });
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '職級搜尋類型', content: '<?php echo ($rank_select==0)?'符合職級':'排除職級'; ?>' } });
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '職級', content: '<?php echo $ranks; ?>' } });



                    $block = $('<div>');
                    $block.appendTo('#LogListContainer');
                    $block.jtable(table);

                    $block.jtable('load', {from:from, to:to, select:select, videos:videos, units:units, ranks:ranks, rank_select:rank_select});


                    $('<br />').appendTo('#LogListContainer');
                    $('<br />').appendTo('#LogListContainer');
                    $('<br />').appendTo('#LogListContainer');


                    $('#detail-form').dialog({
                        autoOpen:false,
                        width:600,
                        height:400,
                        modal:true,
                        buttons: {
                            "關閉": function() { $( this ).dialog( "close" );}
                         }

                    });

                });

            </script>

	</head>
	
	<body>

<button id="close" onclick="window.close();">關閉視窗</button>
            <h1> <?php echo $report_name; ?> </h1>
    <form id='formid' method='post' action='ajax/X-1-2/toExcel2.php'>
        <input type='hidden' name='from' value='<?php echo $from; ?>'/>
        <input type='hidden' name='to' value='<?php echo $to; ?>'/>
        <input type='hidden' name='videos' value='<?php echo $videos; ?>'/>
        <input type='hidden' name='units' value='<?php echo $units; ?>'/>
        <input type='hidden' name='select' value='<?php echo $select; ?>'/>
        <input type='hidden' name='rank_select' value='<?php echo $rank_select; ?>'/>
        <input type='hidden' name='ranks' value='<?php echo $ranks; ?>'/>
    </form>

                <div id="content">

			<div id="queryList"> </div>
                        <div class="clear"> </div>
                        <hr />

			<div id="LogListContainer">
			</div>

		</div>

	</body>
</html>
