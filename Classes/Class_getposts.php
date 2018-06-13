<?php
require_once "database.php";
require_once "session.php";

class PostGetter
{
    private $category;
    private $database;
    private $offset;
    private $limit;
    private $sort;

    public function __construct($category, $offset, $limit, $sort, $database)
    {
        $this->category = $category;
        $this->database = $database;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->sort = $sort;
    }
    public function getPosts()
    {
        switch ($this->sort)
        {
            case 1:
                $json = $this->database->
                    getPosts($this->category, $this->offset,
                    $this->limit, "like_num");
                break;
            case 2:
                $json = $this->database->
                getPosts($this->category, $this->offset,
                    $this->limit, "view_num");
                break;
            default:
                $json = $this->database->
                    getPosts($this->category, $this->offset,
                    $this->limit, "post_time");
                break;
        }
        if ($json === false)
            dieWithErrorMsg("database error");
        else
            echo json_encode($json);
    }
}
