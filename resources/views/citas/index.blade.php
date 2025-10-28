<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center flex-wrap">
            <a href="/" class="text-xl font-bold">Barbería</a>
            <nav>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="text-white hover:text-gray-300">Cerrar sesión</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">Mis Citas</h1>
            <a href="{{ route('citas.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded mb-4 inline-block">Agendar Nueva Cita</a>
            
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($citas->isEmpty())
                <p class="text-gray-600">No tienes citas agendadas.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barbero</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicios</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th>
                                <th class="px-6 py-3 bg-gray-50"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($citas as $cita)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->nombre_completo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->fecha }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->hora }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->barbero->nombre_completo ?? 'No disponible' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->servicios }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->costo }}$</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">Cancelar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>

    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
