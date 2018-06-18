<?php

class GetPostByTags
{
    private $tags;
    private $database;
    private $offset;
    private $limit;
    private $sort;

    public function __construct($tags, $offset, $limit, $sort, $database)
    {
        $this->tags = $tags;
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
                $json = $this->database->getTagPosts
                ($this->tags, $this->offset,
                    $this->limit, null, "like_num");
                break;
            case 2:
                $json = $this->database->getTagPosts
                ($this->tags, $this->offset,
                    $this->limit, null, "view_num");
                break;
            case 3:
                $json = $this->database->getTagPosts
                ($this->tags, $this->offset,
                    $this->limit, null, "post_time");
                break;
            default:
                $json = $this->database->getTagPosts
                ($this->tags, $this->offset,
                    $this->limit, null, null);
                break;
        }
        if ($json === false)
            dieWithErrorMsg("database error");
        else
            echo json_encode($json);
    }
}