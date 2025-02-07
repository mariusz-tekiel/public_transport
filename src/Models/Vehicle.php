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

        foreach ($vehicles as $vehicle) {
            $stmt->execute([
                ':vehicle_id' => $vehicle['id'],
                ':latitude' => $vehicle['latitude'],
                ':longitude' => $vehicle['longitude'],
                ':name' => $vehicle['name'] ?? 'Brak nazwy'
            ]);
        }
    }

    public static function getLatestPositions()
    {
        $pdo = Database::getConnection();
        // $stmt = $pdo->query("SELECT vehicle_id, latitude, longitude, name FROM vehicles_positions WHERE timestamp >= NOW() - INTERVAL '10 minutes'");

        $stmt = $pdo->query("SELECT vehicle_id, latitude, longitude, name FROM vehicles_positions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
