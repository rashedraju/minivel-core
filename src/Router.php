<?php
namespace Minivel;

use Minivel\Exceptions\NotFoundException;

class Router{
    public array $routes = [];
    public Request $request;
    public Response $response;
    public Application $app;
    private const REQUEST_API = 'api';
    private const REQUEST_WEB = 'web';
    private const REQUEST_GET = 'get';
    private const REQUEST_POST = 'post';

    function __construct(Request $request, Response $response){
        $this->request = $request;
        $this->response = $response;
        $this->app = Application::$app;
    }

    public function get($path, $callback){
        $this->parseRoute(self::REQUEST_GET, $path, $callback);
    }

    public function post($path, $callback){
        $this->parseRoute(self::REQUEST_POST, $path, $callback);
    }

    protected function parseRoute($method, $path, $callback){
        $explodedPath = $this->explodePath($path);

        if($explodedPath[0] == self::REQUEST_API){
            $this->routes[self::REQUEST_API][$method][$explodedPath[1]] = $callback;
        }else{
            $this->routes[self::REQUEST_WEB][$method][$explodedPath[0]] = $callback;
        }
    }

    protected function explodePath(string $path){
        return array_filter(explode("/", $path));
    }

    protected function getRequestedCallback(string $requestType, string $path, string $method){
        return $this->routes[$requestType][$method][$path] ?? false;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve(){
        $type = $this->request->getRequestType();
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->getRequestedCallback($type, $path, $method);

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
