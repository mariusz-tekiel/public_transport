<?php

try {
    $pdo = new PDO("pgsql:host=localhost;dbname=transport_db", "transport_user", "pumctppl71");
    echo "Połączenie działa!";
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
