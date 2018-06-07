#!/usr/bin/php
<?php

require_once "utils.php";
require_once "session.php";
const MAX_IMG_SIZE = 0x100000;
const IMG_POST = 1;
function isImgExtension($ext)
{
    return strcmp($ext, "png") === 0 ||
        strcmp($ext, "jpg") === 0 ||
        strcmp($ext, "jpeg") === 0 ||
        strcmp($ext, "bmp") === 0;
}

function getImgName($imgType, $imgId, $ext)
{
    $name = "";
    $name .= encodeNum(getCurUserId(), 6);
    $name .= encodeNum($imgType, 3);
    $name .= encodeNum($imgId, 6);
    $name .= ".".$ext;
    return $name;
}

function returnPicInfoJson($filename)
{
    echo "{\"status\":true,\"filename\":\"".$filename."\"}";
}


if(!isset($_FILES["picture"]))
{
    dieWithErrorMsg("picture not set");
}

$tmp_path = $_FILES["picture"]["tmp_name"];
$ext = pathinfo($tmp_path, PATHINFO_EXTENSION);
echo $ext;
if (!isImgExtension($ext))
{
    dieWithErrorMsg("image extension invalid");
}
if ($_FILES["picture"]["size"] > MAX_IMG_SIZE)
{
    dieWithErrorMsg("image size is too big");
}

dieIfEmpty($_POST, "type");
dieIfEmpty($_POST, "id");

$imgName = getImgName((int)$_POST["type"], (int)$_POST["id"], $ext);
$imgFilePath = "pic/".$imgName;
if (file_exists($imgFilePath))
{
    dieWithErrorMsg("image already exist");
}
if (move_uploaded_file($tmp_path, $imgFilePath))
{
    returnPicInfoJson($imgName);
}
else
{
    returnJsonStatus(false);
}
