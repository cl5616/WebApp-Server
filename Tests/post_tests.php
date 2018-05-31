<?php

class Post_test extends PHPUnit_Framework_TestCase
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

        $db->shouldReceive('getPost')->once()->with(1);
    }

    public function addComment()
    {
        $commenter = new CommentUploader('id', 'content', 'reply', $db);

        $db->shouldReceive('postComment')->once()->with('id', 'content', 'reply', 1);
    }
}