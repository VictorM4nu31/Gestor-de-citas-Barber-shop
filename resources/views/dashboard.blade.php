<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="eyebrow text-brass">Tu espacio</p>
                <h1 class="display-title text-2xl text-light">Hola, {{ auth()->user()->name }}</h1>
            </div>
            <a href="{{ route('citas.create') }}" class="bg-brass px-4 py-2 text-sm font-bold text-secondary transition hover:bg-light">Reservar cita <span aria-hidden="true">→</span></a>
        </div>
    </x-slot>

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[1.3fr_.7fr]">
            <section class="surface-panel overflow-hidden">
                <div class="bg-secondary p-6 text-light sm:p-8">
                    <p class="eyebrow text-brass">Próxima visita</p>
                    @if($proximaCita)
                        <div class="mt-8 flex flex-wrap items-end justify-between gap-6">
                            <div>
                                <p class="text-5xl font-bold tracking-tight">{{ \Carbon\Carbon::parse($proximaCita->fecha)->format('d') }}</p>
                                <p class="mt-1 text-sm uppercase tracking-[0.2em] text-light/60">{{ \Carbon\Carbon::parse($proximaCita->fecha)->translatedFormat('F Y') }}</p>
                            </div>
                            <p class="text-3xl font-semibold text-brass">{{ \Carbon\Carbon::parse($proximaCita->hora)->format('H:i') }}</p>
                        </div>
                    @else
                        <h2 class="display-title mt-8 text-4xl text-light">Todavía no tienes una visita reservada.</h2>
                        <p class="mt-4 max-w-md text-light/70">Elige un servicio y encuentra un espacio que encaje contigo.</p>
                    @endif
                </div>

                @if($proximaCita)
                    <div class="grid gap-4 p-6 sm:grid-cols-3 sm:p-8">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-muted">Barbero</p>
                            <p class="mt-2 font-semibold">{{ $proximaCita->barbero->nombre_completo ?? 'Por confirmar' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-muted">Servicios</p>
                            <p class="mt-2 font-semibold">{{ $proximaCita->servicios_nombres_texto ?: 'Por confirmar' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-muted">Total</p>
                            <p class="mt-2 font-semibold">${{ number_format($proximaCita->costo, 2) }}</p>
                        </div>
                        <div class="flex flex-wrap gap-3 sm:col-span-3">
                            <a href="{{ route('citas.show', $proximaCita) }}" class="border border-accent px-4 py-2 text-sm font-bold transition hover:border-primary">Ver detalles</a>
                            <a href="{{ route('citas.repeat', $proximaCita) }}" class="bg-primary px-4 py-2 text-sm font-bold text-light transition hover:bg-secondary">Repetir cita</a>
                        </div>
                    </div>
                @endif
            </section>

            <aside class="space-y-4">
                <div class="border border-accent bg-light p-6">
                    <p class="eyebrow">Resumen</p>
                    <p class="mt-4 text-4xl font-bold">{{ $totalCitas }}</p>
                    <p class="mt-1 text-muted">cita{{ $totalCitas === 1 ? '' : 's' }} próxima{{ $totalCitas === 1 ? '' : 's' }}</p>
                </div>
                <a href="{{ route('citas.index') }}" class="block border border-secondary bg-secondary p-6 text-light transition hover:bg-primary">
                    <span class="eyebrow text-brass">Historial</span>
                    <span class="mt-3 block text-xl font-semibold">Ver mis citas <span aria-hidden="true">→</span></span>
                </a>
            </aside>
        </div>
    </main>
</x-app-layout>
