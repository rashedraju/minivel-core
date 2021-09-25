<?php

namespace App\core\middlewares;

use App\core\Application;
use App\core\exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    protected array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * @throws ForbiddenException
     */
    public function execute(string $action)
    {
        if(in_array($action, $this->actions) && Application::isGuest()){
            throw new ForbiddenException();
        }
    }
}