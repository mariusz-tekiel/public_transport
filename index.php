<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\MapController;
use App\Controllers\VehicleController;

$uri = trim($_SERVER['REQUEST_URI'], '/');

if ($uri === '' || $uri === 'index.php' || $uri === 'public_transport' || $uri === 'public_transport/index.php') {
    (new MapController())->render();
} elseif ($uri === 'public_transport/map') {
    (new MapController())->render();
} elseif ($uri === 'public_transport/api/vehicles' || $uri === 'api/vehicles') {
    (new VehicleController())->getVehicles();
} elseif ($uri === 'public_transport/api/fetch-vehicles' || $uri === 'api/fetch-vehicles') {
    (new VehicleController())->fetchVehiclesFromAPI();
} else {
    http_response_code(404);
    echo "404 - Nie znaleziono strony: " . htmlspecialchars($uri);
}
