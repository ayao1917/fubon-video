
var chtMessages= {
    serverCommunicationError: '建立失敗.',
    loadingMessage: '載入中...',
    noDataAvailable: '無資料!',
    addNewRecord: '新增後台用戶',
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

    initManagerList();
    loadManagerList();
});


function loadManagerList() {
    $('#ManagerListContainer').jtable('load');
}

function afterSuccess()  {
    $('#UploadForm').resetForm();  // reset form
    $('#SubmitButton').removeAttr('disabled'); //enable submit button
}



function initManagerList() {
	
c = (canCreate)?'ajax/Z-11/CreateManager.php':null;
d = (canCreate)?'ajax/Z-11/DeleteManager.php':null;
u = (canEdit)?'ajax/Z-11/UpdateManager.php':null;

	$('#ManagerListContainer').jtable({
	    title: '後台帳號列表',
	    messages: chtMessages,
	    paging: true, //Enable paging
	    pageSize: 20, //Set page size (default: 10)
	    sorting: false, //Enable sorting
	    defaultSorting: 'ID DESC', //Set default sorting
            deleteConfirmation: function(data) {
                
                data.cancel =  (data.record.ID=="root") ;
                if (data.cancel) alert("不能刪除本記錄");
            },
	    actions: {
	        listAction: 'ajax/Z-11/ManagerList.php',
	        deleteAction: d,
	        updateAction: u,
	        createAction: c 
	    },
            formCreated: function (event, data) {
                data.form.find('input[name="ID"]').addClass('validate[required]');
                data.form.validationEngine();
            },

            formSubmitting: function(event, data) {
                return data.form.validationEngine('validate');
            },
	    recordUpdated: function(event, data) {
	    	loadManagerList();
	    },
	    fields: {
	        ID: {
	            title: 'ID',
	            key: true,
	            create: true,
	            edit: true,
	            list: true
	        },	        
	        PASSWORD: {
	            title: '密碼',
	            type: 'password',
	            create: false,
	            edit: false,
	            list: false
	        },	        
	        VIDEO: {
	            title: '影片管理',
	            type: 'radiobutton',
	            defaultValue: 3,
	            options: { '0': '無', '1': '瀏覽', '2': '編輯', '3': '增刪' },
	            width: '8%',
	            edit: true,
	            create: true,
	            list: true
                },
	        CATEGORY: {
	            title: '分類與系列管理',
	            type: 'radiobutton',
	            defaultValue: 3,
	            options: { '0': '無', '1': '瀏覽', '2': '編輯', '3': '增刪' },
	            width: '8%',
	            edit: true,
	            create: true,
	            list: true
                },
	        FRONTPAGE: {
	            title: '本月新片管理',
	            type: 'radiobutton',
	            defaultValue: 3,
	            options: { '0': '無', '1': '瀏覽', '2': '編輯', '3': '增刪' },
	            width: '8%',
	            edit: true,
	            create: true,
	            list: true
                },
	        BANNER: {
	            title: '廣宣區',
	            type: 'radiobutton',
	            defaultValue: 3,
	            options: { '0': '無', '1': '瀏覽', '2': '編輯', '3': '增刪' },
	            width: '8%',
	            edit: true,
	            create: true,
	            list: true
                },
	        ANNOUNCEMENT: {
	            title: '公告區',
	            type: 'radiobutton',
	            defaultValue: 3,
	            options: { '0': '無', '1': '瀏覽', '2': '編輯', '3': '增刪' },
	            width: '8%',
	            edit: false,
	            create: false,
	            list: false
                },
	        REPORT: {
	            title: '報表',
	            type: 'radiobutton',
	            defaultValue: 3,
	            options: { '0': '無', '1': '瀏覽', '2': '編輯', '3': '增刪' },
	            width: '8%',
	            edit: true,
	            create: true,
	            list: true
                },
	        SYSTEM: {
	            title: '後台帳號管理',
	            type: 'radiobutton',
	            defaultValue: 3,
	            options: { '0': '無', '1': '瀏覽', '2': '編輯', '3': '增刪' },
	            width: '8%',
	            edit: true,
	            create: true,
	            list: true
                }
	    }
	});
 
 
}
