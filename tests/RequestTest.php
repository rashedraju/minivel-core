<?php


use Minivel\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGetPathGivesRequestUriPath(){
        $m = $this->getMockBuilder(Request::class)
            ->onlyMethods(['explodeUri'])
            ->getMock();
        $m->method("explodeUri")
            ->willReturn(['api', 'path']);
        $this->assertSame("path", $m->getPath());
    }

    public function testRequestIsGet(){
        $m = $this->getMockBuilder(Request::class)
            ->onlyMethods(['getMethod'])
            ->getMock();
        $m->method("getMethod")
            ->willReturn("get");
        $this->assertTrue($m->isGet());
    }

    public function testRequestIsPost(){
        $m = $this->getMockBuilder(Request::class)
            ->onlyMethods(['getMethod'])
            ->getMock();
        $m->method("getMethod")
            ->willReturn("post");
        $this->assertTrue($m->isPost());
    }

    public function testGetRequestType(){
        $m = $this->getMockBuilder(Request::class)
            ->onlyMethods(['explodeUri'])
            ->getMock();
        $m->method('explodeUri')
            ->willReturn(['api', 'path']);
        $this->assertSame('api', $m->getRequestType());
    }

    public function testGetUri(){
        $request = new Request();
        $this->assertSame("/", $request->getUri());
    }

    public function testGetBodyMethodWhenRequestIsGet(){
        $_GET["username"]  = "john doe";
        $_GET['email'] = "12345678";
        $m = $this->getMockBuilder(Request::class)
            ->onlyMethods(["getMethod"])
            ->getMock();
        $m->method("getMethod")
            ->willReturn("get");
        $this->assertCount(2, $m->getBody());
    }

    public function testGetBodyMethodWhenRequestIsPost(){
        $_POST["username"]  = "john doe";
        $_POST['email'] = "12345678";
        $m = $this->getMockBuilder(Request::class)
            ->onlyMethods(["getMethod"])
            ->getMock();
        $m->method("getMethod")
            ->willReturn("post");
        $this->assertCount(2, $m->getBody());
    }
}
