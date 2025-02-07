<?php

namespace App\Controllers;

use App\Models\Vehicle;

class VehicleController
{
    public function fetchVehiclesFromAPI()
    {
        $apiUrl = "https://api.przewoznik.com/vehicles"; // Adres API przewoźnika

        // Wyłączenie weryfikacji SSL, aby uniknąć błędów
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ]);

        // Pobranie danych z API
        $response = file_get_contents($apiUrl, false, $context);

        if ($response === false) {
            http_response_code(500);
            echo json_encode(["error" => "Nie udało się pobrać danych z API"]);
            return;
        }

        $vehicles = json_decode($response, true);
        if (!$vehicles) {
            http_response_code(500);
            echo json_encode(["error" => "Błąd dekodowania JSON"]);
            return;
        }

        Vehicle::saveVehicles($vehicles);

        echo json_encode(["status" => "success"]);
    }


    public function getVehicles()
    {
        header('Content-Type: application/json');
        echo json_encode(Vehicle::getLatestPositions());
    }
}
