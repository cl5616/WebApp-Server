<?php
    /*
     * require "database.php";
    require "session.php";
     */
    require "getposts.php";
    require "PostContent.php";
    class PostUploader extends ContentUploader
    {
        private $category;

        public function __construct($category, $content, $database)
        {
            parent::__construct($content, $database);
            $this->category = $category;
        }
        public function doPost()
        {
            $user_id = getCurUserId();
            $ret = $this->database->postMsg($this->content, $this->category, $user_id);
            self::returnJsonStatus($ret);
        }
    }
/*
    $db = PostGREDatabase::getInstance();

    if (!isset($_POST["category"]))
    {
        ?>
        {"status":false, "error":"no category specified"}
        <?php
        die();
    }
    $content = isset($_POST["content"]) ? $_POST["content"] : "";
    $uploader = new PostUploader($_POST["category"], $content, $db);
    $uploader->doPost();

*/