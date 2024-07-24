<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbería</title>
    @vite('resources/css/app.css')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap" async defer></script>
    <script>
        function initMap() {
            var barberiaUbicacion = { lat: 19.432608, lng: -99.133209 }; // Latitud y longitud de ejemplo
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: barberiaUbicacion
            });
            var marker = new google.maps.Marker({
                position: barberiaUbicacion,
                map: map
            });
        }
    </script>
</head>
<body>
    <header class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold">Barbería</a>
            <nav class="space-x-4 hidden md:flex">
                <a href="#home" class="hover:text-gray-400">Inicio</a>
                <a href="#about" class="hover:text-gray-400">Sobre Nosotros</a>
                <a href="#services" class="hover:text-gray-400">Servicios</a>
                <a href="#contact" class="hover:text-gray-400">Contacto</a>
            </nav>
            <div class="space-x-2">
                @guest
                    <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Registrarse</a>
                @endguest
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">Cerrar Sesión</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <main class="bg-gray-100 min-h-screen">
        <section id="home" class="text-center py-12">
            <div class="container mx-auto">
                <h1 class="text-4xl font-bold mb-4">Bienvenidos a Nuestra Barbería</h1>
                <p class="text-lg mb-8">La mejor experiencia de barbería en la ciudad. ¡Reserva tu cita hoy!</p>
                <a href="#contact" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Reserva una Cita</a>
            </div>
        </section>

        <section id="about" class="text-center py-12 bg-white">
            <div class="container mx-auto">
                <h2 class="text-3xl font-semibold mb-4">Sobre Nosotros</h2>
                <p class="text-lg mb-8">Conoce al equipo de profesionales que te atenderá con el mejor servicio en nuestra barbería.</p>
                <div id="map" class="w-full h-64 bg-gray-200"></div>
                <!-- Agrega más contenido aquí -->
            </div>
        </section>

        <section id="services" class="text-center py-12 bg-gray-200">
            <div class="container mx-auto">
                <h2 class="text-3xl font-semibold mb-4">Nuestros Servicios</h2>
                <p class="text-lg mb-8">Descubre los servicios que ofrecemos para ti.</p>
                <!-- Agrega más contenido aquí -->
            </div>
        </section>

        <section id="contact" class="text-center py-12 bg-white">
            <div class="container mx-auto">
                <h2 class="text-3xl font-semibold mb-4">Contacto</h2>
                <p class="text-lg mb-8">Estamos aquí para ayudarte. ¡Envíanos un mensaje o reserva una cita!</p>
                <!-- Agrega el formulario de contacto aquí -->
            </div>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Barbería. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbería</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap&loading=async" async defer></script>
    <script>
        function initMap() {
            var barberiaUbicacion = { lat: 20.486253578721648, lng: -99.2171693197334 }; // Latitud y longitud de ejemplo 
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: barberiaUbicacion
            });
            var marker = new google.maps.Marker({
                position: barberiaUbicacion,
                map: map
            });
        }
    </script>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-white text-lg font-bold">Barbería</a>
            <div class="flex items-center">
                <a href="/barberos" class="text-gray-300 hover:text-white px-3 py-2">Barberos</a>
                <a href="/servicios" class="text-gray-300 hover:text-white px-3 py-2">Servicios</a>
                @guest
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="text-gray-300 hover:text-white px-3 py-2">Registrarse</a>
                @else
                    <a href="{{ route('logout') }}" class="text-gray-300 hover:text-white px-3 py-2">Cerrar Sesión</a>
                @endguest
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-8">
        <h1 class="text-4xl font-bold mb-4">Bienvenidos a nuestra Barbería</h1>
        <p class="mb-8">Aquí puedes encontrar los mejores servicios y barberos para ti.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Aquí se mostrarán las fotos y la información de la barbería -->
        </div>
        <h2 class="text-2xl font-bold mt-8 mb-4">Nosotros</h2>
        <p class="mb-4">Nuestra barbería está ubicada en un lugar conveniente para ti.</p>
        <div id="map" class="w-full h-64 bg-gray-200"></div>
    </div>
</body>
</html>
