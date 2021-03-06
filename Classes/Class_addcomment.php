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
