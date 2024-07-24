<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Agendar Cita</title>
    @vite('resources/css/app.css')

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
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
        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col md:flex-row">
            <!-- Formulario para agendar la cita -->
            <div class="md:w-1/2 md:pr-4 mb-6 md:mb-0">
                <h1 class="text-2xl font-bold mb-4">Agendar Cita</h1>

                @if(session('error'))
                    <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('citas.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <!-- Nombre Completo -->
                        <div>
                            <label for="nombre_completo" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <input type="text" id="nombre_completo" name="nombre_completo" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <!-- Número de Teléfono -->
                        <div>
                            <label for="numero_telefono" class="block text-sm font-medium text-gray-700">Número de Teléfono</label>
                            <input type="text" id="numero_telefono" name="numero_telefono" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <!-- Correo Electrónico -->
                        <div>
                            <label for="correo_electronico" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input type="email" id="correo_electronico" name="correo_electronico" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <!-- Servicios -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Servicios</label>
                            <div class="space-y-2">
                                @foreach($servicios as $servicio)
                                    <div>
                                        <input type="checkbox" id="servicio_{{ $servicio->id }}" name="servicios[]" value="{{ $servicio->id }}" class="mr-2">
                                        <label for="servicio_{{ $servicio->id }}" class="text-sm text-gray-600">{{ $servicio->nombre }} - ${{ $servicio->precio }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" id="total_servicios" name="total_servicios" value="0">
                            <p id="costo_total" class="text-lg font-semibold mt-4">Total: $0</p>
                        </div>

                        <!-- Barbero -->
                        <div>
                            <label for="id_barbero" class="block text-sm font-medium text-gray-700">Seleccionar Barbero</label>
                            <select id="id_barbero" name="id_barbero" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Seleccionar barbero</option>
                                @foreach($barberos as $barbero)
                                    <option value="{{ $barbero->id }}">{{ $barbero->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fecha -->
                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" id="fecha" name="fecha" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <!-- Hora -->
                        <div>
                            <label for="hora" class="block text-sm font-medium text-gray-700">Hora</label>
                            <select id="hora" name="hora" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                                @for($i = 9; $i <= 20; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Agendar Cita</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Apartado para visualizar las citas del barbero y día seleccionado -->
            <div class="md:w-1/2 md:pl-4">
                <h2 class="text-xl font-bold mb-4">Disponibilidad</h2>
                <div id="availability_result" class="bg-white p-6 rounded-lg shadow-lg">
                    <!-- Las citas serán cargadas aquí -->
                </div>
            </div>
        </div>

        <!-- Incluye el script para manejar la solicitud -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const barberoSelect = document.getElementById('id_barbero');
                const fechaInput = document.getElementById('fecha');
                const availabilityResult = document.getElementById('availability_result');
                
                // Establecer la fecha mínima como hoy
                const today = new Date().toISOString().split('T')[0];
                fechaInput.setAttribute('min', today);

                function fetchAvailability() {
                    const barberoId = barberoSelect.value;
                    const fecha = fechaInput.value;

                    if (barberoId && fecha) {
                        const formData = new FormData();
                        formData.append('barbero_id', barberoId);
                        formData.append('fecha', fecha);

                        fetch('{{ route('citas.check_availability') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            let resultHtml = '<h3 class="text-lg font-bold mb-2"></h3>';
                            if (data.length === 0) {
                                resultHtml += '<p class="text-green-500">Fecha totalmente libre.</p>';
                            } else {
                                // Obtener el nombre del barbero seleccionado
                                const selectedBarbero = barberoSelect.options[barberoSelect.selectedIndex].text;

                                resultHtml += `<p class="text-lg font-bold mb-2">${selectedBarbero}</p><p class="text-gray-600 mb-2">Ya tiene agendado los siguientes horarios:</p>`;
                                resultHtml += '<ul class="list-disc pl-5">';
                                data.forEach(cita => {
                                    resultHtml += `
                                        <li class="text-red-500">
                                            <i class="fas fa-times-circle mr-2 text-red-500"></i>
                                            ${cita.hora}
                                        </li>`;
                                });
                                resultHtml += '</ul>';
                            }
                            availabilityResult.innerHTML = resultHtml;

                            // Desplazarse hacia el área de disponibilidad
                            availabilityResult.scrollIntoView({ behavior: 'smooth' });
                        });
                    }
                }

                barberoSelect.addEventListener('change', fetchAvailability);
                fechaInput.addEventListener('change', fetchAvailability);
            });

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

                // Manejar el mensaje de error
                const errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    const inputs = document.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        input.addEventListener('focus', () => {
                            errorMessage.remove();
                        });
                    });
                }
            });
        </script>
    </main>
</body>
</html>
