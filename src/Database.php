<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    private static $pdo;

    public static function getConnection()
    {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO("pgsql:host=localhost;dbname=transport_db", "transport_user", "pumctppl71", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("Błąd połączenia z bazą danych: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
