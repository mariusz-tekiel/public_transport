interface Vehicle {
    vehicle_id: string;
    latitude: number;
    longitude: number;
    name: string;
}

let Mapa: any;
const vehicleMarkers: Record<string, any> = {}; // Przechowywanie markerów pojazdów

function TargeoMapInitialize() {
    const mapOptions = {
        container: 'TargeoMapContainer',
        z: 12,
        c: { y: 52.2298, x: 21.0122 }, // Warszawa
        modArguments: {
            Layers: { modes: ['map', 'satellite'] },
            POI: { disabled: false, visible: true },
            Traffic: { disabled: false, visible: true }
        }
    };
    Mapa = new Targeo.Map(mapOptions);
    Mapa.initialize();

    updateVehicles(); // Pobieramy pojazdy od razu po załadowaniu mapy
    setInterval(updateVehicles, 10000); // Odświeżanie co 10 sek.
}

async function updateVehicles() {
    try {
        const response = await fetch('/public_transport/api/vehicles'); // Poprawiona ścieżka
        if (!response.ok) throw new Error(`Błąd API: ${response.status}`);
        const vehicles = await response.json();
        console.log("📡 Otrzymano pojazdy:", vehicles);
        vehicles.forEach(addVehicleToMap);
    } catch (error) {
        console.error('❌ Błąd pobierania pojazdów:', error);
    }
}

function addVehicleToMap(vehicle: Vehicle) {
    if (!vehicle.latitude || !vehicle.longitude) {
        console.warn(`⚠ Pominięto pojazd ${vehicle.vehicle_id} - brak współrzędnych.`);
        return;
    }

    if (vehicleMarkers[vehicle.vehicle_id]) {
        // Aktualizacja pozycji pojazdu
        vehicleMarkers[vehicle.vehicle_id].moveTo({ y: vehicle.latitude, x: vehicle.longitude });
    } else {
        // Tworzymy nowy marker, jeśli pojazdu jeszcze nie ma
        const vehicleMarker = new Targeo.MapObject.Point({
            c: { y: vehicle.latitude, x: vehicle.longitude },
            icon: '/assets/bus_icon.png', // Ścieżka do ikonki pojazdu
            title: `Linia ${vehicle.name}`
        });

        Mapa.addObject(vehicleMarker);
        vehicleMarkers[vehicle.vehicle_id] = vehicleMarker;
    }
}

// Uruchamiamy mapę po załadowaniu strony
document.addEventListener('DOMContentLoaded', () => {
    TargeoMapInitialize();
});
