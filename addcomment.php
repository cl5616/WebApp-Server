#!/usr/bin/php
<?php
    require_once "database.php";
    require_once "session.php";
    require_once "PostContent.php";
    class CommentUploader extends ContentUploader
    {
        private $msg_id;
        private $reply;

        public function __construct($msg_id, $content, $reply, $database)
        {
            parent::__construct($content, $database);
            $this->msg_id = $msg_id;
            $this->reply = $reply;
        }
        public function doComment()
        {
            $ret = $this->database->postComment($this->msg_id,
                $this->content, $this->reply, getCurUserId());
            parent::returnJsonStatus($ret);
        }
    }


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
