#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "database.php";
require_once "Classes/Class_postsearch.php";

/*//test------------
$_GET["search"] = "pic";
$_GET["offset"] = 0;
$_GET["limit"] = 10;
///test------------*/



$search = emptyIfNotSet($_GET, "search");
$offset = toNum($_GET, "offset");
$limit = toNum($_GET, "limit");

$search = new PostSearcher($search, $offset,
    $limit, PostGREDatabase::getInstance());
$search->doSearch();