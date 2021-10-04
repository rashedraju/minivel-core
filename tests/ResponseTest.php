<?php


use Minivel\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    protected Response $response;
    protected function setUp(): void
    {
        $this->response = new Response();
    }

    public function testSetStatusCode(){
        $this->response->setStatusCode(415);
        $this->assertEquals(415, http_response_code());
    }
}
