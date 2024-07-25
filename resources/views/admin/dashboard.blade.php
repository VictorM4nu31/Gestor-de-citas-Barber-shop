<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control de Administrador</title>
    <!-- Incluye los estilos de Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.4.1/flowbite.min.css" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="font-poppins antialiased bg-black text-white text-lg">
    <div id="view" class="h-full w-screen flex flex-row" x-data="{ sidenav: true }">
        <button
            @click="sidenav = true"
            class="p-2 border-2 bg-red-700 rounded-md border-red-600 shadow-lg text-white focus:bg-red-600 focus:outline-none absolute top-0 left-0 sm:hidden"
        >
            <svg class="w-5 h-5 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </button>
        <div
            id="sidebar"
            class="bg-red-700 h-screen md:block shadow-xl px-3 w-30 md:w-60 lg:w-60 overflow-x-hidden transition-transform duration-300 ease-in-out"
            x-show="sidenav"
            @click.away="sidenav = false"
        >
            <div class="space-y-6 md:space-y-10 mt-10">
                <h1 class="font-bold text-4xl text-center md:hidden text-white">M<span class="text-black">.</span></h1>
                <h1 class="hidden md:block font-bold text-sm md:text-xl text-center text-white">MasterCut<span class="text-black">.</span></h1>
                <div id="profile" class="space-y-3">
                    <img
                        src="https://images.unsplash.com/photo-1603415526960-f0b7f3fc995b?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
                        alt="Avatar user" class="w-10 md:w-16 rounded-full mx-auto"
                    />
                    <div>
                        <h2 class="font-medium text-xs md:text-sm text-center text-white">Bienvenido, Usuario</h2>
                    </div>
                </div>
                <div class="flex flex-col">
                    <button id="link-employees" class="bg-black text-base font-medium text-white py-2 px-2 hover:bg-red-600 hover:text-white hover:scale-105 rounded-md transition duration-150 ease-in-out">
                        <svg class="w-6 h-6 fill-current inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 1016 0 8 8 0 10-16 0zm8-4a1 1 0 110 2 1 1 0 010-2zm-2 4a1 1 0 110 2 1 1 0 010-2zm4 0a1 1 0 110 2 1 1 0 010-2zm2-4a1 1 0 110 2 1 1 0 010-2z"></path>
                        </svg>
                        <span>Gestionar Empleados</span>
                    </button>
                    <button id="link-services" class="bg-black mt-2 text-base font-medium text-white py-2 px-2 hover:bg-red-600 hover:text-white hover:scale-105 rounded-md transition duration-150 ease-in-out">
                        <svg class="w-6 h-6 fill-current inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 3a1 1 0 000 2h12a1 1 0 100-2H4zM3 7a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1zM3 11a1 1 0 011-1h10a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                        </svg>
                        <span>Gestionar Servicios</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="bg-white flex-grow text-black p-6">
            <h1 class="text-4xl font-semibold mb-6">Panel de Control</h1>
            <!-- Contenido del panel de control -->
            <div id="employees-section" class="hidden">
                @include('admin.barberos.table-users')
            </div>
            <div id="services-section" class="hidden">
                @include('admin.servicios.manage-services')
            </div>
        </div>
    </div>

    <script>
        document.getElementById('link-employees').addEventListener('click', function() {
            document.getElementById('employees-section').classList.remove('hidden');
            document.getElementById('services-section').classList.add('hidden');
        });

        document.getElementById('link-services').addEventListener('click', function() {
            document.getElementById('services-section').classList.remove('hidden');
            document.getElementById('employees-section').classList.add('hidden');
        });

        // Mostrar la sección de empleados por defecto
        document.getElementById('employees-section').classList.remove('hidden');
    </script>
</body>
</html>
