<?php
namespace core;

use PDO;

/**
 * Database factory
 */
class DBFactory
{
    /**
     * Connect to Database
     *
     * @return PDO
     */
    public static function dbConnect() : PDO
    {
        $database = new PDO(
            'mysql:host=localhost;dbname=oc_projet5_blog;charset=utf8', 'root', ''
        );
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $database;
    }
}
