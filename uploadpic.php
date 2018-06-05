<?php

require_once "utils.php";

dieIfEmpty($_FILES, "picture");

$_FILES["picture"][""]