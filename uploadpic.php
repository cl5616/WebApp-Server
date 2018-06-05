<?php

require_once "utils.php";

dieIfEmpty($_FILES, "picture");

$tmp_path = $_FILES["picture"]["tmp_name"];
