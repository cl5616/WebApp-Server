#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "database.php";
require_once "Classes/Class_ifaddedone.php";

/*/test---------------
$_GET["post_id"] = "2";
//test---------------*/

$post_id = toNum($_GET, "post_id");
$if_like = new IfAddedOne($post_id, PostGREDatabase::getInstance(), "view");
$if_like->tryIfAdded();