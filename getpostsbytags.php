#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "Classes/Class_getbytags.php";
require_once "database.php";

/*//test------------
$_GET["tags"] = "test";
$_GET["offset"] = 0;
$_GET["limit"] = 10;
$_GET["order"]="0";
///test------------*/


$tags = emptyIfNotSet($_GET, "tags");
$tags_arr = fetchAllWordsAsArr($tags);
$offset = toNum($_GET, "offset");
$limit = toNum($_GET, "limit");
$orderval = toNum($_GET, "order");
if (count($tags_arr) == 0)
    dieWithErrorMsg("tags cannot be empty");

$get_by_tags = new GetPostByTags($tags_arr, $offset,
    $limit, $orderval ,PostGREDatabase::getInstance());
$get_by_tags->getPosts();