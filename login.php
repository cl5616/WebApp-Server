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
        if (!$result)
        {
            dieWithErrorMsg(PostGREDatabase::DB_QUERY_PROBLEM);
        }
        if (isset($result["id"]))
        {
            $hash = hash("sha256", $this->password.$result["salt"]);
            if (strcmp($hash, $result["password"]))
            {
                $_SESSION["id"] = $result["id"];
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

dieIfEmpty($_POST, "email");
dieIfEmpty($_POST, "password");

$login = new Login($_POST["email"], $_POST["password"],
    PostGREDatabase::getInstance());
$login->doLogin();
