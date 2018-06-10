<?php
require_once "./Classes/Class_getposts.php";
use PHPUnit\Framework\TestCase;


class getpostTest extends TestCase
{
  public function testGetPost()
  {
    $db = $this->createMock(Database::class);

    $_GET["offset"] = 0;
    $_GET["limit"] = 30;
    $_GET["category"] = "social";
    $_GET["sort"]="1";
    $offset = toNum($_GET, "offset");
    $limit = toNum($_GET, "limit");
    $category = nullIfNotSet($_GET, "category");
    $sort = toNum($_GET, "sort");
    if ($category != null)
        dieIfInvalidCategory($category);

    $getter = new PostGetter($category, $offset, $limit, $sort, $db);


    $db->expects($this->once())->method('getPosts')->with($category,
    $offset, $limit, "like_num")->willReturn(array(array("msg_id"=>"1",
        "poster_id"=>"2",
        "content"=>"content",
        "picture"=>"picture",
        "view_num"=>"1",
        "like_num"=>"1",
        "post_time"=>"1",
        "title"=>"picture")));

    $getter->getPosts();
  }
}

$getPosts = new getpostTest();
$getPosts->testGetPost();
