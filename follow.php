#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "database.php";
require_once "Classes/Class_follow.php";
/*/test----------------
$_POST["userid"] = "3";
//test----------------*/
dieIfEmpty($_POST, "userid");
$userid = (int)$_POST["userid"];

$follow = new Follow($userid, PostGREDatabase::getInstance());
$follow->doFollow();
