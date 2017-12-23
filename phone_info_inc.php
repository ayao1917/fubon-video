<?php

function getItemListHTML($list) {
    $html='';

    foreach($list as $item) {
        $html .= '<li class="v_item item_'.$item['id'].'">';
        $html .= '<div class="v_cover" style="background-image: url(DATA/images/cover150/' . $item['id']. '.png);"></div>';
        $html .= '<div class="v_desc">' . $item["title"]  .'<br/>影片長度：' . $item["duration"] . '</div>';
        //$html .= '<div class="v_action" onClick="onMenuClicked(\'video\', \'video\', ' . $item['id'] .');"></div>';
        $html .= '<div class="v_action" data-id="'.$item['id'] .'"></div>';
        $html .= '</li>';
    }

    return $html;
}

?>
