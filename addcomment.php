#!/usr/bin/php
<?php
    require_once "./Classes/Class_addcomment.php";


    $db = PostGREDatabase::getInstance();

    //test------------
    $_POST["msg_id"] = "11";
    $_POST["content"] = "reply 17 under same post";
    $_POST["reply_id"] = "17";
    //test------------

    dieIfEmpty($_POST, "msg_id");
    $msg_id = intval($_POST["msg_id"]);

    $reply_id = isset($_POST["reply_id"]) ? intval($_POST["reply_id"]) : "null";
    $content = emptyIfNotSet($_POST, "content");
    $uploader = new CommentUploader($msg_id, $content, $reply_id, $db);
    $uploader->doComment();
