<?php
require_once "./Classes/Class_login.php";

use PHPUnit\Framework\TestCase;

class loginTest extends TestCase
{
  public function testLogin()
  {
    $db = $this->createMock(Database::class);

    $result = array("id"=>(int)$row[0], "password"=>$row[1], "salt"=>$row[1]);
    $result["id"] = 1;
    $result["salt"] = "salt";
    $hash = hash("sha256", "password".$result["salt"]);
    $result["password"] = $hash;

    $db->expects($this->once())->method('getEmailPswInfo')->with("test@example.com")->willReturn($result);

    $login = new Login("test@example.com", "password", $db);

    $login->doLogin();
  }
}

$login = new loginTest();
$login->testLogin();
