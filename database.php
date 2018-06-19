
<?php

require_once "utils.php";

interface Database
{
    public function postMsg($content, $category, $user_id, $picture, $anonymous, $tags, $title);
    public function postComment($msg_id, $content, $reply_id, $user_id);
    public function getPosts($category, $offset, $limit, $order_val);
    public function ifEmailExist($email);
    public function doRegister($email, $password_hash, $salt, $nickname, $introduction);
    public function getEmailPswInfo($email);
    public function getComments($msg_id, $offset, $limit);
}


final class PostGREDatabase implements Database
{
    const PROF_EDIT_NUM = 4;

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

    public function postMsg($content, $category,
                            $user_id, $picture, $anonymous,
                            $tags, $title)
    {
        $tags_arr = fetchAllWordsAsArr($tags);
        $tags_text = join(" ", $tags_arr);
        $query = "INSERT INTO ".self::DB_POSTS_TAB.
            " (user_id,post_time,picture,content,category,deleted,".
            "anonymous,title,tags,search_vec)".
            " VALUES (".$user_id.",CURRENT_TIMESTAMP,$3,$1,$2,B'0',".
            self::boolToBit($anonymous).",$4,$6,".
            "setweight(to_tsvector('english',$5),'C') ||".
            "setweight(to_tsvector('english',$6),'B') ||".
            "setweight(to_tsvector('english',$1),'A'))";
        $result = pg_prepare($this->conn, "post_msg", $query);
        if (!$result)
        {
            return false;
        }//works well when picture===null
        $result = pg_execute($this->conn, "post_msg",
            [$content, $category, $picture, $title, $title, $tags_text]);
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
    private static function orderLimitOffset($order_val, $offset, $limit)
    {
        $order_by = $order_val == null ? "" : " ORDER BY ".$order_val." DESC";
        return $order_by." LIMIT ".$limit." OFFSET ".$offset;
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
    private static function genWhere($category)
    {
        if ($category !== null)
        {
            $where = " WHERE category='".$category."' and deleted=B'0'";
        }
        else
        {
            $where = " WHERE deleted=B'0'";
        }
        return $where;
    }

    private static function fetchAllWords($input, $separator)
    {
        return join($separator, fetchAllWordsAsArr($input));
    }

    private static function tagsToQueries($tags)
    {
        return join(":B|", $tags).":B";
    }

    private static function getOrderVal($orderval, $new_query)
    {
        if ($orderval === null)
        {
            $order = "ts_rank_cd(search_vec, to_tsquery('english','$new_query'))";
        }
        else
        {
            $order = $orderval;
        }
        return $order;
    }

    public function getUserPosts($userid, $offset, $limit, $category, $orderval)
    {
        $where = " WHERE user_id=$userid and deleted=B'0'".
            ($category === null ? "" : " and category='$category'");
        return $this->getPostsCustomWhere($where, $offset, $limit, $orderval);
    }

    public function getTagPosts($tags, $offset, $limit, $category, $orderval)
    {
        $query = self::tagsToQueries($tags);
        $order = self::getOrderVal($orderval, $query);
        $where = " WHERE to_tsquery('english','$query') @@ search_vec and deleted=B'0'".
            ($category === null ? "" : " and category='$category'");
        return $this->getPostsCustomWhere($where, $offset, $limit, $order);
    }


    public function searchPosts($query, $offset, $limit, $category, $orderval)
    {
        $new_query = self::fetchAllWords($query, "&");
        if (strlen($new_query) === 0)
        {
            return self::getPosts(null, $offset, $limit, "post_time");
        }
        else
        {
            $order = self::getOrderVal($orderval, $new_query);
            $where = " WHERE to_tsquery('english','$new_query') @@ search_vec and deleted=B'0'".
                ($category === null ? "" : " and category='$category'");
            return $this->getPostsCustomWhere($where, $offset, $limit, $order);
        }
    }

    public function getPosts($category, $offset, $limit, $order_val)
    {
        $where = self::genWhere($category);
        return self::getPostsCustomWhere($where, $offset, $limit, $order_val);
    }

    private function getPostsCustomWhere($where, $offset, $limit, $order_val)
    {
        $subquery = "SELECT id,".self::DB_POSTS_TAB.".user_id,content,".
            "picture,anonymous,post_time,title,".
            "COUNT(like_relation.user_id) AS like_num,tags,search_vec FROM ".
            self::DB_POSTS_TAB." LEFT JOIN like_relation ON ".
            self::DB_POSTS_TAB.".id=like_relation.msg_id"
            .$where." GROUP BY ".self::DB_POSTS_TAB.".id";

        $query = "SELECT id,posts_like.user_id,content,".
            "picture,anonymous,post_time,title,like_num,".
            "COUNT(view_relation.user_id) AS view_num,tags".
            " FROM ($subquery) AS posts_like".
            " LEFT JOIN view_relation ON posts_like.id=view_relation.msg_id".
            " GROUP BY id,posts_like.user_id,content,".
            "picture,anonymous,post_time,title,like_num,tags,search_vec".
            self::orderLimitOffset($order_val, $offset, $limit);

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
                "view_num"=>(int)$row[8],
                "like_num"=>(int)$row[7],
                "post_time"=>$row[5],
                "title"=>$row[6],
                "tags"=>$row[9]);
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
    public function ifAddedOne($user_id, $post_id, $count_name)
    {
        $query = "SELECT * FROM $count_name"."_relation".
            " WHERE msg_id=$post_id and user_id=$user_id";
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        else if ($row = pg_fetch_row($result))
            return 1;
        else
            return 0;
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
    private static function toInsertQuery(&$columns, &$values, $column_name, $new_val)
    {
        if ($new_val !== null)
        {
            array_push($columns, $column_name);
            array_push($values, $new_val);
        }
    }
    public function editProfile($new_intro, $new_image,
                                $new_nickname, $new_psw_hash, $userid)
    {
        $columns = array();
        $values = array();
        self::toInsertQuery($columns, $values, "introduction", $new_intro);
        self::toInsertQuery($columns, $values, "image", $new_image);
        self::toInsertQuery($columns, $values, "nickname", $new_nickname);
        self::toInsertQuery($columns, $values, "password", $new_psw_hash);
        $len = count($columns);
        if ($len === 0)
            return true;
        $query = "UPDATE ".self::DB_USER_TAB.
            " SET ";
        for ($i = 0; $i < $len - 1; $i++)
        {
            $query .= $columns[$i];
            $query .= "=\$";
            $query .= $i+1;
            $query .= ",";
        }
        $query .= $columns[$i];
        $query .= "=\$";
        $query .= $len;
        $query .= " WHERE id=$userid";
        $result = pg_prepare($this->conn,"edit_profile", $query);
        if (!$result)
            return false;
        $result = pg_execute($this->conn, "edit_profile", $values);
        if (!$result)
            return false;
        else
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
            return array("id"=>(int)$row[0], "password"=>$row[1], "salt"=>$row[2]);
        }
        else
        {
            return array();
        }
    }

    public function getIdPswInfo($id)
    {
        $query = "SELECT password,salt FROM users WHERE id=$id";
        $result = pg_query($this->conn, $query);
        if (!$result)
        {
            return false;
        }
        $row = pg_fetch_row($result);
        if ($row)
        {
            return array("password"=>$row[0], "salt"=>$row[1]);
        }
        else
        {
            return false;
        }
    }

    public function getProfile($user_id)
    {
        $query = "SELECT email,nickname,introduction,image FROM users WHERE id=$user_id";
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        $row = pg_fetch_row($result);
        if ($row)
        {
            $query = "SELECT tag FROM follow_relation WHERE user_id=$user_id";
            $result = pg_query($this->conn, $query);
            if (!$result)
                return false;
            $tags = array();
            while($tag = pg_fetch_row($result))
            {
                array_push($tags, $tag[0]);
            }
            return array("email"=>$row[0], "nickname"=>$row[1],
                "introduction"=>$row[2], "image"=>$row[3], "tags"=>$tags);
        }
        return array();
    }

    public function followTag($user_id, $tag)
    {
        $query = "DO\n \$do\$\n BEGIN";
        $query .= " IF NOT EXISTS (SELECT * FROM follow_relation".
            " WHERE user_id=".$user_id." and tag='$tag') THEN";
        $query .= " INSERT INTO follow_relation (user_id,tag) ".
            "VALUES (".$user_id.", '$tag');";
        $query .= "END IF; END\n\$do\$";
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        else
            return true;
    }

    public function unfollowTag($user_id, $tag)
    {
        $query = "DELETE FROM follow_relation WHERE user_id=$user_id and tag='$tag'";
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        else
            return true;
    }

    public function ifFollowed($user_id, $tag)
    {
        $query = "SELECT * FROM follow_relation".
            " WHERE user_id=".$user_id." and tag='$tag'";
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        else if (pg_fetch_row($result))
            return 1;
        else
            return 0;
    }
    public function deletePost($post_id, $user_id)
    {
        $query = "DELETE FROM ".self::DB_POSTS_TAB." WHERE id=$post_id AND user_id=$user_id";
        $result = pg_query($this->conn, $query);
        if (!$result)
            return false;
        else
            return true;
    }
}
