<?php
require_once "./Classes/Class_addcomment.php";
use PHPUnit\Framework\TestCase;


class addCommentTest extends TestCase
{
  public function testAddComment()
  {
    $db = $this->createMock(Database::class);
    $_POST["msg_id"] = "11";
    $_POST["content"] = "reply 17 under same post";
    $_POST["reply_id"] = "17";
    dieIfEmpty($_POST, "msg_id");
    $msg_id = intval($_POST["msg_id"]);
    $reply_id = isset($_POST["reply_id"]) ? intval($_POST["reply_id"]) : "null";
    $content = emptyIfNotSet($_POST, "content");
    $uploader = new CommentUploader($msg_id, $content, $reply_id, $db);

    $db->expects($this->once())->method('postComment')->with("11",
    $content, $reply_id, getCurUserId())->willReturn(True);

    $uploader->doComment();
  }
}

$addComment = new addCommentTest();
$addComment->testAddComment();
