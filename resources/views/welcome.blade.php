<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.css" rel="stylesheet">
    <title>Barbería</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #mi_mapa {
            height: 400px;
            width: 100%;
        }
        .barbero-card {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .barbero-card img {
            border-radius: 50%;
            height: 150px;
            width: 150px;
            object-fit: cover;
        }
        .barbero-card h3 {
            font-size: 1.25rem;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .barbero-card p {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <x-user.navbar />

    <main class="bg-surface min-h-screen">
        <section id="home" class="text-center py-12 bg-primary">
            <div class="container mx-auto">
                <h1 class="text-4xl font-bold mb-4 text-light">Bienvenidos a Nuestra Barbería</h1>
                <p class="text-lg mb-8 text-graylight">La mejor experiencia de barbería en la ciudad. ¡Reserva tu cita hoy!</p>
                <a href="#contact" class="bg-light text-primary hover:bg-graylight py-2 px-4 rounded">Reserva una Cita</a>
            </div>
        </section>

        <section id="about" class="text-center py-12 bg-secondary">
            <div class="container mx-auto flex flex-col md:flex-row items-center">
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-semibold mb-4 text-primary">Sobre Nosotros</h2>
                    <p class="text-lg mb-8 text-graylight">Conoce al equipo de profesionales que te atenderá con el mejor servicio en nuestra barbería.</p>
                    <!-- Título de Ubicación -->
                    <h3 class="text-2xl font-semibold mb-4 text-primary">Ubicación</h3>
                    <!-- Mapa -->
                    <div id="mi_mapa"></div>
                </div>
                <div class="w-full md:w-1/2 md:pl-8 mt-8 md:mt-0">
                    <h2 class="text-3xl font-semibold mb-4 text-primary">Información de la Barbería</h2>
                    <p class="text-lg mb-4 text-graylight">Dirección: Calle Ejemplo 123, Ciudad</p>
                    <p class="text-lg mb-4 text-graylight">Teléfono: (123) 456-7890</p>
                    <p class="text-lg text-graylight">Horario: Lunes a Sábado - 9:00 AM a 7:00 PM</p>
                </div>
            </div>
        </section>

        <section id="services" class="text-center py-12 bg-surface">
            <div class="container mx-auto">
                <h2 class="text-3xl font-semibold mb-4 text-secondary">Nuestros Servicios</h2>
                <p class="text-lg mb-8 text-muted">Descubre los servicios que ofrecemos para ti.</p>
                <!-- Agrega más contenido aquí -->
            </div>
        </section>

        <!-- Nueva sección de Barberos -->
        <section id="barberos" class="text-center py-12 bg-background">
            <div class="container mx-auto">
                <h2 class="text-3xl font-semibold mb-4 text-secondary">Nuestros Barberos</h2>
                <p class="text-lg mb-8 text-muted">Conoce a nuestros talentosos barberos que están listos para atenderte.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($barberos as $barbero)
                        <div class="barbero-card border border-metal bg-light">
                            @if($barbero->foto)
                                <img src="{{ asset('storage/' . $barbero->foto) }}" alt="{{ $barbero->nombre_completo }}">
                            @else
                                <img src="https://via.placeholder.com/150" alt="{{ $barbero->nombre_completo }}">
                            @endif
                            <h3 class="text-secondary">{{ $barbero->nombre_completo }}</h3>
                            <p class="text-muted">{{ $barbero->especialidad }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="contact" class="text-center py-12 bg-background">
            <div class="container mx-auto">
                <h2 class="text-3xl font-semibold mb-4 text-secondary">Contacto</h2>
                <p class="text-lg mb-8 text-muted">Estamos aquí para ayudarte. ¡Envíanos un mensaje o reserva una cita!</p>
                <!-- Agrega el formulario de contacto aquí -->
            </div>
        </section>
    </main>

    <x-user.footer />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.js"></script>
    <script>
        let map = L.map('mi_mapa').setView([20.48621, -99.21711], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([20.48623, -99.21709]).addTo(map).bindPopup("Barberia MasterCut").openPopup();
    </script>
</body>
</html>
