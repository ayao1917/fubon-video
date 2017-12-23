
var chtMessages= {
    serverCommunicationError: '連線失敗.',
    loadingMessage: '載入中...',
    noDataAvailable: '無資料!',
    addNewRecord: '增加系列',
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

$(window).load(function() {

    initTagList();
    loadTagList();


});


function loadTagList() {
    $('#TagListContainer').jtable('load');
}

function afterSuccess()  {
    $('#UploadForm').resetForm();  // reset form
    $('#SubmitButton').removeAttr('disabled'); //enable submit button
}



function initTagList() {
        c = (canCreate)?'ajax/Z-4/CreateTag.php':null;
        d = (canCreate)?'ajax/Z-4/DeleteTag.php':null;
        u = (canEdit)?'ajax/Z-4/UpdateTag.php':null;
	
	$('#TagListContainer').jtable({
	    title: '系列列表',
	    messages: chtMessages,
	    paging: true, //Enable paging
	    pageSize: 20, //Set page size (default: 10)
	    sorting: true, //Enable sorting
	    defaultSorting: 'ID DESC', //Set default sorting
	    actions: {
	        listAction: 'ajax/Z-4/TagList.php',
	        deleteAction: d,
	        updateAction: u,
	        createAction: c
	    },
	    recordsLoaded: function(event, data) {
	    	$("button").button();
	    },
	    recordUpdated: function(event, data) {
	    	loadTagList();
	    },
	    fields: {
	        ID: {
	            key: true,
	            create: false,
	            edit: false,
	            list: false
	        },	        
                TITLE: {
                    title: '名稱',
                    width: '20%',
                    create: true,
                    edit: true,
                    list: true
                },
	        ICON_NORMAL: {
	            title: '圖示',
	            width: '20%',

		    input: function (data) {
			filename = data.record.ICON_NORMAL;
                        if (filename != null) {
			    return  '<input type="hidden" name="ICON_NORMAL" value="'  + data.record.ICON_NORMAL + '"/><img src="'+ filename + '?'+Math.random() + ' " width="100" />';
			}
		    },
		    display: function (data) {
			filename = data.record.ICON_NORMAL;
                        if (filename != null) {
			    return  '<img src="'+ filename + '?'+Math.random() + ' " width="100" />';
			}
		    },

	            edit: false,
	            create: false,
	            list: true
	        },
	        ICON_PRESS: {
	            title: 'ICON(按下狀態)',
	            width: '20%',

		    input: function (data) {
			filename = data.record.ICON_PRESS;
                        if (filename != null) {
			    return  '<input type="hidden" name="ICON_PRESS" value="'  + data.record.ICON_PRESS + '"/><img src="'+ filename + '?'+Math.random() + ' " width="100" />';
			}
		    },
		    display: function (data) {
			filename = data.record.ICON_PRESS;
                        if (filename != null) {
			    return  '<img src="'+ filename + '?'+Math.random() + ' " width="100" />';
			}
		    },

	            edit: false,
	            create: false,
	            list: false
	        },
	        ENABLED: {
	            title: '啟用',
	            width: '8%',
	            list: true,
	            defaultValue: '0',
	            create: true,
	            edit: true,
	            type: 'radiobutton',
                    width: '5%',
	            options: { '0': '否', '1': '是' }
	        },	        
	        WEIGHT: {
	            title: '權重',
	            width: '8%',
                    options: {'0':0, '1':1, '2':2, '3':3, '4':4, '5':5, '6':6, '7':7, '8':8, '9':9 },
	            create: true,
	            edit: true,
	            list: true
	        },
                ACTION: {
                        title: '操作',
                        display: function(data) {
                                uploadButton =  "<button onclick='location.replace(\"Z-4_upload.php?tag_id="+ data.record.ID + "\")' >上傳圖示</button>";
                                tagButton =  "<button onclick='location.replace(\"Z-4_tag.php?tag_id="+ data.record.ID + "\")' >選擇影片</button>";
                                ret = (canEdit)?'<span>'+ uploadButton + tagButton +'</span>':'無編輯權限';
                                return ret;

                        },
                        width: '20%',
                        list: true,
			sorting:false,
                        edit: false,
                        create:false
                }
	    }
	});
 
 
}
