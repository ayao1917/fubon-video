
var categoryArray = new Object();
var bookArray = new Object();
var unitArray = new Object();

var chtMessages= {
    serverCommunicationError: '連線失敗.',
    loadingMessage: '載入中...',
    noDataAvailable: '無資料!',
    addNewRecord: '新增',
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

var from, to;
var user=[];
var type=[];

$(document).ready(function() {

    initLogList();
//    loadLogList();
 
    loadInitData();

    setupConditionDisplay();
    $("button").button();
    $("#toggle_filter").click(function() {
        $("#selection").slideToggle('fast');
    });
    $("#LoadRecordsButton").click(function() {
        getFilterValues();

/*
        condition = "起始日期:" + from;
        condition += "<br /> 結束日期:" + to;
        condition += "<br /> 編輯:" + user.join(',');
        condition += "<br /> 功能區:" + type.join(',');
        $("#query").html(condition);

*/

        $('#LogListContainer').jtable('load', {from:from, to:to, user:user.join(','), type:type.join(',')}, function() { parent.resize();});


        //loadLogList();
    });
    $("#ClearSearchButton").click(function() {
        $("#from").attr("value", "");
        $("#to").attr("value", "");

        $("#user option:selected").removeAttr("selected");
        $("#user").multiselect("refresh");

        $("#type option:selected").removeAttr("selected");
        $("#type").multiselect("refresh");
    });

});

function getFilterValues() {
    from = $("#from").attr("value");
    to = $("#to").attr("value");
    user = $("#user").val() ||[];
    type= $("#type").val()||[];
    if (from!='') {
        var  a = from.split('/');
        from = a[0]-1911 + '/'+ a[1] + '/' + a[2];
    }
    if (to!='') {
        var  a = to.split('/');
        to = a[0]-1911 + '/'+ a[1] + '/' + a[2];
    }
}


function loadInitData() {
        $.getJSON('ajax/Z-13/load_user.php?ts='+new Date().getMilliseconds(),  function(data) {
                makeUserList(data);
        }); 
        $.getJSON('ajax/Z-13/load_type.php?ts='+new Date().getMilliseconds(),  function(data) {
                makeTypeList(data);
        }); 

}

function makeUserList(data) {

    UserArray = data;

    $.each(data, function(i, item) {
        $("#user").append('<option value="'+data[i].ID+'">'+data[i].ID+'</option>');
        $("#user").multiselect("refresh");
    });


}
function makeTypeList(data) {

    TypeArray = data;
    $.each(data, function(i, item) {
        $("#type").append('<option value="'+data[i].type+'">'+data[i].type+'</option>');
        $("#type").multiselect("refresh");
    });
}

function setupConditionDisplay() {
    $('#from').datepicker();
    $('#to').datepicker();
    $('#user').multiselect({
        noneSelectedText: '編輯ID',
        checkAllText: '全部選擇',
        uncheckAllText: '取消選擇',
        minWidth: 150,
        selectedText: '選擇(#)筆營管資料'
    });
    $('#type').multiselect({
        noneSelectedText: '功能區',
        checkAllText: '全部選擇',
        uncheckAllText: '取消選擇',
        minWidth: 150,
        selectedText: '選擇(#)筆區部資料'
    });

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
    /*
    $('#from').datepicker({ 
        dateFormat: "yymmdd",

        onSelect: function(dateText, inst) {
            dateText = dateText-19110000;
            $(this).val(dateText);
        },    
    });
    */

}



function loadLogList() {
    $('#LogListContainer').jtable('load');

}

function afterSuccess()  {
    $('#UploadForm').resetForm();  // reset form
    $('#SubmitButton').removeAttr('disabled'); //enable submit button
}



function initLogList() {
	
	$('#LogListContainer').jtable({
	    title: '記錄列表',
	    messages: chtMessages,
	    paging: true, //Enable paging
	    pageSize: 20, //Set page size (default: 10)
	    sorting: false, //Enable sorting
	    actions: {
	        listAction: 'ajax/Z-13/LogList.php',
	    },
            toolbar: {
                items: [{
                    text: '匯出Excel檔',
                    click: function () {
                            getFilterValues();
                            l="ajax/Z-13/toExcel.php?from="+from+"&to="+to+"&user="+user.join(',')+"&type="+type.join(',');
                            location.href=l;
                    }
                }]
            },
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
	        USER: {
	            title: '業務員ID',
	            width: '10%',
	            list: true,
	        },	        
	        ACTION: {
	            title: '命令',
	            width: '10%',
	            list: true,
	        },	        
	        TYPE: {
	            title: '功能區',
	            width: '10%',
	            list: true,
	        },	        
	        TARGET: {
	            title: '對象',
	            width: '10%',
	            list: true,
	        },	        
	        EXTRA: {
	            title: '其他資訊',
	            width: '5%',
	            list: true,
	        }	        
	    }
	});
 
 
}
