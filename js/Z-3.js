
var chtMessages= {
    serverCommunicationError: '連線失敗.',
    loadingMessage: '載入中...',
    noDataAvailable: '無資料!',
    addNewRecord: '增加廣告',
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

    initBannerList();
    loadBannerList();


});


function loadBannerList() {
    $('#BannerListContainer').jtable('load');
}

function afterSuccess()  {
    $('#UploadForm').resetForm();  // reset form
    $('#SubmitButton').removeAttr('disabled'); //enable submit button
}


function initBannerList() {
        c = (canCreate)?'ajax/Z-3/CreateBanner.php':null;
        d = (canCreate)?'ajax/Z-3/DeleteBanner.php':null;
        u = (canEdit)?'ajax/Z-3/UpdateBanner.php':null;
	
	$('#BannerListContainer').jtable({
	    title: '廣告列表',
	    messages: chtMessages,
	    paging: true, //Enable paging
	    pageSize: 20, //Set page size (default: 10)
	    sorting: true, //Enable sorting
	    defaultSorting: 'ID DESC', //Set default sorting
	    actions: {
	        listAction: 'ajax/Z-3/BannerList.php',
	        deleteAction: d,
	        updateAction: u,
	        createAction: c
	    },
	    recordsLoaded: function(event, data) {
	    	$("button").button();
	    },
	    recordUpdated: function(event, data) {
	    	loadBannerList();
	    },
	    fields: {
	        ID: {
                    title: 'ID',
	            key: true,
		    input: function (data) {
			    return  '<input type="hidden" name="ID" value="'  + data.record.ID + '"/><span>'+ data.record.ID +'</span>';
		    },
	            create: false,
	            edit: false,
	            list: false
	        },	        
	        BANNER: {
	            title: 'BANNER',
	            width: '20%',

		    input: function (data) {
			filename = data.record.BANNER;
			ret =  ((filename!="") && (filename != null))?'<img src="'+ filename + '?'+Math.random() + ' " width="300" />':'<span>尚未上傳</span>';
			    return  '<input type="hidden" name="BANNER" value="'  + data.record.BANNER + '"/>'+ret;
		    },
		    display: function (data) {
			filename = data.record.BANNER;
			ret =  ((filename!="") &&(filename !=null))?'<img src="'+ filename + '?'+Math.random() + ' " width="300" />':'<span>尚未上傳</span>';
                        return ret;
		    },

	            edit: true,
	            create: false,
	            list: true
	        },
	        ENABLED: {
	            title: '啟用',
	            width: '8%',
	            list: true,
	            defaultValue: 0,
	            create: true,
	            edit: true,
	            type: 'radiobutton',
                    width: '5%',
	            options: { '0': '否', '1': '是' }
	        },	        
	        LINK: {
	            title: 'LINK',
	            create: false,
	            edit: false,
	            list: false
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
                                uploadButton =  "<button onclick='location.replace(\"Z-3_upload.php?banner_id="+ data.record.ID + "\")' >上傳廣告</button>";
                                ret = (canEdit)?'<span>'+uploadButton +'</span>':'無編輯權限';
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
