<?php
final class MySqlDatabase
{
    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new MySqlDatabase();
        }
        return self::$instance;
    }
    //singleton database

    const DB_SERVER = "localhost";
    const DB_USERNAME = "8e3aa7ce047cce83c88378ea714ff2e92b92f03fd2bbf0c6a9cca0460ccabb7";
    const DB_PASSWORD = "28495285ff313e06e171a6854b54070b90e5305658d3c7ac876acdaf187ac8d";
    const DB_NAME = "webapps";
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

        $query = "INSERT INTO posts ".
            "(user_id,post_time,picture,content,category,deleted,anonymous,view_num,like_num)".
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


}