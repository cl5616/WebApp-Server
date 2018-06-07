<?php
require_once "./Classes/Class_getcomment.php";
use PHPUnit\Framework\TestCase;


class getcommentTest extends TestCase
{
  public function testGetcomment()
  {
    $db = $this->createMock(Database::class);

    $_GET["msg_id"] = "11";
    $_GET["offset"] = 0;
    $_GET["limit"] = 10;

    dieIfEmpty($_GET, "msg_id");
    $offset = toNum($_GET, "offset");
    $limit = toNum($_GET, "limit");
    $msg_id = (int)$_GET["msg_id"];

    $get_comment = new CommentGetter($msg_id, $offset, $limit, $db);

    $db->expects($this->once())->method('getComments')->with($msg_id, $offset,
    $limit)->willReturn(array(array("msg_id"=>$msg_id,
        "poster_id"=>1,
        "content"=>1,
        "picture"=>1,
        "view_num"=>1,
        "like_num"=>1,
        "post_time"=>1,
        "title"=>1)));

    $get_comment->getComments();

  }
}

$getComment = new getcommentTest();
$getComment->testGetcomment();
