#!/usr/bin/php
<?php
    require_once "Class_addcomment.php"


    $db = PostGREDatabase::getInstance();

    //test
    $_POST["msg_id"] = "1";
    if (!isset($_POST["msg_id"]))
    {
        ContentUploader::returnJsonErrorDie("no msg_id being specified");
    }
    $msg_id = intval($_POST["msg_id"]);

    $reply_id = isset($_POST["reply_id"]) ? intval($_POST["reply_id"]) : "null";
    $content = isset($_POST["content"]) ? $_POST["content"] : "";
    $uploader = new CommentUploader($msg_id, $content, $reply_id, $db);
    $uploader->doComment();
