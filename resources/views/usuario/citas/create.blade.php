<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="eyebrow text-brass">{{ __('appointments.schedule_appointment') }}</p>
                <h1 class="display-title text-2xl text-light">{{ __('appointments.schedule_appointment') }}</h1>
            </div>
            <span class="text-sm text-accent">{{ __('appointments.auto_filled_info') }}</span>
        </div>
    </x-slot>

    <main
        class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8"
        x-data="appointmentBooking(@js($rebookServiceIds ?? []), @js($rebookBarberoId ?? null))"
        x-init="init()"
        data-slots-url="{{ route('citas.available_slots') }}"
        data-csrf-token="{{ csrf_token() }}"
    >
        <nav class="mb-8 grid grid-cols-4 gap-2" aria-label="Progreso de la reserva">
            <template x-for="item in steps" :key="item.number">
                <div class="border-t-4 pt-3" :class="step >= item.number ? 'border-primary text-primary' : 'border-accent text-muted'">
                    <span class="block text-xs font-bold uppercase tracking-wider" x-text="`0${item.number}`"></span>
                    <span class="mt-1 block text-xs font-semibold sm:text-sm" x-text="item.label"></span>
                </div>
            </template>
        </nav>
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_20rem]">
            <section class="space-y-8">
                <div class="max-w-2xl">
                    <p class="eyebrow">01 / {{ __('appointments.services') }}</p>
                    <h2 class="display-title mt-2 text-4xl sm:text-5xl">Construye tu visita.</h2>
                    <p class="mt-4 max-w-xl text-lg text-muted">Elige lo que necesitas y te mostraremos cuánto dura, cuánto cuesta y cuándo puedes sentarte.</p>
                </div>

                @if(session('error'))
                    <x-ui.alert type="danger" dismissible>
                        {{ session('error') }}
                    </x-ui.alert>
                @endif

                @if($errors->any())
                    <x-ui.alert type="danger" dismissible>
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                <form action="{{ route('citas.store') }}" method="POST" @submit="submit($event)" novalidate>
                    @csrf

                    <div class="space-y-10">
                        <fieldset>
                            <legend class="sr-only">{{ __('appointments.services') }}</legend>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach($servicios as $servicio)
                                    <label class="group relative cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="servicios[]"
                                            value="{{ $servicio->id }}"
                                            class="peer sr-only"
                                            data-service-id="{{ $servicio->id }}"
                                            data-service-price="{{ $servicio->precio }}"
                                            data-service-duration="{{ $servicio->duracion }}"
                                            x-model="selectedServices"
                                            @change="refreshSlots()"
                                        >
                                        <span class="flex min-h-32 flex-col justify-between border border-accent bg-light p-5 transition duration-200 group-hover:border-copper peer-checked:border-primary peer-checked:bg-primary peer-checked:text-light">
                                            <span class="flex items-start justify-between gap-4">
                                                <span class="font-semibold">{{ $servicio->getTranslatedName() }}</span>
                                                <span class="text-sm font-bold text-primary peer-checked:text-brass">${{ number_format($servicio->precio, 2) }}</span>
                                            </span>
                                            <span class="mt-5 flex items-center justify-between gap-3 text-sm text-muted peer-checked:text-light/75">
                                                <span>{{ $servicio->duracion }} {{ __('services.minutes') }}</span>
                                                <span aria-hidden="true" class="text-lg">+</span>
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <div class="grid gap-6 border-t border-accent pt-8 md:grid-cols-2">
                            <div>
                                <label for="id_barbero" class="eyebrow">02 / {{ __('appointments.select_barber') }}</label>
                                <select id="id_barbero" name="id_barbero" required @change="refreshSlots()" class="mt-3 block w-full border-0 border-b-2 border-accent bg-transparent px-0 py-3 text-lg text-secondary focus:border-primary focus:ring-0">
                                    <option value="">{{ __('appointments.select_barber_placeholder') }}</option>
                                    @foreach($barberos as $barbero)
                                        <option value="{{ $barbero->id }}">{{ $barbero->nombre_completo }} · {{ $barbero->getTranslatedEspecialidad() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="fecha" class="eyebrow">03 / {{ __('appointments.date') }}</label>
                                <input id="fecha" name="fecha" type="date" required @change="refreshSlots()" class="mt-3 block w-full border-0 border-b-2 border-accent bg-transparent px-0 py-3 text-lg text-secondary focus:border-primary focus:ring-0">
                            </div>
                        </div>

                        <fieldset class="border-t border-accent pt-8">
                            <legend class="eyebrow">04 / {{ __('appointments.time') }}</legend>
                            <input type="hidden" name="hora" x-model="selectedTime" required>

                            <div x-show="loading" class="mt-4 grid grid-cols-3 gap-2 sm:grid-cols-5" role="status" aria-live="polite" aria-label="Cargando horarios">
                                <template x-for="skeleton in 10" :key="skeleton">
                                    <span class="h-12 animate-pulse border border-accent bg-accent/40"></span>
                                </template>
                            </div>

                            <div x-show="!loading && slots.length" class="mt-4 grid grid-cols-3 gap-2 sm:grid-cols-5" x-cloak>
                                <template x-for="slot in slots" :key="slot.value">
                                    <button type="button" @click="selectedTime = slot.value; updateStep()" :aria-pressed="selectedTime === slot.value" :class="selectedTime === slot.value ? 'bg-primary text-light border-primary' : 'bg-light text-secondary border-accent hover:border-primary'" class="border px-3 py-3 text-sm font-semibold transition">
                                        <span x-text="slot.label"></span>
                                    </button>
                                </template>
                            </div>

                            <p x-show="!loading && !slots.length && hasQuery" class="mt-4 border border-dashed border-accent p-5 text-muted" x-cloak>
                                No hay espacios para esa combinación. Prueba otra fecha o barbero.
                            </p>
                            <p x-show="!hasQuery" class="mt-4 text-muted">
                                Selecciona servicios, barbero y fecha para ver los espacios.
                            </p>
                        </fieldset>

                        <div class="grid gap-4 border-t border-accent pt-8 sm:grid-cols-2">
                            <x-form.input name="nombre_completo" type="text" :label="__('appointments.full_name')" :value="auth()->user()->name" required />
                            <x-form.input name="numero_telefono" type="tel" :label="__('appointments.phone_number')" :placeholder="__('appointments.phone_placeholder')" required />
                            <x-form.input name="correo_electronico" type="email" :label="__('appointments.email')" :value="auth()->user()->email" required />
                        </div>

                        <button type="submit" :disabled="submitting || !selectedTime" class="inline-flex w-full items-center justify-center gap-3 bg-primary px-6 py-4 text-base font-bold text-light transition hover:bg-secondary disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                            <span x-show="!submitting">{{ __('appointments.schedule_button') }}</span>
                            <span x-show="submitting" x-cloak>Guardando tu silla...</span>
                            <span aria-hidden="true">→</span>
                        </button>
                    </div>
                </form>
            </section>

            <aside class="h-fit lg:sticky lg:top-8">
                <div class="surface-panel p-6">
                    <p class="eyebrow">Tu visita</p>
                    <div class="mt-6 space-y-5">
                        <div class="flex items-end justify-between gap-4 border-b border-accent pb-5">
                            <span class="text-sm text-muted">Duración</span>
                            <strong class="text-2xl" x-text="totalDuration ? `${totalDuration} min` : '—'"></strong>
                        </div>
                        <div class="flex items-end justify-between gap-4 border-b border-accent pb-5">
                            <span class="text-sm text-muted">Total</span>
                            <strong class="text-2xl" x-text="totalPrice ? `$${totalPrice.toFixed(2)}` : '—'"></strong>
                        </div>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between gap-4"><dt class="text-muted">Barbero</dt><dd class="text-right font-semibold" x-text="barberName || 'Por elegir'"></dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-muted">Fecha</dt><dd class="text-right font-semibold" x-text="formattedDate || 'Por elegir'"></dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-muted">Hora</dt><dd class="text-right font-semibold" x-text="selectedTime || 'Por elegir'"></dd></div>
                        </dl>
                    </div>
                </div>
                <p class="mt-4 text-xs leading-relaxed text-muted">Puedes cambiar cualquier selección antes de confirmar. La disponibilidad se comprueba de nuevo al guardar.</p>
            </aside>
        </div>
    </main>

    @push('scripts')
    <script>
        function appointmentBooking(serviceIds = [], barberId = null) {
            return {
                selectedServices: serviceIds.map(String),
                selectedTime: '',
                slots: [],
                step: 1,
                steps: [
                    { number: 1, label: 'Servicios' },
                    { number: 2, label: 'Barbero y fecha' },
                    { number: 3, label: 'Horario' },
                    { number: 4, label: 'Confirmar' },
                ],
                loading: false,
                submitting: false,
                hasQuery: false,
                totalPrice: 0,
                totalDuration: 0,
                barberName: '',
                formattedDate: '',

                init() {
                    const date = document.getElementById('fecha');
                    const today = new Date();
                    const localDate = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().split('T')[0];
                    date.min = localDate;

                    if (barberId) {
                        document.getElementById('id_barbero').value = barberId;
                    }

                    if (this.selectedServices.length && barberId) {
                        this.$nextTick(() => this.refreshSlots());
                    }
                },

                updateSummary() {
                    this.totalPrice = 0;
                    this.totalDuration = 0;
                    document.querySelectorAll('[data-service-id]:checked').forEach((service) => {
                        this.totalPrice += Number(service.dataset.servicePrice);
                        this.totalDuration += Number(service.dataset.serviceDuration);
                    });

                    const barber = document.getElementById('id_barbero');
                    this.barberName = barber.value ? barber.options[barber.selectedIndex].text.split(' · ')[0] : '';
                    const date = document.getElementById('fecha').value;
                    this.formattedDate = date ? new Intl.DateTimeFormat(document.documentElement.lang, { dateStyle: 'medium' }).format(new Date(`${date}T12:00:00`)) : '';
                    this.updateStep();
                },

                updateStep() {
                    if (!this.selectedServices.length) {
                        this.step = 1;
                    } else if (!document.getElementById('id_barbero').value || !document.getElementById('fecha').value) {
                        this.step = 2;
                    } else if (!this.selectedTime) {
                        this.step = 3;
                    } else {
                        this.step = 4;
                    }
                },

                async refreshSlots() {
                    this.updateSummary();
                    this.selectedTime = '';
                    this.slots = [];
                    this.updateStep();
                    const barberId = document.getElementById('id_barbero').value;
                    const date = document.getElementById('fecha').value;

                    if (!barberId || !date || !this.selectedServices.length) {
                        this.hasQuery = false;
                        return;
                    }

                    this.hasQuery = true;
                    this.loading = true;

                    try {
                        const response = await fetch(document.querySelector('main[data-slots-url]').dataset.slotsUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('main[data-csrf-token]').dataset.csrfToken,
                            },
                            body: JSON.stringify({
                                barbero_id: barberId,
                                fecha: date,
                                servicios: this.selectedServices,
                            }),
                        });

                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || 'No fue posible consultar disponibilidad.');
                        }

                        this.slots = data.slots || [];
                    } catch (error) {
                        this.slots = [];
                    } finally {
                        this.loading = false;
                        this.updateStep();
                    }
                },

                submit(event) {
                    if (!this.selectedTime) {
                        event.preventDefault();
                        return;
                    }

                    this.submitting = true;
                    this.step = 4;
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
