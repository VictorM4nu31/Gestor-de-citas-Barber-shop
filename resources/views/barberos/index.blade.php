<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Barberos</title>
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
            <h1 class="text-3xl font-semibold">Lista de Barberos</h1>
            <a href="{{ route('barberos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>Crear Barbero</span>
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
                        <th class="py-2 px-4 border">Nombre Completo</th>
                        <th class="py-2 px-4 border">Email</th>
                        <th class="py-2 px-4 border">Teléfono</th>
                        <th class="py-2 px-4 border">Especialidad</th>
                        <th class="py-2 px-4 border">Experiencia</th>
                        <th class="py-2 px-4 border">Foto</th>
                        <th class="py-2 px-4 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barberos as $barbero)
                    <tr>
                        <td class="py-2 px-4 border">{{ $barbero->id }}</td>
                        <td class="py-2 px-4 border">{{ $barbero->nombre_completo }}</td>
                        <td class="py-2 px-4 border">{{ $barbero->email }}</td>
                        <td class="py-2 px-4 border">{{ $barbero->telefono }}</td>
                        <td class="py-2 px-4 border">{{ $barbero->especialidad }}</td>
                        <td class="py-2 px-4 border">{{ $barbero->experiencia }} años</td>
                        <td class="py-2 px-4 border">
                            @if ($barbero->foto)
                                <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-16 h-16 object-cover rounded">
                            @else
                                Sin foto
                            @endif
                        </td>
                        <td class="py-2 px-4 border flex flex-col space-y-2 md:space-y-0 md:flex-row md:space-x-2">
                            <a href="{{ route('barberos.edit', $barbero->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-2 rounded">Editar</a>
                            <form action="{{ route('barberos.destroy', $barbero->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded" onclick="return confirm('¿Estás seguro de que deseas eliminar este barbero?')">Eliminar</button>
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
