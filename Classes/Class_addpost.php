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
