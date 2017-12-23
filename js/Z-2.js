/*jslint browser: true, debug: true, maxerr: 50 */
(function () {
    'use strict';

    /*global $, canEdit */


    $(window).load(function () {
        $("a[type=button], button").button();

        if (canEdit) {
            $("#VideoListContainer , #VideoListContainer1, #VideoListContainer2").sortable({
                scroll: true
            }).disableSelection().on('sortupdate', function () {
                var newOrder0 = $("#VideoListContainer").sortable('toArray').toString(),
                    newOrder1 = $("#VideoListContainer1").sortable('toArray').toString(),
                    newOrder2 = $("#VideoListContainer2").sortable('toArray').toString();
                $.get('ajax/Z-2/save_order.php?ts=' + new Date().getMilliseconds(), {
                    order0: newOrder0,
                    order1: newOrder1,
                    order2: newOrder2
                });
            });


            $('#select_area1, #select_area2').multiselect({
                noneSelectedText: '選擇影片',
                checkAllText: '全部選擇',
                uncheckAllText: '取消選擇',
                minWidth: 200,
                height: 500,
                selectedText: '選擇(#)支影片'
            }).multiselectfilter({
                label: '搜尋',
                placeholder: '關鍵字'
            }).on("multiselectclick", function (event, ui) {
                //console.log(ui);
                var id = ui.value,
                    title = ui.text,
                    cover = '<img src="/DATA/images/cover/' + id + '.png" />',
                    $list = (event.target.id === "select_area1") ? $("#VideoListContainer1") : $("#VideoListContainer2");

                if (ui.checked) {
                    $list.append("<li id='sort_" + id + "' class='ui-widget-content ui-corner-tr' data-cover='" + cover + "' ><h4 class='ui-widget-header'>" + title + "</h4></li>");
                } else {
                    $list.children("#sort_" + id).remove();
                }
                $list.trigger('sortupdate');

            });

            $("#button1").click(function () {
                $.get('ajax/Z-2/save_name.php?ts=' + new Date().getMilliseconds(), {
                    id: "1",
                    name: $("#name_area1").val()
                });
            });
            $("#button2").click(function () {
                $.get('ajax/Z-2/save_name.php?ts=' + new Date().getMilliseconds(), {
                    id: "2",
                    name: $("#name_area2").val()
                });
            });
        } else {
            $("#button1, #button2").attr("disabled", "disabled");
            $("#name_area1, #name_area2").attr("disabled", "disabled");
            $("h1:first").html("熱門設定(<span style='color:red'>唯讀</span>)");
        }

    });

    function removeFromList($id, target) {

        var $sortable = $(target).parent().parent().parent();
        $(target).parent().parent().remove();
    }

}());