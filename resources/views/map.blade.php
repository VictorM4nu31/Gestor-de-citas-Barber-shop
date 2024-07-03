<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Google</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div id="map"></div>
        </div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
    <script>
        function initMap() {
            // Coordenadas de ejemplo (Central Park, Nueva York)
            var location = {lat: 40.785091, lng: -73.968285};

            // Crear el mapa
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: location
            });

            // Añadir un marcador
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }
    </script>
</body>
</html>