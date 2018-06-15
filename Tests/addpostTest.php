<?php
require_once "./Classes/Class_addpost.php";
use PHPUnit\Framework\TestCase;


class addPostTest extends TestCase
{
  public function testAddPost()
  {
    $db = $this->createMock(Database::class);

    $_POST["category"]= "social";
    $_POST["content"]="hello world with pic";
    $_POST["picture"]="aaa.jpg";
    $_POST["anonymous"]="1";

    dieIfEmpty($_POST, "category");
    dieIfInvalidCategory($_POST["category"]);
    $content = emptyIfNotSet($_POST,"content");
    $title = emptyIfNotSet($_POST,"title");
    $picture = nullIfNotSet($_POST, "picture");
    $anonymous = emptyIfNotSet($_POST, "anonymous");
    $tags = emptyIfNotSet($_POST, "tags");

    $uploader = new PostUploader($_POST["category"], $content,
        $picture, $anonymous, $title, $tags, $db);

    $db->expects($this->once())->method('postMsg')->with($content,
     $_POST["category"], getCurUserId(), $picture, $anonymous, $title. $tags)->willReturn(True);

    $uploader->doPost();
  }
}

$addPost = new addPostTest();
$addPost->testAddPost();
