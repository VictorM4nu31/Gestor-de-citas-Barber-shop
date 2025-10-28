<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Servicios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold">Barbería</a>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">
            <h1 class="text-3xl font-semibold">Lista de Servicios</h1>
            <a href="{{ route('servicios.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>Crear Servicio</span>
            </a>
        </div>

        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div id="success-message" class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 border">ID</th>
                        <th class="py-2 px-4 border">Nombre</th>
                        <th class="py-2 px-4 border">Descripción</th>
                        <th class="py-2 px-4 border">Duración (min)</th>
                        <th class="py-2 px-4 border">Precio</th>
                        <th class="py-2 px-4 border">Foto</th>
                        <th class="py-2 px-4 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicios as $servicio)
                    <tr>
                        <td class="py-2 px-4 border">{{ $servicio->id }}</td>
                        <td class="py-2 px-4 border">{{ $servicio->nombre }}</td>
                        <td class="py-2 px-4 border">{{ $servicio->descripcion }}</td>
                        <td class="py-2 px-4 border">{{ $servicio->duracion }}</td>
                        <td class="py-2 px-4 border">{{ $servicio->precio }}</td>
                        <td class="py-2 px-4 border">
                            @if ($servicio->foto)
                                <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}" class="w-16 h-16 object-cover rounded">
                            @else
                                Sin foto
                            @endif
                        </td>
                        <td class="py-2 px-4 border">
                            <a href="{{ route('servicios.edit', $servicio->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-2 rounded">Editar</a>
                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded" onclick="return confirm('¿Estás seguro de que deseas eliminar este servicio?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el elemento del mensaje de éxito
            const successMessage = document.getElementById('success-message');
            
            if (successMessage) {
                // Ocultar el mensaje después de 4 segundos
                setTimeout(() => {
                    successMessage.style.opacity = 0;
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 0); // Tiempo para desvanecerse
                }, 6000); // Tiempo de espera en milisegundos
            }
        });
    </script>
</body>
</html>
