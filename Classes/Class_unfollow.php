<?php
require_once "session.php";
class Unfollow
{
    private $tag;
    private $database;
    public function __construct($tag, $database)
    {
        $this->tag = $tag;
        $this->database = $database;
    }
    public function doUnfollow()
    {
        returnJsonStatus($this->database->unfollowTag
        (getCurUserId(), $this->tag));
    }
}