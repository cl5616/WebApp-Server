<?php
final class PostGREDatabase
{
    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new PostGREDatabase();
        }
        return self::$instance;
    }
    //singleton database

    const DB_SERVER = "localhost";
    const DB_USERNAME = "8e3aa7ce047cce83c88378ea714ff2e92b92f03fd2bbf0c6a9cca0460ccabb7";
    const DB_PASSWORD = "28495285ff313e06e171a6854b54070b90e5305658d3c7ac876acdaf187ac8d";
    const DB_NAME = "webapps";
    const DB_POSTS_TAB = "posts";
    const DB_COMMENTS_TAB = "comments";
    //constants


    private $conn;
    private function __construct()
    {
        $this->conn = pg_connect(
            "host=".self::DB_SERVER.
            " dbname=".self::DB_NAME.
            " user=".self::DB_USERNAME.
            " password=".self::DB_PASSWORD);
        //The connection will be closed automatically when the script ends.
        if (!$this->conn)
        {
            //die directly if there is any error
            self::dieWithErrorMsg("database connection error, please contact admin");
        }
    }

    private static function dieWithErrorMsg($msg)
    {
        echo "{\"status\":false, \"error\":\"".$msg."\"}";
        die();
    }

    public function postMsg($content, $category, $user_id)
    {
        $timestamp = (new DateTime())->getTimestamp();

        $query = "INSERT INTO ".self::DB_POSTS_TAB.
            " (user_id,post_time,picture,content,category,deleted,anonymous,view_num,like_num)".
            " VALUES (".$user_id.",".$timestamp.",'todo',$1,$2,false,false,0,0)";
        $result = pg_prepare($this->conn, "post_msg", $query);
        if (!$result)
        {
            return false;
        }
        $result = pg_execute($this->conn, "post_msg", [$content, $category]);
        if (!$result)
        {
            return false;
        }
        return true;
    }
    public function postComment($msg_id, $content, $reply_id, $user_id)
    {
        $timestamp = (new DateTime())->getTimestamp();
        $query ="INSERT INTO ".self::DB_COMMENTS_TAB.
            " (poster_id,msg_id,comment_time,content,reply_id)".
            " VALUES (".$user_id.",".$msg_id.",".$timestamp.",$1,".$reply_id.")";
        $result = pg_prepare($this->conn, "post_comment", $query);
        if (!$result)
        {
            return false;
        }
        $result = pg_execute($this->conn, "post_comment", array($content));
        if (!$result)
        {
            return false;
        }
        return true;
    }
    public function getPosts()
    {
        $query = "SELECT msg_id,poster_id,content FROM ".self::DB_POSTS_TAB;
        $result = pg_query($this->conn, $query);
        if (!$result)
        {
            self::dieWithErrorMsg("problem with database query, please try again");
        }
        $ret = array();
        while ($row = pg_fetch_row($result))
        {
            $one_row = array("msg_id"=>$row[0], "poster_id"=>$row[1], "content"=>$row[2]);
            array_push($ret, $one_row);
        }
        return $ret;
    }

}