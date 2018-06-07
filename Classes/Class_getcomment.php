<?php
require_once "utils.php";
require_once "database.php";
class CommentGetter
{
    private $msg_id;
    private $database;
    private $offset;
    private $limit;
    public function __construct($msg_id, $offset, $limit, $database)
    {
        $this->msg_id = $msg_id;
        $this->database = $database;
        $this->offset = $offset;
        $this->limit = $limit;
    }
    public function getComments()
    {
        $res = $this->database->getComments($this->msg_id,
            $this->offset, $this->limit);
        if ($res === false)
        {
            dieWithErrorMsg("database query problem");
        }
        echo json_encode($res);
    }
}
