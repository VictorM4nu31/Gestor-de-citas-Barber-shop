<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="eyebrow text-brass">Control de sillas</p>
                <h1 class="display-title text-2xl text-light">Agenda de citas</h1>
            </div>
            <a href="{{ route('admin.citas.create') }}" class="inline-flex items-center gap-2 bg-brass px-4 py-2 text-sm font-bold text-secondary transition hover:bg-light">
                Nueva cita <span aria-hidden="true">+</span>
            </a>
        </div>
    </x-slot>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8" x-data="appointmentAgenda()">
        @if(session('success'))
            <x-ui.alert type="success" dismissible class="mb-6">{{ session('success') }}</x-ui.alert>
        @endif

        <div class="mb-8 grid gap-4 border-b border-accent pb-6 md:grid-cols-[1fr_auto_auto] md:items-end">
            <div>
                <p class="eyebrow">Hoy / {{ now()->format('d M Y') }}</p>
                <p class="mt-2 text-muted">{{ $citas->count() }} citas en la agenda</p>
            </div>
            <label class="text-sm font-semibold">
                <span class="sr-only">Filtrar estado</span>
                <select x-model="status" class="border-0 border-b border-accent bg-transparent py-2 text-sm focus:border-primary focus:ring-0">
                    <option value="all">Todos los estados</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="atendida">Atendidas</option>
                    <option value="cancelada">Canceladas</option>
                </select>
            </label>
            <label class="text-sm font-semibold">
                <span class="sr-only">Buscar cliente</span>
                <input x-model="query" type="search" placeholder="Buscar cliente" class="w-full border-0 border-b border-accent bg-transparent py-2 text-sm placeholder:text-muted focus:border-primary focus:ring-0">
            </label>
        </div>

        @if($citas->isEmpty())
            <div class="border border-dashed border-accent bg-light p-12 text-center">
                <p class="display-title text-3xl">La agenda está limpia.</p>
                <p class="mt-3 text-muted">Cuando entre una reserva aparecerá aquí ordenada por hora.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($citas->sortBy(fn ($cita) => $cita->fecha.' '.$cita->hora) as $cita)
                    <article
                        class="agenda-item group grid gap-4 border border-accent bg-light p-5 transition hover:border-primary md:grid-cols-[8rem_1fr_auto] md:items-center"
                        data-status="{{ $cita->estado }}"
                        data-client="{{ strtolower($cita->nombre_completo) }}"
                        x-show="matches($el)"
                    >
                        <div class="border-l-4 border-primary pl-4">
                            <p class="text-2xl font-bold tracking-tight text-secondary">{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</p>
                            <p class="mt-1 text-xs font-bold uppercase tracking-wider text-muted">{{ \Carbon\Carbon::parse($cita->fecha)->translatedFormat('d M') }}</p>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="truncate text-lg font-bold text-secondary">{{ $cita->nombre_completo }}</h2>
                                <span class="inline-flex items-center gap-2 px-2 py-1 text-xs font-bold uppercase tracking-wider {{ $cita->estado === 'atendida' ? 'bg-success/10 text-success' : ($cita->estado === 'cancelada' ? 'bg-danger/10 text-danger' : 'bg-warning/10 text-warning') }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $cita->estado_texto }}
                                </span>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-muted">
                                <span>{{ $cita->barbero->nombre_completo ?? 'Sin barbero' }}</span>
                                <span>{{ $cita->servicios_nombres_texto ?: 'Sin servicios' }}</span>
                                <span>${{ number_format($cita->costo, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 md:justify-end">
                            <a href="{{ route('admin.citas.show', $cita) }}" class="border border-accent px-3 py-2 text-sm font-bold text-secondary transition hover:border-primary">Ver</a>
                            <a href="{{ route('admin.citas.edit', $cita) }}" class="bg-secondary px-3 py-2 text-sm font-bold text-light transition hover:bg-primary">Editar</a>
                            <form action="{{ route('admin.citas.destroy', $cita) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border border-danger px-3 py-2 text-sm font-bold text-danger transition hover:bg-danger hover:text-light">Eliminar</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
            <p x-show="!visibleCount" class="mt-8 border border-dashed border-accent p-8 text-center text-muted">No hay citas con esos filtros.</p>
        @endif
    </main>

    @push('scripts')
    <script>
        function appointmentAgenda() {
            return {
                status: 'all',
                query: '',
                get visibleCount() {
                    return [...document.querySelectorAll('.agenda-item')].filter((item) => this.matches(item)).length;
                },
                matches(item) {
                    const statusMatches = this.status === 'all' || item.dataset.status === this.status;
                    const queryMatches = !this.query || item.dataset.client.includes(this.query.toLowerCase());

                    return statusMatches && queryMatches;
                },
            };
        }

        document.querySelectorAll('.delete-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (! window.confirm('¿Eliminar esta cita?')) {
                    event.preventDefault();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
