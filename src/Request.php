<?php
namespace Minivel;

/**
 * @package Minivel
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

    public function getMethod(): string{
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(){
        return $this->getMethod() === "get";
    }

    public function isPost(){
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