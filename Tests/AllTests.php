<?php

require_once "database.php";
require_once "addpost.php";
require_once "getposts.php";
require_once "addcomment.php";


use PHPUnit\Framework\TestCase;

class Post_test extends TestCase
{

  public function test()
  {
    $this->assertTrue(True);
  }


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
}
