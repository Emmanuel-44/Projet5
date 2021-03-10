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
        $db = new PDO(
            'mysql:host=localhost;dbname=oc_projet5_blog;charset=utf8', 'root', ''
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
}