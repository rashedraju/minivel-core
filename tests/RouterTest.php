<?php

use Minivel\Request;
use Minivel\Response;
use Minivel\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    public function getMockedRequest(){
        $mockedRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->onlyMethods(["getPath", "getMethod", "getRequestType"])
            ->getMock();
        $mockedRequest->method("getPath")
            ->willReturn("/test");
        $mockedRequest->method("getMethod")
            ->willReturn("get");
        $mockedRequest->method("getRequestType")
            ->willReturn("api");
        return $mockedRequest;
    }

    public function testRouteResolveWithController(){

        $mockedRouter = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRequestedCallback'])
            ->getMock();
        $mockedRouter->method("getRequestedCallback")
            ->willReturn(
                [Controller::class, 'test']
            );

        $response = new Response();
        $mockedRouter->request = $this->getMockedRequest();
        $mockedRouter->response = $response;
        $mockedRouter->app = $this->getMockBuilder(\Minivel\Application::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->assertSame("test", $mockedRouter->resolve());
    }
}

class Controller extends \Minivel\BaseController {
    public function test(): string{
        return 'test';
    }
    public function getMiddlewares(): array
    {
        return [];
    }
}