<?php
class IncrementPost
{
    private $post_id;
    private $database;
    private $column;

    public function __construct($post_id, $database, $column)
    {
        $this->post_id = $post_id;
        $this->database = $database;
        $this->column = $column;
    }
    public function doAdd()
    {
        $this->database->addOne($this->post_id, $this->column);
    }
}

