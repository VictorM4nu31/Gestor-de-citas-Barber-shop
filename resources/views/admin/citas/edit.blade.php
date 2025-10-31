<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar Cita</h2>
            <a href="{{ route('admin.citas.index') }}" class="bg-primary hover:bg-secondary text-light py-2 px-4 rounded">Volver a la Lista</a>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4 text-secondary">Editar Cita #{{ $cita->id }}</h1>

            @if(session('error'))
                <div id="error-message" class="bg-danger text-light p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.citas.update', $cita->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <!-- Nombre Completo -->
                        <div>
                            <label for="nombre_completo" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <input type="text" id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo', $cita->nombre_completo) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <!-- Número de Teléfono -->
                        <div>
                            <label for="numero_telefono" class="block text-sm font-medium text-gray-700">Número de Teléfono</label>
                            <input type="text" id="numero_telefono" name="numero_telefono" value="{{ old('numero_telefono', $cita->numero_telefono) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <!-- Correo Electrónico -->
                        <div>
                            <label for="correo_electronico" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input type="email" id="correo_electronico" name="correo_electronico" value="{{ old('correo_electronico', $cita->correo_electronico) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Barbero -->
                        <div>
                            <label for="id_barbero" class="block text-sm font-medium text-gray-700">Seleccionar Barbero</label>
                            <select id="id_barbero" name="id_barbero" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Seleccionar barbero</option>
                                @foreach($barberos as $barbero)
                                    <option value="{{ $barbero->id }}" {{ old('id_barbero', $cita->id_barbero) == $barbero->id ? 'selected' : '' }}>{{ $barbero->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fecha -->
                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" id="fecha" name="fecha" value="{{ old('fecha', $cita->fecha) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <!-- Hora -->
                        <div>
                            <label for="hora" class="block text-sm font-medium text-gray-700">Hora</label>
                            <select id="hora" name="hora" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                                @for($i = 9; $i <= 20; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00" {{ old('hora', $cita->hora) == str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Servicios -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Servicios</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($servicios as $servicio)
                            <div>
                                <input type="checkbox" id="servicio_{{ $servicio->id }}" name="servicios[]" value="{{ $servicio->id }}" class="mr-2" 
                                    {{ in_array($servicio->id, old('servicios', $cita->servicios->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label for="servicio_{{ $servicio->id }}" class="text-sm text-gray-600">{{ $servicio->nombre }} - ${{ $servicio->precio }}</label>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" id="total_servicios" name="total_servicios" value="{{ $cita->costo }}">
                    <p id="costo_total" class="text-lg font-semibold mt-4">Total: ${{ $cita->costo }}</p>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Actualizar Cita</button>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const servicios = document.querySelectorAll('input[name="servicios[]"]');
            const totalServicios = document.getElementById('total_servicios');
            const costoTotal = document.getElementById('costo_total');

            servicios.forEach(servicio => {
                servicio.addEventListener('change', function () {
                    let total = 0;
                    document.querySelectorAll('input[name="servicios[]"]:checked').forEach(checked => {
                        const precio = parseFloat(checked.nextElementSibling.textContent.split('$')[1]);
                        total += precio;
                    });
                    totalServicios.value = total;
                    costoTotal.textContent = `Total: $${total.toFixed(2)}`;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>