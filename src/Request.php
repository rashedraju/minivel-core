<?php
namespace Minivel;

class Request{
    private array $server;
    private const REQUEST_TYPE_WEB = 'web';
    private const REQUEST_TYPE_API = 'api';

    public function __construct()
    {
        $this->server = $_SERVER;
    }

    public function getPath(): string
    {
        $uriExploded = $this->explodeUri($this->getUri());
        return $uriExploded[0] == self::REQUEST_TYPE_API ? $uriExploded[1] : $uriExploded[0];
    }

    public function getMethod(): string{
        return strtolower($this->server['REQUEST_METHOD']);
    }

    public function getRequestType(): string
    {
        $uriExploded = $this->explodeUri($this->getUri());
        if($uriExploded[0] === self::REQUEST_TYPE_API){
            return self::REQUEST_TYPE_API;
        }
        return self::REQUEST_TYPE_WEB;
    }

    public function getUri():string{
        $uri = $this->server['REQUEST_URI'] ?? "/";
        $position = strpos($uri, "?");

        if($position){
            return substr($uri, 0, $position);
        }
        return $uri;
    }

    protected function explodeUri($uri):array{
        return array_filter(explode("/", $uri));
    }

    public function isGet(): bool
    {
        return $this->getMethod() === "get";
    }

    public function isPost(): bool
    {
        return $this->getMethod() === "post";
    }

    public function getBody(): array{
        $body = [];

        if($this->getMethod() == "get"){
            foreach ($_GET as $key => $value){
                $body[$key] = filter_var($value, INPUT_GET);
            }
        }

        if($this->getMethod() == "post"){
            foreach ($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}