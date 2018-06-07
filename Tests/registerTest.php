<?php
require_once "./Classes/Class_register.php";


use PHPUnit\Framework\TestCase;

class registerTest extends TestCase
{
  public function testRegister()
  {
    $_POST["database"] = $this->createMock(Database::class);
    $_POST["email"] = "test@example.com";
    $_POST["password"] = "password";
    $_POST["nickname"] = "tom";

    dieIfEmpty($_POST, "password");
    dieIfEmpty($_POST, "email");
    dieIfEmpty($_POST, "nickname");
    $introduction = emptyIfNotSet($_POST, "introduction");

    $db = $_POST["database"];

    $db->expects($this->once())->method('ifEmailExist')->with($_POST["email"])->willReturn(False);

    $db->expects($this->once())->method('doRegister')->willReturn(True);

    $register = new RegisterAccount(
        $_POST["email"], $_POST["password"], $_POST["nickname"],
        $introduction, $db);

    $register->doRegister();
  }
}

$register = new registerTest();
$register->testRegister();
