<?php

class FollowChecker
{
    private $user_id;
    private $tag;
    private $database;
    public function __construct($user_id, $tag, $database)
    {
        $this->user_id = $user_id;
        $this->tag = $tag;
        $this->database = $database;
    }
    public function doCheckFollow()
    {
        $result = $this->database->ifFollowed($this->user_id, $this->tag);
        if ($result === false)
        {
            dieWithErrorMsg("error in database query");
        }
        else
        {
            $res = $result == 0 ? "false" : "true";
            echo "{\"status\":true,\"follow\":$res}";
        }
    }
}