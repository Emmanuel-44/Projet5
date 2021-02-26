<?php
namespace models;

class DBFactory
{
    public static function dbConnect()
    {
        $db = new \PDO('mysql:host=localhost;dbname=oc_projet5_blog;charset=utf8', 'root', '');
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $db;
    }
}