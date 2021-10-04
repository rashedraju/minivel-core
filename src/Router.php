<?php
namespace Minivel;

use Minivel\Exceptions\NotFoundException;

class Router{
    public array $routes = [];
    public Request $request;
    public Response $response;
    public Application $app;

    function __construct(Request $request, Response $response){
        $this->request = $request;
        $this->response = $response;
        $this->app = Application::$app;
    }

    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
    }

    protected function getRequestedCallback(string $path, string $method){
        return $this->routes[$method][$path] ?? false;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve(){
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = $this->getRequestedCallback($path, $method);
        if($callback){
            if(is_string($callback)){
                return $this->app->view->renderView($callback);
            }
            if(is_array($callback)){
                $this->app->controller = new $callback[0];
                $callback[0] = $this->app->controller;

                $middlewares = $this->app->controller->getMiddlewares();
                foreach ($middlewares as $middleware) {
                    $middleware->execute($callback[1]);
                }
            }
            return call_user_func($callback, $this->request, $this->response);
        }

        throw new NotFoundException();
    }
}
