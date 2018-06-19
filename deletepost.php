#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "database.php";
require_once "Classes/Class_deletepost.php";
$postid = toNum($_POST, "postid");

$delete = new PostDelete($postid,
    PostGREDatabase::getInstance());
$delete->doDelete();