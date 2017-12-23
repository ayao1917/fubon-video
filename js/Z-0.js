/*jslint debug: true */
/*jslint browser: true */

(function () {
    'use strict';
    /*global $, categoryArray, chtMessages, canCreate, canEdit */
    var categoryArray = {},
        chtMessages = {
            serverCommunicationError: '連線失敗.',
            loadingMessage: '載入中...',
            noDataAvailable: '無資料!',
            addNewRecord: '增加新影片',
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
            pageSizeChangeLabel: '每頁筆數',
            gotoPageLabel: '選擇頁碼'
        };


    $(window).load(function () {

        $.datepicker.regional['zh-TW'] = {
            closeText: '關閉',
            prevText: '&#x3c;上月',
            nextText: '下月&#x3e;',
            currentText: '今天',
            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            monthNamesShort: ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'],
            dayNames: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
            dayNamesShort: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
            dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
            weekHeader: '周',
            dateFormat: 'yy/mm/dd',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: true,
            yearSuffix: '年'
        };
        $.datepicker.setDefaults($.datepicker.regional['zh-TW']);

        $('#LoadVideosButton').click(function (e) {
            e.preventDefault();
            var name = $('#name').val() != undefined ? $('#name').val() : '';
            $('#VideoListContainer').jtable('load', {
                name: name
            });
        });

        loadData();
    });

    function debug(msg) {
        //console.log(msg);
    }


    function loadData() {
        initVideoList();
        loadVideoList(-1);
        //makeCategoryList(data);
    }

    function loadVideoList(categoryID) {
        var currentCategory = categoryID;
        $('#VideoListContainer').jtable('load', {
            "categoryID": categoryID
        });
    }

    function initVideoList() {

        var d, u, c;
        d = (canCreate) ? 'ajax/Z-0/DeleteVideo.php' : null;
        u = (canEdit) ? 'ajax/Z-0/UpdateVideo.php' : null;
        c = (canCreate) ? 'ajax/Z-0/CreateVideo.php' : null;

        $('#VideoListContainer').jtable({
            title: '影片列表',
            messages: chtMessages,
            paging: true, //Enable paging
            pageSize: 20, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'SERIAL_NUMBER DESC', //Set default sorting
            actions: {
                listAction: 'ajax/Z-0/VideoList.php',
                deleteAction: d,
                updateAction: u,
                createAction: c
            },
            //Initialize validation logic when a form is created
            formCreated: function (event, data) {
                data.form.find('input[name="TITLE"]').addClass('validate[required]');
                data.form.find('input[name="AUTHOR"]').addClass('validate[required,maxSize[20]]');
                data.form.validationEngine();

                $("#Edit-DETAIL").cleditor({
                    width: 500,
                    height: 250,
                    controls: "bold italic underline | font size " +
                        "style | color highlight removeformat | bullets numbering | outdent " +
                        "indent | alignleft center alignright justify | undo redo | " +
                        "rule image "
                });
                /*
                $("#Edit-TAG").multiselect({
                    noneSelectedText: '系列',
                    checkAllText: '全部選擇',
                    uncheckAllText: '取消選擇',
                    minWidth: 150,
                    selectedList: 10,
                    selectedText: '指定(#)筆系列'
                }).multiselectfilter({
                    label: '過濾',
                    placeholder: '關鍵字'
                });
*/
            },
            formSubmitting: function (event, data) {
                return data.form.validationEngine('validate');
            },
            recordsLoaded: function (event, data) {
                $("button").button();
            },
            recordUpdated: function (event, data) {
                $("button").button();
            },
            fields: {
                SERIAL_NUMBER: {
                    key: true,
                    title: '序號',
                    width: '10%',
                    input: function (data) {
                        var ret = ((typeof (data.value) !== "undefined")) ? data.value : '新影片';
                        return '<span>' + ret + '</span><input type="hidden" name="SERIAL_NUMBER" style="width:100px" readonly value="' + ret + '" />';
                    },
                    list: true,
                    create: true,
                    edit: true
                },
                TITLE: {
                    title: '影片名稱',
                    width: '20%',
                    input: function (data) {
                        var ret = ((typeof (data.value) !== "undefined")) ? data.value : '';
                        return '<input type="text" name="TITLE" style="width:200px" value="' + ret + '" />';
                    },
                    list: true,
                    create: true,
                    edit: true
                },
                CATEGORY: {
                    title: '分類',
                    width: '10%',
                    defaultValue: '0',
                    options: category_array,
                    list: true
                },
                TAG: {
                    title: '系列',
                    width: '10%',

                    display: function (data) {

                        var ary, name = '';
                        if (data.record.TAG !== null) {
                            ary = data.record.TAG.split(",");
                            $.each(ary, function (key, value) {
                                name = name + tag_array[value] + '<br/>';
                            });
                        }

                        //return data.record.TAG;
                        return name;
                    },
                    input: function (data) {

                        var ret = '';
                        //console.log(data);
                        //                        ()
                        if (data && (typeof (data.value) !== "undefined") && data.record.TAG !== null) {

                            var ary = data.value.split(",");
                            $.each(ary, function (key, value) {
                                ret = ret + tag_array[value] + '<br/>';
                            });
                        }

                        //                        ret = ((typeof(data.value) != "undefined")) ? data.value: '';
                        return '<span>' + ret + '</span><input type="hidden" name="TAG" style="width:100px" readonly value="' + data.value + '" />';
                    },
                    edit: false,
                    list: true
                },
                ORDER_NEW: {
                    title: '新片上架',
                    width: '7%',
                    defaultValue: '0',
                    list: false,
                    type: 'radiobutton',
                    options: {
                        '0': '否',
                        '1': '是'
                    }
                },
                STICKY: {
                    title: '分類中排序',
                    defaultValue: '0',
                    width: '10%',
                    list: false,
                    edit: true
                },
                AUTHOR: {
                    title: '作者',
                    create: false,
                    edit: false,
                    list: false
                },
                DETAIL: {
                    title: '介紹',
                    width: '10%',
                    type: 'textarea',
                    list: false
                },
                EDITOR_ID: {
                    title: '製作者',
                    input: function (data) {
                        var ret = ((typeof (data.value) !== "undefined")) ? data.value : EDITOR;
                        return '<span>' + ret + '</span><input type="hidden" name="EDITOR_ID" style="width:100px" readonly value="' + ret + '" />';
                    },
                    list: false
                },
                STATUS: {
                    title: '狀態',
                    width: '10%',
                    options: {
                        '0': '未發佈',
                        '1': '已發佈',
                        '2': '已下架'
                    }
                },
                /*
                PUBLISH_DATE: {
	            title: '上映日期',
                    input: function (data) {
                        ret = ((typeof(data.value) != "undefined")) ? data.value: '無';
                        return '<span>' + ret + '</span><input type="hidden" name="PUBLISH_DATE" style="width:100px" readonly value="' + ret + '" />';
                    },
	            create: true,
                    edit: true,
	            width: '10%'
	        },
*/
                PUBLISH_DATE: {
                    title: '上映日期',
                    width: '10%',
                    /*
                    display: function (data) {
                        return data.record.PUBLISH_DATE;
                    },  
*/
                    type: 'date',
                    create: true,
                    edit: true,
                    list: false
                },
                OFF_DATE: {
                    title: '下架日期',
                    width: '10%',
                    /*
                    display: function (data) {

                        return data.record.OFF_DATE;
                    },  
*/
                    type: 'date',
                    create: true,
                    edit: true,
                    list: false
                },
                /*
		OFF_DATE: {
	            title: '下架日期',
	            input: function (data) {
                        ret = ((typeof(data.value) != "undefined")) ? data.value: '無';
                        return '<span>' + ret + '</span><input type="hidden" name="OFF_DATE" style="width:100px" readonly value="' + ret + '" />';
                    },
	            list: false
	        },
*/
                VIDEO_LENGTH: {
                    title: '影片時間',
                    input: function (data) {
                        var ret = ((typeof (data.value) !== "undefined")) ? data.value : '0';
                        return '<span>' + ret + '</span><input type="hidden" name="VIDEO_LENGTH" style="width:100px" readonly value="' + ret + '" />';
                    },
                    width: '7%',
                    list: true
                },
                VIDEOS: {
                    title: '影片數',
                    input: function (data) {
                        var ret = ((typeof (data.value) !== "undefined")) ? data.value : '無';
                        return '<span>' + ret + '</span><input type="hidden" name="VIDEOS" style="width:100px" readonly value="' + ret + '" />';
                    },
                    width: '7%',
                    list: true
                },
                ACTION: {
                    title: '操作',
                    display: function (data) {
                        var uploadImagesButton = "<button onclick='location.replace(\"Z-0_upload_images.php?video_id=" + data.record.SERIAL_NUMBER + "\")' >上傳封面</button>",
                            uploadVideoButton = "<button onclick='location.replace(\"Z-0_upload_video.php?video_id=" + data.record.SERIAL_NUMBER + "\")' >上傳影片</button>",
                            uploadBookButton = "<button onclick='location.replace(\"Z-0_upload_book.php?video_id=" + data.record.SERIAL_NUMBER + "\")' >上傳教材</button>",
                            ret = (canEdit) ? '<span>' + uploadImagesButton + uploadVideoButton + uploadBookButton + '</span>' : '';
                        return ret;

                    },
                    width: '20%',
                    list: true,
                    sorting: false,
                    edit: false,
                    create: false
                }
            }
        });
    }

}());