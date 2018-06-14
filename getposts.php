#!/usr/bin/php
<?php
    require_once "preprocess.php";
    require_once "./Classes/Class_getposts.php";

    /*/test-------
$_GET["offset"] = "1";
$_GET["limit"] = "1";
//$_GET["category"] = "social";
$_GET["sort"]="2";
    //test-------*/
    $offset = toNum($_GET, "offset");
    $limit = toNum($_GET, "limit");
    $category = nullIfNotSet($_GET, "category");
    $sort = toNum($_GET, "sort");
    if ($category != null)
        dieIfInvalidCategory($category);
    $getter = new PostGetter($category, $offset, $limit,
        $sort, PostGREDatabase::getInstance());
    $getter->getPosts();
