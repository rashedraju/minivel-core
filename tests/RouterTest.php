<?php

use Minivel\Request;
use Minivel\Response;
use Minivel\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testRouteResolve(){
        $mockedRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->onlyMethods(["getPath", "getMethod"])
            ->getMock();
        $mockedRequest->method("getPath")
            ->willReturn("/test");
        $mockedRequest->method("getMethod")
            ->willReturn("get");
        $mockedRouter = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRequestedCallback'])
            ->getMock();
        $mockedRouter->method("getRequestedCallback")
            ->willReturn(
                function(){
                    return "testing router resolve method";
                }
            );
        $response = new Response();
        $mockedRouter->request = $mockedRequest;
        $mockedRouter->response = $response;
        $this->assertSame("testing router resolve method", $mockedRouter->resolve());
    }
}
