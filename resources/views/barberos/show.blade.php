<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Barbero</title>
    @vite('resources/css/app.css')
</head>
<body>
    <header class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold">Barbería</a>
            <nav class="space-x-4">
                <a href="{{ route('barberos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded">Volver a la Lista</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6">Ver Barbero</h1>
        <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6">
            <div class="mb-4">
                <strong class="text-gray-700">Nombre Completo:</strong>
                <p>{{ $barbero->nombre_completo }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Email:</strong>
                <p>{{ $barbero->email }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Teléfono:</strong>
                <p>{{ $barbero->telefono }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Especialidad:</strong>
                <p>{{ $barbero->especialidad }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Experiencia:</strong>
                <p>{{ $barbero->experiencia }}</p>
            </div>
            <!-- @if ($barbero->foto)
                <div class="mb-4">
                    <strong class="text-gray-700">Foto:</strong>
                    <img src="{{ Storage::url($barbero->foto) }}" alt="Foto del barbero" class="mt-2 w-32 h-32 object-cover">
                </div>
            @endif -->
            @if ($barbero->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-32 h-32 object-cover rounded-md">
                </div>
            @endif
        </div>
    </main>
</body>
</html>
