
<?php
function getCurUserId()
{
    return isset($_SESSION["id"]) ? $_SESSION["id"] : 0;
}
