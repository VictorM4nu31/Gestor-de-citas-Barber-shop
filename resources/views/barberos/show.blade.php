<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Barbero</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-surface">
    <header class="bg-secondary text-light">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-light">Barbería</a>
            <nav class="space-x-4">
                <a href="{{ route('barberos.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la Lista</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-secondary">Ver Barbero</h1>
        <div class="bg-light border border-metal rounded-lg shadow-md p-6">
            <div class="mb-4">
                <strong class="text-secondary">Nombre Completo:</strong>
                <p class="text-muted">{{ $barbero->nombre_completo }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Email:</strong>
                <p class="text-muted">{{ $barbero->email }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Teléfono:</strong>
                <p class="text-muted">{{ $barbero->telefono }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Especialidad:</strong>
                <p class="text-muted">{{ $barbero->especialidad }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-secondary">Experiencia:</strong>
                <p class="text-muted">{{ $barbero->experiencia }}</p>
            </div>
            @if ($barbero->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-32 h-32 object-cover rounded-md border border-metal">
                </div>
            @endif
        </div>
    </main>
</body>
</html>
