<?php

require_once "./Classes/Class_register.php";
require_once "./Classes/Class_addcomment.php";


use PHPUnit\Framework\TestCase;

class All_Test extends TestCase
{
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
    $this->assertEquals($result, '{"status":true,"id":1}');
  }
  public function testAddPost()
  {
    ob_start();
    include_once 'addpostTest.php';
    $result = ob_get_clean();
    $this->assertEquals($result, '{"status":true}');
  }

  public function testAddComment()
  {
    ob_start();
    include_once 'addcommentTest.php';
    $result = ob_get_clean();
    $this->assertEquals($result, '{"status":true}');
  }

  public function testGetPost()
  {
    ob_start();
    include_once 'getpostsTest.php';
    $result = ob_get_clean();
    $expect = '[{"msg_id":"1","poster_id":"2","content":"content","picture"'.
      ':"picture","view_num":"1","like_num":"1","post_time":"1","title":"picture"}]';
    $this->assertEquals($result, $expect);
  }

  public function testGetComment()
  {
    ob_start();
    include_once 'getcommentTest.php';
    $result = ob_get_clean();
    $expect = '[{"msg_id":11,"poster_id":1,"content":1,"picture":1,'.
      '"view_num":1,"like_num":1,"post_time":1,"title":1}]';
    $this->assertEquals($result, $expect);
  }
}
