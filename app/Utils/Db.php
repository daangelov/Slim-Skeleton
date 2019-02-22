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
            $connectionString = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8';

            $db = new PDO($connectionString, $_ENV['DB_USER'], $_ENV['DB_PASS']);

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $error = $_ENV['APP_DEBUG'] === "true" ?
                "Error: " . $e->getMessage() : "Error: Can't connect to database.";

            die($error);
        }

        return $db;
    }
}