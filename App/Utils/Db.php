<?php

namespace App\Utils;

use PDO;
use PDOException;

/**
 * Class Db
 * A singleton class that returns a PDO connection
 *
 * @package App\Utils
 */
class Db
{
    /**
     * Db constructor empty
     * Prevent construction of another instance
     */
    private function __construct()
    {
    }

    /**
     * Db clone method empty
     * Prevent cloning the instance
     */
    private function __clone()
    {
    }

    /**
     * Try to connect to database.
     *
     * @return PDO
     */
    public static function getConnection()
    {
        try {
            $db = new PDO(
                'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
                getenv('DB_USER'),
                getenv('DB_PASS')
            );
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $error = getenv('APP_DEBUG') === "true" ?
                "Error: " . $e->getMessage() : "Error: Can't connect to database.";

            die($error);
        }

        return $db;
    }
}