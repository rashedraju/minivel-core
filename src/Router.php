<?php
namespace Minivel;

use Minivel\Exceptions\NotFoundException;

class Router{
    public array $routes = [];
    public Request $request;
    public Response $response;

    function __construct(Request $request, Response $response){
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve(){
        $app = Application::$app;
        $path = $this->request->getPath();
        $method = $this->request->method();

        $callback = $this->routes[$method][$path] ?? false;
        if($callback){
            if(is_string($callback)){
                return $app->view->renderView($callback);
            }
            if(is_array($callback)){
                $app->controller = new $callback[0];
                $callback[0] = $app->controller;

                $middlewares = $app->controller->getMiddlewares();
                foreach ($middlewares as $middleware) {
                    $middleware->execute($callback[1]);
                }
            }
            return call_user_func($callback, $this->request, $this->response);
        }

        throw new NotFoundException();
    }
}
