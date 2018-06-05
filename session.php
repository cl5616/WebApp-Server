
<?php
require_once "utils.php";
function getCurUserId()
{
    if (!isset($_SESSION["id"]))
    {
        dieWithErrorMsg("not login yet");
    }
    return $_SESSION["id"];
}