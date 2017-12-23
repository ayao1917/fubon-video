

$(document).ready(function() {
    $('#scheduled-time').datetimepicker({
        format:'Y-m-d H:i'
    });
    $("button").button();

    if ($("#scheduled_at").text() == "無") {
        $("#schedule-cancel").hide();
    } 
    $("#schedule-cancel").click(function() {
        location.href= "Z-5_publish_management.php?cancel";
    });

    $("#push-assets").click(function() {
        location.href= "Z-5_publish_management.php?push";
    });

    $("#schedule-confirm").click(function() {
        var schedule = $("#scheduled-time").val();
        if (schedule!=="") {
            location.href= "Z-5_publish_management.php?schedule=" + schedule;
        }
    });
    $("#publish-now").click(function() {
        location.href= "Z-5_publish_management.php?schedule=now";
    });
});

