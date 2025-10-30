<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Mis Citas</h2>
            <a href="{{ route('citas.create') }}" class="bg-primary text-light hover:bg-secondary py-2 px-4 rounded">Agendar Nueva Cita</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4 text-secondary">Mis Citas</h1>
            
            @if(session('success'))
                <div class="bg-success text-light p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($citas->isEmpty())
                <p class="text-gray-600">No tienes citas agendadas.</p>
            @else
                <div class="overflow-x-auto bg-surface">
                    <table class="min-w-full divide-y divide-metal">
                        <thead class="bg-secondary text-light">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nombre Completo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Barbero</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Servicios</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Costo</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-metal text-secondary">
                            @foreach($citas as $cita)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->nombre_completo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->fecha }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->hora }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->barbero->nombre_completo ?? 'No disponible' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->servicios_nombres_texto }}</td>
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

    @push('scripts')
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
    @endpush
</x-app-layout>
