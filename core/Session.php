<?php
namespace core;

class Session
{
    public static function put($key, $value){
        $GLOBALS['_SESSION'][$key] = $value;
    }

    public static function get(?string $key){
        if ($key) {
            return (isset($GLOBALS['_SESSION'][$key]) ? $GLOBALS['_SESSION'][$key] : null);
        }
        return $GLOBALS['_SESSION'];
    }

    public static function forget(){
        session_destroy();
    }
}