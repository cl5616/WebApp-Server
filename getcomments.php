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

//test----------
$_GET["msg_id"] = "11";
$_GET["offset"] = 0;
$_GET["limit"] = 10;
//test----------

dieIfEmpty($_GET, "msg_id");
$offset = toNum($_GET, "offset");
$limit = toNum($_GET, "limit");
$msg_id = (int)$_GET["msg_id"];

$get_comment = new CommentGetter($msg_id, $offset, $limit
                ,PostGREDatabase::getInstance());
$get_comment->getComments();
