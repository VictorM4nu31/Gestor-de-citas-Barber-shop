<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">Agendar Cita</h2>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light p-8 rounded-lg shadow-lg w-full max-w-2xl flex flex-col md:flex-row">
            <div class="md:w-1/2 md:pr-4 mb-6 md:mb-0">
                <h1 class="text-2xl font-bold mb-6 text-secondary">Agendar Cita</h1>

                @if(session('error'))
                    <x-ui.alert type="danger" dismissible id="error-message" class="mb-4">
                        {{ session('error') }}
                    </x-ui.alert>
                @endif

                <form action="{{ route('citas.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <x-form.input 
                            name="nombre_completo" 
                            type="text" 
                            label="Nombre Completo" 
                            required 
                        />

                        <x-form.input 
                            name="numero_telefono" 
                            type="text" 
                            label="Número de Teléfono" 
                            required 
                        />

                        <x-form.input 
                            name="correo_electronico" 
                            type="email" 
                            label="Correo Electrónico" 
                            required 
                        />

                        <div>
                            <x-form.label>Servicios</x-form.label>
                            <div class="space-y-2">
                                @foreach($servicios as $servicio)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="servicio_{{ $servicio->id }}" 
                                               name="servicios[]" 
                                               value="{{ $servicio->id }}" 
                                               class="h-4 w-4 text-primary border-accent rounded focus:ring-primary focus:ring-2 mr-2">
                                        <label for="servicio_{{ $servicio->id }}" class="text-sm text-secondary cursor-pointer">
                                            {{ $servicio->nombre }} - ${{ $servicio->precio }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" id="total_servicios" name="total_servicios" value="0">
                            <p id="costo_total" class="text-lg font-semibold mt-4 text-secondary">Total: $0</p>
                        </div>

                        @php
                            $barberoOptions = [];
                            foreach($barberos as $barbero) {
                                $barberoOptions[$barbero->id] = $barbero->nombre_completo;
                            }
                            
                            $horaOptions = [];
                            for($i = 9; $i <= 20; $i++) {
                                $hora = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                                $horaOptions[$hora] = $hora;
                            }
                        @endphp

                        <x-form.select 
                            name="id_barbero" 
                            label="Seleccionar Barbero" 
                            placeholder="Seleccionar barbero"
                            :options="$barberoOptions"
                            required 
                        />

                        <x-form.input 
                            name="fecha" 
                            type="date" 
                            label="Fecha" 
                            required 
                        />

                        <x-form.select 
                            name="hora" 
                            label="Hora" 
                            :options="$horaOptions"
                            required 
                        />

                        <div class="mt-4">
                            <x-ui.button type="submit">
                                Agendar Cita
                            </x-ui.button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="md:w-1/2 md:pl-4">
                <h2 class="text-xl font-bold mb-4 text-secondary">Disponibilidad</h2>
                <div id="availability_result" class="bg-light p-6 rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </main>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const barberoSelect = document.getElementById('id_barbero');
            const fechaInput = document.getElementById('fecha');
            const availabilityResult = document.getElementById('availability_result');
            const serviciosCheckboxes = document.querySelectorAll('input[name="servicios[]"]');
            const totalServicios = document.getElementById('total_servicios');
            const costoTotal = document.getElementById('costo_total');

            const today = new Date().toISOString().split('T')[0];
            fechaInput.setAttribute('min', today);

            function updateServiciosCheckboxes(serviciosDisponibles) {
                serviciosCheckboxes.forEach(checkbox => {
                    const servicioId = parseInt(checkbox.value);
                    const disponible = serviciosDisponibles.some(s => s.id === servicioId);
                    
                    checkbox.disabled = !disponible;
                    if (!disponible) {
                        checkbox.checked = false;
                    }
                    
                    const label = checkbox.nextElementSibling;
                    label.style.opacity = disponible ? '1' : '0.5';
                    label.style.textDecoration = disponible ? 'none' : 'line-through';
                });
                
                updateCostoTotal();
            }

            function resetServiciosCheckboxes() {
                serviciosCheckboxes.forEach(checkbox => {
                    checkbox.disabled = false;
                    checkbox.checked = false;
                    
                    const label = checkbox.nextElementSibling;
                    label.style.opacity = '1';
                    label.style.textDecoration = 'none';
                });
                
                updateCostoTotal();
            }

            function updateCostoTotal() {
                let total = 0;
                document.querySelectorAll('input[name="servicios[]"]:checked:not(:disabled)').forEach(checked => {
                    const precio = parseFloat(checked.nextElementSibling.textContent.split('$')[1]);
                    total += precio;
                });
                totalServicios.value = total;
                costoTotal.textContent = `Total: $${total.toFixed(2)}`;
            }

            barberoSelect.addEventListener('change', function() {
                const barberoId = this.value;
                
                if (barberoId) {
                    fetch(`/citas/servicios-barbero/${barberoId}`)
                        .then(response => response.json())
                        .then(servicios => {
                            updateServiciosCheckboxes(servicios);
                        })
                        .catch(error => {
                            console.error('Error al obtener servicios del barbero:', error);
                            resetServiciosCheckboxes();
                        });
                } else {
                    resetServiciosCheckboxes();
                }
                
                fetchAvailability();
            });

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
                            resultHtml += '<p class="text-success">Fecha totalmente libre.</p>';
                        } else {
                            const selectedBarbero = barberoSelect.options[barberoSelect.selectedIndex].text;

                            resultHtml += `<p class="text-lg font-bold mb-2">${selectedBarbero}</p><p class="text-muted mb-2">Ya tiene agendado los siguientes horarios:</p>`;
                            resultHtml += '<ul class="list-disc pl-5">';
                            data.forEach(cita => {
                                resultHtml += `
                                    <li class="text-danger">
                                        <i class="fas fa-times-circle mr-2 text-danger"></i>
                                        ${cita.hora} - ${cita.nombre_completo} (${cita.servicios})
                                    </li>`;
                            });
                            resultHtml += '</ul>';
                        }
                        availabilityResult.innerHTML = resultHtml;
                        availabilityResult.scrollIntoView({ behavior: 'smooth' });
                    });
                }
            }

            fechaInput.addEventListener('change', fetchAvailability);

            serviciosCheckboxes.forEach(servicio => {
                servicio.addEventListener('change', updateCostoTotal);
            });

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
    @endpush
</x-app-layout>