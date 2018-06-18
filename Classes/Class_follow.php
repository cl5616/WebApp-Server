<?php
require_once "session.php";
class Follow
{
    private $tag;
    private $database;
    public function __construct($tag, $database)
    {
        $this->tag = $tag;
        $this->database = $database;
    }
    public function doFollow()
    {
        returnJsonStatus($this->database->followTag
            (getCurUserId(), $this->tag));
    }
}