#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "./Classes/Class_getcomment.php";

/*/test----------
$_GET["msg_id"] = "11";
$_GET["offset"] = 0;
$_GET["limit"] = 10;
//test----------*/

dieIfEmpty($_GET, "msg_id");
$offset = toNum($_GET, "offset");
$limit = toNum($_GET, "limit");
$msg_id = (int)$_GET["msg_id"];

$get_comment = new CommentGetter($msg_id, $offset, $limit
                ,PostGREDatabase::getInstance());
$get_comment->getComments();
