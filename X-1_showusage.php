<?php

include_once('inc/global.php');

include_once('inc/config.php');
include_once('inc/utils.php');
include_once('inc/class_log.php');
include_once('inc/class_video.php');
include_once('inc/class_user.php');

$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$videos = isset($_REQUEST['videos'])?$_REQUEST['videos']:'';
$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';
$selected = isset($_REQUEST['selected'])?$_REQUEST['selected']:'';
$uid = isset($_REQUEST['uid'])?$_REQUEST['uid']:'';
$ranks = isset($_REQUEST['ranks'])?$_REQUEST['ranks']:'';
$rank_select = isset($_REQUEST['rank_select'])?$_REQUEST['rank_select']:'';


$logdb = new Logs();
$logdb->init();
$videodb = new Video();
$videodb->init();
$userdb = new User();
$userdb->init();

$all_videos = $videodb->loadAllPublishedVideo();
$js_video_array_declare = "video_info=[];"; 
$js_video_array_declare .= 'video_info["video0"]="全部";'; 
foreach ($all_videos as $key=>$item) {

    $name = $item['TITLE'];
    $js_video_array_declare .= 'video_info["video'.$item["SERIAL_NUMBER"].'"]="'.addslashes($name).'";';
}

$range_modifier = '';
$order_modifier = '';


$videoname_array=array();

$time_constraint = ''; 
$video_constraint= ""; 

if ($from!='')  {
    $time_constraint .= " AND (DATE>='$from') ";
}
if ($to!='')  {
    $time_constraint .= " AND (DATE<='$to') ";
}

if ($videos!='') {
    $b = "'" . str_replace(",", "','", $videos) . "'";
    $video_constraint .= " AND VIDEO IN ($b) ";
}


$qs = "select VIDEO from VIEW where 1 $time_constraint $video_constraint GROUP BY VIDEO";

$rows = $logdb->search($qs);
$video_array = array();

for ($i=0; $i< count($rows); $i++){
    array_push($video_array, $rows[$i]['VIDEO']);
    $k = $videodb->loadVideo($rows[$i]['VIDEO']);
    array_push($videoname_array, ($k==null)?$rows[$i]['VIDEO']:$k['TITLE']);
}




?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>單位閱讀率</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.9.2.custom.css"> 

		<link href="js/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
<!--
		<link rel="stylesheet" href="css/B-18.css"> 
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

var currentVideo="0";
                    var from = '<?php echo $from; ?>';
                    var to = '<?php echo $to; ?>';
                    var uid = '<?php echo $uid; ?>';
                    var select = '<?php echo $select; ?>';
                    var videos = '<?php echo $videos; ?>';
                    var units = "<?php echo $units; ?>";
                    var ranks = '<?php echo $ranks; ?>';
                    var rank_select = "<?php echo $rank_select; ?>";

function showDetail(user, name, serial_number, video) {
$("#user_info").html(video+":"+name+"("+user+")");
$('#detail-form').dialog("open");
$('#SingleLogListContainer').jtable('load', {from:"<?php echo $from; ?>", to:"<?php echo $to; ?>", uid:user, select:0, videos:serial_number, units:"<?php echo $units; ?>"});
}


function doExport() {
    document.getElementById('formid').submit();
}
function doExportVideo() {
    keys=['from', 'to', 'select', 'video', 'units', 'videos', 'ranks', 'rank_select'];
    values=[from, to, select, currentVideo, units, videos, ranks, rank_select];

    var html = ""; 
    
    var form_id = 'video_formid' + Math.round(Math.random()*1000+1);
    html += "<form id='" + form_id + "' method='post' action='ajax/X-1-1/toExcel3.php'>";
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


                    $('#SingleLogListContainer').jtable({
                        messages: chtMessages,
                        paging: true,
                        pageSize: 20,
                        sorting: false,
                        actions: {
                            listAction: 'ajax/B-18/LogList.php'
                        },

/*
                        toolbar: {
                            items: [{
                                text: '匯出Excel檔',
                                click: function () {
                                        l="ajax/B-18/toExcel.php?from=<?php echo $from;?>&to=<?php echo $to; ?>&uid=<?php echo $uid;?>&select=<?php echo $select;?>&videos=<?php echo $videos;?>&units=<?php echo $units; ?>";
                                        location.href=l;
                                }
                            }]
                        },
*/
                        fields: {
                            DATE: {
                                title: '日期',
                                width: '10%',
                                list: true
                            },
                            TIME: {
                                title: '時間',
                                width: '10%',
                                list: true
                            },
                            DURATION: {
                                title: '閱讀時間',
                                width: '10%',
                                list: true,
                                display: function(data){
                                    time=parseInt(data.record.DURATION);
                                    var minutes = Math.floor(time / 60);
                                    var seconds = time - minutes * 60;

                                    var output='';

                                    if (minutes>0) output = minutes+'分';
                                    output += seconds+'秒';
                                    return output;
                                }
                            },
                            LAST_PAGE: {
                                title: '最後頁數',
                                width: '10%',
                                list: true
                            }
                        }
                    });



                    var table = {
                        messages: chtMessages,
                        title: '報表',
                        paging: true,
                        pageSize: 20,
                        sorting: true,
                        actions: {
                            listAction: 'ajax/X-1-1/LogList_video.php',
                        },
                        toolbar: {
                            items: [{
                                text: '匯出Excel檔',
                                click: function () {
                                        doExportVideo();
                                }
                            }]
                        },
                        fields: {
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
                                title: '單位閱讀人數',
                                width: '10%',
/*
                                display: function (data) {
                                    count=data.record.NUM;
                                    msg="<a style='cursor:pointer; color:blue' onclick=\"showDetail('" + data.record.USER+ "', '" + data.record.NAME+"','" + data.record.SERIAL_NUMBER +"', '" + data.record.VIDEO +"')\">"+data.record.NUM+'</a>';
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
                                title: '單位閱讀率',
                                width: '10%',
                                display: function(data) { return data.record.USAGE+"%"; },
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
                                        if (type=='書籍') {
                                            msg += " " + video_info['video'+data.record.content];
                                        }
                                        $ret=msg;
                                    } else {

                                        $ret='<option disabled>共選擇' + separated.length + '筆</option>'; 
                                        $.each(separated, function(index, chunk) {
                                            chunk = (type=='書籍')?chunk + " " + video_info['video'+chunk]:chunk;
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
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '書籍', content: '<?php echo $videos; ?>' } });
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '單位', content: '<?php echo $units; ?>' } });
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '職級搜尋類型', content: '<?php echo ($rank_select==0)?'符合職級':'排除職級'; ?>' } });
                    $('#queryList').jtable('addRecord', { clientOnly: true, animationsEnabled: false, record: { label: '職級', content: '<?php echo $ranks; ?>' } });



                    //The first "0" means all

                    var video_list = ["0", "<?php echo implode('","', $video_array); ?>"];

/*
                    $.each(video_list, function(index, video_id) {

                        serial_number =  (video_id=='0')?'':video_id;

                        $('<h3 style="color:#44aaaa">'+ serial_number +' ' +video_info[video_id]+'</h3>').appendTo('#LogListContainer');

                        $block = $('<div>');
                        $block.appendTo('#LogListContainer');
                        $block.jtable(table);

                        $block.jtable('load', {from:from, to:to, uid:uid, select:select, video:video_id, units:units});


                        $('<br />').appendTo('#LogListContainer');
                        $('<br />').appendTo('#LogListContainer');
                        $('<br />').appendTo('#LogListContainer');
                    });
*/

                    option_list = '';
                    $.each(video_list, function(index, video_id) {
                        serial_number =  video_id;
                        if ('video'+video_id in video_info) {
			    option_list += "<option value='" + serial_number + "'>" + video_info['video'+video_id] +  "</option>";
                        } else {
			    option_list += "<option value='" + serial_number + "'>" + video_id +  "</option>";
                        } 

                    });

                    $('<select id="video_list">'+option_list +'</select><br/>').appendTo("#LogListContainer");


video_id='0';
                    serial_number =  (video_id=='0')?'':video_id;
                    $('<h3 id="selected_title" style="color:#44aaaa">'+ serial_number +' ' +video_info["video0"]+'</h3>').appendTo('#LogListContainer');
                    $block = $('<div>');
                    $block.appendTo('#LogListContainer');
                    $block.jtable(table);

                    $block.jtable('load', {from:from, to:to, uid:uid, select:select, video:videos, units:units, ranks:ranks, rank_select:rank_select});


                    $('<br />').appendTo('#LogListContainer');
                    $('<br />').appendTo('#LogListContainer');
                    $('<br />').appendTo('#LogListContainer');

                    $("#video_list").change(function(){
                        currentVideo = $(this).val();
                        if ($(this).val()=='0') {
                            $("#selected_title").text('不分書別(全部)');
                            $block.jtable('load', {from:from, to:to, uid:uid, select:select, video:videos, units:units, ranks:ranks, rank_select:rank_select});
                        } else {
                            $("#selected_title").text($("#video_list option:selected").text());
                            $block.jtable('load', {from:from, to:to, uid:uid, select:select, video:$(this).val(), units:units, ranks:ranks, rank_select:rank_select});
                        }
                    });


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
<button id="exportButton" onclick="doExport();">匯出全部Excel檔(需較長時間)</button>
            <h1> 單位閱讀率查詢結果 </h1>
    <form id='formid' method='post' action='ajax/X-1-1/toExcel3.php'>
        <input type='hidden' name='from' value='<?php echo $from; ?>'/>
        <input type='hidden' name='to' value='<?php echo $to; ?>'/>
        <input type='hidden' name='videos' value='<?php echo $videos; ?>'/>
        <input type='hidden' name='units' value='<?php echo $units; ?>'/>
        <input type='hidden' name='ranks' value='<?php echo $ranks; ?>'/>
        <input type='hidden' name='rank_select' value='<?php echo $rank_select; ?>'/>
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
