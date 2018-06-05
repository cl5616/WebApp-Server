
<?php

require_once "utils.php";

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
    const DB_QUERY_PROBLEM = "problem with database query, please try again";
    const DB_SERVER = "db.doc.ic.ac.uk";
    const DB_PORT = 5432;
    const DB_USERNAME = "g1727111_u";
    const DB_PASSWORD = "kows5bepvO";
    const DB_NAME = "g1727111_u";
    const DB_POSTS_TAB = "posts";
    const DB_COMMENTS_TAB = "comments";
    const DB_USER_TAB = "users";
    //constants


    private $conn;
    private function __construct()
    {
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
            dieWithErrorMsg("database connection error, please contact admin");
        }
    }

    public function postMsg($content, $category, $user_id)
    {
        $query = "INSERT INTO ".self::DB_POSTS_TAB.
            " (user_id,post_time,picture,content,category,deleted,anonymous,view_num,like_num)".
            " VALUES (".$user_id.",CURRENT_TIMESTAMP,'todo',$1,$2,B'0',B'0',0,0)";
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
        $query ="INSERT INTO ".self::DB_COMMENTS_TAB.
            " (poster_id,msg_id,comment_time,content,reply_id)".
            " VALUES (".$user_id.",".$msg_id.",CURRENT_TIMESTAMP,$1,".$reply_id.")";
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
        $query = "SELECT id,user_id,content,picture FROM ".self::DB_POSTS_TAB;
        $result = pg_query($this->conn, $query);
        if (!$result)
        {
            dieWithErrorMsg(self::DB_QUERY_PROBLEM);
        }
        $ret = array();
        while ($row = pg_fetch_row($result))
        {
            $one_row = array("msg_id"=>(int)$row[0],
                "poster_id"=>(int)$row[1],
                "content"=>$row[2],
                "picture"=>$row[3]);
            array_push($ret, $one_row);
        }
        return $ret;
    }
    public function ifEmailExist($email)
    {
        $query = "SELECT email FROM users WHERE email = $1";
        $result = pg_prepare($this->conn,"email_exist" , $query);
        if (!$result)
        {
            dieWithErrorMsg(self::DB_QUERY_PROBLEM);
        }
        $result = pg_execute($this->conn, "email_exist", array($email));
        if (!$result)
        {
            dieWithErrorMsg(self::DB_QUERY_PROBLEM);
        }
        if ($row = pg_fetch_row($result))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function doRegister($email,
                               $password_hash, $salt,
                               $nickname, $introduction)
    {
        $query = "INSERT INTO ".self::DB_USER_TAB.
            " (email,password,salt,nickname,introduction)".
            " VALUES ($1,'".$password_hash."','".$salt."',$2,$3)";
        $result = pg_prepare($this->conn, "register_user", $query);
        if (!$result)
        {
            return false;
        }
        $result = pg_execute($this->conn, "register_user", array(
            $email, $nickname, $introduction));
        if (!$result)
        {
            return false;
        }
        return true;
    }
    public function getEmailPswInfo($email)
    {
        $query = "SELECT id,password,salt FROM users WHERE email = $1";
        $result = pg_prepare($this->conn, "login_user", $query);
        if (!$result)
        {
            return false;
        }
        $result = pg_execute($this->conn, "login_user", array($email));
        if (!$result)
        {
            return false;
        }
        $row = pg_fetch_row($result);
        if ($row)
        {
            return array("id"=>(int)$row[0], "password"=>$row[1], "salt"=>$row[1]);
        }
        else
        {
            return array();
        }
    }
}
