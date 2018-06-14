<?php
class PostSearcher
{
    private $query;
    private $offset;
    private $limit;
    private $database;

    public function __construct($query, $offset, $limit, $database)
    {
        $this->query = $query;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->database = $database;
    }
    public function doSearch()
    {
        $json = $this->database->searchPosts(
            $this->query, $this->offset, $this->limit);

        if ($json === false)
            dieWithErrorMsg("database error");
        else
            echo json_encode($json);
    }
}