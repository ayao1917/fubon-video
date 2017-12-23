
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

var from, to, select, uid;
var books= [];
var categories= [];
var units=[];
var regions=[];

$(document).ready(function() {

    initLogList();
$('#uid').autocomplete({
      source: "ajax/X-3/load_names.php"
//      minLength: 2
    });
//    loadLogList();
 
    loadInitData();

    setupConditionDisplay();
    $("button").button();
    $("#toggle_filter").click(function() {
        $("#selection").slideToggle('fast');
    });
    $("#LoadRecordsButton").click(function() {

	getFilterValues();

        condition = "起始日期:" + ((from=='')?'未指定':from);
        condition += "<br /> 結束日期:" + ((to=='')?'未指定':to);
        if (books.length==$("#book_level_2 option").size()) { 
            
            if (categories.length>0) {
            condition += "<br /> 分類下所有影片:" + categories.join(',');
            } else {

            condition += "<br /> 影片:未指定";

            }

        } else {
            condition += "<br /> 影片:" + books.join(',');
        }

        condition += "<br /> 業務員:" + ((uid=='')?'未指定':uid);


        //$("#query").html(condition).fadeIn("slow");
        $("#query").html(condition).css("display", "block");
        //$('#LogListContainer').jtable('load', {from:from, to:to, books:books, units:units});

        //$('#LogListContainer').jtable('load', {from:from, to:to, uid:uid, select:"0", books:books.join(','), units:units.join(',')});
        window.open("X-3_showuserlog.php?from="+from+"&to="+to+"&select=0&uid="+uid+"&videos="+books.join(","),"_new");

        //loadLogList();
    });


    $("#ClearSearchButton").click(function() {
        $("#book_level_2 option").remove();
        $("#book_level_2").multiselect("refresh");

        $("#book_level_1 option:selected").removeAttr("selected");
        $("#book_level_1").multiselect("refresh");

        $("#from").attr("value", "");
        $("#to").attr("value", "");
        $("#uid").attr("value", "業務員ID或姓名");

    });

    bookSelectionHandler();
    //unitSelectionHandler();

});

function getFilterValues() {
    from = $("#from").val()||'';
    to = $("#to").val()||'';
    categories = $("#book_level_1").val() ||[];
    books = $("#book_level_2").val() ||[];
    regions= $("#unit_level_2").val()||[];
    units= $("#unit_level_3").val()||[];
    select = "0";
    uid = $("#uid").val().toUpperCase();

    if (uid==='業務員ID或姓名'){
        uid='';
    }

    if ( typeof from != 'undefined' && from!='') {
        console.log(from);
        var  a = from.split('/');
        from = a[0]-1911 + '.'+ a[1] + '.' + a[2];
    } else {
        from = '';
    }
    if (typeof to != 'undefined' && to!='') {
        var  a = to.split('/');
        to = a[0]-1911 + '.'+ a[1] + '.' + a[2];
    } else {
        to = '';
    }
}

function bookSelectionHandler() {
    $("#book_level_1").change(function() {
        $("#book_level_2 option").remove();
        $("#book_level_2").multiselect("refresh");
  
        var d = $(this).val();
        if (d==null) return;

        var added = [];

        $.each(bookArray, function(i, v) {
            if (jQuery.inArray(v.categoryID, d)!=-1) {
                $.each(v.videoList, function(j, item) {
                    if (jQuery.inArray(item.SERIAL_NUMBER, added)==-1) {  // not added yet
                        $("#book_level_2").append('<option value="'+item.SERIAL_NUMBER+'">'+item.TITLE+'</option>');
                        added.push(item.SERIAL_NUMBER); 
                    }

                });
            }
        });
        $("#book_level_2").multiselect("refresh");

        $.each(d, function(i, item) {


        });
      
    });
}

function unitSelectionHandler() {
    $("#unit_level_1").change(function() {
        $("#unit_level_2 option").remove();
        $("#unit_level_3 option").remove();
        $("#unit_level_2").multiselect("refresh");
        $("#unit_level_3").multiselect("refresh");
  
        var d = $(this).val();
        if (d==null) return;

        var added = [];

        $.each(unitArray, function(i, v) {
            if (jQuery.inArray(v.d, d)!=-1) {
                if (jQuery.inArray(v.c, added)==-1) {  // not added yet
                    $("#unit_level_2").append('<option value="'+v.c+'">'+v.c+'</option>');
                    added.push(v.c); 
                }

            }
        });
        $("#unit_level_2").multiselect("refresh");
    });
    $("#unit_level_2").change(function() {
        $("#unit_level_3 option").remove();
        $("#unit_level_3").multiselect("refresh");
  
        var d = $(this).val();
        if (d==null) return;

        var added = [];

        $.each(unitArray, function(i, v) {
            if (jQuery.inArray(v.c, d)!=-1) {
                if (jQuery.inArray(v.a, added)==-1) {  // not added yet
                    $("#unit_level_3").append('<option value="'+v.a+'">'+v.b+'</option>');
                    added.push(v.a); 
                }

            }
        });
        $("#unit_level_3").multiselect("refresh");
    });
}

function loadInitData() {
        $.getJSON('ajax/X-3/load_videos.php?ts='+new Date().getMilliseconds(),  function(data) {
                makeBookList(data);
        }); 
}

function makeBookList(data) {

    bookArray = data;

    $.each(data, function(i, item) {
        $("#book_level_1").append('<option value="'+data[i].categoryID+'">'+data[i].categoryNAME+'</option>');
        $("#book_level_1").multiselect("refresh");
    });


}
function makeUnitList(data) {

    unitArray = data;

    var added = [];
    $.each(data, function(i, item) {
        if (jQuery.inArray(item.d, added)==-1) {  // not added yet
            $("#unit_level_1").append('<option value="'+item.d+'">'+item.d+'</option>');
            $("#unit_level_1").multiselect("refresh");
            added.push(item.d); 
        }
    });
}

function makeCategoryList(data) {

    $.each(data, function(key, val) { recursiveFunction(key, val, 0) });

    function recursiveFunction(key, val, level) {

        actualFunction(key, val, level);
        var value = val['children'];
        if (value instanceof Object) {
            $.each(value, function(key, val) {
                recursiveFunction(key, val, level+1)
            });
        }

    }

    function actualFunction(key, val, level) {
        var key = val.attr.id;
        if (parseInt(key, 10)>lastCategoryId) lastCategoryId = parseInt(key);
        if (key != 0) {
            prefix='';
            for (k=1; k<level; k++) prefix=prefix+'&nbsp;&nbsp;&nbsp;';
            categoryArray[key] = prefix+ val.data;
        }
    }

    $.each(data, function(i, object){


    });

}

function setupConditionDisplay() {
    $('#from').datepicker();
    $('#to').datepicker();
    $('#unit_level_1').multiselect({
        noneSelectedText: '營管',
        checkAllText: '全部選擇',
        uncheckAllText: '取消選擇',
        minWidth: 150,
        selectedText: '#選擇(#)筆營管資料'
    }).multiselectfilter({
        label: '過濾',
        placeholder: '關鍵字'
    });
    $('#unit_level_2').multiselect({
        noneSelectedText: '區部',
        checkAllText: '全部選擇',
        uncheckAllText: '取消選擇',
        minWidth: 150,
        selectedText: '選擇(#)筆區部資料'
    }).multiselectfilter({
        label: '過濾',
        placeholder: '關鍵字'
    });
    $('#unit_level_3').multiselect({
        noneSelectedText: '單位',
        checkAllText: '全部選擇',
        uncheckAllText: '取消選擇',
        minWidth: 150,
        selectedText: '選擇(#)筆單位資料'
    }).multiselectfilter({
        label: '過濾',
        placeholder: '關鍵字'
    });

    $('#book_level_1').multiselect({
        noneSelectedText: '分類',
        checkAllText: '全部選擇',
        uncheckAllText: '取消選擇',
        minWidth: 150,
        selectedText: '選擇(#)筆分類資料'
    }).multiselectfilter({
        label: '過濾',
        placeholder: '關鍵字'
    });
    $('#book_level_2').multiselect({
        noneSelectedText: '影片',
        checkAllText: '全部選擇',
        uncheckAllText: '取消選擇',
        minWidth: 150,
        selectedText: '選擇(#)筆影片資料'
    }).multiselectfilter({
        label: '過濾',
        placeholder: '關鍵字'
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
	        listAction: 'ajax/X-3/LogList.php',
	    },
            toolbar: {
                items: [{
                    text: '匯出Excel檔',
                    click: function () {

                            getFilterValues();
                            l="ajax/X-3/toExcel.php?from="+from+"&to="+to+"&uid="+uid+"&select=0&books="+books.join(',')+"&units="+units.join(',');
                            location.href=l;
                    }
                }]
            },
recordsLoaded: function(event, data) {


if (data.records.length>0) {
            $('#a_name').html(data.records[0].NAME);
            $('#a_unitcode').html(data.records[0].UC);
            $('#a_unitname').html(data.records[0].UN);
            $('#a_rank').html(data.records[0].RANK);
var s = (data.records[0].STATUS<90)? '在職':data.records[0].STATUS;
            $('#a_status').html(s);
}
console.log(data.records[0]);

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
                BOOK: {
                    title: '影片名稱',
                    width: '10%',
                    list: true
                },
	        USER: {
	            title: '業務員ID',
	            width: '10%',
	            list: false
	        },	        
	        NAME: {
	            title: '業務員姓名',
	            width: '10%',
	            list: false
	        },	        
	        UC: {
	            title: '單位代碼',
	            width: '10%',
	            list: false
	        },	        
	        UN: {
	            title: '單位名稱',
	            width: '10%',
	            list: false
	        },	        
	        RANK: {
	            title: '職稱',
	            width: '10%',
	            list: false
	        },	        
	        STATUS: {
	            title: '在職狀態',
	            width: '10%',
	            list: false
                }
	    }
	});
 
 
}
