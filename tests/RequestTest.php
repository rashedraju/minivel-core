<?php


use Minivel\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    protected Request $request;

    protected function setUp(): void
    {
        $this->request = new Request();
    }

    public function testGetPathGivesRequestUriPath(){
        $this->assertSame("/", $this->request->getPath());
    }

    public function testRequestIsGet(){
        $mocked = $this->getMockBuilder(Request::class)
            ->onlyMethods(['getMethod'])
            ->getMock();
        $mocked->method("getMethod")
            ->willReturn("get");
        $this->assertTrue($mocked->isGet());
    }

    public function testRequestIsPost(){
        $mocked = $this->getMockBuilder(Request::class)
            ->onlyMethods(['getMethod'])
            ->getMock();
        $mocked->method("getMethod")
            ->willReturn("post");
        $this->assertTrue($mocked->isPost());
    }

    public function testGetBodyMethodWhenRequestIsGet(){
        $_GET["username"]  = "john doe";
        $_GET['email'] = "12345678";
        $mocked = $this->getMockBuilder(Request::class)
            ->onlyMethods(["getMethod"])
            ->getMock();
        $mocked->method("getMethod")
            ->willReturn("get");
        $this->assertCount(2, $mocked->getBody());
    }

    public function testGetBodyMethodWhenRequestIsPost(){
        $_POST["username"]  = "john doe";
        $_POST['email'] = "12345678";
        $mocked = $this->getMockBuilder(Request::class)
            ->onlyMethods(["getMethod"])
            ->getMock();
        $mocked->method("getMethod")
            ->willReturn("post");
        $this->assertCount(2, $mocked->getBody());
    }
}
