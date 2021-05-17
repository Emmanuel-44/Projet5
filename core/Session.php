<?php
namespace core;

class Session
{
    public static function put($key, $value){
        $_SESSION[$key] = $value;
    }

    public static function get(?string $key){
        if ($key) {
            return (isset($_SESSION[$key]) ? $_SESSION[$key] : null);
        }
        return $_SESSION;   
    }

    public static function forget(){
        session_destroy();
    }
}