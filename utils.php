
<?php
function dieWithErrorMsg($msg)
{
    echo "{\"status\":false, \"error\":\"".$msg."\"}";
    die();
}
    function returnJsonStatus($ret)
    {
        $ret = $ret ? "true" : "false";
        $error_msg = $ret ? "" : ",\"error\":\"database insert error\"";
        echo "{\"status\":".$ret.$error_msg."}";
    }

    function dieIfEmpty($map, $key)
    {
        if (!isset($map[$key]) || strlen($map[$key]) == 0)
            dieWithErrorMsg(
                "argument \"".$key."\" cannot be empty");
    }

    function emptyIfNotSet($map, $key)
    {
        return isset($map[$key]) ? $map[$key] : "";
    }

    function encodeNum($num, $num_bytes)
    {
        $ret = "";
        for ($i = 0; $i < $num_bytes; $i++)
        {
            $ret .= chr($num & 0xff);
            $num >>= 8;
        }
        $base64 = base64_encode($ret);
        $enc = str_replace("+", "_", $base64);
        $enc = str_replace("/", "-", $enc);
        $enc = str_replace("=", "", $enc);
        return $enc;
    }