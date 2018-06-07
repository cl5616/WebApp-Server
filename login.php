#!/usr/bin/php
<?php
require_once "./Classes/Class_login.php";
//test-----
/*/login success
$_POST["email"] = "test@email.com";
$_POST["password"] = "password";//*/

/*/email incorrect
$_POST["email"] = "wrong@email.com";
$_POST["password"] = "password";//*/


/*/password incorrect
$_POST["email"] = "test@email.com";
$_POST["password"] = "wrong password";//*/

//test-----

dieIfEmpty($_POST, "email");
dieIfEmpty($_POST, "password");

$login = new Login($_POST["email"], $_POST["password"],
    PostGREDatabase::getInstance());
$login->doLogin();
