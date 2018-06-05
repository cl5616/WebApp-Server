
<?php
require "utils.php";
abstract class ContentUploader
{
    protected $content;
    protected $database;
    protected static function returnJsonStatus($ret)
    {
        returnJsonStatus($ret);
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
