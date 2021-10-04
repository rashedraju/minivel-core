<?php

namespace Middlewares;

use Minivel\Exceptions\ForbiddenException;
use Minivel\Middlewares\AuthMiddleware;
use PHPUnit\Framework\TestCase;

class AuthMiddlewareTest extends TestCase
{
    public function testAuthMiddlewareThrowForbiddenException(){
        $authMiddleware = new AuthMiddleware(["foo"]);
        $this->expectException(ForbiddenException::class);
        $authMiddleware->execute("foo");
    }
}
