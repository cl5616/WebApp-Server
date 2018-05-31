<?php
    require "database.php";
    require "session.php";
    require "PostContent.php";
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
    if (!isset($_POST["msg_id"]))
    {
        ContentUploader::returnJsonErrorDie("no msg_id being specified");
    }
    if (!isset($_POST["reply_id"]))
    {
        ContentUploader::returnJsonErrorDie("no reply_id being specified");
    }
    $content = isset($_POST["content"]) ? $_POST["content"] : "";
    $uploader = new CommentUploader($_POST["msg_id"], $content, $_POST["reply_id"], $db);
    $uploader->doComment();
