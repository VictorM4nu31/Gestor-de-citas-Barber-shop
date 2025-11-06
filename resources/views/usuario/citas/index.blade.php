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
                                            <h3 class="font-semibold text-lg">{{ $cita->nombre_completo }}</h3>
                                            <p class="text-muted">{{ $cita->fecha }} - {{ $cita->hora }}</p>
                                            <p class="text-muted">{{ __('appointments.barber') }}: {{ $cita->barbero->nombre_completo ?? __('appointments.not_assigned') }}</p>
                                            <p class="text-muted">{{ __('appointments.cost') }}: ${{ number_format($cita->costo, 2) }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('citas.show', $cita->id) }}"
                                               class="bg-info hover:bg-info/90 text-white font-bold py-2 px-4 rounded">
                                                {{ __('appointments.view_details') }}
                                            </a>
                                            <form method="POST" action="{{ route('citas.destroy', $cita->id) }}"
                                                  onsubmit="return confirm('{{ __('appointments.confirm_cancel') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="bg-danger hover:bg-danger/90 text-white font-bold py-2 px-4 rounded">
                                                    {{ __('appointments.cancel') }}
                                                </button>
                                            </form>
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
