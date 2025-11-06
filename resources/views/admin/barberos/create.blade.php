<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('barberos.admin.titles.create') }}</h2>
            <x-ui.button href="{{ route('admin.barberos.index') }}">
                {{ __('barberos.admin.buttons.back_to_list') }}
            </x-ui.button>
        </div>
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <!-- Mostrar errores generales (p. ej. excepciones del controlador) -->
        @if($errors->has('error'))
            <div class="bg-danger/10 text-danger p-4 rounded mb-4">
                {{ $errors->first('error') }}
            </div>
        @endif
        <h1 class="text-3xl font-semibold mb-6 text-secondary">{{ __('barberos.admin.titles.create') }}</h1>
        <form action="{{ route('admin.barberos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <x-form.input
                name="nombre_completo"
                type="text"
                label="{{ __('barberos.admin.labels.full_name') }}"
                required
            />

            <x-form.input
                name="email"
                type="email"
                label="{{ __('barberos.admin.labels.email') }}"
                required
            />

            <x-form.input
                name="password"
                type="password"
                label="{{ __('barberos.admin.labels.password') }}"
                required
            />

            <x-form.input
                name="password_confirmation"
                type="password"
                label="{{ __('barberos.admin.labels.password_confirmation') }}"
                required
            />

            <x-form.input
                name="telefono"
                type="text"
                label="{{ __('barberos.admin.labels.phone') }}"
            />

            <x-form.input
                name="especialidad"
                type="text"
                label="{{ __('barberos.admin.labels.specialty') }}"
                required
            />

            <x-form.textarea
                name="experiencia"
                label="{{ __('barberos.admin.labels.experience') }}"
                required
            />

            <div class="mb-4">
                <x-form.label for="foto">{{ __('barberos.admin.labels.photo') }}</x-form.label>
                <input type="file" id="foto" name="foto" class="w-full px-3 py-2 border border-accent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200">
                <p class="mt-2 text-sm text-muted">{{ __('barberos.admin.messages.file_requirements') }}</p>
                <x-form.error field="foto" />
            </div>

            <!-- Sección de servicios -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-secondary mb-3">{{ __('barberos.admin.labels.services_offered') }}</label>
                <div class="space-y-3 max-h-64 overflow-y-auto border border-graymuted rounded-md p-4">
                    @if($servicios->count() > 0)
                        @foreach($servicios as $servicio)
                            <div class="flex items-start space-x-3 p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                                <input type="checkbox"
                                       name="servicios[]"
                                       value="{{ $servicio->id }}"
                                       id="servicio_{{ $servicio->id }}"
                                       class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <div class="flex-1">
                                    <label for="servicio_{{ $servicio->id }}" class="block text-sm font-medium text-gray-900 cursor-pointer">
                                        {{ $servicio->nombre }}
                                    </label>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <span class="font-medium">${{ number_format($servicio->precio, 0, ',', '.') }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $servicio->duracion }} min</span>
                                    </div>
                                    @if($servicio->descripcion)
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($servicio->descripcion, 80) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500 italic">{{ __('barberos.admin.messages.no_services_available') }}</p>
                    @endif
                </div>
                @if ($errors->has('servicios'))
                    <p class="mt-2 text-sm text-danger">{{ $errors->first('servicios') }}</p>
                @endif
                <p class="mt-2 text-xs text-gray-500">{{ __('barberos.admin.messages.services_help') }}</p>
            </div>
            <div class="mb-4">
                <x-ui.button submit="true">
                    {{ __('barberos.admin.buttons.save') }}
                </x-ui.button>
            </div>
        </form>
    </main>
</x-app-layout>
