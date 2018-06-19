#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "utils.php";
require_once "database.php";
require_once "Classes/Class_follow.php";
//test----------------
//$_POST["tag"] = "ctf";
//test----------------*/
dieIfEmpty($_POST, "tag");
$tag = getTag($_POST["tag"]);


$follow = new Unfollow($tag, PostGREDatabase::getInstance());
$follow->doUnfollow();