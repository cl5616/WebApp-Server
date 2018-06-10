<?php
require_once "session.php";
class IfLike
{
    private $post_id;
    private $database;
    private $count_name;

    public function __construct($post_id, $database, $count_name)
    {
        $this->post_id = $post_id;
        $this->database = $database;
        $this->count_name = $count_name;
    }
    public function tryIfLike()
    {
        $result = $this->database->ifAddedOne(getCurUserId(),
            $this->post_id, $this->count_name);
        if ($result === false)
        {
            returnJsonStatus(false);
        }
        else
        {
            $res = $result == 0 ? "false" : "true";
            echo "{\"status\":true,\"like\":$res}";
        }
    }
}