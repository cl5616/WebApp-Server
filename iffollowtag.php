#!/usr/bin/php
<?php
require_once "database.php";
require_once "Classes/Class_iffollow.php";

/*/test---------------
$_GET["userid"] = "";
//test---------------*/

dieIfEmpty($_GET, "userid");
$userid = (int)$_GET["userid"];
dieIfEmpty($_GET, "tag");
$tag = getTag($_GET["tag"]);

$checker = new FollowChecker($userid, $tag,
    PostGREDatabase::getInstance());
$checker->doCheckFollow();