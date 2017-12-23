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
                var newOrder1 = $("#VideoListContainer1").sortable('toArray').toString(),
                    newOrder2 = $("#VideoListContainer2").sortable('toArray').toString();
                $.get('ajax/Z-6/save_order.php?ts=' + new Date().getMilliseconds(), {
                    category_order: newOrder1,
                    tag_order: newOrder2
                });
            });
        } else {
            $("#button1, #button2").attr("disabled", "disabled");
            $("#name_area1, #name_area2").attr("disabled", "disabled");
            $("h1:first").html("排序設定(<span style='color:red'>唯讀</span>)");
        }

    });
}());