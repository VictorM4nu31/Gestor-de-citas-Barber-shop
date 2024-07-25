<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servicio</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <header class="bg-black text-white sm:rounded-lg shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-xl font-bold text-white">Barbería</a>
            <nav class="space-x-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="bg-red-700 hover:bg-gray-700 text-white py-2 px-4 rounded-md">Volver a la Lista</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6">Editar Servicio</h1>
        <form action="{{ route('servicios.update', $servicio->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $servicio->nombre) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="descripcion" name="descripcion"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                        required>{{ old('descripcion', $servicio->descripcion) }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="duracion" class="block text-sm font-medium text-gray-700">Duración (minutos)</label>
                    <input type="number" id="duracion" name="duracion" min="1" step="1"
                        value="{{ old('duracion', $servicio->duracion) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="precio" class="block text-sm font-medium text-gray-700">Precio</label>
                    <input type="number" id="precio" name="precio" step="0.01"
                        value="{{ old('precio', $servicio->precio) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                        required>
                </div>
                <div class="mb-4">
                    <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                    <input type="file" id="foto" name="foto"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <p class="mt-2 text-sm text-gray-500">Tamaño máximo: 2MB. Formatos permitidos: jpeg, png, jpg.</p>
                    @if ($errors->has('foto'))
                        <p class="mt-2 text-sm text-red-500">{{ $errors->first('foto') }}</p>
                    @endif
                    @if ($servicio->foto)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $servicio->foto) }}" alt="Foto de {{ $servicio->nombre }}"
                                class="w-32 h-32 object-cover rounded">
                            <p class="text-sm text-gray-500">Foto actual</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Guardar
                    Cambios</button>
            </div>
        </form>
    </main>
</body>

</html>
