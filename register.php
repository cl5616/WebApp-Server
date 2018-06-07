#!/usr/bin/php
<?php
require_once "Class_register.php"

/*/test-------
$_POST["password"] = "password";
$_POST["email"] = "test@email.com";
$_POST["nickname"] = "test";
//test-------*/

dieIfEmpty($_POST, "password");
dieIfEmpty($_POST, "email");
dieIfEmpty($_POST, "nickname");
$introduction = emptyIfNotSet($_POST, "introduction");

$register = new RegisterAccount(
    $_POST["email"], $_POST["password"], $_POST["nickname"],
    $introduction, PostGREDatabase::getInstance());

$register->doRegister();
