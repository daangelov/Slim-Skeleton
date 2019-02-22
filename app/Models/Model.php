<?php

namespace App\Models;

use PDO;
use PDOException;
use PDOStatement;

class Model
{
    protected static $table;

    /**
     * @param PDO $db
     * @param array $clauses
     * @return bool|PDOStatement|string
     */
    public static function where($db, $clauses)
    {
        $where = '';
        $values = [];
        foreach ($clauses as $column => $value) {
            $where .= $column . ' = :' . $column . ' AND ';
            $values[$column] = $value;
        }
        $where .= 'TRUE';

        $stmt = false;
        try {
            $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE " . $where . ";");
            $stmt->execute($values);
        } catch (PDOException $e) {
            if ($_ENV['APP_DEBUG']) {
                if ($stmt instanceof PDOStatement) {
                    error_log($stmt->queryString);
                }
                error_log($e->getMessage());
            }

            return false;
        }

        return $stmt;
    }

    /* Only with one where clause
    public static function where($db, $column, $value)
    {
        try {
            $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE $column = :val;");
            $stmt->execute(['val' => $value]);
        } catch (PDOException $e) {
            if ($_ENV['APP_DEBUG']) {
                error_log($e->getMessage());
            }

            return false;
        }

        return $stmt;
    }
    */
}