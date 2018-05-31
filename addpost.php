<?php
    require "database.php";
    require "session.php";
    class PostUploader
    {
        private $category;
        private $content;
        private $database;

        public function __construct($category, $content, $database)
        {
            $this->category = $category;
            $this->content = $content;
            $this->database = $database;
        }
        public function doPost()
        {
            $user_id = getCurUserId();
            $ret = $this->database->postMsg($this->content, $this->category, $user_id);
            $ret = $ret ? "true" : "false";
            $error_msg = $ret ? "" : ",\"error\":\"database query error\"";
            echo "{\"status\":".$ret.$error_msg."}";
        }
    }

    $mysql_db = MySqlDatabase::getInstance();

    if (!isset($_POST["category"]))
    {
        ?>
        {"status":false, "error":"no category specified"}
        <?php
        die();
    }
    $content = isset($_POST["content"]) ? $_POST["content"] : "";
    $uploader = new PostUploader($_POST["category"], $content, $mysql_db);
    $uploader->doPost();