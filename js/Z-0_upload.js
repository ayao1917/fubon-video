$(document).ready(function() {

    $("button, input").button();
    $("#UploadForm1").ajaxForm({
        target: "#output1",
        beforeSubmit: function() { $("#output1").html('<div style="padding:10px"><img src="images/ajax-loader.gif" alt="請稍候"/> <span>上傳中...</span></div>');},
        success:  afterSuccess //call function after success
    });
    $("#UploadForm2").ajaxForm({
        target: "#output2",
        beforeSubmit: function() { $("#output2").html('<div style="padding:10px"><img src="images/ajax-loader.gif" alt="請稍候"/> <span>上傳中...</span></div>');},
        success:  afterSuccess //call function after success
    });
    $("#UploadForm3").ajaxForm({
        target: "#output3",
        beforeSubmit: function() { $("#output3").html('<div style="padding:10px"><img src="images/ajax-loader.gif" alt="請稍候"/> <span>上傳中...</span></div>');},
        success:  afterSuccess //call function after success
    });
    $("#UploadForm4").ajaxForm({
        target: "#output4",
        beforeSubmit: function() { $("#output4").html('<div style="padding:10px"><img src="images/ajax-loader.gif" alt="請稍候"/> <span>上傳中...</span></div>');},
        success:  afterSuccess //call function after success
    });
    $("#UploadForm5").ajaxForm({
        target: "#output5",
        beforeSubmit: function() { $("#output5").html('<div style="padding:10px"><img src="images/ajax-loader.gif" alt="請稍候"/> <span>上傳中...</span></div>');},
        success:  afterSuccess //call function after success
    });
});
function showResponse(responseText, statusText, xhr, $form)  { 
    alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
        '\n\nThe output div should have already been updated with the responseText.');

}

function afterSuccess()  {
    $('#UploadForm1, #UploadForm2, #UploadForm3, #UploadForm4, #UploadForm5').resetForm();  // reset form
    $('#SubmitButton1, #SubmitButton2, #SubmitButton3, #SubmitButton4, #SubmitButton5').removeAttr('disabled'); //enable submit button
}
