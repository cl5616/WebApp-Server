<?php

use PHPUnit\Framework\TestCase;

class Post_test extends TestCase
{
    private $db = $this->createMock(PostGREDatabase::class);
    
    public function addPost()
    {
        $uploader = new PostUploader('category', 'content', $db);

        $db->shouldReceive('postMsg')->once()->with('category','content', 1);

        $uploader->doPost();

    }

    public function getPost()
    {
        $getter = new PostGetter('category', 'content', $db);

        $db->shouldReceive("getPosts")->once()->with(1);
    }
}