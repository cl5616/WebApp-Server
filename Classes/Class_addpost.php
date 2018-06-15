<?php
require_once "database.php";
require_once "session.php";
require_once "PostContent.php";
class PostUploader extends ContentUploader
{
    private $category;
    private $picture;
    private $anonymous;
    private $title;
    private $tags;

    public function __construct($category, $content, $picture,
                                $anonymous, $title, $tags,
                                $database)
    {
        parent::__construct($content, $database);
        $this->category = $category;
        $this->picture = $picture;
        $this->anonymous = strcmp($anonymous, "1") === 0;
        $this->title = $title;
        $this->tags = $tags;
    }
    public function doPost()
    {
        $user_id = getCurUserId();
        $ret = $this->database->postMsg($this->content,
            $this->category, $user_id, $this->picture,
            $this->anonymous, $this->tags, $this->title);
        self::returnJsonStatus($ret);
    }
}
