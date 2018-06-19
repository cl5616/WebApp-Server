#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "Classes/Class_getbyuser.php";
require_once "database.php";

/*//test------------
$_GET["userid"] = "9";
$_GET["offset"] = 0;
$_GET["limit"] = 10;
$_GET["order"]="0";
///test------------*/


$user_id = toNum($_GET, "userid");
$offset = toNum($_GET, "offset");
$limit = toNum($_GET, "limit");
$orderval = toNum($_GET, "order");

$get_by_user = new PostGetterByUser($user_id,
    $offset, $limit, $orderval,
    PostGREDatabase::getInstance());

$get_by_user->getPosts();