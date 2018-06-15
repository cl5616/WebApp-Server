#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "database.php";
require_once "Classes/Class_postsearch.php";

/*//test------------
$_GET["search"] = "tag";
$_GET["offset"] = 0;
$_GET["limit"] = 10;
//$_GET["category"] = "job";
$_GET["order"]="0";
///test------------*/



$search = emptyIfNotSet($_GET, "search");
$offset = toNum($_GET, "offset");
$limit = toNum($_GET, "limit");

if (isset($_GET["category"]))
{
    dieIfInvalidCategory($_GET["category"]);
    $category = $_GET["category"];
}
else
{
    $category = null;
}
$orderval = toNum($_GET, "order");

$search = new PostSearcher($search, $offset,
    $limit, $category, $orderval,
    PostGREDatabase::getInstance());
$search->doSearch();