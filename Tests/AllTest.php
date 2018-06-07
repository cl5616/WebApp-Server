<?php
/*
require_once "database.php";
require_once "addpost.php";
require_once "getposts.php";
require_once "addcomment.php";
*/

require_once "./Classes/Class_register.php";
require_once "./Classes/Class_login.php";


use PHPUnit\Framework\TestCase;

class All_Test extends TestCase
{

  public function test()
  {
    $this->assertTrue(True);
  }

  public function testRegisterAndLogin()
  {
    $_POST["database"] = $this->createMock(Database::class);
    $_POST["email"] = "test@example.com";
    $_POST["password"] = "password";
    $_POST["nickname"] = "tom";
    require "registerTest.php";
    $file = file_get_contents('https://www.doc.ic.ac.uk/project/2017/271/g1727111/WebAppsServer/Tests/registerTest.php');
    $this->assertEquals($file, "");
  }
/*
    public function testAddPost()
    {
        $db = $this->createMock(Database::class);

        $uploader = new PostUploader('category', 'content', $db);

        $db->expects($this->once())->method('postMsg')->with('content','category', 1)->willReturn('Hello');

        $uploader->doPost();

    }

    public function testGetPost()
    {
        $db = $this->createMock(Database::class);

        $getter = new PostGetter('category', 'content', $db);

        $db->expects($this->once())->method('getPosts')->with(1);

        $getter->getPosts();

    }

    public function testAddComment()
    {

        $db = $this->createMock(Database::class);

        $commenter = new CommentUploader('id', 'content', 'reply', $db);

        $db->expects($this->once())->method('postComment')->with('id', 'content', 'reply', 1);

        $commenter->doComment();
    }
*/
}
