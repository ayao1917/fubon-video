<?php

include_once('config.php');
include_once('utils.php');
include_once('global.php');

class Cache{

    function __construct() {
        checkDir(__CACHE_DIR__);
    }

    function write($key, $value) {

        file_put_contents(__CACHE_DIR__."/$key.dat", $value);

    }

    function read($key) {
        if (file_exists(__CACHE_DIR__."/$key.dat")) return file_get_contents(__CACHE_DIR__."/$key.dat"); else return "";
    }


    function updateAllCache() {
/*
        include_once('class_banner.php');
        include_once('class_category.php');
        include_once('class_topic.php');
        include_once('class_link.php');
        include_once('class_newordername.php');
        include_once('class_book.php');
        include_once('class_topiclist.php');
        include_once('class_announcement.php');
        include_once('class_form.php');
        include_once('class_event.php');

        $update_list = array("Banner", "Category", "Topic", "Links", "NewOrderName", "Book", "TopicList", "Announcement", "Form", "Event");

        foreach($update_list as $target) {

            $db = new $target();
            $db->init();
            $db->updateCache();
        }
*/
    }
}

?>
