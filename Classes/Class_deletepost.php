<?php
require_once "session.php";
class PostDelete
{
    private $post_id;
    private $database;

    public function __construct($post_id, $database)
    {
        $this->post_id = $post_id;
        $this->database = $database;
    }
    public function doDelete()
    {
        returnJsonStatus($this->database->deletePost($this->post_id,
            getCurUserId()));
    }
}