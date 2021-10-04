<?php

namespace Exceptions;

use Minivel\Exceptions\ForbiddenException;
use Minivel\Exceptions\NotFoundException;
use PHPUnit\Framework\TestCase;

class NotFoundExceptionTest extends TestCase
{
    public function testNotFoundException(){
        $this->expectException(NotFoundException::class);
        throw new NotFoundException();
    }
}
