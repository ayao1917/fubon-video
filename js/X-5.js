
var categoryArray = new Object();
var bookArray = new Object();
var unitArray = new Object();

var chtMessages= {
    serverCommunicationError: '連線失敗.',
    loadingMessage: '載入中...',
    noDataAvailable: '無資料!',
    addNewRecord: '+ 新增公告',
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

var from, to, select;

$(document).ready(function() {

    initLogList();
 

    setupConditionDisplay();
    $("button").button();
    $("#toggle_filter").click(function() {
        $("#selection").slideToggle('fast');
    });
    $("#ClearSearchButton").click(function() {
        //$("#book_level_2").multiselect("refresh");
        $("#from").attr("value", "");
        $("#to").attr("value", "");
    });
    $("#LoadRecordsButton").click(function() {

        getFilterValues();

        $('#LogListContainer').jtable('load', {from:from, to:to, select:select}, function() {  
		//$.plot("#placeholder", [ d1, d2, d3 ]); 
	});

        $.post('ajax/X-5/StatData.php?ts='+new Date().getMilliseconds(), { from:from, to:to, select:select}, function(data) {
		var d = [0];
		var l = [0];
                $.each(data.Records, function(i, v) {

			d.push([i, v.value]);
			if (i%50==0) l.push([v.key]); 
                });
		$.plot("#placeholder", [ d ], {xaxis: {ticks:[l]}}); 

        });
    });
});


function getFilterValues() {
    from = $("#from").attr("value");
    to = $("#to").attr("value");
    select = $('input[name=type]:checked').val();

    if (from!='') {
        var  a = from.split('/');
        from = a[0]-1911 + '.'+ a[1] + '.' + a[2];
    }
    if (to!='') {
        var  a = to.split('/');
        to = a[0]-1911 + '.'+ a[1] + '.' + a[2];
    }
}

function setupConditionDisplay() {
    $('#from').datepicker();
    $('#to').datepicker();

    $.datepicker.regional['zh-TW'] = {
        closeText: '關閉',
        prevText: '&#x3c;上月',
        nextText: '下月&#x3e;',
        currentText: '今天',
        monthNames: ['一月','二月','三月','四月','五月','六月',
        '七月','八月','九月','十月','十一月','十二月'],
        monthNamesShort: ['一','二','三','四','五','六',
        '七','八','九','十','十一','十二'],
        dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
        dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
        dayNamesMin: ['日','一','二','三','四','五','六'],
        weekHeader: '周',
        dateFormat: 'yy/mm/dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: '年'};
   $.datepicker.setDefaults($.datepicker.regional['zh-TW']);
}

function doExportReport() {
    keys=['from', 'to', 'select'];
    values=[from, to, select];

    var html = ""; 
    
    var form_id = 'book_formid' + Math.round(Math.random()*1000+1);
    html += "<form id='" + form_id + "' method='post' action='ajax/X-5/toExcel2.php'>";
    if (keys && values && (keys.length == values.length)) {
        for (var i = 0; i < keys.length; i++)  {
            html += "<input type='hidden' name='" + keys[i] + "' value='" + values[i] + "'/>";
        }
    }
    html += "</form>";

    $("body").append(html);

    document.getElementById(form_id).submit();

}


function loadLogList() {
    $('#LogListContainer').jtable('load');
}


function initLogList() {
	
	$('#LogListContainer').jtable({
	    title: '統計列表',
	    messages: chtMessages,
	    paging: true, //Enable paging
	    pageSize: 20, //Set page size 
	    sorting: true, //Enable sorting
	    actions: {
	        listAction: 'ajax/X-5/LogList.php',
	    },
            toolbar: {
                items: [{
                    text: '匯出Excel檔',
                    click: function () {
                            doExportReport();
                    }   
                }]  
            },  
	    fields: {
                key: {
                    title: '日期',
	            width: '40%',
                    list: true
                },
                value: {
                    title: '次數',
                    width: '60%',
                    list: true
                }
	    }
	});
 
 
}
