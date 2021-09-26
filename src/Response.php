<?php

namespace Minivel;

class Response
{
    /**
     * @param $code
     */
    public function setStatusCode($code){
        http_response_code($code);
    }

    public function redirect(string $path){
        header("Location: $path");
    }
}