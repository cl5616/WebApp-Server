
<?php
require_once "utils.php";
function getCurUserId()
{
    return 2;
    if (!isset($_SESSION["id"]))
    {
        dieWithErrorMsg("not login yet");
    }
    return $_SESSION["id"];
}