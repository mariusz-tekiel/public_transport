<?php

namespace App\Models;

use PDO;
use App\Database;

class Vehicle
{
    public static function saveVehicles(array $vehicles)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO vehicles_positions (vehicle_id, latitude, longitude, name, timestamp) 
                               VALUES (:vehicle_id, :latitude, :longitude, :name, NOW())
                               ON CONFLICT (vehicle_id) DO UPDATE 
                               SET latitude = EXCLUDED.latitude, 
                                   longitude = EXCLUDED.longitude, 
                                   timestamp = NOW()");
        try {
            foreach ($vehicles as $vehicle) {
                error_log("Zapisuję pojazd: " . json_encode($vehicle));
                $stmt->execute([
                    ':vehicle_id' => $vehicle['VehicleNumber'] ?? '',
                    ':latitude' => $vehicle['Lat'] ?? 0,
                    ':longitude' => $vehicle['Lon'] ?? 0,
                    ':name' => 'Autobus ZTM'
                ]);
            }
        } catch (\PDOException $e) {
            error_log("Błąd zapisu do bazy: " . $e->getMessage());
        }
    }

    public static function getLatestPositions()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT vehicle_id, latitude, longitude, name FROM vehicles_positions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
