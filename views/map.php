<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://mapa.targeo.pl/Targeo.html?vn=3_0&k=ZjlhMmU2Nzc5OGQwNjczMWZkYWE2MGRlZTY1ZjRkY2U3M2E1M2ZkYg==&f=TargeoMapInitialize&e=TargeoMapContainer&ln=pl" type="text/javascript"></script>

    <title>Mapa Targeo - Monitoring pojazdów</title>
    <script type="text/javascript">
        var Mapa;

        function TargeoMapInitialize() {
            var mapOptions = {
                container: 'TargeoMapContainer',
                z: 12,
                c: {
                    y: 52.2298,
                    x: 21.0122
                }, // Warszawa
                modArguments: {
                    Layers: {
                        modes: ['map', 'satellite']
                    },
                    POI: {
                        disabled: false,
                        visible: true
                    },
                    Traffic: {
                        disabled: false,
                        visible: true
                    }
                }
            };
            Mapa = new Targeo.Map(mapOptions);
            Mapa.initialize();
        }

        function addVehicleToMap(vehicle) {
            var vehiclePoint = new Targeo.MapObject.Point({
                c: {
                    y: vehicle.latitude,
                    x: vehicle.longitude
                },
                icon: '/assets/bus_icon.png',
                title: vehicle.name
            });
            Mapa.addObject(vehiclePoint);
        }

        function updateVehicles() {
            fetch('/public_transport/api/vehicles')
                .then(response => response.json())
                .then(vehicles => {
                    Mapa.clearObjects(); // Usuń stare pojazdy
                    vehicles.forEach(addVehicleToMap); // Dodaj nowe
                });
        }

        setInterval(updateVehicles, 10000); // Odświeżanie co 10 sek.
    </script>
</head>

<body>
    <div id="TargeoMapContainer" style="width:100%; height:600px;"></div>
    <script src="/public_transport/public/js/map.ts" type="module"></script>
    <!-- <script src="https://mapa.targeo.pl/Targeo.html?vn=3_0&k=ZjlhMmU2Nzc5OGQwNjczMWZkYWE2MGRlZTY1ZjRkY2U3M2E1M2ZkYg==&f=TargeoMapInitialize&e=TargeoMapContainer&ln=pl" type="text/javascript"></script> -->
</body>

</html>