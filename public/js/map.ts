interface Vehicle {
    vehicle_id: string;
    latitude: number;
    longitude: number;
    name: string;
}

let Mapa: any;
const vehicleMarkers: Record<string, any> = {}; // Obiekt do przechowywania markerów pojazdów

function TargeoMapInitialize() {
    const mapOptions = {
        container: 'TargeoMapContainer',
        z: 12,
        c: { y: 52.2298, x: 21.0122 }, // Warszawa jako domyślna lokalizacja
        modArguments: {
            Layers: { modes: ['map', 'satellite'] },
            POI: { disabled: false, visible: true },
            Traffic: { disabled: false, visible: true }
        }
    };
    Mapa = new Targeo.Map(mapOptions);
    Mapa.initialize();

    updateVehicles(); // Pobieramy pojazdy od razu po załadowaniu mapy
    setInterval(updateVehicles, 10000); // Odświeżanie co 10 sekund
}

function addVehicleToMap(vehicle: Vehicle) {
    if (vehicleMarkers[vehicle.vehicle_id]) {
        // Jeśli pojazd już istnieje, aktualizujemy jego pozycję
        vehicleMarkers[vehicle.vehicle_id].moveTo({ y: vehicle.latitude, x: vehicle.longitude });
    } else {
        // Jeśli pojazd jeszcze nie istnieje, tworzymy nowy punkt na mapie
        const vehicleMarker = new Targeo.MapObject.Point({
            c: { y: vehicle.latitude, x: vehicle.longitude },
            icon: '/assets/bus_icon.png', // Ścieżka do ikonki pojazdu
            title: vehicle.name
        });

        Mapa.addObject(vehicleMarker);
        vehicleMarkers[vehicle.vehicle_id] = vehicleMarker;
    }
}

function updateVehicles() {
    fetch('/api/vehicles')
        .then(response => response.json())
        .then((vehicles: Vehicle[]) => {
            Mapa.clearObjects(); // Czyścimy stare pojazdy
            vehicles.forEach(addVehicleToMap);
        })
        .catch(error => console.error('Błąd pobierania pojazdów:', error));
}
