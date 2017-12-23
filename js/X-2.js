
var categoryArray = new Object();
var bookArray = new Object();
var unitArray = new Object();
var lastCategoryId="0";

var chtMessages= {
    serverCommunicationError: '連線失敗.',
    loadingMessage: '載入中...',
    noDataAvailable: '無資料!',
    addNewRecord: '+ 新增',
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
var ranks= []; 
var rank_select;
var categories= [];
var units=[];
var regions=[];

$(document).ready(function() {

    initLogList();
 

    setupConditionDisplay();
    loadInitData();
//    $("button").button();
    $("#selection").tabs({
    });
    $("#toggle_filter").click(function() {
        $("#selection").slideToggle('fast');
    });


    $("#showUsageButton").click(function(e) {
        e.preventDefault();

	getFilterValues();
console.log(from);
        condition = "起始日期:" + ((from=='')?'未指定':from);
        condition += "<br /> 結束日期:" + ((to=='')?'未指定':to);

        if (units.length==$("#unit_level_3 option").size()) { 
            condition += "<br /> 區部下全部單位:" + regions.join(',');
        } else {
            condition += "<br /> 單位:" + units.join(',');
        }

        //$("#query").html(condition).fadeIn("slow");
        $("#query").html(condition).css("display", "block");

keys=['from', 'to', 'units', 'ranks', 'rank_select'];

values=[from, to, units.join(','), ranks.join(','), rank_select];

openWindowWithPost('X-2_show_login_usage.php', '_new',  keys, values);


        //loadLogList();
    });

    $("#ClearSearchButton").click(function() {

        $("#book_level_1 option:selected").removeAttr("selected");
        $("#book_level_1").multiselect("refresh");

        $("#rank_list option:selected").removeAttr("selected");
        $("#rank_list").multiselect("refresh");

        $("#from").attr("value", "");
        $("#to").attr("value", "");
    });

    bookSelectionHandler();
    unitSelectionHandler();

});

function openWindowWithPost(url, name, keys, values) {
    var newWindow = window.open(url, name);
    if (!newWindow) return false;
    var html = "";
    html += "<html><head></head><body><form id='formid' method='post' action='" + url + "'>";
    if (keys && values && (keys.length == values.length))
        for (var i = 0; i < keys.length; i++)
        html += "<input type='hidden' name='" + keys[i] + "' value='" + values[i] + "'/>";
    html += "</form><script type='text/javascript'>document.getElementById(\"formid\").submit()</script></body></html>";
    newWindow.document.write(html);
    return newWindow;
}

function showAlert(obj, msg) {
    
    var $message = $('<span style="font-size:14pt; color:red; display:none">'+msg+'</span>');
    $(obj).after($message);

    $message.fadeIn("slow", function() {  window.setTimeout(function() { $message.fadeOut("slow"); $message.remove() }, 1000); });
}

function removeDom(obj) {
    obj.remove();
}


function addTargets(target, type, name) {
    $a = $(target).parent();
    $("#selected_targets").append('<li><img src="images/lightboxClose.png" style="cursor:pointer" width="18" height="18" onclick="$a.remove()"/> ' + type +'：' + name + '</li>');


}

function getFilterValues() {
    from = $("#from").val() ||"";
    to = $("#to").val() ||"";
    categories = $("#book_level_1").val() ||[];
    books = $("#book_level_2").val() ||[];
//    regions= $("#unit_level_2").val()||[];
//    units= $("#unit_level_3").val()||[];
    units= [];
    ranks=$("#rank_list").val() ||[];
    rank_select = $('input[name=rank_type]:checked').val();

    $("#unit_area li").each(function() {

        if ($(this).hasClass("selected")) { 
            var code = $(this).attr('id');
            code = code.replace("code_", "");
            units.push(code);
        }

    });
    select = $('input[name=type]:checked').val();
//    uid = $("#uid").attr("value").toUpperCase();


    if (from!='') {
        var  a = from.split('/');
        from = a[0]-1911 + '.'+ a[1] + '.' + a[2];
    } else {
        from = '';
    }
    if (to!='') {
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
                $.each(v.bookList, function(j, item) {
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
    $("#txtSearch").on('keyup', function (e) {
        // clear on Esc
        if (e.which == 27) { this.value = ""; }

        searchString = this.value;

        $("#unit_area li").each(function(index){
            if ($(this).text().indexOf(searchString) == -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    $("#expandAll").on('click', function(e) {
        e.preventDefault();
        $('.expand').removeClass("expand").addClass("collapse");
        $('.p_region:hidden').slideDown("fast");
    });

    $("#collapseAll").click(function(e) {
        e.preventDefault();
        $('.collapse').removeClass("collapse").addClass("expand");
        $('.p_region:visible').slideUp("fast");
    });

    $(".toggle").click(function() {
        var index = $(".toggle").index(this);

        if ($(this).hasClass('expand')) {
            $(this).removeClass("expand").addClass("collapse");
            $(".pp_"+index).slideDown("fast");
        } else  if ($(this).hasClass('collapse')) {
            $(this).removeClass("collapse").addClass("expand");
            $(".pp_"+index).slideUp("fast");
        }

    });

    $('.check').on('click', function() {
        if ($(this).hasClass('checked')) {
            $(this).removeClass("checked");
            $(this).parent().removeClass('selected').addClass('not_selected');
        } else {
            $(this).addClass("checked");
            $(this).parent().addClass('selected').removeClass('not_selected');
        }
    });


    $('#selectAll').click(function(e) {
        e.preventDefault();
        $('.check').addClass('checked');
        $("#unit_area li").addClass('selected').removeClass('not_selected');
    });

    $('#clearAll').click(function(e) {
        e.preventDefault();
        $('.checked').removeClass('checked');
        $("#unit_area li").addClass('not_selected').removeClass('selected');
    });
    $('#showSelected').click(function() {
/*
        $("#txtSearch").val("");
        $("#txtSearch").trigger('keyup');
        $('.p_region:hidden').show();
*/
        if ($(this).attr("checked")) {
            $('.not_selected').css('display', 'none');
            $("#expandAll").trigger('click');
        } else {
            $('.not_selected').css('display', 'block');
        }

    });

    $('.tree').click(function(e) {
        e.preventDefault();
        $(this).parent().children('.check').addClass('checked');
        $(this).parent().addClass('selected').removeClass('not_selected');

        $(this).parent().next().children().each(function() {
         $('.check', this).addClass('checked').parent().addClass('selected').removeClass('not_selected');
        });
    });

    $.support.selectstart = "onselectstart" in document.createElement("div");
    $.fn.disableSelection = function() {
        return this.bind( ( $.support.selectstart ? "selectstart" : "mousedown" ) + ".ui-disableSelection", function( event ) { event.preventDefault(); });
    };

    $("#unit_area").disableSelection();

}


function loadInitData() {
return ;
        $.getJSON('ajax/B-18/load_books.php?ts='+new Date().getMilliseconds(),  function(data) {
                makeBookList(data);
        }); 

}

function makeBookList(data) {

    bookArray = data;

    $.each(data, function(i, item) {
        $("#book_level_1").append('<option value="'+data[i].categoryID+'">'+data[i].categoryNAME+'</option>');
    });
    $("#book_level_1").multiselect("refresh");


}

function setupConditionDisplay() {
    $('#from').datepicker();
    $('#to').datepicker();


    $('#book_level_1').multiselect({
        noneSelectedText: '分類',
        checkAllText: '全部選擇',
        uncheckAllText: '取消選擇',
        minWidth: 150,
        selectedText: '選擇(#)筆分類資料'
    });
    $('#book_level_2').multiselect({
        noneSelectedText: '書籍',
        checkAllText: '全部選擇',
        uncheckAllText: '取消選擇',
        minWidth: 150,
        selectedText: '選擇(#)筆書籍資料'
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
        $('#rank_list').multiselect({
            selectedList: 10,
            selectedText: '選擇(#)筆職級',
            noneSelectedText: '選擇職級',
            checkAllText: '全部選擇',
            uncheckAllText: '取消選擇',
            minWidth: 400,
            height: '400'
        }).multiselectfilter({
            label: '過濾',
            placeholder: '關鍵字'
        });

}



function loadLogList() {
    $('#LogListContainer').jtable('load');
}

function afterSuccess()  {
    $('#UploadForm').resetForm();  // reset form
    $('#SubmitButton').removeAttr('disabled'); //enable submit button
}



function initLogList() {
return;	
 
}
