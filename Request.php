<?php
namespace App\core;

/**
 * @package App\core
 */
class Request{
    public function getPath(){
        $uri = $_SERVER['REQUEST_URI'] ?? "/";
        $position = strpos($uri, "?");
        
        if($position){
            return substr($uri, 0, $position);
        }
        return $uri;
    }

    public function method(): string{
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(){
        return $this->method() === "get";
    }

    public function isPost(){
        return $this->method() === "post";
    }

    public function getBody(): array{
        $body = [];

        if($this->method() == "get"){
            foreach ($_GET as $key => $value){
                $body[$key] = filter_var($value, INPUT_GET);
            }
        }

        if($this->method() == "post"){
            foreach ($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}