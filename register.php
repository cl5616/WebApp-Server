<?php
require_once "utils.php";
require_once "database.php";
class RegisterAccount
{
    private $email;
    private $password;
    private $nickname;
    private $introduction;
    private $database;
    public function __construct($email, $password,
                                $nickname, $introduction,
                                $database)
    {
        $this->email = $email;
        $this->password = $password;
        $this->nickname = $nickname;
        $this->introduction = $introduction;
        $this->database = $database;
    }
    public function doRegister()
    {
        if ($this->database->ifEmailExist($this->email))
        {
            dieWithErrorMsg("email has already been registered");
        }
        else
        {
            $salt = bin2hex(openssl_random_pseudo_bytes(32));
            $password_hash = hash("sha256", $this->password.$salt);

            returnJsonStatus($this->database->doRegister($this->email,
                $password_hash, $salt,
                $this->nickname, $this->introduction));
        }
    }
}

//test-------
$_POST["password"] = "password";
$_POST["email"] = "test@email.com";
$_POST["nickname"] = "test";
//test-------

dieIfEmpty($_POST, "password");
dieIfEmpty($_POST, "email");
dieIfEmpty($_POST, "nickname");
$introduction = emptyIfNotSet($_POST, "introduction");

$register = new RegisterAccount(
    $_POST["email"], $_POST["password"], $_POST["nickname"],
    $introduction, PostGREDatabase::getInstance());

$register->doRegister();