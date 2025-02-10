<?php

try {
    $pdo = new PDO("pgsql:host=localhost;dbname=transport_db", "transport_user", "pumctppl71");
    echo "Połączenie działa!";

    $apiUrl = "https://api.przewoznik.com/vehicles";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    echo "HTTP Code: " . $httpCode . "\n";
    echo "Response: " . $response . "\n";
    echo "cURL Error: " . $curlError . "\n";
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
