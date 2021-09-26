<?php

namespace Minivel;

class Session
{
    protected const FLASH_NAME = 'flash_message';
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_NAME] ?? [];
        foreach ($flashMessages as &$flashMessage){
            $flashMessage['remove'] = true;
        }

        $_SESSION[self::FLASH_NAME] = $flashMessages;
    }

    public function setFlashMessage(string $key, $message){
        $msg = [
            "remove" => false,
            "value" => $message
        ];
        $_SESSION[self::FLASH_NAME][$key] = $msg;
    }

    public function getFlashMessage($key){
        return $_SESSION[self::FLASH_NAME][$key]["value"] ?? false;
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_NAME] ?? [];

        foreach ($flashMessages as $key => &$flashMessage){
            if($flashMessage['remove']){
                unset($_SESSION[self::FLASH_NAME][$key]);
            }
        }
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key){
        return $_SESSION[$key] ?? false;
    }

    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }
}