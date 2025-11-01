<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('barberos.admin.titles.edit') }}</h2>
            <x-ui.button href="{{ route('admin.barberos.index') }}">
                {{ __('barberos.admin.buttons.back_to_index') }}
            </x-ui.button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.barberos.update', $barbero->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-form.input 
                            name="nombre_completo" 
                            type="text" 
                            label="{{ __('barberos.admin.labels.full_name') }}" 
                            :value="$barbero->nombre_completo"
                            required 
                        />
                        
                        <x-form.input 
                            name="email" 
                            type="email" 
                            label="{{ __('barberos.admin.labels.email') }}" 
                            :value="$barbero->email"
                            required 
                        />
                        
                        <x-form.input 
                            name="password" 
                            type="password" 
                            label="{{ __('barberos.admin.labels.password_keep_current') }}" 
                        />
                        
                        <x-form.input 
                            name="password_confirmation" 
                            type="password" 
                            label="{{ __('barberos.admin.labels.password_confirmation') }}" 
                        />
                        
                        <x-form.input 
                            name="telefono" 
                            type="text" 
                            label="{{ __('barberos.admin.labels.phone') }}" 
                            :value="$barbero->telefono"
                        />
                        
                        <x-form.input 
                            name="especialidad" 
                            type="text" 
                            label="{{ __('barberos.admin.labels.specialty') }}" 
                            :value="$barbero->especialidad"
                            required 
                        />
                        
                        <div class="md:col-span-2">
                            <x-form.textarea 
                                name="experiencia" 
                                label="{{ __('barberos.admin.labels.experience') }}" 
                                :value="$barbero->experiencia"
                                required 
                            />
                        </div>
                        
                        <div class="md:col-span-2 mb-4">
                            <x-form.label for="foto">{{ __('barberos.admin.labels.photo') }}</x-form.label>
                            <input type="file" id="foto" name="foto" class="w-full px-3 py-2 border border-accent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200">
                            <p class="mt-2 text-sm text-muted">{{ __('barberos.admin.messages.file_requirements') }}</p>
                            <!-- Mostrar foto -->
                            @if ($barbero->foto)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $barbero->foto) }}" alt="Foto de {{ $barbero->nombre_completo }}" class="w-32 h-32 object-cover rounded-md border border-accent">
                                </div>
                            @endif
                            <x-form.error field="foto" />
                        </div>
                    </div>
                    
                    <!-- Sección de servicios - fuera del grid para ocupar todo el ancho -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-secondary mb-3">{{ __('barberos.admin.labels.services_offered') }}</label>
                        <div class="space-y-3 max-h-64 overflow-y-auto border border-graymuted rounded-md p-4">
                            @if($servicios->count() > 0)
                                @foreach($servicios as $servicio)
                                    <div class="flex items-start space-x-3 p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                                        <input type="checkbox" 
                                               name="servicios[]" 
                                               value="{{ $servicio->id }}"
                                               id="servicio_{{ $servicio->id }}"
                                               @if(in_array($servicio->id, $serviciosAsignados ?? [])) checked @endif
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
                        <x-form.error field="servicios" />
                        <p class="mt-2 text-xs text-gray-500">
                            {{ __('barberos.admin.messages.services_assigned', ['count' => $barbero->servicios->count()]) }}
                        </p>
                    </div>
                    <div class="mt-6">
                        <x-ui.button type="submit">
                            {{ __('barberos.admin.buttons.save') }}
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>