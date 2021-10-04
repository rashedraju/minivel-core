<?php

namespace Exceptions;

use Minivel\Exceptions\ForbiddenException;
use PHPUnit\Framework\TestCase;

class ForbiddenExceptionTest extends TestCase
{
    public function testForbiddenException(){
        $this->expectException(ForbiddenException::class);
        throw new ForbiddenException();
    }
}
