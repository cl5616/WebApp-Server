<?php
/**
 * Created by PhpStorm.
 * User: holing
 * Date: 2018/5/30
 * Time: 23:54
 */


    if (!isset($_GET["category"]))
    {
        $category = "general";
    }
    $category = $_GET["category"];
    $preference = $_GET[""];

    get_indfo($category, $preference);

    ?>
{"msg":"qwe"}
