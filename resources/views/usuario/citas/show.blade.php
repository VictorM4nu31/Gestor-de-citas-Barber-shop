<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('appointments.scheduled_appointment') }}</h2>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-light p-8 rounded-lg shadow-lg w-full max-w-3xl">
            <h1 class="text-2xl font-bold mb-6 text-secondary">{{ __('appointments.scheduled_appointment') }}</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="border border-metal rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4 text-secondary">{{ __('appointments.appointment_details') }}</h2>
                    <p><span class="font-semibold">{{ __('appointments.date') }}:</span> {{ $cita->fecha }}</p>
                    <p><span class="font-semibold">{{ __('appointments.time') }}:</span> {{ $cita->hora }}</p>
                    <p><span class="font-semibold">{{ __('appointments.barber') }}:</span> {{ $cita->barbero->nombre_completo ?? __('appointments.not_available') }}</p>
                    <p><span class="font-semibold">{{ __('appointments.services') }}:</span> {{ $cita->servicios_nombres_texto }}</p>
                    <p><span class="font-semibold">{{ __('appointments.cost') }}:</span> ${{ $cita->costo }}</p>
                </div>
                <div class="border border-metal rounded-lg p-4 text-center">
                    <h2 class="text-lg font-semibold mb-4 text-secondary">{{ __('appointments.actions') }}</h2>
                    <a href="{{ route('citas.index') }}" class="w-full bg-light text-secondary border border-metal py-2 rounded-md mb-4 inline-block">{{ __('appointments.back_to_appointments') }}</a>
                    @if($cita->puedeSerConfirmada())
                        <form action="{{ route('citas.confirmar', $cita->id) }}" method="POST" class="mb-4" x-data x-on:confirmed-confirm-detail.window="$el.submit()">
                            @csrf
                            @method('PATCH')
                            <button type="button" @click="$dispatch('open-modal-confirm-detail', { trigger: $el })" class="w-full bg-success hover:bg-success/90 text-light py-2 rounded-md">Confirmar asistencia</button>
                        </form>
                        <x-ui.confirm-modal id="confirm-detail" title="Confirmar asistencia" message="¿Confirmas que asistirás a esta cita?" variant="neutral" />
                    @endif
                    <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" class="delete-form" x-data x-on:confirmed-cancel-cita.window="$el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="$dispatch('open-modal-cancel-cita', { trigger: $el })" class="w-full bg-danger hover:bg-secondary text-light py-2 rounded-md">{{ __('appointments.cancel_appointment') }}</button>
                    </form>
                    <x-ui.confirm-modal id="cancel-cita" title="Cancelar cita" message="¿Quieres cancelar esta cita?" />
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
    </script>
    @endpush
</x-app-layout>
