<?php
require_once "Classes/Class_editprofile.php";
require_once "database.php";
/*/test--------------
$_POST["intro"] = "test edit 2";
$_POST["nickname"] = "LucasSB";
$_POST["oldpsw"] = "password2";
$_POST["newpsw"] = "password";
//test--------------*/

$intro = nullIfNotSet($_POST, "intro");
$image = nullIfNotSet($_POST, "image");
$nickname = nullIfNotSet($_POST, "nickname");
$new_psw = nullIfNotSet($_POST, "newpsw");
$old_psw = nullIfNotSet($_POST, "oldpsw");

$edit = new ProfileEditor($intro, $image,
    $nickname, $new_psw,
    $old_psw, PostGREDatabase::getInstance());
$edit->editProfile();