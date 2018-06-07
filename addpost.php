#!/usr/bin/php
<?php
    require_once "Class_addpost.php";

    $db = PostGREDatabase::getInstance();

    //test--------
    $_POST["category"]= "social";
    $_POST["content"]="hello world with pic";
    $_POST["picture"]="aaa.jpg";
    $_POST["anonymous"]="1";
    //test--------

    dieIfEmpty($_POST, "category");
    dieIfInvalidCategory($_POST["category"]);
    $content = emptyIfNotSet($_POST,"content");
    $picture = nullIfNotSet($_POST, "picture");
    $anonymous = emptyIfNotSet($_POST, "anonymous");

    $uploader = new PostUploader($_POST["category"], $content,
        $picture, $anonymous, $db);
    $uploader->doPost();
