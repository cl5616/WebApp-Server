#!/usr/bin/php
<?php
    require_once "database.php";
    require_once "session.php";
    require_once "PostContent.php";
    class PostUploader extends ContentUploader
    {
        private $category;
        private $picture;
        private $anonymous;

        public function __construct($category, $content, $picture, $anonymous, $database)
        {
            parent::__construct($content, $database);
            $this->category = $category;
            $this->picture = $picture;
            $this->anonymous = strcmp($anonymous, "1") === 0;
        }
        public function doPost()
        {
            $user_id = getCurUserId();
            $ret = $this->database->postMsg($this->content,
                $this->category, $user_id, $this->picture,
                $this->anonymous);
            self::returnJsonStatus($ret);
        }
    }

    $db = PostGREDatabase::getInstance();

    //test--------
    $_POST["category"]= "social";
    $_POST["content"]="hello world with pic";
    $_POST["picture"]="aaa.jpg";
    $_POST["anonymous"]="1";
    //test--------

    dieIfEmpty($_POST, "category");
    dieIfInvalidCategory($_POST["category"]);
    $content = emptyIfNotSet($_POST,"content");
    $picture = nullIfNotSet($_POST, "picture");
    $anonymous = emptyIfNotSet($_POST, "anonymous");

    $uploader = new PostUploader($_POST["category"], $content,
        $picture, $anonymous, $db);
    $uploader->doPost();
