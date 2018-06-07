#!/usr/bin/php
<?php
    require_once "./Classes/Class_getposts.php";

    //test-------
$_GET["offset"] = 0;
$_GET["limit"] = 30;
//$_GET["category"] = "social";
    //test-------*/
    $offset = toNum($_GET, "offset");
    $limit = toNum($_GET, "limit");
    $category = nullIfNotSet($_GET, "category");
    if ($category != null)
        dieIfInvalidCategory($category);
    $getter = new PostGetter($category, $offset, $limit,
        PostGREDatabase::getInstance());
    $getter->getPosts();
