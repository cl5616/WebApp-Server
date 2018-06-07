<?php

require_once "./Classes/Class_register.php";
#require_once "./Classes/Class_login.php";


use PHPUnit\Framework\TestCase;

class All_Test extends TestCase
{

  public function test()
  {
    $this->assertTrue(True);
  }

  public function testRegister()
  {
    ob_start();
    include_once 'registerTest.php';
    $result = ob_get_clean();
    $this->assertEquals($result, '{"status":true}');
  }

  public function testLogin()
  {
    ob_start();
    include_once 'loginTest.php';
    $result = ob_get_clean();
    $this->assertEquals($result, '{"status":true}');
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
