<?php
require_once "session.php";
class Canceller
{
    private $post_id;
    private $database;
    private $counter_name;

    public function __construct($post_id, $database, $counter_name)
    {
        $this->post_id = $post_id;
        $this->database = $database;
        $this->counter_name = $counter_name;
    }
    public function doAdd()
    {
        returnJsonStatus($this->database->cancel(getCurUserId(),
            $this->post_id, $this->counter_name));
    }
}