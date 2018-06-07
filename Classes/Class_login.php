<?php
require_once "utils.php";
require_once "database.php";
class Login
{
    private $email;
    private $password;
    private $database;

    const LOGIN_FAILED = "login failed";

    public function __construct($email, $password, $database)
    {
        $this->email = $email;
        $this->password = $password;
        $this->database = $database;
    }
    public function doLogin()
    {
        $result = $this->database->getEmailPswInfo($this->email);
        if ($result === false)
        {
            dieWithErrorMsg(PostGREDatabase::DB_QUERY_PROBLEM);
        }
        if (isset($result["id"]))
        {
            $hash = hash("sha256", $this->password.$result["salt"]);
            if (strcmp($hash, $result["password"]) == 0)
            {
                $_SESSION["id"] = $result["id"];
                returnJsonStatus(true);
            }
            else
            {
                dieWithErrorMsg(self::LOGIN_FAILED);
            }
        }
        else
        {
            dieWithErrorMsg(self::LOGIN_FAILED);
        }
    }
}
