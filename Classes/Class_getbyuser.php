<?php
require_once "database.php";
require_once "session.php";

class PostGetterByUser
{
    private $database;
    private $offset;
    private $limit;
    private $sort;
    private $userid;

    public function __construct($userid, $offset, $limit, $sort, $database)
    {
        $this->database = $database;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->userid = $userid;
    }
    public function getPosts()
    {
        switch ($this->sort)
        {
            case 1:
                $json = $this->database->getUserPosts(
                    $this->userid, $this->offset,
                    $this->limit, null, "like_num");
                break;
            case 2:
                $json = $this->database->getUserPosts(
                    $this->userid, $this->offset,
                    $this->limit, null, "view_num");
                break;
            default:
                $json = $this->database->getUserPosts(
                    $this->userid, $this->offset,
                    $this->limit, null, "post_time");
                break;
        }
        if ($json === false)
            dieWithErrorMsg("database error");
        else
            echo json_encode($json);
    }
}
