#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "database.php";
require_once "increment.php";

/*/test--------
$_POST["post_id"] = "13";
//test--------*/

dieIfEmpty($_POST, "post_id");
$post_id = toNum($_POST, "post_id");

$like = new IncrementPost($post_id,
    PostGREDatabase::getInstance(), "like");
$like->doAdd();