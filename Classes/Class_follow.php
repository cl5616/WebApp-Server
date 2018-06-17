<?php
require_once "session.php";
class Follow
{
    private $userid;
    private $database;
    public function __construct($userid, $database)
    {
        $this->userid = $userid;
        $this->database = $database;
    }
    public function doFollow()
    {
        returnJsonStatus($this->database->followUser
            (getCurUserId(), $this->userid));
    }
}