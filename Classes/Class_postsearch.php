<?php
class PostSearcher
{
    private $query;
    private $offset;
    private $limit;
    private $database;
    private $category;
    private $orderval;

    public function __construct($query, $offset, $limit,
                                $category, $orderval, $database)
    {
        $this->query = $query;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->database = $database;
        $this->category = $category;
        $this->orderval = $orderval;
    }
    public function doSearch()
    {
        switch ($this->orderval)
        {
            case 1:
                $json = $this->database->searchPosts(
                    $this->query, $this->offset,
                    $this->limit, $this->category, "like_num");
                break;
            case 2:
                $json = $this->database->searchPosts(
                    $this->query, $this->offset,
                    $this->limit, $this->category, "view_num");
                break;
            case 3:
                $json = $this->database->searchPosts(
                    $this->query, $this->offset,
                    $this->limit, $this->category, "post_time");
                break;
            default:
                $json = $this->database->searchPosts(
                    $this->query, $this->offset,
                    $this->limit, $this->category, null);
                break;
        }
        if ($json === false)
            dieWithErrorMsg("database error");
        else
            echo json_encode($json);
    }
}