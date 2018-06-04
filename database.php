#!/usr/bin/php
<?php

interface Database
{
    public function postMsg($content, $category, $user_id);
    public function postComment($msg_id, $content, $reply_id, $user_id);
    public function getPosts();
}


final class PostGREDatabase implements Database
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

    const DB_SERVER = "db.doc.ic.ac.uk";
    const DB_PORT = 5432;
    const DB_USERNAME = "g1727111_u";
    const DB_PASSWORD = "kows5bepvO";
    const DB_NAME = "g1727111_u";
    const DB_POSTS_TAB = "posts";
    const DB_COMMENTS_TAB = "comments";
    //constants


    private $conn;
    private function __construct()
    {
        $i = "fuck";
        $this->conn = pg_connect(
            "host=".self::DB_SERVER.
            " dbname=".self::DB_NAME.
            " user=".self::DB_USERNAME.
            " password=".self::DB_PASSWORD.
            " port=".self::DB_PORT);
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
        $query = "SELECT id,user_id,content FROM ".self::DB_POSTS_TAB;
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
