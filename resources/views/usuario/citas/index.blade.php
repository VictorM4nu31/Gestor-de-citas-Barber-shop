<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('appointments.my_appointments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4">
                    <x-ui.alert type="success" dismissible>
                        {{ session('success') }}
                    </x-ui.alert>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4">
                    <x-ui.alert type="danger" dismissible>
                        {{ session('error') }}
                    </x-ui.alert>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-secondary">
                    @if($citas->count() > 0)
                        <div class="grid gap-4">
                            @foreach($citas as $cita)
                                <div class="border rounded-lg p-4 bg-surface">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-3">
                                                <h3 class="font-semibold text-lg">{{ $cita->nombre_completo }}</h3>
                                                <x-ui.status-badge :estado="$cita->estado" />
                                            </div>
                                            <p class="text-muted">{{ $cita->fecha }} - {{ $cita->hora }}</p>
                                            <p class="text-muted">{{ __('appointments.barber') }}: {{ $cita->barbero->nombre_completo ?? __('appointments.not_assigned') }}</p>
                                            <p class="text-muted">{{ __('appointments.cost') }}: ${{ number_format($cita->costo, 2) }}</p>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            @if($cita->puedeSerConfirmada())
                                                <form method="POST" action="{{ route('citas.confirmar', $cita->id) }}" class="inline" x-data x-on:confirmed-confirm-{{ $cita->id }}.window="$el.submit()">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" @click="$dispatch('open-modal-confirm-{{ $cita->id }}', { trigger: $el })"
                                                            class="bg-success hover:bg-success/90 text-white font-bold py-2 px-4 rounded">
                                                        Confirmar asistencia
                                                    </button>
                                                </form>
                                                <x-ui.confirm-modal
                                                    id="confirm-{{ $cita->id }}"
                                                    title="Confirmar asistencia"
                                                    message="¿Confirmas que asistirás el {{ $cita->fecha }} a las {{ $cita->hora }}?"
                                                    variant="neutral"
                                                />
                                            @endif
                                            <a href="{{ route('citas.repeat', $cita->id) }}"
                                               class="bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded">
                                                Repetir cita
                                            </a>
                                            <a href="{{ route('citas.show', $cita->id) }}"
                                               class="bg-info hover:bg-info/90 text-white font-bold py-2 px-4 rounded">
                                                {{ __('appointments.view_details') }}
                                            </a>
                                            <form method="POST" action="{{ route('citas.destroy', $cita->id) }}" x-data x-on:confirmed-cancel-cita-{{ $cita->id }}.window="$el.submit()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" @click="$dispatch('open-modal-cancel-cita-{{ $cita->id }}', { trigger: $el })"
                                                        class="bg-danger hover:bg-danger/90 text-white font-bold py-2 px-4 rounded">
                                                    {{ __('appointments.cancel') }}
                                                </button>
                                            </form>
                                            <x-ui.confirm-modal
                                                id="cancel-cita-{{ $cita->id }}"
                                                title="Cancelar cita"
                                                message="¿Quieres cancelar la cita de {{ $cita->fecha }} a las {{ $cita->hora }}?"
                                            />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-muted mb-4">{{ __('appointments.no_appointments_message') }}</p>
                            <a href="{{ route('citas.create') }}"
                               class="bg-info hover:bg-info/90 text-white font-bold py-2 px-4 rounded">
                                {{ __('appointments.schedule_new_appointment') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
