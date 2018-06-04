#!/usr/bin/php
<?php
    require "database.php";
    require "session.php";

    class PostGetter
    {
        private $category;
        private $database;
        private $preference;
        public function __construct($category, $preference, $database)
        {
            $this->category = $category;
            $this->preference = $preference;
            $this->database = $database;
        }
        public function getPosts()
        {
            echo json_encode($this->database->getPosts(getCurUserId()));
        }
    }


    $preference = isset($_GET["preference"]) ? $_GET["preference"] : "time";
    $category = isset($_GET["category"]) ? $_GET["category"] : "general";
    $getter = new PostGetter($category, $preference,
        PostGREDatabase::getInstance());
    $getter->getPosts();
