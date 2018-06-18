<?php
require_once "preprocess.php";
require_once "utils.php";
require_once "database.php";
require_once "Classes/Class_follow.php";
//test----------------
$_POST["tag"] = "ctf";
//test----------------*/
dieIfEmpty($_POST, "tag");
$tags = fetchAllWordsAsArr($_POST["tag"]);
if (count($tags) != 1)
{
    dieWithErrorMsg("invalid tag");
}

$follow = new Follow($tags[0], PostGREDatabase::getInstance());
$follow->doFollow();