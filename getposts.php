#!/usr/bin/php
<?php
    require_once "./Classes/Class_getposts.php";

    /*/test-------
$_GET["offset"] = "0";
$_GET["limit"] = "30";
//$_GET["category"] = "social";
$_GET["sort"]="1";
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
