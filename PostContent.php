#!/usr/bin/php
<?php
abstract class ContentUploader
{
    protected $content;
    protected $database;
    protected static function returnJsonStatus($ret)
    {
        $ret = $ret ? "true" : "false";
        $error_msg = $ret ? "" : ",\"error\":\"database insert error\"";
        echo "{\"status\":".$ret.$error_msg."}";
    }

    public static function returnJsonErrorDie($msg)
    {
        ?>
        {"status":false, "error":"<?php
        echo $msg."\"}";
        die();
    }
    public function __construct($content, $database)
    {
        $this->content = $content;
        $this->database = $database;
    }
}
