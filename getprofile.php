#!/usr/bin/php
<?php
require_once "preprocess.php";
require_once "database.php";
require_once "Classes/Class_getprofile.php";

//test-------------
//$_POST["userid"] = 2;
//test-------------

$userid = toNum($_POST, "userid");
$profile_getter = new ProfileGetter($userid, PostGREDatabase::getInstance());
$profile_getter->getProfile();