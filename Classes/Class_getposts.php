<?php
require_once "database.php";
require_once "session.php";

class PostGetter
{
    private $category;
    private $database;
    private $offset;
    private $limit;

    public function __construct($category, $offset, $limit, $database)
    {
        $this->category = $category;
        $this->database = $database;
        $this->offset = $offset;
        $this->limit = $limit;
    }
    public function getPosts()
    {
        echo json_encode($this->database->
            getPosts($this->category, $this->offset, $this->limit));
    }
}
