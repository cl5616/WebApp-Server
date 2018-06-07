#!/usr/bin/php
<?php
#require_once "/vol/project/2017/271/g1727111/WebAppsServer/Classes/Class_register.php";
require_once "./Classes/Class_register.php";
dieIfEmpty($_POST, "password");
dieIfEmpty($_POST, "email");
dieIfEmpty($_POST, "nickname");
$introduction = emptyIfNotSet($_POST, "introduction");

$db = $_POST["database"];

$db->expects($this->once())->method('ifEmailExist')->with($_POST["email"])->willReturn(False);

$db->expects($this->once())->method('doRegister')->willReturn(True);

$register = new RegisterAccount(
    $_POST["email"], $_POST["password"], $_POST["nickname"],
    $introduction, $db);

$register->doRegister();
