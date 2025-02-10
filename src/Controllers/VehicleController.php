<?php

namespace App\Controllers;

use App\Models\Vehicle;

class VehicleController
{
    public function fetchVehiclesFromAPI()
    {
        $apiKey = "5cd346e7-cf0e-4759-97aa-700a71379146";
        $apiUrl = "https://api.um.warszawa.pl/api/action/busestrams_get/?resource_id=f2e5503e927d-4ad3-9500-4ab9e55deb59&apikey=" . $apiKey . "&type=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            error_log("Błąd pobierania danych z API ZTM: $httpCode - $curlError");
            http_response_code(500);
            echo json_encode(["error" => "Błąd pobierania danych z API ZTM"]);
            return;
        }

        $vehicles = json_decode($response, true);
        if (!$vehicles || !isset($vehicles["result"])) {
            error_log("Błąd dekodowania JSON");
            http_response_code(500);
            echo json_encode(["error" => "Błąd dekodowania danych JSON"]);
            return;
        }

        error_log("Pobrano " . count($vehicles["result"]) . " pojazdów z API ZTM");
        Vehicle::saveVehicles($vehicles["result"]);
        echo json_encode(["status" => "success"]);
    }

    public function getVehicles()
    {
        header('Content-Type: application/json');
        $vehicles = Vehicle::getLatestPositions();

        if (empty($vehicles)) {
            error_log("🚨 Brak pojazdów w bazie danych!");
            echo json_encode(["error" => "Brak pojazdów"]);
            return;
        }

        error_log("📡 Zwracam " . count($vehicles) . " pojazdów do frontendu.");
        echo json_encode($vehicles);
    }
}
