<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold">Barbería</a>
            <nav>
                <a href="{{ route('logout') }}" class="text-white hover:text-gray-300">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">Bienvenido, {{ Auth::user()->name }}</h1>
            <div class="flex flex-col md:flex-row md:justify-between space-y-4 md:space-y-0 md:space-x-4">
                <a href="{{ route('profile.show') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded flex items-center space-x-2">
                    <i class="fas fa-user"></i>
                    <span class="hidden md:inline">Ver Perfil</span>
                </a>
                <a href="{{ route('citas.index') }}" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded flex items-center space-x-2">
                    <i class="fas fa-calendar-check"></i>
                    <span class="hidden md:inline">Ver Citas Agendadas</span>
                </a>
                <a href="{{ route('citas.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded flex items-center space-x-2">
                    <i class="fas fa-calendar-plus"></i>
                    <span class="hidden md:inline">Agendar Cita</span>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
