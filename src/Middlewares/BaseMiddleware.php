<?php

namespace Minivel\Middlewares;

abstract class BaseMiddleware
{
    abstract public function execute(string $action);
}