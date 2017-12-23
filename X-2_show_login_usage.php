<?php

include_once('inc/global.php');

include_once('inc/config.php');
include_once('inc/utils.php');

$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
$rank_select = isset($_REQUEST['rank_select'])?$_REQUEST['rank_select']:'';
$ranks = isset($_REQUEST['ranks'])?$_REQUEST['ranks']:'';

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>單位登入使用率</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 

		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/X-2.css"> 
 		
		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script type="text/javascript" src="js/jquery-ui-all.min.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		
		<script type="text/javascript" src="js/jtable/jquery.jtable.min.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine.js"></script> 	
		<script type="text/javascript" src="js/jquery.validationEngine-zh_TW.js"></script> 	
		<script type="text/javascript" src="js/json2.js"></script> 
                <script type="text/javascript">

                    var from = '<?php echo $from; ?>';
                    var to = '<?php echo $to; ?>';
                    var units = "<?php echo $units; ?>";
                    var ranks = '<?php echo $ranks; ?>';
                    var rank_select = "<?php echo $rank_select; ?>";

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

function doExportBook() {
    keys=['from', 'to', 'units', 'ranks', 'rank_select'];
    values=[from, to, units, ranks, rank_select];

    var html = ""; 
    
    var form_id = 'book_formid' + Math.round(Math.random()*1000+1);
    html += "<form id='" + form_id + "' method='post' action='ajax/X-2/toExcel2.php'>";
    if (keys && values && (keys.length == values.length)) {
        for (var i = 0; i < keys.length; i++)  {
            html += "<input type='hidden' name='" + keys[i] + "' value='" + values[i] + "'/>";
        }
    }
    html += "</form>";

    $("body").append(html);

    document.getElementById(form_id).submit();
}


                $(document).ready(function() {


                    $("button").button();




                    var table = {
                        messages: chtMessages,
                        title: '報表',
                        paging: true,
                        pageSize: 20,
                        sorting: true,
                        actions: {
                            listAction: 'ajax/X-2/LogList.php',
                        },
                        toolbar: {
                            items: [{
                                text: '匯出Excel檔',
                                click: function () {
                                        doExportBook();
                                }
                            }]
                        },
                        fields: {
/*
                            BOOK: {
                                title: '書籍名稱',
                                width: '10%',
                                list: false
                            },
                            SERIAL_NUMBER: {
                                title: '序號',
                                width: '10%',
                                list: false
                            },
*/
                            PUN: {
                                title: '區部名稱',
                                width: '10%',
                                list: true,
                            },
                            UC: {
                                title: '單位代號',
                                width: '10%',
                                list: true,
                            },
                            UN: {
                                title: '單位名稱',
                                width: '10%',
                                list: true,
                            },
                            NUM: {
                                title: '單位登入人數',
                                width: '10%',
/*
                                display: function (data) {
                                    count=data.record.NUM;
                                    msg="<a style='cursor:pointer; color:blue' onclick=\"showDetail('" + data.record.USER+ "', '" + data.record.NAME+"','" + data.record.SERIAL_NUMBER +"', '" + data.record.BOOK +"')\">"+data.record.NUM+'</a>';
                                    return msg;
                                },
*/
                                list: true
                            },
                            HEADCOUNT: {
                                title: '單位在職人數',
                                width: '10%',
                                list: true,
                            },
                            USAGE: {
                                title: '單位使用率',
                                width: '10%',
                                display: function(data) { return data.record.USAGE+"%"; },
                                list: true,
                            }
                        }
                    };


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
                                        if (type=='書籍') {
                                            msg += " " + book_info[data.record.content];
                                        }
                                        $ret=msg;
                                    } else {

                                        $ret='<option disabled>共選擇' + separated.length + '筆</option>'; 
                                        $.each(separated, function(index, chunk) {
                                            chunk = (type=='書籍')?chunk + " " + book_info[chunk]:chunk;
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
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '單位', content: '<?php echo $units; ?>' } });
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '職級搜尋類型', content: '<?php echo ($rank_select==0)?'符合職級':'排除職級'; ?>' } });
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '職級', content: '<?php echo $ranks; ?>' } });


                     $('<h3 style="color:#44aaaa">登入記錄</h3>').appendTo('#LogListContainer');

                     $block = $('<div>');
                     $block.appendTo('#LogListContainer');
                     $block.jtable(table);

                     $block.jtable('load', {from:from, to:to, units:units, ranks:ranks, rank_select:rank_select});

                     $('<br />').appendTo('#LogListContainer');
                     $('<br />').appendTo('#LogListContainer');
                     $('<br />').appendTo('#LogListContainer');


                });

            </script>

	</head>
	
	<body>

<button id="close" onclick="window.close();">關閉視窗</button>
<!--
<button id="exportButton" onclick="doExport();">匯出Excel檔</button>
-->

            <h1> 單位登入率查詢結果 </h1>
    <form id='formid' method='post' action='ajax/X-2/toExcel2.php'>
        <input type='hidden' name='from' value='<?php echo $from; ?>'/>
        <input type='hidden' name='to' value='<?php echo $to; ?>'/>
        <input type='hidden' name='units' value='<?php echo $units; ?>'/>
        <input type='hidden' name='rank_select' value='<?php echo $rank_select; ?>'/>
        <input type='hidden' name='ranks' value='<?php echo $ranks; ?>'/>
    </form>

                <div id="content">

<div id="detail-form" title="詳細內容">
<div id="user_info"> </div>
                        <div class="clear"> </div>
                        <hr />
<div id="SingleLogListContainer"> </div>

</div>

			<div id="queryList"> </div>
                        <div class="clear"> </div>
                        <hr />

			<div id="LogListContainer">
			</div>

		</div>

	</body>
</html>
