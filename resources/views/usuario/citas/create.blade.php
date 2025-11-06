<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('appointments.schedule_appointment') }}</h2>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light p-8 rounded-lg shadow-lg w-full max-w-2xl flex flex-col md:flex-row">
            <div class="md:w-1/2 md:pr-4 mb-6 md:mb-0">
                <h1 id="form-title" class="text-2xl font-bold mb-6 text-secondary">{{ __('appointments.schedule_appointment') }}</h1>

                @if(session('error'))
                    <x-ui.alert type="danger" dismissible id="error-message" class="mb-4">
                        {{ session('error') }}
                    </x-ui.alert>
                @endif

                @if(session('success'))
                    <x-ui.alert type="success" dismissible id="success-message" class="mb-4">
                        {{ session('success') }}
                    </x-ui.alert>
                @endif

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                {{ __('appointments.auto_filled_info') }}
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('citas.store') }}" method="POST" aria-labelledby="form-title" novalidate>
                    @csrf
                    <div class="space-y-4">
                        <x-form.input
                            name="nombre_completo"
                            type="text"
                            :label="__('appointments.full_name')"
                            :value="auth()->user()->name"
                            required
                        />

                        <x-form.input
                            name="numero_telefono"
                            type="tel"
                            :label="__('appointments.phone_number')"
                            :placeholder="__('appointments.phone_placeholder')"
                            pattern="[0-9\-\+\s\(\)]+"
                            :title="__('appointments.phone_title')"
                            required
                        />

                        <x-form.input
                            name="correo_electronico"
                            type="email"
                            :label="__('appointments.email')"
                            :value="auth()->user()->email"
                            required
                        />

                        <fieldset class="border border-accent rounded-lg p-4">
                            <legend class="text-sm font-medium text-secondary px-2">{{ __('appointments.services') }} <span class="text-danger">*</span></legend>
                            <div class="space-y-2 mt-2">
                                @foreach($servicios as $servicio)
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               id="servicio_{{ $servicio->id }}"
                                               name="servicios[]"
                                               value="{{ $servicio->id }}"
                                               class="h-4 w-4 text-primary border-accent rounded focus:ring-primary focus:ring-2 mr-2"
                                               aria-describedby="servicio_{{ $servicio->id }}_description">
                                        <label for="servicio_{{ $servicio->id }}" class="text-sm text-secondary cursor-pointer">
                                            {{ $servicio->nombre }} - ${{ $servicio->precio }}
                                        </label>
                                        <span id="servicio_{{ $servicio->id }}_description" class="sr-only">
                                            Servicio {{ $servicio->nombre }} con precio de ${{ $servicio->precio }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" id="total_servicios" name="total_servicios" value="0">
                            <p id="costo_total" class="text-lg font-semibold mt-4 text-secondary" aria-live="polite">{{ __('appointments.total') }}: $0</p>
                        </fieldset>

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
                            :label="__('appointments.select_barber')"
                            :placeholder="__('appointments.select_barber_placeholder')"
                            :options="$barberoOptions"
                            aria-describedby="barbero-help"
                            required
                        />
                        <div id="barbero-help" class="sr-only">
                            Selecciona el barbero que prefieras para tu cita
                        </div>

                        <x-form.input
                            name="fecha"
                            type="date"
                            :label="__('appointments.date')"
                            aria-describedby="fecha-help"
                            required
                        />
                        <div id="fecha-help" class="sr-only">
                            Selecciona la fecha para tu cita. Debe ser hoy o una fecha futura.
                        </div>

                        <x-form.select
                            name="hora"
                            :label="__('appointments.time')"
                            :options="$horaOptions"
                            aria-describedby="hora-help"
                            required
                        />
                        <div id="hora-help" class="sr-only">
                            Selecciona la hora para tu cita entre las 9:00 AM y 8:00 PM
                        </div>

                        <div class="mt-4">
                            <x-ui.button type="submit" id="submit-button">
                                {{ __('appointments.schedule_button') }}
                            </x-ui.button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="md:w-1/2 md:pl-4">
                <h2 class="text-xl font-bold mb-4 text-secondary">{{ __('appointments.availability') }}</h2>
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
                costoTotal.textContent = `{{ __('appointments.total') }}: $${total.toFixed(2)}`;
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
                            resultHtml += '<p class="text-success">{{ __('appointments.completely_free') }}</p>';
                        } else {
                            const selectedBarbero = barberoSelect.options[barberoSelect.selectedIndex].text;

                            resultHtml += `<p class="text-lg font-bold mb-2">${selectedBarbero}</p><p class="text-muted mb-2">{{ __('appointments.already_scheduled') }}</p>`;
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

            // Resaltar campos pre-rellenados
            const preFilledFields = ['nombre_completo', 'correo_electronico'];
            preFilledFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field && field.value) {
                    field.classList.add('bg-blue-50', 'border-blue-300');
                    field.addEventListener('focus', function() {
                        this.classList.remove('bg-blue-50', 'border-blue-300');
                    });
                }
            });

            // Debug del formulario
            const form = document.querySelector('form');
            const submitButton = document.getElementById('submit-button');

            if (form && submitButton) {
                form.addEventListener('submit', function(e) {
                    console.log('Formulario enviado');

                    // Verificar campos requeridos
                    const requiredFields = ['nombre_completo', 'numero_telefono', 'correo_electronico', 'fecha', 'hora', 'id_barbero'];
                    let allValid = true;

                    requiredFields.forEach(fieldName => {
                        const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
                        if (!field || !field.value) {
                            console.log(`Campo faltante: ${fieldName}`);
                            allValid = false;
                        }
                    });

                    // Verificar servicios seleccionados
                    const serviciosSeleccionados = document.querySelectorAll('input[name="servicios[]"]:checked');
                    if (serviciosSeleccionados.length === 0) {
                        console.log('No hay servicios seleccionados');
                        allValid = false;
                    }

                    if (!allValid) {
                        console.log('Formulario inválido, deteniendo envío');
                        e.preventDefault();
                        alert('Por favor completa todos los campos requeridos y selecciona al menos un servicio.');
                    } else {
                        console.log('Formulario válido, enviando...');
                        submitButton.disabled = true;
                        submitButton.textContent = 'Enviando...';
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
