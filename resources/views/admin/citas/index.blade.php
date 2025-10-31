<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Gestión de Citas</h2>
            <a href="{{ route('admin.citas.create') }}" class="bg-primary text-light hover:bg-secondary py-2 px-4 rounded">Agendar Nueva Cita</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4 text-secondary">Gestión de Citas</h1>
            
            @if(session('success'))
                <div class="bg-success text-light p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($citas->isEmpty())
                <p class="text-muted">No hay citas agendadas.</p>
            @else
                <div class="overflow-x-auto bg-surface">
                    <table class="min-w-full divide-y divide-metal">
                        <thead class="bg-secondary text-light">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nombre Completo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Barbero</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Servicios</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Costo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-metal text-secondary">
                            @foreach($citas as $cita)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->nombre_completo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->fecha }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->hora }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->barbero->nombre_completo ?? 'No disponible' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->servicios_nombres_texto }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cita->costo }}$</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success/10 text-success">
                                            Activa
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.citas.show', $cita->id) }}" class="text-primary hover:text-primary/90 mr-2">Ver</a>
                                        <a href="{{ route('admin.citas.edit', $cita->id) }}" class="text-info hover:text-info/90 mr-2">Editar</a>
                                        <form action="{{ route('admin.citas.destroy', $cita->id) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger hover:text-danger/90">Eliminar</button>
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
                if (confirm('¿Estás seguro de que deseas eliminar esta cita?')) {
                    form.submit();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>