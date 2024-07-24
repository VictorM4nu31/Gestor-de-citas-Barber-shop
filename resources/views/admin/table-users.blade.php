    <div class="relative overflow-x-auto shadow-md sm:rounded-lg" id="table-user">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.table-users-create') }}"
                class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>Agregar Empleado</span>
            </a>
        </div>
        <table class="min-w-full text-base text-left text-white">
            <thead class="text-sm uppercase bg-red-700 text-white">
                <tr>
                    <th scope="col" class="px-6 py-3">Nombre Completo</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Teléfono</th>
                    <th scope="col" class="px-6 py-3">Especialidad</th>
                    <th scope="col" class="px-6 py-3">Experiencia</th>
                    <th scope="col" class="px-6 py-3">Foto</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barberos as $barbero)
                    <tr class="bg-black border-b border-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $barbero->nombre_completo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $barbero->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $barbero->telefono }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $barbero->especialidad }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $barbero->experiencia }} años</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($barbero->foto)
                                <img class="w-10 h-10 rounded-full" src="{{ asset('storage/' . $barbero->foto) }}"
                                    alt="Imagen del barbero">
                            @else
                                Sin foto
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('barberos.edit', $barbero) }}"
                                class="font-medium text-blue-500 hover:underline mr-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                    </path>
                                </svg>
                            </a>
                            <form action="{{ route('barberos.destroy', $barbero->id) }}" method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-500 hover:underline"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este barbero?')">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
