
<?php

require_once "utils.php";

interface Database
{
    public function postMsg($content, $category, $user_id, $picture, $anonymous, $title);
    public function postComment($msg_id, $content, $reply_id, $user_id);
    public function getPosts($category, $offset, $limit);
    public function ifEmailExist($email);
    public function doRegister($email,
                               $password_hash, $salt,
                               $nickname, $introduction);
}


final class PostGREDatabase implements Database
{
    private static function boolToBit($b)
    {
        return $b ? "B'1'" : "B'0'";
    }
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

    public function postMsg($content, $category, $user_id, $picture, $anonymous, $title)
    {
        $query = "INSERT INTO ".self::DB_POSTS_TAB.
            " (user_id,post_time,picture,content,category,deleted,".
            "anonymous,title)".
            " VALUES (".$user_id.",CURRENT_TIMESTAMP,$3,$1,$2,B'0',".
            self::boolToBit($anonymous).",$4)";
        $result = pg_prepare($this->conn, "post_msg", $query);
        if (!$result)
        {
            return false;
        }//works well when picture===null
        $result = pg_execute($this->conn, "post_msg", [$content, $category, $picture, $title]);
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
    private static function orderLimitOffset($time_column, $offset, $limit)
    {
        return " ORDER BY ".$time_column." DESC LIMIT ".$limit." OFFSET ".$offset;
    }
    public function getComments($msg_id, $offset, $limit)
    {
        $query = "SELECT id,poster_id,comment_time,content,reply_id FROM ".
            self::DB_COMMENTS_TAB." WHERE msg_id=".$msg_id.
            self::orderLimitOffset("comment_time",$offset, $limit);
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        $ret = array();
        while ($row = pg_fetch_row($result))
        {
            $one_row = array("id"=>(int)$row[0],
                "poster_id"=>(int)$row[1],
                "comment_time"=>$row[2],
                "content"=>$row[3],
                "reply_id"=>$row[4]);
            array_push($ret, $one_row);
        }
        return $ret;
    }
    private function getRelationCounter($msg_id, $counter_name)
    {
        $query = "SELECT COUNT(*) FROM ".$counter_name.
            "_relation WHERE msg_id=".$msg_id;
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        $row = pg_fetch_row($result);
        return (int)$row[0];
    }

    public function getPosts($category, $offset, $limit)
    {
        if ($category !== null)
        {
            $where = " WHERE category='".$category."' and deleted=B'0'";
        }
        else
        {
            $where = " WHERE deleted=B'0'";
        }
        $query = "SELECT id,user_id,content,picture,anonymous,post_time,title FROM ".
            self::DB_POSTS_TAB.$where.
            self::orderLimitOffset("post_time",$offset, $limit);

        $result = pg_query($this->conn, $query);
        if (!$result)
        {
            dieWithErrorMsg(self::DB_QUERY_PROBLEM);
        }
        $ret = array();
        while ($row = pg_fetch_row($result))
        {
            if (strcmp($row[4], "1") === 0)
            {
                $user_id = 0;
            }
            else
            {
                $user_id = (int)$row[1];
            }
            $msg_id = (int)$row[0];
            $one_row = array("msg_id"=>$msg_id,
                "poster_id"=>$user_id,
                "content"=>$row[2],
                "picture"=>$row[3],
                "view_num"=>$this->getRelationCounter($msg_id, "view"),
                "like_num"=>$this->getRelationCounter($msg_id, "like"),
                "post_time"=>$row[5],
                "title"=>$row[6]);
            array_push($ret, $one_row);
        }
        return $ret;
    }
    public function addOne($user_id, $post_id, $count_name)
    {
        $query = "DO\n \$do\$\n BEGIN";
        $query .= " IF NOT EXISTS (SELECT * FROM ".$count_name."_relation".
            " WHERE msg_id=".$post_id." and user_id=".$user_id.") THEN";
        $query .= " INSERT INTO ".$count_name.
            "_relation (msg_id,user_id) VALUES (".$post_id.", ".$user_id.");";
        $query .= "END IF; END\n\$do\$";
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        else
            return true;

    }
    public function cancel($user_id, $post_id, $count_name)
    {
        $query = "DELETE FROM ".$count_name."_relation WHERE msg_id="
            .$post_id." and user_id=".$user_id;
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        else
            return true;
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
